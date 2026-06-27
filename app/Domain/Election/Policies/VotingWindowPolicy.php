<?php

namespace App\Domain\Election\Policies;

use App\Models\Election;
use Carbon\CarbonInterface;

/**
 * Voting window temporal policy.
 *
 * Pure observational policy. Answers questions about voting window state.
 * Does NOT control lifecycle derivation — engine is sovereign.
 *
 * Boundary semantics:
 * - Window start (voting_starts_at): inclusive (gte)
 * - Window end (voting_ends_at): exclusive (lt)
 */
final class VotingWindowPolicy
{
    /**
     * Is the voting window defined (both start and end times set)?
     */
    public function hasWindowBeenDefined(Election $election): bool
    {
        return $election->voting_starts_at !== null
            && $election->voting_ends_at !== null;
    }

    /**
     * Is the voting window valid (start before end)?
     */
    public function isValidWindow(Election $election): bool
    {
        if (!$this->hasWindowBeenDefined($election)) {
            return false;
        }

        return $election->voting_starts_at->lt($election->voting_ends_at);
    }

    /**
     * Is the voting window currently open?
     *
     * Window is open when: now >= start AND now < end
     * Boundary: start is inclusive, end is exclusive.
     */
    public function isOpen(Election $election, CarbonInterface $now): bool
    {
        if (!$this->hasWindowBeenDefined($election)) {
            return false;
        }

        return $now->gte($election->voting_starts_at)
            && $now->lt($election->voting_ends_at);
    }

    /**
     * Has the voting window ended (passed)?
     *
     * Window is ended when: now >= end
     */
    public function hasEnded(Election $election, CarbonInterface $now): bool
    {
        if (!$this->hasWindowBeenDefined($election)) {
            return false;
        }

        return $now->gte($election->voting_ends_at);
    }

    /**
     * Is the voting window pending (not yet started)?
     *
     * Window is pending when: now < start
     */
    public function isPending(Election $election, CarbonInterface $now): bool
    {
        if (!$this->hasWindowBeenDefined($election)) {
            return false;
        }

        return $now->lt($election->voting_starts_at);
    }
}
