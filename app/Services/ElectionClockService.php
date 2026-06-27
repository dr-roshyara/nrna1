<?php

namespace App\Services;

use App\Models\Election;
use Carbon\Carbon;

/**
 * ElectionClockService: Abstract voting window time checks
 *
 * CONSTITUTIONAL FIX Phase 3.1.C: Replaces raw now() comparisons
 * with a proper temporal abstraction layer.
 *
 * Current implementation: Simple now() wrapper
 * Future: Can be extended to support election-specific timezones
 */
final class ElectionClockService
{
    /**
     * Check if voting has already started for this election.
     *
     * @param Election $election
     * @return bool
     */
    public static function hasVotingStarted(Election $election): bool
    {
        return $election->voting_starts_at && now()->gte($election->voting_starts_at);
    }

    /**
     * Check if voting has already ended for this election.
     *
     * @param Election $election
     * @return bool
     */
    public static function hasVotingEnded(Election $election): bool
    {
        return $election->voting_ends_at && now()->gt($election->voting_ends_at);
    }

    /**
     * Check if voting window is currently open.
     *
     * @param Election $election
     * @return bool
     */
    public static function isVotingOpen(Election $election): bool
    {
        if (!$election->voting_starts_at || !$election->voting_ends_at) {
            return false;
        }

        $now = now();
        return $now->gte($election->voting_starts_at) && $now->lt($election->voting_ends_at);
    }
}
