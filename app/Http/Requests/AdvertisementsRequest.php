<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisementsRequest extends FormRequest
{
  public function authorize()
  {
    return true; // Adjust this as needed for authorization
  }

  public function rules(): array
  {
    return [
      'campaign_name' => 'required|string|max:255',
      'placement' => 'required|integer',
      'option' => 'required|integer',
      'image_blob' => 'required_if:option,1|nullable',
      'image_code' => 'required_if:option,2|nullable',
      'start_date' => 'required|date|after_or_equal:today',
      'end_date' => 'required|date|after:start_date',
    ];
  }

  public function messages()
  {
    return [
      'campaign_name.required' => 'The campaign name is required.',
      'placement.required' => 'Placement is required.',
      'option.required' => 'You must select an option.',
      'image.required_if' => 'Image is required when Choose Image is selected.',
      'image_code.required_if' => 'Insert Code is required when Insert Code is selected.',
      'start_date.required' => 'The start date is required.',
      'end_date.required' => 'The end date is required.',
      'end_date.after' => 'The end date must be after the start date.',
    ];
  }
}
