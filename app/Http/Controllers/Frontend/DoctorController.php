<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSocialMedia;
use App\Models\DoctorAppointment;
use App\Models\DoctorAwardAchievement;
use App\Models\BusinessCategory;
use App\Models\HospitalsProfile;
use App\Models\LocationsMaster;
use App\Models\DoctorEducationalQualification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
  // Store Doctor
  public function store(Request $request)
  {
    $validator = $request->validate([
      'name'           => 'required|string|max:255',
      'degree'         => 'required|string|max:255', // Added validation for degree
      'experience'     => 'required|string|max:255',
      'specialisation' => 'required|array',
      'profile_image'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
      'short_intro'    => 'nullable|string|max:250',
    ]);

    try {
      $data = $request->all();
      $data['hospital_id'] = Auth::id(); // Assign the hospital_id
      if ($request->hasFile('profile_image')) {
        $data['profile_image'] = $request->file('profile_image')->store('doctors', 'public');
      }

      $doctor = Doctor::create($data);

      return response()->json([
        'success' => true,
        'message' => 'Doctor added successfully.',
        'doctor' => $doctor,
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to add doctor.',
        'error' => $e->getMessage(),
      ]);
    }
  }

  // Update Doctor
  public function update(Request $request, $id)
  {
    $validator = $request->validate([
      'name'           => 'required|string|max:255',
      'degree'         => 'required|string|max:255', // Added validation for degree
      'experience'     => 'required|string|max:255',
      'specialisation' => 'required|array',
      'profile_image'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
      'short_intro'    => 'nullable|string|max:250',
    ]);

    try {
      $doctor = Doctor::findOrFail($id);

      $data = $request->all();
      if ($request->hasFile('profile_image')) {
        // Delete old image
        if ($doctor->profile_image) {
          Storage::disk('public')->delete($doctor->profile_image);
        }
        $data['profile_image'] = $request->file('profile_image')->store('doctors', 'public');
      }

      $doctor->update($data);

      return response()->json([
        'success' => true,
        'message' => 'Doctor updated successfully.',
        'doctor' => $doctor,
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update doctor.',
        'error' => $e->getMessage(),
      ]);
    }
  }

  // Delete Doctor
  public function destroy($id)
  {
    try {
      $doctor = Doctor::findOrFail($id);

      if ($doctor->profile_image) {
        Storage::disk('public')->delete($doctor->profile_image);
      }

      $doctor->delete();

      return response()->json([
        'success' => true,
        'message' => 'Doctor deleted successfully.',
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to delete doctor.',
        'error' => $e->getMessage(),
      ]);
    }
  }

  public function social_media()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();
    return view('frontend.content.doctors.social_media', compact('user'));
  }


  public function social_media_store(Request $request)
  {
    // Validate the fields
    $validatedData = $request->validate([
      'youtube_link'   => 'nullable|string|max:255',
      'facebook_link'  => 'nullable|string|max:255',
      'linkdin_link'   => 'nullable|string|max:255',
      'instagram_link' => 'nullable|string|max:255',
    ]);

    // Create a new record with the authenticated user's ID
    $user   = Auth::user();
    $doctor = Doctor::where('user_id' , $user->id)->first();
    DoctorSocialMedia::create([
      'doctor_id'      => $doctor->id,
      'youtube_link'   => $validatedData['youtube_link'],
      'facebook_link'  => $validatedData['facebook_link'],
      'linkdin_link'   => $validatedData['linkdin_link'],
      'instagram_link' => $validatedData['instagram_link'],
    ]);

    // Redirect back with success message
    return redirect()->route('doctor.social-media')->with('success', 'Social Media added successfully');
  }

  

  public function getSocialMedia()
  {
    // Fetch medicines data from the database (example)
    $user        = Auth::user();
    $doctor      = Doctor::where('user_id' , $user->id)->first();
    $socialMedia = DoctorSocialMedia::where('doctor_id', $doctor->id)->orderBy('id', 'desc')->get();

    $data = $socialMedia->map(function ($media) {
      return [
        $media->id,
        $media->youtube_link,
        $media->facebook_link,
        $media->linkdin_link,
        $media->instagram_link,
        "<button class='btn p-0 border-0' onclick='edit_popup(" . $media->id . ")'><img src='" . asset('assets/frontend/images/icons/edit.svg') . "' /></button>
             <button class='btn p-0 border-0' onclick='delete_social_media(" . $media->id . ")'><img src='" . asset('assets/frontend/images/icons/delete.svg') . "' /></button>"
      ];
          
    });

    // Return the data as JSON
    return response()->json(['data' => $data]);
  }

  public function updateSocialMedia(Request $request, $id)
  {
    $socialMedia = DoctorSocialMedia::find($id);

    $validated = $request->validate([
      'youtube_link'   => 'nullable|string|max:255',
      'facebook_link'  => 'nullable|string|max:255',
      'linkdin_link'   => 'nullable|string|max:255',
      'instagram_link' => 'nullable|string|max:255',
    ]);

    if ($validated) {
      $socialMedia->update($validated);

      return response()->json(['message' => 'Social Media successfully']);
    }

    return response()->json(['errors' => 'All fields are required'], 422);
  }

  public function editGetSocialMedia($id)
  {
    $socialMedia = DoctorSocialMedia::find($id);
    return response()->json($socialMedia);
  }

  public function deleteSocialMedia($id)
  {
    $socialMedia = DoctorSocialMedia::find($id);

    if (!$socialMedia) {
      return response()->json(['message' => 'Social Media not found.'], 404);
    }

    $socialMedia->delete();

    return response()->json(['message' => 'Social Media deleted successfully']);
  }

  public function deleteAwardAchievement($id)
  {
    $awardAchievement = DoctorAwardAchievement::find($id);

    if (!$awardAchievement) {
      return response()->json([
        'success' => false,
        'message' => 'Award Achievement not found.']
      , 404);
    }

    $awardAchievement->delete();

    return response()->json([
      'success' => true,
      'message' => 'Award Achievement deleted successfully'
    ]);
  }

  public function deleteEducationalQualification($id)
  {
    $educationalQualification = DoctorEducationalQualification::find($id);

    if (!$educationalQualification) {
      return response()->json([
        'success' => false,
        'message' => 'Educational Qualification not found.']
      , 404);
    }

    $educationalQualification->delete();

    return response()->json([
      'success' => true,
      'message' => 'Educational Qualification deleted successfully'
    ]);
  }

  public function doctor_logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('doctors.login')->with('success', 'Logout successful!');
  }


  public function appointments()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();
    $categories            = BusinessCategory::orderBy('order_no')->get();
    $businessCategories    = $categories->where('is_sub_category', false);
    $businessSubCategories = $categories->where('is_sub_category', true);
    $locationMaster        = LocationsMaster::all();
    $uniqueLocalities      = LocationsMaster::select('locality')->distinct()->pluck('locality');
    $uniqueCities          = LocationsMaster::select('city')->distinct()->pluck('city');
    $uniqueStates          = LocationsMaster::select('state')->distinct()->pluck('state');
    $uniqueCountries       = LocationsMaster::select('country')->distinct()->pluck('country');
    return view('frontend.content.doctors.appointments', compact(
      'user',
      'categories',
      'businessCategories',
      'businessSubCategories',
      'locationMaster' ,
      'uniqueLocalities',
      'uniqueCities'  ,
      'uniqueStates' ,
      'uniqueCountries',
    ));
  }

  public function searchHospitals(Request $request)
  {
    if ($request->ajax()) {
        $term     = $request->input('search_text');
        $country  = $request->input('country');
        $state    = $request->input('state');
        $city     = $request->input('city');
        $locality = $request->input('locality');

        $query = HospitalsProfile::query();

        if (!empty($term)) {
            $query->where('hospital_name', 'LIKE', '%' . $term . '%');
        }

        if (!empty($country)) {
            $query->where('country', $country);
        }

        if (!empty($state)) {
            $query->where('state', $state);
        }

        if (!empty($city)) {
            $query->where('city', $city);
        }

        if (!empty($locality)) {
            $query->where('locality', $locality);
        }

        $results = $query->limit(10)->get();

        return response()->json([
            'result' => $results->map(function ($hospital) {
                return [
                    'id'       => $hospital->id,
                    'name'     => $hospital->hospital_name,
                    'phone_no' => $hospital->phone,
                    'city'     => $hospital->city,
                ];
            }),
        ]);
    }

    // Optional fallback in case request is not AJAX
    return response()->json(['error' => 'Invalid request'], 400);
  }

  public function getAppointments()
  {     
    $user = Auth::user();
    // Fetch medicines data from the database (example)
    $appointments = DoctorAppointment::select(
      'doctor_appointments.*' ,
      'doctors.user_id' ,
      'user_profile.address',
      'hospitals_profile.hospital_name',
    )
    ->leftJoin('hospitals_profile','hospitals_profile.id' , 'doctor_appointments.hospital_id')
    ->leftJoin('users' , 'users.id' , 'hospitals_profile.hospital_id')
    ->leftJoin('user_profile' , 'users.id' , 'user_profile.user_id')
    ->leftJoin('doctors' , 'doctors.id' , 'doctor_appointments.doctor_id')
    ->where('doctors.user_id', $user->id)
    ->orderBy('doctor_appointments.id', 'asc')
    ->get();       


    // Format the data to match the structure for DataTable
    $data = $appointments->map(function ($appointment) {
        $from_time     = $appointment['from_time'];
        $finalFromTime = date('h:i a', strtotime($from_time)); 
        $to_time       = $appointment['to_time'];
        $finalToTime   = date('h:i a', strtotime($to_time));

        return [
            $appointment->id,
            $appointment->day,
            $finalFromTime . " - " . $finalToTime,
            ($appointment->hospital_name ?? '') . "<br>" . 
            ($appointment->country ?? '') . "<br>" . 
            ($appointment->state ?? '') . "<br>" . 
            ($appointment->city ?? '') . "<br>" . 
            ($appointment->locality ?? '') . "<br>" . 
            ($appointment->address ?? ''),
            "<button class='btn p-0 border-0' onclick='edit_popup(" . $appointment->id . ")'><img src='" . asset('assets/frontend/images/icons/edit.svg') . "' /></button>
             <button class='btn p-0 border-0' onclick='delete_appointment(" . $appointment->id . ")'><img src='" . asset('assets/frontend/images/icons/delete.svg') . "' /></button>"
        ];
    });


    // Return the data as JSON
    return response()->json(['data' => $data]);
  }

  public function appointment_store(Request $request)
  {
      // Validate the fields
      $validatedData = $request->validate([
          'day'         => 'required|string|max:255',
          'from_time'   => 'required|string|max:255',
          'to_time'     => 'required|string|max:255',
          'hospital_id' => 'required',
          'country'     => 'required',
          'state'       => 'required',
          'city'        => 'required',
          'locality'    => 'required',
      ]);

      // Convert time to H:i:s format
      $fromTime = date('H:i:s', strtotime($request->from_time));
      $toTime   = date('H:i:s', strtotime($request->to_time));

      // Get the current doctor's record
      $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

      // Check if an appointment already exists for this doctor on this day
      $existingAppointment = DoctorAppointment::where('doctor_id', $doctor->id)
      ->where('day', $validatedData['day'])
      ->first();

      if ($existingAppointment) {
          $existingAppointment->update([
              'from_time'   => $fromTime,
              'to_time'     => $toTime,
              'hospital_id' => $request->hospital_id,
              'country'     => $request->country,
              'state'       => $request->state,
              'city'        => $request->city,
              'locality'    => $request->locality,
          ]);
      } else {
          // Create a new appointment
          DoctorAppointment::create([
              'doctor_id'   => $doctor->id,
              'day'         => $validatedData['day'],
              'from_time'   => $fromTime,
              'to_time'     => $toTime,
              'hospital_id' => $request->hospital_id,
              'country'     => $request->country,
              'state'       => $request->state,
              'city'        => $request->city,
              'locality'    => $request->locality,
          ]);
      }

      // Redirect back with success message
      return redirect()->route('doctor.appointments')->with('success', 'Timing added successfully');
  }

   public function AwardAchievementsStore(Request $request)
  {
      // Validate the fields
      $validatedData = $request->validate([
        'award_name'        => 'required|string|max:255',
        'awarded_year'      => 'required|numeric',
        'award_certificate' => 'required',
      ]);

      $imageName = null;

      if ($request->hasFile('award_certificate')) {
        // foreach ($request->award_certificate as $key => $image) {
            $image = $request->file('award_certificate');
            $imageName = time() . '.' . $image->getClientOriginalExtension(); 

            if (!file_exists(public_path('uploads/doctors'))) {
                mkdir(public_path('uploads/doctors'), 0777, true);
            }

            $image->move(public_path('uploads/doctors'), $imageName); 
        // }
      }

    // Update user profile
    $user          = Auth::user();
    $doctor        = Doctor::where('user_id', $user->id)->first();
    $doctorProfile = DoctorAwardAchievement::where('doctor_id',$doctor->id)->first();

      DoctorAwardAchievement::create([
        'doctor_id'         => $doctor->id,
        'award_name'        => $request->award_name,
        'awarded_year'      => $request->awarded_year,
        'award_certificate' => $imageName,
      ]);

      // Redirect back with success message
      return redirect()->route('doctor.award-achievements')->with('success', 'Award & Achievements added successfully');
  }


  public function editGetAppointment($id)
  {
    $appointment = DoctorAppointment::leftJoin('hospitals_profile' , 'hospitals_profile.id' , 'doctor_appointments.hospital_id')
    ->select('doctor_appointments.*' , 'hospitals_profile.hospital_name')
    ->find($id);
        
    return response()->json($appointment);
  }

  public function updateAppointment(Request $request, $id)
  {
    $appointment = DoctorAppointment::find($id);

    $validated = $request->validate([
      'day'         => 'required|string|max:255',
      'from_time'   => 'required|string|max:255',
      'to_time'     => 'required|string|max:255',
      'hospital_id' => 'required',
      'country'     => 'required',
      'state'       => 'required',
      'city'        => 'required',
      'locality'    => 'required',
    ]);


    $fromTime = $request->from_time ? date('H:i:s',strtotime($request->from_time)) : null;
    $toTime   = $request->to_time ? date('H:i:s',strtotime($request->to_time)) : null;

    $validatedData['from_time'] = $fromTime;
    $validatedData['to_time']   = $toTime;


    if ($validated) {
      $appointment->update($validated);

      return response()->json(['message' => 'Timing updated successfully']);
    }

    return response()->json(['errors' => 'All fields are required'], 422);
  }

  public function deleteAppointment($id)
  {
    $appointment = DoctorAppointment::find($id);

    if (!$appointment) {
      return response()->json(['message' => 'Doctor Timing not found.'], 404);
    } else{
      $appointment->delete();

      return response()->json(['message' => 'Doctor Timing deleted successfully']);
    }
  }
}
