<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
  /**
   * Display a listing of the medical records.
   */
  public function index()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();

    // Filter records by category
    $currentUserId = $user->id; // Retrieve the current user's ID

    $allMedicalRecords = MedicalRecord::where('user_id', $currentUserId)->orderBy('id', 'desc')->get();

    $prescriptions = MedicalRecord::where('user_id', $currentUserId)
      ->where('report_category', 'prescriptions')
      ->get();

    $labReports = MedicalRecord::where('user_id', $currentUserId)
      ->where('report_category', 'lab_reports')
      ->get();

    $xraysImaging = MedicalRecord::where('user_id', $currentUserId)
      ->where('report_category', 'xrays_imaging')
      ->get();

    $medicalHistory = MedicalRecord::where('user_id', $currentUserId)
      ->where('report_category', 'medical_history')
      ->get();

    $vaccinationRecords = MedicalRecord::where('user_id', $currentUserId)
      ->where('report_category', 'vaccination_records')
      ->get();


    return view('frontend.content.users.user_medical_records', compact(
      'user',
      'allMedicalRecords',
      'prescriptions',
      'labReports',
      'xraysImaging',
      'medicalHistory',
      'vaccinationRecords'
    ));
  }

  /**
   * Show the form for creating a new medical record.
   */
  public function create()
  {
    return view('frontend.medical_records.create');
  }

  /**
   * Store a newly created medical record in storage.
   */
  public function store(Request $request)
  {
    // Validate the incoming request
    $request->validate([
      'report_category' => 'required|string|max:255',
      'report_name' => 'required|string|max:255',
      'report_date' => 'required|date',
      'report_file' => 'required|file|mimes:jpg,pdf,png|max:2048',
    ]);

    // Check if the user is authenticated
    if (auth()->check()) {
      $userId = auth()->id();

      // Handle the uploaded file
      if ($request->hasFile('report_file')) {
        // Store the file in a specific directory and get the file path
        $file = $request->file('report_file');
        $filePath = $file->store("public/users/{$userId}/medical_records");
        $fileName = basename($filePath);

        // Create the medical record entry
        MedicalRecord::create([
          'user_id' => $userId,
          'report_category' => $request->report_category,
          'report_name' => $request->report_name,
          'report_date' => $request->report_date,
          'report_file' => $fileName,
        ]);

        // Redirect with success message
        return redirect()->route('user.medicalRecords')->with('success', 'Medical Report created successfully.');
      }

      // Handle case where no file is uploaded
      return redirect()->route('user.medicalRecords')->with('error', 'No file was uploaded.');
    }

    // Handle case where user is not authenticated
    return redirect()->route('users.login')->with('error', 'You must be logged in to submit a medical record.');
  }


  /**
   * Remove the specified medical record from storage.
   */
  public function destroy($id)
  {
    $medicalRecord = MedicalRecord::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
    if (!$medicalRecord) {
      session()->flash('error', 'Report not found.');
      return response()->json(['message' => 'Report not found.'], 404);
    }
    $medicalRecord->delete();
    session()->flash('success', 'Report deleted successfully.');
    return response()->json(['message' => 'Report deleted successfully.']);
  }
}
