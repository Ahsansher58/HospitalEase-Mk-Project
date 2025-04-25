<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AllergicFood;
use Illuminate\Support\Facades\Auth;

class AllergicFoodController extends Controller
{

  public function allergic_food()
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    $user = Auth::user();
    return view('frontend.content.users.user_allergic_food', compact('user'));
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
    AllergicFood::create([
      'user_id' => Auth::id(),
      'medicine_name' => $validatedData['medicine_name'],
      'symptoms_reactions' => $validatedData['symptoms_reactions'],
    ]);

    // Redirect back with success message
    return redirect()->route('user.allergicFood')->with('success', 'Medicine added successfully');
  }

  /**
   * Show the form for editing the specified allergic medicine.
   *
   * @param  \App\Models\AllergicFood  $AllergicFood
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $medicine = AllergicFood::find($id);
    return response()->json($medicine);
  }

  /**
   * Update the specified allergic medicine in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\AllergicFood  $AllergicFood
   * @return \Illuminate\Http\Response
   */

  public function update(Request $request, $id)
  {
    $medicine = AllergicFood::find($id);

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


  public function delete($id)
  {
    $medicine = AllergicFood::find($id);

    if (!$medicine) {
      return response()->json(['message' => 'Medicine not found.'], 404);
    }

    $medicine->delete();

    return response()->json(['message' => 'Medicine deleted successfully']);
  }

  public function get()
  {
    // Fetch medicines data from the database (example)
    $medicines = AllergicFood::where('user_id', Auth::id())->orderBy('id', 'desc')->get();

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
