<?php

namespace App\Http\Requests\Election;

use App\Contexts\Elections\Domain\ValueObjects\AdmissionRequirement;
use App\Contexts\Elections\Domain\ValueObjects\VoterAdmissionPolicy;
use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateVoterAdmissionPolicyRequest — "Identity verification required: ON/OFF"
 * (PBDIGIT-213 C2, ADR_20261004_1549 R3-1).
 *
 * The client contract is the business toggle; no technical requirement codes cross
 * the HTTP boundary. Authorization: the active Chief or Deputy OF THIS ELECTION
 * (ElectionPolicy::manageSettings — election-scoped).
 */
class UpdateVoterAdmissionPolicyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manageSettings', $this->route('election'));
    }

    public function rules(): array
    {
        return [
            'identity_verification_required' => ['required', 'boolean'],
        ];
    }

    public function proposedPolicy(): VoterAdmissionPolicy
    {
        return $this->boolean('identity_verification_required')
            ? VoterAdmissionPolicy::requiring(AdmissionRequirement::OfficerIdentityVerification)
            : VoterAdmissionPolicy::none();
    }
}
