<?php

namespace App\Services\Constitutional;

use Illuminate\Support\Facades\Log;
use App\Models\DivergenceObservationWindow;

/**
 * DivergenceLogger Service
 *
 * D.0.1 Constitutional Evidence Collection
 *
 * Persists divergence observations into DivergenceObservationWindow
 * for D.0.2 aggregation and authority transfer analysis.
 *
 * NOT a monitoring/alerting service.
 * Pure constitutional archaeology infrastructure.
 *
 * Archives:
 * - When procedural sovereignty and constitutional sovereignty diverge
 * - What evidence was available
 * - Why each path made its decision
 * - Whether divergence is deterministic
 *
 * Used by: D.0.2 aggregation to determine if authority can be safely transferred
 */
class DivergenceLogger
{
    /**
     * Log divergence observation for later D.0.2 aggregation
     *
     * This is NOT an alert or security incident.
     * It is evidence of whether legitimate sovereign convergence is achieved.
     */
    public static function logObservation(array $observation): void
    {
        // Ensure observation window is active
        if (!DivergenceObserver::isObservationWindowActive()) {
            return;
        }

        try {
            // Store in database for D.0.2 aggregation
            DivergenceObservationWindow::create([
                'election_id' => $observation['election_id'] ?? null,
                'user_id' => $observation['user_id'] ?? null,
                'route' => $observation['route'] ?? null,
                'timestamp_window_start' => $observation['timestamp'] ?? now(),
                'timestamp_window_end' => $observation['timestamp'] ?? now(),
                'registered_ip' => $observation['registered_ip'] ?? null,
                'current_ip' => $observation['current_ip'] ?? null,
                'user_agent' => $observation['user_agent'] ?? null,
                'procedural_outcome' => $observation['procedural_outcome'] ?? null,
                'constitutional_outcome' => $observation['constitutional_outcome'] ?? null,
                'divergence_type' => $observation['divergence_type'] ?? null,
                'source_finding' => $observation['source_finding'] ?? null,
                'divergence_frequency' => $observation['divergence_frequency'] ?? 1,
                'is_deterministic' => $observation['is_deterministic'] ?? true,
                'replay_notes' => $observation['replay_notes'] ?? null,
                'observation_window_id' => null, // Will be linked during D.0.2 aggregation
            ]);

            // Log to constitutional audit trail
            Log::channel('constitutional')->info('D.0.1 Constitutional Evidence: Divergence Observed', [
                'timestamp' => now()->toIso8601String(),
                'window_id' => DivergenceObserver::getTelemetryWindowId(),
                'source_finding' => $observation['source_finding'] ?? 'unknown',
                'divergence_type' => $observation['divergence_type'] ?? 'unknown',
                'user_id' => $observation['user_id'] ?? 'anonymous',
                'route' => $observation['route'] ?? 'unknown',
                'procedural_outcome' => $observation['procedural_outcome'] ?? null,
                'constitutional_outcome' => $observation['constitutional_outcome'] ?? null,
                'is_deterministic' => $observation['is_deterministic'] ?? true,
                'reason' => 'Constitutional convergence certification (D.0.1 observation window)',
            ]);

        } catch (\Exception $e) {
            // Observation logging failure should NOT break voting flow
            Log::error('D.0.1 Constitutional evidence collection failed', [
                'error' => $e->getMessage(),
                'observation' => $observation,
            ]);
        }
    }

    /**
     * Count current observations in window
     */
    public static function countObservations(): int
    {
        return DivergenceObservationWindow::where('is_finalized', false)->count();
    }

    /**
     * Get observations by source finding (H.1, H.2, etc)
     */
    public static function getObservationsBySource(string $sourceFinding): int
    {
        return DivergenceObservationWindow::where('source_finding', $sourceFinding)
            ->where('is_finalized', false)
            ->count();
    }

    /**
     * Check if any nondeterministic divergences detected
     * (RED FLAG: indicates topology-dependent authority)
     */
    public static function hasNondeterministicDivergences(): bool
    {
        return DivergenceObservationWindow::where('is_deterministic', false)
            ->where('is_finalized', false)
            ->exists();
    }

    /**
     * Check if topology leakage detected
     * (Multiple different outcomes for same user/route/conditions)
     */
    public static function hasTopologyLeakage(): bool
    {
        // Group by pattern and check for outcome variance
        $patterns = DivergenceObservationWindow::where('is_finalized', false)
            ->select('user_id', 'route', 'source_finding', 'procedural_outcome')
            ->get()
            ->groupBy(fn ($obs) => "{$obs->user_id}|{$obs->route}|{$obs->source_finding}");

        foreach ($patterns as $pattern => $observations) {
            $outcomes = $observations->pluck('procedural_outcome')->unique();
            if ($outcomes->count() > 1) {
                return true; // Multiple outcomes for same pattern = topology dependency
            }
        }

        return false;
    }

    /**
     * Constitutional assessment query
     *
     * Answer: "Can procedural sovereignty now be safely retired?"
     *
     * Returns false if:
     * - Nondeterministic divergences exist (topology leakage)
     * - Outcome variance detected (order-dependent authority)
     * - Unexplained divergence patterns found
     */
    public static function canSafelyRetireProcedural(): bool
    {
        if (self::hasNondeterministicDivergences()) {
            return false;
        }

        if (self::hasTopologyLeakage()) {
            return false;
        }

        // All divergences are deterministic and explainable
        return true;
    }

    /**
     * Get summary statistics for human review
     */
    public static function getSummaryStatistics(): array
    {
        $totalObservations = self::countObservations();
        $bySource = DivergenceObservationWindow::where('is_finalized', false)
            ->select('source_finding')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('source_finding')
            ->pluck('count', 'source_finding')
            ->toArray();

        $deterministicCount = DivergenceObservationWindow::where('is_finalized', false)
            ->where('is_deterministic', true)
            ->count();

        $nondeterministicCount = DivergenceObservationWindow::where('is_finalized', false)
            ->where('is_deterministic', false)
            ->count();

        return [
            'total_observations' => $totalObservations,
            'by_source_finding' => $bySource,
            'deterministic_count' => $deterministicCount,
            'nondeterministic_count' => $nondeterministicCount,
            'topology_leakage_detected' => self::hasTopologyLeakage(),
            'safe_to_retire_procedural' => self::canSafelyRetireProcedural(),
        ];
    }
}
