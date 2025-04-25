<?php

namespace App\Http\Controllers\settings;

use App\Models\BusinessCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessCategories extends Controller
{
  public function index()
  {
    $categories = BusinessCategory::where('is_sub_category', 0)
      ->orderBy('order_no')
      ->get();

    return view('content.settings.business-categories', ['categories' => $categories]);
  }


  public function store(Request $request)
  {
    // Validate the form data
    $validated = $request->validate([
      'main_category_id' => 'nullable|exists:business_categories,id',
      'order_no' => 'required|integer',
      'name' => 'required|string|max:255',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the image
    ]);

    // Handle image upload using Laravel's Storage
    $imagePath = null;
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $imageName = time() . '_' . $image->getClientOriginalName(); // Create a unique name
      $imagePath = $image->storeAs('uploads/business_categories', $imageName, 'public'); // Save in 'public' disk
    }

    // Set is_sub_category based on whether main_category_id is provided
    $isSubCategory = isset($validated['main_category_id']) ? 1 : 0;

    // Insert the new category into the database
    BusinessCategory::create([
      'name' => $validated['name'],
      'main_category_id' => $validated['main_category_id'] ?? null, // Set to null if empty
      'is_sub_category' => $isSubCategory, // Set based on the condition
      'order_no' => $validated['order_no'],
      'image' => $imagePath, // Save the image path if uploaded
    ]);

    // Redirect or return response
    return redirect()->back()->with('success', 'Category added successfully.');
  }


  public function edit($id)
  {
    $category = BusinessCategory::find($id);
    return response()->json($category); // Return category as JSON for AJAX call
  }

  public function update(Request $request, $id)
  {
    // Validate the form data
    $validatedData = $request->validate([
      'main_category_id' => 'nullable|exists:business_categories,id',
      'order_no' => 'required|integer',
      'name' => 'required|string|max:255',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
    ]);

    // Find the existing category
    $category = BusinessCategory::find($id);

    // Handle image upload if provided
    if ($request->hasFile('image')) {
      // Delete the old image if it exists
      if ($category->image && Storage::exists($category->image)) {
        Storage::delete($category->image);
      }

      // Handle new image upload
      $image = $request->file('image');
      $imageName = time() . '_' . $image->getClientOriginalName(); // Create a unique name for the image
      $imagePath = $image->storeAs('uploads/business_categories', $imageName, 'public'); // Store the image

      // Add image path to validated data
      $validatedData['image'] = $imagePath;
    }

    // Update the category with validated data
    $category->update($validatedData);

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Category updated successfully.');
  }

  public function destroy($id)
  {
    // Find the category by ID
    $category = BusinessCategory::find($id);

    // Check if the category exists
    if (!$category) {
      return response()->json(['success' => false, 'message' => 'Category not found.']);
    }

    try {
      // Prepare the success message
      $msg = 'Category deleted successfully.';

      // Delete the associated image if it exists
      if ($category->image && Storage::exists($category->image)) {
        Storage::delete($category->image); // Delete the image from storage
      }

      // Check if the category is a main category (not a subcategory)
      if ($category->is_sub_category == 0) {
        $msg = 'Category and its subcategories deleted successfully.';

        // Delete any subcategories linked to this main category
        BusinessCategory::where('main_category_id', $category->id)->delete();
      }

      // Delete the main category
      $category->delete();

      // Return success response
      return response()->json(['success' => true, 'message' => $msg]);
    } catch (\Exception $e) {
      // Catch any errors and return a failure response
      return response()->json(['success' => false, 'message' => 'Failed to delete category.']);
    }
  }
  public function viewAll()
  {
    $businessCategory = BusinessCategory::where('is_sub_category', 0)->orderBy('order_no', 'ASC')->get();
    return view('frontend.content.business-categories-all', compact(
      'businessCategory'
    ));
  }

  public function table_json(Request $request)
  {
    // Get all main categories, ordered by 'order_no'
    $categories = BusinessCategory::where('is_sub_category', 0)
      ->orderBy('order_no')
      ->get();

    $data = [];

    foreach ($categories as $category) {
      // Add main category to the data array
      $category_name = $category->name;
      $category_image = $category->image ? Storage::url($category->image) : asset('storage/uploads/business_categories/default_cat.png');
      $data[] = [
        $category->id,
        "<div class='category-image'><img src='{$category_image}' alt='{$category_name}' width='50' height='50'></div>", // Add image in the second column
        "<div class='sort-order'>{$category->order_no}</div>",
        $category_name,
        '', // Subcategory column is blank for main categories
        "<div class='action-btn'>
                <a href='javascript:;' onclick='edit_business_category({$category->id})' class='action-item'>
                    <i class='menu-icon tf-icons ti ti-pencil'></i>
                </a>
                <a href='javascript:;' onclick='delete_business_category({$category->id})' class='action-item'>
                    <i class='menu-icon tf-icons ti ti-trash'></i>
                </a>
            </div>",
      ];

      // Get the subcategories for the current main category
      $subCategories = BusinessCategory::where('main_category_id', $category->id)->get();

      foreach ($subCategories as $subCategory) {
        // Add subcategory to the data array
        $subcategory_image = $subCategory->image ? Storage::url($subCategory->image) : asset('storage/uploads/business_categories/default_cat.png');

        $data[] = [
          $subCategory->id,
          "<div class='category-image'><img src='{$subcategory_image}' alt='{$subCategory->name}' width='50' height='50'></div>", // Add image for subcategory
          "<div class='sort-order'>{$subCategory->order_no}</div>",
          $category_name,
          $subCategory->name,
          "<div class='action-btn'>
                    <a href='javascript:;' onclick='edit_business_category({$subCategory->id})' class='action-item'>
                        <i class='menu-icon tf-icons ti ti-pencil'></i>
                    </a>
                    <a href='javascript:;' onclick='delete_business_category({$subCategory->id})' class='action-item'>
                        <i class='menu-icon tf-icons ti ti-trash'></i>
                    </a>
                </div>",
        ];
      }
    }

    return response()->json([
      "draw" => $request->draw,
      "recordsTotal" => count($data), // Total records includes both categories and subcategories
      "recordsFiltered" => count($data), // Adjusted if filtering is applied
      "data" => $data,
    ]);
  }
}
