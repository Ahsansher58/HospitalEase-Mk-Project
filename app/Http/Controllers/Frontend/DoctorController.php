<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSocialMedia;
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
    // Fetch medicines data from the database (example)
    $appointments = DoctorAppointment::where('doctor_id', Auth::id())->orderBy('id', 'desc')->get();

    // Format the data to match the structure for DataTable
    $data = $appointments->map(function ($appointment) {
      return [
        $appointment->id,
        $appointment->day,
        $appointment->from_time,
        $appointment->to_time,
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

    // Create a new record with the authenticated user's ID
    DoctorAppointment::create([
      'user_id'   => Auth::id(),
      'day'       => $validatedData['day'],
      'from_time' => $validatedData['from_time'],
      'to_time'   => $validatedData['to_time'],
    ]);

    // Redirect back with success message
    return redirect()->route('doctor.social-media')->with('success', 'Appointment added successfully');
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

    if ($validated) {
      $appointment->update($validated);

      return response()->json(['message' => 'Appointment successfully']);
    }

    return response()->json(['errors' => 'All fields are required'], 422);
  }

}
