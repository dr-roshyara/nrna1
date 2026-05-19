<?php

namespace App\Application\Election\Monitoring;

/**
 * ConstitutionalMetricsContract: Injectable Governance Observability
 *
 * This contract enables future implementations:
 * - Cache-based (current)
 * - Redis (high-volume)
 * - Prometheus (native metrics)
 * - Elastic (long-term storage)
 * - StatsD (time-series)
 *
 * Keeps metrics abstraction consistent with overall DI-first architecture.
 */
interface ConstitutionalMetricsContract
{
    /**
     * Record a lifecycle evaluation (denominator for violation context).
     */
    public function recordLifecycleEvaluation(): void;

    /**
     * Record a deprecated field access violation (SEVERITY: LOW).
     *
     * Extended parameters (optional) enable Phase 4 extraction blueprint generation.
     */
    public function recordDeprecatedFieldAccess(
        string $field,
        string $context,
        ?array $dependencyContext = null
    ): void;

    /**
     * Record a query guard violation (SEVERITY: MEDIUM).
     *
     * Extended parameters (optional) enable Phase 4 extraction blueprint generation.
     */
    public function recordQueryGuardViolation(
        string $field,
        string $context,
        ?array $dependencyContext = null
    ): void;

    /**
     * Record a drift monitor violation (SEVERITY: HIGH).
     */
    public function recordDriftViolation(string $violationType, string $context): void;

    /**
     * Record an illegal activation attempt (SEVERITY: CRITICAL).
     */
    public function recordIllegalActivationAttempt(string $electionId, string $reason): void;

    /**
     * Get comprehensive health report for strict-mode readiness decision.
     *
     * @return array{
     *     is_healthy: bool,
     *     violations_24h: int,
     *     violations_by_severity: array<string, int>,
     *     lifecycle_evaluations: int,
     *     strict_mode_ready: bool,
     *     report_timestamp: string
     * }
     */
    public function getHealth(): array;

    /**
     * Reset metrics (for Phase 3.2 strict mode activation).
     */
    public function reset(): void;
}
