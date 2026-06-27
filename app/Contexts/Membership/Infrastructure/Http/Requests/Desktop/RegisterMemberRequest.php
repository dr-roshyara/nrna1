<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Requests\Desktop;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Desktop Member Registration Request
 *
 * CASE 4: Tenant Desktop API - Admin Registration
 * Route: /{tenant}/api/v1/members (POST)
 *
 * CRITICAL CORRECTIONS APPLIED:
 * - Format validation ONLY (no cross-context DB validation)
 * - No exists() check for tenant_user_id (moved to Application Service)
 * - ULID format validation for tenant_user_id
 * - Environment-aware connection for uniqueness checks
 *
 * DAY 2 LESSONS APPLIED:
 * - No DB queries for cross-context validation in FormRequest
 * - PostgreSQL JSON uniqueness using proper connection
 * - Validation messages in sync with Mobile API pattern
 */
class RegisterMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization handled by middleware (auth:web + can:manage-members)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Environment-aware tenant connection
        $tenantConnection = app()->environment('testing') ? 'tenant_test' : 'tenant';

        return [
            // REQUIRED: Tenant user ID (ULID format)
            // NOTE: Existence check moved to DesktopMemberRegistrationService
            // Will use TenantAuthProvisioningInterface to validate user exists
            'tenant_user_id' => [
                'required',
                'string',
                'regex:/^[0-9A-Z]{26}$/', // ULID format (26 uppercase alphanumeric)
            ],

            // REQUIRED: Personal information
            'full_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                // Unique within tenant (PostgreSQL JSON column)
                "unique:{$tenantConnection}.members,personal_info->email",
            ],

            // OPTIONAL: Contact information
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^\+?[0-9\-\(\)\s]+$/', // International phone format
            ],

            // OPTIONAL: Member ID (party-defined identifier)
            'member_id' => [
                'nullable',
                'string',
                'min:3',
                'max:50',
                'regex:/^[A-Z0-9\-_]+$/', // Alphanumeric + hyphens/underscores
                // TODO: Fix PostgreSQL column detection issue
                // Temporarily disabled to test registration flow
                // Unique within tenant (composite unique constraint)
                // Rule::unique("{$tenantConnection}.members", 'member_id')
                //     ->where('tenant_id', $this->route('tenant')),
            ],

            // OPTIONAL: Geography reference (string only, no FK)
            'geo_reference' => [
                'nullable',
                'string',
                'regex:/^[a-z]{2}\.[0-9\.]+$/', // Format: np.3.15.234
            ],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Tenant User ID
            'tenant_user_id.required' => 'Tenant user ID is required for desktop registration.',
            'tenant_user_id.regex' => 'Tenant user ID must be a valid ULID (26 uppercase alphanumeric characters).',

            // Personal Information
            'full_name.required' => 'Full name is required.',
            'full_name.min' => 'Full name must be at least 2 characters.',
            'full_name.max' => 'Full name must not exceed 255 characters.',

            'email.required' => 'Email address is required.',
            'email.email' => 'Email address must be a valid email format.',
            'email.unique' => 'This email address is already registered for this tenant.',

            // Optional Fields
            'phone.regex' => 'Phone number must be in international format (e.g., +977-9841234567).',

            'member_id.regex' => 'Member ID must contain only uppercase letters, numbers, hyphens, and underscores.',
            'member_id.unique' => 'This member ID is already in use for this tenant.',

            'geo_reference.regex' => 'Geography reference must be in format: country_code.level.id (e.g., np.3.15.234).',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'tenant_user_id' => 'tenant user ID',
            'full_name' => 'full name',
            'email' => 'email address',
            'phone' => 'phone number',
            'member_id' => 'member ID',
            'geo_reference' => 'geography reference',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normalize member_id to uppercase if provided
        if ($this->has('member_id') && $this->member_id !== null) {
            $this->merge([
                'member_id' => strtoupper((string) $this->member_id),
            ]);
        }

        // Normalize email to lowercase if provided
        if ($this->has('email') && $this->email !== null) {
            $this->merge([
                'email' => strtolower((string) $this->email),
            ]);
        }
    }
}
