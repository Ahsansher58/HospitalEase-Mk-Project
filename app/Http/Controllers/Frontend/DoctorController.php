<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSocialMedia;
use App\Models\DoctorAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
  // Store Doctor
  public function store(Request $request)
  {
    $validator = $request->validate([
      'name' => 'required|string|max:255',
      'degree' => 'required|string|max:255', // Added validation for degree
      'experience' => 'required|string|max:255',
      'specialisation' => 'required|array',
      'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
      'short_intro' => 'nullable|string|max:250',
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
      'name' => 'required|string|max:255',
      'degree' => 'required|string|max:255', // Added validation for degree
      'experience' => 'required|string|max:255',
      'specialisation' => 'required|array',
      'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
      'short_intro' => 'nullable|string|max:250',
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
      'name' => 'required|string|max:255',
      'icon' => 'required|string|max:255',
      'link' => 'required|string|max:255',
    ]);

    // Create a new record with the authenticated user's ID
    DoctorSocialMedia::create([
      'user_id' => Auth::id(),
      'name'    => $validatedData['name'],
      'icon'    => $validatedData['icon'],
      'link'    => $validatedData['link'],
    ]);

    // Redirect back with success message
    return redirect()->route('doctor.social-media')->with('success', 'Social Media added successfully');
  }

  

  public function getSocialMedia()
  {
    // Fetch medicines data from the database (example)
    $socialMedia = DoctorSocialMedia::where('user_id', Auth::id())->orderBy('id', 'desc')->get();

    // Format the data to match the structure for DataTable
    $data = $socialMedia->map(function ($media) {
      return [
        $media->id,
        $media->name,
        $media->icon,
        $media->link,
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
      'name' => 'required|string|max:255',
      'icon' => 'required|string|max:255',
      'link' => 'required|string|max:255',
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
    return view('frontend.content.doctors.appointments', compact('user'));
  }

  public function getAppointments()
  {     
    $user = Auth::user();
    // Fetch medicines data from the database (example)
    $appointments = DoctorAppointment::leftJoin('doctors' , 'doctors.id' , 'doctor_appointments.doctor_id')
    ->leftJoin('users' , 'users.id' , 'doctors.user_id')
    ->select('doctor_appointments.*' , 'doctors.user_id')
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
        $finalFromTime,
        $finalToTime,
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
          'day'       => 'required|string|max:255',
          'from_time' => 'required|string|max:255',
          'to_time'   => 'required|string|max:255',
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
              'from_time' => $fromTime,
              'to_time'   => $toTime,
          ]);
      } else {
          // Create a new appointment
          DoctorAppointment::create([
              'doctor_id' => $doctor->id,
              'day'       => $validatedData['day'],
              'from_time' => $fromTime,
              'to_time'   => $toTime,
          ]);
      }

      // Redirect back with success message
      return redirect()->route('doctor.appointments')->with('success', 'Appointment added successfully');
  }


  public function editGetAppointment($id)
  {
    $appointment = DoctorAppointment::find($id);
    return response()->json($appointment);
  }

  public function updateAppointment(Request $request, $id)
  {
    $appointment = DoctorAppointment::find($id);

    $validated = $request->validate([
      'day'       => 'required|string|max:255',
      'from_time' => 'required|string|max:255',
      'to_time'   => 'required|string|max:255',
    ]);


    $fromTime = $request->from_time ? date('H:i:s',strtotime($request->from_time)) : null;
    $toTime   = $request->to_time ? date('H:i:s',strtotime($request->to_time)) : null;

    $validatedData['from_time'] = $fromTime;
    $validatedData['to_time']   = $toTime;


    if ($validated) {
      $appointment->update($validated);

      return response()->json(['message' => 'Appointment updated successfully']);
    }

    return response()->json(['errors' => 'All fields are required'], 422);
  }

  public function deleteAppointment($id)
  {
    $appointment = DoctorAppointment::find($id);

    if (!$appointment) {
      return response()->json(['message' => 'Doctor Appointment not found.'], 404);
    }

    $appointment->delete();

    return response()->json(['message' => 'Doctor Appointment deleted successfully']);
  }

}
