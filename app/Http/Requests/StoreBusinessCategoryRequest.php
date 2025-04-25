<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'order_no' => 'required|integer|min:1',
            'main_category_id' => 'nullable|integer|exists:business_categories,id',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Please enter a category name.',
            'name.max' => 'The category name should not exceed 255 characters.',
            'order_no.required' => 'Please provide a sort order.',
            'order_no.integer' => 'The sort order must be a number.',
            'main_category_id.exists' => 'The selected main category does not exist.',
        ];
    }
}
