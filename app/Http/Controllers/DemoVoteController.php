<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\DemoCode;
use App\Models\DemoVote;
use App\Models\Election;
use App\Models\DemoCandidacy;
use App\Models\DemoPost;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * DemoVoteController - IDENTICAL Voting Experience for Demo Elections
 *
 * This controller mirrors VoteController exactly, providing the same user experience
 * and workflow, but using demo models (DemoCode, DemoVote, DemoCandidate, DemoPost).
 *
 * Key differences from real voting:
 * - No IP restrictions (allows testing from same machine)
 * - Allows multiple votes (for testing purposes)
 * - Uses demo election models instead of real models
 * - Same validation, same steps, same UI feeling
 *
 * Workflow (IDENTICAL to VoteController):
 * 1. Show voting form with posts and candidates
 * 2. User selects candidates
 * 3. First submission and code verification
 * 4. Agreement/consent
 * 5. Final vote verification
 * 6. Vote storage
 * 7. Thank you page
 */
class DemoVoteController extends Controller
{
    /**
     * STEP 1: Show voting form with posts and candidates
     * GET /v/{slug}/demo-vote/create
     *
     * This shows the same form as real voting, displaying:
     * - All posts (constituencies)
     * - All candidates for each post
     * - Selection interface
     */
    public function create(Request $request)
    {
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('🎮 [DEMO] Vote create - showing voting form', [
            'user_id' => $auth_user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
        ]);

        // Get DemoCode - must be verified before accessing voting form
        $code = DemoCode::where('user_id', $auth_user->id)
            ->where('election_id', $election->id)
            ->first();

        // If no code or code not verified, redirect to code verification
        if (!$code || $code->can_vote_now != 1) {
            Log::info('🎮 [DEMO] Code not verified - redirecting to code verification', [
                'user_id' => $auth_user->id,
                'has_code' => $code ? 'yes' : 'no',
                'can_vote_now' => $code ? $code->can_vote_now : 'N/A',
            ]);

            return redirect()->route(
                $voterSlug ? 'slug.demo-code.create' : 'demo-code.create',
                $voterSlug ? ['vslug' => $voterSlug->slug] : []
            )->with('info', 'Please verify your code first.');
        }

        // Fetch demo posts and candidates (same structure as real voting)
        $demoCandidates = DemoCandidacy::where('election_id', $election->id)
            ->orderBy('position_order')
            ->get();
        $groupedCandidates = $demoCandidates->groupBy('post_id');

        $posts = DemoPost::where('election_id', $election->id)
            ->orderBy('post_id')
            ->get()
            ->map(function ($post) use ($groupedCandidates) {
                $candidatesForPost = $groupedCandidates->get($post->post_id, collect());

                return [
                    'post_id' => $post->post_id,
                    'id' => $post->id,
                    'name' => $post->name,
                    'nepali_name' => $post->nepali_name,
                    'description' => $post->description,
                    'required_number' => $post->required_number,
                    'candidates' => $candidatesForPost->map(function ($c) {
                        return [
                            'id' => $c->id,
                            'candidacy_id' => $c->candidacy_id,
                            'name' => $c->name,
                            'user_name' => $c->user_name ?? 'Demo Candidate',
                            'image_path' => $c->image_path_1,
                            'description' => $c->description,
                            'proposer_name' => $c->proposer_name,
                            'supporter_name' => $c->supporter_name,
                            'position_order' => $c->position_order,
                        ];
                    })->values(),
                ];
            })->values();

        return Inertia::render('Vote/DemoVote/Create', [
            'name' => $auth_user->name,
            'user_id' => $auth_user->id,
            'election_id' => $election->id,
            'election_name' => $election->name,
            'election_type' => 'demo',
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'posts' => $posts,
            'code_id' => $code->id,
            'voting_window_minutes' => $code->voting_time_in_minutes,
        ]);
    }

    /**
     * STEP 2: Submit votes (first submission with validation)
     * POST /v/{slug}/demo-vote/submit
     *
     * Validates selected votes and prepares for verification
     * (Same validation as real voting)
     */
    public function firstSubmission(Request $request)
    {
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        $votes = $request->input('votes', []);

        Log::info('🎮 [DEMO] Vote first_submission - validating votes', [
            'user_id' => $auth_user->id,
            'votes_count' => count($votes),
        ]);

        // Validate: at least one vote
        if (empty($votes)) {
            return back()->withErrors(['votes' => 'Please select at least one candidate.'])->withInput();
        }

        // Validate: all votes have candidate_id
        foreach ($votes as $postId => $voteData) {
            if (empty($voteData['candidate_id'])) {
                return back()->withErrors(['votes' => 'All selected votes must have a candidate.'])->withInput();
            }
        }

        // Get DemoCode
        $code = DemoCode::where('user_id', $auth_user->id)
            ->where('election_id', $election->id)
            ->first();

        if (!$code) {
            return back()->withErrors(['code' => 'Code verification failed. Please start over.']);
        }

        // Store votes in session for verification step
        session([
            'demo_votes' => $votes,
            'demo_code_id' => $code->id,
            'demo_election_id' => $election->id,
        ]);

        // Mark code as used for first stage
        $code->update([
            'has_used_code1' => 1,
            'code1_used_at' => now(),
            'is_code1_usable' => 0,
        ]);

        Log::info('🎮 [DEMO] Vote first_submission - moving to agreement', [
            'user_id' => $auth_user->id,
            'votes_stored' => count($votes),
        ]);

        // Redirect to agreement page (same as real voting)
        return redirect()->route($voterSlug ? 'slug.demo-vote.agreement' : 'demo-vote.agreement', $voterSlug ? ['vslug' => $voterSlug->slug] : [])
            ->with('success', 'Votes validated. Please confirm your agreement.');
    }

    /**
     * STEP 3: Show agreement/consent page
     * GET /v/{slug}/demo-vote/agreement
     *
     * User confirms they understand the voting rules and commit to vote
     */
    public function showAgreement(Request $request)
    {
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        if (!session()->has('demo_votes')) {
            return redirect()->route($voterSlug ? 'slug.demo-vote.create' : 'demo-vote.create', $voterSlug ? ['vslug' => $voterSlug->slug] : []);
        }

        Log::info('🎮 [DEMO] Vote agreement page shown', [
            'user_id' => $auth_user->id,
        ]);

        return Inertia::render('Vote/DemoVote/Agreement', [
            'name' => $auth_user->name,
            'election_name' => $election->name,
            'election_type' => 'demo',
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'votes_count' => count(session('demo_votes', [])),
        ]);
    }

    /**
     * STEP 4: Submit agreement and show verification
     * POST /v/{slug}/demo-vote/agreement
     */
    public function submitAgreement(Request $request)
    {
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        $agree = $request->input('agree');

        if (!$agree) {
            return back()->withErrors(['agree' => 'You must agree to proceed.']);
        }

        // Get code and mark agreement accepted
        $code = DemoCode::find(session('demo_code_id'));
        if ($code) {
            $code->update([
                'has_agreed_to_vote' => 1,
                'has_agreed_to_vote_at' => now(),
            ]);
        }

        Log::info('🎮 [DEMO] Vote agreement submitted', [
            'user_id' => $auth_user->id,
        ]);

        return redirect()->route($voterSlug ? 'slug.demo-vote.verify' : 'demo-vote.verify', $voterSlug ? ['vslug' => $voterSlug->slug] : []);
    }

    /**
     * STEP 5: Verify votes before final submission
     * GET /v/{slug}/demo-vote/verify
     *
     * Show summary of selected votes for final confirmation
     */
    public function verify(Request $request)
    {
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        $votes = session('demo_votes', []);

        if (empty($votes)) {
            return redirect()->route($voterSlug ? 'slug.demo-vote.create' : 'demo-vote.create', $voterSlug ? ['vslug' => $voterSlug->slug] : []);
        }

        // Fetch posts and candidates for verification display
        $allPosts = DemoPost::where('election_id', $election->id)->get();
        $allCandidates = DemoCandidacy::where('election_id', $election->id)->get();

        $verificationData = [];
        foreach ($votes as $postId => $voteData) {
            $candidateId = $voteData['candidate_id'];
            $post = $allPosts->find($postId);
            $candidate = $allCandidates->find($candidateId);

            if ($post && $candidate) {
                $verificationData[] = [
                    'post_id' => $postId,
                    'post_name' => $post->name,
                    'candidate_id' => $candidateId,
                    'candidate_name' => $candidate->name,
                    'candidate_image' => $candidate->image_path_1,
                ];
            }
        }

        Log::info('🎮 [DEMO] Vote verify page shown', [
            'user_id' => $auth_user->id,
            'selected_votes' => count($verificationData),
        ]);

        return Inertia::render('Vote/DemoVote/Verify', [
            'name' => $auth_user->name,
            'election_name' => $election->name,
            'election_type' => 'demo',
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'selected_votes' => $verificationData,
            'total_votes' => count($verificationData),
        ]);
    }

    /**
     * STEP 6: Final vote storage
     * POST /v/{slug}/demo-vote/submit-final
     *
     * Store votes in DemoVote table (same flow as real voting)
     */
    public function store(Request $request)
    {
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        $votes = session('demo_votes', []);

        if (empty($votes)) {
            return back()->withErrors(['votes' => 'No votes to submit.']);
        }

        try {
            $codeId = session('demo_code_id');
            $code = DemoCode::find($codeId);

            if (!$code) {
                return back()->withErrors(['code' => 'Code not found.']);
            }

            // Store each vote
            $storedCount = 0;
            foreach ($votes as $postId => $voteData) {
                DemoVote::create([
                    'user_id' => $auth_user->id,
                    'election_id' => $election->id,
                    'post_id' => $postId,
                    'candidate_id' => $voteData['candidate_id'],
                    'code_id' => $code->id,
                    'submitted_at' => now(),
                    'client_ip' => $request->ip(),
                ]);
                $storedCount++;
            }

            // Mark code as fully used
            $code->update([
                'has_used_code2' => 1,
                'code2_used_at' => now(),
                'vote_submitted' => 1,
                'vote_submitted_at' => now(),
                'has_voted' => 1,
                'is_code2_usable' => 0,
            ]);

            Log::info('🎮 [DEMO] Votes stored successfully', [
                'user_id' => $auth_user->id,
                'stored_votes' => $storedCount,
            ]);

            // Clear session
            session()->forget(['demo_votes', 'demo_code_id', 'demo_election_id']);

            return redirect()->route($voterSlug ? 'slug.demo-vote.thank-you' : 'demo-vote.thank-you', $voterSlug ? ['vslug' => $voterSlug->slug] : [])
                ->with('success', 'Your votes have been recorded successfully!');

        } catch (\Exception $e) {
            Log::error('🎮 [DEMO] Vote storage failed', [
                'error' => $e->getMessage(),
                'user_id' => $auth_user->id,
            ]);

            return back()->withErrors(['error' => 'Failed to submit votes. Please try again.']);
        }
    }

    /**
     * STEP 7: Thank you page
     * GET /v/{slug}/demo-vote/thank-you
     *
     * Show success message and vote summary
     */
    public function thankYou(Request $request)
    {
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('🎮 [DEMO] Vote thank you page', [
            'user_id' => $auth_user->id,
        ]);

        // Get recent votes for this user in this election
        $votes = DemoVote::where('user_id', $auth_user->id)
            ->where('election_id', $election->id)
            ->with(['post', 'candidate'])
            ->orderByDesc('submitted_at')
            ->limit(20)
            ->get();

        return Inertia::render('Vote/DemoVote/ThankYou', [
            'name' => $auth_user->name,
            'election_name' => $election->name,
            'election_type' => 'demo',
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'votes_count' => $votes->count(),
            'votes' => $votes->map(function ($vote) {
                return [
                    'post_name' => $vote->post->name ?? 'Unknown',
                    'candidate_name' => $vote->candidate->name ?? 'Unknown',
                    'submitted_at' => $vote->submitted_at->format('d.m.Y H:i:s'),
                ];
            })->values(),
        ]);
    }

    /**
     * Helper: Get authenticated user
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
     * Helper: Get election from request (set by middleware)
     */
    private function getElection(Request $request)
    {
        $election = $request->attributes->get('election');

        if (!$election || $election->type !== 'demo') {
            abort(404, 'Demo election not found.');
        }

        return $election;
    }
}
