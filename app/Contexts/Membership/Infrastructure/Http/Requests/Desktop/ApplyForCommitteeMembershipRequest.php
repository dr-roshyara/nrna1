<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Requests\Desktop;

use Illuminate\Foundation\Http\FormRequest;

final class ApplyForCommitteeMembershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasVerifiedEmail();
    }

    public function rules(): array
    {
        return [
            'committee_id' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'in:RESIDENCE,EXCEPTION,MANUAL'],
            'exception_justification' => ['nullable', 'required_if:reason,EXCEPTION', 'string', 'max:1000'],
            'committee_geo_unit_id' => ['required', 'integer', 'min:1'],
            'committee_geopath' => ['required', 'string', 'max:255'],
            'committee_geopath_segments' => ['required', 'json'],
        ];
    }

    public function messages(): array
    {
        return [
            'committee_id.required' => 'Committee is required.',
            'reason.required' => 'Application reason is required.',
            'reason.in' => 'Application reason must be one of: RESIDENCE, EXCEPTION, MANUAL.',
            'exception_justification.max' => 'Justification cannot exceed 1000 characters.',
            'committee_geo_unit_id.required' => 'Committee geographic unit is required.',
            'committee_geopath.required' => 'Committee geographic path is required.',
            'committee_geopath_segments.json' => 'Committee geographic segments must be valid JSON.',
        ];
    }
}
