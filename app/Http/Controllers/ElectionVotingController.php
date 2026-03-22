<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\VoterSlug;
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
            ->where('slug', $slug)
            ->where('type', 'real')
            ->firstOrFail();

        $user = auth()->user();

        $hasVoted = VoterSlug::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->where('status', 'voted')
            ->exists();

        $isEligible = $user->isVoterInElection($election->id);

        $canVote = $isEligible
            && ! $hasVoted
            && $election->status === 'active'
            && $election->start_date <= now()
            && $election->end_date   >= now();

        return Inertia::render('Election/Show', [
            'election'   => $election,
            'hasVoted'   => $hasVoted,
            'canVote'    => $canVote,
            'isEligible' => $isEligible,
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
    public function start(string $slug): RedirectResponse
    {
        $election = Election::withoutGlobalScopes()
            ->where('slug', $slug)
            ->where('type', 'real')
            ->firstOrFail();

        $user = auth()->user();

        if (! $user->isVoterInElection($election->id)) {
            return redirect()->route('elections.show', $slug)
                ->with('error', 'You are not eligible to vote in this election.');
        }

        $hasVoted = VoterSlug::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->where('status', 'voted')
            ->exists();

        if ($hasVoted) {
            return redirect()->route('elections.show', $slug)
                ->with('info', 'You have already voted.');
        }

        // Reuse an unexpired active slug rather than creating duplicates
        $existing = VoterSlug::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();

        if ($existing) {
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
