<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use App\Models\DemoVote;
use App\Models\DemoCode;
use App\Models\DemoCandidacy;
use App\Models\DemoPost;
use App\Models\DemoResult;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Candidacy;
use App\Models\Post;
use App\Models\Result;
use App\Models\Code;
use App\Models\Election;
use App\Models\Upload;
use App\Services\VotingServiceFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Services\VoterProgressService;
use Illuminate\Routing\Redirector;
use App\Notifications\SecondVerificationCode;
use App\Notifications\SendVoteSavingCode;
//controllers
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DemoVoteController extends Controller 
{
    public $vote ; 
    public $has_voted;
    public $in_code ; 
    public $out_code;
    public $user_id;
    public $verify_final_vote;
    public $vote_id_for_voter;
    public $session_name;
    public $session_id_for_verify_vote;

    /**
     * Constructor - Initialize voting process state
     */
    public function __construct()
    {
        $this->in_code = '';
        $this->verify_final_vote = false;
    }

    /**
     * ==========================================
     * CONFIGURATION & HELPER METHODS
     * ==========================================
     */

    /**
     * Check if system is in STRICT MODE (two separate codes)
     *
     * @return bool
     */
    private function isStrictMode(): bool
    {
        return config('voting.two_codes_system') == 1;
    }

    /**
     * Check if system is in SIMPLE MODE (one code, two uses)
     *
     * @return bool
     */
    private function isSimpleMode(): bool
    {
        return config('voting.two_codes_system') == 0;
    }

    /**
     * Expire a code by resetting all its state
     * Used when voting window timeout occurs
     *
     * @param DemoCode $code
     * @return void
     */
    private function expireCode(&$code): void
    {
        $code->can_vote_now = 0;
        $code->is_code1_usable = 0;
        $code->is_code2_usable = 0;
        $code->has_code1_sent = 0;
        $code->has_code2_sent = 0;
        $code->save();
    }

    /**
     * Verify Code1 state for SIMPLE MODE
     * In SIMPLE MODE, Code1 is used twice (entry + vote submission)
     * Check: code1_used_at is set (FIRST USE), code2_used_at is NULL (SECOND USE not yet used)
     *
     * @param DemoCode $code
     * @return string Route name if verification fails, empty string if passes
     */
    private function verifySimpleModeCodeState(&$code): string
    {
        // Check if code1 has been entered at /code/create (FIRST USE)
        if ($code->code1_used_at === null) {
            return "code.create";
        }
        
        // Check if code has already been used for voting (SECOND USE)
        // In SIMPLE MODE, code2_used_at tracks the second use (vote submission)
        if ($code->code2_used_at !== null) {
            return "dashboard";  // Code already used for voting
        }

        return "";  // All checks passed
    }

    /**
     * Verify Code1/Code2 state for STRICT MODE
     * In STRICT MODE, Code1 and Code2 are separate codes used sequentially
     * Check: code1_used_at is set, code2_used_at is NULL
     *
     * @param DemoCode $code
     * @return string Route name if verification fails, empty string if passes
     */
    private function verifyStrictModeCodeState(&$code): string
    {
        // In STRICT MODE, Code2 should not have been used yet
        if ($code->code2_used_at !== null || $code->is_code2_usable == 0) {
            return "dashboard";  // Code expired or already used
        }

        // Ensure Code1 was used first (should have code1_used_at set)
        if ($code->code1_used_at === null) {
            return "code.create";
        }

        return "";  // All checks passed
    }

    /**
     * Check if voting window has expired based on code1_used_at timestamp
     *
     * @param DemoCode $code
     * @return bool True if code has expired, false otherwise
     */
    private function hasVotingWindowExpired(&$code): bool
    {
        if ($code->code1_used_at === null) {
            return false;  // Code not yet used, can't be expired
        }

        $current = Carbon::now();
        $code1_used_at = $code->code1_used_at;
        $voting_time = (int) $code->voting_time_in_minutes;
        // Use parse($past)->diffInMinutes(now()) to get positive elapsed minutes
        $totalDuration = Carbon::parse($code1_used_at)->diffInMinutes($current);

        return $totalDuration > $voting_time;
    }

    /**
     * Get authenticated user from request or middleware
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User
     */
    private function getUser(Request $request): User
    {
        return $request->attributes->has('voter')
            ? $request->attributes->get('voter')
            : auth()->user();
    }

    /**
     * Get election from multiple sources:
     * 1. Middleware (ElectionMiddleware)
     * 2. Voter slug election_id (for demo voting)
     * 3. Default to first real election
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Election
     */
    private function getElection(Request $request): Election
    {
        // First, check if middleware set an election
        if ($request->attributes->has('election')) {
            return $request->attributes->get('election');
        }

        // Second, check if voter slug has election_id
        $voterSlug = $request->attributes->get('voter_slug');
        if ($voterSlug && $voterSlug->election_id) {
            // CRITICAL: Use withoutGlobalScopes() because demo elections are accessible
            // to ALL users regardless of organisation context (organisation_id=NULL)
            $election = Election::withoutGlobalScopes()->find($voterSlug->election_id);
            if ($election) {
                return $election;
            }
        }

        // Third, default to first real election
        $election = Election::where('type', 'real')->first();

        // If no real election found, try any active election (including demo)
        if (!$election) {
            $election = Election::withoutGlobalScopes()
                ->where('is_active', true)
                ->orderBy('id')
                ->first();
        }

        return $election;
    }

    /**
     * Get appropriate voting service for the election
     * Demo elections use DemoVotingService, real use RealVotingService
     *
     * @param \App\Models\Election $election
     * @return \App\Services\VotingService
     */
    private function getVotingService(Election $election)
    {
        return VotingServiceFactory::make($election);
    }

    /**
     * Check if user is eligible to vote in this election
     *
     * CRITICAL DIFFERENCE:
     * - Demo elections: Always allow (for testing, ignore timing restrictions)
     * - Real elections: Must check can_vote_now flag (timing restrictions apply)
     *
     * @param \App\Models\User $user
     * @param \App\Models\Election $election
     * @return bool
     */
    private function isUserEligibleToVote(User $user, Election $election): bool
    {
        if ($election->isDemo()) {
            // DEMO: Always allow (for testing)
            return true;
        }

        // REAL: Must check can_vote_now flag (timing restrictions)
        return $user->can_vote_now == 1;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
         
        
        //  here ends 
        return Inertia::render('Vote/VoteIndex', [
            //    "presidents" => $presidents,
            //   "vicepresidents" => $vicepresidents,
                // 'name'=>auth()->user()->name 
              
         ]);
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     




/**
 * STEP 3: Show voting form
 * Route: GET /v/{slug}/vote/create
 */
/**
 * STEP 3: Show voting form
 * Route: GET /v/{slug}/vote/create
 */
public function create(Request $request)
{
    // Get user and election context
    $auth_user = $this->getUser($request);
    $election = $this->getElection($request);
    $voterSlug = $request->attributes->get('voter_slug');

    // Set organisation context for tenant scoping
    // This ensures DemoCandidacy and DemoPost queries respect the organisation_id filter
    session(['current_organisation_id' => $election->organisation_id]);

    // Get organisation_id from voter slug if available (correct context)
    // Fall back to election's organisation_id
    $orgId = $voterSlug ? $voterSlug->organisation_id : $election->organisation_id;

    Log::info('Vote creation page accessed', [
        'user_id' => $auth_user->id,
        'election_id' => $election->id,
        'election_type' => $election->type,
        'organisation_id' => $orgId,
    ]);

    // Check election-aware eligibility
    if (!$this->isUserEligibleToVote($auth_user, $election)) {
        Log::warning('User not eligible to vote', [
            'user_id' => $auth_user->id,
            'election_id' => $election->id,
            'can_vote_now' => $auth_user->can_vote_now,
        ]);

        return redirect()->route('dashboard')
            ->with('error', 'You are not eligible to vote in this election.');
    }

    // Get code for this election
    $code = DemoCode::where('user_id', $auth_user->id)
        ->where('election_id', $election->id)
        ->first();

    if (!$code) {
        $route = $voterSlug ? 'slug.demo-code.create' : 'demo-code.create';
        $params = $voterSlug ? ['vslug' => $voterSlug->slug] : [];

        return redirect()->route($route, $params)
            ->with('error', 'Please verify your code first.');
    }

    // Check if user has agreed to voting terms (Step 2)
    if (!$code->has_agreed_to_vote) {
        Log::warning('User accessing voting page without accepting agreement', [
            'user_id' => $auth_user->id,
            'election_id' => $election->id,
            'has_agreed' => $code->has_agreed_to_vote,
        ]);

        $route = $voterSlug ? 'slug.demo-code.agreement' : 'demo-code.agreement';
        $params = $voterSlug ? ['vslug' => $voterSlug->slug] : [];

        return redirect()->route($route, $params)
            ->with('error', 'Please accept the voting agreement first.');
    }

    // IP validation
    $ipValidation = validateVotingIpWithResponse();
    if ($ipValidation instanceof \Inertia\Response) {
        return $ipValidation;
    }

    // --- Fetch National Posts and Candidates ---
    if ($election->isDemo()) {
        // ✅ FIXED: Properly group candidates by post_id (foreign key)
        // This allows us to match with $post->id (primary key)
        $demoCandidates = DemoCandidacy::where('election_id', $election->id)
            ->orderBy('position_order')
            ->get();
        
        // Group by post_id (foreign key that stores the DemoPost.id)
        $groupedCandidates = $demoCandidates->groupBy('post_id');

        // National posts
        $national_posts = DemoPost::where('election_id', $election->id)
            ->where('is_national_wide', 1)
            ->orderBy('position_order')
            ->get()
            ->map(function ($post) use ($groupedCandidates) {
                // ✅ Use $post->id (integer PK) to match the grouped post_id
                $candidatesForPost = $groupedCandidates->get($post->id, collect());

                return [
                    'id' => $post->id, // Add the integer ID for reference
                    'post_id' => $post->post_id, // Keep string identifier for display
                    'name' => $post->name,
                    'nepali_name' => $post->nepali_name,
                    'required_number' => $post->required_number,
                    'candidates' => $candidatesForPost->map(function ($c) {
                        return [
                            'id' => $c->id,
                            'candidacy_id' => $c->candidacy_id,
                            'user_id' => $c->user_id,
                            'user_name' => $c->user_name ?? 'Demo Candidate',
                            'post_id' => $c->post_id,
                            'image_path_1' => $c->image_path_1,
                            'candidacy_name' => $c->candidacy_name,
                            'proposer_name' => $c->proposer_name,
                            'supporter_name' => $c->supporter_name,
                            'position_order' => $c->position_order,
                        ];
                    })->values(),
                ];
            })->values();

        // Regional posts
        $regional_posts = collect();
        if (!empty($auth_user->region)) {
            $regional_posts = DemoPost::where('election_id', $election->id)
                ->where('is_national_wide', 0)
                ->where('state_name', trim($auth_user->region))
                ->orderBy('position_order')
                ->get()
                ->map(function ($post) use ($groupedCandidates) {
                    // ✅ Use $post->id (integer PK) to match the grouped post_id
                    $candidatesForPost = $groupedCandidates->get($post->id, collect());

                    return [
                        'id' => $post->id,
                        'post_id' => $post->post_id,
                        'name' => $post->name,
                        'nepali_name' => $post->nepali_name,
                        'required_number' => $post->required_number,
                        'candidates' => $candidatesForPost->map(function ($c) {
                            return [
                                'id' => $c->id,
                                'candidacy_id' => $c->candidacy_id,
                                'user_id' => $c->user_id,
                                'user_name' => $c->user_name ?? 'Demo Candidate',
                                'post_id' => $c->post_id,
                                'image_path_1' => $c->image_path_1,
                                'candidacy_name' => $c->candidacy_name,
                                'proposer_name' => $c->proposer_name,
                                'supporter_name' => $c->supporter_name,
                                'position_order' => $c->position_order,
                            ];
                        })->values(),
                    ];
                })->values();
        }
    } else {
        // Real elections (existing code, unchanged)
        $national_posts = QueryBuilder::for(Post::with(['candidates' => function($query) {
            $query->join('users', 'users.id', '=', 'candidacies.user_id')
                  ->select('candidacies.*', 'users.name as user_name');
        }]))
        ->where('is_national_wide', 1)
        ->orderBy('post_id')
        ->get()
        ->map(function ($post) {
            return [
                'id' => $post->id,
                'post_id' => $post->post_id,
                'name' => $post->name,
                'nepali_name' => $post->nepali_name,
                'required_number' => $post->required_number,
                'candidates' => $post->candidates->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'candidacy_id' => $c->candidacy_id,
                        'user_id' => $c->user_id,
                        'user_name' => $c->user_name,
                        'post_id' => $c->post_id,
                        'image_path_1' => $c->image_path_1,
                        'candidacy_name' => $c->candidacy_name,
                        'proposer_name' => $c->proposer_name,
                        'supporter_name' => $c->supporter_name,
                        'position_order' => $c->position_order,
                    ];
                })->values(),
            ];
        })->values();

        $regional_posts = collect();
        if (!empty($auth_user->region)) {
            $regional_posts = QueryBuilder::for(Post::with(['candidates' => function($query) {
                $query->join('users', 'users.id', '=', 'candidacies.user_id')
                      ->select('candidacies.*', 'users.name as user_name');
            }]))
            ->where('is_national_wide', 0)
            ->where('state_name', trim($auth_user->region))
            ->orderBy('post_id')
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'post_id' => $post->post_id,
                    'name' => $post->name,
                    'nepali_name' => $post->nepali_name,
                    'required_number' => $post->required_number,
                    'candidates' => $post->candidates->map(function ($c) {
                        return [
                            'id' => $c->id,
                            'candidacy_id' => $c->candidacy_id,
                            'user_id' => $c->user_id,
                            'user_name' => $c->user_name,
                            'post_id' => $c->post_id,
                            'image_path_1' => $c->image_path_1,
                            'candidacy_name' => $c->candidacy_name,
                            'proposer_name' => $c->proposer_name,
                            'supporter_name' => $c->supporter_name,
                            'position_order' => $c->position_order,
                        ];
                    })->values(),
                ];
            })->values();
        }
    }

    // For API/JSON requests
    if ($request->wantsJson() || $request->expectsJson()) {
        return response()->json([
            'step' => 3,
            'current_step' => $voterSlug ? $voterSlug->current_step : 3,
            'user_name' => $auth_user->name,
            'user_id' => $auth_user->id,
            'user_region' => $auth_user->region,
            'national_posts_count' => $national_posts->count(),
            'regional_posts_count' => $regional_posts->count(),
        ]);
    }
    // dd($national_posts, $regional_posts);

    return Inertia::render('Vote/CreateVotingPage', [
        'national_posts' => $national_posts,
        'regional_posts' => $regional_posts,
        'user_name' => $auth_user->name,
        'user_id' => $auth_user->id,
        'user_region' => $auth_user->region,
        'slug' => $voterSlug ? $voterSlug->slug : null,
        'useSlugPath' => $voterSlug !== null,
        'election' => $election ? [
            'id' => $election->id,
            'name' => $election->name,
            'type' => $election->type,
            'description' => $election->description,
            'is_active' => $election->is_active,
        ] : null,
    ]);
}

    /**
 * Handles the very first submission of the vote (after Code-1 check).
 * STEP 3-4: Collect and validate vote selections, prepare for verification
 */
public function first_submission(Request $request)
{
    // Get user and election context
    $auth_user = $this->getUser($request);
    $election = $this->getElection($request);

    \Log::info('=== FIRST_SUBMISSION START ===', [
        'url' => $request->url(),
        'method' => $request->method(),
        'user_id' => $auth_user ? $auth_user->id : null,
        'election_id' => $election->id,
        'election_type' => $election->type,
    ]);
    // Get the appropriate code model based on election type
    if ($election->type === 'demo') {
        // DEMO ELECTIONS: Get DemoCode by user_id and election_id
        $code = DemoCode::where('user_id', $auth_user->id)
            ->where('election_id', $election->id)
            ->first();

        \Log::info('📋 Fetching DemoCode for demo election', [
            'user_id' => $auth_user->id,
            'election_id' => $election->id,
            'code_found' => $code !== null,
            'code_id' => $code ? $code->id : null
        ]);
    } else {
        // REAL ELECTIONS: Get Code through relationship
        $code = $auth_user->code;

        \Log::info('📋 Fetching Code for real election', [
            'user_id' => $auth_user->id,
            'code_found' => $code !== null,
            'code_id' => $code ? $code->id : null
        ]);
    }

    // ⛔ REAL ELECTIONS: Block voting if already voted
    if ($election->type === 'real' && $code && $code->has_voted) {
        \Log::warning('⛔ Real election - blocking vote submission for voter who already voted', [
            'user_id' => $auth_user->id,
            'election_id' => $election->id,
            'code_id' => $code->id,
        ]);

        // Always redirect to regular dashboard (slug is only for voting path)
        return redirect()->route('dashboard')
            ->withErrors(['vote' => 'You have already voted in this election. Each voter can only vote once.']);
    }

    // Get the code model and set as submitted
    $code->vote_submitted    = 1;
    $code->vote_submitted_at = \Carbon\Carbon::now();
    $code->save(); // ✅ FIXED: Save the vote_submitted state immediately
    
    // Pre-checks (time, code usability, etc.)
    $pre_check_route = $this->vote_pre_check($code);
    \Log::info('Pre-check result', [
        'pre_check_route' => $pre_check_route,
        'code_exists' => $code !== null,
        'can_vote_now' => $code ? $code->can_vote_now : 'N/A',
        'has_voted' => $code ? $code->has_voted : 'N/A',
        'has_code1_sent' => $code ? $code->has_code1_sent : 'N/A',
        'is_code1_usable' => $code ? $code->is_code1_usable : 'N/A'
    ]);

    if ($pre_check_route && $pre_check_route != "") {
        \Log::info('Pre-check failed, redirecting', ['route' => $pre_check_route]);
        if ($pre_check_route == '404') {
            abort(404);
        }

        // Convert legacy route names to slug-aware routes
        // For demo elections, use demo-code routes!
        $voterSlug = $request->attributes->get('voter_slug');
        if ($pre_check_route === 'code.create') {
            $pre_check_route = $voterSlug ? 'slug.demo-code.create' : 'demo-code.create';
            $routeParams = $voterSlug ? ['vslug' => $voterSlug->slug] : [];
            return redirect()->route($pre_check_route, $routeParams);
        }

        return redirect()->route($pre_check_route);
    }

    // Store the vote data in session for verification page
    $session_name = $code->session_name ?: 'vote_data_' . $auth_user->id;
    $code->session_name = $session_name;
    $code->save();

    // Get and validate vote data
    $vote_data = $request->only([
        'user_id',
        'national_selected_candidates',
        'regional_selected_candidates',
        'no_vote_posts',
        'agree_button'
    ]);

    // 🐛 BUG FIX: Sanitize vote data before validation to fix inconsistent no_vote flags
    $vote_data = $this->sanitize_vote_data($vote_data);
    // Validate candidate selections with SELECT_ALL_REQUIRED logic
    $validation_errors = $this->validate_candidate_selections($vote_data);

    if (!empty($validation_errors)) {
        \Log::warning('Vote selection validation failed in first_submission', [
            'user_id' => $auth_user->id,
            'errors' => $validation_errors
        ]);

        // Get voterSlug for proper redirect to DEMO routes (not real voting routes!)
        $voterSlug = $request->attributes->get('voter_slug');
        $redirectRoute = $voterSlug ? 'slug.demo-vote.create' : 'demo-vote.create';
        $routeParams = $voterSlug ? ['vslug' => $voterSlug->slug] : [];

        return redirect()->route($redirectRoute, $routeParams)
            ->withErrors($validation_errors)
            ->withInput();
    }

    $request->session()->put($session_name, $vote_data);
    \Log::info('Stored vote data in session', [
        'session_name' => $session_name,
        'user_id' => $auth_user->id,
        'data_keys' => array_keys($vote_data)
    ]);

    // No need to send second verification code - reuse the first code to reduce emails
    \Log::info('Using first verification code for second verification', [
        'user_id' => $auth_user->id,
        'code1_available' => !empty($code->code1)
    ]);

    // ✅ NEW: Record step 3 completion (vote submitted)
    $voterSlug = $request->attributes->get('voter_slug');
    if ($voterSlug) {
        \Log::info('🔵 [FIRST_SUBMISSION] About to record step 3', [
            'voter_slug_id' => $voterSlug->id,
            'election_id' => $election->id,
        ]);

        try {
            $stepTrackingService = new \App\Services\VoterStepTrackingService();
            $stepTrackingService->completeStep(
                $voterSlug,
                $election,
                3, // Step 3: Vote submission
                ['vote_submitted' => true, 'submitted_at' => now()->toIso8601String()]
            );
            \Log::info('✅ Step 3 recorded in voter_slug_steps', [
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $election->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('❌ [FIRST_SUBMISSION] Failed to record step 3', [
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $election->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        // Legacy: Advance slug step (use demo-vote.create for demo elections!)
        $progressService = new \App\Services\VoterProgressService();
        $progressService->advanceFrom($voterSlug, 'slug.demo-vote.create', ['vote_submitted' => true]);
        \Log::info('Advanced slug step from 3 to 4', ['current_step' => $voterSlug->fresh()->current_step]);
    }

    // Run verify_first_submission; this now always returns a RedirectResponse
    \Log::info('About to call verify_first_submission', [
        'election_id' => $election->id,
        'election_type' => $election->type
    ]);
    $verify_result = $this->verify_first_submission($request, $code, $auth_user, $election);

    \Log::info('verify_first_submission returned', [
        'type' => get_class($verify_result),
        'target_url' => method_exists($verify_result, 'getTargetUrl') ? $verify_result->getTargetUrl() : 'N/A'
    ]);

    // verify_first_submission always returns a RedirectResponse (either for errors or success)
    return $verify_result;
}



/**
 * STEP 6: Handle the submission of candidate selections
 * Process vote data, validate selections, send second verification code,
 * and redirect to verification page
 */
public function second_submission(Request $request)
{
    DB::beginTransaction();

    try {
        $auth_user = auth()->user();
        $election = $this->getElection($request);



        // Basic authentication check
        if (!$auth_user) {
            Log::error('Second submission attempted without authentication');

            return redirect()->route('dashboard')
                ->withErrors(['auth' => 'Authentication required. Please log in again.']);
        }

        // ✅ FIXED: Fetch correct code model based on election type
        if ($election->type === 'demo') {
            $code = DemoCode::where('user_id', $auth_user->id)
                ->where('election_id', $election->id)
                ->first();
        } else {
            $code = $auth_user->code;
        }
        
        // Check if user has a code record
        if (!$code) {
            Log::error('Second submission attempted without code record', ['user_id' => $auth_user->id]);
            $route = $voterSlug ? 'slug.code.create' : 'code.create';
            $routeParams = $voterSlug ? ['vslug' => $voterSlug->slug] : [];

            return redirect()->route($route, $routeParams)
                ->withErrors(['code' => 'Voting code not found. Please start the voting process again.']);
        }
        // Pre-submission timing and eligibility checks
        $pre_check_result = $this->vote_pre_check($code);
        if ($pre_check_result && $pre_check_result !== "") {
            Log::warning('Pre-check failed during second submission', [
                'user_id' => $auth_user->id,
                'redirect_to' => $pre_check_result
            ]);
            
            if ($pre_check_result === '404') {
                abort(404, 'Voting session expired or invalid');
            }
            return redirect()->route($pre_check_result);
        }

        // Validate request structure
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'national_selected_candidates' => 'nullable|array',
            'regional_selected_candidates' => 'nullable|array', 
            'agree_button' => 'required|boolean|accepted',
        ]);

        if ($validator->fails()) {
            Log::warning('Second submission validation failed', [
                'user_id' => $auth_user->id,
                'errors' => $validator->errors()->toArray()
            ]);
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify user ID matches authenticated user
        $submitted_user_id = $request->input('user_id');
        if ((int)$submitted_user_id !== (int)$auth_user->id) {
            Log::error('User ID mismatch in second submission', [
                'auth_user_id' => $auth_user->id,
                'submitted_user_id' => $submitted_user_id
            ]);
            
            return redirect()->back()
                ->withErrors(['user_id' => 'User verification failed. Please try again.'])
                ->withInput();
        }

        // Enhanced eligibility verification
        $eligibility_errors = $this->validateVotingEligibility($auth_user, $code);
        if (!empty($eligibility_errors)) {
            return redirect()->route('dashboard')
                ->withErrors($eligibility_errors);
        }

        // Get and validate vote data
        $vote_data = $request->all();

        // 🐛 BUG FIX: Sanitize vote data before validation to fix inconsistent no_vote flags
        $vote_data = $this->sanitize_vote_data($vote_data);

        $validation_errors = $this->validate_candidate_selections($vote_data);
        
        if (!empty($validation_errors)) {
            Log::warning('Vote selection validation failed', [
                'user_id' => $auth_user->id,
                'errors' => $validation_errors
            ]);
            
            return redirect()->back()
                ->withErrors($validation_errors)
                ->withInput();
        }

        // Additional vote integrity checks
        $integrity_errors = $this->validateVoteIntegrity($vote_data, $auth_user);
        if (!empty($integrity_errors)) {
            Log::error('Vote integrity validation failed', [
                'user_id' => $auth_user->id,
                'errors' => $integrity_errors
            ]);
            
            return redirect()->back()
                ->withErrors($integrity_errors)
                ->withInput();
        }

        // Update submission status
        $code->vote_submitted = 1;
        $code->vote_submitted_at = Carbon::now();
         $code->session_name = 'vote_' . $code->id."_". auth()->id();

        $code->save();

        // Send second verification code with error handling
        // $code_result = $this->send_second_voting_code($code, $auth_user);
        
        // if (isset($code_result['error'])) {
        //     Log::error('Failed to send second verification code', [
        //         'user_id' => $auth_user->id,
        //         'error' => $code_result['error']
        //     ]);
            
        //     return redirect()->back()
        //         ->withErrors(['code' => 'Failed to send verification code. Please try again.'])
        //         ->withInput();
        // }

        $totalDuration = $code_result['duration'] ?? 0;

        // Prepare comprehensive session data
        $session_data = $this->prepareSessionData($vote_data, $auth_user, $totalDuration, $code);
        
        // Store in session with error handling
        try {
              
            $session_name =$code->session_name;

            $request->session()->put($session_name, $session_data);
            
            
            // Verify session storage
            $stored_data = $request->session()->get($session_name);
            if (!$stored_data) {
                throw new \Exception('Session storage verification failed');
            }
            $code->session_name=$session_name;
            
        } catch (\Exception $e) {
            Log::error('Session storage failed during vote submission', [
                'user_id' => $auth_user->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->withErrors(['session' => 'Failed to store vote data. Please try again.'])
                ->withInput();
        }

        // Log successful submission with detailed info
        Log::info('Vote second submission completed successfully', [
            'user_id' => $auth_user->id,
            'user_name' => $auth_user->name,
            'total_duration' => $totalDuration,
            'national_posts_count' => count($vote_data['national_selected_candidates'] ?? []),
            'regional_posts_count' => count($vote_data['regional_selected_candidates'] ?? []),
            'session_id' => $request->session()->getId(),
            'timestamp' => Carbon::now()->toISOString()
        ]);

        DB::commit();

        // Redirect to verification with success message
        $route = $voterSlug ? 'slug.vote.verify' : 'vote.verify';
        $routeParams = $voterSlug ? ['vslug' => $voterSlug->slug] : [];

        return redirect()->route($route, $routeParams)
            ->with([
                'totalDuration' => $totalDuration,
                'code_expires_in' => $code->voting_time_in_minutes ?? config('voting.time_in_minutes', 30),
                'success' => 'Vote submitted successfully. Please check your email for verification code.'
            ]);

    } catch (\Throwable $e) {
        DB::rollBack();
        
        // Comprehensive error logging
        Log::error('Second submission failed with exception', [
            'user_id' => auth()->id(),
            'error_message' => $e->getMessage(),
            'error_file' => $e->getFile(),
            'error_line' => $e->getLine(),
            'stack_trace' => $e->getTraceAsString(),
            'request_data' => $request->except(['_token'])
        ]);

        return redirect()->back()
            ->withErrors(['submission' => 'An error occurred while processing your vote. Please try again.'])
            ->withInput();
    }
}

/**
 * Enhanced voting eligibility validation
 */
private function validateVotingEligibility($auth_user, $code)
{
    $errors = [];
    
    if (!$code->can_vote_now) {
        $errors['eligibility'] = 'Voting is not currently available for you.';
    }
    
    if ($code->has_voted) {
        $errors['already_voted'] = 'You have already completed your vote.';
    }
    
    if (!$code->is_code1_usable && $code->vote_submitted) {
        // Check if we're in the valid submission window
        $submission_window = $code->voting_time_in_minutes ?? config('voting.time_in_minutes', 30);
        $elapsed = \Carbon\Carbon::parse($code->code1_used_at)->diffInMinutes(now());
        
        if ($elapsed > $submission_window) {
            $errors['expired'] = 'Your voting session has expired. Please start again.';
        }
    }
    
    return $errors;
}

/**
 * Validate vote data integrity against available posts and candidates
 */
private function validateVoteIntegrity($vote_data, $auth_user)
{
    $errors = [];
    
    try {
        // Get available posts for verification
        $available_national_posts = Post::where('is_national_wide', 1)->pluck('post_id')->toArray();
        $available_regional_posts = Post::where('is_national_wide', 0)
            ->where('state_name', trim($auth_user->region))
            ->pluck('post_id')->toArray();
        
        // Validate national selections
        foreach ($vote_data['national_selected_candidates'] ?? [] as $index => $selection) {
            if ($selection && !$selection['no_vote']) {
                $post_id = $selection['post_id'] ?? null;
                
                if (!in_array($post_id, $available_national_posts)) {
                    $errors["national_integrity_{$index}"] = "Invalid national post selection detected.";
                    continue;
                }
                
                // Validate candidates exist for this post
                foreach ($selection['candidates'] ?? [] as $candidate) {
                    $candidacy_exists = Candidacy::where('candidacy_id', $candidate['candidacy_id'])
                        ->where('post_id', $post_id)
                        ->exists();
                    
                    if (!$candidacy_exists) {
                        $errors["national_candidate_{$index}"] = "Invalid candidate selection detected.";
                    }
                }
            }
        }
        
        // Validate regional selections
        foreach ($vote_data['regional_selected_candidates'] ?? [] as $index => $selection) {
            if ($selection && !$selection['no_vote']) {
                $post_id = $selection['post_id'] ?? null;
                
                if (!in_array($post_id, $available_regional_posts)) {
                    $errors["regional_integrity_{$index}"] = "Invalid regional post selection detected.";
                    continue;
                }
                
                // Validate candidates exist for this post
                foreach ($selection['candidates'] ?? [] as $candidate) {
                    $candidacy_exists = Candidacy::where('candidacy_id', $candidate['candidacy_id'])
                        ->where('post_id', $post_id)
                        ->exists();
                    
                    if (!$candidacy_exists) {
                        $errors["regional_candidate_{$index}"] = "Invalid candidate selection detected.";
                    }
                }
            }
        }
        
    } catch (\Exception $e) {
        Log::error('Vote integrity validation error', [
            'user_id' => $auth_user->id,
            'error' => $e->getMessage()
        ]);
        $errors['integrity'] = 'Unable to validate vote integrity. Please try again.';
    }
    
    return $errors;
}

/**
 * Prepare comprehensive session data
 */
private function prepareSessionData($vote_data, $auth_user, $totalDuration, $code)
{
    $code_expires_in = $code->voting_time_in_minutes ?? config('voting.time_in_minutes', 30);
    
    return [
        'user_id' => $auth_user->id,
        'user_name' => $auth_user->name,
        'user_region' => $auth_user->region,
        'national_selected_candidates' => $vote_data['national_selected_candidates'] ?? [],
        'regional_selected_candidates' => $vote_data['regional_selected_candidates'] ?? [],
        'agree_button' => $vote_data['agree_button'] ?? false,
        'totalDuration' => $totalDuration,
        'code_expires_in' => $code_expires_in,
        'submission_timestamp' => Carbon::now()->toISOString(),
        'session_id' => session()->getId(),
        'vote_integrity_hash' => $this->generateVoteHash($vote_data),
    ];
}

/**
 * Generate hash for vote integrity verification
 */
private function generateVoteHash($vote_data)
{
    $hash_data = [
        'national' => $vote_data['national_selected_candidates'] ?? [],
        'regional' => $vote_data['regional_selected_candidates'] ?? [],
        'timestamp' => Carbon::now()->timestamp,
    ];
    
    return hash('sha256', serialize($hash_data));
}

/**
 * Enhanced second code sending with better error handling
 */
public function send_second_voting_code(&$code, $auth_user)
{
    try {
        $code1_used_at = Carbon::parse($code->code1_used_at);
        $current = Carbon::now();
        $totalDuration = $code1_used_at->diffInMinutes($current);
        
        // Check if we're within the valid voting window
        $voting_window = $code->voting_time_in_minutes ?? config('voting.time_in_minutes', 30);
        if ($totalDuration >= $voting_window) {
            return [
                'error' => 'Voting window expired',
                'duration' => $totalDuration
            ];
        }
        
        // Check if we need to send a new code
        if (!$code->has_code2_sent || !$code->is_code2_usable) {
            $voting_code = get_random_string(6);
            $code->code2 = Hash::make($voting_code);
            $code->has_code2_sent = 1;
            $code->is_code1_usable = 0; 
            $code->is_code2_usable = 1;
            $code->code2_sent_at = Carbon::now();
            $code->save();
            
            // Send notification with error handling
            try {
                $auth_user->notify(new SecondVerificationCode($auth_user, $voting_code));
                
                Log::info('Second verification code sent successfully', [
                    'user_id' => $auth_user->id,
                    'duration' => $totalDuration
                ]);
                
            } catch (\Exception $e) {
                Log::error('Failed to send second verification code notification', [
                    'user_id' => $auth_user->id,
                    'error' => $e->getMessage()
                ]);
                
                return [
                    'error' => 'Failed to send verification code email',
                    'duration' => $totalDuration
                ];
            }
        }
        
        return [
            'success' => true,
            'duration' => $totalDuration
        ];
        
    } catch (\Exception $e) {
        Log::error('Exception in send_second_voting_code', [
            'user_id' => $auth_user->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return [
            'error' => 'Code generation failed',
            'duration' => 0
        ];
    }
}


/**
 * Simplified validation for candidate selections
 * Focuses on actual data structure rather than complex flow checks
 *
 * @param array $vote_data
 * @return array
 */
/**
 * Sanitize vote data to fix inconsistent no_vote flags
 * 🐛 BUG FIX: Prevent {no_vote: false, candidates: []} from being saved
 *
 * @param array $vote_data
 * @return array
 */
private function sanitize_vote_data($vote_data)
{
    // Sanitize national selections
    if (isset($vote_data['national_selected_candidates']) && is_array($vote_data['national_selected_candidates'])) {
        foreach ($vote_data['national_selected_candidates'] as $index => $selection) {
            if ($selection) {
                $vote_data['national_selected_candidates'][$index] = $this->sanitize_selection($selection);
            }
        }
    }

    // Sanitize regional selections
    if (isset($vote_data['regional_selected_candidates']) && is_array($vote_data['regional_selected_candidates'])) {
        foreach ($vote_data['regional_selected_candidates'] as $index => $selection) {
            if ($selection) {
                $vote_data['regional_selected_candidates'][$index] = $this->sanitize_selection($selection);
            }
        }
    }

    return $vote_data;
}

/**
 * Sanitize individual selection to ensure data consistency
 *
 * @param array $selection
 * @return array
 */
private function sanitize_selection($selection)
{
    // Check for the bug pattern: no_vote=false with empty candidates
    $no_vote = $selection['no_vote'] ?? false;
    $candidates = $selection['candidates'] ?? [];
    $candidate_count = is_array($candidates) ? count($candidates) : 0;

    // 🐛 BUG FIX: If no_vote is false but candidates array is empty,
    // this is inconsistent data - treat it as no_vote = true
    if ($no_vote === false && $candidate_count === 0) {
        \Log::warning('Data inconsistency detected and fixed', [
            'post_id' => $selection['post_id'] ?? 'unknown',
            'post_name' => $selection['post_name'] ?? 'unknown',
            'issue' => 'no_vote=false with empty candidates',
            'action' => 'Changed no_vote to true'
        ]);

        $selection['no_vote'] = true;
    }

    return $selection;
}

private function validate_candidate_selections($vote_data)
{
    $errors = [];
    $isSelectAllRequired = config('app.select_all_required', 'no') === 'yes';

    // Get selections
    $national_selections = $vote_data['national_selected_candidates'] ?? [];
    $regional_selections = $vote_data['regional_selected_candidates'] ?? [];

    // Check if user made any selections at all
    $has_any_selection = false;

    // Check national selections
    foreach ($national_selections as $index => $selection) {
        if ($selection) {
            if (isset($selection['no_vote']) && $selection['no_vote']) {
                $has_any_selection = true;
            } elseif (isset($selection['candidates']) && is_array($selection['candidates']) && count($selection['candidates']) > 0) {
                $has_any_selection = true;

                $required_count = $selection['required_number'] ?? 1;
                $candidate_count = count($selection['candidates']);
                $post_name = $selection['post_name'] ?? "Post #" . ($index + 1);

                if ($isSelectAllRequired) {
                    // Must select exactly required_number candidates
                    if ($candidate_count !== $required_count) {
                        $errors["national_post_{$index}"] = "You must select exactly {$required_count} candidate(s) for {$post_name}.";
                    }
                } else {
                    // Current behavior: validate max selections
                    if ($candidate_count > $required_count) {
                        $errors["national_post_{$index}"] = "Too many candidates selected for {$post_name}. Maximum: {$required_count}";
                    }
                }
            } else {
                // 🐛 BUG FIX: Detect inconsistent data (no_vote=false with no candidates)
                $no_vote = $selection['no_vote'] ?? false;
                $candidates_count = isset($selection['candidates']) && is_array($selection['candidates']) ? count($selection['candidates']) : 0;

                if ($no_vote === false && $candidates_count === 0) {
                    $post_name = $selection['post_name'] ?? "Post #" . ($index + 1);
                    $errors["national_post_{$index}"] = "Invalid selection for {$post_name}. Please select candidates or choose to skip.";
                }
            }
        }
    }

    // Check regional selections
    foreach ($regional_selections as $index => $selection) {
        if ($selection) {
            if (isset($selection['no_vote']) && $selection['no_vote']) {
                $has_any_selection = true;
            } elseif (isset($selection['candidates']) && is_array($selection['candidates']) && count($selection['candidates']) > 0) {
                $has_any_selection = true;

                $required_count = $selection['required_number'] ?? 1;
                $candidate_count = count($selection['candidates']);
                $post_name = $selection['post_name'] ?? "Post #" . ($index + 1);

                if ($isSelectAllRequired) {
                    // Must select exactly required_number candidates
                    if ($candidate_count !== $required_count) {
                        $errors["regional_post_{$index}"] = "You must select exactly {$required_count} candidate(s) for {$post_name}.";
                    }
                } else {
                    // Current behavior: validate max selections
                    if ($candidate_count > $required_count) {
                        $errors["regional_post_{$index}"] = "Too many candidates selected for {$post_name}. Maximum: {$required_count}";
                    }
                }
            } else {
                // 🐛 BUG FIX: Detect inconsistent data (no_vote=false with no candidates)
                $no_vote = $selection['no_vote'] ?? false;
                $candidates_count = isset($selection['candidates']) && is_array($selection['candidates']) ? count($selection['candidates']) : 0;

                if ($no_vote === false && $candidates_count === 0) {
                    $post_name = $selection['post_name'] ?? "Post #" . ($index + 1);
                    $errors["regional_post_{$index}"] = "Invalid selection for {$post_name}. Please select candidates or choose to skip.";
                }
            }
        }
    }

    // Ensure user made at least one selection or no-vote choice
    if (!$has_any_selection) {
        $errors['no_selections'] = 'Please make at least one selection or choose "Skip" for the positions you wish to abstain from.';
    }

    return $errors;
}
/**
 * Validate vote selections to ensure proper choices are made
 *
 * @param array $vote_data
 * @return array
 */
private function validate_vote_selections($vote_data)
{
    $errors = [];

    // Get selections
    $national_selections = $vote_data['national_selected_candidates'] ?? [];
    $regional_selections = $vote_data['regional_selected_candidates'] ?? [];

    // Validate that user has made at least some choices
    $has_national_choices = $this->has_valid_selections($national_selections);
    $has_regional_choices = $this->has_valid_selections($regional_selections);

    if (!$has_national_choices && !$has_regional_choices) {
        $errors['no_selections'] = 'Please make at least one selection or choose "Skip" for the positions you wish to abstain from.';
    }

    // Validate individual selections
    foreach ($national_selections as $index => $selection) {
        if ($selection && !$selection['no_vote']) {
            $candidate_count = count($selection['candidates'] ?? []);
            $required_count = $selection['required_number'] ?? 1;
            
            if ($candidate_count > $required_count) {
                $errors["national_post_{$index}"] = "Too many candidates selected for {$selection['post_name']}. Maximum allowed: {$required_count}";
            }
        }
    }

    foreach ($regional_selections as $index => $selection) {
        if ($selection && !$selection['no_vote']) {
            $candidate_count = count($selection['candidates'] ?? []);
            $required_count = $selection['required_number'] ?? 1;
            
            if ($candidate_count > $required_count) {
                $errors["regional_post_{$index}"] = "Too many candidates selected for {$selection['post_name']}. Maximum allowed: {$required_count}";
            }
        }
    }

    return $errors;
}

/**
 * Check if selections array has any valid choices (either candidates or no_vote)
 *
 * @param array $selections
 * @return bool
 */
private function has_valid_selections($selections)
{
    foreach ($selections as $selection) {
        if ($selection) {
            // Valid if either no_vote is true OR candidates array has items
            if ($selection['no_vote'] || (!empty($selection['candidates']) && count($selection['candidates']) > 0)) {
                return true;
            }
        }
    }
    return false;
} 
 
    ////////////////////////////////////////////////// 
    
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * STEP 5: Final vote submission
     * Verify second code and permanently store the vote in appropriate table
     * Uses VotingServiceFactory to handle demo vs real elections
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
     try {

        // Get user and election context
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);

        // Set organisation context for tenant scoping
        session(['current_organisation_id' => $election->organisation_id]);

        // PHASE 3 VALIDATION: Election Validation
        // Demo elections: No organisation validation (can be voted by anyone)
        // Real elections: Require organisation matching
        if ($election->type === 'real') {
            // REAL ELECTION: Validate organisation matching
            if ($auth_user->organisation_id !== $election->organisation_id) {
                DB::rollBack();
                \Log::channel('voting_security')->error('Organisation mismatch in vote submission', [
                    'user_organisation_id' => $auth_user->organisation_id,
                    'election_organisation_id' => $election->organisation_id,
                    'election_id' => $election->id,
                    'user_id' => $auth_user->id,
                    'reason' => 'organisation_mismatch',
                    'timestamp' => now(),
                    'ip' => request()->ip(),
                ]);

                return redirect()->route('dashboard')->withErrors([
                    'vote' => __('You do not have permission to vote in this election.')
                ]);
            }
        }
        // Demo elections bypass organisation validation (backward compatibility)

        // PHASE 3 VALIDATION: Log successful validation
        \Log::channel('voting_audit')->info('Vote submission validated at controller level', [
            'election_id' => $election->id,
            'organisation_id' => $auth_user->organisation_id,
            'user_id' => $auth_user->id,
            'timestamp' => now(),
            'ip' => request()->ip(),
        ]);

        Log::info('Vote final submission started', [
            'user_id' => $auth_user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
        ]);

        if (!$auth_user) {
            DB::rollBack();
            \Log::error('No authenticated user found in vote store');
            return back()->withErrors(['error' => 'Authentication required.'])->withInput();
        }

        // ✅ FIXED: Fetch correct code model based on election type
        if ($election->type === 'demo') {
            $code = DemoCode::where('user_id', $auth_user->id)
                ->where('election_id', $election->id)
                ->first();
        } else {
            $code = $auth_user->code;
        }

        // ⛔ REAL ELECTIONS: Block final vote submission if already voted
        if ($election->type === 'real' && $code->has_voted) {
            DB::rollBack();
            \Log::warning('⛔ Real election - blocking final vote submission for voter who already voted', [
                'user_id' => $auth_user->id,
                'election_id' => $election->id,
                'code_id' => $code->id,
            ]);

            return redirect()->route('dashboard')
                ->withErrors(['vote' => 'You have already voted in this election. Each voter can only vote once.']);
        }
        $voting_session_name =Hash::make($code->code2);

         // IP validation - single line replacement
            $ipValidation = validateVotingIpWithResponse();
            if ($ipValidation instanceof \Inertia\Response) {
                return $ipValidation; // Returns the denial response
            }


        //everything take from Code Model
        $this->has_voted    =$code->has_voted;
        // $this->in_code      =$code->code_for_vote;
        $this->in_code       =$code->code1;
        $this->out_code     = $request['voting_code'];
        $voting_code        = trim($request->input('voting_code'));
        /********************************************************** */
        //verify second  submission code:
             // Validate request input
            $request->validate([
                'voting_code' => 'required|string|min:6|max:6'
            ], [
                'voting_code.required' => 'Please enter the verification code.',
                'voting_code.min' => 'Code must be exactly 6 characters.',
                'voting_code.max' => 'Code must be exactly 6 characters.',
            ]);
            

            $_codeVerified =$this->verify_submitted_code($this->in_code, $this->out_code);
            // Use the verification method
            if (!$_codeVerified) {
            
                \Log::warning('Code verification failed - returning with error',
                [
                'user_id' => $auth_user->id,
                'user_identifier' => $auth_user->user_id ?? null,
                'submitted_code_length' => strlen($request['voting_code'] ?? ''),
                'failed_at' => now()
                ]);
            
            // Return back with your specified error message
            return back()->withErrors([
                'voting_code' => 'Submitted code is false. Please check your email and try again.'
            ])->withInput();
        }
        /*********************************************************** */
        $this->user_id      =$code->user_id;
        // Use the existing session_name from the code (set during first_submission)
        // Don't overwrite it, just use what's already there
        $session_name       =$code->session_name;
        //get deligatevote from session
        $vote_data = $request->session()->get($session_name);
        // check the  voting codes 
        // dd($vote_data["national_selected_candidates"]);
        // 1. Validate pre-conditions
        // dd($vote_data);
        $pre_check = $this->vote_post_check($auth_user, $code, $vote_data);

        /**
             *Here Everything is checked . you save the deligatevote.
             * One can't come here easly
             * He must be authnicated user ;
             * the code must be true
             * He has not voted before
             */
            //get deligatevote from session
            // 6. Generate and store verification key



 
             // 6. Save the vote WITHOUT user information
            // $vote = $this->saveAnonymizedVote($prviate_key, $vote_data);
            // 7. Save the vote and related data
            // $vote = $this->saveVoteTransactionally($auth_user, $private_key, $vote_data);

            // Generate a secure random key component
            //$random_key = bin2hex(random_bytes(16)); // 32-character random string
            // $private_key = $this->generateAndStoreVerificationKey($code);
            $private_key = bin2hex(random_bytes(16)); // 32-character random string
            $hashed_key = password_hash ($private_key,PASSWORD_BCRYPT);
             //dd(password_verify($private_key, $hashed_key));         
             //dd($hashed_key);

            #
            Log::info('private_key', [
                'user_id' => $private_key
            ]);
             $vote_hashed_key =$hashed_key;

            // Save vote using election-aware factory service
            $this->save_vote($vote_data, $vote_hashed_key, $election, $auth_user, $private_key);

            // After save_vote(), $this->out_code contains the vote_id
            // Concatenate private_key with vote_id for uniqueness
            // This is sent to email and user will submit when verifying
            $verification_code_for_email = $private_key . '_' . $this->out_code;

            // Record Step 5: Final vote submission
            $voterSlug = $request->attributes->get('voter_slug');
            try {
                $stepTrackingService = new \App\Services\VoterStepTrackingService();
                $stepTrackingService->completeStep(
                    $voterSlug, $election, 5,
                    ['vote_submitted_final' => true, 'submitted_final_at' => now()->toIso8601String(), 'vote_id' => $this->out_code]
                );
                Log::info('Step 5 recorded: Final vote submission', [
                    'voter_slug_id' => $voterSlug->id,
                    'election_id' => $election->id,
                    'vote_id' => $this->out_code,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to record Step 5: Final vote submission', [
                    'voter_slug_id' => $voterSlug->id ?? 'unknown',
                    'election_id' => $election->id ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);
            }

            // 8. Mark user as voted and update code status
                $this->markUserAsVoted($code, $hashed_key);

                // 9. Send verification notification (only if user has valid email)
                if ($auth_user->email && filter_var($auth_user->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        $auth_user->notify(new SendVoteSavingCode($verification_code_for_email));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send vote saving code email', [
                            'user_id' => $auth_user->id,
                            'email' => $auth_user->email,
                            'error' => $e->getMessage(),
                        ]);
                        // Don't fail the vote submission if email fails
                    }
                } else {
                    \Log::warning('User does not have valid email for vote saving code', [
                        'user_id' => $auth_user->id,
                        'email' => $auth_user->email ?? 'null',
                    ]);
                }

                DB::commit();
        // Advance slug step after successful vote submission
        $vslug = $request->route('vslug');
        if ($vslug instanceof \App\Models\VoterSlug && $vslug->current_step < 5) {
            $vslug->current_step = 5; // Advance to completion step
            $vslug->save();

            \Log::info('Advanced VoterSlug step after vote verification', [
                'slug' => $vslug->slug,
                'new_step' => $vslug->current_step,
                'user_id' => $auth_user->id
            ]);
        }

        // For demo elections, redirect to demo-specific verify_to_show page
        if ($election->type === 'demo') {
            // Get the demo vote we just saved
            $demoVote = \App\Models\DemoVote::find($this->out_code);

            \Log::info('🎯 DEMO VOTE SUBMISSION - Redirect decision', [
                'has_voter_slug' => $voterSlug ? 'yes' : 'no',
                'slug' => $voterSlug ? $voterSlug->slug : null,
                'vote_id' => $this->out_code,
                'has_demo_vote' => $demoVote ? 'yes' : 'no',
                'demo_vote_voting_code' => $demoVote ? $demoVote->voting_code : null,
                'route_choice' => $voterSlug ? 'slug.demo-vote.verify_to_show' : 'demo-vote.verify_to_show'
            ]);

            if ($voterSlug) {
                // Use slug-based route
                \Log::info('✅ SUCCESS - Redirecting to slug.demo-vote.verify_to_show', [
                    'vslug' => $voterSlug->slug,
                    'url' => route('slug.demo-vote.verify_to_show', ['vslug' => $voterSlug->slug])
                ]);
                return redirect()->route('slug.demo-vote.verify_to_show', ['vslug' => $voterSlug->slug])
                    ->with([
                        'success' => 'Your demo vote has been successfully submitted!',
                        'verification_code' => $demoVote && $demoVote->voting_code ? $demoVote->voting_code : null,
                        'is_demo' => true,
                        'demo_vote_id' => $demoVote ? $demoVote->id : null
                    ]);
            } else {
                // Use non-slug route
                \Log::info('✅ SUCCESS - Redirecting to demo-vote.verify_to_show', [
                    'url' => route('demo-vote.verify_to_show')
                ]);
                return redirect()->route('demo-vote.verify_to_show')
                    ->with([
                        'success' => 'Your demo vote has been successfully submitted!',
                        'verification_code' => $demoVote && $demoVote->voting_code ? $demoVote->voting_code : null,
                        'is_demo' => true,
                        'demo_vote_id' => $demoVote ? $demoVote->id : null
                    ]);
            }
        }

        // Real elections redirect to real voting verify_to_show page
        // $request->session()->forget('vote');
        \Log::info('✅ SUCCESS - Redirecting to vote.verify_to_show (real election)', [
            'url' => route('vote.verify_to_show')
        ]);
        return redirect()->route('vote.verify_to_show')->with('success', 'Your vote has been successfully submitted.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        \Log::error('❌ VALIDATION EXCEPTION in store()', [
            'user_id' => auth()->id(),
            'errors' => $e->errors()
        ]);
        return redirect()->back()->withErrors($e->errors())->withInput();

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('❌ EXCEPTION in store() - Vote submission failed', [
            'user_id' => auth()->id(),
            'error' => $e->getMessage(),
            'error_class' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return $this->handleVoteError('An error occurred while processing your vote. Please try again.');
    }



}

/**
 * Mark user as having voted and update code status
 *
 * @param Code $code
 */
 function markUserAsVoted($code, string $hashed_key )
{
    // ✅ FIXED: Configurable code state after vote submission
    // Accepts both Code and DemoCode models
    $updateData = [
        'has_voted' => true,
        'can_vote_now' => false,
        'code2_used_at' => now(),
        'vote_completed_at' => now()
    ];

    // In SIMPLE MODE: Mark Code1 as fully used (both uses completed)
    // In STRICT MODE: Mark Code2 as fully used
    if (config('voting.two_codes_system') == 1) {
        // STRICT MODE: Code2 is now exhausted
        $updateData['is_code2_usable'] = false;
    } else {
        // SIMPLE MODE: Code1 is now fully used (second use completed)
        $updateData['is_code1_usable'] = 0;
        $updateData['is_code2_usable'] = false;  // Code2 never used in simple mode
    }

    $code->update($updateData);
    $code->save();
    // dd($code);
}

/**
 * Prepare vote data for storage
 * 
 * @param array|null $selection
 * @return array|null
 */
protected function prepareVoteData(?array $selection): ?array
{
    if ($selection === null) {
        return ['candidates' => null, 'no_vote' => true];
    }

    return [
        'candidates' => $selection['candidates'] ?? [],
        'no_vote' => $selection['no_vote'] ?? false,
        'post_id' => $selection['post_id'] ?? null,
        'post_name' => $selection['post_name'] ?? null
    ];
}
/**
 * Save individual candidate results
 * 
 * @param int $vote_id
 * @param array $selection
 */
protected function saveCandidateResults(int $vote_id, array $selection)
{
    foreach ($selection['candidates'] as $candidate) {
        DemoResult::create([
            'vote_id' => $vote_id,
            'post_id' => $selection['post_id'],
            'candidate_id' => $candidate['candidacy_id']  // candidacy_id from input maps to candidate_id in results table
        ]);
    }
}

protected function handleVoteError(string $message)
{
    return redirect()->back()
        ->withErrors(['vote_error' => $message])
        ->withInput();
}

/**
 * Save the vote and related candidate selections in a transaction
 * 
 * @param User $user
 * @param string $voting_code
 * @param array $vote_data
 * @return Vote
 */
protected function saveVoteTransactionally(User $user, string $voting_code, array $vote_data): Vote
{
    $vote = new Vote();
    $vote->user_id = $user->id; // Store actual user ID
    $vote->voting_code = $voting_code;
    $vote->save();

    if (!empty($vote_data['national_selected_candidates']) || !empty($vote_data['regional_selected_candidates'])) {
        $this->saveCandidateSelections($vote, $vote_data);
    }

    return $vote;
}

/**
 * Save all candidate selections for the vote
 * 
 * @param Vote $vote
 * @param array $vote_data
 */
protected function saveCandidateSelections(Vote $vote, array $vote_data)
{
    $all_candidates = array_merge(
        $vote_data['national_selected_candidates'] ?? [],
        $vote_data['regional_selected_candidates'] ?? []
    );

    foreach ($all_candidates as $index => $selection) {
        $column_name = 'candidate_' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
        $vote_data = $this->prepareVoteData($selection);
        
        if ($vote_data) {
            $vote->$column_name = json_encode($vote_data);
            
            // Save individual candidate results if selection exists
            if (!empty($selection['candidates'])) {
                $this->saveCandidateResults($vote->id, $selection);
            }
        }
    }

    $vote->save();
}



    /**
 * Save an anonymized vote record without any user identification
 * 
 * @param string $voting_code
 * @param array $vote_data
 * @return Vote
 */
protected function saveAnonymizedVote(string $voting_code, array $vote_data): Vote
{
     // IP validation - single line replacement
    $ipValidation = validateVotingIpWithResponse();
    if ($ipValidation instanceof \Inertia\Response) {
        return $ipValidation; // Returns the denial response
    }
    
    $vote = new Vote();
    
    // Store only the voting code, no user identification
    $vote->voting_code = $voting_code;
    $vote->save();

    if (!empty($vote_data['national_selected_candidates']) || !empty($vote_data['regional_selected_candidates'])) {
        $this->saveCandidateSelections($vote, $vote_data);
    }

    return $vote;
}
/**
 * Generate and store the vote verification key with proper hashing
 * 
 * @param Code $code
 * @param int $vote_id
 * @return string Returns the unhashed private key for one-time notification
 */
 function generateAndStoreVerificationKey(Code $code): string
{

    // Generate a secure random key component
    $random_key = bin2hex(random_bytes(16)); // 32-character random string
    
    // Create the composite private key
    $private_key = $random_key; 
    // . '_' . $code->id;
    $this->out_code=$private_key;
    // Hash the private key using Laravel's secure Hash facade
    $hashed_key = Hash::make($private_key);
    
    // Store only the hashed version in the database
    $code->code_for_vote = $hashed_key;
    $code->save();
    
    // Return the unhashed version only for the one-time notification
    return $private_key;
}

/**
 * Verify the submitted voting code against the hashed version
 * 
 * @param string $submitted_code
 * @param Code $code
 * @return bool
 */
public function verifyVotingCode(string $submitted_code, Code $code): bool
{
    // Security checks before verification
    if (empty($submitted_code) || empty($code->code_for_vote)) {
        return false;
    }
    // Timing attack resistant comparison code_for_vote
    return Hash::check($submitted_code, $code->code_for_vote);
}


     public function verify_to_show(){
        //
        //   $vote =$auth_user->vote();
        $auth_user            = auth()->user();
        $code                 = $auth_user->code;
        $this->user_id        =$auth_user->id;
        $has_voted            =false;
        if($code!=null) {
            $has_voted            = $code->has_voted;
        }

      //   dd($selected_candidates);
      return Inertia::render('Vote/VoteShowVerify', [

              'user_name'=>$auth_user->name,
              'has_voted'=>$has_voted,
       ]);

     }

     /**
      * Demo-specific verify/show page after vote is saved
      * Mirrors the real voting verify_to_show but for demo elections
      * Queries by voting_code (not user_id) to avoid storing user info in demo_votes table
      */
     public function demo_verify_to_show(){
        $auth_user = auth()->user();
        $election = request()->attributes->get('election');
        $voterSlug = request()->attributes->get('voter_slug');

        \Log::info('🎯 demo_verify_to_show - ENTRY', [
            'user_id' => $auth_user ? $auth_user->id : null,
            'election_id' => $election ? $election->id : null,
            'election_type' => $election ? $election->type : null,
            'has_voter_slug' => $voterSlug ? 'yes' : 'no',
            'voter_slug' => $voterSlug ? $voterSlug->slug : null
        ]);

        // Get demo code for this user
        $code = DemoCode::where('user_id', $auth_user->id)
                       ->where('election_id', $election?->id)
                       ->first();

        \Log::debug('🎮 demo_verify_to_show - Code lookup', [
            'code_found' => $code ? 'yes' : 'no',
            'code_id' => $code ? $code->id : null,
            'code.has_voted' => $code ? $code->has_voted : null,
            'code.voting_code' => $code ? $code->voting_code : null
        ]);

        $has_voted = false;
        $verification_code = null;

        if ($code && $code->has_voted) {
            $has_voted = true;
        }

        // Get demo vote by voting_code (not by user_id, to keep votes anonymous)
        if ($code && $code->voting_code) {
            $demoVote = \App\Models\DemoVote::where('voting_code', $code->voting_code)
                                           ->first();

            \Log::debug('🎮 demo_verify_to_show - Vote lookup', [
                'vote_found' => $demoVote ? 'yes' : 'no',
                'vote_id' => $demoVote ? $demoVote->id : null,
                'voting_code' => $code->voting_code
            ]);

            if ($demoVote) {
                $verification_code = $demoVote->voting_code;
            }
        }

        Log::info('🎯 demo_verify_to_show - RENDERING', [
            'user_id' => $auth_user->id,
            'has_voted' => $has_voted,
            'is_demo' => true,
            'has_verification_code' => $verification_code ? true : false,
            'verification_code' => $verification_code,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        return Inertia::render('Vote/VoteShowVerify', [
            'user_name' => $auth_user->name,
            'has_voted' => $has_voted,
            'is_demo' => true,
            'verification_code' => $verification_code,
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'default_election_type' => 'demo',
        ]);
     }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vote $vote)
    {
        //
    }
    public function at_least_one_vote_casted(){

        $btemp = false;
        // Check if voter abstained from any posts (no_vote_posts is JSON array)
        $no_vote_posts = request('no_vote_posts', []);
        $btemp = !empty($no_vote_posts);
        $btemp =$btemp | sizeof(request('icc_member'))>0;
        $btemp =$btemp | sizeof(request('president'))>0;
         $btemp =$btemp | sizeof(request('vice_president'))>0;
        $btemp =$btemp | sizeof(request('wvp'))>0;
        $btemp =$btemp | sizeof(request('general_secretary'))>0;
        $btemp =$btemp | sizeof(request('secretary'))>0;
        $btemp =$btemp | sizeof(request('treasure'))>0;
        $btemp =$btemp | sizeof(request('w_coordinator'))>0;
        $btemp =$btemp | sizeof(request('y_coordinator'))>0;
        $btemp =$btemp | sizeof(request('cult_coordinator'))>0;
        $btemp =$btemp | sizeof(request('child_coordinator'))>0;
        $btemp =$btemp | sizeof(request('studt_coordinator'))>0;
        $btemp =$btemp | sizeof(request('member_berlin'))>0;
        $btemp =$btemp | sizeof(request('member_hamburg'))>0;
        $btemp =$btemp | sizeof(request('member_nsachsen'))>0;
        $btemp =$btemp | sizeof(request('member_nrw'))>0;
        $btemp =$btemp | sizeof(request('member_hessen'))>0;
        $btemp =$btemp | sizeof(request('member_rhein_pfalz'))>0;
        $btemp =$btemp | sizeof(request('member_bayern'))>0;


        return $btemp;
    }
    public function get_candidate($key){
        $_candivec =[];
         $submit_vec =request($key);
        if(sizeof($submit_vec)>0)
        {
                
            
                for($i=0; $i<sizeof($submit_vec); ++$i){
                    //    var_dump($submit_vec[$i] );
                    $_candi                      = DB::table('candidacies')->where([
                    ['candidacy_id', '=',  $submit_vec[$i] ], 
                    // ['post_id',        '=',  $_postid]
                    ])->get()->first();

                            // dd($_candi); 
                       $myvec = array(
                                'post_name' =>$_candi->post_name,
                                'candidacy_id'     =>$_candi->user_id,
                                  'candidacy_name'  => $_candi->candidacy_name
                        );
                        
                    array_push($_candivec,   $myvec);         
                    
                }
    
            }
            
       return $_candivec; 
    }


/**
 * STEP 7: Display the vote verification page where users confirm their selections
 * and enter the second verification code to finalize their vote
 * 
 * This function handles the verification step after candidate selections have been submitted
 *
 * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
 */
/**
 * STEP 4: Show vote verification page
 * Display vote selections for user confirmation before final submission
 */
public function verify(Request $request)
{
    try {
        // Get user and election context
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        // Set organisation context for tenant scoping
        session(['current_organisation_id' => $election->organisation_id]);

        // ✅ FIXED: Fetch correct code model based on election type
        if ($election->type === 'demo') {
            $code = DemoCode::where('user_id', $auth_user->id)
                ->where('election_id', $election->id)
                ->first();
        } else {
            $code = $auth_user->code;
        }

        Log::info('Vote verification page accessed', [
            'user_id' => $auth_user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
        ]);

        // Get vote data from session (stored by first_submission)
        $vote_data = request()->session()->get($code->session_name);

        // Critical check: Ensure we have vote data in session
        if (!$vote_data) {
            Log::warning('Vote verification attempted without session data', [
                'user_id' => $auth_user->id,
                'election_id' => $election->id,
                'session_id' => request()->session()->getId()
            ]);
            
            // Use demo routes for demo elections!
            $route = $voterSlug ? 'slug.demo-vote.create' : 'demo-vote.create';
            $routeParams = $voterSlug ? ['vslug' => $voterSlug->slug] : [];

            return redirect()->route($route, $routeParams)
                ->withErrors(['session' => 'Vote session expired. Please start the voting process again.']);
        }
       

        // Perform comprehensive post-submission checks
        $_error = $this->vote_post_check($auth_user, $code, $vote_data);
        if ($_error["error_message"] != "") {
            Log::error('Vote verification post-check failed', [
                'user_id' => $auth_user->id,
                'error' => $_error["error_message"]
            ]);
            
            return redirect()->route('dashboard')
                ->withErrors(['verification' => 'Vote verification failed. Please contact support if this persists.']);
        }
        
        if ($_error["return_to"] != "") {
            Log::info('Vote verification redirecting user', [
                'user_id' => $auth_user->id,
                'redirect_to' => $_error["return_to"]
            ]);
            return redirect()->route($_error["return_to"]);
        }

        // Check second code timing and validity
        $_message = $this->second_code_check($code);
        $code_expires_in = $code->voting_time_in_minutes;
        if ($_message["error_message"] != "") {
            Log::error('Second code check failed during verification', [
                'user_id' => $auth_user->id,
                'error' => $_message["error_message"],
                'total_duration' => $_message["totalDuration"] ?? 'unknown'
            ]);
            
            $route = $voterSlug ? 'slug.code.create' : 'code.create';
            $routeParams = $voterSlug ? ['vslug' => $voterSlug->slug] : [];

            return redirect()->route($route, $routeParams)
                ->withErrors(['code' => 'Verification code expired. Please restart the voting process.']);
        }

        if ($_message["return_to"] != "") {
            return redirect()->route($_message["return_to"]);
        }

        // Process and structure vote data for clean display
        $processed_vote_data = $this->process_vote_data_for_verification($vote_data);

        // Calculate remaining time for user awareness
        $remaining_time = max(0, $code_expires_in - $_message["totalDuration"]);
        
        // Prepare comprehensive user information
        $user_info = [
            'name' => $auth_user->name,
            'user_id' => $auth_user->user_id ?? 'N/A',
            'state' => $auth_user->state ?? 'N/A',
            'region' => $auth_user->region ?? 'N/A',
        ];

        // Generate voting summary for quick overview
        $voting_summary = $this->generate_verification_summary($processed_vote_data);

        // Record Step 4: Vote verification
        try {
            $stepTrackingService = new \App\Services\VoterStepTrackingService();
            $stepTrackingService->completeStep(
                $voterSlug, $election, 4,
                ['vote_verified' => true, 'verified_at' => now()->toIso8601String()]
            );
            Log::info('Step 4 recorded: Vote verification', [
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $election->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to record Step 4: Vote verification', [
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $election->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Log successful verification page load
        Log::info('Vote verification page loaded successfully', [
            'user_id' => $auth_user->id,
            'remaining_time' => $remaining_time,
            'total_posts' => $voting_summary['total_posts'],
            'voted_posts' => $voting_summary['voted_posts']
        ]);

        // For API/JSON requests (tests), return structured data
        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'step' => 4,
                'current_step' => $voterSlug ? $voterSlug->current_step : 4,
                'user_info' => $user_info,
                'voting_summary' => $voting_summary,
                'has_vote_data' => !empty($processed_vote_data),
            ]);
        }

        // Check if user has valid email - if not, show code on page
        $hasValidEmail = $auth_user->email && filter_var($auth_user->email, FILTER_VALIDATE_EMAIL);
        $showDebugCode = !$hasValidEmail || app()->environment(['local', 'development']);

        $election = $this->getElection($request);

        // ✅ FIXED: Render demo verification component for demo elections
        return Inertia::render('Vote/DemoVote/Verify', [
            'selected_votes' => $voting_summary['vote_details'] ?? [],  // ✅ FIXED: Pass correct prop name
            'total_votes' => $voting_summary['voted_posts'] ?? 0,
            'vote_data' => $processed_vote_data,
            'user_info' => $user_info,
            'timing_info' => [
                'total_duration' => $_message["totalDuration"],
                'code_expires_in' => $code_expires_in,
                'remaining_time' => $remaining_time,
                'submission_time' => $vote_data['submission_timestamp'] ?? Carbon::now()->toISOString(),
                'code_sent_at' => Carbon::now()->subMinutes($_message["totalDuration"])->format('H:i:s')
            ],
            'voting_summary' => $voting_summary,
            'debug_code' => $showDebugCode ? $code->code1 : null,
            'has_valid_email' => $hasValidEmail,
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'election' => $election ? [
                'id' => $election->id,
                'name' => $election->name,
                'type' => $election->type,
                'description' => $election->description,
                'is_active' => $election->is_active,
            ] : null,
        ]);

    } catch (\Exception $e) {
        Log::error('Verify function encountered unexpected error', [
            'user_id' => auth()->id(),
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->route('dashboard')
            ->withErrors(['system' => 'System error during verification. Please try again or contact support.']);
    }
}

/**
 * Process vote data specifically for verification display
 * Handles both the new data structure and ensures backward compatibility
 *
 * @param array $vote_data
 * @return array
 */
private function process_vote_data_for_verification($vote_data)
{
    $processed = [
        'national_posts' => [],
        'regional_posts' => [],
        'has_national_votes' => false,
        'has_regional_votes' => false,
        'submission_metadata' => [
            'user_id' => $vote_data['user_id'] ?? null,
            'user_name' => $vote_data['user_name'] ?? 'Unknown',
            'submission_timestamp' => $vote_data['submission_timestamp'] ?? null,
            'agree_button' => $vote_data['agree_button'] ?? false
        ]
    ];

    // Process national posts selections
    if (isset($vote_data['national_selected_candidates']) && is_array($vote_data['national_selected_candidates'])) {
        foreach ($vote_data['national_selected_candidates'] as $index => $selection) {
            if ($selection && is_array($selection)) {
                $post_data = [
                    'post_id' => $selection['post_id'] ?? "unknown_national_{$index}",
                    'post_name' => $selection['post_name'] ?? 'Unknown National Post',
                    'required_number' => $selection['required_number'] ?? 1,
                    'no_vote' => $selection['no_vote'] ?? false,
                    'candidates' => $selection['candidates'] ?? [],
                    'selection_type' => 'national'
                ];
                
                $processed['national_posts'][] = $post_data;
                
                // Check if this post has actual candidate votes
                if (!$post_data['no_vote'] && !empty($post_data['candidates'])) {
                    $processed['has_national_votes'] = true;
                }
            }
        }
    }

    // Process regional posts selections
    if (isset($vote_data['regional_selected_candidates']) && is_array($vote_data['regional_selected_candidates'])) {
        foreach ($vote_data['regional_selected_candidates'] as $index => $selection) {
            if ($selection && is_array($selection)) {
                $post_data = [
                    'post_id' => $selection['post_id'] ?? "unknown_regional_{$index}",
                    'post_name' => $selection['post_name'] ?? 'Unknown Regional Post',
                    'required_number' => $selection['required_number'] ?? 1,
                    'no_vote' => $selection['no_vote'] ?? false,
                    'candidates' => $selection['candidates'] ?? [],
                    'selection_type' => 'regional'
                ];
                
                $processed['regional_posts'][] = $post_data;
                
                // Check if this post has actual candidate votes
                if (!$post_data['no_vote'] && !empty($post_data['candidates'])) {
                    $processed['has_regional_votes'] = true;
                }
            }
        }
    }

    return $processed;
}

/**
 * Generate comprehensive voting summary for verification page
 *
 * @param array $processed_vote_data
 * @return array
 */
private function generate_verification_summary($processed_vote_data)
{
    $summary = [
        'total_posts' => 0,
        'voted_posts' => 0,
        'no_vote_posts' => 0,
        'candidate_selections' => 0,
        'completion_percentage' => 0,
        'national_summary' => [
            'total' => 0, 
            'voted' => 0, 
            'no_vote' => 0,
            'candidates' => 0
        ],
        'regional_summary' => [
            'total' => 0, 
            'voted' => 0, 
            'no_vote' => 0,
            'candidates' => 0
        ],
    ];

    // Count national posts
    foreach ($processed_vote_data['national_posts'] as $post) {
        $summary['total_posts']++;
        $summary['national_summary']['total']++;
        
        if ($post['no_vote']) {
            $summary['no_vote_posts']++;
            $summary['national_summary']['no_vote']++;
        } else {
            $candidate_count = count($post['candidates']);
            if ($candidate_count > 0) {
                $summary['voted_posts']++;
                $summary['national_summary']['voted']++;
                $summary['candidate_selections'] += $candidate_count;
                $summary['national_summary']['candidates'] += $candidate_count;
            }
        }
    }

    // Count regional posts
    foreach ($processed_vote_data['regional_posts'] as $post) {
        $summary['total_posts']++;
        $summary['regional_summary']['total']++;
        
        if ($post['no_vote']) {
            $summary['no_vote_posts']++;
            $summary['regional_summary']['no_vote']++;
        } else {
            $candidate_count = count($post['candidates']);
            if ($candidate_count > 0) {
                $summary['voted_posts']++;
                $summary['regional_summary']['voted']++;
                $summary['candidate_selections'] += $candidate_count;
                $summary['regional_summary']['candidates'] += $candidate_count;
            }
        }
    }

    // Calculate completion percentage
    if ($summary['total_posts'] > 0) {
        $completed_posts = $summary['voted_posts'] + $summary['no_vote_posts'];
        $summary['completion_percentage'] = round(($completed_posts / $summary['total_posts']) * 100, 1);
    }

    return $summary;
}

/**
 * Validate the voting code submission with proper error handling
 * 
 * @return \Illuminate\Validation\Validator
 */
/**
 * Validate the voting code submission
 * 
 * @return \Illuminate\Validation\Validator
 */
public function verifyVoteSubmit(): array
{

    $request = request();

    $auth_user = auth()->user();
    $code      =$auth_user->code;
    $in_code  = $code->code1;
  
    $submittedCode = trim($request->input('voting_code'));

    $isCodeValid = false;

    $validator = Validator::make($request->all(), [
        'voting_code' => 'required|string|size:6'
    ]);
 
    $validator->after(function ($validator) use ($code, $submittedCode, &$isCodeValid) {
        if (!$code) {
            $validator->errors()->add('voting_code', 'Verification record missing.');
            return;
        }

        if ($code->has_voted) {
            $validator->errors()->add('voting_code', 'You have already voted.');
            return;
        }

        if (!$code->is_code2_usable) {
            $validator->errors()->add('voting_code', 'This code is no longer valid.');
            return;
        }
        
        // Plain text comparison for code1 (always plain text)
        $clean_submitted_code = strtoupper(trim($submittedCode));
        $clean_in_code = strtoupper(trim($in_code));

        if ($clean_submitted_code !== $clean_in_code) {
            $validator->errors()->add('voting_code', 'Incorrect code. Please try again.');
            return;
        }

        $isCodeValid = true; // ✅ Set your flag
    });
    // Run validation logic
    $validator->validate(); // Triggers after callbacks
      return [
        'validator' => $validator,
        'is_code_valid' => $isCodeValid
    ];
}


/**
 * Save vote and all candidate selections to the database
 * 
 * This method handles the final persistence of a vote after all validation steps.
 * It creates a vote record (anonymous) and associated result records for each
 * selected candidate. The method is election-type aware and works for both
 * demo and real elections through the VotingServiceFactory.
 *
 * @param array $input_data The validated vote data from the session
 * @param string $hashed_voting_key The hashed verification key for this vote
 * @param Election|null $election The election context (demo or real)
 * @param User|null $auth_user The authenticated user casting the vote
 * @param string|null $private_key The unhashed private key for email verification
 * @return void
 * @throws \Exception When required data is missing or invalid
 */
public function save_vote($input_data, $hashed_voting_key, $election = null, $auth_user = null, $private_key = null)
{
    // =========================================================================
    // SECTION 1: INITIALIZATION AND CONTEXT SETUP
    // =========================================================================
    
    // Fallback for backward compatibility - ensure we have an election context
    if (!$election) {
        $election = Election::where('type', 'real')->first();
        \Log::warning('save_vote called without election context, using fallback', [
            'fallback_election_id' => $election->id
        ]);
    }

    // Get the appropriate voting service and model classes based on election type
    // This ensures we use DemoVote/DemoResult for demo elections and Vote/Result for real ones
    $votingService = $this->getVotingService($election);
    $voteModel = $votingService->getVoteModel();      // DemoVote or Vote
    $resultModel = $votingService->getResultModel();  // DemoResult or Result

    // Retrieve the code record for this user and election - needed for vote_hash generation
    $codeModelClass = $election->type === 'demo' 
        ? \App\Models\DemoCode::class 
        : \App\Models\Code::class;
    
    $code = $codeModelClass::where('user_id', $auth_user->id)
        ->where('election_id', $election->id)
        ->first();

    if (!$code) {
        \Log::error('Cannot find code record for vote hash generation', [
            'user_id' => $auth_user->id,
            'election_id' => $election->id,
            'election_type' => $election->type
        ]);
        throw new \Exception('Code record not found for vote hash generation');
    }

    // =========================================================================
    // SECTION 2: CREATE THE MAIN VOTE RECORD (ANONYMOUS)
    // =========================================================================
    
    // Extract abstention data - JSON array of post IDs where voter chose to skip
    $no_vote_posts = $input_data['no_vote_posts'] ?? [];

    // Create new vote instance (either DemoVote or Vote)
    $vote = new $voteModel;
    
    // ⚠️ CRITICAL: Do NOT store user_id - votes must remain anonymous!
    // The vote_hash provides verifiability without compromising anonymity
    
    $vote->no_vote_posts = $no_vote_posts;
    $vote->election_id = $election->id;
    
    // Set organisation_id based on election type and context
    // This supports multi-tenancy at the vote level
    if ($election->type === 'real') {
        // Real elections: organisation_id comes from the election (strict enforcement)
        $vote->organisation_id = $election->organisation_id;
    } else {
        // Demo elections: organisation_id comes from session
        // MODE 1: NULL = public demo (visible to all)
        // MODE 2: organisation_id = scoped to specific organisation
        $vote->organisation_id = session('current_organisation_id');
    }

    // Set timestamp for cryptographic hash generation
    $vote->cast_at = now();

    // =========================================================================
    // SECTION 3: GENERATE VERIFIABLE VOTE HASH (ANONYMOUS VERIFICATION)
    // =========================================================================
    
    // Generate cryptographic vote_hash using SHA256
    // This allows voters to verify their vote was counted WITHOUT revealing:
    // - Who they voted for
    // - Their identity
    // 
    // Hash components:
    // - code.user_id: Unique to the voter (but not publicly identifiable)
    // - election_id: Scoped to this election
    // - code.code1: The first verification code (known only to voter)
    // - cast_at.timestamp: Prevents hash collisions
    $vote->vote_hash = hash('sha256',
        $code->user_id .
        $election->id .
        $code->code1 .
        $vote->cast_at->timestamp
    );

    \Log::info('Generated vote hash', [
        'vote_hash_prefix' => substr($vote->vote_hash, 0, 8) . '...',
        'election_id' => $election->id,
        'election_type' => $election->type,
        'cast_at' => $vote->cast_at->toIso8601String(),
    ]);

    // Save the vote record - this gives us the vote ID for results
    $vote->save();
    $this->out_code = $vote->getKey(); // Store vote ID for later use

    // =========================================================================
    // SECTION 4: PROCESS AND SAVE CANDIDATE SELECTIONS
    // =========================================================================
    
    // Merge national and regional candidate selections into a single array
    // This simplifies the processing loop
    $all_candidates = array_merge(
        $input_data["national_selected_candidates"] ?? [],
        $input_data["regional_selected_candidates"] ?? []
    );

    \Log::info('Processing candidate selections', [
        'total_candidate_entries' => count($all_candidates),
        'vote_id' => $vote->id
    ]);

    // Loop through each post's candidate selection
    foreach ($all_candidates as $index => $post_selection) {
        
        // Construct the column name for storing this post's selection
        // Format: candidate_01, candidate_02, ..., candidate_60
        $column_name = 'candidate_' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
        
        $vote_data_json = [];

        // Case 1: No selection for this post (null value)
        if ($post_selection === null) {
            $vote_data_json = [
                "candidates" => null,
                "no_vote" => true
            ];
        } 
        // Case 2: Selection exists for this post
        else {
                $vote_data_json = $post_selection;
                $selected_candidates = $vote_data_json["candidates"] ?? [];

            // Validate and correct inconsistent data
                // If no_vote flag is false but candidates array is empty, this is a bug
                // Force no_vote to true to maintain data integrity
                if (empty($selected_candidates)) {
                    $vote_data_json["no_vote"] = true;
                    \Log::warning('Fixed inconsistent vote data: empty candidates with no_vote=false', [
                        'post_id' => $post_selection['post_id'] ?? 'unknown',
                        'post_name' => $post_selection['post_name'] ?? 'unknown'
                    ]);
                } else {
                    // Valid vote with candidate selections
                    $vote_data_json["no_vote"] = false;
                
                // =================================================================
                // SECTION 4.1: SAVE INDIVIDUAL RESULTS FOR EACH SELECTED CANDIDATE
                // =================================================================

                // Get the post ID and ensure it's in the correct format (integer)
                $post_id = $vote_data_json['post_id'];
                $post_id = $this->normalizePostId($post_id, $election);

                // Process each selected candidate for this post
                foreach ($selected_candidates as $candidate_data) {
                    
                    // Create a result record for this candidate
                    $result = new $resultModel;
                    $result->vote_id = $vote->id;
                    $result->election_id = $election->id;
                    $result->post_id = $post_id;
                    
                    // =================================================================
                    // CRITICAL FIX: Extract the actual database ID from the complex string
                    // The frontend sends "demo-general_secretary-1-1" 
                    // We need to extract the last number (the actual database ID)
                    // =================================================================
                    $candidate_string_id = $candidate_data['candidacy_id'];
                    
                    // Extract the last number from the string (e.g., from "demo-general_secretary-1-1" get "1")
                    if (preg_match('/(\d+)$/', $candidate_string_id, $matches)) {
                        $actual_candidate_id = $matches[1];
                        
                        \Log::debug('Extracted candidate ID from string', [
                            'original' => $candidate_string_id,
                            'extracted' => $actual_candidate_id,
                            'post_id' => $post_id,
                            'election_id' => $election->id
                        ]);
                        
                        // Look up by the actual database ID
                        $demoCandidacy = DemoCandidacy::where('id', $actual_candidate_id)
                            ->where('election_id', $election->id)
                            ->where('post_id', $post_id)
                            ->first();
                    }
                    
                    // Fallback: try to find by position if extraction fails
                    if (!$demoCandidacy) {
                        $demoCandidacy = DemoCandidacy::where('election_id', $election->id)
                            ->where('post_id', $post_id)
                            ->orderBy('position_order')
                            ->first();
                    }
                    
                    if (!$demoCandidacy) {
                        \Log::error('CRITICAL: Demo candidacy not found', [
                            'candidacy_id' => $candidate_string_id,
                            'election_id' => $election->id,
                            'post_id' => $post_id,
                            'vote_id' => $vote->id
                        ]);
                        throw new \Exception('Cannot save result: Candidate not found in database. ID: ' . $candidate_string_id);
                    }
                    
                    // ✅ Set candidate_id to the database ID
                    $result->candidate_id = $demoCandidacy->id;
                    
                    // Set organisation_id based on election type
                    if ($election->type === 'real') {
                        $result->organisation_id = $election->organisation_id;
                    } else {
                        $result->organisation_id = session('current_organisation_id');
                    }
                    
                    $result->save();
                    
                    \Log::debug('Saved candidate result', [
                        'result_id' => $result->id,
                        'candidate_id' => $demoCandidacy->id,
                        'candidate_string' => $candidate_string_id
                    ]);
                    }
                }
        }

        // Store the JSON representation of this post's selection in the vote record
        $vote->$column_name = json_encode($vote_data_json);
    }

    // =========================================================================
    // SECTION 5: FINALIZE AND LOG
    // =========================================================================
    
    // Save the vote record with all JSON candidate data
    $vote->save();

    // Log successful vote save for audit trail
    Log::info('✅ Vote saved successfully', [
        'vote_id' => $vote->id,
        'election_id' => $election->id,
        'election_type' => $election->type,
        'organisation_id' => $vote->organisation_id,
        'results_count' => count($all_candidates),
        'vote_hash_prefix' => substr($vote->vote_hash, 0, 8) . '...',
        'timestamp' => now()->toIso8601String()
    ]);

    // Audit log for compliance (separate channel)
    \Log::channel('voting_audit')->info('Vote and results persisted', [
        'vote_id' => $vote->id,
        'election_id' => $election->id,
        'organisation_id' => $vote->organisation_id,
        'result_count' => count($all_candidates),
        'ip' => request()->ip(),
        'timestamp' => now(),
    ]);
}

/**
 * Helper method to normalize post_id to integer format
 * 
 * @param mixed $post_id The post ID from frontend (could be string, integer, or name)
 * @param Election $election The election context
 * @return int Normalized integer post ID
 * @throws \Exception If post_id cannot be normalized
 */
private function normalizePostId($post_id, $election): int
{
    // If already integer, return as-is
    if (is_int($post_id)) {
        return $post_id;
    }
    
    // If not a string, we can't process it
    if (!is_string($post_id)) {
        throw new \Exception('Invalid post_id type: ' . gettype($post_id));
    }
    
    // Case 1: String contains only digits (e.g., "42")
    if (is_numeric($post_id)) {
        $converted = (int) $post_id;
        \Log::debug('Converted numeric string post_id to integer', [
            'original' => $post_id,
            'converted' => $converted
        ]);
        return $converted;
    }
    
    // Case 2: String ends with digits (e.g., "president-3")
    if (preg_match('/\d+$/', $post_id, $matches)) {
        $converted = (int) $matches[0];
        \Log::debug('Extracted numeric post_id from string', [
            'original' => $post_id,
            'converted' => $converted
        ]);
        return $converted;
    }
    
    // Case 3: String is a post name - try to look it up
    $post = DemoPost::where('election_id', $election->id)
        ->whereRaw('LOWER(name) = ?', [strtolower($post_id)])
        ->orWhereRaw('LOWER(post_id) = ?', [strtolower($post_id)])
        ->first();
    
    if ($post) {
        \Log::info('Mapped post identifier to ID', [
            'identifier' => $post_id,
            'post_id' => $post->id,
            'post_name' => $post->name
        ]);
        return $post->id;
    }
    
    // If all else fails, log error and throw exception
    \Log::error('Cannot normalize post_id', [
        'post_id' => $post_id,
        'election_id' => $election->id
    ]);
    
    throw new \Exception('Invalid post_id format: ' . $post_id);
}


//vote thanks 
    public function thankyou(){
           return Inertia::render('Thankyou/Thankyou', [
                 'vote' =>$vote,
                //  'name'=>auth()->user()->name,
                //  'nrna_id'=>auth()->user()->nrna_id,
                //  'state' =>auth()->user()->state              
        ]);
                   
    }

    // 

public function verify_final_vote(Request $request)
{
    $this->out_code = trim($request['voting_code']);
    $auth_user = auth()->user();
    $code = $auth_user->code;

    $validator = $this->verify_code_to_check_vote($code);
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator);
    }

    $final_vote = [
        'selected_candidates' => [],
        'name' => $auth_user->name,
        'has_voted' => $code->has_voted,
        'verify_final_vote' => Hash::check($this->out_code, $code->code_for_vote)
    ];

    if ($final_vote['verify_final_vote']) {
        $str_pos = strpos($this->out_code, "_") + 1;
        $voting_id = (int)substr($this->out_code, $str_pos);
        $vote = DemoVote::find($voting_id);
        
        if ($vote) {
            $vote_data = $vote->toArray();
            $final_vote['selected_candidates'] = array_filter($vote_data, function($key) {
                return strpos($key, 'candidate') === 0;
            }, ARRAY_FILTER_USE_KEY);
        }
    }

    // Store in multiple ways for redundancy
    $request->session()->put('final_vote', $final_vote);
    session(['final_vote' => $final_vote]); // Alternative method
    $request->session()->save(); // Force immediate save 

    // Also store in database as backup
    //$code->vote_show_data = json_encode($final_vote);
    $code->save();

    \Log::debug('Session ID during save:', ['id' => session()->getId()]);
    \Log::debug('Final vote data saved:', $final_vote);

    return redirect()->route('vote.show')->with([
        '_final_vote' => $final_vote // Flash data as additional backup
    ]);
}
//
    public function verify_code_to_check_vote($code){

        $validator =  Validator::make(request()->all(), [
            'voting_code' =>['required'],
        ]);
        if($code==null  ){
            // Use translation for error message
            $errorMsg = __('pages.vote-show-verify.errors.wrong_verification_code');
            $validator->errors()->add('code_to_check_vote', $errorMsg);
        }
        if($code!=null){
            $this->has_voted   =$code->has_voted;
           $this->in_code      =$code->code_for_vote;

        }
        if(!$this->has_voted){
            // Use translation for error message
            $errorMsg = __('pages.vote-show-verify.errors.not_voted_yet');
            $validator->errors()->add('code_to_check_vote', $errorMsg);
        }

         $validator->after(function ($validator) {

            $voting_code =request('voting_code');
            if (!Hash::check($this->out_code, $this->in_code)) {
                // Use translation for error message
                $errorMsg = __('pages.vote-show-verify.errors.wrong_verification_code');
                $validator->errors()->add('code_to_check_vote', $errorMsg);
            }
        });


        return $validator;
    }

    /**
     * ==========================================
     * VOTE PRE-CHECK METHOD
     * ==========================================
     *
     * Pre-check voting code before allowing vote submission
     * Handles both SIMPLE and STRICT modes based on configuration
     *
     * SIMPLE MODE (default):
     *   - One code used twice (entry + vote submission)
     *   - Tracks entry with code1_used_at, vote with code2_used_at
     *
     * STRICT MODE:
     *   - Two separate codes (Code1 for entry, Code2 for voting)
     *   - Code1 set to unusable after first use
     *
     * @param DemoCode $code
     * @return string Route name for redirect ("" if passes all checks)
     */
    public function vote_pre_check(&$code)        
    {
        //   dd([
        // 'code_id' => $code->id ?? 'null',
        // 'can_vote_now' => $code->can_vote_now ?? 'null',
        // 'has_voted' => $code->has_voted ?? 'null',
        // 'has_code1_sent' => $code->has_code1_sent ?? 'null',
        // 'is_code1_usable' => $code->is_code1_usable ?? 'null',
        // 'code1_used_at' => $code->code1_used_at ?? 'null',
        // 'code2_used_at' => $code->code2_used_at ?? 'null',
        // 'vote_submitted' => $code->vote_submitted ?? 'null',
        // ]);
        
        // ========== GUARD CLAUSE 1: No code found ==========
        if ($code === null) {
            \Log::warning('🔴 vote_pre_check: GUARD 1 - Code is null');
            return "code.create";
        }

        // ========== GUARD CLAUSE 2: Voting window closed ==========
        if (!$code->can_vote_now) {
            \Log::warning('🔴 vote_pre_check: GUARD 2 - can_vote_now is false', [
                'can_vote_now' => $code->can_vote_now
            ]);
            return "dashboard";
        }

        // ========== GUARD CLAUSE 3: Already voted ==========
        if ($code->has_voted) {
            \Log::warning('🔴 vote_pre_check: GUARD 3 - has_voted is true', [
                'has_voted' => $code->has_voted
            ]);
            return "dashboard";
        }

          // ========== GUARD CLAUSE 4: Code1 never sent ==========
        if (!$code->has_code1_sent) {
            \Log::warning('🔴 vote_pre_check: GUARD 4 TRIGGERED - has_code1_sent is false', [
                'has_code1_sent' => $code->has_code1_sent
            ]);
            return "code.create";
        }
        // ========== GUARD CLAUSE 4: Code1 never sent ==========
        // if (!$code->has_code1_sent) {
         //     \Log::warning('🔴 vote_pre_check: GUARD 4 - has_code1_sent is false', [
        //         'has_code1_sent' => $code->has_code1_sent
        //     ]);
        //     return "code.create";
        // }
         // ========== GUARD CLAUSE 5: Check if code has already been used for voting ==========
        if ($code->code2_used_at !== null) {
            \Log::warning('🔴 vote_pre_check: GUARD 5 TRIGGERED - code2_used_at is set', [
                'code2_used_at' => $code->code2_used_at
            ]);
            return "dashboard";
        }

        // ========== GUARD CLAUSE 6: Voting window timeout ==========
        if ($this->hasVotingWindowExpired($code)) {
            \Log::warning('🔴 vote_pre_check: GUARD 6 TRIGGERED - Voting window expired', [
                'code1_used_at' => $code->code1_used_at,
                'current_time' => Carbon::now(),
                'voting_time_minutes' => $code->voting_time_in_minutes
            ]);
            $this->expireCode($code);
            return "code.create";
        }


        // ========== MODE-SPECIFIC VERIFICATION ==========
        $mode = $this->isStrictMode() ? 'STRICT' : 'SIMPLE';
        \Log::info("ℹ️ vote_pre_check: MODE CHECK - {$mode} MODE", [
            'code1_used_at' => $code->code1_used_at,
            'code2_used_at' => $code->code2_used_at,
            'is_code1_usable' => $code->is_code1_usable,
            'is_code2_usable' => $code->is_code2_usable,
        ]);

        if ($this->isStrictMode()) {
            $modeCheckResult = $this->verifyStrictModeCodeState($code);
            if ($modeCheckResult !== "") {
                \Log::warning('🔴 vote_pre_check: STRICT MODE FAILED', [
                    'result' => $modeCheckResult,
                    'code1_used_at' => $code->code1_used_at,
                    'code2_used_at' => $code->code2_used_at
                ]);
                return $modeCheckResult;
            }
        } else {
            $modeCheckResult = $this->verifySimpleModeCodeState($code);
            if ($modeCheckResult !== "") {
                \Log::warning('🔴 vote_pre_check: SIMPLE MODE FAILED', [
                    'result' => $modeCheckResult,
                    'code1_used_at' => $code->code1_used_at,
                    'code2_used_at' => $code->code2_used_at
                ]);
                return $modeCheckResult;
            }
        }

        // ========== GUARD CLAUSE 5: Voting window timeout ==========
        if ($this->hasVotingWindowExpired($code)) {
            \Log::warning('🔴 vote_pre_check: GUARD 5 - Voting window expired', [
                'code1_used_at' => $code->code1_used_at,
                'current_time' => Carbon::now(),
                'voting_time_minutes' => $code->voting_time_in_minutes
            ]);
            $this->expireCode($code);
            return "code.create";
        }

        // ========== ALL CHECKS PASSED ==========
        \Log::info('✅ vote_pre_check: ALL CHECKS PASSED', [
            'code1_used_at' => $code->code1_used_at,
            'code2_used_at' => $code->code2_used_at,
            'can_vote_now' => $code->can_vote_now
        ]);
        return "";
    } //end of vote_pre_check
   
    
    /***
     * 
     * post check
     * Check after submitting the code 
     *  
     */
 public function vote_post_check($auth_user, &$code, $vote)
{
    $_error = [
        "return_to" => "",
        "error_message" => "",
    ];

    // 1. Code is missing or invalid
    if ($code === null) {
        $_error['error_message'] = view('components.error_message', [
            'message' => 'Either your code is wrong or you have not voted properly. Send the screenshot to administrator!',
            'link' => route('dashboard'),
            'link_text' => 'Click here to go to the main Dashboard',
        ])->render();
        return $_error;
    }

    // 2. IP address check
    $clientIP = \Request::getClientIp(true);
    $max_use_clientIP = config('app.max_use_clientIP');
    $_message = check_ip_address($clientIP, $max_use_clientIP);

    if (!empty($_message['error_message'])) {
        // Just return the error, let the controller handle the redirect/flash
        $_error['error_message'] = $_message['error_message'];
        return $_error;
    }

    // // 3. Code is not usable
    // if (!$code->is_code2_usable) {
    //     $code->is_code1_usable = 0;
    //     $code->has_code1_sent = 0;
    //     $_error['return_to'] = 'vote.create';
    //     return $_error;
    // }

    // 4. User already voted
    if ($code->has_voted) {
        $_error['error_message'] = view('components.error_message', [
            'message' => 'You have already voted and your vote is already saved! See below',
            'link' => route('vote.verify_to_show'),
            'link_text' => 'Click here to see your vote',
        ])->render();
        return $_error;
    }

    // 5. Vote is missing (should never happen if code is valid and hasn't voted)
    if ($vote === null) {
        $_error['error_message'] = view('components.error_message', [
            'message' => 'We could not find your vote. Please contact the administrator. You can also start to vote again.',
            'link' => route('code.create'),
            'link_text' => 'Click here to vote',
        ])->render();
        return $_error;
    }
     
        // No error
    return $_error;
}

    public function second_code_check(&$code){
        $_message                  = [];
        $_message['error_message'] = "";
        $_message['return_to']     ="";
        $_message['totalDuration'] =0;

        $code_expires_in        = $code->voting_time_in_minutes;
        $current                = Carbon::now();
        $code1_used_at          = $code->code1_used_at;
        $totalDuration          = \Carbon\Carbon::parse($code1_used_at)->diffInMinutes($current);
        $_message['totalDuration']=$totalDuration;

        // ✅ FIXED: Check voting window timeout (independent of mode)
        if($totalDuration > $code_expires_in) {
            \Log::warning('🔴 second_code_check: Voting window expired', [
                'code_id' => $code->id,
                'total_duration' => $totalDuration,
                'voting_time' => $code_expires_in
            ]);
            $code->is_code1_usable      = 0;
            $code->has_code2_sent       = 0;
            $code->vote_submitted       = 0;
            $code->save();
            $return_to                  = 'code.create';
            $_message["return_to"]      = $return_to;
            $_message["totalDuration"]  = $totalDuration;
            return $_message;
        }

        // ✅ FIXED: Mode-specific checks
        if (config('voting.two_codes_system') == 1) {
            // STRICT MODE: Check if Code2 has been used
            if (!$code->code2_used_at) {
                \Log::warning('🔴 second_code_check: STRICT MODE - Code2 not yet verified', [
                    'code_id' => $code->id
                ]);
                $code->is_code1_usable      = 0;
                $code->is_code2_usable      = 0;
                $code->has_code2_sent       = 0;
                $code->save();
                $_message["return_to"]      = 'demo-vote.create';
                $_message["totalDuration"]  = $totalDuration;
                return $_message;
            }
        } else {
            // SIMPLE MODE: Check if vote was submitted (code2_used_at tracks second use)
            if (!$code->vote_submitted) {
                \Log::warning('🔴 second_code_check: SIMPLE MODE - Vote not submitted', [
                    'code_id' => $code->id
                ]);
                $code->is_code1_usable      = 0;
                $code->is_code2_usable      = 0;
                $code->has_code2_sent       = 0;
                $code->save();
                $_message["return_to"]      = 'demo-vote.create';
                $_message["totalDuration"]  = $totalDuration;
                return $_message;
            }
        }

        \Log::info('✅ second_code_check: All checks passed', [
            'code_id' => $code->id,
            'mode' => config('voting.two_codes_system') == 1 ? 'STRICT' : 'SIMPLE'
        ]);
        return $_message;
    }

    /**
     * Validate the user's eligibility and status before allowing vote casting.
     * If any condition fails, redirects back with detailed errors.
     * If already voted, returns the route to the vote summary page.
     * If all checks pass, sets a session flag to grant one-time access to the voting form and returns the voting route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $code
     * @param  \App\Models\User  $auth_user
     * @param  \App\Models\Election  $election
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify_first_submission(Request $request, &$code, $auth_user, $election)
    {
        // Abort if not authenticated (defensive check)
        if (!$auth_user) {
            abort(403, 'Not authenticated.');
        }

        // Gather inputs from request
        $user_id      = $request->input('user_id');
        $agree_button = $request->input('agree_button');
        $errors       = [];

        // Election type is determined by the $election parameter passed in
        $isDemoElection = $election && $election->type === 'demo';

        // 📋 LOG: Entry point
        \Log::debug('🔵 verify_first_submission - ENTRY', [
            'user_id' => $auth_user->id ?? null,
            'auth_user' => $auth_user ? 'present' : 'null',
            'code' => $code ? 'present' : 'null',
            'election_type' => $election->type ?? 'unknown',
            'is_demo_election' => $isDemoElection,
            'submitted_user_id' => $user_id,
            'agree_button' => $agree_button
        ]);

        // 1. User must be a registered voter (skip for demo elections)
        if (!$isDemoElection && $auth_user->is_voter != 1) {
            \Log::warning('❌ CHECK 1 FAILED: is_voter', [
                'condition' => '!isDemoElection && auth_user->is_voter != 1',
                'isDemoElection' => $isDemoElection,
                'auth_user.is_voter' => $auth_user->is_voter ?? null
            ]);
            $errors['is_voter'] = 'You are not registered as a voter.';
        } else {
            \Log::debug('✅ CHECK 1 PASSED: is_voter', [
                'condition' => '!isDemoElection && auth_user->is_voter != 1',
                'isDemoElection' => $isDemoElection,
                'auth_user.is_voter' => $auth_user->is_voter ?? null
            ]);
        }

        // 2. Voting window must be open for this user
        if ($code->can_vote_now != 1) {
            \Log::warning('❌ CHECK 2 FAILED: can_vote_now', [
                'condition' => 'code->can_vote_now != 1',
                'code' => $code ? 'present' : 'null',
                'code.can_vote_now' => $code ? $code->can_vote_now : 'code is null',
                'code_full' => $code ? [
                    'id' => $code->id,
                    'can_vote_now' => $code->can_vote_now,
                    'has_used_code2' => $code->has_used_code2 ?? null,
                    'code2_used_at' => $code->code2_used_at ?? null
                ] : null
            ]);
            $errors['can_vote_now'] = 'Voting is not open for you at this time.';
        } else {
            \Log::debug('✅ CHECK 2 PASSED: can_vote_now', [
                'code.can_vote_now' => $code ? $code->can_vote_now : 'code is null'
            ]);
        }

        // 3. User must be eligible to vote (skip for demo elections)
        if (!$isDemoElection && $auth_user->can_vote != 1) {
            \Log::warning('❌ CHECK 3 FAILED: can_vote', [
                'condition' => '!isDemoElection && auth_user->can_vote != 1',
                'isDemoElection' => $isDemoElection,
                'auth_user.can_vote' => $auth_user->can_vote ?? null
            ]);
            $errors['can_vote'] = 'You are not eligible to vote.';
        } else {
            \Log::debug('✅ CHECK 3 PASSED: can_vote', [
                'isDemoElection' => $isDemoElection,
                'auth_user.can_vote' => $auth_user->can_vote ?? null
            ]);
        }

        // 4. User must have used Code-1 to reach this step (check Code model)
        // $code = $auth_user->code;
        if (!$code || $code->can_vote_now != 1) {
            \Log::warning('❌ CHECK 4 FAILED: has_used_code1', [
                'condition' => '!code || code->can_vote_now != 1',
                'code' => $code ? 'present' : 'null',
                'code.can_vote_now' => $code ? $code->can_vote_now : 'N/A'
            ]);
            $errors['has_used_code1'] = 'You have not used your first voting code yet.';
        } else {
            \Log::debug('✅ CHECK 4 PASSED: has_used_code1', [
                'code' => 'present',
                'code.can_vote_now' => $code->can_vote_now
            ]);
        }

        // 5. User must NOT have used Code-2 (should be 0) - check Code model
        if ($code && $code->has_used_code2 != 0) {
            \Log::warning('❌ CHECK 5 FAILED: has_used_code2', [
                'condition' => 'code && code->has_used_code2 != 0',
                'code' => $code ? 'present' : 'null',
                'code.has_used_code2' => $code ? $code->has_used_code2 : 'N/A',
                'code.code2_used_at' => $code ? $code->code2_used_at : 'N/A'
            ]);
            $errors['has_used_code2'] = 'You have already confirmed your vote with Code-2.';
        } else {
            \Log::debug('✅ CHECK 5 PASSED: has_used_code2', [
                'code' => $code ? 'present' : 'null',
                'code.has_used_code2' => $code ? $code->has_used_code2 : 'N/A'
            ]);
        }

        // 6. User must NOT have already voted
        if ($auth_user->has_voted == 1) {
            \Log::info('⚠️ CHECK 6: User already voted', [
                'auth_user.has_voted' => $auth_user->has_voted,
                'returning' => 'vote.verify_to_show'
            ]);
            // Instead of redirecting back, return the 'vote.verify_to_show' route for already-voted users
            return 'vote.verify_to_show';
        } else {
            \Log::debug('✅ CHECK 6 PASSED: has_voted', [
                'auth_user.has_voted' => $auth_user->has_voted
            ]);
        }

        // 7. Ensure the submitted user ID matches the authenticated user
        if ((int)$user_id !== (int)$auth_user->id) {
            \Log::warning('❌ CHECK 7 FAILED: user_id match', [
                'condition' => '(int)user_id !== (int)auth_user->id',
                'submitted_user_id' => $user_id,
                'auth_user.id' => $auth_user->id,
                'submitted_user_id_int' => (int)$user_id,
                'auth_user_id_int' => (int)$auth_user->id
            ]);
            $errors['user_id'] = 'Login user does not match form user.';
        } else {
            \Log::debug('✅ CHECK 7 PASSED: user_id match', [
                'submitted_user_id' => $user_id,
                'auth_user.id' => $auth_user->id
            ]);
        }

        // 8. User must agree before proceeding (checkbox)
        if (!$agree_button) {
            \Log::warning('❌ CHECK 8 FAILED: agree_button', [
                'condition' => '!agree_button',
                'agree_button' => $agree_button
            ]);
            $errors['agree_button'] = 'You must agree before proceeding.';
        } else {
            \Log::debug('✅ CHECK 8 PASSED: agree_button', [
                'agree_button' => $agree_button
            ]);
        }

        // If there are any errors, redirect back to the form with all error messages and old input
        if (!empty($errors)) {
            \Log::warning('🔴 verify_first_submission - VALIDATION FAILED - Redirecting back', [
                'errors' => $errors,
                'error_count' => count($errors),
                'code_state' => $code ? [
                    'id' => $code->id,
                    'can_vote_now' => $code->can_vote_now,
                    'has_used_code2' => $code->has_used_code2,
                    'code2_used_at' => $code->code2_used_at,
                    'vote_submitted' => $code->vote_submitted ?? null,
                    'has_voted' => $code->has_voted ?? null
                ] : 'null',
                'auth_user_state' => [
                    'id' => $auth_user->id,
                    'has_voted' => $auth_user->has_voted,
                    'can_vote' => $auth_user->can_vote,
                    'is_voter' => $auth_user->is_voter
                ]
            ]);
            return redirect()->back()->withErrors($errors)->withInput();
        }

        // Grant one-time session access for the voting form
        // session(['vote_access_granted' => true]);

        // Return the appropriate redirect response to the verification page
        // Check if this is slug-based voting by looking for voter slug in request
        $voterSlug = $request->attributes->get('voter_slug');

        \Log::debug('✅ verify_first_submission - ALL CHECKS PASSED', [
            'has_voter_slug' => $voterSlug !== null,
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'is_demo_election' => $isDemoElection
        ]);

        // ✅ FIX: Redirect to demo routes for demo elections, regular routes for real elections
        if ($voterSlug) {
            if ($isDemoElection) {
                // Demo election with slug - use demo verification route
                $redirect = redirect()->route('slug.demo-vote.verify', ['vslug' => $voterSlug->slug]);
                \Log::info('🟢 verify_first_submission SUCCESS - Redirecting to slug.demo-vote.verify', [
                    'url' => $redirect->getTargetUrl(),
                    'vslug' => $voterSlug->slug
                ]);
            } else {
                // Real election with slug - use regular verification route
                $redirect = redirect()->route('slug.vote.verify', ['vslug' => $voterSlug->slug]);
                \Log::info('🟢 verify_first_submission SUCCESS - Redirecting to slug.vote.verify', [
                    'url' => $redirect->getTargetUrl(),
                    'vslug' => $voterSlug->slug
                ]);
            }
            return $redirect;
        } else {
            if ($isDemoElection) {
                // Demo election without slug - use demo verification route
                $redirect = redirect()->route('demo-vote.verify');
                \Log::info('🟢 verify_first_submission SUCCESS - Redirecting to demo-vote.verify', [
                    'url' => $redirect->getTargetUrl()
                ]);
            } else {
                // Real election without slug - use regular verification route
                $redirect = redirect()->route('vote.verify');
                \Log::info('🟢 verify_first_submission SUCCESS - Redirecting to vote.verify', [
                    'url' => $redirect->getTargetUrl()
                ]);
            }
            return $redirect;
        }
    }
 
// Add these methods to your VoteController class

public function verify_code_saved_in_vote($voting_code, $hashed_voting_code)
{
    if (!password_verify($voting_code, $hashed_voting_code)) {
        // If the codes do not match, redirect back with error and input
        return redirect()->back()
            ->withErrors(['voting_code' => 'Invalid verification code.'])
            ->withInput();
    }
    // If matched, you can return true or handle success as you wish
    return true;
}
/**
 * Process vote verification code and display the associated vote
 * 
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function submit_code_to_view_vote(Request $request)
{
    try {
        // Validate request input - support both old and new formats
        $request->validate([
            'election_type' => 'nullable|in:demo,real',
            'voting_code' => 'required|string|min:3|max:500'
        ], [
            'voting_code.required' => 'Verification code is required.',
            'voting_code.min' => 'Verification code is too short.',
            'voting_code.max' => 'Verification code is too long.'
        ]);
        $auth_user = auth()->user();
        $submitted_code = trim($request->input('voting_code'));
        $election_type = $request->input('election_type', 'real'); // Default to real

        // Unified retrieval for both demo and real elections
        $vote_id = null;

        if ($election_type === 'demo') {
            // For demo elections, retrieve from demo_votes table
            $vote_record = $this->retrieve_demo_vote_record($submitted_code);
            if (isset($vote_record['vote'])) {
                $vote_id = $vote_record['vote']->id;
            }
        } else {
            // For real elections, extract vote ID and retrieve from votes table
            $vote_data = $this->extract_vote_data_from_code($submitted_code);

            if (!$vote_data['success']) {
                return redirect()->back()
                    ->withErrors(['voting_code' => 'Invalid verification code format.'])
                    ->withInput();
            }

            $vote_record = $this->retrieve_vote_record($vote_data['vote_id'], $submitted_code);
            $vote_id = $vote_data['vote_id'];
        }   
        if (!$vote_record['success']) {
            return redirect()->back()
                ->withErrors(['voting_code' => 'Vote record not found.'])
                ->withInput();
        }

        // Prepare unified vote display data (works for both demo and real)
        $display_data = $this->prepare_unified_vote_display($vote_record['vote'], $auth_user, $submitted_code, $election_type);

        // Store in session for display
        $session_id = "vote_display_data_" . $vote_id;
        $request->session()->put($session_id, $display_data);
	
        

        // Log successful verification
        Log::info('Vote verification successful', [
            'user_id' => $auth_user->id,
            'vote_id' => $vote_id,
            'election_type' => $election_type,
            'verification_timestamp' => now()->toISOString()
        ]);

        return redirect()->route('vote.show', ['vote_id' => $vote_id])
            ->with('success', 'Vote verification successful.');


    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
            
    } catch (\Exception $e) {
        Log::error('Vote verification failed', [
            'user_id' => auth()->id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->withErrors(['voting_code' => 'Verification failed. Please try again or contact support.'])
            ->withInput();
    }
}

/**
 * Verify and display a demo election vote
 * Demo votes are stored separately in demo_votes table
 * Follows same pattern as retrieve_vote_record but for DemoVote
 *
 * Try by ID first (extracted from voting code), then by voting_code hash as fallback
 * Demo votes store plain codes (no hashing), so uses exact match for fallback
 *
 * @param string $verification_code Format: "private_key_vote_id" (e.g., "abc123_5")
 * @param object $auth_user
 * @return Response
 */
private function verify_demo_vote($verification_code, $auth_user)
{
    try {
        Log::info('Verifying demo vote', [
            'code_length' => strlen($verification_code),
            'user_id' => $auth_user->id,
            'code_format' => 'private_key_vote_id'
        ]);

        // Extract vote_id from verification_code if possible (format: "private_key_vote_id")
        $demo_vote_id = null;
        if (strpos($verification_code, '_') !== false) {
            $parts = explode('_', $verification_code);
            $demo_vote_id = end($parts); // Get the last part (vote_id)
            Log::info('Extracted vote_id from verification code', [
                'extracted_vote_id' => $demo_vote_id,
                'code_length' => strlen($verification_code)
            ]);
        }

        // Try to find by ID first
        if ($demo_vote_id) {
            $demoVote = DemoVote::find($demo_vote_id);

            if ($demoVote) {
                Log::info('Demo vote found by ID', [
                    'vote_id' => $demoVote->id,
                    'election_id' => $demoVote->election_id
                ]);

                return $this->display_demo_vote($demoVote, $auth_user);
            }
        }

        // Fallback: If vote not found by ID, try searching by voting_code (exact match)
        Log::info('Demo vote not found by ID, searching by voting code', [
            'vote_id' => $demo_vote_id,
            'code_provided' => substr($verification_code, 0, 20) . '...'
        ]);

        $demoVote = DemoVote::where('voting_code', $verification_code)
            ->first();

        if ($demoVote) {
            Log::info('Demo vote found by exact voting_code match', [
                'vote_id' => $demoVote->id,
                'election_id' => $demoVote->election_id
            ]);

            return $this->display_demo_vote($demoVote, $auth_user);
        }

        // Fallback 2: Search through all demo votes for exact match (in case code format differs)
        Log::info('Demo vote not found by voting_code, searching all demo votes', [
            'code_provided' => substr($verification_code, 0, 20) . '...'
        ]);

        $allDemoVotes = DemoVote::all();
        Log::info('Total demo votes in database', ['count' => count($allDemoVotes)]);

        $exactMatches = 0;
        foreach ($allDemoVotes as $candidateDemoVote) {
            if (!empty($candidateDemoVote->voting_code) && $candidateDemoVote->voting_code === $verification_code) {
                $exactMatches++;
                Log::info('Demo vote found by exhaustive search', [
                    'vote_id' => $candidateDemoVote->id,
                    'election_id' => $candidateDemoVote->election_id
                ]);

                return $this->display_demo_vote($candidateDemoVote, $auth_user);
            }
        }

        Log::warning('Demo vote not found by any method', [
            'vote_id_searched' => $demo_vote_id,
            'total_votes_checked' => count($allDemoVotes),
            'exact_matches_found' => $exactMatches,
            'submitted_code_length' => strlen($verification_code)
        ]);

        return redirect()->back()
            ->withErrors(['voting_code' => 'Invalid demo vote verification code. Please check your email for the correct code.'])
            ->withInput();

    } catch (\Exception $e) {
        Log::error('Demo vote verification failed', [
            'user_id' => $auth_user->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->withErrors(['voting_code' => 'Verification failed. Please try again.'])
            ->withInput();
    }
}

/**
 * Display demo vote after successful verification
 * Extracted helper method used by verify_demo_vote
 *
 * @param DemoVote $demoVote
 * @param object $auth_user
 * @return Response
 */
private function display_demo_vote($demoVote, $auth_user)
{
    try {
        // Get election info
        $election = Election::find($demoVote->election_id);

        if (!$election) {
            Log::warning('Election not found for demo vote', [
                'vote_id' => $demoVote->id,
                'election_id' => $demoVote->election_id
            ]);

            return redirect()->back()
                ->withErrors(['voting_code' => 'Election information not found.'])
                ->withInput();
        }

        // Prepare vote display data
        $display_data = $this->prepare_demo_vote_display($demoVote, $election, $auth_user);

        // Store in session for display
        $sessionId = "demo_vote_display_" . $demoVote->id;
        request()->session()->put($sessionId, $display_data);

        Log::info('Demo vote verification successful', [
            'vote_id' => $demoVote->id,
            'election_id' => $demoVote->election_id,
            'session_id' => $sessionId
        ]);

        return redirect()->route('vote.show', ['vote_id' => $demoVote->id])
            ->with('success', 'Demo vote verification successful.');

    } catch (\Exception $e) {
        Log::error('Failed to display demo vote', [
            'vote_id' => $demoVote->id,
            'error' => $e->getMessage()
        ]);

        return redirect()->back()
            ->withErrors(['voting_code' => 'Failed to display vote. Please try again.'])
            ->withInput();
    }
}

/**
 * Prepare demo vote display data
 *
 * @param object $demoVote
 * @param object $election
 * @param object $auth_user
 * @return array
 */
private function prepare_demo_vote_display($demoVote, $election, $auth_user)
{
    $voteSelections = [];

    // Process all 60 candidate columns
    for ($i = 1; $i <= 60; $i++) {
        $candidateColumn = 'candidate_' . sprintf('%02d', $i);
        if (isset($demoVote->$candidateColumn) && !empty($demoVote->$candidateColumn)) {
            $voteSelections[] = [
                'position' => $i,
                'candidate_id' => $demoVote->$candidateColumn,
                'selected' => true
            ];
        }
    }

    return [
        'vote_id' => $demoVote->id,
        'is_demo_vote' => true,
        'election_type' => 'demo',
        'verification_code' => $demoVote->voting_code,
        'verification_timestamp' => now()->toISOString(),
        'verification_successful' => true,
        'voter_info' => [
            'name' => $auth_user->name ?? 'Demo Voter',
            'user_id' => $auth_user->user_id ?? 'DEMO',
            'region' => $auth_user->region ?? 'Demo Region',
        ],
        'vote_info' => [
            'voted_at' => $demoVote->created_at ? $demoVote->created_at->format('M j, Y \a\t g:i A') : 'Unknown',
            'abstained_from_posts' => $demoVote->no_vote_posts ?? [],
            'vote_hash' => substr($demoVote->vote_hash ?? '', 0, 8) . '...',
        ],
        'vote_selections' => $voteSelections,
        'summary' => [
            'total_selections' => count($voteSelections),
            'election_id' => $election->id,
            'election_name' => $election->name ?? 'Demo Election'
        ]
    ];
}

/**
 * Validate the submitted verification code against stored hash
 * 
 * @param string $submitted_code
 * @param object $code
 * @param object $auth_user
 * @return array
 */
private function validate_vote_verification_code($submitted_code, $vote, $auth_user)
{
    // Check if user has voted
    if (!$code->has_voted) {
        return [
            'success' => false,
            'message' => 'You have not voted yet. Please vote first before verifying.'
        ];
    }
    
       // Verify the code against stored hash
    if (!Hash::check(trim($submitted_code), trim($code->code_for_vote))) {
        return [
            'success' => false,
            'message' => 'Invalid verification code. Please check your email and try again.'
        ];
    }

    return [
        'success' => true,
        'message' => 'Code verified successfully.'
    ];
}

/**
 * Extract vote ID from verification code format (e.g., "ABC123_456")
 * 
 * @param string $verification_code
 * @return array
 */
private function extract_vote_data_from_code($verification_code)
{
    // Check if code contains underscore separator
    if (!str_contains($verification_code, '_')) {
        return [
            'success' => false,
            'message' => 'Invalid code format.'
        ];
    }

    // Extract vote ID from after the underscore
    $parts = explode('_', $verification_code);
    
    if (count($parts) < 2) {
        return [
            'success' => false,
            'message' => 'Invalid code format.'
        ];
    }

    $vote_id = (int) end($parts);
    
    if ($vote_id <= 0) {
        return [
            'success' => false,
            'message' => 'Invalid vote ID in code.'
        ];
    }

    return [
        'success' => true,
        'vote_id' => $vote_id,
        'random_part' => $parts[0]
    ];
}

/**
 * Retrieve vote record from database
 * Try by ID first, then by voting code hash as fallback
 *
 * @param int $vote_id
 * @param string $voting_code Optional: voting code to search by hash
 * @return array
 */
private function retrieve_vote_record($vote_id, $voting_code = null)
{
    try {
        // Try to find by ID first
        $vote = DemoVote::find($vote_id);

        if ($vote) {
            return [
                'success' => true,
                'vote' => $vote
            ];
        }

        // Fallback: If vote not found by ID, try searching by voting code hash
        if ($voting_code) {
            Log::info('Vote not found by ID, searching by voting code hash', [
                'vote_id' => $vote_id,
                'voting_code_length' => strlen($voting_code),
                'voting_code_first_20_chars' => substr($voting_code, 0, 20)
            ]);

            // Search all votes where the voting code matches when hashed
            $allVotes = DemoVote::all();
            Log::info('Total votes in database', ['count' => count($allVotes)]);

            $hashMatches = 0;
            foreach ($allVotes as $candidateVote) {
                if (!empty($candidateVote->voting_code)) {
                    // Try to match the hash
                    try {
                        if (Hash::check($voting_code, $candidateVote->voting_code)) {
                            $hashMatches++;
                            Log::info('Vote found by voting code hash match', [
                                'vote_id' => $candidateVote->id,
                                'original_id_requested' => $vote_id,
                                'voting_code_column_length' => strlen($candidateVote->voting_code)
                            ]);

                            return [
                                'success' => true,
                                'vote' => $candidateVote
                            ];
                        }
                    } catch (\Exception $hashCheckError) {
                        Log::debug('Hash check failed for vote', [
                            'vote_id' => $candidateVote->id,
                            'error' => $hashCheckError->getMessage()
                        ]);
                    }
                }
            }

            Log::warning('No vote found by hash matching', [
                'vote_id_searched' => $vote_id,
                'total_votes_checked' => count($allVotes),
                'hash_matches_found' => $hashMatches,
                'submitted_code_length' => strlen($voting_code)
            ]);
        }

        return [
            'success' => false,
            'message' => 'Vote record not found in database. Please check your verification code.'
        ];

    } catch (\Exception $e) {
        Log::error('Failed to retrieve vote record', [
            'vote_id' => $vote_id,
            'error' => $e->getMessage()
        ]);

        return [
            'success' => false,
            'message' => 'Database error while retrieving vote.'
        ];
    }
}

/**
 * Retrieve demo vote record from database - returns same format as retrieve_vote_record
 * Try by ID first, then by voting code as fallback (plain text matching)
 *
 * @param string $voting_code
 * @return array
 */
private function retrieve_demo_vote_record($voting_code)
{
    try {
        // Extract vote_id from verification_code if possible (format: "private_key_vote_id")
        $demo_vote_id = null;
        if (strpos($voting_code, '_') !== false) {
            $parts = explode('_', $voting_code);
            $demo_vote_id = end($parts); // Get the last part (vote_id)
        }

        // Try to find by ID first
        if ($demo_vote_id) {
            $demoVote = DemoVote::find($demo_vote_id);

            if ($demoVote) {
                Log::info('Demo vote found by ID', [
                    'vote_id' => $demoVote->id,
                    'election_id' => $demoVote->election_id
                ]);

                return [
                    'success' => true,
                    'vote' => $demoVote
                ];
            }
        }

        // Fallback: If vote not found by ID, try searching by voting_code (exact match - plain for demo)
        Log::info('Demo vote not found by ID, searching by voting code', [
            'vote_id' => $demo_vote_id,
            'code_provided' => substr($voting_code, 0, 20) . '...'
        ]);

        $demoVote = DemoVote::where('voting_code', $voting_code)->first();

        if ($demoVote) {
            Log::info('Demo vote found by exact voting_code match', [
                'vote_id' => $demoVote->id,
                'election_id' => $demoVote->election_id
            ]);

            return [
                'success' => true,
                'vote' => $demoVote
            ];
        }

        // Fallback 2: Search through all demo votes for exact match
        Log::info('Demo vote not found by voting_code, searching all demo votes', [
            'code_provided' => substr($voting_code, 0, 20) . '...'
        ]);

        $allDemoVotes = DemoVote::all();
        Log::info('Total demo votes in database', ['count' => count($allDemoVotes)]);

        $exactMatches = 0;
        foreach ($allDemoVotes as $candidateDemoVote) {
            if (!empty($candidateDemoVote->voting_code) && $candidateDemoVote->voting_code === $voting_code) {
                $exactMatches++;
                Log::info('Demo vote found by exhaustive search', [
                    'vote_id' => $candidateDemoVote->id,
                    'election_id' => $candidateDemoVote->election_id
                ]);

                return [
                    'success' => true,
                    'vote' => $candidateDemoVote
                ];
            }
        }

        Log::warning('Demo vote not found by any method', [
            'vote_id_searched' => $demo_vote_id,
            'total_votes_checked' => count($allDemoVotes),
            'exact_matches_found' => $exactMatches,
            'submitted_code_length' => strlen($voting_code)
        ]);

        return [
            'success' => false,
            'message' => 'Demo vote record not found. Please check your verification code.'
        ];

    } catch (\Exception $e) {
        Log::error('Failed to retrieve demo vote record', [
            'error' => $e->getMessage()
        ]);

        return [
            'success' => false,
            'message' => 'Database error while retrieving demo vote.'
        ];
    }
}

/**
 * Prepare comprehensive vote data for display
 * 
 * @param object $vote
 * @param object $auth_user
 * @param string $verification_code
 * @return array
 */
private function prepare_vote_display_data($vote, $auth_user, $verification_code)
{
    // Get voter information (might be different from auth user if they're viewing someone else's vote)
    $voter_user = $vote->user;
    
    // Process vote candidates from JSON columns
    $vote_selections = $this->process_vote_selections($vote);
    
    // Determine if this is the current user's own vote
    $is_own_vote = $voter_user && $voter_user->id === $auth_user->id;
    
    return [
        'vote_id' => $vote->id,
        'verification_code' => $verification_code,
        'verification_timestamp' => now()->toISOString(),
        'verification_successful' => true,
        'is_own_vote' => $is_own_vote,
        'voter_info' => [
            'name' => $voter_user->name ?? 'Unknown Voter',
            'user_id' => $voter_user->user_id ?? 'N/A',
            'region' => $voter_user->region ?? 'N/A',
        ],
        'vote_info' => [
            'voted_at' => $vote->created_at ? $vote->created_at->format('M j, Y \a\t g:i A') : 'Unknown',
            'abstained_from_posts' => $vote->no_vote_posts ?? [],
            'vote_hash' => substr($vote->vote_hash ?? '', 0, 8) . '...',
        ],
        'vote_selections' => $vote_selections,
        'summary' => [
            'total_positions' => count($vote_selections),
            'positions_voted' => count(array_filter($vote_selections, function($selection) {
                return !empty($selection['candidates']) || !empty($selection['no_vote']);
            })),
            'candidates_selected' => array_sum(array_map(function($selection) {
                return count($selection['candidates'] ?? []);
            }, $vote_selections))
        ]
    ];
}

/**
 * Process vote selections from database JSON columns
 * 
 * @param object $vote
 * @return array
 */
private function process_vote_selections($vote)
{
    $selections = [];
    
    // Process all candidate columns (candidate_01, candidate_02, etc.)
    for ($i = 1; $i <= 30; $i++) {
        $column_name = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
        
        if (isset($vote->$column_name) && !empty($vote->$column_name)) {
            $selection_data = json_decode($vote->$column_name, true);
            
            if ($selection_data && is_array($selection_data)) {
                // Enrich candidate data with additional information
                $enriched_selection = $this->enrich_selection_data($selection_data);
                
                if ($enriched_selection) {
                    $selections[] = $enriched_selection;
                }
            }
        }
    }
    
    return $selections;
}

/**
 * Enrich selection data with candidate and post information
 * Get candidate names from User table through relationship
 * 
 * @param array $selection_data
 * @return array|null
 */
private function enrich_selection_data($selection_data)
{
    try {
        $enriched = [
            'post_id' => $selection_data['post_id'] ?? 'Unknown',
            'post_name' => $selection_data['post_name'] ?? 'Unknown Position',
            'post_nepali_name' => $selection_data['post_nepali_name'] ?? '',
            'no_vote' => $selection_data['no_vote'] ?? false,
            'candidates' => []
        ];

        // If no vote was selected, return early
        if ($enriched['no_vote'] || empty($selection_data['candidates'])) {
            return $enriched;
        }

        // Enrich candidate information
        foreach ($selection_data['candidates'] as $candidate_data) {
            $candidacy_id = $candidate_data['candidacy_id'] ?? null;
            
            if ($candidacy_id) {
                // Load candidacy WITH user relationship
                $candidacy = Candidacy::with('user')
                    ->where('candidacy_id', $candidacy_id)
                    ->first();
                
                if ($candidacy) {
                    // Get candidate name from User table through relationship
                    $candidate_name = $this->getCandidateNameFromCandidacy($candidacy);
                    
                    $enriched['candidates'][] = [
                        'candidacy_id' => $candidacy->candidacy_id,
                        'candidacy_name' => $candidate_name,  // ✅ FROM USER TABLE
                        'proposer_name' => $candidacy->proposer_name,
                        'supporter_name' => $candidacy->supporter_name,
                        'image_path_1' => $candidacy->image_path_1,
                        'user_info' => [
                            'id' => $candidacy->user->id ?? null,
                            'name' => $candidacy->user->name ?? 'Unknown',
                            'user_id' => $candidacy->user->user_id ?? 'N/A',
                            'region' => $candidacy->user->region ?? 'N/A',
                        ]
                    ];
                } else {
                    // Fallback if candidacy not found in database
                    $enriched['candidates'][] = [
                        'candidacy_id' => $candidacy_id,
                        'candidacy_name' => 'Candidate ' . str_replace(['_', '-'], ' ', $candidacy_id),
                        'proposer_name' => 'Unknown',
                        'supporter_name' => 'Unknown',
                        'image_path_1' => '',
                        'user_info' => [
                            'id' => null,
                            'name' => 'Unknown',
                            'user_id' => 'N/A',
                            'region' => 'N/A',
                        ]
                    ];
                }
            }
        }

        return $enriched;
        
    } catch (\Exception $e) {
        Log::warning('Failed to enrich selection data', [
            'selection_data' => $selection_data,
            'error' => $e->getMessage()
        ]);
        
        // Return basic structure with available data
        return [
            'post_id' => $selection_data['post_id'] ?? 'Unknown',
            'post_name' => $selection_data['post_name'] ?? 'Unknown Position',
            'post_nepali_name' => $selection_data['post_nepali_name'] ?? '',
            'no_vote' => $selection_data['no_vote'] ?? false,
            'candidates' => []
        ];
    }
}

/**
 * Prepare unified vote display data for both demo and real elections
 * Works with both Vote and DemoVote objects
 *
 * @param object $vote Vote or DemoVote object
 * @param object $auth_user
 * @param string $verification_code
 * @param string $election_type 'demo' or 'real'
 * @return array
 */
private function prepare_unified_vote_display($vote, $auth_user, $verification_code, $election_type)
{
    try {
        $is_demo = $election_type === 'demo';

        // Get voter information
        if ($is_demo) {
            // Demo votes store basic voter info
            $voter_info = [
                'name' => $auth_user->name ?? 'Demo Voter',
                'user_id' => $auth_user->user_id ?? 'DEMO',
                'region' => $auth_user->region ?? 'Demo Region',
            ];
            $is_own_vote = true; // Demo votes are always the current user's
            $no_vote_option = $vote->no_vote ?? false;
            $voted_at = $vote->created_at ? $vote->created_at->format('M j, Y \a\t g:i A') : 'Unknown';
        } else {
            // Real votes have associated user
            $voter_user = $vote->user;
            $voter_info = [
                'name' => $voter_user->name ?? 'Unknown Voter',
                'user_id' => $voter_user->user_id ?? 'N/A',
                'region' => $voter_user->region ?? 'N/A',
            ];
            $is_own_vote = $voter_user && $voter_user->id === $auth_user->id;
            $no_vote_option = $vote->no_vote_option ?? false;
            $voted_at = $vote->created_at ? $vote->created_at->format('M j, Y \a\t g:i A') : 'Unknown';
        }

        // Process vote selections - both use same structure
        $vote_selections = $this->process_vote_selections($vote);

        // Get election info
        $election = Election::find($vote->election_id);

        return [
            'vote_id' => $vote->id,
            'verification_code' => $verification_code,
            'verification_timestamp' => now()->toISOString(),
            'verification_successful' => true,
            'is_own_vote' => $is_own_vote,
            'election_type' => $election_type,
            'voter_info' => $voter_info,
            'vote_info' => [
                'voted_at' => $voted_at,
                'no_vote_option' => $no_vote_option,
                'voting_code_used' => $vote->voting_code ?? 'N/A',
            ],
            'vote_selections' => $vote_selections,
            'summary' => [
                'total_positions' => count($vote_selections),
                'positions_voted' => count(array_filter($vote_selections, function($selection) {
                    return !empty($selection['candidates']) || $selection['no_vote'];
                })),
                'candidates_selected' => array_sum(array_map(function($selection) {
                    return count($selection['candidates'] ?? []);
                }, $vote_selections)),
                'election_id' => $election->id ?? null,
                'election_name' => $election->name ?? 'Unknown Election'
            ]
        ];

    } catch (\Exception $e) {
        Log::error('Failed to prepare unified vote display', [
            'vote_id' => $vote->id,
            'election_type' => $election_type,
            'error' => $e->getMessage()
        ]);

        throw $e;
    }
}

/**
 * Get candidate name from Candidacy model using User relationship
 * 
 * @param \App\Models\Candidacy $candidacy
 * @return string
 */
private function getCandidateNameFromCandidacy($candidacy)
{
    // Priority 1: Get name from related User
    if ($candidacy->user && !empty($candidacy->user->name)) {
        return $candidacy->user->name;
    }
    
    // Priority 2: Construct from first_name + last_name if available
    if ($candidacy->user && (!empty($candidacy->user->first_name) || !empty($candidacy->user->last_name))) {
        $fullName = trim(($candidacy->user->first_name ?? '') . ' ' . ($candidacy->user->last_name ?? ''));
        if (!empty($fullName)) {
            return $fullName;
        }
    }
    
    // Priority 3: Use user_name field from candidacy table (backup)
    if (!empty($candidacy->user_name)) {
        return $candidacy->user_name;
    }
    
  
    
    // Priority 5: Generate from candidacy_id
    if (!empty($candidacy->candidacy_id)) {
        return 'Candidate ' . str_replace(['_', '-'], ' ', $candidacy->candidacy_id);
    }
    
    return 'Unknown Candidate';
}

public function show($vote_id)
{
    try {
        $sessionKey = 'vote_display_data_' . $vote_id;
        $voteDisplayData = session()->get($sessionKey);

        if (!$voteDisplayData) {
            return redirect()->route('vote.verify_to_show')
                ->withErrors(['session' => 'No vote data found. Please verify your code again.']);
        }

        if (!$this->isValidVoteDisplayData($voteDisplayData)) {
            session()->forget($sessionKey);
            return redirect()->route('vote.verify_to_show')
                ->withErrors(['data' => 'Invalid vote data. Please verify your code again.']);
        }

        // No user logging, no user info passed
        return Inertia::render('Vote/VoteShow', [
            'vote_data' => $voteDisplayData,
        ]);
    } catch (\Throwable $e) {
        Log::error('Vote show page error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()->route('vote.verify_to_show')
            ->withErrors(['system' => 'An error occurred while displaying the vote. Please try again.']);
    }
}


/**
 * Validate the structure of vote display data.
 *
 * @param mixed $data
 * @return bool
 */
private function isValidVoteDisplayData($data)
{
    if (!is_array($data)) {
        return false;
    }

    $requiredKeys = [
        'vote_id',
        'verification_successful',
        'voter_info',
        'vote_info',
        'vote_selections',
    ];

    foreach ($requiredKeys as $key) {
        if (!array_key_exists($key, $data)) {
            return false;
        }
    }

    return true;
}

/**
 * Verify submitted voting code against stored hashed code
 * 
 * @param string $in_code The hashed code stored in database (e.g., $code->code1)
 * @param string $submitted_code The code submitted by user from form (e.g., $request['voting_code'])
 * @return bool True if codes match, false otherwise
 */
public function verify_submitted_code($in_code, $submitted_code)
{
    // Input validation
    if (empty($in_code) || empty($submitted_code)) {
        \Log::warning('Code verification failed: empty parameters', [
            'in_code_empty' => empty($in_code),
            'submitted_code_empty' => empty($submitted_code),
            'user_id' => auth()->id() ?? 'unknown',
        ]);
        return false;
    }

    // Clean and format submitted code (uppercase, trim whitespace)
    $clean_submitted_code = strtoupper(trim($submitted_code));
    
    // Log verification attempt for audit trail
    \Log::info('Code verification attempt', [
        'user_id' => auth()->id() ?? 'unknown',
        'submitted_code_length' => strlen($clean_submitted_code),
        'submitted_code_format' => ctype_alnum($clean_submitted_code) ? 'valid_format' : 'invalid_format',
        'has_stored_hash' => !empty($in_code),
        'attempted_at' => now(),
    ]);

    try {
        // Clean and format both codes for comparison
        $clean_in_code = strtoupper(trim($in_code));

        // Simple plain text comparison for code1 (always plain text)
        $verification_result = ($clean_submitted_code === $clean_in_code);

        \Log::info('Verifying plain text code', [
            'user_id' => auth()->id() ?? 'unknown',
            'method' => 'plain_text',
            'expected_code' => $clean_in_code,
            'submitted_code' => $clean_submitted_code,
            'expected_length' => strlen($clean_in_code),
            'submitted_length' => strlen($clean_submitted_code),
        ]);

        // Log the result for audit trail
        if ($verification_result) {
            \Log::info('✅ Code verification successful', [
                'user_id' => auth()->id() ?? 'unknown',
                'verified_at' => now(),
                'method' => 'plain_text',
            ]);
        } else {
            \Log::warning('❌ Code verification failed - Mismatch', [
                'user_id' => auth()->id() ?? 'unknown',
                'expected_code' => $clean_in_code,
                'submitted_code' => $clean_submitted_code,
                'submitted_code_length' => strlen($clean_submitted_code),
                'failed_at' => now(),
                'method' => 'plain_text',
            ]);
        }

        return $verification_result;
        
    } catch (\Exception $e) {
        // Handle any unexpected errors during verification
        \Log::error('Code verification error', [
            'user_id' => auth()->id() ?? 'unknown',
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
        ]);
        
        return false;
    }
}



}//end of the controller 
