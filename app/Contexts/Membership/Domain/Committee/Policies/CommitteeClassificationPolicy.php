<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Policies;

use App\Contexts\Membership\Domain\Committee\Projections\GeoSemanticProjection;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeCategory;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;

/**
 * CommitteeClassificationPolicy
 *
 * Governance-owned classification. Consumes GeoSemanticProjection (NOT raw
 * geoUnitId) and optional wing category to derive CommitteeType.
 *
 * Admin Level Convention (Membership interpretation):
 *   0 = continent       → CENTRAL
 *   1 = country         → CENTRAL
 *   2 = province/state  → GEOGRAPHIC
 *   3 = district        → GEOGRAPHIC
 *   4+ = local          → GEOGRAPHIC
 *
 * Invariant I-06: Same projection → same CommitteeType (deterministic).
 * Invariant I-07: geoUnitId is the only persistent geo reference.
 *
 * FORBIDDEN:
 *  - NO direct access to GeographicJurisdictionProvider
 *  - NO dependency on CommitteeStructure
 *  - NO identity construction
 *  - NO geographic domain logic (only governance interpretation)
 */
final readonly class CommitteeClassificationPolicy
{
    public function resolve(
        GeoSemanticProjection $projection,
        ?CommitteeCategory $wing = null,
    ): CommitteeType {
        if ($wing !== null && $wing->isWingType()) {
            return match ($wing) {
                CommitteeCategory::YOUTH => CommitteeType::youthWing(),
                CommitteeCategory::WOMEN => CommitteeType::womenWing(),
                CommitteeCategory::STUDENT => CommitteeType::studentWing(),
            };
        }

        return match (true) {
            $projection->adminLevel <= 1 => CommitteeType::central(),
            $projection->adminLevel === 2,
            $projection->adminLevel === 3,
            $projection->adminLevel >= 4 => CommitteeType::geographic(),
        };
    }

    public function isGeographicEligible(GeoSemanticProjection $projection): bool
    {
        return $projection->adminLevel >= 2;
    }

    /**
     * Map admin level to CommitteeCategory (Phase 8C.2D).
     *
     * Admin Level Convention (Membership interpretation):
     *   0 = continent       → CENTRAL
     *   1 = country         → CENTRAL
     *   2 = province/state  → PROVINCE
     *   3 = district        → DISTRICT
     *   4+ = local          → WARD
     *
     * Wing types (YOUTH/WOMEN/STUDENT) have no admin level mapping.
     */
    public function mapAdminLevelToCategory(int $adminLevel): CommitteeCategory
    {
        return match (true) {
            $adminLevel <= 1 => CommitteeCategory::CENTRAL,
            $adminLevel === 2 => CommitteeCategory::PROVINCE,
            $adminLevel === 3 => CommitteeCategory::DISTRICT,
            $adminLevel >= 4 => CommitteeCategory::WARD,
        };
    }
}
