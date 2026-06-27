<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * DivergenceObservationWindow
 *
 * Constitutional Evidence Model
 *
 * Captures divergence between procedural sovereignty (H.1-H.3)
 * and constitutional sovereignty (PolicySequence) during D.0.1 observation window.
 *
 * NOT a security model. This is pure constitutional archaeology.
 * Each observation record is evidence about legitimacy convergence.
 *
 * Preserved dimensions:
 * - route (which endpoint diverged)
 * - user_id (which voter)
 * - registered_ip (procedural authority sees)
 * - current_ip (runtime sees)
 * - timestamp_window (when divergence occurred)
 * - user_agent (context)
 * - divergence_frequency (how often)
 * - outcome_category (ALLOWED vs BLOCKED vs DEFERRED)
 * - source_finding (which H-finding caused divergence: H.1, H.2, H.3, etc)
 * - replay_notes (archaeological reasoning)
 */
class DivergenceObservationWindow extends Model
{
    protected $table = 'divergence_observation_windows';

    protected $fillable = [
        'election_id',
        'user_id',
        'route',
        'timestamp_window_start',
        'timestamp_window_end',
        'registered_ip',
        'current_ip',
        'user_agent',
        'procedural_outcome',           // What H.1-H.3 path returned
        'constitutional_outcome',       // What PolicySequence would return
        'divergence_type',              // ALLOWED_vs_DENIED, IP_MISMATCH, etc
        'source_finding',               // H.1, H.2, H.3, H.4, etc
        'divergence_frequency',         // How many times observed
        'is_deterministic',             // Always diverges same way?
        'replay_notes',                 // Archaeological context
        'observation_window_id',        // Links to parent aggregation window
    ];

    protected $casts = [
        'timestamp_window_start' => 'datetime',
        'timestamp_window_end' => 'datetime',
        'divergence_frequency' => 'integer',
        'is_deterministic' => 'boolean',
    ];

    /**
     * Observation windows belong to a parent aggregation window
     */
    public function aggregationWindow()
    {
        return $this->belongsTo(SovereigntyDivergenceSummary::class, 'observation_window_id');
    }

    /**
     * Constitutional evidence: Was this divergence consistent?
     *
     * If same user, same route, same conditions always diverge the same way,
     * it's deterministically replaceable (procedural path can be retired).
     *
     * If divergence is nondeterministic, topology leakage detected.
     */
    public function isDeterministic(): bool
    {
        return $this->is_deterministic;
    }

    /**
     * Constitutional classification: Is this divergence expected?
     *
     * Expected: H.1 blocks because procedural IP check runs before constitutional path
     * Expected: H.2 exposes cleartext IP but evaluation is same
     * Unexpected: Same user gets different legitimacy on different calls (replay drift)
     */
    public function isExpectedDivergence(): bool
    {
        return in_array($this->source_finding, ['H.1', 'H.2', 'H.3', 'H.4']);
    }

    /**
     * Replay archaeology: Can this divergence be explained?
     */
    public function hasArchaeologicalExplanation(): bool
    {
        return !empty($this->replay_notes) && strlen($this->replay_notes) > 10;
    }
}
