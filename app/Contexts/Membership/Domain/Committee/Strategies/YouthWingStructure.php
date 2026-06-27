<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Strategies;

use App\Contexts\Membership\Domain\Models\Member;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\Role;

/**
 * YouthWingStructure Strategy
 *
 * Business Rules for Youth Wing:
 * - Age 18-35 years (inclusive)
 * - No minimum membership years required
 * - Geography optional (can be national or regional)
 * - No gender restrictions
 *
 * Political Context: Youth wings attract young members and prepare
 * future party leaders. Common in Nepali political parties.
 *
 * MVP Note: Age validation pending Member::getAge() implementation.
 * For MVP, accepts all eligible members.
 */
final class YouthWingStructure implements CommitteeStructure
{
    /**
     * Role limits for Youth Wing
     *
     * Youth wing structure typically mirrors main committee but with
     * focus on youth leadership development.
     */
    private const ROLE_LIMITS = [
        'chairperson' => 1,
        'vice_chairperson' => 2,
        'secretary' => 1,
        'treasurer' => 1,
        'coordinator' => 10, // More coordinators for youth outreach
        'member' => null, // Unlimited youth members
    ];

    public function validateAssignment(Member $member, Role $role): bool
    {
        // 1. Must be eligible for committee roles (basic requirement)
        if (!$member->canHoldCommitteeRole()) {
            return false;
        }

        // 2. MVP: Age validation pending Member::getAge() implementation
        // TODO: Implement age validation when Member entity supports it
        // $age = $member->getAge();
        // if ($age === null || $age < 18 || $age > 35) {
        //     return false;
        // }

        // 3. MVP: Accept all eligible members
        // This ensures committee assignment works during MVP phase
        // Age validation will be added in Phase 2
        return true;
    }

    public function canAssignRole(string $rolePath): bool
    {
        $segments = explode('.', $rolePath);
        return count($segments) <= 3;
    }

    public function getRoleLimits(): array
    {
        return self::ROLE_LIMITS;
    }

    public function getMinimumMembershipYears(): int
    {
        return 0; // No minimum membership for Youth Wing
    }

    public function getAgeRange(): ?array
    {
        return [18, 35]; // Youth Wing: 18-35 years (inclusive)
    }

    public function getGenderRequirement(): ?string
    {
        return null; // No gender restrictions
    }

    public function requiresGeography(): bool
    {
        return false; // Geography optional for Youth Wing
    }

    public function getSupportedType(): CommitteeType
    {
        return CommitteeType::youth(); // Returns CommitteeType::YOUTH_WING
    }

    public function getDescription(): string
    {
        return 'Youth Wing: Age 18-35 (MVP: age validation pending), geography optional';
    }
}