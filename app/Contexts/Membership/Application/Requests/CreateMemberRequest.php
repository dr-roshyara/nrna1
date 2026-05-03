<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Requests;

use App\Contexts\Membership\Domain\Services\GeographyLookupInterface;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Create Member Request Validation
 *
 * OPTIONAL GEOGRAPHY ARCHITECTURE - Conditional Validation
 *
 * This form request validates member creation data with optional geography support.
 * Geography validation is conditional - only enforced if Geography module is installed.
 *
 * Validation Strategy:
 * - Core fields: Always required (name, email, membership number)
 * - Geography fields: Conditionally validated based on module installation
 * - Hierarchy validation: Ensures parent-child relationships are correct
 *
 * Architecture Pattern: Application-Level Validation
 * - Replaces database FK constraints (removed for loose coupling)
 * - Uses GeographyLookupInterface for validation logic
 * - Graceful degradation when Geography not installed
 *
 * Benefits:
 * - Members can be created without geography (small parties)
 * - Geography validation kicks in when module installed
 * - Clear error messages for invalid geography data
 * - Supports progressive enhancement workflow
 *
 * @package App\Contexts\Membership\Application\Requests
 */
class CreateMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // TODO: Implement proper authorization logic
        // Check if user has permission to create members
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Core fields are always validated.
     * Geography fields are conditionally validated based on module installation.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Core member fields (always required)
        $rules = [
            'full_name' => 'required|string|max:255',
            'membership_number' => [
                'required',
                'string',
                'max:50',
                'unique:members,membership_number',
            ],
            'membership_type' => 'nullable|in:full,associate,youth,student',
            'status' => 'nullable|in:active,suspended,expired',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'country_code' => 'nullable|string|size:2',
        ];

        // Get geography service
        $geographyLookup = app(GeographyLookupInterface::class);

        // Check if Geography module is installed
        if ($geographyLookup->isGeographyModuleInstalled()) {
            // Geography module installed - validate geography IDs exist
            $this->addGeographyValidationRules($rules, $geographyLookup);
        } else {
            // Geography module NOT installed - fields are nullable integers
            $this->addNullableGeographyRules($rules);
        }

        return $rules;
    }

    /**
     * Add geography validation rules when Geography module is installed.
     *
     * Validates:
     * - Geography IDs exist in geo_administrative_units table
     * - IDs are active geography units
     * - Hierarchy integrity (parent-child relationships)
     *
     * @param array $rules Rules array to modify
     * @param GeographyLookupInterface $geographyLookup Geography validation service
     * @return void
     */
    protected function addGeographyValidationRules(array &$rules, GeographyLookupInterface $geographyLookup): void
    {
        // Validate each geography level (1-8)
        for ($level = 1; $level <= 8; $level++) {
            $field = "admin_unit_level{$level}_id";

            $rules[$field] = [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) use ($geographyLookup, $level) {
                    if ($value && !$geographyLookup->validateGeographyIdExists((int)$value)) {
                        $fail("The selected geography unit for level {$level} does not exist or is inactive.");
                    }
                },
            ];
        }

        // Validate hierarchy integrity if multiple levels provided
        $rules['_geography_hierarchy_validation'] = [
            function ($attribute, $value, $fail) use ($geographyLookup) {
                // Collect all geography level IDs from request
                $hierarchyData = [];
                for ($level = 1; $level <= 8; $level++) {
                    $levelField = "admin_unit_level{$level}_id";
                    if ($this->has($levelField) && $this->input($levelField)) {
                        $hierarchyData["level{$level}_id"] = (int)$this->input($levelField);
                    }
                }

                // Only validate if at least one geography level provided
                if (empty($hierarchyData)) {
                    return; // No geography data - skip validation
                }

                // Validate hierarchy integrity
                $validation = $geographyLookup->validateGeographyHierarchy($hierarchyData);

                if (!$validation['valid']) {
                    foreach ($validation['errors'] as $error) {
                        $fail($error);
                    }
                }
            },
        ];
    }

    /**
     * Add nullable geography rules when Geography module is NOT installed.
     *
     * Geography fields are optional integers - no validation performed.
     *
     * @param array $rules Rules array to modify
     * @return void
     */
    protected function addNullableGeographyRules(array &$rules): void
    {
        // Geography module not installed - all geography fields are nullable
        for ($level = 1; $level <= 8; $level++) {
            $field = "admin_unit_level{$level}_id";
            $rules[$field] = 'nullable|integer';
        }
    }

    /**
     * Get custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'full_name' => 'full name',
            'membership_number' => 'membership number',
            'membership_type' => 'membership type',
            'email' => 'email address',
            'phone' => 'phone number',
            'country_code' => 'country code',
            'admin_unit_level1_id' => 'province/state',
            'admin_unit_level2_id' => 'district',
            'admin_unit_level3_id' => 'local level',
            'admin_unit_level4_id' => 'ward',
            'admin_unit_level5_id' => 'village/tole',
            'admin_unit_level6_id' => 'ward committee',
            'admin_unit_level7_id' => 'street captain',
            'admin_unit_level8_id' => 'household coordinator',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Member name is required.',
            'membership_number.required' => 'Membership number is required.',
            'membership_number.unique' => 'This membership number is already in use.',
            'email.email' => 'Please provide a valid email address.',
            'membership_type.in' => 'Invalid membership type. Must be: full, associate, youth, or student.',
            'status.in' => 'Invalid status. Must be: active, suspended, or expired.',
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * Called after validation passes.
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        // Log successful validation for monitoring
        \Log::debug('Member creation request validated successfully', [
            'membership_number' => $this->input('membership_number'),
            'has_geography' => $this->has('admin_unit_level1_id'),
        ]);
    }
}
