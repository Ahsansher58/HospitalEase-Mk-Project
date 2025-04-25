<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\EmergencyRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmergencyRequestsController extends Controller
{

  public function addEmergencyRequest(Request $request)
  {

    if (auth()->check()) {

      if (auth()->user()->type != 3) {
        return redirect()->back()->with('error', 'You do not have permission to submit an emergency request.');
      }

      $validated = $request->validate([
        'emergency_request_note' => 'required|string|max:50',
        'g-recaptcha-response' => 'required|captcha',
      ]);

      $existingRequest = EmergencyRequest::where('notes', $validated['emergency_request_note'])
        ->where('ip_address', $request->ip())
        ->where('date_time', '>=', now()->subHour())
        ->first();

      if ($existingRequest) {
        return redirect()->back()->with('error', 'You can only submit the same note after 1 hour.');
      }

      EmergencyRequest::create([
        'notes' => $validated['emergency_request_note'],
        'status' => 1,
        'date_time' => now(),
        'ip_address' => $request->ip(),
        'user_id' => auth()->id(),
      ]);

      return redirect()->route('home')->with('success', 'Emergency Request created successfully.');
    }

    // If user is not authenticated, redirect with error message
    return redirect()->route('login')->with('error', 'You must be logged in to submit an emergency request.');
  }
}
