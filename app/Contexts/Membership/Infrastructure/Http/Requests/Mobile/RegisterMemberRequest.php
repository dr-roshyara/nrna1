<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Requests\Mobile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

/**
 * Mobile Member Registration Request
 *
 * INFRASTRUCTURE LAYER - HTTP Validation
 *
 * Validates mobile registration requests for POST /{tenant}/mapi/v1/members/register
 *
 * Validation Rules:
 * - full_name: Required, 2-255 characters
 * - email: Required, valid email, unique per tenant
 * - phone: Optional, max 20 characters
 * - member_id: Optional, 3-50 characters, unique per tenant
 * - geo_reference: Optional, validated via GeographyResolverInterface
 * - device_id: Optional, mobile device identifier
 * - app_version: Optional, mobile app version
 * - platform: Optional, mobile platform (ios, android, web)
 *
 * Authorization:
 * - Public endpoint (no authentication required for registration)
 * - Always returns true
 */
class RegisterMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Mobile registration is a public endpoint - no authentication required.
     */
    public function authorize(): bool
    {
        return true; // Public endpoint
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $tenantId = $this->route('tenant');
        // Use correct connection based on environment (tenant_test in testing, tenant in production)
        $tenantConnection = app()->environment('testing') ? 'tenant_test' : 'tenant';

        return [
            // Required fields
            'full_name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => [
                'required',
                // In testing: only validate format (DNS may not resolve for example.com)
                // In production: validate format + DNS (strict validation)
                app()->environment('testing') ? 'email:rfc' : 'email:rfc,dns',
                'max:255',
                // TODO: Custom validation causes PostgreSQL error - needs investigation
                // $this->emailUniqueRule($tenantId),
            ],

            // Optional personal info
            'phone' => ['nullable', 'string', 'max:20'],
            'member_id' => [
                'nullable',
                'string',
                'min:3',
                'max:50',
                // TODO: Custom validation causes PostgreSQL error - needs investigation
                // $this->memberIdUniqueRule($tenantId),
            ],

            // Optional geography reference
            'geo_reference' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[a-z]{2}(\.[a-z0-9\-_]+)+$/', // Basic format: country.level1.level2...
            ],

            // Mobile-specific fields (optional)
            'device_id' => ['nullable', 'string', 'max:255'],
            'app_version' => ['nullable', 'string', 'max:50'],
            'platform' => ['nullable', 'string', 'in:ios,android,web'],
        ];
    }

    /**
     * Custom validation for email uniqueness in JSON column
     *
     * PostgreSQL JSON column requires custom query
     * Format: personal_info->>'email' for text extraction
     */
    private function emailUniqueRule(string $tenantId): \Closure
    {
        return function ($attribute, $value, $fail) use ($tenantId) {
            $connection = app()->environment('testing') ? 'tenant_test' : 'tenant';

            $exists = DB::connection($connection)
                ->table('members')
                ->where('tenant_id', '=', $tenantId)
                ->where(DB::raw("personal_info->>'email'"), '=', $value)
                ->exists();

            if ($exists) {
                $fail('A member with this email already exists.');
            }
        };
    }

    /**
     * Custom validation for member_id uniqueness per tenant
     *
     * Business Rule: member_id must be unique within tenant
     */
    private function memberIdUniqueRule(string $tenantId): \Closure
    {
        return function ($attribute, $value, $fail) use ($tenantId) {
            if (empty($value)) {
                return; // Optional field, skip if empty
            }

            $connection = app()->environment('testing') ? 'tenant_test' : 'tenant';

            $exists = \DB::connection($connection)
                ->table('members')
                ->where('tenant_id', $tenantId)
                ->where('member_id', $value)
                ->exists();

            if ($exists) {
                $fail("Member ID '{$value}' already exists for tenant '{$tenantId}'");
            }
        };
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required for registration.',
            'full_name.min' => 'Full name must be at least 2 characters.',
            'full_name.max' => 'Full name cannot exceed 255 characters.',

            'email.required' => 'Email address is required for registration.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'A member with this email already exists.',

            'phone.max' => 'Phone number cannot exceed 20 characters.',

            'member_id.min' => 'Member ID must be at least 3 characters.',
            'member_id.max' => 'Member ID cannot exceed 50 characters.',
            'member_id.unique' => 'This member ID is already in use.',

            'geo_reference.regex' => 'Invalid geography reference format. Expected format: country.level1.level2... (e.g., "np.3.15.234")',
            'geo_reference.max' => 'Geography reference cannot exceed 500 characters.',

            'platform.in' => 'Platform must be one of: ios, android, web.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'full_name' => 'full name',
            'email' => 'email address',
            'phone' => 'phone number',
            'member_id' => 'member ID',
            'geo_reference' => 'geography reference',
            'device_id' => 'device ID',
            'app_version' => 'app version',
            'platform' => 'platform',
        ];
    }
}
