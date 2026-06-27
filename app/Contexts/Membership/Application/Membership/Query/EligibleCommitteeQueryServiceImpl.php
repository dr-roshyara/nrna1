<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\Query;

use App\Contexts\Membership\Application\Membership\Ports\MembershipApplicationRepositoryPort;
use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Membership\Application\Membership\Query\Ports\CommitteeGeoPathProviderPort;
use App\Contexts\Membership\Domain\Committee\Policies\CommitteeEligibilityPolicy;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId as CommitteeIdValue;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Eligible Committee Query Service Implementation
 *
 * Pure query orchestration without persistence, caching, or mutations.
 * Evaluates geographic eligibility, decorates with association state.
 */
final class EligibleCommitteeQueryServiceImpl implements EligibleCommitteeQueryService
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $committeeRepository,
        private readonly MembershipLineageRepositoryPort $membershipLineageRepository,
        private readonly MembershipApplicationRepositoryPort $membershipApplicationRepository,
        private readonly CommitteeEligibilityPolicy $committeeEligibilityPolicy,
        private readonly CommitteeGeoPathProviderPort $geoPathProvider,
    ) {}

    /**
     * @return EligibleCommitteeView[]
     */
    public function eligibleForMember(
        MemberId $memberId,
        TenantId $tenantId,
        GeoPathChain $memberGeoPath,
    ): array {
        // Load all active committees for tenant
        $committees = $this->committeeRepository->findByTenant($tenantId);
        $activeCommittees = array_filter(
            $committees,
            fn($committee) => $committee->getStatus()->isActive(),
        );

        // Evaluate eligibility for each active committee
        $eligibleCommittees = array_filter(
            $activeCommittees,
            fn($committee) => $this->isEligibleForCommittee($committee, $memberGeoPath),
        );

        // Decorate with association and application state
        $views = array_map(
            fn($committee) => $this->decorateWithState($committee, $memberId, $tenantId),
            $eligibleCommittees,
        );

        // Sort deterministically: governanceLevel ASC, then committeeName ASC
        usort($views, fn($a, $b) => $this->compareViews($a, $b));

        return array_values($views);
    }

    /**
     * Determine geographic eligibility for a committee.
     *
     * Rule 1 (GEO-ELIG-2): Central committees (geoUnitId=null) — all members eligible
     * Rule 2 (GEO-ELIG-1/3): Geographic committees (geoUnitId!=null) — path matching via policy
     *
     * Members with empty geo path cannot join geographic committees.
     */
    private function isEligibleForCommittee(
        $committee,
        GeoPathChain $memberGeoPath,
    ): bool {
        $geoUnitId = $committee->getGeoUnitId();

        // Central committee — always eligible
        if ($geoUnitId === null) {
            return true;
        }

        // Geographic committee — member must have geo path
        if ($memberGeoPath->isEmpty()) {
            return false;
        }

        // Resolve committee's geo path and evaluate policy
        $committeeGeoPath = $this->geoPathProvider->resolveForCommittee($geoUnitId);

        return $this->committeeEligibilityPolicy->isEligible(
            $committeeGeoPath,
            $memberGeoPath,
        );
    }

    /**
     * Decorate committee with association and application state.
     */
    private function decorateWithState(
        $committee,
        MemberId $memberId,
        TenantId $tenantId,
    ): EligibleCommitteeView {
        // Convert CommitteeId to the type expected by the repository port
        // (Codebase has two CommitteeId types; port expects Committee\ValueObjects\CommitteeId)
        $committeeId = CommitteeIdValue::fromString((string)$committee->getId());

        // Check for active membership association
        $lineage = $this->membershipLineageRepository->findLineageByMemberAndCommitteeForTenant(
            $memberId,
            $committeeId,
            $tenantId,
        );
        $hasActiveAssociation = $lineage !== null && $lineage->isActive();

        // Check for pending application
        $hasPendingApplication = $this->membershipApplicationRepository->existsActiveForTenant(
            $memberId,
            $committeeId,
            $tenantId,
        );

        return new EligibleCommitteeView(
            committeeId: (string)$committee->getId(),
            committeeName: $committee->getName()->value(),
            committeeCode: $committee->code(),
            governanceLevel: $committee->levelIndex() ?? \PHP_INT_MAX,
            hasActiveAssociation: $hasActiveAssociation,
            hasPendingApplication: $hasPendingApplication,
        );
    }

    /**
     * Comparison function for deterministic sorting.
     *
     * Primary: governanceLevel ASC (NULL treated as INT_MAX, shown last)
     * Secondary: committeeName ASC (alphabetical)
     */
    private function compareViews(EligibleCommitteeView $a, EligibleCommitteeView $b): int
    {
        if ($a->governanceLevel !== $b->governanceLevel) {
            return $a->governanceLevel <=> $b->governanceLevel;
        }

        return $a->committeeName <=> $b->committeeName;
    }
}
