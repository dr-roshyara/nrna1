<?php

namespace App\Models;

/**
 * Result Model - Real Election Results
 *
 * Extends BaseResult to inherit all shared result aggregation logic.
 * This model represents aggregated results from REAL elections.
 *
 * Table: results
 * Inheritance: Result extends BaseResult
 * Siblings: DemoResult (for demo election results)
 *
 * Real results are stored in a separate results table (vs demo_results)
 * to ensure complete separation of real voting data from test data.
 */
class Result extends BaseResult
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'results';

    /**
     * Get the real vote this result is from
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }

    /**
     * Check if this is a real result (always true for this class)
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return true;
    }

    /**
     * Check if this is a demo result (always false for this class)
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return false;
    }
}
