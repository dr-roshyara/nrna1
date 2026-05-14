<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Contexts\Membership\Application\Membership\Query\MyCommitteesQueryService;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * MemberCommitteesController
 *
 * API for "My Committees" feature.
 * Shows eligible committees + current memberships + pending applications.
 */
final class MemberCommitteesController extends Controller
{
    public function __construct(
        private readonly MyCommitteesQueryService $myCommitteesQueryService,
    ) {}

    /**
     * GET /api/members/{memberId}/committees
     *
     * Returns committees for a member with state.
     */
    public function index(Request $request, string $memberId): JsonResponse
    {
        $tenantId = TenantId::fromString($request->get('tenant_id', 'default'));
        $memberGeoPath = GeoPathChain::fromString($request->get('geo_path', ''));

        $committees = $this->myCommitteesQueryService->getForMember(
            new MemberId($memberId),
            $tenantId,
            $memberGeoPath
        );

        // Map to array for JSON response
        $data = array_map(fn ($view) => [
            'committee_id' => $view->committeeId,
            'committee_name' => $view->committeeName,
            'committee_code' => $view->committeeCode,
            'governance_level' => $view->governanceLevel,
            'has_active_association' => $view->hasActiveAssociation,
            'has_pending_application' => $view->hasPendingApplication,
            'can_apply' => $view->canApply,
            'application_status' => $view->applicationStatus,
            'joined_date' => $view->joinedDate?->format('Y-m-d'),
            'role_in_committee' => $view->roleInCommittee,
        ], $committees);

        return response()->json([
            'data' => $data,
            'count' => count($data),
        ]);
    }
}
