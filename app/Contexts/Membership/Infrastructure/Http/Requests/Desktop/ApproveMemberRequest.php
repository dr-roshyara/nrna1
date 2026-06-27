<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Requests\Desktop;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Approve Member Form Request
 *
 * Desktop API - Member Approval Validation
 * Route: POST /{tenant}/api/v1/members/{member}/approve
 *
 * No additional validation needed (member ID from route, admin ID from auth).
 * Authorization handled by middleware (auth:web).
 */
class ApproveMemberRequest extends FormRequest
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
        // No additional validation needed
        // Member ID comes from route parameter
        // Admin ID comes from authenticated user
        return [];
    }
}
