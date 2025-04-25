<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MainCategory;
use Illuminate\Validation\Rule;


class MainCategories extends Controller
{
  public function index()
  {
    return view('content.settings.main-categories');
  }
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255|unique:main_categories,name',
      'sort_order' => 'required|integer|min:1',
    ]);

    MainCategory::create($validated);

    return redirect()->route('main-categories')->with('success', 'Main Category created successfully.');
  }


  public function edit($id)
  {
    $category = MainCategory::findOrFail($id);
    $category = MainCategory::find($id);
    return response()->json($category);
  }

  public function update(Request $request, $id)
  {
    $category = MainCategory::findOrFail($id);

    $validated = $request->validate([
      'name' => [
        'required',
        'string',
        'max:255',
        // Ensure the name is unique, but ignore the current record's name
        Rule::unique('main_categories', 'name')->ignore($category->id),
      ],
      'sort_order' => 'required|integer|min:1',
    ]);

    $category->update($validated);

    return redirect()->route('main-categories')->with('success', 'Main Category updated successfully.');
  }

  public function destroy($id)
  {
    $mainCategory = MainCategory::findOrFail($id);
    if ($mainCategory->mandatory == 1) {
      return response()->json(['success' => false, 'message' => 'This category cannot be deleted as it is marked mandatory.']);
    }
    $mainCategory->delete();
    return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
  }

  public function table_json(Request $request)
  {
    $result = MainCategory::orderBy('sort_order', 'asc')->get();
    $data = [];
    foreach ($result as $item) {
      // Add main category to the data array
      $data[] = [
        $item->id,
        $item->sort_order,
        $item->name,
        "<div class='action-btn'>
            <a href='javascript:;' onclick='edit_main_category({$item->id})' class='action-item'>
                <i class='menu-icon tf-icons ti ti-pencil'></i>
            </a>"
          . ($item->mandatory != 1 ?
            "<a href='javascript:;' onclick='delete_main_category({$item->id})' class='action-item'>
                <i class='menu-icon tf-icons ti ti-trash'></i>
            </a>" : "") .
          "</div>",
      ];
    }

    return response()->json([
      "draw" => $request->draw,
      "recordsTotal" => count($data), // Total records includes both categories and subcategories
      "recordsFiltered" => count($data), // Adjusted if filtering is applied
      "data" => $data,
    ]);
  }
}
