<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\HospitalAppointment;
use App\Models\MedicalRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAppointmentsController extends Controller
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

    $currentUserId = $user->id;
    return view('frontend.content.users.user_appointments', compact(
      'user'
    ));
  }

  public function getUserAppointments(Request $request)
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();
    $query = HospitalAppointment::where('email', $user->email);

    $order = $request->input('order.0.column'); // Column index
    $dir = $request->input('order.0.dir'); // asc or desc
    $columns = ['id', 'patient_name', 'phone_number', 'email', 'appointment_date'];
    if ($order == 0)
      $query->orderBy('id', 'desc');
    else
      $query->orderBy($columns[$order], $dir);
    $perPage = $request->input('length');
    $offset = $request->input('start');
    $appointments = $query->skip($offset)->take($perPage)->get();

    // Total records
    $totalData = HospitalAppointment::where('email', $user->email)->count();
    $totalFiltered = $query->count();

    // Format data for DataTable
    $data = [];
    foreach ($appointments as $appointment) {
      $data[] = [
        'checkbox' => "<input type='checkbox' class='appointment-checkbox' data-id='{$appointment->id}' />",
        'appointment_number' => 'HE-' . Carbon::parse($appointment->created_at)->format('d-m-Y') . '-' . str_pad($appointment->id, 4, '0', STR_PAD_LEFT),
        'hospital_name' => $appointment->hospitalProfile->hospital_name,
        'phone_number' => $appointment->phone_number,
        'email' => $appointment->email,
        'appointment_date' => Carbon::parse($appointment->appointment_date)->format('M d, Y'),
        /* 'actions' => "<button class='btn btn-icon p-0 border-0 mx-2' onclick='deleteAppointment({$appointment->id})'>
                    <img src='" . asset('assets/frontend/images/icons/delete.svg') . "' alt='Delete'/>
                </button>
            ", */
      ];
    }
    // Response structure
    $json_data = [
      "draw" => intval($request->input('draw')),
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data" => $data
    ];

    return response()->json($json_data);
  }
  /**
   * Remove the specified medical record from storage.
   */
  public function destroy($id)
  {
    $appointment = HospitalAppointment::where('id', $id)->firstOrFail();
    if (!$appointment) {
      session()->flash('error', 'Report not found.');
      return response()->json(['message' => 'Report not found.'], 404);
    }
    $appointment->delete();
    session()->flash('success', 'Report deleted successfully.');
    return response()->json(['message' => 'Appointment deleted successfully.']);
  }
}
