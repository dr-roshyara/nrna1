<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Requests\Desktop;

use Illuminate\Foundation\Http\FormRequest;

final class ReviewMembershipApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasVerifiedEmail();
    }

    public function rules(): array
    {
        return [
            'application_id' => ['required', 'string', 'max:255'],
            'action' => ['required', 'string', 'in:APPROVE,REJECT'],
        ];
    }

    public function messages(): array
    {
        return [
            'application_id.required' => 'Application ID is required.',
            'action.required' => 'Action is required.',
            'action.in' => 'Action must be either APPROVE or REJECT.',
        ];
    }
}
