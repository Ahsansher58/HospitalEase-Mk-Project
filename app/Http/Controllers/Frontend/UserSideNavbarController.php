<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use App\Models\FavoriteHospital;
use App\Models\LocationsMaster;
use App\Models\UserProfile as ModelsUserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserSideNavbarController extends Controller
{
  public function profile()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();
    $categories = BusinessCategory::orderBy('order_no')->get();
    $businessCategories = $categories->where('is_sub_category', false);
    $businessSubCategories = $categories->where('is_sub_category', true);
    $locationMaster = LocationsMaster::all();
    $uniqueLocalities = LocationsMaster::select('locality')->distinct()->pluck('locality');
    $uniqueCities = LocationsMaster::select('city')->distinct()->pluck('city');
    $uniqueStates = LocationsMaster::select('state')->distinct()->pluck('state');
    $uniqueCountries = LocationsMaster::select('country')->distinct()->pluck('country');
    return view('frontend.content.users.user_profile', compact('user', 'uniqueLocalities', 'uniqueCities', 'uniqueStates', 'uniqueCountries', 'businessCategories', 'businessSubCategories', 'locationMaster'));
  }
  public function dashboard()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();

    // Pass the user data to the view
    return view('frontend.content.users.user_profile_dashboard', compact('user'));
  }
  public function personnel_info()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();
    $user_profile = ModelsUserProfile::where('user_id', $user->id)->first();
    return view('frontend.content.users.user_personnel', compact('user', 'user_profile'));
  }
  public function edit_personnel_info()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();
    $user_profile = ModelsUserProfile::where('user_id', $user->id)->first();
    $categories = BusinessCategory::orderBy('order_no')->get();
    $businessCategories = $categories->where('is_sub_category', false);
    $businessSubCategories = $categories->where('is_sub_category', true);
    $locationMaster = LocationsMaster::all();
    $uniqueLocalities = LocationsMaster::select('locality')->distinct()->pluck('locality');
    $uniqueCities = LocationsMaster::select('city')->distinct()->pluck('city');
    $uniqueStates = LocationsMaster::select('state')->distinct()->pluck('state');
    $uniqueCountries = LocationsMaster::select('country')->distinct()->pluck('country');
    return view('frontend.content.users.edit_user_personnel', compact('user', 'uniqueLocalities', 'uniqueCities', 'uniqueStates', 'uniqueCountries', 'businessCategories', 'businessSubCategories', 'locationMaster', 'user_profile'));
  }

  public function user_profile_fav()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();
    $favoriteHospitals = FavoriteHospital::where('user_id', $user->id)
      ->with('hospital') // Assuming there is a `hospital` relationship
      ->get();

    return view('frontend.content.users.user_profile_fav', compact('user', 'favoriteHospitals'));
  }
  public function medical_records()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();
    return view('frontend.content.users.user_medical_records', compact('user'));
  }

  /*Add other fields data of user */
  public function profile_update(Request $request)
  {
    // Validation rules for the form fields
    $request->validate([
      'height' => 'required|numeric',
      'weight' => 'required|numeric',
      'address' => 'required|string|max:255',
      'locality' => 'required|string|max:100',
      'city' => 'required|string|max:100',
      'state' => 'required|string|max:100',
      'pincode' => 'required|string|max:10',
      'country' => 'required|string|max:100',
    ]);

    // Save the data to the `user_profile` table
    $userProfile = ModelsUserProfile::updateOrCreate(
      ['user_id' => auth()->id()],
      [
        'height' => $request->height,
        'weight' => $request->weight,
        'address' => $request->address,
        'locality' => $request->locality,
        'city' => $request->city,
        'state' => $request->state,
        'pincode' => $request->pincode,
        'country' => $request->country,
      ]
    );

    if ($userProfile) {
      session(['user_profile' => $userProfile]);
    }
    // Redirect back with a success message
    return redirect()->route('user.dashboard')->with('success', 'Profile updated successfully!');
  }
  public function profile_update_all(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'gender' => 'required|string',
      'dob' => 'required|date',
      'marital_status' => 'required|string',
      'height' => 'required|regex:/^\d+(\.\d{1,2})?$/',
      'weight' => 'required|numeric',
      'address' => 'required|string',
      'locality' => 'required|string',
      'city' => 'required|string',
      'state' => 'required|string',
      'pincode' => 'required|numeric',
      'country' => 'required|string',
    ]);
    // Update user data
    $user = auth()->user();
    if ($user instanceof User) {
      $user->update([
        'name' => $request->name,
        'gender' => $request->gender,
        'dob' => \Carbon\Carbon::parse($request->dob)->format('Y-m-d'),
      ]);
    }
    // Update user profile
    $userProfile = ModelsUserProfile::where('user_id', auth()->id())->first();
    $userProfile->update([
      'marital_status' => $request->marital_status,
      'height' => $request->height,
      'weight' => $request->weight,
      'address' => $request->address,
      'locality' => $request->locality,
      'city' => $request->city,
      'state' => $request->state,
      'pincode' => $request->pincode,
      'country' => $request->country,
    ]);

    // Redirect back with success message
    return redirect()->route('user.personnelInfo')->with('success', 'Profile updated');
  }
}
