<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManageUser extends Controller
{
  public function index()
  {
    return view('content.users.manage-user');
  }
  public function user_destroy($id)
  {
    try {
      // Find the user by ID
      $user = User::findOrFail($id);

      // Check if the user profile exists
      $user_profile = UserProfile::where('user_id', $id)->first();

      // If user profile exists, delete associated images and the profile
      if ($user_profile) {

        // Delete the user profile
        $user_profile->delete();
      }

      $user->delete();

      // Return a success response
      return response()->json([
        'success' => true,
        'message' => 'User and associated user profile deleted successfully.'
      ]);
    } catch (\Exception $e) {
      // Handle any errors
      return response()->json([
        'success' => false,
        'message' => 'An error occurred while trying to delete the user and user profile.',
        'error' => $e->getMessage()
      ], 500);
    }
  }
  public function user_status_update(Request $request, $id)
  {
    $user = User::find($id);

    if (!$user) {
      return response()->json(['success' => false, 'message' => 'user not found.']);
    }

    try {

      $update_status = $user->verify == 1 ? 0 : 1;
      $user->verify = $update_status;
      $user->save();
      $newStatus = $user->verify == 1 ? 'Approved' : 'blocked';


      return response()->json([
        'success' => true,
        'message' => "user successfully {$newStatus}.",
        'status' => $user->verify
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update user status.'
      ]);
    }
  }
  public function users_table_json(Request $request)
  {
    $query = User::with('userprofile')->where('type', 3)->orWhere('type', 4);

    // Filter by country if provided
    if ($request->has('country') && $request->country) {
      $query->whereHas('userprofile', function ($q) use ($request) {
        $q->where('country', 'like', '%' . $request->country . '%'); // Use 'like' for partial match
      });
    }

    // Filter by state if provided
    if ($request->has('state') && $request->state != '') {
      $query->whereHas('userprofile', function ($q) use ($request) {
        $q->where('state', 'like', '%' . $request->state . '%'); // Use 'like' for partial match
      });
    }

    // Filter by city if provided
    if ($request->has('city') && $request->city != '') {
      $query->whereHas('userprofile', function ($q) use ($request) {
        $q->where('city', 'like', '%' . $request->city . '%'); // Use 'like' for partial match
      });
    }

    // Execute the query and fetch users
    $users = $query->orderBy('id', 'desc')->get();

    // Prepare the response data
    if ($users->isEmpty()) {
      return response()->json([
        "draw" => $request->draw,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => [],
      ]);
    }

    // Format the response data
    $data = [];
    foreach ($users as $item) {
      // Determine the status
      $status = ($item->verify == 1)
        ? '<span class="status approved">Approved</span>'
        : '<span class="status pending">Pending</span>';
      $blockText = $item->verify == 1 ? 'Block' : 'Unblock';
      $blockIcon = $item->verify == 1 ? 'ti-lock' : 'ti-lock-open';
      $action_html = "
        <div class='d-inline-block'>
            <a href='javascript:;'
                class='btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow'
                data-bs-toggle='dropdown'
                aria-expanded='false'>
                <i class='ti ti-dots ti-md'></i>
            </a>
            <div class='dropdown-menu dropdown-menu-end m-0'>
                <a href='" . route('edit-user', ['id' => $item->id]) . "' class='dropdown-item'>
                    <i class='menu-icon tf-icons ti ti-pencil'></i> Edit
                </a>
                <a href='javascript:;'
                    class='dropdown-item'
                    onclick='delete_user({$item->id})'>
                    <i class='menu-icon tf-icons ti ti-trash'></i> Delete
                </a>
                <a href='javascript:;'
                    class='dropdown-item'
                    onclick='block_unblock_user({$item->id})'>
                    <i class='menu-icon tf-icons ti {$blockIcon}'></i> {$blockText}
                </a>
            </div>
        </div>
        ";

      // Add formatted data to the response array
      $city = $item->userprofile->city ?? '';
      $state = $item->userprofile->state ?? '';
      $country = $item->userprofile->country ?? '';
      $data[] = [
        $item->id,
        Carbon::parse($item->created_at)->format('M d, Y'),
        $status,
        $item->name,
        $item->email,
        "<div class='city'>{$city}</div> <div class='state'>{$state}</div> <div class='country'>{$country}</div>",
        $item->mobile,
        $action_html
      ];
    }

    // Return the response in JSON format
    return response()->json([
      "draw" => $request->draw,
      "recordsTotal" => count($data),
      "recordsFiltered" => count($data),
      "data" => $data,
    ]);
  }

  public function export_users_csv(Request $request)
  {

    $query = User::with('userprofile')->where('type', 3);

    // Filter by country if provided
    if ($request->has('country') && $request->country) {
      $query->whereHas('userprofile', function ($q) use ($request) {
        $q->where('country', 'like', '%' . $request->country . '%'); // Use 'like' for partial match
      });
    }

    // Filter by state if provided
    if ($request->has('state') && $request->state != '') {
      $query->whereHas('userprofile', function ($q) use ($request) {
        $q->where('state', 'like', '%' . $request->state . '%'); // Use 'like' for partial match
      });
    }

    // Filter by city if provided
    if ($request->has('city') && $request->city != '') {
      $query->whereHas('userprofile', function ($q) use ($request) {
        $q->where('city', 'like', '%' . $request->city . '%'); // Use 'like' for partial match
      });
    }

    $users_list = $query->get();

    $headers = [
      'Content-type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename=user-list.csv',
      'Pragma' => 'no-cache',
      'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
      'Expires' => '0',
    ];

    $columns = ['ID', 'Full Name', 'Email', 'Mobile', 'City', 'State', 'Country'];

    $callback = function () use ($users_list, $columns) {
      $file = fopen('php://output', 'w');

      fputcsv($file, $columns);

      foreach ($users_list as $record) {
        fputcsv($file, [
          $record->id,
          $record->name,
          $record->email,
          $record->mobile,
          $record->userprofile->city,
          $record->userprofile->state,
          $record->userprofile->country,
        ]);
      }
      fclose($file);
    };

    // Return the streamed response with the headers
    return Response::stream($callback, 200, $headers);
  }
}
