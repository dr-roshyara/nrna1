<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Constitutional\DivergenceObserver;
use App\Services\Constitutional\DivergenceLogger;

/**
 * LogDivergenceObservations Middleware
 *
 * D.0.1 Observational Sovereignty Convergence Layer
 *
 * During D.0.1 observation window:
 * - H.1 (ValidateVotingIp) is DISABLED (returns $next($request) unconditionally)
 * - But constitutional policies ARE executing
 * - This middleware observes if they would diverge
 *
 * Purpose: Collect evidence for D.0.2 authority transfer analysis
 * (NOT monitoring/alerting)
 *
 * Collects:
 * - When procedural authority would block, constitutional would allow
 * - When outcomes differ
 * - Whether differences are deterministic
 * - What constitutional evidence was available
 *
 * This evidence answers: "Can procedural sovereignty now be safely retired?"
 */
class LogDivergenceObservations
{
    /**
     * Handle an incoming request.
     *
     * BEFORE H.1 executes (or when H.1 is disabled),
     * capture whether outcomes would diverge.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only collect during observation window
        if (!DivergenceObserver::isObservationWindowActive()) {
            return $next($request);
        }

        // For voting routes, prepare to detect divergence
        if ($this->isVotingRoute($request)) {
            return $this->observeVotingDivergence($request, $next);
        }

        return $next($request);
    }

    /**
     * Detect divergence on voting routes during observation window
     *
     * When H.1 (ValidateIpAddress middleware) is DISABLED,
     * this captures what would have happened vs what does happen.
     */
    private function observeVotingDivergence(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Simulate H.1 outcome (what it would have decided)
        $h1_outcome = $this->simulateH1Outcome($request, $user);

        // Proceed with request (constitutional path is active)
        $response = $next($request);

        // After response, check if outcomes diverged
        $constitutional_outcome = $this->deriveConstitutionalOutcome($response, $user);

        if ($h1_outcome !== $constitutional_outcome) {
            // DIVERGENCE DETECTED
            DivergenceLogger::logObservation([
                'election_id' => $request->route('election_id') ?? session('current_election_id'),
                'user_id' => $user?->id,
                'route' => $request->path(),
                'timestamp' => now(),
                'registered_ip' => $user?->voting_ip,
                'current_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'procedural_outcome' => $h1_outcome,
                'constitutional_outcome' => $constitutional_outcome,
                'divergence_type' => "H1_{$h1_outcome}_vs_{$constitutional_outcome}",
                'source_finding' => 'H.1',
                'divergence_frequency' => 1,
                'is_deterministic' => true, // Will be refined by D.0.2
                'replay_notes' => 'H.1 ValidateVotingIp outcome differs from constitutional path',
                'election_id' => $request->route('election_id') ?? session('current_election_id'),
            ]);
        }

        return $response;
    }

    /**
     * Simulate H.1 (ValidateVotingIp middleware) outcome
     * WITHOUT actually executing it (since it's disabled during observation)
     */
    private function simulateH1Outcome(Request $request, $user): string
    {
        // H.1 logic (from ValidateVotingIp middleware)
        if (!$user) {
            return 'ALLOWED'; // Not authenticated, let through
        }

        // Check if IP control is enabled (may be disabled during observation)
        if (config('voting_security.control_ip_address', 1) != 1) {
            return 'ALLOWED';
        }

        // If user has no voting_ip restriction, allow
        if (is_null($user->voting_ip)) {
            return 'ALLOWED';
        }

        // Compare IPs
        $currentIp = $request->ip();
        if ($user->voting_ip !== $currentIp) {
            return 'BLOCKED'; // H.1 would block
        }

        return 'ALLOWED';
    }

    /**
     * Derive constitutional path outcome from response
     *
     * If response is successful (vote created), constitutional path allowed.
     * If response is error, constitutional path blocked.
     */
    private function deriveConstitutionalOutcome(Response $response, $user): string
    {
        // Constitutional path succeeded if:
        // - Vote was created (200/201 response)
        // - Request reached action handler (not blocked by middleware)
        // - No validation errors

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return 'ALLOWED'; // Vote succeeded
        }

        if ($response->getStatusCode() >= 400) {
            // Check if it's a validation error or authentication error
            // (Constitutional blocking vs other blocking)

            if ($response->getStatusCode() === 403 || $response->getStatusCode() === 401) {
                return 'BLOCKED'; // Authentication/authorization failure
            }

            // Other errors (validation, etc) = deferred
            return 'DEFERRED';
        }

        return 'UNKNOWN';
    }

    /**
     * Is this a voting route where divergence is relevant?
     */
    private function isVotingRoute(Request $request): bool
    {
        $path = $request->path();

        return str_contains($path, '/vote/') ||
               str_contains($path, '/voting/') ||
               str_contains($path, '/election/') && str_contains($path, '/vote');
    }
}
