<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * SovereigntyDivergenceSummary
 *
 * Constitutional Aggregation Model
 *
 * Summarizes constitutional convergence evidence during D.0.1 observation window.
 *
 * This is NOT an alert. It is constitutional archaeology.
 * It answers: "Can procedural sovereignty (H.1-H.3) now be safely retired?"
 *
 * Contains:
 * - observation_period (when D.0.1 window was active)
 * - total_divergence_events (how many times procedural and constitutional paths diverged)
 * - deterministic_divergence_count (divergences that happen consistently)
 * - nondeterministic_divergence_count (divergences with replay drift — RED FLAG)
 * - by_source_finding (breakdown by H.1, H.2, H.3, etc)
 * - by_outcome_type (ALLOWED vs DENIED divergences)
 * - by_route (which endpoints had divergence)
 * - constitutional_equivalence_assessment (can procedural be retired?)
 */
class SovereigntyDivergenceSummary extends Model
{
    protected $table = 'sovereignty_divergence_summaries';

    protected $fillable = [
        'election_id',
        'observation_period_start',
        'observation_period_end',
        'feature_flag_name',                    // voting_security.enable_legacy_middleware_ip_check
        'feature_flag_value',                   // enabled or disabled
        'total_divergence_events',
        'unique_users_with_divergence',
        'unique_routes_with_divergence',
        'deterministic_divergence_count',
        'nondeterministic_divergence_count',
        'by_source_finding',                    // JSON: { H.1: 150, H.2: 45, H.3: 12 }
        'by_outcome_type',                      // JSON: { ALLOWED_vs_DENIED: 120, IP_MISMATCH: 87 }
        'by_route',                             // JSON: { /vote/create: 50, /vote/submit: 107 }
        'constitutional_equivalence_status',    // EQUIVALENT, DIVERGENT, UNKNOWN
        'equivalence_confidence_pct',           // 0-100
        'topology_leakage_detected',            // boolean: was order-dependent authority found?
        'temporal_drift_detected',              // boolean: was replay drift found?
        'archaeological_conclusion',            // Text explanation
        'safe_to_retire_procedural',            // boolean: recommendation
        'authorized_at',                        // When human authorized deletion
        'authorized_by_user_id',                // Who authorized
        'is_finalized',                         // Locked after human review
    ];

    protected $casts = [
        'observation_period_start' => 'datetime',
        'observation_period_end' => 'datetime',
        'total_divergence_events' => 'integer',
        'unique_users_with_divergence' => 'integer',
        'unique_routes_with_divergence' => 'integer',
        'deterministic_divergence_count' => 'integer',
        'nondeterministic_divergence_count' => 'integer',
        'by_source_finding' => 'array',
        'by_outcome_type' => 'array',
        'by_route' => 'array',
        'equivalence_confidence_pct' => 'integer',
        'topology_leakage_detected' => 'boolean',
        'temporal_drift_detected' => 'boolean',
        'safe_to_retire_procedural' => 'boolean',
        'authorized_at' => 'datetime',
        'is_finalized' => 'boolean',
    ];

    /**
     * Relationship: All divergence observation windows included in this summary
     */
    public function divergenceObservations(): HasMany
    {
        return $this->hasMany(DivergenceObservationWindow::class, 'observation_window_id');
    }

    /**
     * Constitutional question: Are procedural and constitutional paths equivalent?
     */
    public function arePathsEquivalent(): bool
    {
        return $this->constitutional_equivalence_status === 'EQUIVALENT'
               && $this->equivalence_confidence_pct >= 95
               && !$this->topology_leakage_detected
               && !$this->temporal_drift_detected;
    }

    /**
     * Constitutional question: Can procedural sovereignty be safely retired?
     *
     * Returns true only if:
     * 1. Paths are constitutionally equivalent
     * 2. No topology leakage detected
     * 3. No temporal drift detected
     * 4. Human has authorized deletion
     */
    public function isSafeToRetireProcedural(): bool
    {
        return $this->arePathsEquivalent()
               && $this->safe_to_retire_procedural
               && $this->authorized_at !== null
               && !$this->is_finalized === false; // Wait until finalized
    }

    /**
     * Constitutional assessment: Why is retirement safe or unsafe?
     */
    public function getConstitutionalAssessment(): string
    {
        if ($this->arePathsEquivalent()) {
            return sprintf(
                'Procedural sovereignty can be safely retired. '
                . 'Constitutional equivalence: %d%%, '
                . 'Divergence events: %d deterministic / %d nondeterministic, '
                . 'Topology leakage: %s, '
                . 'Temporal drift: %s',
                $this->equivalence_confidence_pct,
                $this->deterministic_divergence_count,
                $this->nondeterministic_divergence_count,
                $this->topology_leakage_detected ? 'DETECTED' : 'none',
                $this->temporal_drift_detected ? 'DETECTED' : 'none'
            );
        }

        $issues = [];
        if ($this->nondeterministic_divergence_count > 0) {
            $issues[] = 'Nondeterministic divergence detected (replay drift)';
        }
        if ($this->topology_leakage_detected) {
            $issues[] = 'Topology-dependent authority found';
        }
        if ($this->temporal_drift_detected) {
            $issues[] = 'Temporal ordering sovereignty detected';
        }
        if ($this->equivalence_confidence_pct < 95) {
            $issues[] = sprintf('Low equivalence confidence (%d%%)', $this->equivalence_confidence_pct);
        }

        return 'Procedural sovereignty retirement blocked: ' . implode('; ', $issues);
    }

    /**
     * Lock summary after human authorization
     */
    public function authorize(User $by, bool $approved = true): void
    {
        if ($this->is_finalized) {
            throw new \LogicException('Summary already finalized; cannot modify authorization.');
        }

        $this->safe_to_retire_procedural = $approved;
        $this->authorized_at = now();
        $this->authorized_by_user_id = $by->id;
        $this->is_finalized = true;
        $this->save();
    }
}
