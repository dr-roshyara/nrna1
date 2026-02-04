<?php

namespace App\Services;

use App\Models\DemoVote;
use App\Models\DemoResult;

/**
 * DemoVotingService
 *
 * Voting service for DEMO elections.
 * Extends VotingService with demo election-specific logic.
 * Uses DemoVote and DemoResult models, stores in demo_votes and demo_results tables.
 *
 * Demo elections are for testing purposes:
 * - Can be reset/truncated for testing
 * - Data is temporary
 * - No production impact
 */
class DemoVotingService extends VotingService
{
    /**
     * Get the demo vote model
     *
     * @return string
     */
    public function getVoteModel(): string
    {
        return DemoVote::class;
    }

    /**
     * Get the demo result model
     *
     * @return string
     */
    public function getResultModel(): string
    {
        return DemoResult::class;
    }

    /**
     * Check if this is a demo service (always true)
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return true;
    }

    /**
     * Check if this is a real service (always false)
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return false;
    }

    /**
     * Demo-specific: Verify vote integrity
     * Checks that vote belongs to demo election and is properly formatted
     *
     * @param \App\Models\DemoVote $vote
     * @return bool
     */
    public function verifyVoteIntegrity(DemoVote $vote): bool
    {
        return $vote->election_id === $this->election->id
            && $vote->isDemo();
    }

    /**
     * Demo-specific: Get election statistics
     * Returns detailed stats for demo election results
     *
     * @return array
     */
    public function getElectionStatistics(): array
    {
        return [
            'total_votes' => $this->getVoteCount(),
            'unique_voters' => $this->getVotes()->distinct('user_id')->count(),
            'election_type' => 'demo',
            'is_active' => $this->election->isCurrentlyActive(),
        ];
    }

    /**
     * Demo-specific: Cleanup old demo votes
     * Remove demo votes older than specified days
     *
     * @param int $days
     * @return int Number of votes deleted
     */
    public function cleanupOlderThan(int $days = 7): int
    {
        return DemoVote::where('election_id', $this->election->id)
            ->where('created_at', '<', now()->subDays($days))
            ->delete();
    }

    /**
     * Demo-specific: Reset demo election
     * Clear all demo votes and results for fresh testing
     *
     * @return array Summary of cleared data
     */
    public function reset(): array
    {
        $voteCount = $this->deleteAllVotes();
        $resultCount = DemoResult::where('election_id', $this->election->id)->delete();

        return [
            'votes_deleted' => $voteCount,
            'results_deleted' => $resultCount,
            'election_id' => $this->election->id,
            'cleared_at' => now(),
        ];
    }
}
