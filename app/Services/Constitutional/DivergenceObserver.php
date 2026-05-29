<?php

namespace App\Services\Constitutional;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

/**
 * DivergenceObserver Service
 *
 * D.0.1 Observability Layer
 *
 * Detects and records divergences between:
 * - Procedural sovereignty (H.1-H.3) outcomes
 * - Constitutional sovereignty (PolicySequence) outcomes
 *
 * NOT a monitoring/alerting service.
 * Pure constitutional archaeology.
 * Each observation is evidence of legitimacy convergence.
 */
class DivergenceObserver
{
    /**
     * Telemetry window ID (identifies this observation batch)
     * Generated at D.0.1 start, used to link observations to D.0.2 aggregation
     */
    private static ?string $telemetryWindowId = null;

    /**
     * Is D.0.1 observation window currently active?
     */
    public static function isObservationWindowActive(): bool
    {
        // Check if feature flag is enabled (H.1 is disabled, constitutional path active)
        $flagEnabled = config('voting_security.enable_legacy_middleware_ip_check', 0) == 1;

        // Observation window is ACTIVE when flag is disabled (shadow sovereignty mode)
        return !$flagEnabled && config('constitutional.d01_observation_enabled', false);
    }

    /**
     * Record a divergence observation
     *
     * Called when procedural path (H.1-H.3) blocks or allows,
     * but constitutional path would do the opposite.
     *
     * Preserves replay-relevant dimensions:
     * - user_id
     * - route
     * - registered_ip (what procedural path saw)
     * - current_ip (what request contained)
     * - timestamp
     * - user_agent
     * - divergence_type (ALLOWED_vs_DENIED, IP_MISMATCH, etc)
     * - source_finding (H.1, H.2, H.3, etc)
     * - divergence_frequency (how many times)
     * - is_deterministic (always diverges same way?)
     */
    public static function recordDivergence(
        Request $request,
        ?User $user,
        string $procedureOutcome,      // ALLOWED, BLOCKED, DEFERRED
        string $constitutionalOutcome,  // What PolicySequence would return
        string $divergenceType,         // ALLOWED_vs_DENIED, IP_MISMATCH, etc
        string $sourceFinding,          // H.1, H.2, H.3, H.4, etc
        array $context = []
    ): void {
        if (!self::isObservationWindowActive()) {
            return;
        }

        $observation = [
            'timestamp' => now(),
            'window_id' => self::getTelemetryWindowId(),
            'user_id' => $user?->id,
            'route' => $request->path(),
            'registered_ip' => $context['registered_ip'] ?? null,
            'current_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'procedural_outcome' => $procedureOutcome,
            'constitutional_outcome' => $constitutionalOutcome,
            'divergence_type' => $divergenceType,
            'source_finding' => $sourceFinding,
            'divergence_frequency' => $context['frequency'] ?? 1,
            'is_deterministic' => $context['is_deterministic'] ?? true,
            'replay_notes' => $context['notes'] ?? null,
            'election_id' => $context['election_id'] ?? null,
            'extra_context' => $context['extra'] ?? [],
        ];

        // Store observation for D.0.2 aggregation
        self::storeObservation($observation);
    }

    /**
     * Record when H.1 (ValidateVotingIp middleware) blocks a request
     * that the constitutional path would allow
     */
    public static function recordH1Divergence(
        Request $request,
        User $user,
        string $registeredIp,
        string $currentIp
    ): void {
        self::recordDivergence(
            request: $request,
            user: $user,
            procedureOutcome: 'BLOCKED',
            constitutionalOutcome: 'ALLOWED', // Constitutional evaluator would decide
            divergenceType: 'IP_MISMATCH',
            sourceFinding: 'H.1',
            context: [
                'registered_ip' => $registeredIp,
                'notes' => 'H.1 ValidateVotingIp middleware blocks before constitutional evaluation',
            ]
        );
    }

    /**
     * Record when H.2 (validateVotingIpWithResponse) blocks
     */
    public static function recordH2Divergence(
        Request $request,
        User $user,
        string $registeredIp,
        string $currentIp
    ): void {
        self::recordDivergence(
            request: $request,
            user: $user,
            procedureOutcome: 'BLOCKED',
            constitutionalOutcome: 'DEFERRED', // Constitutional path not reached
            divergenceType: 'IP_MISMATCH',
            sourceFinding: 'H.2',
            context: [
                'registered_ip' => $registeredIp,
                'notes' => 'H.2 validateVotingIpWithResponse exposes cleartext IP in frontend props',
            ]
        );
    }

    /**
     * Record when H.3 (check_ip_address global query) blocks
     */
    public static function recordH3Divergence(
        Request $request,
        User $user,
        string $clientIp,
        int $maxUseClientIp,
        int $actualCount
    ): void {
        self::recordDivergence(
            request: $request,
            user: $user,
            procedureOutcome: 'BLOCKED',
            constitutionalOutcome: 'DEFERRED',
            divergenceType: 'GLOBAL_IP_COUNT_EXCEEDED',
            sourceFinding: 'H.3',
            context: [
                'current_ip' => $clientIp,
                'max_allowed' => $maxUseClientIp,
                'actual_count' => $actualCount,
                'notes' => 'H.3 check_ip_address queries global codes table (multi-tenant violation)',
            ]
        );
    }

    /**
     * Get or generate telemetry window ID
     * Links all observations in this D.0.1 window to one aggregation batch
     */
    public static function getTelemetryWindowId(): string
    {
        if (self::$telemetryWindowId) {
            return self::$telemetryWindowId;
        }

        // Try to get from cache (shared across requests)
        self::$telemetryWindowId = Cache::get('d01_telemetry_window_id');

        if (!self::$telemetryWindowId) {
            // Start new observation window
            self::$telemetryWindowId = 'd01_' . now()->format('YmdHis') . '_' . uniqid();
            Cache::put('d01_telemetry_window_id', self::$telemetryWindowId, 86400); // 24h
        }

        return self::$telemetryWindowId;
    }

    /**
     * Store observation in telemetry cache
     * D.0.2 aggregation command will read from this cache
     */
    private static function storeObservation(array $observation): void
    {
        $windowId = self::getTelemetryWindowId();
        $cacheKey = "d01_observations_{$windowId}";

        // Get existing observations
        $observations = Cache::get($cacheKey, []);

        // Add new observation
        $observations[] = $observation;

        // Store back (keep for 24h)
        Cache::put($cacheKey, $observations, 86400);

        // Also log for audit trail
        \Log::channel('constitutional')->info('D.0.1 Divergence Observation', $observation);
    }

    /**
     * Fetch all observations for D.0.2 aggregation
     */
    public static function getObservationsForAggregation(?string $windowId = null): array
    {
        $windowId = $windowId ?? self::getTelemetryWindowId();
        $cacheKey = "d01_observations_{$windowId}";

        return Cache::get($cacheKey, []);
    }

    /**
     * Clear observations after aggregation (D.0.2 cleanup)
     */
    public static function clearObservations(?string $windowId = null): void
    {
        $windowId = $windowId ?? self::getTelemetryWindowId();
        $cacheKey = "d01_observations_{$windowId}";

        Cache::forget($cacheKey);
    }

    /**
     * Constitutional assessment: Are observations deterministic?
     *
     * If same user, same route, same conditions always diverge the same way,
     * it's deterministically replaceable (procedural path can be retired).
     */
    public static function assessDeterminism(array $observations): array
    {
        $groupedByPattern = [];

        foreach ($observations as $obs) {
            $pattern = "{$obs['user_id']}|{$obs['route']}|{$obs['source_finding']}";
            $groupedByPattern[$pattern][] = $obs;
        }

        $deterministicPatterns = [];
        $nondeterministicPatterns = [];

        foreach ($groupedByPattern as $pattern => $group) {
            $outcomes = array_column($group, 'procedural_outcome');
            $uniqueOutcomes = array_unique($outcomes);

            if (count($uniqueOutcomes) === 1) {
                // Always same outcome = deterministic
                $deterministicPatterns[$pattern] = count($group);
            } else {
                // Different outcomes = nondeterministic (RED FLAG)
                $nondeterministicPatterns[$pattern] = [
                    'count' => count($group),
                    'outcomes' => $uniqueOutcomes,
                ];
            }
        }

        return [
            'deterministic_count' => array_sum($deterministicPatterns),
            'nondeterministic_count' => count($nondeterministicPatterns),
            'deterministic_patterns' => $deterministicPatterns,
            'nondeterministic_patterns' => $nondeterministicPatterns,
        ];
    }
}
