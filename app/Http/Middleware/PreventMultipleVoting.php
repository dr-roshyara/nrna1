<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\VoterSlug;
use App\Services\VotingSecurityService;
use Illuminate\Support\Facades\Log;

/**
 * Middleware to prevent multiple voting by enforcing one active slug per user
 *
 * This middleware runs on every slug-based voting route to ensure
 * no user can have multiple active voting sessions simultaneously
 */
class PreventMultipleVoting
{
    protected VotingSecurityService $securityService;

    public function __construct(VotingSecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    public function handle(Request $request, Closure $next)
    {
        // Get the voter slug from the route
        $vslug = $request->route('vslug');

        if (!$vslug instanceof VoterSlug) {
            return response('Invalid voting link', 403)->header('X-Vote-Block', 'invalid-slug');
        }

        $user = $vslug->user;

        // 1. SECURITY CHECK: Enforce one active slug per user
        $enforcement = $this->securityService->enforceOneActiveSlugPerUser($user);

        if ($enforcement['enforcement_needed']) {
            Log::warning('Multiple voting attempt detected and blocked', [
                'user_id' => $user->id,
                'current_slug' => $vslug->slug,
                'deactivated_slugs' => $enforcement['deactivated_slugs'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Check if the current slug was deactivated
            if (in_array($vslug->slug, $enforcement['deactivated_slugs'])) {
                return response('This voting link has been deactivated due to security policy. Only one active voting session is allowed per voter.', 403)
                    ->header('X-Vote-Block', 'slug-deactivated');
            }
        }

        // 2. FINAL VALIDATION: Ensure current slug is still valid after enforcement
        $vslug->refresh(); // Refresh from database in case it was modified

        if (!$vslug->is_active || $vslug->isExpired()) {
            return response('Voting link is no longer valid. Please request a new voting link.', 403)
                ->header('X-Vote-Block', 'expired-or-inactive');
        }

        // 3. CHECK FOR COMPLETED VOTING
        if ($user->has_voted) {
            Log::info('User attempted to vote again after completing voting', [
                'user_id' => $user->id,
                'slug' => $vslug->slug,
                'ip_address' => $request->ip(),
            ]);

            return response('You have already completed voting. Multiple voting is not allowed.', 403)
                ->header('X-Vote-Block', 'already-voted');
        }

        // 4. ATOMIC RATE LIMITING: per minute and per user
        if ($user && $user->id) {
            $minuteKey = now()->format('YmdHi'); // per minute (YYYYMMDDHHmm)
            $key = "voting_requests_{$user->id}_{$minuteKey}";
            $count = \Cache::increment($key, 1);
            // TTL > 1 min so counter exists
            \Cache::put($key, $count, now()->addMinutes(2));

            if ($count > 100) { // Test limit; production maybe 10-30
                Log::warning('Rate limit exceeded', [
                    'user_id' => $user->id,
                    'count' => $count,
                    'ip' => $request->ip(),
                ]);

                return response('Too many voting requests. Please wait.', 429)
                    ->header('Retry-After', '60')
                    ->header('X-Vote-Block', 'rate-limit');
            }
        }

        // 5. LOG LEGITIMATE ACCESS
        Log::info('Legitimate voting access granted', [
            'user_id' => $user->id,
            'slug' => $vslug->slug,
            'step' => $vslug->current_step,
            'route' => $request->route()->getName(),
            'ip_address' => $request->ip(),
        ]);

        // Debug header helps in Browser Network tab
        header('X-Vote-OK: 1');

        // All security checks passed - proceed with request
        return $next($request);
    }
}