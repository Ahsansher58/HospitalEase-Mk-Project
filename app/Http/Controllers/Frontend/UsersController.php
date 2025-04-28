<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordMail;
use Carbon\Carbon;
use App\Models\HospitalsProfile;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Illuminate\Support\Facades\Session;
use App\Models\UserProfile as ModelsUserProfile;


class UsersController extends Controller
{
  /**
   * Show the login form.
   *
   * @return \Illuminate\View\View
   */
  public function userLogin()
  {
    return view('frontend.content.users.login');
  }
  public function user_login(Request $request)
  {
    // Validate the input, allowing either email or mobile for login
    $validatedData = $request->validate([
      'login' => 'required|string', // Login can be email or mobile
      'password' => 'required|string|min:6',
      'g-recaptcha-response' => 'required|captcha',
    ]);

    // Determine if the login input is an email or a mobile number
    $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

    // Find the user by email or mobile based on the input
    $user = User::where($fieldType, $request->login)->first();

    // Check if the user exists and if the password is correct
    if ($user && $user->verify == 1) {
      if (Hash::check($request->password, $user->password)) {
        Auth::login($user);
        $userProfile = ModelsUserProfile::where('user_id', $user->id)->first();
        if ($userProfile) {
          session(['user_profile' => $userProfile]);
          return redirect()->route('user.dashboard');
        } else {
          return redirect()->route('user.profile');
        }
      } else {
        return back()->withErrors(['error' => 'Invalid email/mobile or password']);
      }
    } else {
      return back()->withErrors(['error' => 'Your account is not verified/blocked. Please wait for admin approval/unblock.']);
    }
  }

  public function user_logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('users.login')->with('success', 'Logout successful!');
  }

  public function userRegister()
  {
    return view('frontend.content.users.register');
  }
  public function user_register(Request $request)
  {
    $validatedData = $request->validate([
      'email' => 'required|email|unique:users,email',
      'mobile' => 'required|unique:users,mobile|regex:/^[0-9]{10}$/', // Adjust if needed
      'firstName' => 'required|string|max:255',
      'lastName' => 'required|string|max:255',
      'dob' => 'required|date',
      'gender' => 'required|in:male,female,other',
      'g-recaptcha-response' => 'required|captcha',
    ]);

    try {
      $randomPassword = Str::random(8);

      $user = User::create([
        'name' => $request->firstName . ' ' . $request->lastName,
        'email' => $request->email,
        'mobile' => $request->mobile,
        'password' => Hash::make($randomPassword),
        'type' => 3,
        'dob' => $request->dob,
        'gender' => $request->gender,
      ]);

      // Send password to the user's email
      Mail::to($user->email)->send(new SendPasswordMail($user, $randomPassword));
      return redirect()->route('users.login')->with('success', 'Registration successful! Check your email for your password.');
    } catch (TransportExceptionInterface $e) {
      // If the email fails to send, capture the error message and display it
      return back()->withErrors(['error' => 'Failed to send email: ' . $e->getMessage()]);
    } catch (\Exception $e) {
      // Catch any other exceptions and display the general error message
      return back()->withErrors(['error' => 'An error occurred while processing your registration: ' . $e->getMessage()]);
    }
  }
  public function userChangePassword(Request $request)
  {
    //dd($request->all());
    if (!Auth::check()) {
      return redirect()->route('users.login')->with('error', 'You must be logged in to change your password.');
    }

    // Validate the request
    $request->validate([
      'old_password' => 'required',
      'new_password' => 'required|min:8|confirmed',
    ]);

    // Check if the old password matches the logged-in user
    if (!Hash::check($request->old_password, Auth::user()->password)) {
      return back()->withErrors(['old_password' => 'The provided password does not match your current password.']);
    }

    // Update the password

    $user = User::where('id', auth()->id())->first();
    if (!$user) {
      return back()->with('error', 'User not authenticated');
    }
    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Password changed successfully!');
  }

  public function doctorChangePassword(Request $request)
  {
    //dd($request->all());
    if (!Auth::check()) {
      return redirect()->route('doctors.login')->with('error', 'You must be logged in to change your password.');
    }

    // Validate the request
    $request->validate([
      'old_password' => 'required',
      'new_password' => 'required|min:8|confirmed',
    ]);

    // Check if the old password matches the logged-in user
    if (!Hash::check($request->old_password, Auth::user()->password)) {
      return back()->withErrors(['old_password' => 'The provided password does not match your current password.']);
    }

    // Update the password

    $user = User::where('id', auth()->id())->first();
    if (!$user) {
      return back()->with('error', 'User not authenticated');
    }
    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Password changed successfully!');
  }

  /*Hospital functions */
  public function hospitalLogin()
  {
    return view('frontend.content.hospitals.login');
  }
  public function hospital_login(Request $request)
  {
    $validatedData = $request->validate([
      'login'                   => 'required|string',
      'password'                => 'required|string|min:6',
      // 'g-recaptcha-response' => 'required|captcha',
    ]);

    $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

    $user = User::where($fieldType, $request->login)->first();

    // Check if the user exists, if the password is correct, and if the account is verified
    if ($user) {
      if ($user->verify == 1 && Hash::check($request->password, $user->password)) {
        Auth::login($user);
        return redirect()->route('hospital.profile');
      } else {
        return back()->withErrors(['error' => 'Your account is not verified. Please wait for admin approval.']);
      }
    } else {
      return back()->withErrors(['error' => 'Invalid email/mobile or password']);
    }
  }

  public function hospitalRegister()
  {
    return view('frontend.content.hospitals.signup');
  }
  /*  public function hospital_signup(Request $request)
  {

    $validatedData = $request->validate([
      'email' => 'required|email|unique:users,email',
      'mobile' => 'required|unique:users,mobile|regex:/^[0-9]{10}$/',
    ]);

    try {

      $randomPassword = Str::random(8);

      // Generate a 5-digit OTP
      $otp = random_int(10000, 99999);
      $otpExpiry = now()->addMinutes(10);

      $user = User::create([
        'email' => $request->email,
        'mobile' => $request->mobile,
        'type' => 2,
        'otp' => $otp,
        'otp_expires_at' => $otpExpiry,
        'name' => ucfirst(explode('@', $request->email)[0]),
        'password' => Hash::make($randomPassword),
      ]);

      // Send OTP to the user's email
      Mail::to($user->email)->send(new SendOtpMail($user, $otp));

      session(['otp_email' => $request->email]);
      return redirect()->route('hospitals.verifyOTP')->with('verifyOTP', 'Registration successful! Check your email for your OTP.');
    } catch (TransportExceptionInterface $e) {
      return back()->withErrors(['error' => 'Failed to send OTP: ' . $e->getMessage()]);
    } catch (\Exception $e) {
      return back()->withErrors(['error' => 'An error occurred while processing your registration: ' . $e->getMessage()]);
    }
  } */

  public function hospital_signup(Request $request)
  {
    // Validate incoming request
    $validated = $request->validate([
      'email' => 'required|email|unique:users,email',
      'mobile' => 'required|numeric|unique:users,mobile',
      'hospital_name' => 'required|string|max:255',
      'password' => 'required|string|min:8|confirmed',
      'g-recaptcha-response' => 'required|captcha',
    ]);

    // Create the user
    $user = User::create([
      'email' => $validated['email'],
      'name' => ucfirst(explode('@', $validated['email'])[0]),
      'mobile' => $validated['mobile'],
      'password' => Hash::make($validated['password']),
      'type' => 2,
      'verify' => 0,
    ]);

    $slug = Str::slug($validated['hospital_name']);
    $originalSlug = $slug;

    $counter = 1;
    while (HospitalsProfile::where('hospital_slug', $slug)->exists()) {
      $slug = "{$originalSlug}-{$counter}";
      $counter++;
    }

    HospitalsProfile::create([
      'hospital_id' => $user->id,
      'hospital_name' => $validated['hospital_name'],
      'hospital_slug' => $slug,
    ]);

    return redirect()->route('hospital.login')->with('success', 'Your account is created, Please wait for admin approval. Your account is under review.');
  }


  public function verifyOtp(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users,email',
      'otp' => 'required|digits:5',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || $user->otp !== $request->otp) {
      return back()->withErrors(['error' => 'Invalid OTP.']);
    }

    if (now()->greaterThan($user->otp_expires_at)) {
      return back()->withErrors(['error' => 'OTP has expired.']);
    }

    // Mark email as verified
    $randomPassword = Str::random(8);
    Mail::to($user->email)->send(new SendPasswordMail($user, $randomPassword));
    $user->update(['password' => Hash::make($randomPassword), 'otp' => null, 'otp_expires_at' => null, 'email_verified_at' => now(), 'verify' => 1]);

    return redirect()->route('hospital.login')->with('success', 'Email verified successfully!');
  }

  public function resendOtp(Request $request)
  {
    try {
      // Retrieve email from the request
      $email = $request->input('email');
      $user = User::where('email', $email)->first();

      if (!$user) {
        return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
      }

      // Generate a new OTP
      $otp = random_int(10000, 99999);
      $otpExpiry = now()->addMinutes(10);
      // Save OTP in the database (or session)
      $user->otp = $otp;
      $user->otp_expires_at = $otpExpiry;
      $user->save();

      // Send OTP via email
      Mail::to($email)->send(new SendOtpMail($user, $otp));

      return response()->json(['status' => 'success', 'message' => 'OTP resent successfully.']);
    } catch (\Exception $e) {
      return response()->json(['status' => 'error', 'message' => 'Failed to resend OTP: ' . $e->getMessage()], 500);
    }
  }
  public function hospitalverifyOTP()
  {
    return view('frontend.content.hospitals.verify_otp');
  }
  public function hospital_logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('hospital.login')->with('success', 'Logout successful!');
  }
  public function hospitalChangePassword(Request $request)
  {
    //dd($request->all());
    if (!Auth::check()) {
      return redirect()->route('hospital.login')->with('error', 'You must be logged in to change your password.');
    }

    // Validate the request
    $request->validate([
      'old_password' => 'required',
      'new_password' => 'required|min:8|confirmed',
    ]);

    // Check if the old password matches the logged-in user
    if (!Hash::check($request->old_password, Auth::user()->password)) {
      return back()->withErrors(['old_password' => 'The provided password does not match your current password.']);
    }

    // Update the password

    $user = User::where('id', auth()->id())->first();
    if (!$user) {
      return back()->with('error', 'User not authenticated');
    }
    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Password changed successfully!');
  }

  // Doctors

  public function doctorLogin()
  {
    return view('frontend.content.doctors.login');
  }

  public function doctor_login(Request $request)
  {
    $validatedData = $request->validate([
      'login'                => 'required|string',
      'password'             => 'required|string|min:6',
      // 'g-recaptcha-response' => 'required|captcha',
    ]);

    $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

    $user = User::where($fieldType, $request->login)->first();

    // Check if the user exists, if the password is correct, and if the account is verified
    if ($user) {
      if ($user->verify == 1 && Hash::check($request->password, $user->password)) {

        Auth::login($user);
        return redirect()->route('doctor.dashboard');
      } else {
        return back()->withErrors(['error' => 'Your account is not verified. Please wait for admin approval.']);
      }
    } else {
      return back()->withErrors(['error' => 'Invalid email/mobile or password']);
    }
  }


  public function doctorRegister()
  {
    return view('frontend.content.doctors.register');
  }
  public function doctor_register(Request $request)
  {
    $validatedData = $request->validate([
      'email'                => 'required|email|unique:users,email',
      'mobile'               => 'required|unique:users,mobile|regex:/^[0-9]{10}$/',
      'firstName'            => 'required|string|max:255',
      'lastName'             => 'required|string|max:255',
      'dob'                  => 'required|date',
      'gender'               => 'required|in:male,female,other',
      'g-recaptcha-response' => 'required|captcha',
    ]);

    try {
      $randomPassword = Str::random(8);

      $user = User::create([
        'name'     => $request->firstName . ' ' . $request->lastName,
        'email'    => $request->email,
        'mobile'   => $request->mobile,
        'password' => Hash::make($randomPassword),
        'type'     => 4,
        'dob'      => $request->dob,
        'gender'   => $request->gender,
      ]);

      $year         = Carbon::now()->year;
      $randomNumber = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
      $doctor_no    = "HE/" . $year . "/" . $randomNumber;

      Doctor::create([
        'user_id'   => $user->id,
        'doctor_no' => $doctor_no,
        'name'      => $request->firstName . ' ' . $request->lastName,
        'email'     => $request->email,
        'phone'     => $request->mobile,
        'dob'       => $request->dob,
        'gender'    => $request->gender,
      ]);

      // Send password to the user's email
      Mail::to($user->email)->send(new SendPasswordMail($user, $randomPassword));
      return redirect()->route('doctors.login')->with('success', 'Registration successful! Check your email for your password.');
    } catch (TransportExceptionInterface $e) {
      // If the email fails to send, capture the error message and display it
      return back()->withErrors(['error' => 'Failed to send email: ' . $e->getMessage()]);
    } catch (\Exception $e) {
      // Catch any other exceptions and display the general error message
      return back()->withErrors(['error' => 'An error occurred while processing your registration: ' . $e->getMessage()]);
    }
  }
}
