<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Controllers\Desktop;

use App\Contexts\Membership\Application\DTOs\MemberApprovalDto;
use App\Contexts\Membership\Application\DTOs\MemberRejectionDto;
use App\Contexts\Membership\Application\Services\DesktopMemberApprovalService;
use App\Contexts\Membership\Application\Services\DesktopMemberRejectionService;
use App\Contexts\Membership\Infrastructure\Http\Requests\Desktop\ApproveMemberRequest;
use App\Contexts\Membership\Infrastructure\Http\Requests\Desktop\RejectMemberRequest;
use App\Contexts\Membership\Infrastructure\Http\Resources\Desktop\DesktopMemberResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * Member Approval Controller (Desktop API)
 *
 * Handles member approval and rejection endpoints for Vue admin dashboard.
 *
 * Routes:
 * - POST /{tenant}/api/v1/members/{member}/approve
 * - POST /{tenant}/api/v1/members/{member}/reject
 *
 * Authorization: auth:web middleware + Gate checks
 *
 * Architecture:
 * - THIN CONTROLLER: Delegates all business logic to application services
 * - Uses DTOs to pass data from HTTP layer to application layer
 * - Returns JSON:API formatted responses
 */
class MemberApprovalController extends Controller
{
    public function __construct(
        private readonly DesktopMemberApprovalService $approvalService,
        private readonly DesktopMemberRejectionService $rejectionService
    ) {
    }

    /**
     * Approve a pending member
     *
     * POST /{tenant}/api/v1/members/{member}/approve
     *
     * @param string $tenant Tenant slug from route
     * @param string $member Member ID (ULID) from route
     * @param ApproveMemberRequest $request Validated request
     * @return JsonResponse
     */
    public function approve(
        string $tenant,
        string $member,
        ApproveMemberRequest $request
    ): JsonResponse {
        try {
            // Create DTO from request data
            $dto = new MemberApprovalDto(
                memberId: $member,
                tenantId: $tenant,
                adminUserId: auth()->id()
            );

            // Delegate to application service
            $approvedMember = $this->approvalService->approve($dto);

            // Return JSON:API formatted response
            return (new DesktopMemberResource($approvedMember))
                ->additional([
                    'message' => 'Member approved successfully.',
                ])
                ->response()
                ->setStatusCode(200);
        } catch (\DomainException $e) {
            // Business rule violations (member not found, not pending, wrong tenant)
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            // Unexpected errors
            return response()->json([
                'error' => 'Failed to approve member.',
                'message' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Reject a pending member
     *
     * POST /{tenant}/api/v1/members/{member}/reject
     *
     * @param string $tenant Tenant slug from route
     * @param string $member Member ID (ULID) from route
     * @param RejectMemberRequest $request Validated request (includes 'reason')
     * @return JsonResponse
     */
    public function reject(
        string $tenant,
        string $member,
        RejectMemberRequest $request
    ): JsonResponse {
        try {
            // Create DTO from request data
            $dto = new MemberRejectionDto(
                memberId: $member,
                tenantId: $tenant,
                adminUserId: auth()->id(),
                reason: $request->validated('reason')
            );

            // Delegate to application service
            $rejectedMember = $this->rejectionService->reject($dto);

            // Return JSON:API formatted response
            return (new DesktopMemberResource($rejectedMember))
                ->additional([
                    'message' => 'Member rejected successfully.',
                ])
                ->response()
                ->setStatusCode(200);
        } catch (\DomainException $e) {
            // Business rule violations (member not found, not pending, wrong tenant)
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        } catch (\InvalidArgumentException $e) {
            // Validation errors from domain (e.g., empty reason)
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            // Unexpected errors
            return response()->json([
                'error' => 'Failed to reject member.',
                'message' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
