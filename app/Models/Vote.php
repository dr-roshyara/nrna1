<?php

namespace App\Models;

/**
 * Vote Model - Real Election Votes
 *
 * Extends BaseVote to inherit all shared voting logic.
 * This model represents votes cast in REAL elections.
 *
 * Table: votes
 * Inheritance: Vote extends BaseVote
 * Siblings: DemoVote (for demo elections)
 *
 * Demo votes are stored in a separate demo_votes table to ensure
 * complete physical and logical separation from real voting data.
 */
class Vote extends BaseVote
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'votes';

    /**
     * Get all results aggregated from this vote
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Check if this is a real vote (always true for this class)
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return true;
    }

    /**
     * Check if this is a demo vote (always false for this class)
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return false;
    }
}
