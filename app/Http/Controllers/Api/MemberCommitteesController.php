<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Contexts\Membership\Application\Membership\Ports\MemberGeoPathProviderPort;
use App\Contexts\Membership\Application\Membership\Query\MyCommitteesQueryService;
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
 *
 * ARCHITECTURAL NOTE: GeoPathChain resolution delegated to port.
 * Controller never constructs value objects (CLAUDE.md Rule 2).
 */
final class MemberCommitteesController extends Controller
{
    public function __construct(
        private readonly MyCommitteesQueryService $myCommitteesQueryService,
        private readonly MemberGeoPathProviderPort $geoPathProvider,
    ) {}

    /**
     * GET /organisations/{organisationId}/my-committees
     *
     * Returns committees for authenticated member within organisation context.
     * GeoPathChain resolution delegated to port (not controller).
     *
     * TENANT-SCOPED: Everything is organisation-specific.
     */
    public function index(Request $request, string $organisationId): JsonResponse
    {
        $tenantId = TenantId::fromString($organisationId);
        // Implicitly: authenticated user's ID (from auth session)
        $memberId = new MemberId(auth()->id());

        // Port resolves member's geo context within organisation (no controller construction)
        $memberGeoPath = $this->geoPathProvider->resolveForMember($memberId, $tenantId);

        $committees = $this->myCommitteesQueryService->getForMember(
            $memberId,
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
