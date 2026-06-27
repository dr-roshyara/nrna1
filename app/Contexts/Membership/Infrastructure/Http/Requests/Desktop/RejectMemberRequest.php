<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Requests\Desktop;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Reject Member Form Request
 *
 * Desktop API - Member Rejection Validation
 * Route: POST /{tenant}/api/v1/members/{member}/reject
 *
 * Validates rejection reason before passing to application service.
 * Authorization handled by middleware (auth:web).
 */
class RejectMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization handled by middleware (auth:web + Gate::before in tests)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'min:10', 'max:500'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'reason.required' => 'Rejection reason is required.',
            'reason.min' => 'Rejection reason must be at least 10 characters.',
            'reason.max' => 'Rejection reason must not exceed 500 characters.',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'reason' => 'rejection reason',
        ];
    }
}
