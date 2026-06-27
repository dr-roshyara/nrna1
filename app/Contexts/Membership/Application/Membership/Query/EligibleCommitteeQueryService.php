<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\Query;

use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Eligible Committee Query Service
 *
 * Application-layer query service that orchestrates governance read operations.
 * Answers: "Which committees is this member constitutionally eligible to join?"
 *
 * NOT responsible for:
 * - Persistence or caching
 * - Mutations or domain events
 * - Voting eligibility (separate concern - use VotingEligibilityPolicy)
 * - Cross-context integration (that's Elections gateway's job in Phase B.3)
 *
 * Orchestrates:
 * - CommitteeRepository (find active committees)
 * - CommitteeEligibilityPolicy (geographic matching)
 * - MembershipLineageRepository (existing associations)
 * - MembershipApplicationRepository (pending applications)
 * - CommitteeGeoPathProviderPort (geo resolution for non-central committees)
 *
 * Returns read-only DTOs with no domain objects exposed.
 */
interface EligibleCommitteeQueryService
{
    /**
     * Find all constitutionally eligible committees for a member.
     *
     * Returns committees where member satisfies geographic eligibility rules:
     * - Central committees (geoUnitId=null): all members eligible
     * - Geographic committees (geoUnitId!=null): member path must match committee path
     *
     * Filters:
     * - Only active committees included
     * - Members with no geo (empty GeoPathChain) cannot join geographic committees
     *
     * Decoration:
     * - hasActiveAssociation: member already belongs (MembershipLineage.isActive)
     * - hasPendingApplication: active MembershipApplication exists
     *
     * @param MemberId $memberId Member to check eligibility for
     * @param TenantId $tenantId Tenant (organisation) context
     * @param GeoPathChain $memberGeoPath Member's geographic path (passed by caller, not resolved internally)
     * @return EligibleCommitteeView[] Eligible committees, sorted by level ASC then name ASC
     */
    public function eligibleForMember(
        MemberId $memberId,
        TenantId $tenantId,
        GeoPathChain $memberGeoPath,
    ): array;
}
