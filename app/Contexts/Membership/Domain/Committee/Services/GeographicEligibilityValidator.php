<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Services;

use App\Contexts\Membership\Domain\Committee\Factories\GeoPathChainFactory;
use App\Contexts\Membership\Domain\Committee\Policies\CommitteeEligibilityPolicy;
use App\Contexts\Membership\Domain\Committee\Projections\GeoSemanticProjection;

/**
 * GeographicEligibilityValidator — Domain service for geographic eligibility checks.
 *
 * Orchestrates projection building → chain derivation → policy evaluation.
 * Used by the application handler to validate member-committee assignments.
 */
final readonly class GeographicEligibilityValidator
{
    public function __construct(
        private readonly GeoSemanticProjectionBuilder $projectionBuilder,
        private readonly CommitteeEligibilityPolicy $policy,
    ) {}

    /**
     * Validate that a member (at $memberResidenceGeoUnitId) is eligible
     * for a committee (at $committeeGeoUnitId).
     *
     * @param int $memberResidenceGeoUnitId Member's residence geo unit ID
     * @param int $committeeGeoUnitId Committee's operational geo unit ID
     * @return bool True if member is geographically eligible
     */
    public function validate(int $memberResidenceGeoUnitId, int $committeeGeoUnitId): bool
    {
        $memberProjection = $this->projectionBuilder->build($memberResidenceGeoUnitId);
        $committeeProjection = $this->projectionBuilder->build($committeeGeoUnitId);

        if ($memberProjection === null || $committeeProjection === null) {
            return false;
        }

        $memberChain = GeoPathChainFactory::from($memberProjection);
        $committeeChain = GeoPathChainFactory::from($committeeProjection);

        return $this->policy->isEligible($committeeChain, $memberChain);
    }
}
