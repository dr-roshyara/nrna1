<?php

namespace App\Domain\Election\Enum;

use App\Models\Organisation;

/**
 * ElectionMode — Domain bifurcation point
 *
 * Represents the two operational modes for elections:
 *
 * FullMembership (uses_full_membership=true):
 * - Requires member table + fees validation
 * - Membership type grants voting rights (if set)
 * - More restrictive, organization-level governance
 *
 * ElectionOnly (uses_full_membership=false):
 * - Only requires organisation_users table
 * - Simpler, faster, no fee validation
 * - Lightweight election participation
 *
 * This enum is the gateway for Phase B policy routing:
 * Different eligibility rules apply depending on mode.
 */
enum ElectionMode: string
{
    case FullMembership = 'full_membership';
    case ElectionOnly = 'election_only';

    /**
     * Load mode from organisation's uses_full_membership flag
     *
     * Bridges old boolean column to new enum-based routing
     */
    public static function fromOrganisation(Organisation $organisation): self
    {
        return $organisation->uses_full_membership
            ? self::FullMembership
            : self::ElectionOnly;
    }

    /**
     * Check if this is election-only mode
     */
    public function isElectionOnly(): bool
    {
        return $this === self::ElectionOnly;
    }

    /**
     * Check if this is full membership mode
     */
    public function isFullMembership(): bool
    {
        return $this === self::FullMembership;
    }

    /**
     * Human-readable label for UI/logs
     */
    public function label(): string
    {
        return match($this) {
            self::ElectionOnly => 'Election-Only',
            self::FullMembership => 'Full Membership',
        };
    }
}
