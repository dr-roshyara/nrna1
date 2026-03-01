<?php

namespace App\Services;

use App\Models\Election;
use App\Models\User;

/**
 * VotingService Base Class
 *
 * Abstract base class defining voting operations.
 * Subclasses (RealVotingService, DemoVotingService) implement specific vote models.
 * Same business logic, different models/tables.
 */
abstract class VotingService
{
    /**
     * The election this service operates on
     *
     * @var \App\Models\Election
     */
    protected Election $election;

    /**
     * The vote model class to use (Vote::class or DemoVote::class)
     *
     * @var string
     */
    abstract protected function getVoteModel(): string;

    /**
     * The result model class to use (Result::class or DemoResult::class)
     *
     * @var string
     */
    abstract protected function getResultModel(): string;

    /**
     * Constructor
     *
     * @param \App\Models\Election $election
     */
    public function __construct(Election $election)
    {
        $this->election = $election;
    }

    /**
     * Get the election this service operates on
     *
     * @return \App\Models\Election
     */
    public function getElection(): Election
    {
        return $this->election;
    }

    /**
     * Check if this is a demo voting service
     *
     * @return bool
     */
    abstract public function isDemo(): bool;

    /**
     * Check if this is a real voting service
     *
     * @return bool
     */
    abstract public function isReal(): bool;

    /**
     * Create a vote record
     *
     * @param \App\Models\User $user
     * @param array $voteData
     * @return mixed Vote or DemoVote instance
     */
    public function createVote(User $user, array $voteData)
    {
        $voteModel = $this->getVoteModel();

        return $voteModel::create([
            'user_id' => $user->id,
            'election_id' => $this->election->id,
            ...$voteData,
        ]);
    }

    /**
     * Get all votes for this election
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getVotes()
    {
        $voteModel = $this->getVoteModel();

        return $voteModel::where('election_id', $this->election->id);
    }

    /**
     * Get vote count for this election
     *
     * @return int
     */
    public function getVoteCount(): int
    {
        return $this->getVotes()->count();
    }

    /**
     * Check if user has already voted in this election
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function userHasVoted(User $user): bool
    {
        $voteModel = $this->getVoteModel();

        return $voteModel::where('user_id', $user->id)
            ->where('election_id', $this->election->id)
            ->exists();
    }

    /**
     * Get user's vote for this election
     *
     * @param \App\Models\User $user
     * @return mixed Vote or DemoVote instance, or null
     */
    public function getUserVote(User $user)
    {
        $voteModel = $this->getVoteModel();

        return $voteModel::where('user_id', $user->id)
            ->where('election_id', $this->election->id)
            ->first();
    }

    /**
     * Delete all votes for this election
     * Used for cleanup, especially with demo elections
     *
     * @return int Number of votes deleted
     */
    public function deleteAllVotes(): int
    {
        return $this->getVotes()->delete();
    }

    /**
     * Get top candidates by vote count for a post
     *
     * @param string $postId
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getTopCandidates(string $postId, int $limit = 10)
    {
        $resultModel = $this->getResultModel();

        return $resultModel::where('election_id', $this->election->id)
            ->forPost($postId)
            ->selectRaw('candidate_id, COUNT(*) as vote_count')
            ->groupBy('candidate_id')
            ->orderByDesc('vote_count')
            ->limit($limit)
            ->get();
    }
}
