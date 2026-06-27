<?php

namespace App\Contexts\Geography\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Administrative Unit Request
 *
 * Validates requests for querying administrative units.
 * Supports filtering by country, level, parent, etc.
 */
class AdministrativeUnitRequest extends FormRequest
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
                'sometimes',
                'string',
                'size:2',
                Rule::exists('countries', 'code')->where('is_active', true),
            ],
            'admin_level' => [
                'sometimes',
                'integer',
                'min:1',
                'max:10',
            ],
            'parent_id' => [
                'sometimes',
                'integer',
                'exists:geo_administrative_units,id',
            ],
            'admin_type' => [
                'sometimes',
                'string',
                'max:50',
            ],
            'active_only' => [
                'sometimes',
                'boolean',
            ],
            'page' => [
                'sometimes',
                'integer',
                'min:1',
            ],
            'per_page' => [
                'sometimes',
                'integer',
                'min:1',
                'max:100', // Prevent excessive page sizes
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
            'country_code.size' => 'Country code must be exactly 2 characters (ISO 3166-1 alpha-2)',
            'country_code.exists' => 'Invalid or inactive country code',
            'admin_level.integer' => 'Admin level must be an integer',
            'admin_level.min' => 'Admin level must be at least 1',
            'admin_level.max' => 'Admin level cannot exceed 10',
            'parent_id.integer' => 'Parent ID must be an integer',
            'parent_id.exists' => 'Parent unit does not exist',
            'per_page.max' => 'Maximum 100 items per page',
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
            'admin_level' => 'administrative level',
            'parent_id' => 'parent ID',
            'admin_type' => 'administrative type',
            'active_only' => 'active only filter',
            'per_page' => 'items per page',
        ];
    }

    /**
     * Get validated data with defaults.
     *
     * @return array
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        // Set defaults
        $validated['active_only'] = $validated['active_only'] ?? true;
        $validated['page'] = $validated['page'] ?? 1;
        $validated['per_page'] = $validated['per_page'] ?? 15;

        return $validated;
    }
}
