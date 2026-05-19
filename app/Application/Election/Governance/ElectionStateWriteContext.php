<?php

namespace App\Application\Election\Governance;

/**
 * ElectionStateWriteContext: Controlled Authority Boundary
 *
 * Establishes authorized write barrier for election state mutations.
 * ONLY ConstitutionalTransitionGuard can authorize state changes.
 *
 * GOVERNANCE PRINCIPLE:
 * State mutations are NOT blocked by exceptions. Instead:
 * 1. Check if write is authorized (from guard or bootstrap)
 * 2. Record unauthorized attempts to metrics for audit trail
 * 3. At Level 4 (full strict), throw exception
 * 4. At Level 1 (metrics strict), log silently and allow
 *
 * This preserves system resilience during bootstrap (hydration, migrations,
 * queue deserialization) while establishing sovereign governance over mutations.
 *
 * USAGE: Only ConstitutionalTransitionGuard calls authorize()
 * ```php
 * ElectionStateWriteContext::authorize(function() {
 *     $election->state = 'new_state';
 *     $election->save();
 * });
 * ```
 */
final class ElectionStateWriteContext
{
    /**
     * Authorization flag. True when mutations are permitted.
     * Scoped to single closure execution, automatically reverted.
     */
    private static ?bool $isAuthorized = false;

    /**
     * Execute a closure with state write authority.
     *
     * ONLY ConstitutionalTransitionGuard should call this.
     * Opens write barrier for the duration of the callback, then reverts.
     *
     * @param \Closure $callback Code that mutates election state
     * @return mixed Result of callback
     */
    public static function authorize(\Closure $callback): mixed
    {
        $previous = self::$isAuthorized;
        self::$isAuthorized = true;

        try {
            return $callback();
        } finally {
            self::$isAuthorized = $previous;
        }
    }

    /**
     * Check if current execution context is authorized to mutate state.
     *
     * Called by Election model's state mutator to validate writes.
     *
     * @return bool True if within authorize() callback, false otherwise
     */
    public static function isAuthorized(): bool
    {
        return self::$isAuthorized === true;
    }

    /**
     * Record an unauthorized state mutation attempt.
     *
     * Called by Election model mutator when unauthorized write detected.
     * Metrics service logs violation for audit trail and observability.
     *
     * @param string $field Field being mutated (e.g., 'state')
     * @param string $context Where mutation attempted (e.g., 'ActivateElectionCommand')
     * @return void
     */
    public static function recordViolation(string $field, string $context): void
    {
        app(\App\Application\Election\Monitoring\ConstitutionalMetricsContract::class)
            ->recordUnauthorizedStateMutation($field, $context);
    }
}
