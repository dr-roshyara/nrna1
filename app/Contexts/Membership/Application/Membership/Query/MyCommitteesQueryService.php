<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\Query;

use App\Contexts\Membership\Application\Membership\Ports\MembershipApplicationRepositoryPort;
use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * MyCommitteesQueryService
 *
 * Returns eligible committees + current membership + application state.
 * Orchestrates Phase C EligibleCommitteeQueryService with membership/application decorations.
 *
 * CRITICAL: Phase C is the single source of truth for eligibility.
 * This service only reads eligibility, never duplicates logic.
 */
final class MyCommitteesQueryService
{
    public function __construct(
        private readonly EligibleCommitteeQueryService $eligibleCommitteeService,
        private readonly MembershipLineageRepositoryPort $membershipLineageRepository,
        private readonly MembershipApplicationRepositoryPort $membershipApplicationRepository,
    ) {}

    /**
     * Get committees for a member with state decoration.
     *
     * @return MyCommitteeView[]
     */
    public function getForMember(
        MemberId $memberId,
        TenantId $tenantId,
        GeoPathChain $memberGeoPath,
    ): array {
        // Phase C: Get eligible committees (source of truth for eligibility)
        $eligible = $this->eligibleCommitteeService->eligibleForMember(
            $memberId,
            $tenantId,
            $memberGeoPath
        );

        // Map to enhanced DTO with additional state
        return array_map(function (EligibleCommitteeView $view) use ($memberId, $tenantId) {
            // Determine if member can apply (eligible && not active && no pending app)
            $canApply = !$view->hasActiveAssociation && !$view->hasPendingApplication;

            // TODO: Query application status to distinguish pending/accepted/rejected
            $applicationStatus = $view->hasPendingApplication ? 'pending' : null;

            return new MyCommitteeView(
                committeeId: $view->committeeId,
                committeeName: $view->committeeName,
                committeeCode: $view->committeeCode,
                governanceLevel: $view->governanceLevel,
                hasActiveAssociation: $view->hasActiveAssociation,
                hasPendingApplication: $view->hasPendingApplication,
                canApply: $canApply,
                applicationStatus: $applicationStatus,
                joinedDate: null, // TODO: Query from lineage
                roleInCommittee: null, // TODO: Query from lineage
            );
        }, $eligible);
    }
}
