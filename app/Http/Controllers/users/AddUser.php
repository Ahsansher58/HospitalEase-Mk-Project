<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AddUser extends Controller
{
  public function index()
  {
    return view('content.users.add-user');
  }
  public function update(Request $request, $user_id)
  {
    $rules = [];

    $user = User::where('id', $user_id)->first();
    if ($request->has('fullname')) {
      $rules['fullname'] = 'required|string';
    }
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

    /*user profile */
    if ($request->has('country') && !empty($request->input('country'))) {
      $rules['country'] = 'required|string';
    }
    if ($request->has('state') && !empty($request->input('state'))) {
      $rules['state'] = 'required|string';
    }
    if ($request->has('city') && !empty($request->input('city'))) {
      $rules['city'] = 'required|string';
    }
    if ($request->has('locality') && !empty($request->input('locality'))) {
      $rules['locality'] = 'required|string';
    }
    $validated = $request->validate($rules);

    if ($user) {
      $updateData = [];

      if (!empty($validated['fullname']) && $user->name !== $validated['fullname']) {
        $updateData['name'] = $validated['fullname'];
      }

      if (!empty($validated['email']) && $user->email !== $validated['email']) {
        $updateData['email'] = $validated['email'];
      }

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


    $check = $this->userProfileUpdate($request, $user->id);
    if ($check)
      return redirect()->route('manage-user')->with('success', 'User is Updated successfully!');
    else
      return redirect()->route('manage-user')->with('success', 'User is Updated successfully, But locality is not Updated');
  }
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'fullname' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string|min:8|confirmed',
      'country' => 'required|string',
      'state' => 'required|string',
      'city' => 'required|string',
      'locality' => 'required|string',
      'mobile' => 'required|unique:users,mobile|regex:/^[0-9]{10}$/',
    ]);
    try {
      $user = User::create([
        'name' => $request->fullname,
        'email' => $request->email,
        'mobile' => $request->mobile,
        'password' => Hash::make($request->password),
        'type' => 3
      ]);
      $check = $this->userProfileUpdate($request, $user->id);
      if ($check)
        return redirect()->route('manage-user')->with('success', 'User is added successfully!');
      else
        return redirect()->route('manage-user')->with('success', 'User is added successfully, But locality is not added');
    } catch (\Exception $e) {
      return back()->withErrors(['error' => 'An error occurred while processing your registration: ' . $e->getMessage()]);
    }
  }
  public function userProfileUpdate($request, $user_id)
  {
    $data = [
      'user_id ' => $user_id,
      'locality' => $request->locality,
      'city' => $request->city,
      'state' => $request->state,
      'country' => $request->country,
    ];
    try {
      UserProfile::updateOrCreate(
        ['user_id' => $user_id],
        $data
      );
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }

  public function edit($id)
  {
    $data = User::with('userprofile')->where('id', $id)->first();
    return view('content.users.edit-user', compact('data'));
  }
}
