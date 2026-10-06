<?php

namespace App\Http\Requests\Election;

use Illuminate\Foundation\Http\FormRequest;

class UpdateElectionSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manageSettings', $this->route('election'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'ip_restriction_enabled'    => ['boolean'],
            'ip_restriction_max_per_ip' => ['integer', 'min:1', 'max:50'],
            'ip_whitelist'              => ['nullable', 'array'],
            'ip_whitelist.*'            => ['string'],
            'no_vote_option_enabled'    => ['boolean'],
            'no_vote_option_label'      => ['string', 'max:100'],
            'selection_constraint_type' => ['required', 'in:any,exact,range,minimum,maximum'],
            'selection_constraint_min'  => ['nullable', 'integer', 'min:0'],
            'selection_constraint_max'  => ['nullable', 'integer', 'min:1'],
            // Legacy, non-authoritative (PBDIGIT-222, PO 2026-10-06): optional, kept for backward compatibility.
            'voter_verification_mode'   => ['sometimes', 'in:none,ip_only,fingerprint_only,both'],
            'ip_verification_enabled'   => ['boolean'],
            'settings_version'          => ['required', 'integer'],
            'confirmed_active_changes'  => ['boolean'],
            'agreed_to_settings'        => ['boolean'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'settings_version.required'      => 'Settings version is required for optimistic locking.',
            'selection_constraint_max.min'   => 'Maximum selections must be at least 1.',
            'ip_restriction_max_per_ip.max'  => 'IP restriction limit cannot exceed 50.',
            'selection_constraint_type.required' => 'Selection constraint type is required.',
        ];
    }
}
