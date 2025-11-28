<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Code;
use App\Services\VoterProgressService;
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
    private $clientIP;
    private $maxUseClientIP;
    private $votingTimeInMinutes;

    public function __construct()
    {
        $this->clientIP = \Request::getClientIp(true);
        $this->maxUseClientIP = config('app.max_use_clientIP', 7);
        $this->votingTimeInMinutes = 20;
    }

    /**
     * STEP 1: Show code entry form
     * Route: GET /v/{slug}/code/create
     */
    public function create(Request $request)
    {
        $user = $this->getUser($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('Code create page accessed', [
            'user_id' => $user->id,
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'is_slug_request' => $voterSlug !== null,
        ]);

        // Check basic eligibility
        if (!$this->isUserEligible($user)) {
            return $this->redirectToDashboard('You are not eligible to vote.');
        }

        // Get or create code record
        $code = $this->getOrCreateCode($user);

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
            'nrna_id' => $user->nrna_id ?? '',
            'state' => 'code_sent',
            'code_duration' => $code->code1_sent_at ? now()->diffInMinutes($code->code1_sent_at) : 0,
            'code_expires_in' => 20, // 20 minutes expiry
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'has_valid_email' => $hasValidEmail,
            'show_code_fallback' => !$hasValidEmail, // Show code on page if email can't be sent
            'verification_code' => !$hasValidEmail ? $code->code1 : null, // Only show if no email
        ]);
    }

    /**
     * STEP 1 → STEP 2: Process code submission
     * Route: POST /v/{slug}/code
     */
    public function store(Request $request)
    {
        $user = $this->getUser($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('Code verification started', [
            'user_id' => $user->id,
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

        // Check basic eligibility
        if (!$this->isUserEligible($user)) {
            return $this->jsonOrRedirect($request, false, 'You are not eligible to vote.',
                $this->redirectToDashboard('You are not eligible to vote.'));
        }

        // Get code record
        $code = Code::where('user_id', $user->id)->first();
        if (!$code) {
            return $this->jsonOrRedirect($request, false, 'No verification code found. Please request a new code.',
                back()->withErrors(['voting_code' => 'No verification code found. Please request a new code.']));
        }

        // Check if already verified
        if ($code->can_vote_now == 1) {
            return $this->handleAlreadyVerified($request, $voterSlug);
        }

        // Verify the code
        $verificationResult = $this->verifyCode($code, $submittedCode, $user);

        if (!$verificationResult['success']) {
            return $this->jsonOrRedirect($request, false, $verificationResult['message'],
                back()->withErrors(['voting_code' => $verificationResult['message']])->withInput());
        }

        // Code verified successfully - update database
        $this->markCodeAsVerified($code);

        // Advance slug step if using slug-based voting
        if ($voterSlug) {
            $this->advanceSlugStep($voterSlug);
        }

        Log::info('Code verification successful', [
            'user_id' => $user->id,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        // Redirect to agreement page
        $agreementUrl = $voterSlug
            ? route('slug.code.agreement', ['vslug' => $voterSlug->slug])
            : route('code.agreement');

        return $this->jsonOrRedirect($request, true, 'Code verified successfully!',
            redirect($agreementUrl)->with('success', 'Code verified successfully!'), $agreementUrl);
    }

    /**
     * STEP 2: Show agreement page
     * Route: GET /v/{slug}/vote/agreement
     */
    public function showAgreement(Request $request)
    {
        $user = $this->getUser($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('Agreement page accessed', [
            'user_id' => $user->id,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        // Verify user has completed code verification
        $code = Code::where('user_id', $user->id)->first();
        if (!$code || $code->can_vote_now != 1) {
            $redirectUrl = $voterSlug
                ? route('slug.code.create', ['vslug' => $voterSlug->slug])
                : route('code.create');

            return redirect($redirectUrl)
                ->with('error', 'Code verification required before proceeding.');
        }

        // Check if agreement already accepted
        if ($code->has_agreed_to_vote) {
            // BUGFIX: If agreement already accepted, advance step before redirecting
            // This prevents redirect loop when has_agreed_to_vote=1 but current_step=2
            if ($voterSlug && $voterSlug->current_step < 3) {
                $progressService = new VoterProgressService();
                $progressService->advanceFrom($voterSlug, 'slug.code.agreement', ['agreement_accepted' => true]);

                Log::info('Advanced step after finding agreement already accepted', [
                    'user_id' => $user->id,
                    'slug' => $voterSlug->slug,
                    'old_step' => 2,
                    'new_step' => 3,
                ]);
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
                'voting_time_minutes' => $code->voting_time_in_minutes ?? 20,
            ]);
        }

        return Inertia::render('Code/Agreement', [
            'user_name' => $user->name,
            'voting_time_minutes' => $code->voting_time_in_minutes ?? 20,
            'agreement_text_nepali' => 'म यो अनलाइन मतदान प्रणालीमा स्वेच्छाले भाग लिइरहेको छु र मेरो मत गोप्य राखिनेछ भन्ने कुरामा सहमत छु।',
            'agreement_text_english' => 'I voluntarily participate in this online voting system and agree that my vote will remain secret and secure.',
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
        ]);
    }

    /**
     * STEP 2 → STEP 3: Process agreement submission
     * Route: POST /v/{slug}/code/agreement
     */
    public function submitAgreement(Request $request)
    {
        $user = $this->getUser($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('Agreement submission started', [
            'user_id' => $user->id,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        // Validate agreement
        $request->validate([
            'agreement' => 'required|accepted'
        ], [
            'agreement.required' => 'You must accept the terms and conditions.',
            'agreement.accepted' => 'You must accept the terms and conditions.',
        ]);

        // Verify user has completed code verification
        $code = Code::where('user_id', $user->id)->first();
        if (!$code || $code->can_vote_now != 1) {
            return $this->jsonOrRedirect($request, false, 'Code verification required.',
                redirect()->route('slug.code.create', ['vslug' => $voterSlug->slug]));
        }

        // Mark agreement as accepted
        $code->update([
            'has_agreed_to_vote' => 1,
            'has_agreed_to_vote_at' => now(),
            'voting_started_at' => now(),
        ]);

        // Advance slug step
        if ($voterSlug) {
            $progressService = new VoterProgressService();
            $progressService->advanceFrom($voterSlug, 'slug.code.agreement', ['agreement_accepted' => true]);
        }

        Log::info('Agreement accepted successfully', [
            'user_id' => $user->id,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

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

    private function getUser(Request $request): User
    {
        return $request->attributes->has('voter')
            ? $request->attributes->get('voter')
            : auth()->user();
    }

    private function isUserEligible(User $user): bool
    {
        return $user && $user->can_vote == 1;
    }

    private function getOrCreateCode(User $user): Code
    {
        $code = Code::where('user_id', $user->id)->first();

        if (!$code) {
            // No code exists - create new one
            $code = Code::create([
                'user_id' => $user->id,
                'code1' => $this->generateCode(),
                'code1_sent_at' => now(),
                'has_code1_sent' => 1,
                'client_ip' => $this->clientIP,
                'voting_time_in_minutes' => $this->votingTimeInMinutes,
                'is_code1_usable' => 1,
                'can_vote_now' => 0,
            ]);

            // Send code via email only if user has valid email
            if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    $user->notify(new SendFirstVerificationCode($user, $code->code1));
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

            Log::info('New verification code created and sent', [
                'user_id' => $user->id,
                'code_id' => $code->id,
                'code' => $code->code1,
            ]);
        } else {
            // Code exists - check if it needs resending
            $isExpired = $code->code1_sent_at && now()->diffInMinutes($code->code1_sent_at) > 20;
            $codeWasUsed = $code->is_code1_usable == 0;
            $notYetVoted = !$code->has_voted;
            $voteNotSubmitted = !$code->vote_submitted;

            // Resend code if:
            // 1. Code is expired AND not yet used AND not voted, OR
            // 2. Code was used but vote wasn't submitted (user restarted), OR
            // 3. Code was used, vote submitted but not completed (user restarted)
            $shouldResend = ($isExpired && !$codeWasUsed && $notYetVoted) ||
                            ($codeWasUsed && $notYetVoted);

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
                    'previous_sent_at' => $code->code1_sent_at,
                ]);
            }
        }

        return $code;
    }

    private function verifyCode(Code $code, string $submittedCode, User $user): array
    {
        // Check if code is usable
        if (!$code->is_code1_usable) {
            return ['success' => false, 'message' => 'This verification code has already been used.'];
        }

        // Check if code matches
        if ($code->code1 !== $submittedCode) {
            Log::warning('Invalid code submission', [
                'user_id' => $user->id,
                'expected' => $code->code1,
                'submitted' => $submittedCode,
            ]);
            return ['success' => false, 'message' => 'Invalid verification code. Please check and try again.'];
        }

        // Check if code is expired (20 minutes)
        if ($code->code1_sent_at && now()->diffInMinutes($code->code1_sent_at) > 20) {
            return ['success' => false, 'message' => 'Verification code has expired. Please request a new code.'];
        }

        // Check IP rate limiting
        $votesFromIP = Code::where('client_ip', $this->clientIP)->where('has_voted', 1)->count();
        if ($votesFromIP >= $this->maxUseClientIP) {
            return ['success' => false, 'message' => 'Too many votes from this IP address.'];
        }

        return ['success' => true, 'message' => 'Code verified successfully.'];
    }

    private function markCodeAsVerified(Code $code): void
    {
        $code->update([
            'can_vote_now' => 1,
            'is_code1_usable' => 0,
            'code1_used_at' => now(),
            'is_codemodel_valid' => true,
            'client_ip' => $this->clientIP,
        ]);
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
        $agreementUrl = $voterSlug
            ? route('slug.code.agreement', ['vslug' => $voterSlug->slug])
            : route('code.agreement');

        return $this->jsonOrRedirect($request, true, 'Code already verified. Continue to agreement.',
            redirect($agreementUrl)->with('info', 'Code already verified. Continue to agreement.'), $agreementUrl);
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