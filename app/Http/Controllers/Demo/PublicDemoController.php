<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Models\DemoCandidacy;
use App\Models\DemoPost;
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
        return Inertia::render('Vote/DemoVote/Guide', [
            'start_url' => route('public-demo.start'),
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

        // If session exists but was completed, give a fresh one
        if ($demoSession->has_voted || $demoSession->isExpired()) {
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

    public function voteShow(PublicDemoSession $publicDemoSession): Response
    {
        $this->requireStep($publicDemoSession, 3);

        $election = Election::withoutGlobalScopes()->find($publicDemoSession->election_id);

        $nationalPosts = $this->buildPostsData($election, true);
        $regionalPosts = $this->buildPostsData($election, false);

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
     */
    private function buildPostsData(Election $election, bool $national): \Illuminate\Support\Collection
    {
        return DemoPost::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->where('is_national_wide', $national ? 1 : 0)
            ->orderBy('position_order')
            ->get()
            ->map(function (DemoPost $post) {
                $candidates = DemoCandidacy::withoutGlobalScopes()
                    ->where('post_id', $post->id)
                    ->orderBy('position_order')
                    ->get()
                    ->map(fn ($c) => [
                        'id' => $c->id,
                        'candidacy_id' => $c->id,
                        'user_name' => $c->candidacy_name ?? $c->user_name ?? 'Demo Candidate',
                        'candidacy_name' => $c->candidacy_name ?? $c->user_name ?? 'Demo Candidate',
                        'description' => $c->description,
                        'position_order' => $c->position_order,
                        'user_id' => null,
                        'is_selected' => false,
                    ]);

                return [
                    'id' => $post->id,
                    'post_id' => $post->id,
                    'name' => $post->name,
                    'post_name' => $post->name,
                    'nepali_name' => $post->nepali_name,
                    'is_national_wide' => $post->is_national_wide,
                    'required_number' => $post->required_number,
                    'position_order' => $post->position_order,
                    'state_name' => $post->state_name,
                    'candidates' => $candidates,
                ];
            });
    }
}
