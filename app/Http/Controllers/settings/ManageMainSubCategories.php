<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MainCategory;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ManageMainSubCategories extends Controller
{
  public function index()
  {
    $categories = MainCategory::all();
    $subcategories = SubCategory::all();
    return view('content.settings.manage-main-sub-categories', ['categories' => $categories, 'subcategories' => $subcategories]);
  }

  public function create()
  {
    $categories = MainCategory::all();
    return view('content.settings.add-main-sub-categories', ['categories' => $categories]);
  }

  public function store(Request $request)
  {
    // Define validation rules
    $validationRules = [
      'mainCategory' => 'required|exists:main_categories,id',
      'sort_order' => 'required|integer|min:1',
      'sub_category_name' => 'required|string|max:255',
      'sub_category_required' => 'required|in:0,1',
      'type' => 'required|integer|between:1,10',
    ];

    // Variable to hold the option value
    $option = '';

    if ($request->type && !in_array($request->type, [5, 6, 7, 8, 9, 10])) {
      $validationRules["option$request->type"] = 'required|string|max:255';
      $option = $request->input("option$request->type");
    }


    if ($request->has('group-a') && in_array($request->type, [3, 4])) {
      foreach ($request->input('group-a') as $key => $fileInput) {
        $validationRules["group-a.$key.file$request->type"] = 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048'; // Adjust max size as needed
      }
    }


    // Validate the incoming request data
    $request->validate($validationRules);

    // Create a new SubCategory instance
    $subCategory = new SubCategory();
    $subCategory->main_category_id = $request->mainCategory;
    $subCategory->sort_order = $request->sort_order;
    $subCategory->sub_category_name = $request->sub_category_name;
    $subCategory->sub_category_required = $request->sub_category_required;
    $subCategory->type = $request->type;
    $subCategory->option = $option; // Store the option value

    // Handle different types of options based on the selected type
    if (in_array($request->type, [3, 4])) {
      $filesData = [];
      if ($request->has('group-a')) {
        $groupAInputs = $request->input('group-a');

        // Loop through the group-a input array
        foreach ($request->file('group-a') as $key => $fileInput) {
          // Ensure the file is present and valid
          if (isset($fileInput['file' . $request->type]) && $fileInput['file' . $request->type]->isValid()) {
            // Define the folder path for storage
            $folderPath = public_path('storage/mainsubcategory/' . $request->type);

            // Check if folder exists, if not, create it
            if (!file_exists($folderPath)) {
              mkdir($folderPath, 0755, true); // Create the folder with appropriate permissions
            }

            // Generate a unique filename
            $originalName = $fileInput['file' . $request->type]->getClientOriginalName(); // Get the original file name
            $extension = $fileInput['file' . $request->type]->getClientOriginalExtension(); // Get the file extension
            $uniqueName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '_' . uniqid() . '.' . $extension; // Create a unique name

            // Move the file to the defined folder with the unique name
            $fileInput['file' . $request->type]->move($folderPath, $uniqueName);

            // Store additional information in an array
            $fileEntry = [
              'file' => 'storage/mainsubcategory/' . $request->type . '/' . $uniqueName, // Adjust path to be accessible via storage
              'name' => $groupAInputs[$key]['name' . $request->type],
            ];

            if ($request->type == 8) {
              $fileEntry['position'] = $groupAInputs[$key]['position' . $request->type];
            }

            $filesData[] = $fileEntry;
          }
        }

        // Convert files data to JSON and save to the subcategory
        $subCategory->value = json_encode($filesData);
      }
    }

    $subCategory->save();

    return redirect()->route('manageMainSubCategories')->with('success', 'Subcategory added successfully.');
  }
  public function update(Request $request, $id)
  {
    // Find the existing subcategory
    $subCategory = SubCategory::findOrFail($id);

    // Define validation rules
    $validationRules = [
      'mainCategory' => 'required|exists:main_categories,id',
      'sort_order' => 'required|integer|min:1',
      'sub_category_name' => 'required|string|max:255',
      'sub_category_required' => 'required|in:0,1',
      'type' => 'required|integer|between:1,10',
    ];


    // Variable to hold the option value
    $option = '';

    if ($request->type && !in_array($request->type, [5, 6, 7, 8, 9, 10])) {
      $validationRules["option$request->type"] = 'required|string|max:255';
      $option = $request->input("option$request->type");
    }

    if ($request->has('group-a') && in_array($request->type, [3, 4])) {
      foreach ($request->input('group-a') as $key => $fileInput) {
        $validationRules["group-a.$key.file$request->type"] = 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048'; // Use nullable for update
      }
    }

    // Validate the incoming request data
    $request->validate($validationRules);

    // Update SubCategory instance
    $subCategory->main_category_id = $request->mainCategory;
    $subCategory->sort_order = $request->sort_order;
    $subCategory->sub_category_name = $request->sub_category_name;
    $subCategory->sub_category_required = $request->sub_category_required;
    $subCategory->type = $request->type;
    $subCategory->option = $option; // Update the option value

    // Handle different types of options based on the selected type
    if (in_array($request->type, [3, 4])) {
      $filesData = json_decode($subCategory->value, true) ?: []; // Decode existing files if any
      $filesData_new = [];
      if ($request->has('group-a')) {
        $groupAInputs = $request->input('group-a');

        // Loop through the group-a input array
        foreach ($groupAInputs as $key => $groupAInput) {
          $fileEntry = []; // Initialize the file entry for each group-a input

          // Check if the current group-a input has a file for the current type
          if ($request->file("group-a.$key.file$request->type") != '' && $request->file("group-a.$key.file$request->type")->isValid()) {
            // Get the uploaded file
            $fileInput = $request->file("group-a.$key.file$request->type");
            // Define the folder path for storage
            $folderPath = public_path('storage/mainsubcategory/' . $request->type);

            // Check if folder exists; if not, create it
            if (!file_exists($folderPath)) {
              mkdir($folderPath, 0755, true); // Create the folder with appropriate permissions
            }

            // Generate a unique filename
            $originalName = $fileInput->getClientOriginalName(); // Get the original file name
            $extension = $fileInput->getClientOriginalExtension(); // Get the file extension
            $uniqueName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '_' . uniqid() . '.' . $extension; // Create a unique name

            if (isset($filesData[$key]['file'])) {
              $oldFilePath = public_path($filesData[$key]['file']);
              if (file_exists($oldFilePath)) {
                unlink($oldFilePath); // Delete the old file
              }
            }

            // Move the file to the defined folder with the unique name
            $fileInput->move($folderPath, $uniqueName);

            // Store file path in the entry
            $fileEntry['file'] = 'storage/mainsubcategory/' . $request->type . '/' . $uniqueName; // Adjust path to be accessible via storage
          } else if (isset($groupAInput['name' . $request->type])) {
            if (!isset($key)) {
              return redirect()->back()->withErrors(['file_upload_error' => 'Error: you need to upload image/icon.']);
            } else {
              $fileEntry['file'] = array_column($filesData, 'file')[$key];
            }
          }

          // Add additional information from group-a inputs
          if (isset($groupAInput['name' . $request->type])) {
            $fileEntry['name'] = $groupAInput['name' . $request->type];
          }
          if (isset($groupAInput['position' . $request->type]) && $request->type == 8) {
            $fileEntry['position'] = $groupAInput['position' . $request->type];
          }

          // Check if the entry already exists based on some key (e.g., file name or another unique identifier)
          $existingKey = $fileEntry['file'] ?? null; // Assuming 'file' is the key to check for uniqueness

          if ($existingKey) {
            // Check if the key exists in filesData
            $keyIndex = array_search($existingKey, array_column($filesData, 'file'));

            if ($keyIndex !== false) {
              // Update existing entry
              $filesData_new[] = array_merge($filesData[$keyIndex], $fileEntry);
            } else {
              // Add new entry
              $filesData_new[] = $fileEntry; // Add the file entry to the files data
            }
          } elseif (!empty($fileEntry)) {
            // Add new entry if there's a file
            $filesData_new[] = $fileEntry; // Add the file entry to the files data
          }
        }

        // Convert files data to JSON and save to the subcategory
        $subCategory->value = json_encode($filesData_new);
      }
    }

    $subCategory->save();

    return redirect()->route('manageMainSubCategories')->with('success', 'Subcategory updated successfully.');
  }

  public function edit($id)
  {
    $type_values = config('subcategorytype.subcategory_type');
    $mainsubcategory = SubCategory::with(['mainCategory'])->where('id', $id)->first()->toArray();

    $categories = MainCategory::all();
    return view('content.settings.add-main-sub-categories', ['categories' => $categories, 'editMainSubCat' => $mainsubcategory, 'type_values' => $type_values]);
  }

  public function destroy($id)
  {
    try {

      $subcategory = SubCategory::findOrFail($id);

      // Check if the subcategory is mandatory
      if ($subcategory->mandatory == 1) {
        return response()->json([
          'success' => false,
          'message' => 'This subcategory cannot be deleted as it is marked mandatory.'
        ]);
      }

      // Check if the type requires file deletion (e.g., type 3, 4, or 8)
      if (in_array($subcategory->type, [3, 4, 8])) {
        // Decode JSON data to access file information
        $filesData = json_decode($subcategory->value, true);

        // Loop through files data to delete each file
        if (is_array($filesData)) {
          foreach ($filesData as $fileData) {
            if (isset($fileData['file'])) {
              $filePath = public_path($fileData['file']);

              // Check if file exists and delete it
              if (File::exists($filePath)) {
                File::delete($filePath);
              }
            }
          }
        }
      }

      // Delete the subcategory record from the database
      $subcategory->delete();

      // Return a success response
      return response()->json([
        'success' => true,
        'message' => 'Subcategory deleted successfully, along with associated files (if any).'
      ]);
    } catch (\Exception $e) {
      // Handle any errors that occur during the process
      return response()->json([
        'success' => false,
        'message' => 'An error occurred while trying to delete the subcategory.',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function table_json(Request $request)
  {
    $type_values = config('subcategorytype.subcategory_type');

    // Filter based on category_id and subcategory_id
    $query = SubCategory::with(['mainCategory']);

    if ($request->has('main_cat_id') && $request->main_cat_id != '') {
      $query->where('main_category_id', $request->main_cat_id);
    }
    if ($request->has('sub_cat_id') && $request->sub_cat_id != '') {
      $query->where('id', $request->sub_cat_id);
    }
    if ($request->has('search') && $request->search != '') {
      $searchValue = $request->search;

      // Filter by sub_category_name
      $query->where('sub_category_name', 'like', '%' . $searchValue . '%');

      // Filter by mainCategory name
      $query->orWhereHas('mainCategory', function ($q) use ($searchValue) {
        $q->where('main_categories.name', 'like', '%' . $searchValue . '%');
      });
    }
    $query->orderBy('sort_order', 'asc');

    $result = $query->get();
    $data = [];

    foreach ($result as $item) {
      $option_value = '';

      // Check item type and handle the option display accordingly
      if ($item->type == 1 || $item->type == 2) {
        $option_array = explode(',', $item->option);
        $option_value = '';

        foreach ($option_array as $key => $option_item) {
          if ($key == 0 || $key == 1) {
            // Display first two options as buttons
            $option_value .= "<span class='option-cs btn btn-secondary me-2'>" . $option_item . "</span>";
          } elseif ($key == 2) {
            // For the third option, create a dropdown for remaining options
            $option_value .= "<div class='d-inline-block btn btn-secondary p-0 me-2'>
                        <a href='javascript:;' class='btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow' data-bs-toggle='dropdown' aria-expanded='false'>2+</a>
                        <div class='dropdown-menu dropdown-menu-end m-0'>";
          }

          if ($key >= 2) {
            // Add remaining options to dropdown
            $option_value .= "<a href='javascript:;' class='dropdown-item'>" . $option_item . "</a>";
          }
        }

        if (count($option_array) > 2) {
          // Close the dropdown div if more than two options exist
          $option_value .= "</div> </div>";
        }
      } else {
        // For other types, use the full option value
        $option_value = $item->option;
      }

      $data[] = [
        $item->id,
        $item->sort_order,
        $item->mainCategory->name,
        $item->sub_category_name,
        isset($type_values[$item->type]) ? $type_values[$item->type] : 'Unknown Type',
        $option_value,
        "<div class='d-inline-block'>
            <a href='javascript:;' class='btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow' data-bs-toggle='dropdown' aria-expanded='false'>
                <i class='ti ti-dots ti-md'></i>
            </a>
            <div class='dropdown-menu dropdown-menu-end m-0'>
                <a href='" . route('manageMainSubCategories.edit', $item->id) . "' class='dropdown-item'>
                    <i class='menu-icon tf-icons ti ti-pencil'></i> Edit
                </a>"
          . ($item->mandatory != 1 ?
            "<a href='javascript:;' class='dropdown-item' onclick='delete_main_subcategory({$item->id})'>
                    <i class='menu-icon tf-icons ti ti-trash'></i> Delete
                </a>" : "") .
          "</div>
        </div>"
      ];
    }

    return response()->json([
      "draw" => $request->draw,
      "recordsTotal" => count($data), // Total records based on the filter
      "recordsFiltered" => count($data), // Adjusted if filtering is applied
      "data" => $data,
    ]);
  }
}
