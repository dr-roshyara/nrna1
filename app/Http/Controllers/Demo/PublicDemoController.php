<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Models\DemoCandidacy;
use App\Models\DemoPost;
use App\Models\DemoVote;
use App\Models\Election;
use App\Models\PublicDemoSession;
use App\Services\DemoElectionResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

/**
 * PublicDemoController - Anonymous 5-Step Demo Voting Flow
 *
 * Handles the complete voting workflow for visitors who are NOT logged in.
 * Each anonymous visitor is tracked by their Laravel session ID via PublicDemoSession.
 *
 * No user_id anywhere. Identity = session_token (Laravel session ID).
 *
 * Routes (all outside auth middleware):
 *   GET  /public-demo/start                → Entry point
 *   GET  /public-demo/{token}/code         → Step 1: code entry (code shown on screen)
 *   POST /public-demo/{token}/code         → Step 1 submit
 *   GET  /public-demo/{token}/agreement    → Step 2: agreement
 *   POST /public-demo/{token}/agreement    → Step 2 submit
 *   GET  /public-demo/{token}/vote         → Step 3: ballot
 *   POST /public-demo/{token}/vote         → Step 3 submit
 *   GET  /public-demo/{token}/verify       → Step 4: review
 *   POST /public-demo/{token}/verify       → Step 4 confirm (final submit)
 *   GET  /public-demo/{token}/thank-you    → Step 5: complete
 */
class PublicDemoController extends Controller
{
    public function __construct(private readonly DemoElectionResolver $resolver) {}

    // ─────────────────────────────────────────────────────────────────────────
    // Tutorial / Guide (no session required)
    // ─────────────────────────────────────────────────────────────────────────

    public function guide(): Response
    {
        $user = auth()->user();

        // Build org demo URL only if user is authenticated
        $org_demo_url = null;
        if ($user && $user->organisation) {
            $org_demo_url = route('election.demo.start', ['organisation_slug' => $user->organisation->slug]);
        }

        return Inertia::render('Vote/DemoVote/Guide', [
            'start_url'     => route('public-demo.start'),
            'org_demo_url'  => $org_demo_url,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Entry Point
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Start the public demo: get/create a PublicDemoSession, redirect to Step 1.
     */
    public function start(): \Illuminate\Http\RedirectResponse
    {
        $election = $this->resolver->getPublicDemoElection();

        if (!$election) {
            abort(503, 'Demo election is not currently available.');
        }

        $sessionToken = session()->getId();

        $demoSession = PublicDemoSession::firstOrCreate(
            ['session_token' => $sessionToken],
            [
                'election_id' => $election->id,
                'display_code' => $this->generateDisplayCode(),
                'current_step' => 1,
                'expires_at' => now()->addMinutes(60),
            ]
        );

        // Reset session if: completed, expired, or the resolver found a better election
        if ($demoSession->has_voted || $demoSession->isExpired() || $demoSession->election_id !== $election->id) {
            $demoSession->delete();
            $demoSession = PublicDemoSession::create([
                'session_token' => $sessionToken,
                'election_id' => $election->id,
                'display_code' => $this->generateDisplayCode(),
                'current_step' => 1,
                'expires_at' => now()->addMinutes(60),
            ]);
        }

        return redirect()->route('public-demo.code.show', $demoSession->session_token);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 1: Code Entry (Code Displayed On Screen)
    // ─────────────────────────────────────────────────────────────────────────

    public function codeShow(PublicDemoSession $publicDemoSession): Response
    {
        return Inertia::render('Code/DemoCode/Create', [
            'name' => 'Demo Voter',
            'user_id' => '',
            'state' => 'code_sent',
            'code_duration' => 0,
            'code_expires_in' => 60,
            'slug' => $publicDemoSession->session_token,
            'useSlugPath' => true,
            'has_valid_email' => false,
            'show_code_fallback' => true,
            'verification_code' => $publicDemoSession->display_code,
            'election_type' => 'demo',
            'is_public_demo' => true,
        ]);
    }

    public function codeVerify(Request $request, PublicDemoSession $publicDemoSession): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['voting_code' => ['required', 'string']]);

        if (strtoupper(trim($request->voting_code)) !== $publicDemoSession->display_code) {
            return back()->withErrors(['voting_code' => 'The code you entered is incorrect. Please check and try again.']);
        }

        $publicDemoSession->update([
            'code_verified' => true,
            'current_step' => 2,
        ]);

        return redirect()->route('public-demo.agreement.show', $publicDemoSession->session_token);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 2: Agreement
    // ─────────────────────────────────────────────────────────────────────────

    public function agreementShow(PublicDemoSession $publicDemoSession): Response
    {
        $this->requireStep($publicDemoSession, 2);

        return Inertia::render('Code/DemoCode/Agreement', [
            'slug' => $publicDemoSession->session_token,
            'useSlugPath' => true,
            'election_type' => 'demo',
            'is_public_demo' => true,
        ]);
    }

    public function agreementSubmit(Request $request, PublicDemoSession $publicDemoSession): \Illuminate\Http\RedirectResponse
    {
        $this->requireStep($publicDemoSession, 2);

        $request->validate(['agreement' => ['required', 'accepted']]);

        $publicDemoSession->update([
            'agreed' => true,
            'current_step' => 3,
        ]);

        return redirect()->route('public-demo.vote.show', $publicDemoSession->session_token);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 3: Vote (Ballot Selection)
    // ─────────────────────────────────────────────────────────────────────────

    public function voteShow(PublicDemoSession $publicDemoSession): Response|\Illuminate\Http\RedirectResponse
    {
        $this->requireStep($publicDemoSession, 3);

        $election = Election::withoutGlobalScopes()->find($publicDemoSession->election_id);

        // Election deleted — start fresh
        if (!$election) {
            $publicDemoSession->delete();
            return redirect()->route('public-demo.start');
        }

        // Election exists but has no data — resolver may have found a better one
        if (!$this->sessionElectionHasData($election)) {
            $better = $this->resolver->getPublicDemoElection();
            if ($better && $better->id !== $election->id) {
                $publicDemoSession->update(['election_id' => $better->id]);
                $election = $better;
            }
        }

        $nationalPosts = $this->buildPostsData($election, true);
        $regionalPosts = $this->buildPostsData($election, false);

        // ── DIAGNOSTIC TOOLS ─────────────────────────────────────────────────
        // Use these to trace the exact data reaching the frontend on production.
        //
        // TOOL 1 — Database counts (uncomment, reload, re-comment):
        // dd([
        //     'election_id'                      => $election->id,
        //     'total_posts'                      => DemoPost::withoutGlobalScopes()->where('election_id', $election->id)->count(),
        //     'total_candidacies_in_db'          => DemoCandidacy::withoutGlobalScopes()->count(),
        //     'candidacies_matching_election_id' => DemoCandidacy::withoutGlobalScopes()->where('election_id', $election->id)->count(),
        //     'candidacies_by_post_id'           => DemoCandidacy::withoutGlobalScopes()->whereIn(
        //         'post_id',
        //         DemoPost::withoutGlobalScopes()->where('election_id', $election->id)->pluck('id')
        //     )->count(),
        // ]);
        //
        // TOOL 2 — Exact JSON payload sent to Vue (uncomment, reload, re-comment):
        // return response()->json([
        //     'national_sample' => $nationalPosts->first(),
        //     'regional_sample' => $regionalPosts->first(),
        //     'national_count'  => $nationalPosts->count(),
        //     'regional_count'  => $regionalPosts->count(),
        //     'first_post_candidate_count' => $nationalPosts->first()?->get('candidates')?->count() ?? 0,
        // ]);
        // ─────────────────────────────────────────────────────────────────────

        return Inertia::render('Vote/DemoVote/Create', [
            'posts' => [
                'national' => $nationalPosts,
                'regional' => $regionalPosts,
            ],
            'user_name' => 'Demo Voter',
            'user_id' => null,
            'user_region' => null,
            'slug' => $publicDemoSession->session_token,
            'useSlugPath' => true,
            'election' => [
                'id' => $election->id,
                'name' => $election->name,
                'type' => $election->type,
                'description' => $election->description,
                'is_active' => $election->is_active,
            ],
            'election_type' => 'demo',
            'is_public_demo' => true,
            'select_all_required' => config('voting.select_all_required', false),
        ]);
    }

    public function voteSubmit(Request $request, PublicDemoSession $publicDemoSession): \Illuminate\Http\RedirectResponse
    {
        $this->requireStep($publicDemoSession, 3);

        $request->validate([
            'national_selected_candidates' => ['present', 'array'],
            'regional_selected_candidates' => ['present', 'array'],
        ]);

        $selections = [
            'national' => $request->national_selected_candidates ?? [],
            'regional' => $request->regional_selected_candidates ?? [],
            'no_vote_posts' => $request->no_vote_posts ?? [],
        ];

        $publicDemoSession->update([
            'candidate_selections' => $selections,
            'current_step' => 4,
        ]);

        return redirect()->route('public-demo.verify.show', $publicDemoSession->session_token);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 4: Verify & Final Confirm
    // ─────────────────────────────────────────────────────────────────────────

    public function verifyShow(PublicDemoSession $publicDemoSession): Response
    {
        $this->requireStep($publicDemoSession, 4);

        $selections = $publicDemoSession->candidate_selections ?? [];

        return Inertia::render('Vote/DemoVote/Verify', [
            'selected_votes' => $selections,
            'total_votes' => count($selections['national'] ?? []) + count($selections['regional'] ?? []),
            'vote_data' => [
                'national_posts' => $selections['national'] ?? [],
                'regional_posts' => $selections['regional'] ?? [],
                'national_selected_candidates' => $selections['national'] ?? [],
                'regional_selected_candidates' => $selections['regional'] ?? [],
                'no_vote_posts' => $selections['no_vote_posts'] ?? [],
            ],
            'user_info' => [
                'name' => 'Demo Voter',
                'user_id' => null,
                'region' => null,
            ],
            'timing_info' => [
                'started_at' => $publicDemoSession->created_at?->toDateTimeString(),
                'expires_at' => $publicDemoSession->expires_at?->toDateTimeString(),
            ],
            'voting_summary' => [
                'voted_posts' => count($selections['national'] ?? []) + count($selections['regional'] ?? []),
                'no_vote_posts' => count($selections['no_vote_posts'] ?? []),
                'total_posts' => count($selections['national'] ?? []) + count($selections['regional'] ?? []),
            ],
            'debug_code' => $publicDemoSession->display_code,
            'verify_url' => route('public-demo.verify.show', $publicDemoSession->session_token),
            'slug' => $publicDemoSession->session_token,
            'useSlugPath' => true,
            'election_type' => 'demo',
            'is_public_demo' => true,
        ]);
    }

    public function verifyConfirm(Request $request, PublicDemoSession $publicDemoSession): \Illuminate\Http\RedirectResponse
    {
        $this->requireStep($publicDemoSession, 4);

        $request->validate(['voting_code' => ['required', 'string']]);

        if (strtoupper(trim($request->voting_code)) !== $publicDemoSession->display_code) {
            return back()->withErrors(['voting_code' => 'The code you entered is incorrect.']);
        }

        $receiptHash = strtoupper(substr(hash('sha256', $publicDemoSession->session_token . now()->timestamp), 0, 12));

        $publicDemoSession->update([
            'has_voted' => true,
            'voted_at' => now(),
            'current_step' => 5,
        ]);

        session(['public_demo_receipt_' . $publicDemoSession->session_token => $receiptHash]);

        return redirect()->route('public-demo.thankyou', $publicDemoSession->session_token);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 5: Thank You
    // ─────────────────────────────────────────────────────────────────────────

    public function thankYou(PublicDemoSession $publicDemoSession): Response
    {
        $receiptHash = session('public_demo_receipt_' . $publicDemoSession->session_token,
            strtoupper(substr(hash('sha256', $publicDemoSession->session_token), 0, 12))
        );

        $selections = $publicDemoSession->candidate_selections ?? [];
        $allPosts = array_merge($selections['national'] ?? [], $selections['regional'] ?? []);

        return Inertia::render('Vote/DemoVote/ThankYou', [
            'is_public_demo' => true,
            'election_type' => 'demo',
            'receipt_hash' => $receiptHash,
            'verify_url' => route('public-demo.verify.show', $publicDemoSession->session_token),
            'voted_at' => $publicDemoSession->voted_at?->format('d.m.Y H:i:s'),
            'national_posts' => $selections['national'] ?? [],
            'regional_posts' => $selections['regional'] ?? [],
            'votes_count' => count($allPosts),
            'slug' => $publicDemoSession->session_token,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Result: Enter receipt hash → reveal voted candidates
    // ─────────────────────────────────────────────────────────────────────────

    public function resultShow(PublicDemoSession $publicDemoSession): Response
    {
        if (!$publicDemoSession->has_voted) {
            return redirect()->route('public-demo.thankyou', $publicDemoSession->session_token);
        }

        $receiptHash = session('public_demo_receipt_' . $publicDemoSession->session_token,
            strtoupper(substr(hash('sha256', $publicDemoSession->session_token), 0, 12))
        );

        return Inertia::render('Vote/DemoVote/PublicResult', [
            'verified'       => false,
            'receipt_hash'   => $receiptHash,
            'voted_at'       => $publicDemoSession->voted_at?->format('d.m.Y H:i:s'),
            'national_posts' => [],
            'regional_posts' => [],
            'is_public_demo' => true,
            'slug'           => $publicDemoSession->session_token,
        ]);
    }

    public function resultVerify(Request $request, PublicDemoSession $publicDemoSession): \Illuminate\Http\RedirectResponse|Response
    {
        $request->validate(['receipt_hash' => ['required', 'string']]);

        $expectedHash = session('public_demo_receipt_' . $publicDemoSession->session_token,
            strtoupper(substr(hash('sha256', $publicDemoSession->session_token), 0, 12))
        );

        if (strtoupper(trim($request->receipt_hash)) !== $expectedHash) {
            return back()->withErrors(['receipt_hash' => 'The receipt code is incorrect. Please check and try again.']);
        }

        $selections = $publicDemoSession->candidate_selections ?? [];

        return Inertia::render('Vote/DemoVote/PublicResult', [
            'verified'       => true,
            'receipt_hash'   => $expectedHash,
            'voted_at'       => $publicDemoSession->voted_at?->format('d.m.Y H:i:s'),
            'national_posts' => $selections['national'] ?? [],
            'regional_posts' => $selections['regional'] ?? [],
            'is_public_demo' => true,
            'slug'           => $publicDemoSession->session_token,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Abort if the session hasn't reached the required step yet.
     */
    private function requireStep(PublicDemoSession $session, int $step): void
    {
        if ($session->current_step < $step) {
            abort(403, 'Please complete the previous steps first.');
        }
    }

    /**
     * Show aggregated public demo results — no auth required.
     *
     * Performance note: O(posts × votes × 60) iterations. Acceptable for demo
     * volumes (~600k iters at 1k votes). Optimize by indexing votes by post_id
     * if this becomes a bottleneck.
     */
    public function publicResults(): \Inertia\Response|\Illuminate\Http\RedirectResponse
    {
        $election = $this->resolver->getPublicDemoElection();

        if (!$election) {
            return redirect()->route('public-demo.guide');
        }

        // Only show results if the election is active and has data
        if (!$election->is_active) {
            return redirect()->route('public-demo.guide');
        }

        // Load posts for this election
        $posts = DemoPost::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->get(['id as post_id', 'name', 'state_name', 'required_number']);

        if ($posts->isEmpty()) {
            return \Inertia\Inertia::render('Demo/Result/Index', [
                'final_result' => ['total_votes' => 0, 'posts' => []],
                'posts' => [],
                'mode' => 'public',
                'organisation_id' => $election->organisation_id,
                'is_demo' => true,
                'page_title' => 'Public Digit Demo Election Results',
            ]);
        }

        // Calculate results (adapted from DemoResultController pattern)
        $results = $this->calculatePublicResults($posts, $election);

        return \Inertia\Inertia::render('Demo/Result/Index', [
            'final_result' => $results,
            'posts' => $posts,
            'mode' => 'public',
            'organisation_id' => $election->organisation_id,
            'is_demo' => true,
            'page_title' => 'Public Digit Demo Election Results',
        ]);
    }

    /**
     * Calculate aggregated vote results for the public demo election.
     */
    private function calculatePublicResults($posts, Election $election): array
    {
        $totalVotes = DemoVote::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->count();

        $results = [
            'total_votes' => $totalVotes,
            'posts' => [],
        ];

        // Pre-load all votes for this election to avoid N+1 on DB
        $allVotes = DemoVote::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->get();

        foreach ($posts as $post) {
            $postResults = [
                'post_id' => $post->post_id,
                'post_name' => $post->name,
                'state_name' => $post->state_name,
                'candidates' => [],
                'no_vote_count' => 0,
                'total_votes_for_post' => 0,
            ];

            // Get candidates for this post with user relationship loaded
            $allCandidates = DemoCandidacy::withoutGlobalScopes()
                ->where('post_id', $post->post_id)
                ->where('election_id', $election->id)
                ->with('user')
                ->get();

            $candidateVotes = [];
            foreach ($allCandidates as $c) {
                $candidateVotes[$c->id] = [
                    'name' => $c->user?->name ?? $c->user_name ?? $c->candidacy_name ?? $c->name ?? 'Unknown',
                    'count' => 0,
                ];
            }

            // Process each vote to count candidates for this post
            foreach ($allVotes as $vote) {
                for ($i = 1; $i <= 60; $i++) {
                    $field = 'candidate_' . str_pad((string)$i, 2, '0', STR_PAD_LEFT);
                    $candidateData = $vote->$field ? json_decode($vote->$field, true) : null;

                    if (!$candidateData || ($candidateData['post_id'] ?? null) !== $post->post_id) {
                        continue;
                    }

                    if (isset($candidateData['no_vote']) && $candidateData['no_vote'] === true) {
                        $postResults['no_vote_count']++;
                        $postResults['total_votes_for_post']++;
                        continue;
                    }

                    foreach ($candidateData['candidates'] ?? [] as $candidate) {
                        $candidateId = $candidate['candidacy_id'] ?? null;
                        if ($candidateId && isset($candidateVotes[$candidateId])) {
                            $candidateVotes[$candidateId]['count']++;
                            $postResults['total_votes_for_post']++;
                        }
                    }
                }
            }

            // Format and sort candidates by vote count descending
            foreach ($candidateVotes as $candidateId => $data) {
                $postResults['candidates'][] = [
                    'candidacy_id' => $candidateId,
                    'name' => $data['name'],
                    'vote_count' => $data['count'],
                    'vote_percent' => $postResults['total_votes_for_post'] > 0
                        ? round(($data['count'] / $postResults['total_votes_for_post']) * 100, 2)
                        : 0,
                ];
            }

            usort($postResults['candidates'], function ($a, $b) {
                if ($a['vote_count'] === $b['vote_count']) {
                    return strcmp($a['name'], $b['name']);
                }
                return $b['vote_count'] - $a['vote_count'];
            });

            $results['posts'][] = $postResults;
        }

        return $results;
    }

    /**
     * Quick check if an election has posts and candidates.
     */
    private function sessionElectionHasData(Election $election): bool
    {
        if ($election->posts_count > 0 && $election->candidates_count > 0) {
            return true;
        }

        return DemoPost::withoutGlobalScopes()->where('election_id', $election->id)->exists()
            && DemoCandidacy::withoutGlobalScopes()->where('election_id', $election->id)->exists();
    }

    /**
     * Generate a human-friendly display code (e.g. ABCD-1234).
     */
    private function generateDisplayCode(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < 8; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }

    /**
     * Build posts data for the vote page (national or regional).
     *
     * Strategy: eager-load via relationship (primary) with a direct decoupled
     * query fallback (Scenario B). Both paths call withoutGlobalScopes() to
     * bypass the BelongsToTenant scope on this anonymous public-demo route.
     */
    private function buildPostsData(Election $election, bool $national): \Illuminate\Support\Collection
    {
        return DemoPost::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->where('is_national_wide', $national)
            ->with(['candidacies' => function ($query) {
                $query->withoutGlobalScopes()
                      ->orderBy('position_order');
            }])
            ->orderBy('position_order')
            ->get()
            ->map(function (DemoPost $post) use ($election) {
                // Primary path: use the eager-loaded relation.
                // Fallback: if the relation returned empty despite data existing,
                // run a direct decoupled query (Scenario B — tenant scope re-applied).
                $candidacies = $post->candidacies;

                if ($candidacies->isEmpty()) {
                    // Scenario B fallback: direct query bypassing all scopes.
                    // Filter by election_id first; if still empty (Scenario A — old
                    // data created before election_id was stored), fall back to post_id only.
                    $candidacies = DemoCandidacy::withoutGlobalScopes()
                        ->where('post_id', $post->id)
                        ->where('election_id', $election->id)
                        ->orderBy('position_order')
                        ->get();

                    if ($candidacies->isEmpty()) {
                        $candidacies = DemoCandidacy::withoutGlobalScopes()
                            ->where('post_id', $post->id)
                            ->orderBy('position_order')
                            ->get();
                    }
                }

                return [
                    'id'               => $post->id,
                    'post_id'          => $post->id,
                    'name'             => $post->name,
                    'post_name'        => $post->name,
                    'nepali_name'      => $post->nepali_name,
                    'is_national_wide' => $post->is_national_wide,
                    'required_number'  => $post->required_number,
                    'position_order'   => $post->position_order,
                    'state_name'       => $post->state_name,
                    'candidates'       => $candidacies->map(fn ($c) => [
                        'id'             => $c->id,
                        'candidacy_id'   => $c->id,
                        'user_id'        => $c->user_id,
                        'user_name'      => $c->user_name ?? $c->candidacy_name ?? $c->name ?? 'Demo Candidate',
                        'candidacy_name' => $c->candidacy_name ?? $c->user_name ?? $c->name ?? 'Demo Candidate',
                        'description'    => $c->description,
                        'position_order' => $c->position_order,
                        'image_path_1'   => $c->image_path_1,
                        'post_id'        => $c->post_id,
                        'is_selected'    => false,
                    ])->values(),
                ];
            });
    }
}
