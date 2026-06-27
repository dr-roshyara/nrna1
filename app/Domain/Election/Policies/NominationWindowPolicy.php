<?php

namespace App\Domain\Election\Policies;

use App\Models\Election;
use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * Nomination window temporal policy.
 *
 * Pure observational policy. Answers questions about nomination window state.
 * Does NOT control lifecycle derivation — engine is sovereign.
 *
 * Boundary semantics:
 * - Window start (nomination_suggested_start): inclusive (gte)
 * - Window end (nomination_suggested_end): exclusive (lt)
 */
final class NominationWindowPolicy
{
    /**
     * Is the nomination window defined (both start and end dates set)?
     */
    public function hasNominationWindow(Election $election): bool
    {
        return $election->nomination_suggested_start !== null
            && $election->nomination_suggested_end !== null;
    }

    /**
     * Is the nomination window valid (start before end)?
     */
    public function isValidWindow(Election $election): bool
    {
        if (!$this->hasNominationWindow($election)) {
            return false;
        }

        return $election->nomination_suggested_start->lt($election->nomination_suggested_end);
    }

    /**
     * Is the nomination window currently open?
     *
     * Window is open when: now >= start AND now < end
     * Boundary: start is inclusive, end is exclusive.
     */
    public function isOpen(Election $election, CarbonInterface $now): bool
    {
        if (!$this->hasNominationWindow($election)) {
            return false;
        }

        return $now->gte($election->nomination_suggested_start)
            && $now->lt($election->nomination_suggested_end);
    }

    /**
     * Has the nomination window expired (passed)?
     *
     * Window is expired when: now > end
     */
    public function isExpired(Election $election, CarbonInterface $now): bool
    {
        if (!$this->hasNominationWindow($election)) {
            return false;
        }

        return $now->gt($election->nomination_suggested_end);
    }

    /**
     * Is the nomination window pending (not yet started)?
     *
     * Window is pending when: now < start
     */
    public function isPending(Election $election, CarbonInterface $now): bool
    {
        if (!$this->hasNominationWindow($election)) {
            return false;
        }

        return $now->lt($election->nomination_suggested_start);
    }
}
