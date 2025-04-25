<?php

namespace App\Http\Controllers\advertisements;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdvertisementsRequest;
use App\Models\Advertisements;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;


class ManageAdvertisements extends Controller
{
  public function index()
  {
    return view('content.advertisements.manage-advertisements');
  }
  public function edit($id)
  {
    // Fetch the advertisement by ID
    $advertisement = Advertisements::findOrFail($id);

    // Pass the advertisement data to the view
    $places = config('adsplaces.places');
    return view('content.advertisements.add-advertisements', [
      'advertisement' => $advertisement,
      'places' => $places
    ]);
  }
  public function destroy($id)
  {
    $advertisement = Advertisements::find($id);
    if (!$advertisement) {
      return response()->json(['success' => false, 'message' => 'Advertisement not found.']);
    }

    try {
      // Check if option is 1 and delete the associated image
      if ($advertisement->option == 1) {
        $imagePath = 'public/storage/' . $advertisement->image_code;
        if (Storage::exists($imagePath)) {
          Storage::delete($imagePath);
        }
      }

      // Delete the advertisement
      $advertisement->delete();

      return response()->json(['success' => true, 'message' => 'Advertisement deleted successfully.']);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Failed to delete advertisement: ' . $e->getMessage()]);
    }
  }

  public function block($id)
  {
    $advertisement = Advertisements::find($id);

    if (!$advertisement) {
      return response()->json(['success' => false, 'message' => 'Advertisement not found.']);
    }

    try {
      // Toggle the status: If it's 1 (blocked), change to 0 (active), otherwise change to 1 (blocked)
      $advertisement->status = $advertisement->status == 1 ? 0 : 1;

      // Save the updated status
      $advertisement->save();

      // Prepare the message based on the new status
      $message = $advertisement->status == 1 ? 'Advertisement blocked successfully.' : 'Advertisement activated successfully.';

      return response()->json(['success' => true, 'message' => $message]);
    } catch (Exception $e) {
      return response()->json(['success' => false, 'message' => 'Failed to update advertisement status.']);
    }
  }

  public function table_json(Request $request)
  {
    $placements = config('adsplaces.places');

    // Base query
    $query = Advertisements::query();

    // Handle search input if provided
    if ($request->has('search') && $request->search != '') {
      $searchValue = $request->search;

      // Apply search filter to specific columns (e.g., campaign_name, placement, etc.)
      $query->where(function ($q) use ($searchValue) {
        $q->where('campaign_name', 'like', '%' . $searchValue . '%');
      });
    }

    // Sorting
    $orderColumnIndex = $request->order[0]['column'] ?? 0;
    $orderDirection = $request->order[0]['dir'] ?? 'desc';
    $columns = ['id', 'start_date', 'end_date', 'campaign_name', 'image_code', 'placement', 'status'];
    $orderByColumn = $columns[$orderColumnIndex] ?? 'id';

    // Get total records count
    $totalRecords = $query->count();

    // Apply ordering
    $result = $query->orderBy($orderByColumn, $orderDirection)
      ->limit($request->length)
      ->get();

    $data = [];
    foreach ($result as $item) {
      // Check if image_code contains a valid image path and add an <img> tag
      $imageTag = $item->option == 1 ? '<a href="#" data-fancybox="gallery"><img src="' . asset('storage/' . $item->image_code) . '" alt="' . $item->campaign_name . '" class="ads"></a>' : substr($item->image_code, 0, 20) . '...';
      // Determine status
      $statusText = $item->status == 1 ? 'Blocked' : 'Active';
      $placementName = $placements[$item->placement] ?? 'Unknown';
      $formattedStartDate = Carbon::parse($item->start_date)->format('d/m/Y');
      $formattedEndDate = Carbon::parse($item->end_date)->format('d/m/Y');
      $data[] = [
        $item->id,
        $formattedStartDate . " to " . $formattedEndDate,
        $item->campaign_name,
        $imageTag,
        $placementName,
        $item->status == 1 ? '<i class="menu-icon tf-icons ti ti-checkbox text-success"></i>' : '<i class="menu-icon tf-icons ti ti-lock text-danger"></i>',
        '<div class="d-inline-block">
                <a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots ti-md"></i></a>
                <div class="dropdown-menu dropdown-menu-end m-0" style="">
                    <a href="' . route("edit-advertisements", $item->id) . '" class="dropdown-item" ><i class="menu-icon tf-icons ti ti-pencil"></i> Edit</a>
                    <a href="javascript:;" class="dropdown-item" onclick="delete_ads(' . $item->id . ')"><i class="menu-icon tf-icons ti ti-trash"></i> Delete</a>
                    <a href="javascript:;" class="dropdown-item" onclick="block_ads(' . $item->id . ',' . $item->status . ')"><i class="menu-icon tf-icons ti ti-lock"></i> ' . $statusText . '</a>
                </div>
            </div>',
      ];
    }

    return response()->json([
      "draw" => $request->draw,
      "recordsTotal" => $totalRecords,
      "recordsFiltered" => $totalRecords,
      "data" => $data,
    ]);
  }
  public function exportCsv(Request $request)
  {
    $query = Advertisements::query();
    if ($request->has('search') && $request->search) {
      $searchValue = $request->search;
      $query->where(function ($q) use ($searchValue) {
        $q->where('campaign_name', 'like', '%' . $searchValue . '%')
          ->orWhere('image_code', 'like', '%' . $searchValue . '%')
          ->orWhere('placement', 'like', '%' . $searchValue . '%');
      });
    }

    $advertisements = $query->get();

    $headers = [
      'Content-type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename=advertisements.csv',
      'Pragma' => 'no-cache',
      'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
      'Expires' => '0',
    ];

    $columns = ['Start Date', 'End Date', 'Campaign Name', 'Image/Code', 'Placement', 'Status'];

    $callback = function () use ($advertisements, $columns) {
      $file = fopen('php://output', 'w');

      fputcsv($file, $columns);
      $placements = config('adsplaces.places');
      foreach ($advertisements as $advertisement) {
        $statusText = $advertisement->status == 1 ? 'Blocked' : 'Active';
        $startDate = date('d/m/Y', strtotime($advertisement->start_date));
        $endDate = date('d/m/Y', strtotime($advertisement->end_date));
        $placementName = $placements[$advertisement->placement] ?? 'Unknown';
        $row = [
          $startDate,
          $endDate,
          $advertisement->campaign_name,
          $advertisement->image_code,
          $placementName,
          $statusText
        ];

        fputcsv($file, $row);
      }

      fclose($file);
    };

    // Return the streamed response with the headers
    return Response::stream($callback, 200, $headers);
  }
}
