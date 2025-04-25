<?php

namespace App\Http\Controllers\business_listing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BusinessListing;
use App\Models\BusinessCategory;
use App\Models\LocationsMaster;
use Illuminate\Support\Str;

class AddBusinessListing extends Controller
{
  public function index()
  {
    $categories = BusinessCategory::orderBy('order_no')->get();
    $businessCategories = $categories->where('is_sub_category', false);
    $businessSubCategories = $categories->where('is_sub_category', true);
    $locationMaster = LocationsMaster::all();
    $uniqueCities = LocationsMaster::select('city')->distinct()->pluck('city');
    $uniqueStates = LocationsMaster::select('state')->distinct()->pluck('state');
    $uniqueCountries = LocationsMaster::select('country')->distinct()->pluck('country');
    return view('content.business-listing.add-business-listing', compact('uniqueCities', 'uniqueStates', 'uniqueCountries', 'businessCategories', 'businessSubCategories', 'locationMaster'));
  }


  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'business_name' => 'required|string|max:255',
      'business_address' => 'required|string',
      'city' => 'required|string',
      'state' => 'required|string',
      'country' => 'required|string',
      'mobile_number' => 'required|numeric',
      'email' => 'required|email',
      'categories' => 'required|array',
      'description' => 'nullable|string',
      'google_map_url' => 'nullable|url',
      'facebook_link' => 'nullable|url',
      'instagram_link' => 'nullable|url',
      'youtube_link' => 'nullable|url',
      'linkedin_link' => 'nullable|url',
      'imageJson' => 'nullable|string',
      'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $data = $request->except(['imageJson', 'banner_image']);

    if ($request->has('imageJson')) {
      $data['photos'] = $request->input('imageJson');
    }

    if ($request->hasFile('banner_image')) {
      $imagePath = $request->file('banner_image')->store('uploads/banner_images', 'public');
      $data['banner_image'] = $imagePath;
    }

    $slug = Str::slug($request->business_name);
    $slugExists = BusinessListing::where('slug', $slug)->exists();
    if ($slugExists) {
      $slug = $slug . '-' . time();
    }
    $data['slug'] = $slug;

    $res = BusinessListing::create($data);

    // Redirect with success message
    return redirect()->route('manage-business-listing')
      ->with('success', 'Business listing created successfully.');
  }




  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'business_name' => 'required|string|max:255',
      'business_address' => 'required|string',
      'city' => 'required|string',
      'state' => 'required|string',
      'country' => 'required|string',
      'mobile_number' => 'required|numeric',
      'email' => 'required|email',
      'categories' => 'required|array',
      'description' => 'nullable|string',
      'google_map_url' => 'nullable|url',
      'facebook_link' => 'nullable|url',
      'instagram_link' => 'nullable|url',
      'youtube_link' => 'nullable|url',
      'linkedin_link' => 'nullable|url',
      'imageJson' => 'nullable|string',
      'removeImageJson' => 'nullable|string',
      'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Add validation for banner_image
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    // Find the business listing by ID
    $listing = BusinessListing::findOrFail($id);

    // Get data except imageJson, removeImageJson, and banner_image
    $data = $request->except(['imageJson', 'removeImageJson', 'banner_image', 'slug']);

    // Handle imageJson if exists (images to be saved)
    if ($request->has('imageJson')) {
      $data['photos'] = $request->input('imageJson');
    }

    // Handle images to be removed (removeImageJson)
    if ($request->has('removeImageJson') && !empty($request->input('removeImageJson'))) {
      $removePaths = json_decode($request->input('removeImageJson'), true);

      // Delete the images from storage
      foreach ($removePaths as $path) {
        $filePath = storage_path('app/public/' . $path);
        if (file_exists($filePath)) {
          unlink($filePath); // Delete the file
        }
      }
    }

    // Handle banner image upload if exists
    if ($request->hasFile('banner_image')) {
      // Store the banner image and get its path
      $imagePath = $request->file('banner_image')->store('uploads/banner_images', 'public');
      // Add the image path to the data
      $data['banner_image'] = $imagePath;
    }

    // Keep the existing slug (do not update it)
    $data['slug'] = $listing->slug; // Use the current slug value

    // Update the business listing with the new data (excluding slug update)
    $listing->update($data);

    return redirect()->route('manage-business-listing')
      ->with('success', 'Business listing updated successfully.');
  }


  public function uploadPhotos(Request $request)
  {
    if ($request->hasFile('photos')) {
      $image_file = $request->file('photos');
      if (is_array($image_file)) {
        $image_file = $image_file[0];
      }
      $path = $image_file->store('uploads/business-listing', 'public');
      return $path;
    }
  }
  public function deleteUploadedImage(Request $request)
  {
    $request->validate([
      'filePath' => 'required|string',
    ]);

    $filePath = $request->input('filePath');
    $fullPath = storage_path('app/public/' . $filePath);
    echo $fullPath;
    if (file_exists($fullPath)) {
      if (unlink($fullPath)) {
        return response()->json(['message' => 'File deleted successfully.']);
      } else {
        return response()->json(['message' => 'Failed to delete the file.'], 500);
      }
    } else {
      return response()->json(['message' => 'File not found.'], 404);
    }
  }
}
