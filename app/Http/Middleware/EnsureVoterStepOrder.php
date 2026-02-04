<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VoterSlug;
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
        /** @var VoterSlug $vslug */
        $vslug = $request->route('vslug');

        // Ensure we have a VoterSlug instance, not a string
        if (!$vslug instanceof VoterSlug) {
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
        $election = \App\Models\Election::find($vslug->election_id);
        if (!$election) {
            abort(403, 'Election not found for this voting link.');
        }

        // ✅ NEW: Use VoterStepTrackingService to determine actual progress
        $stepTracker = new VoterStepTrackingService();
        $highestCompletedStep = $stepTracker->getHighestCompletedStep($vslug, $election);
        $nextAllowedStep = $highestCompletedStep + 1;

        \Log::info('🔵 EnsureVoterStepOrder - NEW SYSTEM', [
            'route_name' => $routeName,
            'target_step' => $targetStep,
            'highest_completed_step' => $highestCompletedStep,
            'next_allowed_step' => $nextAllowedStep,
            'is_non_step_route' => $targetStep === false,
            'election_id' => $election->id,
            'vslug_election_id' => $vslug->election_id,
        ]);

        // Non-step routes (e.g., POST actions) pass through
        if ($targetStep === false) {
            \Log::info('✅ Non-step route passing through');
            return $next($request);
        }

        // ✅ NEW LOGIC: Allow access to:
        // - Any completed step (they can go back)
        // - The next incomplete step (they can proceed)
        // - Block future incomplete steps
        if ($targetStep > $nextAllowedStep) {
            $nextRoute = $map[$nextAllowedStep] ?? reset($map);
            \Log::warning('⚠️ User tried to skip ahead', [
                'target_step' => $targetStep,
                'next_allowed_step' => $nextAllowedStep,
                'redirecting_to' => $nextRoute,
            ]);

            return redirect()->route($nextRoute, ['vslug' => $vslug->slug])
                ->with('info', 'Please complete the current step first.');
        }

        return $next($request);
    }
}
