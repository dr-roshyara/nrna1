<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Policies;

use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;

/**
 * CommitteeEligibilityPolicy — Pure domain policy for geographic eligibility.
 *
 * Determines whether a member (with a given residence geography) is eligible
 * for assignment to a committee (with a given operational geography).
 *
 * ZERO dependencies. Deterministic. Pure GeoPathChain comparison.
 *
 * Business Rule GEO-ELIG-1:
 * "A member is eligible for a committee if the committee's geographic anchor
 *  is an ancestor of (or equal to) the member's residence geo unit."
 *
 * Business Rule GEO-ELIG-2:
 * "Central committees (no geography anchor) are eligible for ALL members."
 *
 * Business Rule GEO-ELIG-3:
 * "Members without residence geography cannot be assigned to geographic committees."
 */
final readonly class CommitteeEligibilityPolicy
{
    /**
     * Check if a member is eligible for a committee based on geography.
     *
     * @param GeoPathChain $committee The committee's geographic chain (may be empty for central)
     * @param GeoPathChain $member The member's residence geographic chain (may be empty)
     * @return bool True if member is geographically eligible
     */
    public function isEligible(GeoPathChain $committee, GeoPathChain $member): bool
    {
        // GEO-ELIG-2: Central committees (empty path) cover all members
        if ($committee->path === '') {
            return true;
        }

        // GEO-ELIG-3: Geographic committees require member to have geography
        if ($member->path === '') {
            return false;
        }

        // GEO-ELIG-1: Member is within committee if member's path starts with committee's path
        return $member->startsWith($committee->path);
    }
}
