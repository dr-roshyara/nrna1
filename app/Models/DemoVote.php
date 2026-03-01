<?php

namespace App\Models;

/**
 * DemoVote Model - Demo Election Votes
 *
 * Extends BaseVote to inherit all shared voting logic.
 * This model represents votes cast in DEMO elections for testing.
 *
 * Table: demo_votes (separate from votes table)
 * Inheritance: DemoVote extends BaseVote
 * Siblings: Vote (for real elections)
 *
 * Demo votes are stored in a separate demo_votes table to ensure:
 * - Complete physical separation from real voting data
 * - Easy cleanup and testing (can truncate demo_votes table)
 * - No risk of demo data contaminating real results
 *
 * Same voting business logic as Vote, but different table/lifecycle.
 */
class DemoVote extends BaseVote
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demo_votes';

    /**
     * Get all results aggregated from this demo vote
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(DemoResult::class);
    }

    /**
     * Check if this is a real vote (always false for this class)
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return false;
    }

    /**
     * Check if this is a demo vote (always true for this class)
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return true;
    }

    /**
     * Scope: Get demo votes for current testing session
     * Demo votes are typically short-lived for testing purposes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentSession($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    /**
     * Delete all demo votes older than N days
     * Useful for scheduled cleanup of old test data.
     *
     * @param int $days
     * @return int
     */
    public static function cleanupOlderThan(int $days = 7): int
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }
}
