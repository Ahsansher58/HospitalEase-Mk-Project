<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginCover extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login', ['pageConfigs' => $pageConfigs]);
  }
  public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');

    // Attempt to log in the user
    if (Auth::attempt($credentials)) {
      // Check if the user's type is 1
      if (Auth::user()->type == 1) {
        return redirect()->intended('/sadmin');
      }

      // Redirect other types of users to the default intended page
      return redirect()->intended('/');
    }

    // Return back with an error if authentication fails
    return back()->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ]);
  }
  public function logout(Request $request)
  {
    // Log the user out
    Auth::logout();

    // Invalidate the session to prevent re-login on back button press
    $request->session()->invalidate();

    // Regenerate the session token
    $request->session()->regenerateToken();

    // Redirect to a specific page, e.g., homepage or login page
    return redirect()->route('login')->with('success', 'Logout successful!');
  }
}
