<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Strategies;

use App\Contexts\Membership\Domain\Models\Member;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\Role;

/**
 * StudentWingStructure Strategy
 *
 * Business Rules for Student Wing:
 * - Student status required (MVP: validation pending)
 * - Age 16-30 years (typical student age range)
 * - No minimum membership years required
 * - Geography optional (can be national or regional)
 * - No gender restrictions
 *
 * Political Context: Student wings focus on student members and
 * campus politics. Common in Nepali political parties.
 *
 * MVP Note: Student status validation pending Member::isStudent() implementation.
 * For MVP, accepts all eligible members.
 */
final class StudentWingStructure implements CommitteeStructure
{
    /**
     * Role limits for Student Wing
     *
     * Student wing structure typically mirrors youth wing with
     * focus on student leadership development.
     */
    private const ROLE_LIMITS = [
        'chairperson' => 1,
        'vice_chairperson' => 2,
        'secretary' => 1,
        'treasurer' => 1,
        'coordinator' => 10, // More coordinators for student outreach
        'member' => null, // Unlimited student members
    ];

    public function validateAssignment(Member $member, Role $role): bool
    {
        // 1. Must be eligible for committee roles (basic requirement)
        if (!$member->canHoldCommitteeRole()) {
            return false;
        }

        // 2. MVP: Student status validation pending Member::isStudent() implementation
        // TODO: Implement student status validation when Member entity supports it
        // if (!$member->isStudent()) {
        //     return false;
        // }

        // 3. MVP: Age validation pending Member::getAge() implementation
        // TODO: Implement age validation when Member entity supports it
        // $age = $member->getAge();
        // if ($age === null || $age < 16 || $age > 30) {
        //     return false;
        // }

        // 4. MVP: Accept all eligible members
        // Student status and age validation will be added in Phase 2
        return true;
    }

    public function getRoleLimits(): array
    {
        return self::ROLE_LIMITS;
    }

    public function getMinimumMembershipYears(): int
    {
        return 0; // No minimum membership for Student Wing
    }

    public function getAgeRange(): ?array
    {
        return [16, 30]; // Student Wing: 16-30 years (inclusive)
    }

    public function getGenderRequirement(): ?string
    {
        return null; // No gender restrictions
    }

    public function requiresGeography(): bool
    {
        return false; // Geography optional for Student Wing
    }

    public function getSupportedType(): CommitteeType
    {
        return CommitteeType::student(); // Returns CommitteeType::STUDENT_WING
    }

    public function getDescription(): string
    {
        return 'Student Wing: Student status required (MVP: validation pending), age 16-30, geography optional';
    }
}