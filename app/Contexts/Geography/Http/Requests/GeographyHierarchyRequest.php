<?php

namespace App\Contexts\Geography\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Geography Hierarchy Validation Request
 *
 * Validates requests for hierarchy validation endpoint.
 * Ensures all unit IDs and country code are valid.
 */
class GeographyHierarchyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Public endpoint - no authorization required
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
            'country_code' => [
                'required',
                'string',
                'size:2',
                Rule::exists('countries', 'code')->where('is_active', true),
            ],
            'unit_ids' => [
                'required',
                'array',
                'min:1',
                'max:10', // Limit to prevent abuse
            ],
            'unit_ids.*' => [
                'required',
                'integer',
                'exists:geo_administrative_units,id',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'country_code.required' => 'Country code is required',
            'country_code.size' => 'Country code must be exactly 2 characters (ISO 3166-1 alpha-2)',
            'country_code.exists' => 'Invalid or inactive country code',
            'unit_ids.required' => 'At least one unit ID must be provided',
            'unit_ids.array' => 'Unit IDs must be an array',
            'unit_ids.min' => 'At least one unit ID must be provided',
            'unit_ids.max' => 'Maximum 10 unit IDs can be validated at once',
            'unit_ids.*.integer' => 'Each unit ID must be an integer',
            'unit_ids.*.exists' => 'One or more unit IDs do not exist',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'country_code' => 'country code',
            'unit_ids' => 'unit IDs',
            'unit_ids.*' => 'unit ID',
        ];
    }
}
