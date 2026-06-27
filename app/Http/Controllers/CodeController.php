<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Code;
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
 * Clean CodeController for Slug-Based Voting System
 *
 * Core Principle: can_vote_now field is the single source of truth
 * - can_vote_now = 0: User needs to verify code
 * - can_vote_now = 1: User verified, can proceed to agreement
 */
class CodeController extends Controller
{
    use \App\Traits\EnsuresVoterMembership;

    private $clientIP;
    private $maxUseClientIP;
    private $votingTimeInMinutes;

    public function __construct()
    {
        $this->clientIP = \Request::getClientIp(true);
        $this->maxUseClientIP = config('app.max_use_clientIP') ?? 7;
        $this->votingTimeInMinutes = (int) config('voting.time_in_minutes', 30); // Configurable voting window (matches voter slug expiry)
    }

    /**
     * STEP 1: Show code entry form
     * Route: GET /v/{slug}/code/create
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

        Log::emergency('🔴 [CREATE] ENTERED create() method', [
            'user_id' => $user->id ?? 'null',
            'election_id' => $election->id ?? 'null',
            'has_voter_slug' => $voterSlug !== null,
            'voter_slug_value' => $voterSlug ? $voterSlug->slug : 'null',
        ]);

        // Bind organisation context so BelongsToTenant scope resolves correctly
        // for all Code queries within this request.
        session(['current_organisation_id' => $election->organisation_id]);

        // Layer 0: Membership check (defense-in-depth — middleware is primary gate).
        // Skip when the voter-slug middleware stack has already verified eligibility;
        // the cached isVoterInElection() result may be stale (5-min TTL).
        if (! $voterSlug && $redirect = $this->ensureVoterMembership($election, $user)) {
            return $redirect;
        }

        // Check if code is already verified (should not be accessing create page)
        $existingCode = Code::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();

        Log::info('🟣 [CREATE] Code create page accessed', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'is_slug_request' => $voterSlug !== null,
            'existing_code_verified' => $existingCode ? $existingCode->can_vote_now : 'no_code',
        ]);

        // ⚠️ If code is already verified AND no fresh voter slug is being used, redirect to the appropriate next step
        // When a voter slug is present, we allow a fresh code verification flow (don't redirect)
        Log::emergency('🔴 [CREATE] Checking redirect condition', [
            'existing_code_exists' => $existingCode !== null,
            'can_vote_now' => $existingCode ? $existingCode->can_vote_now : 'no_code',
            'has_voter_slug' => $voterSlug !== null,
            'voter_slug_value' => $voterSlug ? $voterSlug->slug : 'null',
            'should_redirect' => $existingCode && $existingCode->can_vote_now === true && !$voterSlug,
        ]);

        if ($existingCode && $existingCode->can_vote_now === true && !$voterSlug) {
            Log::warning('⚠️ [CREATE] User already has verified code - redirecting to correct step', [
                'user_id' => $user->id,
                'code_id' => $existingCode->id,
            ]);
            return $this->handleAlreadyVerified($request, $voterSlug);
        }

        // ⛔ REAL ELECTIONS: Block access to code page if already voted
        if ($election->type === 'real' && $existingCode && $existingCode->has_voted === true) {
            Log::warning('⛔ Real election - blocking code page access for voter who already voted', [
                'user_id' => $user->id,
                'election_id' => $election->id,
                'code_id' => $existingCode->id,
            ]);
            return $this->redirectToDashboard('You have already voted in this election. Each voter can only vote once.');
        }

        // Check basic eligibility
        if (!$this->isUserEligible($user, $request)) {
            return $this->redirectToDashboard('You are not eligible to vote.');
        }

        // Get or create code record for this election
        $code = $this->getOrCreateCode($user, $election);

        // ✅ CHECK IF CODE HAS EXPIRED OR IS STALE - IF YES, SEND NEW ONE
        $minutesSinceSent = $code->code_to_open_voting_form_sent_at
            ? \Carbon\Carbon::parse($code->code_to_open_voting_form_sent_at)->diffInMinutes(now())
            : 0;

        // Determine if code needs regeneration:
        // - Window expired (>= voting time minutes) AND user hasn't voted yet
        // - OR: code flags were zeroed by vote_pre_check timeout (has_code1_sent=0) but sent_at is stale
        // ✅ CRITICAL: ALSO regenerate verified codes (can_vote_now=true) if they're old
        //    This prevents "code expired" error when user returns days later
        $codeNeedsReset = !$code->has_voted && (
            ($minutesSinceSent >= $this->votingTimeInMinutes)
            || (!$code->has_code1_sent && $code->code_to_open_voting_form_sent_at)
        );

        if ($codeNeedsReset) {
            Log::info('🔄 Code expired - sending new code', [
                'user_id' => $user->id,
                'minutes_since_sent' => $minutesSinceSent,
                'max_minutes' => $this->votingTimeInMinutes,
                'was_verified' => $code->can_vote_now,
            ]);

            // Generate new code and reset all flags (full window reset)
            // ✅ CRITICAL: Preserve verified status (can_vote_now=true) after regeneration
            //    If code was already verified, it should stay verified
            $code->update([
                'code_to_open_voting_form'           => $this->generateUniqueCodeForOrganisation($election->organisation_id),
                'code_to_open_voting_form_sent_at'   => now(),
                'has_code1_sent'                     => true,
                'is_code_to_open_voting_form_usable' => true,
                'can_vote_now'                       => $code->can_vote_now, // Preserve verified status
                'code_to_open_voting_form_used_at'   => null,
                'voting_started_at'                  => null,
                'vote_submitted'                     => false,
            ]);

            // Extend voter slug expiry so the user gets a fresh 30-minute window
            if ($voterSlug) {
                $voterSlug->update([
                    'expires_at'  => now()->addMinutes($this->votingTimeInMinutes),
                    'is_active'   => true,
                    'can_vote_now' => true,
                    'status'      => 'active',
                ]);
                Log::info('✅ Voter slug window extended after code regeneration', [
                    'voter_slug_id' => $voterSlug->id,
                    'new_expires_at' => now()->addMinutes($this->votingTimeInMinutes),
                ]);
            }

            // Send new code notification
            try {
                $user->notify(new SendFirstVerificationCode($user, $code->code_to_open_voting_form));
                Log::info('✅ New verification code sent', [
                    'user_id' => $user->id,
                    'code' => $code->code_to_open_voting_form,
                ]);
            } catch (\Exception $e) {
                Log::error('❌ Failed to send new code', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
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

        return Inertia::render('Code/CreateCode', [
            'name' => $user->name,
            'user_id' => $user->user_id ?? '',
            'state' => 'code_sent',
            'code_duration' => $minutesSinceSent,
            'code_expires_in' => $this->votingTimeInMinutes, // expires after voting window
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'has_valid_email' => $hasValidEmail,
            'show_code_fallback' => !$hasValidEmail, // Show code on page if email can't be sent
            'verification_code' => !$hasValidEmail ? $code->code_to_open_voting_form : null, // Only show if no email
        ]);
    }

    /**
     * STEP 1 → STEP 2: Process code submission
     * Route: POST /v/{slug}/code
     *
     * Verifies code for the selected election
     */
    public function store(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        // Layer 0: Membership check (defense-in-depth).
        // Skip for voter-slug requests — middleware already verified eligibility.
        if (! $voterSlug && $redirect = $this->ensureVoterMembership($election, $user)) {
            return $redirect;
        }

        Log::info('Code verification started', [
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
                'voting_code' => 'required|string|size:8'
            ], [
                'voting_code.required' => 'Please enter the verification code.',
                'voting_code.size' => 'Code must be exactly 8 characters.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->handleValidationError($e, $request);
        }

        $submittedCode = trim(strtoupper($request->input('voting_code')));

        // Check basic eligibility
        if (!$this->isUserEligible($user, $request)) {
            return $this->redirectToDashboard('You are not eligible to vote.');
        }

        // Get code record for this election
        // ✅ CRITICAL: Use organisation_id from election to scope correctly
        $code = Code::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->where('organisation_id', $election->organisation_id)
            ->first();
        if (!$code) {
            Log::warning('Code not found in verification', [
                'user_id' => $user->id,
                'election_id' => $election->id,
                'election_org_id' => $election->organisation_id,
                'user_org_id' => $user->organisation_id,
            ]);
            return back()->withErrors(['voting_code' => 'No verification code found. Please request a new code.']);
        }

        // REAL ELECTIONS: Prevent double voting
        if ($election->type === 'real' && $code->has_voted) {
            Log::warning('Real election - double vote attempt prevented', [
                'user_id' => $user->id,
                'election_id' => $election->id,
                'election_type' => $election->type,
            ]);
            return back()->withErrors(['voting_code' => 'You have already voted in this election. Each voter can only vote once.']);
        }

        // Check if already verified
        if ($code->can_vote_now === true) {
            return $this->handleAlreadyVerified($request, $voterSlug);
        }

        // Verify the code
        $verificationResult = $this->verifyCode($code, $submittedCode, $user);

        if (!$verificationResult['success']) {
            return back()->withErrors(['voting_code' => $verificationResult['message']])->withInput();
        }

        // Code verified successfully - update database
        Log::info('🔵 [STORE] About to call markCodeAsVerified', [
            'code_id' => $code->id,
            'before_can_vote_now' => $code->can_vote_now,
        ]);

        $this->markCodeAsVerified($code);

        // Verify the update in database
        $freshCode = $code->fresh();
        Log::info('🟢 [STORE] markCodeAsVerified completed', [
            'code_id' => $code->id,
            'after_can_vote_now' => $freshCode->can_vote_now,
            'is_code_to_open_voting_form_usable' => $freshCode->is_code_to_open_voting_form_usable,
        ]);

        // ✅ NEW: Record step completion in voter_slug_steps table
        if ($voterSlug) {
            Log::info('🔵 [STORE] About to record step 1', [
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
                Log::info('✅ Step 1 recorded in voter_slug_steps', [
                    'voter_slug_id' => $voterSlug->id,
                    'election_id' => $election->id,
                ]);
            } catch (\Exception $e) {
                Log::error('❌ [STORE] Failed to record step 1', [
                    'voter_slug_id' => $voterSlug->id,
                    'election_id' => $election->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        } else {
            Log::warning('⚠️ [STORE] No voter_slug in request', [
                'user_id' => $user->id,
            ]);
        }

        // Legacy: Advance slug step if using slug-based voting
        if ($voterSlug) {
            $this->advanceSlugStep($voterSlug);
        }

        Log::info('Code verification successful', [
            'user_id' => $user->id,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        app(\App\Services\ElectionAuditService::class)->logVoterAction(
            election: $election,
            voter: $user,
            step: 1,
            action: 'code_verified',
            metadata: ['code_id' => $code->id]
        );

        // Redirect to agreement page
        $agreementUrl = $voterSlug
            ? route('slug.code.agreement', ['vslug' => $voterSlug->slug])
            : route('code.agreement');

        Log::info('🟡 [STORE] About to return redirect', [
            'agreementUrl' => $agreementUrl,
            'voterSlug' => $voterSlug ? $voterSlug->slug : 'null',
            'is_response_object' => is_object(redirect($agreementUrl)),
        ]);

        // Always return Redirect for form submissions (Inertia will follow it)
        $redirectResponse = redirect($agreementUrl)->with('success', 'Code verified successfully!');

        Log::info('✅ [STORE] Returning redirect response', [
            'response_status_code' => $redirectResponse->getStatusCode(),
            'response_location' => $redirectResponse->getTargetUrl(),
        ]);

        return $redirectResponse;
    }

    /**
     * STEP 2: Show agreement page
     * Route: GET /v/{slug}/vote/agreement
     *
     * User reads and accepts voting agreement
     * Can only proceed if code was verified in this election
     */
    public function showAgreement(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        // Layer 0: Membership check (defense-in-depth)
        if ($redirect = $this->ensureVoterMembership($election, $user)) {
            return $redirect;
        }

        Log::info('Agreement page accessed', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        // Verify user has completed code verification for this election
        $code = Code::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();

        // Block if already voted
        if ($election->type === 'real' && $code && $code->has_voted) {
            Log::warning('Security: Voted user attempted showAgreement', ['user_id' => $user->id, 'election_id' => $election->id]);
            return $this->redirectToDashboard('You have already voted in this election.');
        }

        if (!$code || $code->can_vote_now !== true) {
            $redirectUrl = $voterSlug
                ? route('slug.code.create', ['vslug' => $voterSlug->slug])
                : route('code.create');

            return redirect($redirectUrl)
                ->with('error', 'Code verification required before proceeding.');
        }

        // Check if agreement already accepted
        if ($code->has_agreed_to_vote) {
            Log::info('🔵 [showAgreement] Detected agreement already accepted', [
                'user_id' => $user->id,
                'has_agreed_to_vote' => $code->has_agreed_to_vote,
            ]);

            // CRITICAL FIX: Record Step 2 if not already recorded
            // This breaks the circular redirect loop
            if ($voterSlug) {
                try {
                    $stepTracker = new VoterStepTrackingService();
                    $highestStep = $stepTracker->getHighestCompletedStep($voterSlug, $election);

                    Log::info('🔵 [showAgreement] Current highest step', [
                        'highest_step' => $highestStep,
                    ]);

                    // If Step 2 not recorded yet, record it now
                    if ($highestStep < 2) {
                        Log::info('🔵 [showAgreement] Step 2 not recorded - recording now to break loop', [
                            'voter_slug_id' => $voterSlug->id,
                        ]);

                        $stepTracker->completeStep(
                            $voterSlug,
                            $election,
                            2,
                            ['agreement_accepted' => true, 'auto_recorded' => true, 'recorded_at' => now()->toIso8601String()]
                        );

                        Log::info('✅ [showAgreement] Step 2 auto-recorded to break circular redirect', [
                            'voter_slug_id' => $voterSlug->id,
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('❌ [showAgreement] Failed to record step 2', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $voteUrl = $voterSlug
                ? route('slug.vote.create', ['vslug' => $voterSlug->slug])
                : route('vote.create');

            return redirect($voteUrl)
                ->with('info', 'You have already accepted the agreement.');
        }

        // For API requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'step' => 2,
                'user_name' => $user->name,
                'voting_time_minutes' => $code->voting_time_in_minutes ?? config('voting.time_in_minutes', 30),
            ]);
        }

        return Inertia::render('Code/Agreement', [
            'user_name' => $user->name,
            'voting_time_minutes' => $code->voting_time_in_minutes ?? config('voting.time_in_minutes', 30),
            'agreement_text_nepali' => 'म यो अनलाइन मतदान प्रणालीमा स्वेच्छाले भाग लिइरहेको छु र मेरो मत गोप्य राखिनेछ भन्ने कुरामा सहमत छु।',
            'agreement_text_english' => 'I voluntarily participate in this online voting system and agree that my vote will remain secret and secure.',
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
        ]);
    }

    /**
     * STEP 2 → STEP 3: Process agreement submission
     * Route: POST /v/{slug}/code/agreement
     *
     * User confirms agreement and can proceed to vote
     */
    public function submitAgreement(Request $request)
    {
        Log::info('🔴 [CRITICAL] submitAgreement() method called', [
            'route' => $request->route()->getName(),
            'url' => $request->url(),
            'method' => $request->method(),
            'all_params' => $request->all(),
        ]);

        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        // Layer 0: Membership check (defense-in-depth)
        if ($redirect = $this->ensureVoterMembership($election, $user)) {
            return $redirect;
        }

        Log::info('Agreement submission started', [
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
        $code = Code::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();

        // Block if already voted
        if ($election->type === 'real' && $code && $code->has_voted) {
            Log::warning('Security: Voted user attempted submitAgreement', ['user_id' => $user->id, 'election_id' => $election->id]);
            return $this->redirectToDashboard('You have already voted in this election.');
        }

        if (!$code || $code->can_vote_now !== true) {
            return $this->jsonOrRedirect($request, false, 'Code verification required.',
                redirect()->route('slug.code.create', ['vslug' => $voterSlug->slug]));
        }

        // Mark agreement as accepted
        $code->update([
            'has_agreed_to_vote' => true,
            'has_agreed_to_vote_at' => now(),
            'voting_started_at' => now(),
        ]);

        // ✅ NEW: Record step 2 completion in voter_slug_steps table
        if ($voterSlug) {
            Log::info('🔵 [SUBMIT_AGREEMENT] About to record step 2', [
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
                Log::info('✅ Step 2 recorded in voter_slug_steps', [
                    'voter_slug_id' => $voterSlug->id,
                    'election_id' => $election->id,
                ]);
            } catch (\Exception $e) {
                Log::error('❌ [SUBMIT_AGREEMENT] Failed to record step 2', [
                    'voter_slug_id' => $voterSlug->id,
                    'election_id' => $election->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            // Legacy: Advance slug step (deprecated, but keep for backward compatibility)
            $progressService = new VoterProgressService();
            $progressService->advanceFrom($voterSlug, 'slug.code.agreement', ['agreement_accepted' => true]);
        }

        Log::info('Agreement accepted successfully', [
            'user_id' => $user->id,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        app(\App\Services\ElectionAuditService::class)->logVoterAction(
            election: $election,
            voter: $user,
            step: 2,
            action: 'agreement_accepted'
        );

        // Redirect to voting page
        $voteUrl = $voterSlug
            ? route('slug.vote.create', ['vslug' => $voterSlug->slug])
            : route('vote.create');

        return $this->jsonOrRedirect($request, true, 'Agreement accepted successfully!',
            redirect($voteUrl)->with('success', 'Agreement accepted. You may now cast your vote.'), $voteUrl);
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
     * Get election from middleware or default to real election
     * The ElectionMiddleware ensures an election is always set
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Election
     */
    private function getElection(Request $request): Election
    {
        $election  = $request->attributes->get('election');
        $voterSlug = $request->attributes->get('voter_slug');

        if (! $election) {
            Log::error('Election context missing from request attributes', [
                'route'   => $request->route()?->getName(),
                'user_id' => auth()->id(),
            ]);
            throw new \Exception('Election context is required but was not set by middleware.');
        }

        if ($voterSlug && $election) {
            if ($election->id !== $voterSlug->election_id) {
                Log::error('Election mismatch', ['election' => $election->id, 'slug_election' => $voterSlug->election_id]);
                throw new \Exception('Election mismatch detected between voter slug and election context');
            }
            $orgsMatch       = $election->organisation_id === $voterSlug->organisation_id;
            $platformOrgId   = config('app.platform_organisation_id');
            $isPlatformElect = $platformOrgId && $election->organisation_id === $platformOrgId;
            $isPlatformSlug  = $platformOrgId && $voterSlug->organisation_id === $platformOrgId;
            if (!$orgsMatch && !$isPlatformElect && !$isPlatformSlug) {
                Log::error('Organisation mismatch', ['election_org' => $election->organisation_id, 'slug_org' => $voterSlug->organisation_id]);
                throw new \Exception('Organisation mismatch detected');
            }
        }
        return $election;
    }

    private function isUserEligible(User $user, ?Request $request = null): bool
    {
        // Allow any authenticated user to vote in demo elections
        if (session('selected_election_type') === 'demo') {
            return true;
        }

        // If the request arrived via the new ElectionMembership voter-slug middleware
        // stack, the user has already been validated as an eligible voter — skip the
        // legacy can_vote column check.
        if ($request && $request->attributes->get('voter_slug')) {
            return true;
        }

        // Legacy: Real elections require can_vote permission
        return $user && $user->can_vote === true;
    }

    /**
     * Get or create verification code for user and election
     * Now election-scoped: one code per user per election
     *
     * @param \App\Models\User $user
     * @param \App\Models\Election $election
     * @return \App\Models\Code
     */
    private function getOrCreateCode(User $user, Election $election): Code
    {
        // Get code for this specific election
        // CRITICAL: Use withoutGlobalScopes() because demo elections have organisation_id=NULL
        // The global scope would filter out codes for demo elections
        $code = Code::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();

        // DEMO ELECTIONS: Allow re-voting by resetting flags
        if ($code && $code->has_voted && $election->type === 'demo') {
            Log::info('🔄 Demo election - resetting code for re-voting', [
                'user_id' => $user->id,
                'code_id' => $code->id,
                'old_has_voted' => $code->has_voted,
            ]);

            // Reset voting flags for demo to allow new vote
            $code->update([
                'has_voted' => false,
                'vote_submitted' => false,
                'can_vote_now' => false,
                'is_code_to_open_voting_form_usable' => true,
                'code_to_open_voting_form' => $this->generateCode(),
                'code_to_open_voting_form_sent_at' => now(),
                'has_code1_sent' => true,
            ]);

            // Send new code via email
            if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    $user->notify(new SendFirstVerificationCode($user, $code->code_to_open_voting_form));
                    Log::info('✅ New demo voting code sent', [
                        'user_id' => $user->id,
                        'code_id' => $code->id,
                        'code' => $code->code_to_open_voting_form,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send demo voting code', [
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
            // - Demo elections (type='demo'): organisation_id=NULL
            // - Real elections (type='real'): organisation_id from election
            $code = Code::create([
                'user_id' => $user->id,
                'election_id' => $election->id,
                'organisation_id' => $election->organisation_id,  // ✅ EXPLICIT
                'code_to_open_voting_form' => $this->generateUniqueCodeForOrganisation($election->organisation_id),
                'code_to_open_voting_form_sent_at' => now(),
                'has_code1_sent' => true,
                'client_ip' => $this->clientIP,
                'voting_time_in_minutes' => $this->votingTimeInMinutes,
                'is_code_to_open_voting_form_usable' => true,
                'can_vote_now' => false,
            ]);

            // Send code via email only if user has valid email
            if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    $user->notify(new SendFirstVerificationCode($user, $code->code_to_open_voting_form));
                } catch (\Exception $e) {
                    Log::error('Failed to send verification code email', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'error' => $e->getMessage(),
                    ]);
                }
            } else {
                Log::warning('User does not have valid email for verification code', [
                    'user_id' => $user->id,
                    'email' => $user->email ?? 'null',
                ]);
            }

            Log::info('✅ New verification code created with organisation_id and election_id', [
                'code_id' => $code->id,
                'user_id' => $user->id,
                'election_id' => $code->election_id,
                'organisation_id' => $code->organisation_id,
                'code' => $code->code_to_open_voting_form,
            ]);
        } else {
            // ✅ CRITICAL: If code already verified, DO NOT regenerate
            // Code was successfully verified and user should go to agreement page
            if ($code->can_vote_now === true) {
                Log::info('Code already verified - returning existing code', [
                    'user_id' => $user->id,
                    'election_id' => $election->id,
                    'code_id' => $code->id,
                ]);
                return $code; // Return without regenerating
            }

            // Code exists - check if it needs resending
            $isExpired = $code->code_to_open_voting_form_sent_at && \Carbon\Carbon::parse($code->code_to_open_voting_form_sent_at)->diffInMinutes(now()) > $this->votingTimeInMinutes;
            $codeWasUsed = $code->is_code_to_open_voting_form_usable === false;
            $notYetVoted = !$code->has_voted;
            $voteNotSubmitted = !$code->vote_submitted;

            // Resend code ONLY if:
            // - Code is expired AND not yet used AND not voted
            //
            // DO NOT resend if code was already used/verified ($codeWasUsed = true)
            // because user should be redirected to agreement page, not given new code
            $shouldResend = ($isExpired && !$codeWasUsed && $notYetVoted);

            if ($shouldResend) {
                // Generate unique code and reset all flags
                $newCode = $this->generateUniqueCodeForOrganisation($code->organisation_id);

                $code->update([
                    'code_to_open_voting_form'           => $newCode,
                    'code_to_open_voting_form_sent_at'   => now(),
                    'has_code1_sent'                     => true,
                    'is_code_to_open_voting_form_usable' => true,
                    'can_vote_now'                       => false,
                    'code_to_open_voting_form_used_at'   => null,
                    'voting_started_at'                  => null,
                    'vote_submitted'                     => false,
                ]);
                Log::info('Security: Expired code regenerated', ['user_id' => $user->id, 'election_id' => $election->id]);

                // Send new code via email only if user has valid email
                if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        $user->notify(new SendFirstVerificationCode($user, $newCode));
                    } catch (\Exception $e) {
                        Log::error('Failed to resend verification code email', [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'error' => $e->getMessage(),
                        ]);
                    }
                } else {
                    Log::warning('User does not have valid email for verification code resend', [
                        'user_id' => $user->id,
                        'email' => $user->email ?? 'null',
                    ]);
                }

                Log::info('Code regenerated and resent', [
                    'user_id' => $user->id,
                    'code_id' => $code->id,
                    'new_code' => $newCode,
                    'reason' => $isExpired ? 'expired' : 'restart_after_use',
                    'was_used' => $codeWasUsed,
                    'previous_sent_at' => $code->code_to_open_voting_form_sent_at,
                ]);
            }
        }

        return $code;
    }

    private function verifyCode(Code $code, string $submittedCode, User $user): array
    {
        // Check if code is usable
        if (!$code->is_code_to_open_voting_form_usable) {
            return ['success' => false, 'message' => 'This verification code has already been used.'];
        }

        // Check if code matches
        if ($code->code_to_open_voting_form !== $submittedCode) {
            Log::warning('Invalid code submission', [
                'user_id' => $user->id,
                'expected' => $code->code_to_open_voting_form,
                'submitted' => $submittedCode,
            ]);
            return ['success' => false, 'message' => 'Invalid verification code. Please check and try again.'];
        }

        // Check if code is expired (20 minutes)
        if ($code->code_to_open_voting_form_sent_at && \Carbon\Carbon::parse($code->code_to_open_voting_form_sent_at)->diffInMinutes(now()) > $this->votingTimeInMinutes) {
            return ['success' => false, 'message' => 'Verification code has expired. Please request a new code.'];
        }

        // Check IP rate limiting (scoped to current election only)
        $votesFromIP = Code::withoutGlobalScopes()
            ->where('client_ip', $this->clientIP)
            ->where('election_id', $code->election_id)
            ->where('has_voted', 1)
            ->count();
        if ($votesFromIP >= $this->maxUseClientIP) {
            Log::warning('Security: IP rate limit exceeded', ['ip' => $this->clientIP, 'election_id' => $code->election_id, 'count' => $votesFromIP]);
            return ['success' => false, 'message' => 'Too many votes from this IP address.'];
        }

        return ['success' => true, 'message' => 'Code verified successfully.'];
    }

    private function markCodeAsVerified(Code $code): void
    {
        Log::info('🔴 [markCodeAsVerified] Starting', ['code_id' => $code->id]);

        try {
            $updateResult = $code->update([
                'can_vote_now'                       => true,
                'is_code_to_open_voting_form_usable' => config('voting.two_codes_system', false) === true ? false : true,
                'code_to_open_voting_form_used_at'   => now(), 
                'client_ip'                          => $this->clientIP,
            ]);

            Log::info('🟠 [markCodeAsVerified] Update result', [
                'code_id' => $code->id,
                'update_result' => $updateResult,
                'can_vote_now' => $code->can_vote_now,
            ]);
        } catch (\Exception $e) {
            Log::error('❌ [markCodeAsVerified] EXCEPTION', [
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
            $progressService->advanceFrom($voterSlug, 'slug.code.create', ['code_completed' => true]);
        }
    }

    private function handleAlreadyVerified(Request $request, $voterSlug)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);

        // ✅ CRITICAL: Record Step 1 if not already recorded
        // This fixes the case where user resubmits an already-verified code
        if ($voterSlug) {
            Log::info('🔵 [handleAlreadyVerified] Recording Step 1 for already-verified code', [
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
                    Log::info('✅ Step 1 recorded for already-verified code', [
                        'voter_slug_id' => $voterSlug->id,
                        'election_id' => $election->id,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('❌ [handleAlreadyVerified] Failed to record step 1', [
                    'voter_slug_id' => $voterSlug->id,
                    'election_id' => $election->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Redirect to the correct next step based on how far the voter has progressed
        if ($voterSlug) {
            try {
                $stepTrackingService = new VoterStepTrackingService();
                $highestStep = $stepTrackingService->getHighestCompletedStep($voterSlug, $election);

                // Step 2 (agreement) already done → redirect to vote page (step 3)
                if ($highestStep >= 2) {
                    Log::info('🔵 [handleAlreadyVerified] Steps 1+2 complete — redirecting to vote page', [
                        'voter_slug_id' => $voterSlug->id,
                        'highest_step'  => $highestStep,
                    ]);
                    return redirect(route('slug.vote.create', ['vslug' => $voterSlug->slug]))
                        ->with('info', 'Code already verified. Continue to vote.');
                }
            } catch (\Exception $e) {
                Log::error('❌ [handleAlreadyVerified] Failed to read step progress', [
                    'voter_slug_id' => $voterSlug->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return redirect(route('slug.code.agreement', ['vslug' => $voterSlug->slug]))
                ->with('info', 'Code already verified. Continue to agreement.');
        }

        return redirect(route('code.agreement'))
            ->with('info', 'Code already verified. Continue to agreement.');
    }

    private function generateCode(): string
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < 8; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $code;
    }

    private function generateUniqueCodeForOrganisation($organisationId): string
    {
        $maxAttempts = 10;
        $attempts    = 0;
        do {
            $code   = $this->generateCode();
            $exists = Code::withoutGlobalScopes()
                ->where('code_to_open_voting_form', $code)
                ->where('organisation_id', $organisationId)
                ->exists();
            $attempts++;
        } while ($exists && $attempts < $maxAttempts);

        if ($attempts >= $maxAttempts) {
            Log::error('Failed to generate unique code after max attempts', ['organisation_id' => $organisationId]);
            return $this->generateCode() . Str::random(4); // fallback
        }
        return $code;
    }

    private function redirectToDashboard(string $message)
    {
        return redirect()->route('dashboard')->with('error', $message);
    }

    private function handleValidationError(\Illuminate\Validation\ValidationException $e, Request $request)
    {
        Log::error('Validation failed', ['errors' => $e->errors()]);

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