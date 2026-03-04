<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DemoCode;

/**
 * CheckVotingWindow Middleware
 *
 * Detects when a voter's voting window (code1_used_at + voting_time_in_minutes) has expired
 * and gracefully handles the restart flow instead of leaving the user in a redirect loop.
 *
 * PROBLEM SOLVED:
 *   When code1_used_at is older than voting_time_in_minutes but expireCode() was never called
 *   (because the user never submitted the vote form), can_vote_now remains 1.
 *   This causes DemoCodeController::create() to redirect to agreement, which redirects back
 *   to demo-vote/create → infinite loop.
 *
 * SOLUTION:
 *   - On demo-code/create: Reset can_vote_now=0 so getOrCreateCode() generates a fresh code
 *   - On all other demo routes: Redirect to demo-code/create with an informative message
 *
 * MUST run after: voter.slug.verify (sets voter_slug in request attributes)
 * MUST run before: voter.step.order (step checking)
 */
class CheckVotingWindow
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Requires authenticated user
        if (!auth()->check()) {
            return $next($request);
        }

        // Requires voter slug (set by voter.slug.verify middleware)
        $voterSlug = $request->attributes->get('voter_slug');
        if (!$voterSlug) {
            return $next($request);
        }

        // Only apply to demo elections
        $election = \App\Models\Election::withoutGlobalScopes()->find($voterSlug->election_id);
        if (!$election || !$election->isDemo()) {
            return $next($request);
        }

        // Look up the user's code for this election (bypass global scope)
        $code = DemoCode::withoutGlobalScopes()
            ->where('user_id', auth()->id())
            ->where('election_id', $election->id)
            ->first();

        // No code yet — user hasn't started, nothing to check
        if (!$code || !$code->code1_used_at) {
            return $next($request);
        }

        // Code already voted — no need to check window expiry
        if ($code->has_voted) {
            return $next($request);
        }

        // Check if the voting window has expired
        // Note: Carbon::parse($past)->diffInMinutes(now()) gives positive minutes elapsed
        $votingMinutes = (int) ($code->voting_time_in_minutes ?? config('voting.time_in_minutes', 30));
        $minutesElapsed = \Carbon\Carbon::parse($code->code1_used_at)->diffInMinutes(now());
        $isExpired = $minutesElapsed > $votingMinutes;

        if (!$isExpired) {
            return $next($request);
        }

        // Window has expired — reset the code so it can be reissued
        Log::info('⏰ [CheckVotingWindow] Voting window expired — resetting code state', [
            'user_id'               => auth()->id(),
            'code_id'               => $code->id,
            'election_id'           => $election->id,
            'code1_used_at'         => $code->code1_used_at,
            'voting_time_minutes'   => $votingMinutes,
            'minutes_elapsed'       => $minutesElapsed,
            'route'                 => $request->route()?->getName(),
        ]);

        // Reset can_vote_now so DemoCodeController::create() skips the "already verified" redirect
        // and lets getOrCreateCode() issue a fresh code
        $code->can_vote_now = 0;
        $code->save();

        // Determine redirect target
        $codeCreateUrl = route('slug.demo-code.create', ['vslug' => $voterSlug->slug]);

        // If user is ALREADY on demo-code/create or submitting the code form:
        // Just let them through — getOrCreateCode() will handle issuing a new code
        $routeName = $request->route()?->getName() ?? '';
        $isCodeCreateRoute = str_contains($routeName, 'demo-code.create')
            || str_contains($routeName, 'demo-code.store');

        if ($isCodeCreateRoute) {
            Log::info('✅ [CheckVotingWindow] User on code create route after expiry — continuing', [
                'user_id'    => auth()->id(),
                'route_name' => $routeName,
            ]);
            return $next($request);
        }

        // For all other routes: redirect to code create page
        Log::info('🔄 [CheckVotingWindow] Redirecting expired user to code create', [
            'user_id'    => auth()->id(),
            'route_name' => $routeName,
            'redirect'   => $codeCreateUrl,
        ]);

        return redirect($codeCreateUrl)
            ->with('warning', 'Ihre 30-minütige Abstimmungszeit ist abgelaufen. Bitte fordern Sie einen neuen Code an, um erneut abzustimmen.');
    }
}
