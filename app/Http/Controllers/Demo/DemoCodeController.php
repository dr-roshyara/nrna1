<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\DemoCode;
use App\Models\Election;
use App\Services\VoterProgressService;
use App\Services\VoterStepTrackingService;
use App\Services\VotingServiceFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\SendFirstVerificationCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/**
 * Demo CodeController - 1:1 Mirror of CodeController for Demo Elections
 *
 * This is an EXACT COPY of CodeController with these changes ONLY:
 * - All Code:: replaced with DemoCode::
 * - All $user->can_vote checks removed (allow all users)
 * - Re-voting logic added in getOrCreateCode()
 * - Demo-specific logging added
 *
 * Core Principle: can_vote_now field is the single source of truth
 * - can_vote_now = 0: User needs to verify code
 * - can_vote_now = 1: User verified, can proceed to agreement
 */
class DemoCodeController extends Controller
{
    private $clientIP;
    private $maxUseClientIP;
    private $votingTimeInMinutes;

    public function __construct()
    {
        $this->clientIP = \Request::getClientIp(true);
        $this->maxUseClientIP = config('app.max_use_clientIP', 7);
        $this->votingTimeInMinutes = 30; // 30 minutes voting window (matches voter slug expiry)
    }

    /**
     * STEP 1: Show code entry form
     * Route: GET /v/{slug}/demo-code/create
     *
     * Election context is set by ElectionMiddleware:
     * - Defaults to first REAL active election
     * - Or uses election_id from session (if demo/test)
     */
    public function create(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        // Set organisation context for tenant scoping
        // This ensures DemoCode queries respect the organisation_id filter
        session(['current_organisation_id' => $election->organisation_id]);

        // Check if code is already verified (should not be accessing create page)
        $existingCode = DemoCode::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();

        Log::info('🎮 [DEMO-CREATE] Code create page accessed', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
            'organisation_id' => $election->organisation_id,
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'is_slug_request' => $voterSlug !== null,
            'existing_code_verified' => $existingCode ? $existingCode->can_vote_now : 'no_code',
        ]);

        // ⚠️ If code is already verified, user should not be here!
        if ($existingCode && $existingCode->can_vote_now == 1) {
            Log::warning('⚠️ [DEMO-CREATE] User already has verified code - should be on agreement page!', [
                'user_id' => $user->id,
                'code_id' => $existingCode->id,
            ]);
        }

        // ⛔ DEMO: Allow re-voting (no voting restriction for demo)
        // Unlike real elections, demo allows unlimited revoting

        // Get or create code record for this election
        $code = $this->getOrCreateCode($user, $election);

        // ✅ CRITICAL: Reset voter slug step for demo re-voting
        // For demo elections, allow users to vote multiple times by resetting the step
        if ($voterSlug && $election->type === 'demo') {
            $oldStep = $voterSlug->current_step;
            $voterSlug->current_step = 1; // Reset to step 1 (code entry)
            $voterSlug->save();

            Log::info('🔄 [DEMO] Reset voter slug step for demo re-voting', [
                'voter_slug_id' => $voterSlug->id,
                'old_step' => $oldStep,
                'new_step' => $voterSlug->current_step,
                'user_id' => $user->id,
                'election_id' => $election->id,
            ]);
        }

        // ✅ CHECK IF CODE HAS EXPIRED - IF YES, SEND NEW ONE
        $minutesSinceSent = $code->code1_sent_at ? now()->diffInMinutes($code->code1_sent_at) : 0;

        if ($minutesSinceSent >= $this->votingTimeInMinutes && $code->has_code1_sent) {
            Log::info('🔄 [DEMO] Code expired - sending new code', [
                'user_id' => $user->id,
                'minutes_since_sent' => $minutesSinceSent,
                'max_minutes' => $this->votingTimeInMinutes,
            ]);

            // Generate new code and reset timer
            $code->code1 = Str::random(6);
            $code->code1_sent_at = now();
            $code->has_code1_sent = true;
            $code->save();

            // Send new code notification
            try {
                Log::info('📧 [DEMO] Attempting to send new verification code after expiry', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'code' => $code->code1,
                    'previous_sent_at' => $code->code1_sent_at,
                ]);

                $user->notify(new SendFirstVerificationCode($user, $code->code1));

                Log::info('✅ [DEMO] New verification code sent after expiry', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'code' => $code->code1,
                ]);
            } catch (\Exception $e) {
                Log::error('❌ [DEMO] Failed to send new code after expiry', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                    'exception_class' => get_class($e),
                ]);
            }

            // Reset duration counter since we just sent a new code
            $minutesSinceSent = 0;
        }

        // For API requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'step' => 1,
                'user_name' => $user->name,
                'code_sent' => $code->has_code1_sent,
                'voting_time_minutes' => $this->votingTimeInMinutes,
            ]);
        }

        // Check if email is valid
        $hasValidEmail = $user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL);

        return Inertia::render('Code/DemoCode/Create', [
            'name' => $user->name,
            'user_id' => $user->user_id ?? '',
            'state' => 'code_sent',
            'code_duration' => $minutesSinceSent,
            'code_expires_in' => $this->votingTimeInMinutes, // expires after voting window
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'has_valid_email' => $hasValidEmail,
            'show_code_fallback' => !$hasValidEmail, // Show code on page if email can't be sent
            'verification_code' => !$hasValidEmail ? $code->code1 : null, // Only show if no email
            'election_type' => 'demo',
        ]);
    }

    /**
     * STEP 1 → STEP 2: Process code submission
     * Route: POST /v/{slug}/demo-code
     *
     * Verifies code for the selected election
     */
    public function store(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('🎮 [DEMO-CODE] Code verification started', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'input' => $request->only('voting_code'),
            'is_ajax' => $request->wantsJson(),
        ]);

        // Validate input
        try {
            $request->validate([
                'voting_code' => 'required|string|size:6'
            ], [
                'voting_code.required' => 'Please enter the verification code.',
                'voting_code.size' => 'Code must be exactly 6 characters.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->handleValidationError($e, $request);
        }

        $submittedCode = trim(strtoupper($request->input('voting_code')));

        // Get code record for this election
        $code = DemoCode::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();
        if (!$code) {
            return back()->withErrors(['voting_code' => 'No verification code found. Please request a new code.']);
        }

        // Check if already verified
        if ($code->can_vote_now == 1) {
            return $this->handleAlreadyVerified($request, $voterSlug);
        }

        // Verify the code
        $verificationResult = $this->verifyCode($code, $submittedCode, $user);

        if (!$verificationResult['success']) {
            return back()->withErrors(['voting_code' => $verificationResult['message']])->withInput();
        }

        // Code verified successfully - update database
        Log::info('🔵 [DEMO-STORE] About to call markCodeAsVerified', [
            'code_id' => $code->id,
            'before_can_vote_now' => $code->can_vote_now,
        ]);

        $this->markCodeAsVerified($code);

        // Verify the update in database
        $freshCode = $code->fresh();
        Log::info('🟢 [DEMO-STORE] markCodeAsVerified completed', [
            'code_id' => $code->id,
            'after_can_vote_now' => $freshCode->can_vote_now,
            'is_code1_usable' => $freshCode->is_code1_usable,
        ]);

        // ✅ NEW: Record step completion in voter_slug_steps table
        if ($voterSlug) {
            Log::info('🔵 [DEMO-STORE] About to record step 1', [
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $election->id,
                'voter_slug_type' => get_class($voterSlug),
            ]);

            try {
                $stepTrackingService = new VoterStepTrackingService();
                $stepTrackingService->completeStep(
                    $voterSlug,
                    $election,
                    1, // Step 1: Code verification
                    ['code_verified' => true, 'verified_at' => now()->toIso8601String()]
                );
                Log::info('✅ [DEMO-CODE] Step 1 recorded in voter_slug_steps', [
                    'voter_slug_id' => $voterSlug->id,
                    'election_id' => $election->id,
                ]);
            } catch (\Exception $e) {
                Log::error('❌ [DEMO-STORE] Failed to record step 1', [
                    'voter_slug_id' => $voterSlug->id,
                    'election_id' => $election->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        } else {
            Log::warning('⚠️ [DEMO-STORE] No voter_slug in request', [
                'user_id' => $user->id,
            ]);
        }

        // Legacy: Advance slug step if using slug-based voting
        if ($voterSlug) {
            $this->advanceSlugStep($voterSlug);
        }

        Log::info('🎮 [DEMO-CODE] Code verification successful', [
            'user_id' => $user->id,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        // Redirect to agreement page
        $agreementUrl = $voterSlug
            ? route('slug.demo-code.agreement', ['vslug' => $voterSlug->slug])
            : route('demo-code.agreement');

        Log::info('🟡 [DEMO-STORE] About to return redirect', [
            'agreementUrl' => $agreementUrl,
            'voterSlug' => $voterSlug ? $voterSlug->slug : 'null',
            'is_response_object' => is_object(redirect($agreementUrl)),
        ]);

        // Always return Redirect for form submissions (Inertia will follow it)
        $redirectResponse = redirect($agreementUrl)->with('success', 'Code verified successfully!');

        Log::info('✅ [DEMO-STORE] Returning redirect response', [
            'response_status_code' => $redirectResponse->getStatusCode(),
            'response_location' => $redirectResponse->getTargetUrl(),
        ]);

        return $redirectResponse;
    }

    /**
     * STEP 2: Show agreement page
     * Route: GET /v/{slug}/demo-code/agreement
     *
     * User reads and accepts voting agreement
     * Can only proceed if code was verified in this election
     */
    public function showAgreement(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('🎮 [DEMO-CODE] Agreement page accessed', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        // Verify user has completed code verification for this election
        $code = DemoCode::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();
        if (!$code || $code->can_vote_now != 1) {
            $redirectUrl = $voterSlug
                ? route('slug.demo-code.create', ['vslug' => $voterSlug->slug])
                : route('demo-code.create');

            return redirect($redirectUrl)
                ->with('error', 'Code verification required before proceeding.');
        }

        // Check if agreement already accepted
        if ($code->has_agreed_to_vote) {
            Log::info('🔵 [DEMO-showAgreement] Detected agreement already accepted', [
                'user_id' => $user->id,
                'has_agreed_to_vote' => $code->has_agreed_to_vote,
            ]);

            // CRITICAL FIX: Record Step 2 if not already recorded
            // This breaks the circular redirect loop
            if ($voterSlug) {
                try {
                    $stepTracker = new VoterStepTrackingService();
                    $highestStep = $stepTracker->getHighestCompletedStep($voterSlug, $election);

                    Log::info('🔵 [DEMO-showAgreement] Current highest step', [
                        'highest_step' => $highestStep,
                    ]);

                    // If Step 2 not recorded yet, record it now
                    if ($highestStep < 2) {
                        Log::info('🔵 [DEMO-showAgreement] Step 2 not recorded - recording now to break loop', [
                            'voter_slug_id' => $voterSlug->id,
                        ]);

                        $stepTracker->completeStep(
                            $voterSlug,
                            $election,
                            2,
                            ['agreement_accepted' => true, 'auto_recorded' => true, 'recorded_at' => now()->toIso8601String()]
                        );

                        Log::info('✅ [DEMO-showAgreement] Step 2 auto-recorded to break circular redirect', [
                            'voter_slug_id' => $voterSlug->id,
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('❌ [DEMO-showAgreement] Failed to record step 2', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $voteUrl = $voterSlug
                ? route('slug.demo-vote.create', ['vslug' => $voterSlug->slug])
                : route('demo-vote.create');

            return redirect($voteUrl)
                ->with('info', 'You have already accepted the agreement.');
        }

        // For API requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'step' => 2,
                'user_name' => $user->name,
                'voting_time_minutes' => $code->voting_time_in_minutes ?? 20,
            ]);
        }

        return Inertia::render('Code/DemoCode/Agreement', [
            'user_name' => $user->name,
            'voting_time_minutes' => $code->voting_time_in_minutes ?? 20,
            'agreement_text_nepali' => 'म यो अनलाइन मतदान प्रणालीमा स्वेच्छाले भाग लिइरहेको छु र मेरो मत गोप्य राखिनेछ भन्ने कुरामा सहमत छु।',
            'agreement_text_english' => 'I voluntarily participate in this online voting system and agree that my vote will remain secret and secure.',
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'is_demo' => true,
        ]);
    }

    /**
     * STEP 2 → STEP 3: Process agreement submission
     * Route: POST /v/{slug}/demo-code/agreement
     *
     * User confirms agreement and can proceed to vote
     */
    public function submitAgreement(Request $request)
    {
        // 🔴 CRITICAL DEBUG - Capture slug context at entry
        \Log::info('🔴 [CRITICAL] submitAgreement ENTRY', [
            'route_name' => $request->route()->getName(),
            'route_params' => $request->route()->parameters(),
            'vslug_from_route' => $request->route('vslug'),
            'url' => $request->url(),
            'method' => $request->method(),
            'session_id' => session()->getId(),
        ]);

        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        \Log::info('🔴 [CRITICAL] After getUser and getElection', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'has_voter_slug' => $voterSlug ? 'yes' : 'no',
            'voter_slug_slug' => $voterSlug ? $voterSlug->slug : null,
            'voter_slug_id' => $voterSlug ? $voterSlug->id : null,
        ]);

        Log::info('🎮 [DEMO-CODE] Agreement submission started', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        // Validate agreement
        $request->validate([
            'agreement' => 'required|accepted'
        ], [
            'agreement.required' => 'You must accept the terms and conditions.',
            'agreement.accepted' => 'You must accept the terms and conditions.',
        ]);

        // Verify user has completed code verification for this election
        $code = DemoCode::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();
        if (!$code || $code->can_vote_now != 1) {
            return $this->jsonOrRedirect($request, false, 'Code verification required.',
                redirect()->route('slug.demo-code.create', ['vslug' => $voterSlug->slug]));
        }

        // Mark agreement as accepted
        $code->update([
            'has_agreed_to_vote' => 1,
            'has_agreed_to_vote_at' => now(),
            'voting_started_at' => now(),
        ]);

        // ✅ NEW: Record step 2 completion in voter_slug_steps table
        if ($voterSlug) {
            Log::info('🔵 [DEMO-SUBMIT_AGREEMENT] About to record step 2', [
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $election->id,
            ]);

            try {
                $stepTrackingService = new VoterStepTrackingService();
                $stepTrackingService->completeStep(
                    $voterSlug,
                    $election,
                    2, // Step 2: Agreement acceptance
                    ['agreement_accepted' => true, 'accepted_at' => now()->toIso8601String()]
                );
                Log::info('✅ [DEMO-CODE] Step 2 recorded in voter_slug_steps', [
                    'voter_slug_id' => $voterSlug->id,
                    'election_id' => $election->id,
                ]);
            } catch (\Exception $e) {
                Log::error('❌ [DEMO-SUBMIT_AGREEMENT] Failed to record step 2', [
                    'voter_slug_id' => $voterSlug->id,
                    'election_id' => $election->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            // Legacy: Advance slug step (deprecated, but keep for backward compatibility)
            $progressService = new VoterProgressService();
            $progressService->advanceFrom($voterSlug, 'slug.demo-code.agreement', ['agreement_accepted' => true]);
        }

        Log::info('🎮 [DEMO-CODE] Agreement accepted successfully', [
            'user_id' => $user->id,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        // Redirect to voting page
        $voteUrl = $voterSlug
            ? route('slug.demo-vote.create', ['vslug' => $voterSlug->slug])
            : route('demo-vote.create');

        \Log::info('🟢 [CRITICAL] submitAgreement REDIRECT DECISION', [
            'has_voter_slug' => $voterSlug ? 'yes' : 'no',
            'voter_slug_slug' => $voterSlug ? $voterSlug->slug : null,
            'route_used' => $voterSlug ? 'slug.demo-vote.create' : 'demo-vote.create',
            'voteUrl' => $voteUrl,
            'request_is_json' => $request->expectsJson(),
        ]);

        return $this->jsonOrRedirect($request, true, 'Agreement accepted successfully!',
            redirect($voteUrl)->with('success', 'Agreement accepted. You may now cast your demo vote.'), $voteUrl);
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

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
     * Get election from middleware or default to demo election
     * The ElectionMiddleware ensures an election is always set
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Election
     */
    /**
     * Get election with PRIORITY-BASED selection:
     *
     * PRIORITY 1: Election from middleware (if valid for user's org)
     * PRIORITY 2: Demo election with user's organisation_id (if exists)
     * PRIORITY 3: Platform-wide demo (organisation_id = null)
     *
     * Only fails if NO demo election exists at all
     */
    private function getElection(Request $request): Election
    {
        $user = $this->getUser($request);

        \Log::info('🎯 [DemoCodeController] Selecting demo election', [
            'user_id' => $user->id,
            'user_org_id' => $user->organisation_id,
            'has_middleware_election' => $request->attributes->has('election'),
        ]);

        // Priority 1: Use election from middleware if it exists AND is valid
        $election = $request->attributes->get('election');

        if ($election) {
            // Verify the election belongs to user's organisation
            if ($user->organisation_id === $election->organisation_id) {
                \Log::info('✅ Using election from middleware', [
                    'user_id' => $user->id,
                    'user_org_id' => $user->organisation_id,
                    'election_id' => $election->id,
                    'election_org_id' => $election->organisation_id,
                ]);
                return $election;
            }

            \Log::warning('⚠️ Election from middleware has wrong org, will find correct one', [
                'user_id' => $user->id,
                'user_org_id' => $user->organisation_id,
                'election_id' => $election->id,
                'election_org_id' => $election->organisation_id,
            ]);
            // Fall through to find correct election
        }

        // Priority 2 & 3: Find appropriate demo election
        $query = Election::withoutGlobalScopes()->where('type', 'demo');

        if ($user->organisation_id !== null) {
            // 👥 USER HAS ORGANISATION - Try to find org-specific demo first
            $orgDemo = (clone $query)->where('organisation_id', $user->organisation_id)->first();

            if ($orgDemo) {
                \Log::info('✅ Found org-specific demo election', [
                    'user_id' => $user->id,
                    'user_org_id' => $user->organisation_id,
                    'election_id' => $orgDemo->id,
                ]);
                return $orgDemo;
            }

            // No org-specific demo found - fall back to platform demo
            \Log::info('⚠️ No org-specific demo found, will try platform demo', [
                'user_id' => $user->id,
                'user_org_id' => $user->organisation_id,
            ]);
        }

        // Priority 3: Platform-wide demo (organisation_id = null)
        $platformDemo = (clone $query)->whereNull('organisation_id')->first();

        if ($platformDemo) {
            \Log::info('✅ Using platform-wide demo election', [
                'user_id' => $user->id,
                'user_org_id' => $user->organisation_id ?? 'null',
                'election_id' => $platformDemo->id,
            ]);
            return $platformDemo;
        }

        // ❌ NO DEMO ELECTIONS EXIST AT ALL
        \Log::error('❌ No demo elections found in database', [
            'user_id' => $user->id,
            'user_org_id' => $user->organisation_id,
        ]);

        throw new \Exception('No demo election available. Please create a demo election first.');
    }

    /**
     * Check eligibility for demo elections
     * DEMO: All authenticated users are eligible (no can_vote check)
     */
    private function isUserEligible(User $user): bool
    {
        // DEMO: All authenticated users are eligible
        return true;  // ← REMOVED can_vote check
    }

    /**
     * Get or create verification code for user and election
     * Now election-scoped: one code per user per election
     *
     * @param \App\Models\User $user
     * @param \App\Models\Election $election
     * @return \App\Models\DemoCode
     */
    private function getOrCreateCode(User $user, Election $election): DemoCode
    {
        // Get code for this specific election
        // CRITICAL: Use withoutGlobalScopes() because demo elections have organisation_id=NULL
        // The global scope would filter out codes for demo elections
        $code = DemoCode::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();

        // DEMO ELECTIONS: Allow re-voting by resetting flags
        if ($code && $code->has_voted && $election->type === 'demo') {
            Log::info('🔄 [DEMO] Demo election - resetting code for re-voting', [
                'user_id' => $user->id,
                'code_id' => $code->id,
                'old_has_voted' => $code->has_voted,
                'old_organisation_id' => $code->organisation_id,
            ]);

            // Reset voting flags for demo to allow new vote
            $code->update([
                'organisation_id' => $election->organisation_id,  // Ensure organisation_id is set
                'has_voted' => false,
                'vote_submitted' => false,
                'can_vote_now' => 0,
                'is_code1_usable' => 1,
                'code1' => $this->generateCode(),
                'code1_sent_at' => now(),
                'has_code1_sent' => 1,
                'code1_used_at' => null,
                'code2_used_at' => null,
                'is_code2_usable' => 1,
            ]);

            \Log::info('✅ [DEMO] DemoCode reset for re-voting with organisation_id confirmed', [
                'code_id' => $code->id,
                'user_id' => $user->id,
                'election_id' => $election->id,
                'organisation_id' => $code->organisation_id,
            ]);

            // Send new code via email
            if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    $user->notify(new SendFirstVerificationCode($user, $code->code1));
                    Log::info('✅ [DEMO] New demo voting code sent', [
                        'user_id' => $user->id,
                        'code_id' => $code->id,
                        'code' => $code->code1,
                    ]);
                } catch (\Exception $e) {
                    Log::error('[DEMO] Failed to send demo voting code', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return $code;
        }

        if (!$code) {
            // No code exists for this election - create new one
            // CRITICAL: Set organisation_id explicitly
            // - Demo elections (type='demo'): organisation_id=NULL (MODE 1) or org_id (MODE 2)
            // - Real elections (type='real'): organisation_id from election
            $code = DemoCode::create([
                'user_id' => $user->id,
                'election_id' => $election->id,
                'organisation_id' => $election->organisation_id,  // ✅ EXPLICIT
                'code1' => $this->generateCode(),
                'code1_sent_at' => now(),
                'has_code1_sent' => 1,
                'client_ip' => $this->clientIP,
                'voting_time_in_minutes' => $this->votingTimeInMinutes,
                'is_code1_usable' => 1,
                'can_vote_now' => 0,
            ]);

            \Log::info('✅ [DEMO] New DemoCode created with organisation_id', [
                'code_id' => $code->id,
                'user_id' => $user->id,
                'election_id' => $election->id,
                'organisation_id' => $code->organisation_id,
            ]);

            // Send code via email only if user has valid email
            if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Log::info('[DEMO] 📧 Attempting to send verification code email', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'code' => $code->code1,
                        'mailer' => config('mail.default'),
                    ]);

                    $user->notify(new SendFirstVerificationCode($user, $code->code1));

                    Log::info('[DEMO] ✅ Verification code email sent successfully', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'code' => $code->code1,
                    ]);
                } catch (\Exception $e) {
                    Log::error('[DEMO] ❌ Failed to send verification code email', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'code' => $code->code1,
                        'error' => $e->getMessage(),
                        'exception_class' => get_class($e),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            } else {
                Log::warning('[DEMO] ⚠️ User does not have valid email for verification code', [
                    'user_id' => $user->id,
                    'email' => $user->email ?? 'null',
                    'email_filter_result' => filter_var($user->email ?? '', FILTER_VALIDATE_EMAIL),
                ]);
            }

            Log::info('[DEMO] New verification code created and sent', [
                'user_id' => $user->id,
                'code_id' => $code->id,
                'code' => $code->code1,
            ]);
        } else {
            // ✅ CRITICAL: If code already verified, DO NOT regenerate
            // Code was successfully verified and user should go to agreement page
            if ($code->can_vote_now == 1) {
                Log::info('[DEMO] Code already verified - returning existing code', [
                    'user_id' => $user->id,
                    'election_id' => $election->id,
                    'code_id' => $code->id,
                ]);
                return $code; // Return without regenerating
            }

            // Code exists - check if it needs resending
            $isExpired = $code->code1_sent_at && now()->diffInMinutes($code->code1_sent_at) > $this->votingTimeInMinutes;
            $codeWasUsed = $code->is_code1_usable == 0;
            $notYetVoted = !$code->has_voted;
            $voteNotSubmitted = !$code->vote_submitted;

            // Resend code ONLY if:
            // - Code is expired AND not yet used AND not voted
            //
            // DO NOT resend if code was already used/verified ($codeWasUsed = true)
            // because user should be redirected to agreement page, not given new code
            $shouldResend = ($isExpired && !$codeWasUsed && $notYetVoted);

            if ($shouldResend) {
                // Generate and send new code
                $newCode = $this->generateCode();

                $code->update([
                    'code1' => $newCode,
                    'code1_sent_at' => now(),
                    'has_code1_sent' => 1,
                    'is_code1_usable' => 1,
                    'can_vote_now' => 0,
                    'vote_submitted' => 0, // Reset submission status
                ]);

                // Send new code via email only if user has valid email
                if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        $user->notify(new SendFirstVerificationCode($user, $newCode));
                    } catch (\Exception $e) {
                        Log::error('[DEMO] Failed to resend verification code email', [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'error' => $e->getMessage(),
                        ]);
                    }
                } else {
                    Log::warning('[DEMO] User does not have valid email for verification code resend', [
                        'user_id' => $user->id,
                        'email' => $user->email ?? 'null',
                    ]);
                }

                Log::info('[DEMO] Code regenerated and resent', [
                    'user_id' => $user->id,
                    'code_id' => $code->id,
                    'new_code' => $newCode,
                    'reason' => $isExpired ? 'expired' : 'restart_after_use',
                    'was_used' => $codeWasUsed,
                    'previous_sent_at' => $code->code1_sent_at,
                ]);
            }
        }

        return $code;
    }

    private function verifyCode(DemoCode $code, string $submittedCode, User $user): array
    {
        // Check if code is usable
        if (!$code->is_code1_usable) {
            return ['success' => false, 'message' => 'This verification code has already been used.'];
        }

        // Check if code matches
        if ($code->code1 !== $submittedCode) {
            Log::warning('[DEMO] Invalid code submission', [
                'user_id' => $user->id,
                'expected' => $code->code1,
                'submitted' => $submittedCode,
            ]);
            return ['success' => false, 'message' => 'Invalid verification code. Please check and try again.'];
        }

        // Check if code is expired (20 minutes)
        if ($code->code1_sent_at && now()->diffInMinutes($code->code1_sent_at) > $this->votingTimeInMinutes) {
            return ['success' => false, 'message' => 'Verification code has expired. Please request a new code.'];
        }

        // DEMO: No IP rate limiting (unlike real elections)
        // This allows testing from same machine

        return ['success' => true, 'message' => 'Code verified successfully.'];
    }

    private function markCodeAsVerified(DemoCode $code): void
    {
        Log::info('🔴 [DEMO-markCodeAsVerified] Starting', ['code_id' => $code->id]);

        try {
            // ✅ FIXED: Configurable code state based on voting system mode
            $updateData = [
                'can_vote_now' => 1,
                'code1_used_at' => now(),
                'is_codemodel_valid' => true,
                'client_ip' => $this->clientIP,
            ];

            // In SIMPLE MODE: Keep is_code1_usable = 1 so Code1 can be used again at vote submission
            // In STRICT MODE: Set is_code1_usable = 0 since Code1 is only used for form access
            if (config('voting.two_codes_system') == 1) {
                // STRICT MODE: Code1 is now exhausted for form access
                $updateData['is_code1_usable'] = 0;
            } else {
                // SIMPLE MODE: Code1 is still usable for vote submission (second use)
                $updateData['is_code1_usable'] = 1;
            }

            $updateResult = $code->update($updateData);

            Log::info('🟠 [DEMO-markCodeAsVerified] Update result', [
                'code_id' => $code->id,
                'update_result' => $updateResult,
                'can_vote_now' => $code->can_vote_now,
                'is_code1_usable' => $code->is_code1_usable,
                'mode' => config('voting.two_codes_system') == 1 ? 'STRICT' : 'SIMPLE',
            ]);
        } catch (\Exception $e) {
            Log::error('❌ [DEMO-markCodeAsVerified] EXCEPTION', [
                'code_id' => $code->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    private function advanceSlugStep($voterSlug): void
    {
        if ($voterSlug) {
            $progressService = new VoterProgressService();
            $progressService->advanceFrom($voterSlug, 'slug.demo-code.create', ['code_completed' => true]);
        }
    }

    private function handleAlreadyVerified(Request $request, $voterSlug)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);

        // ✅ CRITICAL: Record Step 1 if not already recorded
        // This fixes the case where user resubmits an already-verified code
        if ($voterSlug) {
            Log::info('🔵 [DEMO-handleAlreadyVerified] Recording Step 1 for already-verified code', [
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $election->id,
            ]);

            try {
                $stepTrackingService = new VoterStepTrackingService();
                $highestStep = $stepTrackingService->getHighestCompletedStep($voterSlug, $election);

                // Only record if Step 1 hasn't been recorded yet
                if ($highestStep < 1) {
                    $stepTrackingService->completeStep(
                        $voterSlug,
                        $election,
                        1,
                        ['code_verified' => true, 'verified_at' => now()->toIso8601String()]
                    );
                    Log::info('✅ [DEMO-CODE] Step 1 recorded for already-verified code', [
                        'voter_slug_id' => $voterSlug->id,
                        'election_id' => $election->id,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('❌ [DEMO-handleAlreadyVerified] Failed to record step 1', [
                    'voter_slug_id' => $voterSlug->id,
                    'election_id' => $election->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $agreementUrl = $voterSlug
            ? route('slug.demo-code.agreement', ['vslug' => $voterSlug->slug])
            : route('demo-code.agreement');

        // Already verified, just redirect to agreement
        return redirect($agreementUrl)->with('info', 'Code already verified. Continue to agreement.');
    }

    private function generateCode(): string
    {
        return strtoupper(Str::random(6));
    }

    private function redirectToDashboard(string $message)
    {
        return redirect()->route('dashboard')->with('error', $message);
    }

    private function handleValidationError(\Illuminate\Validation\ValidationException $e, Request $request)
    {
        Log::error('[DEMO] Validation failed', ['errors' => $e->errors()]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        throw $e;
    }

    private function jsonOrRedirect(Request $request, bool $success, string $message, $redirect, string $redirectUrl = null)
    {
        if ($request->wantsJson()) {
            $response = [
                'success' => $success,
                'message' => $message,
            ];

            if ($success && $redirectUrl) {
                $response['redirect'] = $redirectUrl;
            }

            return response()->json($response, $success ? 200 : 400);
        }

        return $redirect;
    }
}
