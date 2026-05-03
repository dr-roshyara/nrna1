<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

/**
 * CommitteeStatus Enum
 *
 * Represents the lifecycle status of a political party committee.
 *
 * Political Party Committee Statuses:
 * - ACTIVE: Committee is operational and can accept new member assignments
 * - INACTIVE: Committee exists but not operational (cannot accept assignments)
 * - DISSOLVED: Committee formally dissolved (cannot accept assignments)
 * - SUSPENDED: Committee temporarily suspended (cannot accept assignments)
 */
enum CommitteeStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case DISSOLVED = 'dissolved';
    case SUSPENDED = 'suspended';

    /**
     * Check if committee is active
     */
    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * Check if committee can accept new member assignments
     */
    public function canAcceptAssignments(): bool
    {
        return $this === self::ACTIVE;
    }
}