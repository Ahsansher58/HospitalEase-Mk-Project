<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FamilyHealthHistory; // Import the model for the allergic_medicine table
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyHealthHistoryController extends Controller
{
  public function familyHealthHistory()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();
    return view('frontend.content.users.user_family_health_history', compact('user'));
  }

  public function store(Request $request)
  {

    // Validate the fields
    $validatedData = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string'
    ]);

    // Create a new record with the authenticated user's ID
    FamilyHealthHistory::create([
      'user_id' => Auth::id(),
      'title' => $validatedData['title'],
      'description' => $validatedData['description'],
    ]);

    // Redirect back with success message
    return redirect()->route('user.familyHealthHistory')->with('success', 'Family Health History added successfully');
  }

  public function editGetFamilyHealthHistory($id)
  {
    $fhh = FamilyHealthHistory::find($id);
    return response()->json($fhh);
  }

  public function updateFamilyHealthHistory(Request $request, $id)
  {
    $fhh = FamilyHealthHistory::find($id);

    $validated = $request->validate([
      'title' => 'required|string',
      'description' => 'required|string',
    ]);

    if ($validated) {
      $fhh->update($validated);

      return response()->json(['message' => 'Family Health History updated successfully']);
    }

    return response()->json(['errors' => 'All fields are required'], 422);
  }


  public function deleteFamilyHealthHistory($id)
  {
    $fhh = FamilyHealthHistory::find($id);

    if (!$fhh) {
      return response()->json(['message' => 'Family Health History not found.'], 404);
    }

    $fhh->delete();

    return response()->json(['message' => 'Family Health History deleted successfully']);
  }

  public function getFamilyHealthHistory()
  {
    // Fetch fhh data from the database (example)
    $fhh = FamilyHealthHistory::where('user_id', Auth::id())->orderBy('id', 'desc')->get();


    // Format the data to match the structure for DataTable
    $data = $fhh->map(function ($fhh_data) {
      return [
        $fhh_data->id,
        $fhh_data->title,
        $fhh_data->description,
        "<button class='btn p-0 border-0' onclick='edit_popup(" . $fhh_data->id . ")'><img src='" . asset('assets/frontend/images/icons/edit.svg') . "' /></button>
             <button class='btn p-0 border-0' onclick='delete_fhh(" . $fhh_data->id . ")'><img src='" . asset('assets/frontend/images/icons/delete.svg') . "' /></button>"
      ];
    });

    // Return the data as JSON
    return response()->json(['data' => $data]);
  }
}
