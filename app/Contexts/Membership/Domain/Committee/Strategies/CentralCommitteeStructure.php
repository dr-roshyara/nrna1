<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Strategies;

use App\Contexts\Membership\Domain\Models\Member;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\Role;

/**
 * CentralCommitteeStructure Strategy
 *
 * Business Rules for Central Committee (National Level):
 * - 2+ years of membership required
 * - No geography requirement (national level)
 * - No age or gender restrictions
 * - Specific role limits (1 Chairperson, etc.)
 *
 * Political Context: Central Committee is the highest decision-making body
 * in Nepali political parties, consisting of senior party members.
 */
final class CentralCommitteeStructure implements CommitteeStructure
{
    /**
     * Role limits for Central Committee
     *
     * Typical Nepali political party structure:
     * - 1 Chairperson (President)
     * - 2 Vice-Chairpersons (Vice Presidents)
     * - 1 General Secretary
     * - 2 Deputy General Secretaries
     * - 1 Treasurer
     * - Unlimited regular members
     */
    private const ROLE_LIMITS = [
        'chairperson' => 1,
        'vice_chairperson' => 2,
        'secretary' => 1,
        'treasurer' => 1,
        'coordinator' => 5,
        'advisor' => 3,
        'member' => null, // Unlimited regular members
    ];

    public function canAssignRole(string $rolePath): bool
    {
        // Central Committee allows role paths in standard hierarchy (1-3 levels deep)
        // Examples: '1', '1.1', '1.1.1', '2.1', etc.
        $segments = explode('.', $rolePath);

        // Allow up to 3 levels deep
        if (count($segments) > 3) {
            return false;
        }

        // First level should be 1-7 (7 major role categories)
        $firstLevel = (int) $segments[0];
        if ($firstLevel < 1 || $firstLevel > 7) {
            return false;
        }

        return true;
    }

    public function validateAssignment(Member $member, Role $role): bool
    {
        // Central Committee requires active member status
        // Note: For MVP, we use status check instead of membership duration
        // TODO: Add membership duration check when Member entity supports it
        return $member->canHoldCommitteeRole();
    }

    public function getRoleLimits(): array
    {
        return self::ROLE_LIMITS;
    }

    public function getMinimumMembershipYears(): int
    {
        return 2; // 2+ years required for Central Committee
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
        return false; // Central Committee has no operational geography
    }

    public function getSupportedType(): CommitteeType
    {
        return CommitteeType::central();
    }

    public function getDescription(): string
    {
        return 'Central Committee (National Level): 2+ years membership required, no geography';
    }
}