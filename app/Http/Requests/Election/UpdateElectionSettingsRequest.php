<?php

namespace App\Http\Requests\Election;

use App\Models\Election;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
            // PBDIGIT-285: validated with the matcher's own parser (IPv4 address or IPv4 CIDR /0–32), so an entry
            // the matcher cannot read is refused here instead of being stored.
            'ip_whitelist.*'            => ['string', function (string $attribute, mixed $value, \Closure $fail) {
                if (! is_string($value) || ! \App\Domain\Election\Security\Ipv4Whitelist::isValidEntry($value)) {
                    $fail('Each whitelist entry must be an IPv4 address (e.g. 203.0.113.7) or an IPv4 range (e.g. 10.0.0.0/8).');
                }
            }],
            'no_vote_option_enabled'    => ['boolean'],
            'no_vote_option_label'      => ['string', 'max:100'],
            'selection_constraint_type' => ['required', 'in:any,exact,range,minimum,maximum'],
            'selection_constraint_min'  => ['nullable', 'integer', 'min:0'],
            'selection_constraint_max'  => ['nullable', 'integer', 'min:1'],
            // Legacy, non-authoritative (PBDIGIT-222, PO 2026-10-06): optional, kept for backward compatibility.
            'voter_verification_mode'   => ['sometimes', 'in:none,ip_only,fingerprint_only,both'],
            'ip_verification_enabled'   => ['boolean'],
            'ip_binding_enabled'        => ['boolean'],   // PBDIGIT-247 — security policy, locked from ReadyForVoting
            'settings_version'          => ['required', 'integer'],
            'confirmed_active_changes'  => ['boolean'],
            'agreed_to_settings'        => ['boolean'],
        ];
    }

    /**
     * PBDIGIT-247 (B2): IP Binding needs IP verification — binding compares against captured IPs, and officers
     * must be able to re-capture while it is on. The effective values (request, else stored) must not end up as
     * binding ON with verification OFF. The error names the field the request is trying to change.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $election = $this->route('election');
                if (! $election instanceof Election || $validator->errors()->isNotEmpty()) {
                    return;
                }

                $storedBinding = $election->isIpBindingEnabled();
                $binding = $this->has('ip_binding_enabled') ? $this->boolean('ip_binding_enabled') : $storedBinding;
                $verification = $this->has('ip_verification_enabled')
                    ? $this->boolean('ip_verification_enabled')
                    : $election->isIpVerificationEnabled();

                if ($binding && ! $verification) {
                    $storedBinding
                        ? $validator->errors()->add('ip_verification_enabled', 'IP verification cannot be disabled while IP binding is enabled.')
                        : $validator->errors()->add('ip_binding_enabled', 'IP binding requires IP verification to be enabled.');
                }
            },
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
