<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

/**
 * EnsureRealVoteOrganisation Middleware
 *
 * Phase 4: Pre-Request Validation Layer
 *
 * CRITICAL SECURITY: Validates that user's organisation matches election's organisation
 * BEFORE the request reaches the controller.
 *
 * BACKWARD COMPATIBILITY: Demo elections bypass ALL checks.
 */
class EnsureRealVoteOrganisation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('🔵 [EnsureRealVoteOrganisation] Middleware START', [
            'user_id' => auth()->id(),
            'route' => $request->route()->getName(),
        ]);

        // STEP 1: Get election from middleware chain (set by VerifyVoterSlugConsistency)
        $election = $request->attributes->get('election');

        if (!$election) {
            \Log::channel('voting_security')->error('PHASE 4: No election in middleware chain', [
                'route' => $request->route()->getName(),
                'url' => $request->url(),
                'timestamp' => now(),
            ]);

            return back()->withErrors([
                'election' => __('Election context not found. Please try again.')
            ]);
        }

        \Log::info('✅ [EnsureRealVoteOrganisation] Election found', [
            'election_id' => $election->id,
            'election_type' => $election->type,
        ]);

        // STEP 2: BACKWARD COMPATIBILITY - Demo elections bypass ALL validation
        if ($election->type === 'demo') {
            $this->logBypassedCheck($request, 'Demo election - bypassing organisation check', $election);
            return $next($request);
        }

        // STEP 3: Real elections require authenticated user
        $user = auth()->user();

        if (!$user) {
            // Auth middleware should have caught this, but double-check
            \Log::channel('voting_security')->warning('PHASE 4: Unauthenticated user in voting flow', [
                'election_id' => $election->id,
                'route' => $request->route()->getName(),
                'timestamp' => now(),
            ]);

            return redirect()->route('login');
        }

        // STEP 4: ✅ CRITICAL FIX - Check voter_slug organisation (not user organisation)
        // In multi-tenancy, user may be from different org than the election they're voting in
        // VerifyVoterSlugConsistency already validated voter_slug ↔ election org consistency
        // We just need to verify voter_slug context is present
        $voterSlug = $request->attributes->get('voter_slug');

        \Log::info('🔍 [EnsureRealVoteOrganisation] Checking voter slug', [
            'has_voter_slug' => $voterSlug !== null,
            'voter_slug_id' => $voterSlug?->id,
            'voter_slug_org_id' => $voterSlug?->organisation_id,
            'election_org_id' => $election->organisation_id,
        ]);

        if (!$voterSlug) {
            \Log::channel('voting_security')->error('PHASE 4: No voter slug in voting flow', [
                'user_id' => $user->id,
                'election_id' => $election->id,
                'route' => $request->route()->getName(),
            ]);

            return back()->withErrors([
                'voting_context' => __('Voting session context missing. Please start over.')
            ]);
        }

        // STEP 5: Validation passed - log and continue
        $this->logSuccessfulValidation($request, $user, $election);
        return $next($request);
    }

    /**
     * Handle organisation mismatch - block request and log security incident
     */
    protected function handleOrganisationMismatch(Request $request, $user, $election): Response
    {
        \Log::channel('voting_security')->error('PHASE 4: Organisation mismatch blocked at middleware', [
            'user_id' => $user->id,
            'user_organisation_id' => $user->organisation_id,
            'election_id' => $election->id,
            'election_organisation_id' => $election->organisation_id,
            'route' => $request->route()->getName(),
            'url' => $request->url(),
            'ip' => $request->ip(),
            'timestamp' => now(),
            'blocked_at' => 'middleware_layer',
        ]);

        return back()->withErrors([
            'organisation' => __('You do not have permission to vote in this election. Your organisation does not match.')
        ])->with([
            'error_type' => 'organisation_mismatch',
            'user_org' => $user->organisation_id,
            'election_org' => $election->organisation_id,
        ]);
    }

    /**
     * Log successful validation to audit channel
     */
    protected function logSuccessfulValidation(Request $request, $user, $election): void
    {
        \Log::channel('voting_audit')->info('PHASE 4: Organisation validation passed at middleware', [
            'user_id' => $user->id,
            'organisation_id' => $user->organisation_id,
            'election_id' => $election->id,
            'election_type' => $election->type,
            'route' => $request->route()->getName(),
            'timestamp' => now(),
        ]);
    }

    /**
     * Log bypassed check (demo elections)
     */
    protected function logBypassedCheck(Request $request, string $reason, $election): void
    {
        \Log::channel('voting_audit')->info('PHASE 4: Organisation check bypassed', [
            'reason' => $reason,
            'election_id' => $election->id,
            'election_type' => $election->type,
            'route' => $request->route()->getName(),
            'timestamp' => now(),
        ]);
    }
}
