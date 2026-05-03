<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Controllers\Desktop;

use App\Contexts\Membership\Application\DTOs\DesktopRegistrationDto;
use App\Contexts\Membership\Application\Services\DesktopMemberRegistrationService;
use App\Contexts\Membership\Infrastructure\Http\Requests\Desktop\RegisterMemberRequest;
use App\Contexts\Membership\Infrastructure\Http\Resources\Desktop\DesktopMemberResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * Desktop Member Controller - Registration Only
 *
 * CASE 4: Tenant Desktop API - Admin Registration
 * Route: /{tenant}/api/v1/members (POST only)
 * Middleware: web, identify.tenant, auth:web, can:manage-members
 *
 * SCOPE: Member registration for desktop admin interface
 * FUTURE: Separate controllers for management/approval (Phase 5)
 */
class MemberController extends Controller
{
    public function __construct(
        private readonly DesktopMemberRegistrationService $registrationService
    ) {}

    /**
     * Register a new member (Admin-initiated)
     *
     * POST /{tenant}/api/v1/members
     *
     * Business Rules:
     * - Admin must be authenticated (auth:web)
     * - Admin must have manage-members permission
     * - Tenant user must already exist (TenantAuth context)
     * - Creates member with PENDING status (skips DRAFT)
     * - Registration channel: 'desktop'
     */
    public function store(RegisterMemberRequest $request): JsonResponse
    {
        $tenantId = $request->route('tenant');

        $dto = new DesktopRegistrationDto(
            tenantId: $tenantId,
            tenantUserId: $request->validated('tenant_user_id'),
            fullName: $request->validated('full_name'),
            email: $request->validated('email'),
            phone: $request->validated('phone'),
            memberId: $request->validated('member_id'),
            geoReference: $request->validated('geo_reference')
        );

        $member = $this->registrationService->register($dto);

        return (new DesktopMemberResource($member))
            ->response()
            ->setStatusCode(201)
            ->header('Location', url("/{$tenantId}/api/v1/members/{$member->id}"));
    }
}
