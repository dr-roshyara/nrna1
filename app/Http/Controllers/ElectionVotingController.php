<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Carbon\Carbon;
use App\Models\VoterSlug; // still used for active-session reuse in start()
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ElectionVotingController extends Controller
{
    /**
     * Show the election page.
     *
     * GET /elections/{slug}
     *
     * Determines the user's voting eligibility and renders the civic election dashboard.
     * Uses withoutGlobalScopes() to bypass BelongsToTenant — slug is authoritative context.
     */
    public function show(string $slug): Response
    {
        $election = Election::withoutGlobalScopes()
            ->with('organisation:id,name,logo')
            ->where('slug', $slug)
            ->where('type', 'real')
            ->firstOrFail();

        $user = auth()->user();

        $membership = $user->electionMemberships()
            ->where('election_id', $election->id)
            ->first();

        $hasVoted   = $membership?->has_voted ?? false;
        $isEligible = $membership !== null
            && $membership->role   === 'voter'
            && $membership->status !== 'removed';

        // Compare end_date as end-of-day: an election ending "on March 23" should
        // remain open for the full day, regardless of whether it was stored as midnight.
        $endOfDay = \Carbon\Carbon::parse($election->end_date)->endOfDay();

        $canVote = $isEligible
            && ! $hasVoted
            && $election->status === 'active'
            && $election->start_date <= now()
            && $endOfDay >= now();

        $org = $election->organisation;

        return Inertia::render('Election/Show', [
            'election'         => $election,
            'hasVoted'         => $hasVoted,
            'canVote'          => $canVote,
            'isEligible'       => $isEligible,
            'ipAddress'        => request()->ip(),
            'organisationLogo' => $org?->logo ? asset($org->logo) : null,
            'organisationName' => $org?->name,
        ]);
    }

    /**
     * Start the voting flow.
     *
     * POST /elections/{slug}/start
     *
     * Validates eligibility, creates (or reuses) a VoterSlug,
     * then redirects into the existing voting workflow at slug.code.create.
     */
    public function start(Request $request, string $slug): RedirectResponse
    {
        $election = Election::withoutGlobalScopes()
            ->where('slug', $slug)
            ->where('type', 'real')
            ->firstOrFail();

        $user = auth()->user();

        $membership = $user->electionMemberships()
            ->where('election_id', $election->id)
            ->first();

        if (! $membership || $membership->role !== 'voter' || $membership->status === 'removed') {
            return redirect()->route('elections.show', $slug)
                ->with('error', 'You are not eligible to vote in this election.');
        }

        if ($membership->has_voted) {
            return redirect()->route('elections.show', $slug)
                ->with('info', 'You have already voted.');
        }

        // Per-election IP restriction check
        if ($election->isIpRestricted()) {
            $ip = $request->ip();

            // Whitelisted IPs (exact or CIDR) bypass all limits
            if (!$election->isIpWhitelisted($ip)) {
                $votedCount = VoterSlug::where('election_id', $election->id)
                    ->where('step_1_ip', $ip)
                    ->where('has_voted', true)
                    ->count();

                if ($votedCount >= $election->ip_restriction_max_per_ip) {
                    abort(403, "Maximum {$election->ip_restriction_max_per_ip} votes allowed from your IP address");
                }
            }
        }

        // Reuse an unexpired active slug, or refresh any existing slug for this user+election
        $existing = VoterSlug::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();

        if ($existing) {
            // Refresh slug and expiry so it's valid for a new voting session
            $existing->update([
                'slug'       => Str::random(32),
                'status'     => 'active',
                'is_active'  => true,
                'can_vote_now' => true,
                'expires_at' => now()->addMinutes(30),
            ]);
            return redirect()->route('slug.code.create', ['vslug' => $existing->slug]);
        }

        $voterSlug = VoterSlug::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'election_id'     => $election->id,
            'organisation_id' => $election->organisation_id,
            'slug'            => Str::random(32),
            'status'          => 'active',
            'expires_at'      => now()->addMinutes(30),
        ]);

        return redirect()->route('slug.code.create', ['vslug' => $voterSlug->slug]);
    }
}
