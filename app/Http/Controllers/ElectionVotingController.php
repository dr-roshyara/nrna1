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

        // Evaluate IP restriction status
        $ip      = request()->ip();
        $ipBlock = $this->resolveIpBlock($election, $ip);

        return Inertia::render('Election/Show', [
            'election'         => $election,
            'hasVoted'         => $hasVoted,
            'canVote'          => $canVote,
            'isEligible'       => $isEligible,
            'ipAddress'        => $ip,
            'ipBlocked'        => $ipBlock['blocked'],
            'ipBlockMessage'   => $ipBlock['message'],
            'remainingVotes'   => $ipBlock['remainingVotes'] ?? null,
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

        // IP restriction check (replaces bare abort() with friendly redirect)
        $ipBlock = $this->resolveIpBlock($election, $request->ip());
        if ($ipBlock['blocked']) {
            return redirect()->route('elections.show', $slug)
                ->with('error', $ipBlock['message']);
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

    /**
     * Evaluate IP restriction status.
     *
     * Three-layer precedence:
     * 1. Whitelist (always bypasses all layers)
     * 2. Layer 3: Per-election IP restriction (ip_restriction_enabled)
     * 3. Layer 2: Global fallback (config('app.max_use_clientIP'))
     */
    private function resolveIpBlock(Election $election, string $ip): array
    {
        // Whitelist always bypasses ALL layers (Layer 3 AND Layer 2)
        if ($election->ip_whitelist && $election->isIpWhitelisted($ip)) {
            return ['blocked' => false, 'message' => null];
        }

        // Layer 3: Per-election setting (takes precedence when enabled)
        if ($election->isIpRestricted()) {
            return $this->evaluateIpCount($election, $ip, $election->ip_restriction_max_per_ip);
        }

        // Layer 2: Global platform fallback (config('app.max_use_clientIP'))
        $globalMax = (int) config('app.max_use_clientIP', 0);
        if ($globalMax > 0) {
            return $this->evaluateIpCount($election, $ip, $globalMax);
        }

        return ['blocked' => false, 'message' => null];
    }

    /**
     * Count completed votes from an IP and determine if blocked.
     */
    private function evaluateIpCount(Election $election, string $ip, int $max): array
    {
        $votedCount = VoterSlug::where('election_id', $election->id)
            ->where('step_1_ip', $ip)
            ->where('has_voted', true)
            ->count();

        if ($votedCount >= $max) {
            return [
                'blocked'        => true,
                'message'        => "The maximum of {$max} vote(s) from your network has been reached.",
                'remainingVotes' => 0,
            ];
        }

        return [
            'blocked'        => false,
            'message'        => null,
            'remainingVotes' => $max - $votedCount,
        ];
    }
}
