<?php

namespace App\Http\Controllers\advertisements;

use App\Models\Advertisements;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertisementsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AddAdvertisements extends Controller
{
  public function index()
  {
    $places = config('adsplaces.places');
    return view('content.advertisements.add-advertisements', compact('places'));
  }

  public function store(AdvertisementsRequest $request)
  {
    // Validate the request data
    $validatedData = $request->validated();

    // Initialize image code variable
    $image_code = null;

    // Handle file upload if the option is 1
    if ($validatedData['option'] == 1) {
      if ($request->filled('image_blob')) {
        $blob = $validatedData['image_blob'];

        // Validate if the blob is a valid image
        if (!preg_match('/^data:image\/(?<type>.+);base64,(?<data>.+)$/', $blob, $matches)) {
          return redirect()->back()->withErrors(['image_blob' => 'Invalid image blob format.']);
        }

        // Check the file type
        $imageType = strtolower($matches['type']);
        $validTypes = ['jpeg', 'jpg', 'png', 'gif'];

        if (!in_array($imageType, $validTypes)) {
          return redirect()->back()->withErrors(['image_blob' => 'Invalid image type. Allowed types: ' . implode(', ', $validTypes)]);
        }

        // Convert the blob to a file
        $fileName = 'advertisement_' . time() . '.' . $imageType; // Use the correct file extension
        $directoryPath = public_path('storage/advertisements/');

        // Check if the directory exists, if not, create it
        if (!is_dir($directoryPath)) {
          // Create the directory with write permissions (0755)
          mkdir($directoryPath, 0755, true);
        }

        // Define the full file path
        $filePath = $directoryPath . $fileName;

        // Decode the base64 blob (remove the data type part)
        $imageData = base64_decode($matches['data']);

        // Save the file
        if (file_put_contents($filePath, $imageData)) {
          $image_code = 'advertisements/' . $fileName; // Save path for database
        } else {
          return redirect()->back()->withErrors(['image_blob' => 'Failed to save the image.']);
        }
      } else {
        return redirect()->back()->withErrors(['image_blob' => 'Image is required when selecting this option.']);
      }
    } else if ($validatedData['option'] == 2) {
      $image_code = $validatedData['image_code']; // Directly use the image code
    }

    // Create and save the advertisement
    Advertisements::create([
      'campaign_name' => $validatedData['campaign_name'],
      'placement' => $validatedData['placement'],
      'option' => $validatedData['option'],
      'image_code' => $image_code, // Save the image path or code here
      'start_date' => $validatedData['start_date'],
      'end_date' => $validatedData['end_date'],
    ]);

    // Return a success response or redirect
    return redirect()->route('manage-advertisements')->with('success', 'Advertisement added successfully');
  }

  public function update(Request $request, $id)
  {
    // Validate the request data
    $validatedData = $request->validate([
      'campaign_name' => 'required|string|max:255',
      'placement' => 'required',
      'option' => 'required|in:1,2',
      'image_code' => 'required_if:option,2',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
      'image_blob' => 'nullable',
    ]);

    // Find the advertisement by ID
    $advertisement = Advertisements::findOrFail($id);

    // Handle file upload only if the option is 1 and a new image is provided
    if ($validatedData['option'] == 1) {
      $image_code = $advertisement->image_code;

      if ($request->filled('image_blob')) {
        $blob = $validatedData['image_blob'];

        // Validate if the blob is a valid image
        if (!preg_match('/^data:image\/(?<type>.+);base64,(?<data>.+)$/', $blob, $matches)) {
          return redirect()->back()->withErrors(['image_blob' => 'Invalid image blob format.']);
        }

        // Check the file type
        $imageType = strtolower($matches['type']);
        $validTypes = ['jpeg', 'jpg', 'png', 'gif'];

        if (!in_array($imageType, $validTypes)) {
          return redirect()->back()->withErrors(['image_blob' => 'Invalid image type. Allowed types: ' . implode(', ', $validTypes)]);
        }

        // Convert the blob to a file
        $fileName = 'advertisement_' . time() . '.' . $imageType; // Use the correct file extension
        $directoryPath = public_path('storage/advertisements/');

        // Check if the directory exists, if not, create it
        if (!is_dir($directoryPath)) {
          mkdir($directoryPath, 0755, true); // Create the directory with the correct permissions
        }

        // Define the full file path
        $filePath = $directoryPath . $fileName;

        // Decode the base64 blob (remove the data type part)
        $imageData = base64_decode($matches['data']);

        // Save the file
        if (file_put_contents($filePath, $imageData)) {
          // Remove the old file if exists (if updating the image)
          if ($image_code && file_exists(public_path('storage/' . $image_code))) {
            unlink(public_path('storage/' . $image_code)); // Delete the old file
          }

          // Update image code with the new image path
          $image_code = 'advertisements/' . $fileName;
        } else {
          return redirect()->back()->withErrors(['image_blob' => 'Failed to save the image.']);
        }
      }
    } else if ($validatedData['option'] == 2) {
      $image_code = $validatedData['image_code']; // Use the new image code if provided
    }

    // Update the advertisement data
    $advertisement->update([
      'campaign_name' => $validatedData['campaign_name'],
      'placement' => $validatedData['placement'],
      'option' => $validatedData['option'],
      'image_code' => $image_code, // Save the updated image path or code here, or retain the old one
      'start_date' => $validatedData['start_date'],
      'end_date' => $validatedData['end_date'],
    ]);

    // Return a success response or redirect
    return redirect()->route('manage-advertisements')->with('success', 'Advertisement updated successfully');
  }
}
