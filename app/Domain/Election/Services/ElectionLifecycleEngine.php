<?php

namespace App\Domain\Election\Services;

use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use App\Models\Election;

/**
 * Single Source of Truth for election lifecycle.
 *
 * Computes the canonical current state of an election from all signals:
 * - State machine value
 * - Time windows (voting starts/ends)
 * - Setup completion (administration_completed, nomination_completed)
 * - Results state (results_published_at)
 *
 * Returns immutable snapshot with:
 * - Current state
 * - Permission flags (canEdit, canVote, etc.)
 * - Lock status
 * - Explanation of blocked actions
 * - List of allowed next actions
 */
interface ElectionLifecycleEngine
{
    /**
     * Compute the current lifecycle snapshot for an election.
     *
     * @param Election $election The election aggregate root
     * @return ElectionLifecycleSnapshot Immutable read model with all state info
     */
    public function compute(Election $election): ElectionLifecycleSnapshot;

    /**
     * Get the current state without full snapshot (optimization).
     *
     * @param Election $election
     * @return ElectionLifecycleState
     */
    public function getState(Election $election): ElectionLifecycleState;
}
