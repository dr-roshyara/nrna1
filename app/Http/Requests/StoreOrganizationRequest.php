<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrganizationRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $isSelf = $this->input('representative.is_self', false);

        $rules = [
            // Step 1: Basic Information
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('organizations'),
            ],
            'email' => [
                'required',
                'email:rfc',  // Always validate RFC format
                'max:255',
                Rule::unique('organizations'),
            ],

            // Step 2: Address Information
            'address.street' => 'required|string|max:255',
            'address.city' => 'required|string|max:100',
            'address.zip' => 'required|string|max:20|regex:/^\d{5}$/',
            'address.country' => 'required|string|size:2|in:DE,AT,CH',

            // Step 3: Representative Information
            'representative.name' => 'required|string|min:3|max:255',
            'representative.role' => 'required|string|min:2|max:100',
            // Email is only required if NOT self-representative
            'representative.email' => $isSelf
                ? 'nullable'
                : 'required|email:rfc|max:255',
            'representative.is_self' => 'boolean',

            // Legal acceptance
            'accept_gdpr' => 'required|accepted',
            'accept_terms' => 'required|accepted',
        ];

        // Add DNS validation only in non-test environments
        if (!app()->environment('testing')) {
            $rules['email'][] = function ($attribute, $value, $fail) {
                $domain = explode('@', $value)[1] ?? null;
                if (!$domain || !checkdnsrr($domain, 'MX')) {
                    $fail('The email domain must have valid MX records.');
                }
            };

            $rules['representative.email'][] = function ($attribute, $value, $fail) {
                if (!$value) return; // Skip if empty (handled by required_if)
                $domain = explode('@', $value)[1] ?? null;
                if (!$domain || !checkdnsrr($domain, 'MX')) {
                    $fail('The representative email domain must have valid MX records.');
                }
            };
        }

        return $rules;
    }

    /**
     * Get custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.organization.name.required'),
            'name.unique' => __('validation.organization.name.unique'),
            'email.required' => __('validation.organization.email.required'),
            'email.email' => __('validation.organization.email.invalid'),
            'email.unique' => __('validation.organization.email.unique'),
            'address.zip.regex' => __('validation.organization.zip.format'),
            'accept_gdpr.accepted' => __('validation.organization.gdpr.required'),
            'accept_terms.accepted' => __('validation.organization.terms.required'),
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
            'name' => __('validation.attributes.organization_name'),
            'email' => __('validation.attributes.organization_email'),
            'address.street' => __('validation.attributes.street'),
            'address.city' => __('validation.attributes.city'),
            'address.zip' => __('validation.attributes.zip_code'),
            'representative.name' => __('validation.attributes.representative_name'),
            'representative.role' => __('validation.attributes.representative_role'),
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'success' => false,
                'message' => __('validation.failed'),
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
