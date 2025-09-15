<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VoterSlug;

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

        if (!$vslug || !$vslug->is_active || $vslug->expires_at->isPast()) {
            abort(403, 'Voting link has expired or is invalid.');
        }

        $routeName = optional($request->route())->getName();
        $map = config('election_steps');
        $targetStep = array_search($routeName, $map, true);

        // Non-step routes (e.g., POST actions) pass through
        if ($targetStep === false) {
            return $next($request);
        }

        // CRITICAL: Enforce database state binding for Step 2+
        if ($targetStep >= 2) {
            $user = $vslug->user;
            $code = \App\Models\Code::where('user_id', $user->id)->first();

            // Step 2+ requires code verification (can_vote_now = 1)
            if (!$code || $code->can_vote_now != 1) {
                \Log::warning('User attempted to access Step 2+ without code verification', [
                    'user_id' => $user->id,
                    'target_step' => $targetStep,
                    'target_route' => $routeName,
                    'can_vote_now' => $code ? $code->can_vote_now : 'no_code',
                    'slug' => $vslug->slug,
                ]);

                // Force redirect back to Step 1 (code verification)
                return redirect()->route('slug.code.create', ['vslug' => $vslug->slug])
                    ->with('error', 'Code verification required before proceeding.');
            }
        }

        // If user tries to open FUTURE step, send them back to current
        if ($targetStep > $vslug->current_step) {
            $currentRoute = $map[$vslug->current_step] ?? reset($map);
            return redirect()->route($currentRoute, ['vslug' => $vslug->slug]);
        }

        return $next($request);
    }
}
