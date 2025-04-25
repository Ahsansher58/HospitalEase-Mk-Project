<?php

namespace App\Http\Controllers\hospital;

use App\Http\Controllers\Controller;
use App\Models\HospitalsProfile;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AddHospital extends Controller
{
  public function index()
  {
    if (!Auth::check()) {
      return redirect('/');
    }

    $user = Auth::user();
    $main_cats = MainCategory::orderBy('sort_order', 'asc')->get();
    $currentUserHospitalId = $user->id;

    $hospitalProfileData = HospitalsProfile::where('hospital_id', $currentUserHospitalId)
      ->get()
      ->map(function ($profile) {
        $profileArray = $profile->toArray();
        $otherData = $profileArray['other_data'] ?? null;

        if (is_string($otherData)) {
          $otherData = json_decode($otherData, true) ?? [];
        } elseif (!is_array($otherData)) {
          $otherData = [];
        }

        unset($profileArray['other_data']);

        return array_merge($profileArray, $otherData);
      })
      ->toArray();

    if (empty($hospitalProfileData)) {
      $hospitalProfileData = [];
    }

    $main_sub_cats = SubCategory::orderBy('sort_order', 'asc')->get()->groupBy('main_category_id');
    return view('content.hospital.add-hospital', compact('user', 'main_cats', 'main_sub_cats', 'hospitalProfileData'));
  }
  public function edit($hospital_id)
  {
    if (!Auth::check()) {
      return redirect('/');
    }

    $main_cats = MainCategory::orderBy('sort_order', 'asc')->get();
    $hospital_login = User::where('id', $hospital_id)->first();

    $hospitalProfileData = HospitalsProfile::where('hospital_id', $hospital_id)
      ->get()
      ->map(function ($profile) {
        $profileArray = $profile->toArray();
        $otherData = $profileArray['other_data'] ?? null;

        if (is_string($otherData)) {
          $otherData = json_decode($otherData, true) ?? [];
        } elseif (!is_array($otherData)) {
          $otherData = [];
        }

        unset($profileArray['other_data']);

        return array_merge($profileArray, $otherData);
      })
      ->toArray();

    if (empty($hospitalProfileData)) {
      $hospitalProfileData = [];
    }

    $main_sub_cats = SubCategory::orderBy('sort_order', 'asc')->get()->groupBy('main_category_id');
    return view('content.hospital.edit-hospital', compact('hospital_login', 'main_cats', 'main_sub_cats', 'hospitalProfileData', 'hospital_id'));
  }
  public function creatHospital(Request $request)
  {
    // Initialize dynamic rules for additional fields
    $rules = [];

    // Validate required fields if 'required_casees' is provided and it's an array
    if ($request->has('required_casees') && is_array($request->required_casees)) {
      foreach ($request->required_casees as $field) {
        if (!empty($field)) {
          $rules[$field] = 'required';
        }
      }
    }

    // Main validation rules for essential fields
    $validated = $request->validate(array_merge([
      'email' => 'required|email|unique:users,email',
      'mobile' => 'required|numeric|unique:users,mobile',
      'password' => 'required|string|min:8|confirmed',
    ], $rules));


    // Create the user
    $user = User::create([
      'email' => $validated['email'],
      'name' => ucfirst(explode('@', $validated['email'])[0]),
      'mobile' => $validated['mobile'],
      'password' => Hash::make($validated['password']),
      'type' => 2
    ]);

    $this->hospitalProfileUpdate($request, $user->id);
    return redirect()->route('manage-hospital')->with('success', 'Hospital Created successfully.');
  }
  public function updateHospital(Request $request, $hospital_id)
  {
    $rules = [];
    // Validate required fields if 'required_casees' is provided and it's an array
    if ($request->has('required_casees') && is_array($request->required_casees)) {
      foreach ($request->required_casees as $field) {
        if (!empty($field)) {
          $rules[$field] = 'required';
        }
      }
    }

    $user = User::where('id', $hospital_id)->first();
    if ($request->has('email') && $user->email !== $request->input('email')) {
      $rules['email'] = 'required|email|unique:users,email';
    } else {
      $rules['email'] = 'required|email';
    }
    if ($request->has('mobile') && $user->mobile !== $request->input('mobile')) {
      $rules['mobile'] = 'required|numeric|unique:users,mobile';
    } else {
      $rules['mobile'] = 'required|numeric';
    }
    if ($request->has('password') && !empty($request->input('password'))) {
      $rules['password'] = 'required|string|min:8|confirmed';
    }
    $validated = $request->validate($rules);




    if ($user) {
      $updateData = [];
      if (!empty($validated['email']) && $user->email !== $validated['email']) {
        $updateData['email'] = $validated['email'];
      }

      if (!empty($validated['mobile']) && $user->mobile !== $validated['mobile']) {
        $updateData['mobile'] = $validated['mobile'];
      }

      if (!empty($validated['password'])) {
        $updateData['password'] = Hash::make($validated['password']);
      }

      if (!empty($updateData)) {
        $user->update($updateData);
      }
    }


    $this->hospitalProfileUpdate($request, $hospital_id);
    return redirect()->route('manage-hospital')->with('success', 'Hospital Updated successfully.');
  }
  public function hospitalProfileUpdate($request, $hospital_id)
  {
    // Handle image removal
    if ($request->has('removed_images') && is_array($request->removed_images)) {
      foreach ($request->removed_images as $imagePath) {
        $filePath = public_path('storage/' . $imagePath);
        if (file_exists($filePath)) {
          unlink($filePath); // Delete the file from storage
        }
      }
    }

    // Collect other data and remove unnecessary fields
    $other_data = $request->except([
      '_token',
      'removed_images',
      'mandatory_hospital_name',
      'mandatory_hospital_image',
      'mandatory_description',
      'mandatory_specialization',
      'mandatory_medical_system',
      'mandatory_phone',
      'mandatory_emergency_contact',
      'mandatory_email',
      'mandatory_website',
      'mandatory_location',
      'mandatory_facilities',
    ]);
    if ($request->has('mandatory_specialization')) {
      $slug = Str::slug($request->hospital_name);
      $originalSlug = $slug;
      $counter = 1;
      while (HospitalsProfile::where('hospital_slug', $slug)->exists()) {
        $slug = "{$originalSlug}-{$counter}";
        $counter++;
      }
    }



    // Convert checkbox fields to JSON
    $specialization = $request->has('mandatory_specialization') && is_array($request->mandatory_specialization)
      ? json_encode($request->mandatory_specialization)
      : null;

    $medical_system = $request->has('mandatory_medical_system') && is_array($request->mandatory_medical_system)
      ? json_encode($request->mandatory_medical_system)
      : null;

    $facilities = $request->has('mandatory_facilities') && is_array($request->mandatory_facilities)
      ? json_encode($request->mandatory_facilities)
      : null;

    $hospital_images = $request->has('mandatory_hospital_images') && is_array($request->mandatory_hospital_images)
      ? json_encode($request->mandatory_hospital_images)
      : null;
    // Generate the hospital slug from the hospital name
    $hospital_slug = Str::slug($request->mandatory_hospital_name);
    $slugExists = HospitalsProfile::where('hospital_slug', $hospital_slug)->exists();
    if ($slugExists) {
      $hospital_slug .= '-' . Str::random(5);
    }

    // Prepare data for update or create
    $data = [
      'hospital_id' => $hospital_id,
      'hospital_name' => $request->mandatory_hospital_name,
      'description' => $request->mandatory_description,
      'specialization' => $specialization,
      'medical_system' => $medical_system,
      'phone' => $request->mandatory_phone,
      'emergency_contact' => $request->mandatory_emergency_contact,
      'email' => $request->mandatory_email,
      'website' => $request->mandatory_website,
      'location' => $request->mandatory_location,
      'facilities' => $facilities,
      'other_data' => json_encode($other_data),
      'hospital_slug' => $hospital_slug,
      'hospital_images' => $hospital_images,
    ];

    // Update or create the hospital profile
    HospitalsProfile::updateOrCreate(
      ['hospital_id' => $hospital_id],
      $data
    );

    return true;
  }
  public function destroy($id)
  {
    try {
      // Find the user by ID
      $user = User::findOrFail($id);

      // Check if the hospital profile exists
      $hospital_profile = HospitalsProfile::where('hospital_id', $id)->first();

      // If hospital profile exists, delete associated images and the profile
      if ($hospital_profile) {
        // Delete images associated with the hospital profile
        if ($hospital_profile->hospital_images) {
          $images_data = json_decode($hospital_profile->hospital_images, true);
          $image_urls = $images_data['images'] ?? [];

          foreach ($image_urls as $image_url) {
            // Extract the file path from the URL
            $image_path = parse_url($image_url, PHP_URL_PATH);

            // Delete the image file
            if (Storage::exists($image_path)) {
              Storage::delete($image_path);
            }
          }

          // Optionally update the hospital_images column to null or an empty JSON object
          $hospital_profile->hospital_images = null; // or '{}'
          $hospital_profile->save();
        }

        // Delete the hospital profile
        $hospital_profile->delete();
      }

      // Delete the user
      $user->delete();

      // Return a success response
      return response()->json([
        'success' => true,
        'message' => 'User and associated hospital profile deleted successfully, along with associated images (if any).'
      ]);
    } catch (\Exception $e) {
      // Handle any errors
      return response()->json([
        'success' => false,
        'message' => 'An error occurred while trying to delete the user and hospital profile.',
        'error' => $e->getMessage()
      ], 500);
    }
  }


  public function uploadImageCase8(Request $request)
  {
    // Validate the file with a custom error message for size
    $request->validate([
      'file' => 'required|image|max:5125', // Validate image file
    ], [
      'file.max' => 'The image size must not exceed 5MB.',
    ]);

    // If validation passes, store the image
    if ($request->file('file')->isValid()) {
      $hospital_id = Auth::id();
      //dd($hospital_id);
      $filePath = $request->file('file')->store('uploads/case8/' . $hospital_id, 'public');

      return response()->json(['success' => true, 'filePath' => asset('storage/' . $filePath)]);
    }

    // If validation fails, return failure response
    return response()->json(['success' => false]);
  }
}
