<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VoterSlug;
use App\Models\DemoVoterSlug;
use App\Services\VoterStepTrackingService;

class EnsureVoterStepOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var VoterSlug|DemoVoterSlug $vslug */
        // Use the model already resolved and validated by VerifyVoterSlug middleware
        $vslug = $request->attributes->get('voter_slug');

        // Accept both VoterSlug (real elections) and DemoVoterSlug (demo elections)
        if (!$vslug instanceof VoterSlug && !$vslug instanceof DemoVoterSlug) {
            abort(403, 'Invalid voting link.');
        }

        if (!$vslug->is_active || $vslug->expires_at->isPast()) {
            abort(403, 'Voting link has expired or is invalid.');
        }

        $routeName = optional($request->route())->getName();
        $map = config('election_steps');
        $targetStep = array_search($routeName, $map, true);

        // ✅ FIX: Use VoterSlug's election_id directly (don't wait for election middleware)
        // This avoids the middleware ordering issue - we get the election from the slug itself
        // CRITICAL: Use withoutGlobalScopes() because demo elections are accessible
        // to ALL users regardless of organisation context (organisation_id=NULL)
        $election = \App\Models\Election::withoutGlobalScopes()->find($vslug->election_id);
        if (!$election) {
            \Log::error('❌ Election not found', [
                'vslug_id' => $vslug->id,
                'vslug_election_id' => $vslug->election_id,
            ]);
            abort(403, 'Election not found for this voting link.');
        }

        // ✅ NEW: Use VoterStepTrackingService to determine actual progress
        $stepTracker = new VoterStepTrackingService();
        $highestCompletedStep = $stepTracker->getHighestCompletedStep($vslug, $election);
        $nextAllowedStep = $highestCompletedStep + 1;

        \Log::info('🔵 EnsureVoterStepOrder - DETAILED', [
            'vslug_id' => $vslug->id,
            'route_name' => $routeName,
            'target_step' => $targetStep,
            'highest_completed_step' => $highestCompletedStep,
            'next_allowed_step' => $nextAllowedStep,
            'is_non_step_route' => $targetStep === false,
            'election_id' => $election->id,
            'election_type' => $election->type,
            'vslug_election_id' => $vslug->election_id,
            'vslug_is_active' => $vslug->is_active,
            'vslug_expires_at' => $vslug->expires_at?->toIso8601String(),
            'config_step_map' => $map,
        ]);

        // Non-step routes (e.g., POST actions) pass through
        if ($targetStep === false) {
            \Log::info('✅ EnsureVoterStepOrder: Non-step route passing through', [
                'route_name' => $routeName,
            ]);
            return $next($request);
        }

        // ✅ NEW LOGIC: Allow access to:
        // - Any completed step (they can go back)
        // - The next incomplete step (they can proceed)
        // - Block future incomplete steps
        if ($targetStep > $nextAllowedStep) {
            $nextRoute = $map[$nextAllowedStep] ?? reset($map);
            \Log::warning('⚠️ EnsureVoterStepOrder: User tried to skip ahead', [
                'target_step' => $targetStep,
                'next_allowed_step' => $nextAllowedStep,
                'redirecting_to' => $nextRoute,
                'vslug_slug' => $vslug->slug,
            ]);

            return redirect()->route($nextRoute, ['vslug' => $vslug->slug])
                ->with('info', 'Please complete the current step first.');
        }

        \Log::info('✅ EnsureVoterStepOrder: Step order check passed', [
            'target_step' => $targetStep,
            'next_allowed_step' => $nextAllowedStep,
        ]);

        // ✅ CRITICAL FIX: Set election on request attributes for downstream middleware
        // EnsureRealVoteOrganisation expects this to be present
        $request->attributes->set('election', $election);

        return $next($request);
    }
}
