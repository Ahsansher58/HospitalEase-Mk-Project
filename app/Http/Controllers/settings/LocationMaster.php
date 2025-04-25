<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LocationsMaster;

class LocationMaster extends Controller
{
  public function index()
  {
    return view('content.settings.locality-city-state-country-master');
  }
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    // Decode the JSON values from the request
    $locality = json_decode($request->input('locality'), true)[0]['value'] ?? null;
    $city = json_decode($request->input('city'), true)[0]['value'] ?? null;
    $state = json_decode($request->input('state'), true)[0]['value'] ?? null;
    $country = json_decode($request->input('country'), true)[0]['value'] ?? null;

    // Initialize an array to hold error messages
    $errors = [];

    // Validate each field individually
    if (is_null($locality)) {
      $errors['locality'] = 'The locality field is required.';
    }
    if (is_null($city)) {
      $errors['city'] = 'The city field is required.';
    }
    if (is_null($state)) {
      $errors['state'] = 'The state field is required.';
    }
    if (is_null($country)) {
      $errors['country'] = 'The country field is required.';
    }

    // If there are any errors, redirect back with the errors
    if (!empty($errors)) {
      return redirect()->back()->withErrors($errors)->withInput();
    }

    // Create a new LocationMaster entry
    LocationsMaster::create([
      'locality' => $locality,
      'city' => $city,
      'state' => $state,
      'country' => $country,
    ]);

    return redirect()->route('locality-city-state-country-master')->with('success', 'Location added successfully.');
  }
  public function edit($id)
  {
    $location = LocationsMaster::find($id);
    return response()->json($location);
  }

  public function update(Request $request, $id)
  {
    // Decode the JSON values from the request
    $locality = json_decode($request->input('locality'), true)[0]['value'] ?? null;
    $city = json_decode($request->input('city'), true)[0]['value'] ?? null;
    $state = json_decode($request->input('state'), true)[0]['value'] ?? null;
    $country = json_decode($request->input('country'), true)[0]['value'] ?? null;

    // Initialize an array to hold error messages
    $errors = [];

    // Validate each field individually
    if (is_null($locality)) {
      $errors['locality'] = 'The locality field is required.';
    }
    if (is_null($city)) {
      $errors['city'] = 'The city field is required.';
    }
    if (is_null($state)) {
      $errors['state'] = 'The state field is required.';
    }
    if (is_null($country)) {
      $errors['country'] = 'The country field is required.';
    }

    // If there are any errors, redirect back with the errors
    if (!empty($errors)) {
      return redirect()->back()->withErrors($errors)->withInput();
    }

    $location = LocationsMaster::find($id);
    $location->update([
      'locality' => $locality,
      'city' => $city,
      'state' => $state,
      'country' => $country,
    ]);

    return redirect()->back()->with('success', 'Loction updated successfully.');
  }

  public function destroy($id)
  {
    $location = LocationsMaster::find($id);
    if (!$location) {
      return response()->json(['success' => false, 'message' => 'Loction not found.']);
    }

    try {
      $location->delete();
      return response()->json(['success' => true, 'message' => 'Loction deleted successfully.']);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Failed to delete location.']);
    }
  }
  public function table_json(Request $request)
  {
    $result = LocationsMaster::all();
    foreach ($result as $item) {
      // Add main category to the data array
      $data[] = [
        $item->id,
        $item->locality,
        $item->city,
        $item->state,
        $item->country,
        "<div class='action-btn'>
                  <a href='javascript:;' onclick='edit_location_master({$item->id})' class='action-item'><i class='menu-icon tf-icons ti ti-pencil'></i></a>
                  <a href='javascript:;' onclick='delete_location_master({$item->id})' class='action-item'><i class='menu-icon tf-icons ti ti-trash'></i></a>
              </div>",
      ];
    }

    return response()->json([
      "draw" => $request->draw,
      "recordsTotal" => count($data), // Total records includes both categories and subcategories
      "recordsFiltered" => count($data), // Adjusted if filtering is applied
      "data" => $data,
    ]);
  }


  public function getCountries()
  {
    $countries = get_locality_country();
    return response()->json($countries);
  }
  public function getStates(Request $request)
  {
    $states = get_locality_state($request->country);
    return response()->json($states);
  }

  public function getCities(Request $request)
  {
    $cities = get_locality_city($request->country, $request->state);
    return response()->json($cities);
  }

  public function getLocalities(Request $request)
  {
    $localities = get_locality($request->country, $request->state, $request->city);
    return response()->json($localities);
  }
}
