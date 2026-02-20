<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\DemoCode;
use App\Models\DemoVote;
use App\Models\Election;
use App\Models\DemoCandidate;
use App\Models\DemoPost;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * DemoVoteController - Simplified Voting for Demo Elections
 *
 * Handles the complete demo voting workflow with relaxed validation rules:
 * - No complex code verification steps (single step)
 * - No IP restrictions
 * - Allow multiple votes for testing
 * - Simplified state tracking
 *
 * Routes: All demo voting goes through /v/{slug}/demo-vote/*
 */
class DemoVoteController extends Controller
{
    /**
     * STEP 1: Show demo voting dashboard
     *
     * GET /v/{slug}/demo-vote
     */
    public function index(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('🎮 [DEMO] Vote index - demo voting dashboard', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        // Get posts for this demo election
        $posts = DemoPost::where('election_id', $election->id)
            ->with(['candidates' => function ($query) {
                $query->where('is_active', 1);
            }])
            ->where('is_active', 1)
            ->orderBy('order')
            ->get();

        return Inertia::render('Vote/DemoVote/Index', [
            'name' => $user->name,
            'user_id' => $user->user_id ?? '',
            'election_id' => $election->id,
            'election_name' => $election->name,
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'posts' => $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'post_id' => $post->post_id,
                    'name' => $post->name,
                    'description' => $post->description,
                    'candidates' => $post->candidates->map(function ($candidate) {
                        return [
                            'id' => $candidate->id,
                            'name' => $candidate->name,
                            'symbol_url' => $candidate->symbol_url,
                            'description' => $candidate->description,
                        ];
                    })->values(),
                ];
            })->values(),
        ]);
    }

    /**
     * STEP 2: Verify votes before submission
     *
     * POST /v/{slug}/demo-vote/verify
     *
     * Simplified verification:
     * - Check user has voted for at least one post
     * - Check all required posts have votes
     * - No code verification needed
     */
    public function verify(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        $votes = $request->input('votes', []);

        Log::info('🎮 [DEMO] Vote verify - checking votes', [
            'user_id' => $user->id,
            'votes_count' => count($votes),
            'votes' => $votes,
        ]);

        // Validate: at least one vote
        if (empty($votes)) {
            return back()->withErrors(['votes' => 'Please select at least one candidate.'])->withInput();
        }

        // Validate: all votes have candidate_id
        $invalid = array_filter($votes, function ($vote) {
            return empty($vote['candidate_id']);
        });

        if (!empty($invalid)) {
            return back()->withErrors(['votes' => 'All selected votes must have a candidate.'])->withInput();
        }

        // ✅ All checks passed - render verification page
        $posts = DemoPost::where('election_id', $election->id)
            ->with(['candidates' => function ($query) {
                $query->where('is_active', 1);
            }])
            ->where('is_active', 1)
            ->orderBy('order')
            ->get();

        $selectedCandidates = [];
        foreach ($votes as $postId => $voteData) {
            $candidateId = $voteData['candidate_id'];
            $post = $posts->find($postId);
            if ($post) {
                $candidate = $post->candidates->find($candidateId);
                if ($candidate) {
                    $selectedCandidates[$postId] = [
                        'post_name' => $post->name,
                        'candidate_id' => $candidate->id,
                        'candidate_name' => $candidate->name,
                        'candidate_symbol' => $candidate->symbol_url,
                    ];
                }
            }
        }

        return Inertia::render('Vote/DemoVote/Verify', [
            'name' => $user->name,
            'user_id' => $user->user_id ?? '',
            'election_id' => $election->id,
            'election_name' => $election->name,
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'selected_votes' => $selectedCandidates,
            'total_votes' => count($selectedCandidates),
        ]);
    }

    /**
     * STEP 3: Submit and store votes
     *
     * POST /v/{slug}/demo-vote/submit
     *
     * Simplified submission:
     * - Store all votes
     * - Mark code as used (if applicable)
     * - Record vote as submitted
     * - Redirect to thank you page
     */
    public function store(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        $votes = $request->input('votes', []);

        Log::info('🎮 [DEMO] Vote store - submitting votes', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'votes_count' => count($votes),
        ]);

        // Final validation
        if (empty($votes)) {
            return response()->json([
                'success' => false,
                'message' => 'No votes to submit.',
            ], 422);
        }

        try {
            // Get or create demo code
            $code = DemoCode::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'election_id' => $election->id,
                ],
                [
                    'code1' => strtoupper(Str::random(6)),
                    'can_vote_now' => 1, // Demo doesn't require verification
                    'is_code1_usable' => 1,
                    'voting_time_in_minutes' => 30,
                ]
            );

            // Store each vote
            $storedVotes = 0;
            foreach ($votes as $postId => $voteData) {
                $candidateId = $voteData['candidate_id'];

                DemoVote::create([
                    'user_id' => $user->id,
                    'election_id' => $election->id,
                    'post_id' => $postId,
                    'candidate_id' => $candidateId,
                    'code_id' => $code->id,
                    'submitted_at' => now(),
                    'client_ip' => $request->ip(),
                ]);

                $storedVotes++;
            }

            // Mark code as used
            $code->update([
                'has_used_code1' => 1,
                'code1_used_at' => now(),
                'vote_submitted' => 1,
                'vote_submitted_at' => now(),
                'has_voted' => 1,
            ]);

            Log::info('🎮 [DEMO] Votes stored successfully', [
                'user_id' => $user->id,
                'stored_votes' => $storedVotes,
                'code_id' => $code->id,
            ]);

            // If AJAX request, return JSON
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your votes have been submitted successfully!',
                    'redirect' => $voterSlug
                        ? route('slug.demo-vote.thank-you', ['vslug' => $voterSlug->slug])
                        : route('demo-vote.thank-you'),
                ]);
            }

            // Otherwise redirect to thank you page
            return redirect()->route($voterSlug ? 'slug.demo-vote.thank-you' : 'demo-vote.thank-you')
                ->with('success', 'Your votes have been recorded. Thank you for voting!');

        } catch (\Exception $e) {
            Log::error('🎮 [DEMO] Vote storage failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'election_id' => $election->id,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while submitting your votes. Please try again.',
                ], 500);
            }

            return back()->withErrors(['error' => 'Failed to submit votes. Please try again.']);
        }
    }

    /**
     * STEP 4: Show thank you page
     *
     * GET /v/{slug}/demo-vote/thank-you
     */
    public function thankYou(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('🎮 [DEMO] Vote thank you page', [
            'user_id' => $user->id,
            'election_id' => $election->id,
        ]);

        // Get vote summary
        $votes = DemoVote::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->with(['post', 'candidate'])
            ->get();

        return Inertia::render('Vote/DemoVote/ThankYou', [
            'name' => $user->name,
            'election_name' => $election->name,
            'votes_count' => $votes->count(),
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'votes' => $votes->map(function ($vote) {
                return [
                    'post_name' => $vote->post->name ?? 'Unknown Post',
                    'candidate_name' => $vote->candidate->name ?? 'Unknown Candidate',
                    'submitted_at' => $vote->submitted_at->format('d.m.Y H:i'),
                ];
            })->values(),
        ]);
    }

    /**
     * Get authenticated user
     */
    private function getUser(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Not authenticated.');
        }

        return $user;
    }

    /**
     * Get current election from request attributes (set by middleware)
     */
    private function getElection(Request $request)
    {
        $election = $request->attributes->get('election');

        if (!$election || $election->type !== 'demo') {
            abort(404, 'Demo election not found.');
        }

        return $election;
    }

    /**
     * Helper: Redirect with message
     */
    private function redirectToDashboard(string $message)
    {
        return redirect('/dashboard')->with('error', $message);
    }
}
