<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Strategies;

use App\Contexts\Membership\Domain\Models\Member;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\Role;

/**
 * GeographicCommitteeStructure Strategy
 *
 * Business Rules for Geographic Committees (Province, District, Ward):
 * - Member must live within committee's operational geography (validated by Committee aggregate)
 * - No minimum membership years required
 * - No age or gender restrictions
 * - Specific role limits similar to Central Committee
 *
 * Political Context: Geographic committees operate at specific administrative levels
 * (Province, District, Ward). Members must reside within the committee's operational area.
 * This is a generic strategy for all geographic committee types.
 *
 * Architecture Note: Geography validation is performed by Committee::coversGeography()
 * using Member::getResidenceGeoReference() and GeoReference::isWithinOrEqual().
 * This strategy only validates business rules (none beyond basic eligibility).
 */
final class GeographicCommitteeStructure implements CommitteeStructure
{
    /**
     * Role limits for Geographic Committees
     *
     * Similar to Central Committee but with fewer coordinators.
     * Typical Nepali political party structure at geographic levels:
     * - 1 Chairperson (President)
     * - 2 Vice-Chairpersons (Vice Presidents)
     * - 1 Secretary
     * - 1 Treasurer
     * - 3-5 Coordinators (geography-specific outreach)
     * - Unlimited regular members
     */
    private const ROLE_LIMITS = [
        'chairperson' => 1,
        'vice_chairperson' => 2,
        'secretary' => 1,
        'treasurer' => 1,
        'coordinator' => 5,
        'member' => null, // Unlimited regular members
    ];

    public function canAssignRole(string $rolePath): bool
    {
        // Geographic committees allow any reasonable role path (up to 3 levels deep)
        $segments = explode('.', $rolePath);
        return count($segments) <= 3;
    }

    public function validateAssignment(Member $member, Role $role): bool
    {
        // Geographic committees require active member status
        // Geography validation is performed separately by Committee aggregate
        return $member->canHoldCommitteeRole();
    }

    public function getRoleLimits(): array
    {
        return self::ROLE_LIMITS;
    }

    public function getMinimumMembershipYears(): int
    {
        return 0; // No minimum membership for geographic committees
    }

    public function getAgeRange(): ?array
    {
        return null; // No age restrictions
    }

    public function getGenderRequirement(): ?string
    {
        return null; // No gender restrictions
    }

    public function requiresGeography(): bool
    {
        return true; // Geographic committees require operational geography
    }

    public function getSupportedType(): CommitteeType
    {
        return CommitteeType::geographic();
    }

    public function getDescription(): string
    {
        return 'Geographic Committee (Province/District/Ward): No minimum membership, geography validated by Committee aggregate';
    }
}