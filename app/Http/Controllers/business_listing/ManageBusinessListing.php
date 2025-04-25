<?php

namespace App\Http\Controllers\business_listing;

use App\Http\Controllers\Controller;
use App\Models\BusinessCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\BusinessListing;
use App\Models\LocationsMaster;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ManageBusinessListing extends Controller
{
  public function index()
  {
    $businessListings = BusinessListing::all();
    $businessCategories = BusinessCategory::orderBy('order_no')->get();
    $locationMaster = LocationsMaster::all();
    $uniqueCities = LocationsMaster::select('city')->distinct()->pluck('city');
    $uniqueStates = LocationsMaster::select('state')->distinct()->pluck('state');
    $uniqueCountries = LocationsMaster::select('country')->distinct()->pluck('country');
    return view('content.business-listing.manage-business-listing', compact('businessListings', 'uniqueCities', 'uniqueStates', 'uniqueCountries', 'businessCategories', 'locationMaster'));
  }
  public function table_json(Request $request)
  {
    // Base query
    $query = BusinessListing::query();

    // Handle search input if provided
    if ($request->has('search') && $request->search != '') {
      $searchValue = $request->search;

      $query->where(function ($q) use ($searchValue) {
        $q->where('business_name', 'like', '%' . $searchValue . '%');
      });
    }
    // Handle categories input if provided
    if ($request->has('business_category_id') && $request->business_category_id != '') {
      $searchCat = $request->business_category_id;

      $query->where(function ($q) use ($searchCat) {
        $q->whereRaw('JSON_CONTAINS(categories, ?)', [json_encode($searchCat)]);
      });
    }

    // Handle city input if provided
    if ($request->has('select_city') && $request->select_city != '') {
      $query->where('city', $request->select_city);
    }
    // Handle state input if provided
    if ($request->has('select_state') && $request->select_state != '') {
      $query->where('state', $request->select_state);
    }
    // Handle country input if provided
    if ($request->has('select_country') && $request->select_country != '') {
      $query->where('country', $request->select_country);
    }


    // Sorting
    $orderColumnIndex = $request->order[0]['column'] ?? 0;
    $orderDirection = $request->order[0]['dir'] ?? 'desc';
    $columns = ['id', 'start_date', 'end_date', 'campaign_name', 'image_code', 'placement', 'status'];
    $orderByColumn = $columns[$orderColumnIndex] ?? 'id';

    // Get total records count
    $totalRecords = $query->count();

    // Apply ordering
    $records = $query->orderBy($orderByColumn, $orderDirection)
      ->limit($request->length)
      ->get();

    $data = $records->map(function ($record) {

      $categoryIds = is_string($record->categories) ? json_decode($record->categories) : $record->categories;
      $categoryNames = BusinessCategory::whereIn('id', $categoryIds)->pluck('name')->toArray();

      // Join the category names into a comma-separated string
      $categoriesString = implode(', ', $categoryNames);
      if ($record->status == 1)
        $status_text = 'BLOCK';
      else
        $status_text = 'ACTIVE';
      return [
        $record->id,
        $categoriesString,
        $record->business_name,
        $record->business_address . " " . $record->city . " " . $record->state . " " . $record->country,
        $record->whatsapp_number,
        $record->mobile_number,
        $record->email,
        "<div class='d-inline-block'>
                <a href='javascript:;' class='btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow' data-bs-toggle='dropdown' aria-expanded='false'>
                    <i class='ti ti-dots ti-md'></i>
                </a>
                <div class='dropdown-menu dropdown-menu-end m-0'>
                    <a href='" . route('business.edit', $record->id) . "' class='dropdown-item'><i class='menu-icon tf-icons ti ti-pencil'></i> Edit</a>
                    <a href='javascript:;' class='dropdown-item' onclick='delete_business_listing(" . $record->id . ")'><i class='menu-icon tf-icons ti ti-trash'></i> Delete</a>
                    <a href='javascript:;' class='dropdown-item' onclick='block_business_listing(" . $record->id . ")'><i class='menu-icon tf-icons ti ti-lock'></i> " . $status_text . "</a>
                </div>
            </div>"
      ];
    });

    return response()->json([
      "draw" => 1,
      "recordsTotal" => $totalRecords,
      "recordsFiltered" => $records->count(),
      "data" => $data
    ]);
  }
  public function exportCsv(Request $request)
  {
    $query = BusinessListing::query();

    // Handle search input
    if ($request->has('search') && $request->search) {
      $searchValue = $request->search;
      $query->where('business_name', 'like', '%' . $searchValue . '%');
    }

    // Handle categories input if provided
    if ($request->has('business_category_id') && $request->business_category_id != '') {
      $searchCat = $request->business_category_id;
      $query->whereRaw('JSON_CONTAINS(categories, ?)', [json_encode($searchCat)]);
    }

    // Handle city input if provided
    if ($request->has('select_city') && $request->select_city != '') {
      $query->where('city', $request->select_city);
    }

    // Handle state input if provided
    if ($request->has('select_state') && $request->select_state != '') {
      $query->where('state', $request->select_state);
    }

    // Handle country input if provided
    if ($request->has('select_country') && $request->select_country != '') {
      $query->where('country', $request->select_country);
    }

    $results = $query->get();

    $headers = [
      'Content-type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename=business_listing.csv',
      'Pragma' => 'no-cache',
      'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
      'Expires' => '0',
    ];

    $columns = ['Business Name', 'Business Address', 'City', 'State', 'Country', 'Whatsapp Number', 'Mobile Number', 'Email', 'Website', 'Description', 'Google Map URL', 'Photos', 'Categories', 'Facebook Link', 'Instagram Link', 'YouTube Link', 'LinkedIn Link', 'Status'];

    $callback = function () use ($results, $columns) {
      $file = fopen('php://output', 'w');

      // Add column headers
      fputcsv($file, $columns);

      foreach ($results as $data) {
        // Decode categories JSON and fetch category names
        $categoryIds = is_string($data->categories) ? json_decode($data->categories, true) : $data->categories;
        $categoryNames = $categoryIds ? BusinessCategory::whereIn('id', $categoryIds)->pluck('name')->implode(', ') : '';

        $statusText = $data->status == 1 ? 'Active' : 'Blocked';

        $row = [
          $data->business_name,
          $data->business_address,
          $data->city,
          $data->state,
          $data->country,
          $data->whatsapp_number,
          $data->mobile_number,
          $data->email,
          $data->website,
          $data->description,
          $data->google_map_url,
          $data->photos,
          $categoryNames, // Converted to a comma-separated string
          $data->facebook_link,
          $data->instagram_link,
          $data->youtube_link,
          $data->linkedin_link,
          $statusText
        ];

        // Write row to CSV
        fputcsv($file, $row);
      }

      fclose($file);
    };

    // Return the streamed response with the headers
    return Response::stream($callback, 200, $headers);
  }


  public function edit($id)
  {
    $listing = BusinessListing::findOrFail($id);

    $categories = BusinessCategory::orderBy('order_no')->get();
    $businessCategories = $categories->where('is_sub_category', false);
    $businessSubCategories = $categories->where('is_sub_category', true);
    $locationMaster = LocationsMaster::all();
    $uniqueCities = LocationsMaster::select('city')->distinct()->pluck('city');
    $uniqueStates = LocationsMaster::select('state')->distinct()->pluck('state');
    $uniqueCountries = LocationsMaster::select('country')->distinct()->pluck('country');
    return view('content.business-listing.edit-business-listing', compact('listing', 'uniqueCities', 'uniqueStates', 'uniqueCountries', 'businessCategories', 'businessSubCategories', 'locationMaster'));
  }

  public function block($id)
  {
    $businessListing = BusinessListing::find($id);

    if (!$businessListing) {
      return response()->json(['success' => false, 'message' => 'Business Listing not found.']);
    }

    try {
      // Toggle the status: If it's 1 (blocked), change to 0 (active), otherwise change to 1 (blocked)
      $businessListing->status = $businessListing->status == 1 ? 0 : 1;

      // Save the updated status
      $businessListing->save();

      // Prepare the message based on the new status
      $message = $businessListing->status == 0 ? 'Business Listing blocked successfully.' : 'Business Listing activated successfully.';

      return response()->json(['success' => true, 'message' => $message]);
    } catch (Exception $e) {
      return response()->json(['success' => false, 'message' => 'Failed to update Business Listing status.']);
    }
  }
  public function destroy($id)
  {
    $listing = BusinessListing::findOrFail($id);
    try {

      $photos = is_string($listing->photos) ? json_decode($listing->photos, true) : $listing->photos;
      if (is_array($photos)) {
        foreach ($photos as $photo) {
          $fullPath = storage_path('app/public/' . $photo);
          if (file_exists($fullPath)) {
            unlink($fullPath); // Delete the file
          }
        }
      }

      $listing->delete();
      return response()->json(['success' => true, 'message' => 'Business Listing deleted successfully.']);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Failed to delete Business Listing: ' . $e->getMessage()]);
    }
  }
}
