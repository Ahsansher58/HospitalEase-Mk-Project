<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationMasterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'locality' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'locality.required' => 'The locality field is required.',
            'city.required' => 'The city field is required.',
            'state.required' => 'The state field is required.',
            'country.required' => 'The country field is required.',
            'locality.max' => 'The locality must not exceed 255 characters.',
            'city.max' => 'The city must not exceed 255 characters.',
            'state.max' => 'The state must not exceed 255 characters.',
            'country.max' => 'The country must not exceed 255 characters.',
        ];
    }
}
