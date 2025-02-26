<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'locations' => ['required', 'array', 'min:1', 'max:3'],
            'locations.*.city' => ['required', 'string', 'min:3', 'max:100'],
            'locations.*.country' => ['required', 'string', 'min:3', 'max:100'],
            'locations.*.max_uv' => ['required', 'numeric', 'min:0', 'max:100'],
            'locations.*.max_precipitation' => ['required', 'numeric', 'min:0', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'locations.required' => 'At least one location is required',
            'locations.max' => 'Maximum 3 locations allowed',
            'locations.*.city.required' => 'City name is required',
            'locations.*.country.required' => 'Country name is required',
            'locations.*.max_uv.required' => 'Maximum UV index is required',
            'locations.*.max_precipitation.required' => 'Maximum precipitation is required',
        ];
    }
}
