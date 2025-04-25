<?php

namespace App\Http\Controllers\hospital;

use App\Http\Controllers\Controller;
use App\Models\HospitalAppointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ManageHospital extends Controller
{
  public function index()
  {
    return view('content.hospital.manage-hospital');
  }
  public function add()
  {
    return view('content.hospital.manage-hospital');
  }
  public function list()
  {
    return view('content.hospital.manage-hospital');
  }
  public function appointment_list()
  {
    $appointments = HospitalAppointment::with('hospitalProfile')->get();
    return view('content.hospital-appointments.index', ['appointments' => $appointments]);
  }
  public function appointment_table_json(Request $request)
  {
    $query = HospitalAppointment::query();
    if ($request->has('date_range') && $request->date_range != '') {

      $dates = explode(' to ', $request->date_range);

      if (count($dates) === 2) {
        $start_date = $dates[0];
        $end_date = $dates[1];

        $query->whereBetween('appointment_date', [$start_date, $end_date]);
      }
    }
    if ($request->has('search') && $request->search != '') {
      $query->where(function ($query) use ($request) {
        // Filter by patient name
        $query->orWhere('patient_name', 'like', '%' . $request->search . '%');

        // Filter by phone number
        $query->orWhere('phone_number', 'like', '%' . $request->search . '%');

        // Filter by email
        $query->orWhere('email', 'like', '%' . $request->search . '%');

        // Filter by hospital name (joining with hospital profile)
        $query->orWhereHas('hospitalProfile', function ($q) use ($request) {
          $q->where('hospital_name', 'like', '%' . $request->search . '%');
        });
      });
    }
    $appointments = $query->orderBy('appointment_date', 'desc')->get();

    if ($appointments->isEmpty()) {
      return response()->json([
        "draw" => $request->draw,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => [],
      ]);
    }
    foreach ($appointments as $item) {
      // Add main category to the data array
      $data[] = [
        $item->id,
        'HE-' . Carbon::parse($item->created_at)->format('d-m-Y') . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
        Carbon::parse($item->appointment_date)->format('M d, Y'),
        $item->hospitalProfile->hospital_name,
        $item->patient_name,
        $item->phone_number,
        $item->email,
        "<div class='action-btn'>
                  <a href='javascript:;' onclick='view_appointment({$item->id})' class='action-item'><i class='menu-icon tf-icons ti ti-eye'></i></a>
                  <a href='javascript:;' onclick='delete_appointment({$item->id})' class='action-item'><i class='menu-icon tf-icons ti ti-trash'></i></a>
              </div>",
      ];
    }

    return response()->json([
      "draw" => $request->draw,
      "recordsTotal" => count($data), // Total records includes both categories and subcategories
      "recordsFiltered" => count($data), // Adjusted if filtering is applied
      "data" => $data,
    ]);
  }
  public function appointment_destroy($id)
  {
    $appointment = HospitalAppointment::find($id);
    if (!$appointment) {
      return response()->json(['success' => false, 'message' => 'Appointment not found.']);
    }
    try {
      $appointment->delete();
      return response()->json(['success' => true, 'message' => 'Appointment deleted successfully.']);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Failed to delete Appointment.']);
    }
  }
  public function exportCsv(Request $request)
  {
    $query = HospitalAppointment::query();
    if ($request->has('date_range') && $request->date_range) {

      $dates = explode(' to ', $request->date_range);

      if (count($dates) === 2) {
        $start_date = $dates[0];
        $end_date = $dates[1];

        $query->whereBetween('appointment_date', [$start_date, $end_date]);
      }
    }

    $appointments = $query->get();

    $headers = [
      'Content-type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename=appointments.csv',
      'Pragma' => 'no-cache',
      'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
      'Expires' => '0',
    ];

    $columns = ['id', 'patient_name', 'phone_number', 'email', 'appointment_date'];

    $callback = function () use ($appointments, $columns) {
      $file = fopen('php://output', 'w');

      fputcsv($file, $columns);

      foreach ($appointments as $record) {
        fputcsv($file, [
          $record->id,
          $record->patient_name,
          $record->phone_number,
          $record->email,
          Carbon::parse($record->appointment_date)->format('M d, Y'),
        ]);
      }
      fclose($file);
    };

    // Return the streamed response with the headers
    return Response::stream($callback, 200, $headers);
  }



  public function hospitals_table_json(Request $request)
  {
    // Initialize the query with the necessary relationship and type condition
    $query = User::with('profile')->where('type', 2); // Only include users of type 2

    // Apply the search filter if the 'search' parameter exists
    if ($request->has('search') && $request->search != '') {
      $query->where(function ($query) use ($request) {
        $query->where('email', 'like', '%' . $request->search . '%')
          ->orWhere('mobile', 'like', '%' . $request->search . '%');
      });
    }

    // Apply the 'status' filter if the 'status' parameter exists
    if ($request->has('status') && $request->status != '') {
      $query->where('verify', $request->status);
    }

    // Get the total count of records for pagination
    $totalRecords = $query->count();

    // Retrieve the records
    $hospitals = $query->get();

    // Prepare the data for the table
    $data = [];
    foreach ($hospitals as $item) {
      // Check if profile exists and get hospital_name
      $hospitalName = $item->profile ? $item->profile->hospital_name : 'Not Available';

      // Determine the status
      $status = ($item->verify == 1)
        ? '<span class="status approved">Approved</span>'
        : '<span class="status pending">Pending</span>';

      // Add data to the response array
      $data[] = [
        $item->id,
        Carbon::parse($item->created_at)->format('M d, Y'), // Format the created_at date
        $status, // Status of the hospital
        $hospitalName, // Display hospital name or default value
        $item->mobile, // Hospital mobile number
        $item->email, // Hospital email
        '<div class="d-inline-block">
                <a
                    href="javascript:;"
                    class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <i class="ti ti-dots ti-md"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end m-0">
                <a href="' . route('edit_hospital', ['id' => $item->id]) . '" class="dropdown-item">
                        <i class="menu-icon tf-icons ti ti-pencil"></i> Edit
                    </a>
                <a href="javascript:void(0);" class="dropdown-item" onclick="delete_hospital(' . $item->id . ', 0)">
                        <i class="menu-icon tf-icons ti ti-trash"></i> Delete
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item" onclick="hospital_block_approved(' . $item->id . ', 0)">
                        <i class="menu-icon tf-icons ti ti-lock"></i> Block
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item" onclick="hospital_block_approved(' . $item->id . ', 1)">
                        <i class="menu-icon tf-icons ti ti-checkbox"></i> Approve
                    </a>
                </div>
            </div>',
      ];
    }

    // Return the response with pagination and data
    return response()->json([
      "draw" => $request->draw, // Pass the draw parameter back for DataTables
      "recordsTotal" => $totalRecords, // Total number of records (without filters)
      "recordsFiltered" => $totalRecords, // Number of records after filters (same as total if no filter applied)
      "data" => $data, // The data for the table
    ]);
  }

  public function hospital_unblock_review(Request $request, $id)
  {
    $hospital = User::find($id);

    if (!$hospital) {
      return response()->json(['success' => false, 'message' => 'Hospital not found.']);
    }

    try {
      $hospital->verify = $request->status;
      $hospital->save();

      $newStatus = $hospital->verify == 1 ? 'Approved' : 'blocked';

      return response()->json([
        'success' => true,
        'message' => "Hospital successfully {$newStatus}.",
        'status' => $hospital->verify
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update Hospital status.'
      ]);
    }
  }
}
