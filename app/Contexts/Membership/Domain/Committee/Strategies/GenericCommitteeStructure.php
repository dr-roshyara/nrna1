<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Strategies;

use App\Contexts\Membership\Domain\Models\Member;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\Role;

/**
 * GenericCommitteeStructure Strategy
 *
 * Business Rules for Generic Committees (e.g., Diaspora):
 * - No minimum membership years required
 * - No age or gender restrictions
 * - Geography optional (no geography validation in strategy)
 * - Standard role limits
 *
 * Political Context: Generic committees serve as fallback for committee types
 * without specific restrictions, such as Diaspora committees (members living abroad).
 * Also serves as default for any future committee types without specific rules.
 */
final class GenericCommitteeStructure implements CommitteeStructure
{
    /**
     * Role limits for Generic Committees
     *
     * Standard committee structure with unlimited regular members.
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

    public function validateAssignment(Member $member, Role $role): bool
    {
        // Generic committees have no special validation rules
        // Only basic requirement: member must be eligible for committee roles
        return $member->canHoldCommitteeRole();
    }

    public function getRoleLimits(): array
    {
        return self::ROLE_LIMITS;
    }

    public function getMinimumMembershipYears(): int
    {
        return 0; // No minimum membership requirement
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
        return false; // Geography optional for generic committees
    }

    public function getSupportedType(): CommitteeType
    {
        return CommitteeType::diaspora(); // Supports DIASPORA committee type
    }

    public function getDescription(): string
    {
        return 'Generic Committee: No restrictions (age, gender, membership years), geography optional';
    }
}