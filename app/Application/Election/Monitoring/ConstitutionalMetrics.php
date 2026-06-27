<?php

namespace App\Application\Election\Monitoring;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * ConstitutionalMetrics: Observable Governance Health
 *
 * Turns constitutional governance from "architecturally correct" to "operationally measurable."
 *
 * Before Phase 3.2 strict mode activation, measurable proof is required:
 * - Warning mode is quiet (no legacy path activity)
 * - No hidden deprecated patterns remain
 * - Production drift is zero
 *
 * This enables: Governance Based on Evidence, not intuition.
 *
 * Usage: php artisan election:constitution:health
 *
 * ARCHITECTURAL NOTE: Injectable service with Contract interface.
 * Allows future implementations: Redis, Prometheus, Elastic, etc.
 */
final class ConstitutionalMetrics implements ConstitutionalMetricsContract
{
    private const CACHE_KEY = 'constitutional_metrics:24h';
    private const RETENTION_HOURS = 24;

    public function __construct(
        private readonly ?\Illuminate\Contracts\Cache\Repository $cache = null,
    ) {}

    private function getCache(): \Illuminate\Contracts\Cache\Repository
    {
        return $this->cache ?? cache();
    }

    /**
     * Record a lifecycle evaluation (denominator metric for context).
     *
     * Without this, violations are meaningless:
     *   0 violations (system unused?)
     *   vs
     *   0 violations / 14,281 evaluations (truly healthy)
     */
    public function recordLifecycleEvaluation(): void
    {
        $this->incrementMetric('lifecycle_evaluations', ['severity' => 'INFO']);
    }

    /**
     * Record a deprecated field access violation (SEVERITY: LOW).
     *
     * Optional $dependencyContext enables Phase 4 extraction blueprint generation:
     * - source_system: 'controller'|'service'|'repository'|'model'|'test'
     * - source_file: Absolute or relative file path
     * - source_method: Method where violation occurred
     * - call_chain: Array of call stack frames
     */
    public function recordDeprecatedFieldAccess(
        string $field,
        string $context,
        ?array $dependencyContext = null
    ): void {
        $this->incrementMetric('deprecated_field_reads', array_merge(
            [
                'field' => $field,
                'context' => $context,
                'severity' => 'LOW',
            ],
            $dependencyContext ?? []
        ));
    }

    /**
     * Record a query guard violation (SEVERITY: MEDIUM).
     *
     * Optional $dependencyContext enables Phase 4 extraction blueprint generation:
     * - source_system: 'controller'|'service'|'repository'|'model'|'test'
     * - source_file: Absolute or relative file path
     * - source_method: Method where violation occurred
     * - call_chain: Array of call stack frames
     */
    public function recordQueryGuardViolation(
        string $field,
        string $context,
        ?array $dependencyContext = null
    ): void {
        $this->incrementMetric('illegal_queries_blocked', array_merge(
            [
                'field' => $field,
                'context' => $context,
                'severity' => 'MEDIUM',
            ],
            $dependencyContext ?? []
        ));
    }

    /**
     * Record a drift monitor violation (SEVERITY: HIGH).
     */
    public function recordDriftViolation(string $violationType, string $context): void
    {
        $this->incrementMetric('drift_violations', [
            'type' => $violationType,
            'context' => $context,
            'severity' => 'HIGH',
        ]);
    }

    /**
     * Record an illegal activation attempt (SEVERITY: CRITICAL).
     */
    public function recordIllegalActivationAttempt(string $electionId, string $reason): void
    {
        $this->incrementMetric('illegal_activations_blocked', [
            'election_id' => $electionId,
            'reason' => $reason,
            'severity' => 'CRITICAL',
        ]);
    }

    /**
     * Record an unauthorized state mutation attempt (SEVERITY: CRITICAL).
     *
     * Called when code tries to mutate election.state without ConstitutionalTransitionGuard authorization.
     * At Level 1 (metrics strict): recorded silently
     * At Level 4 (full strict): will trigger exception in Election model mutator
     */
    public function recordUnauthorizedStateMutation(string $field, string $context): void
    {
        $this->incrementMetric('unauthorized_state_mutations', [
            'field' => $field,
            'context' => $context,
            'severity' => 'CRITICAL',
        ]);
    }

    /**
     * Get health status for strict-mode eligibility decision.
     *
     * Provides complete observability:
     * - violation counts by severity
     * - denominator (lifecycle evaluations)
     * - compliance status
     * - strict-mode readiness
     */
    public function getHealth(): array
    {
        $metrics = $this->getCache()->get(self::CACHE_KEY, $this->defaultMetrics());

        $totalViolations = (
            ($metrics['deprecated_field_reads'] ?? 0) +
            ($metrics['illegal_queries_blocked'] ?? 0) +
            ($metrics['drift_violations'] ?? 0) +
            ($metrics['illegal_activations_blocked'] ?? 0)
        );

        $isHealthy = $totalViolations === 0;
        $strictModeReady = $isHealthy;

        return [
            'is_healthy' => $isHealthy,
            'violations_24h' => $totalViolations,
            'violations_by_severity' => [
                'low' => $metrics['deprecated_field_reads'] ?? 0,
                'medium' => $metrics['illegal_queries_blocked'] ?? 0,
                'high' => $metrics['drift_violations'] ?? 0,
                'critical' => $metrics['illegal_activations_blocked'] ?? 0,
            ],
            'lifecycle_evaluations' => $metrics['lifecycle_evaluations'] ?? 0,
            'strict_mode_ready' => $strictModeReady,
            'report_timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Reset metrics (after Phase 3.2 strict mode activation).
     */
    public function reset(): void
    {
        $this->getCache()->forget(self::CACHE_KEY);
        Log::channel('constitutional_integrity')->info('Constitutional metrics reset');
    }

    /**
     * ============================================================================
     * PRIVATE HELPERS
     * ============================================================================
     */

    private function incrementMetric(string $metric, array $context = []): void
    {
        $cache = $this->getCache();
        $metrics = $cache->get(self::CACHE_KEY, $this->defaultMetrics());

        if (!isset($metrics[$metric])) {
            $metrics[$metric] = 0;
        }

        $metrics[$metric]++;

        // Store with 24-hour TTL
        $cache->put(self::CACHE_KEY, $metrics, now()->addHours(self::RETENTION_HOURS));

        // Log violation for audit trail
        Log::channel('constitutional_integrity')
            ->warning("Constitutional violation: {$metric}", $context);
    }

    private function defaultMetrics(): array
    {
        return [
            'lifecycle_evaluations' => 0,
            'deprecated_field_reads' => 0,
            'illegal_queries_blocked' => 0,
            'drift_violations' => 0,
            'illegal_activations_blocked' => 0,
        ];
    }
}
