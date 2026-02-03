<?php

namespace App\Models;

/**
 * DemoResult Model - Demo Election Results
 *
 * Extends BaseResult to inherit all shared result aggregation logic.
 * This model represents aggregated results from DEMO elections.
 *
 * Table: demo_results (separate from results table)
 * Inheritance: DemoResult extends BaseResult
 * Siblings: Result (for real election results)
 *
 * Demo results are stored in a separate demo_results table to ensure:
 * - Complete physical separation from real election results
 * - Easy cleanup and testing (can truncate demo_results table)
 * - No risk of demo results contaminating real election data
 *
 * Same result aggregation logic as Result, but different table/lifecycle.
 */
class DemoResult extends BaseResult
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demo_results';

    /**
     * Get the demo vote this result is from
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vote()
    {
        return $this->belongsTo(DemoVote::class, 'vote_id');
    }

    /**
     * Check if this is a real result (always false for this class)
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return false;
    }

    /**
     * Check if this is a demo result (always true for this class)
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return true;
    }

    /**
     * Scope: Get demo results from current testing session
     * Demo results are typically short-lived for testing purposes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentSession($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    /**
     * Delete all demo results older than N days
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
