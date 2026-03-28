<?php

namespace App\Services;

use App\Models\Vote;
use App\Models\Result;

/**
 * RealVotingService
 *
 * Voting service for REAL elections.
 * Extends VotingService with real election-specific logic.
 * Uses Vote and Result models, stores in votes and results tables.
 */
class RealVotingService extends VotingService
{
    /**
     * Get the real vote model
     *
     * @return string
     */
    public function getVoteModel(): string
    {
        return Vote::class;
    }

    /**
     * Get the real result model
     *
     * @return string
     */
    public function getResultModel(): string
    {
        return Result::class;
    }

    /**
     * Check if this is a demo service (always false)
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return false;
    }

    /**
     * Check if this is a real service (always true)
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return true;
    }

    /**
     * Real-specific: Verify vote integrity
     * Checks that vote belongs to real election and is properly formatted
     *
     * @param \App\Models\Vote $vote
     * @return bool
     */
    public function verifyVoteIntegrity(Vote $vote): bool
    {
        return $vote->election_id === $this->election->id
            && !$vote->isDemo();
    }

    /**
     * Real-specific: Get election statistics
     * Returns detailed stats for real election results
     *
     * @return array
     */
    public function getElectionStatistics(): array
    {
        return [
            'total_votes' => $this->getVoteCount(),
            'unique_voters' => $this->getVotes()->distinct('user_id')->count(),
            'election_type' => 'real',
            'is_active' => $this->election->isCurrentlyActive(),
        ];
    }
}
