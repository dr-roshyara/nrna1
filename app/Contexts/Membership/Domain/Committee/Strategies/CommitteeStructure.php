<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Strategies;

use App\Contexts\Membership\Domain\Models\Member;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\Role;

/**
 * CommitteeStructure Interface (Strategy Pattern)
 *
 * Different committee types in political parties have different business rules:
 *
 * Nepali Political Party Committee Structure:
 * - CENTRAL COMMITTEE: National level, no geography, 2+ years membership required
 * - PROVINCE COMMITTEE: Province level, member must live in same province
 * - DISTRICT COMMITTEE: District level, member must live in same district
 * - WARD COMMITTEE: Ward level, member must live in same ward
 * - YOUTH WING: Age 18-35, geography optional
 * - WOMEN WING: Women only, age 18+, geography optional
 * - DIASPORA COMMITTEE: Members living abroad, foreign country geography
 *
 * This interface defines business rules ONLY. Geography validation is handled
 * separately by the Committee aggregate using GeographyLookupInterface.
 */
interface CommitteeStructure
{
    /**
     * Validate if a member can be assigned to this committee with given role
     *
     * Validates business rules only (age, gender, membership duration).
     * Geography validation is handled separately by Committee::coversGeography().
     *
     * @param Member $member The member to assign
     * @param Role $role The role to assign
     * @return bool True if assignment satisfies business rules
     */
    public function validateAssignment(Member $member, Role $role): bool;

    /**
     * Get role limits for this committee type
     *
     * Political parties have specific role limits:
     * - Central Committee: 1 Chairperson, 2 Vice-Chairpersons, etc.
     * - Other committees follow similar hierarchical limits
     *
     * @return array<string, int|null> Role value => maximum count (null = unlimited)
     */
    public function getRoleLimits(): array;

    /**
     * Get minimum membership years required
     *
     * Central committees often require longer membership.
     * Example: Central Committee = 2+ years, others = 0 years
     *
     * @return int Minimum years of membership required
     */
    public function getMinimumMembershipYears(): int;

    /**
     * Get age requirements for this committee
     *
     * Youth Wing: 18-35 years
     * Other committees: No age restrictions
     *
     * @return array|null [min_age, max_age] or null for no restrictions
     */
    public function getAgeRange(): ?array;

    /**
     * Get gender requirement for this committee
     *
     * Women Wing: 'female' only
     * Other committees: No gender restrictions
     *
     * @return string|null 'male', 'female', or null for no restriction
     */
    public function getGenderRequirement(): ?string;

    /**
     * Check if this committee type requires operational geography
     *
     * Central Committee: No geography
     * Geographic committees (Province, District, Ward): Geography required
     * Youth/Women Wings: Geography optional
     * Diaspora: Foreign country geography required
     *
     * @return bool True if geography is required for committee formation
     */
    public function requiresGeography(): bool;

    /**
     * Get the committee type this strategy supports
     *
     * Each strategy implementation supports exactly one committee type.
     * Committee aggregate uses this to match strategy with committee type.
     *
     * @return CommitteeType The supported committee type
     */
    public function getSupportedType(): CommitteeType;

    /**
     * Get description of committee type rules
     *
     * For display and debugging purposes.
     *
     * @return string Human-readable description of committee rules
     */
    public function getDescription(): string;
}