<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AllergicMedicine; // Import the model for the allergic_medicine table
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllergicMedicineController extends Controller
{
  public function allergic_medicine()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();
    return view('frontend.content.users.user_allergic_medicine', compact('user'));
  }
  /**
   * Store a newly created allergic medicine in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {

    // Validate the fields
    $validatedData = $request->validate([
      'medicine_name' => 'required|string|max:255',
      'symptoms_reactions' => 'required|string'
    ]);

    // Create a new record with the authenticated user's ID
    AllergicMedicine::create([
      'user_id' => Auth::id(),
      'medicine_name' => $validatedData['medicine_name'],
      'symptoms_reactions' => $validatedData['symptoms_reactions'],
    ]);

    // Redirect back with success message
    return redirect()->route('user.allergicMedicine')->with('success', 'Medicine added successfully');
  }

  /**
   * Show the form for editing the specified allergic medicine.
   *
   * @param  \App\Models\AllergicMedicine  $allergicMedicine
   * @return \Illuminate\Http\Response
   */
  public function editGetMedicines($id)
  {
    $medicine = AllergicMedicine::find($id);
    return response()->json($medicine);
  }

  /**
   * Update the specified allergic medicine in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\AllergicMedicine  $allergicMedicine
   * @return \Illuminate\Http\Response
   */

  public function updateMedicine(Request $request, $id)
  {
    $medicine = AllergicMedicine::find($id);

    $validated = $request->validate([
      'medicine_name' => 'required|string',
      'symptoms_reactions' => 'required|string',
    ]);

    if ($validated) {
      $medicine->update($validated);

      return response()->json(['message' => 'Medicine updated successfully']);
    }

    return response()->json(['errors' => 'All fields are required'], 422);
  }


  public function deleteMedicine($id)
  {
    $medicine = AllergicMedicine::find($id);

    if (!$medicine) {
      return response()->json(['message' => 'Medicine not found.'], 404);
    }

    $medicine->delete();

    return response()->json(['message' => 'Medicine deleted successfully']);
  }

  public function getMedicines()
  {
    // Fetch medicines data from the database (example)
    $medicines = AllergicMedicine::where('user_id', Auth::id())->orderBy('id', 'desc')->get();


    // Format the data to match the structure for DataTable
    $data = $medicines->map(function ($medicine) {
      return [
        $medicine->id,
        $medicine->medicine_name,
        $medicine->symptoms_reactions,
        "<button class='btn p-0 border-0' onclick='edit_popup(" . $medicine->id . ")'><img src='" . asset('assets/frontend/images/icons/edit.svg') . "' /></button>
             <button class='btn p-0 border-0' onclick='delete_medicine(" . $medicine->id . ")'><img src='" . asset('assets/frontend/images/icons/delete.svg') . "' /></button>"
      ];
    });

    // Return the data as JSON
    return response()->json(['data' => $data]);
  }
}
