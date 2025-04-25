<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MainCategoryRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return false;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255|unique:main_categories,name',
      'sort_order' => 'required|integer|min:1',
    ];
  }
  public function messages()
  {
    return [
      'name.required' => 'The category name is required.',
      'name.string' => 'The category name must be a valid string.',
      'name.max' => 'The category name may not be longer than 255 characters.',
      'sort_order.required' => 'The sort order is required.',
      'sort_order.integer' => 'The sort order must be an integer.',
      'sort_order.min' => 'The sort order must be at least 1.',
      'name.unique' => 'The name you entered already exists. Please choose a different name.',
    ];
  }
}
