<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreOrganisationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * CRITICAL: All rules are in ARRAY format to allow custom closures to be appended.
     * This prevents "[] operator not supported for strings" error.
     */
    public function rules(): array
    {
        $isSelf = $this->input('representative.is_self', false);

        // ✅ ALL RULES IN CONSISTENT ARRAY FORMAT
        $rules = [
            // Step 1: Basic Information
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('organisations'),
            ],
            'email' => [
                'required',
                'email:rfc',
                'max:255',
                Rule::unique('organisations'),
            ],

            // Step 2: Address Information
            'address.street' => [
                'required',
                'string',
                'max:255',
            ],
            'address.city' => [
                'required',
                'string',
                'max:100',
            ],
            'address.zip' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{5}$/',
            ],
            'address.country' => [
                'required',
                'string',
                'size:2',
                'in:DE,AT,CH',
            ],

            // Step 3: Representative Information
            'representative.name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'representative.role' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'representative.email' => $isSelf
                ? ['nullable']
                : [
                    'required',
                    'email:rfc',
                    'max:255',
                ],
            'representative.is_self' => [
                'boolean',
            ],

            // Legal acceptance
            'accept_gdpr' => [
                'required',
                'accepted',
            ],
            'accept_terms' => [
                'required',
                'accepted',
            ],
        ];

        // ✅ DNS validation disabled in production due to timeout issues
        // checkdnsrr() can hang on slow DNS servers, causing 30s timeout
        // Email format validation (email:rfc) is sufficient for now
        // DNS validation could be moved to async job if needed
        /*
        if (!app()->environment('testing', 'production')) {
            // organisation email DNS validation closure
            $rules['email'][] = function ($attribute, $value, $fail) {
                if (!is_string($value) || empty($value)) {
                    return;
                }
                $parts = explode('@', $value);
                $domain = $parts[1] ?? null;
                if (!$domain || !checkdnsrr($domain, 'MX')) {
                    $fail(__('validation.organisation.email.dns'));
                }
            };

            // Representative email DNS validation closure
            $rules['representative.email'][] = function ($attribute, $value, $fail) {
                if (!is_string($value) || empty($value)) {
                    return;
                }
                $parts = explode('@', $value);
                $domain = $parts[1] ?? null;
                if (!$domain || !checkdnsrr($domain, 'MX')) {
                    $fail(__('validation.organisation.rep_email.dns'));
                }
            };
        }
        */

        return $rules;
    }

    /**
     * Get custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.organisation.name.required'),
            'name.unique' => __('validation.organisation.name.unique'),
            'email.required' => __('validation.organisation.email.required'),
            'email.email' => __('validation.organisation.email.invalid'),
            'email.unique' => __('validation.organisation.email.unique'),
            'address.zip.regex' => __('validation.organisation.zip.format'),
            'accept_gdpr.accepted' => __('validation.organisation.gdpr.required'),
            'accept_terms.accepted' => __('validation.organisation.terms.required'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim($this->email)),
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => __('validation.attributes.organisation_name'),
            'email' => __('validation.attributes.organisation_email'),
            'address.street' => __('validation.attributes.street'),
            'address.city' => __('validation.attributes.city'),
            'address.zip' => __('validation.attributes.zip_code'),
            'representative.name' => __('validation.attributes.representative_name'),
            'representative.role' => __('validation.attributes.representative_role'),
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * For Inertia AJAX requests, throw ValidationException which Inertia's exception handler
     * automatically converts to a proper Inertia response with validation errors.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw ValidationException::withMessages($validator->errors()->messages());
    }
}
