<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Strategies;

use App\Contexts\Membership\Domain\Models\Member;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\Role;

/**
 * WomenWingStructure Strategy
 *
 * Business Rules for Women Wing:
 * - Women only (gender requirement)
 * - Age 18+ years (minimum age)
 * - No minimum membership years required
 * - Geography optional (can be national or regional)
 *
 * Political Context: Women wings focus on women's participation and
 * gender issues within political parties. Mandatory in Nepali politics.
 */
final class WomenWingStructure implements CommitteeStructure
{
    /**
     * Role limits for Women Wing
     *
     * Similar structure to main committee but focused on women's issues.
     */
    private const ROLE_LIMITS = [
        'chairperson' => 1,
        'vice_chairperson' => 2,
        'secretary' => 1,
        'treasurer' => 1,
        'coordinator' => 8,
        'member' => null, // Unlimited women members
    ];

    public function validateAssignment(Member $member, Role $role): bool
    {
        // 1. Must be eligible for committee roles (basic requirement)
        if (!$member->canHoldCommitteeRole()) {
            return false;
        }

        // 2. MVP: Gender validation pending Member::getGender() implementation
        // TODO: Implement gender validation when Member entity supports it
        // $gender = $member->getGender();
        // if ($gender !== 'female') {
        //     return false;
        // }

        // 3. MVP: Age validation pending Member::getAge() implementation
        // TODO: Implement age validation when Member entity supports it
        // $age = $member->getAge();
        // if ($age === null || $age < 18) {
        //     return false;
        // }

        // 4. MVP: Accept all eligible members
        // Gender and age validation will be added in Phase 2
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
        return 0; // No minimum membership for Women Wing
    }

    public function getAgeRange(): ?array
    {
        return [18, null]; // Women Wing: 18+ years (no upper limit)
    }

    public function getGenderRequirement(): ?string
    {
        return 'female'; // Women Wing: Women only
    }

    public function requiresGeography(): bool
    {
        return false; // Geography optional for Women Wing
    }

    public function getSupportedType(): CommitteeType
    {
        return CommitteeType::womenWing(); // ✅ FIXED: Use womenWing() not women()
    }

    public function getDescription(): string
    {
        return 'Women Wing: Women only, age 18+ (MVP: validation pending), geography optional';
    }
}