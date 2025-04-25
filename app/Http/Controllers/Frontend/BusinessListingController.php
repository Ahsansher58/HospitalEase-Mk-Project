<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Advertisements;
use App\Models\BusinessCategory;
use App\Models\BusinessListing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BusinessListingController extends Controller
{
  public function index(Request $request, $slug = '')
  {
    // Start the query to get the business listings
    $businessListingsQuery = BusinessListing::where('status', 1);

    // Apply city and location search filters
    if (isset($request->search_by) && $request->search_by) {
      $search_by = strtolower($request->search_by);

      // Fetch matching category IDs
      $categoryIds = BusinessCategory::whereRaw('LOWER(name) LIKE ?', ['%' . $search_by . '%'])->pluck('id')->toArray();

      $businessListingsQuery->where(function ($query) use ($search_by, $categoryIds) {
        $query->whereRaw('LOWER(business_name) LIKE ?', ['%' . $search_by . '%'])
          ->orWhereRaw('LOWER(city) LIKE ?', ['%' . $search_by . '%']);

        // If there are matching category IDs, add a JSON search condition
        if (!empty($categoryIds)) {
          foreach ($categoryIds as $categoryId) {
            $query->orWhereRaw("JSON_CONTAINS(categories, '\"$categoryId\"')");
          }
        }
      });
    }


    if (isset($request->search_by_location) && $request->search_by_location) {
      $search_by_location = strtolower($request->search_by_location);
      $businessListingsQuery->whereRaw('LOWER(business_address) LIKE ?', ['%' . $search_by_location . '%']);
    }

    // Search by Category ID (Stored in JSON format)
    if (isset($slug) && $slug != '') {
      $search_by_category = strtolower(str_replace('-', ' ', $slug));
      $catname = strtolower(str_replace('-', ' ', $slug));

      $category = BusinessCategory::where('name', $catname)->first();
      $cat_id = $category->id;
      $businessListingsQuery->where(function ($query) use ($cat_id) {
        $query->whereRaw("JSON_CONTAINS(categories, '\"{$cat_id}\"')");
      });
    }

    // Search by Category ID (Stored in JSON format)
    if (isset($request->search_by_category) && $request->search_by_category) {
      $search_by_category = strtolower($request->search_by_category);

      // Check if 'categories' JSON column contains the category ID
      $businessListingsQuery->where(function ($query) use ($search_by_category) {
        $query->whereRaw("JSON_CONTAINS(categories, '\"{$search_by_category}\"')");
      });
    }

    // Paginate the filtered business listings
    $businessListings = $businessListingsQuery->orderBy('id', 'desc')->paginate(10);

    // Add category name to each business listing
    $businessListings->getCollection()->transform(function ($businessListing) {
      // Assuming getBusinessCategory() returns a category name
      $businessListing->cat_name = $businessListing->getBusinessCategory();
      return $businessListing;
    });

    // Total count for pagination
    $totalCount = $businessListings->total();

    /* Sidebar Categories */
    $allCategories = BusinessCategory::orderBy('order_no', 'desc')->get();
    $categories = [];
    foreach ($allCategories as $category) {
      if ($category->is_sub_category == 0) {
        $categories[$category->id] = [
          'id' => $category->id,
          'name' => $category->name,
          'subcategories' => [],
        ];
      } else {
        if (isset($categories[$category->main_category_id])) {
          $categories[$category->main_category_id]['subcategories'][] = [
            'id' => $category->id,
            'name' => $category->name,
          ];
        }
      }
    }
    /* Sidebar Categories */

    return view('frontend.content.business-listing', compact('businessListings', 'totalCount', 'categories'));
  }



  public function show($slug)
  {
    $business = BusinessListing::where('status', 1)
      ->where('slug', $slug)
      ->first();

    if ($business) {
      $business->cat_name = $business->getBusinessCategory();
    }
    $currentDate = Carbon::now()->toDateString();
    $advertisement = Advertisements::where('placement', 2)->where('status', 1)->whereDate('start_date', '<=', $currentDate)
      ->whereDate('end_date', '>=', $currentDate)->inRandomOrder()->first();
    return view('frontend.content.business-listing-single', compact('business', 'advertisement'));
  }
}
