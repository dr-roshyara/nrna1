## 📋 **PROFESSIONAL PROMPT INSTRUCTIONS: Implement DemoCodeController and DemoCode Create View**

```
## CONTEXT
We have a working CodeController for real elections with full multi-tenancy support. Now we need to create a parallel DemoCodeController for demo elections that follows the exact same logic but uses DemoCode model instead of Code model.

## CURRENT ARCHITECTURE
```
Real Elections (CodeController + Code model):
├── Uses Code model with BelongsToTenant trait
├── organisation_id: NOT NULL (enforced at DB level)
├── Routes: /code/*, /v/{slug}/code/*
├── View: resources/js/Pages/Code/Create.vue
└── Tracks real votes with strict validation

Demo Elections (NEED TO CREATE):
├── Should use DemoCodeController + DemoCode model
├── DemoCode model must have BelongsToTenant trait
├── organisation_id: NULL for MODE 1, value for MODE 2
├── Routes: /demo/code/*, /demo/v/{slug}/code/*
├── View: resources/js/Pages/DemoCode/Create.vue (clone of Code/Create.vue)
└── Tracks demo votes with relaxed validation (allow re-voting)
```

## REQUIREMENTS

### 1. Create DemoCode Model (if not exists)
```php
// app/Models/DemoCode.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;  // CRITICAL for multi-tenancy

class DemoCode extends Model
{
    use BelongsToTenant;
    
    protected $table = 'demo_codes';
    
    protected $fillable = [
        'user_id',
        'election_id',
        'organisation_id',  // NULL for MODE 1, value for MODE 2
        'code1',
        'code1_sent_at',
        'has_code1_sent',
        'code1_used_at',
        'is_code1_usable',
        'can_vote_now',
        'has_agreed_to_vote',
        'has_agreed_to_vote_at',
        'voting_started_at',
        'has_voted',
        'vote_submitted',
        'client_ip',
        'voting_time_in_minutes',
        'is_codemodel_valid',
    ];
    
    protected $casts = [
        'code1_sent_at' => 'datetime',
        'code1_used_at' => 'datetime',
        'has_agreed_to_vote_at' => 'datetime',
        'voting_started_at' => 'datetime',
        'has_code1_sent' => 'boolean',
        'is_code1_usable' => 'boolean',
        'can_vote_now' => 'boolean',
        'has_agreed_to_vote' => 'boolean',
        'has_voted' => 'boolean',
        'vote_submitted' => 'boolean',
        'is_codemodel_valid' => 'boolean',
    ];
    
    // Relationships (same as Code model)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function election()
    {
        return $this->belongsTo(Election::class);
    }
}
```

### 2. Create DemoCodeController (Clone of CodeController with DemoCode)
```php
// app/Http/Controllers/DemoCodeController.php

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\DemoCode;  // ← USING DEMOCODE
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
 * DemoCodeController for Demo Election Voting System
 *
 * EXACT SAME LOGIC as CodeController, but uses DemoCode model
 * The key difference: DemoCode has organisation_id = NULL for MODE 1, 
 * and organisation_id = X for MODE 2 (organisation-specific demo)
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
        $this->votingTimeInMinutes = 30; // 30 minutes voting window
    }

    /**
     * STEP 1: Show code entry form for demo election
     * Route: GET /demo/v/{slug}/code/create
     */
    public function create(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        // Verify this is a demo election
        if ($election->type !== 'demo') {
            abort(404, 'Demo election not found');
        }

        // Check if code is already verified
        $existingCode = DemoCode::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();

        Log::info('🟣 [DEMO CREATE] Demo code create page accessed', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'existing_code_verified' => $existingCode ? $existingCode->can_vote_now : 'no_code',
            'mode' => $election->organisation_id ? 'MODE 2 (Org-specific)' : 'MODE 1 (Public)',
        ]);

        // Check basic eligibility (always true for demo)
        if (!$this->isUserEligible($user)) {
            return $this->redirectToDashboard('You are not eligible to vote in demo.');
        }

        // Get or create code record for this election
        $code = $this->getOrCreateCode($user, $election);

        // Check if code has expired
        $minutesSinceSent = $code->code1_sent_at ? now()->diffInMinutes($code->code1_sent_at) : 0;

        if ($minutesSinceSent >= $this->votingTimeInMinutes && $code->has_code1_sent) {
            Log::info('🔄 Demo code expired - sending new code', [
                'user_id' => $user->id,
                'minutes_since_sent' => $minutesSinceSent,
            ]);

            // Generate new code and reset timer
            $code->code1 = Str::random(6);
            $code->code1_sent_at = now();
            $code->has_code1_sent = true;
            $code->save();

            // Send new code notification
            try {
                $user->notify(new SendFirstVerificationCode($user, $code->code1));
            } catch (\Exception $e) {
                Log::error('❌ Failed to send new demo code', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }

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
                'mode' => $election->organisation_id ? 'org-specific' : 'public',
            ]);
        }

        // Check if email is valid
        $hasValidEmail = $user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL);

        return Inertia::render('DemoCode/Create', [  // ← Different view path
            'name' => $user->name,
            'user_id' => $user->user_id ?? '',
            'state' => 'code_sent',
            'code_duration' => $minutesSinceSent,
            'code_expires_in' => $this->votingTimeInMinutes,
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'has_valid_email' => $hasValidEmail,
            'show_code_fallback' => !$hasValidEmail,
            'verification_code' => !$hasValidEmail ? $code->code1 : null,
            'is_demo' => true,  // Flag for demo mode
            'organisation_mode' => $election->organisation_id ? 'org-specific' : 'public',
        ]);
    }

    /**
     * STEP 1 → STEP 2: Process code submission for demo election
     * Route: POST /demo/v/{slug}/code
     */
    public function store(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        // Verify this is a demo election
        if ($election->type !== 'demo') {
            abort(404, 'Demo election not found');
        }

        Log::info('Demo code verification started', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
            'slug' => $voterSlug ? $voterSlug->slug : null,
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
            return $this->redirectToDashboard('You are not eligible to vote in demo.');
        }

        // Get code record for this election
        $code = DemoCode::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();
        if (!$code) {
            return back()->withErrors(['voting_code' => 'No verification code found. Please request a new code.']);
        }

        // DEMO ELECTIONS: Allow re-voting by resetting
        if ($code->has_voted) {
            Log::info('🔄 Demo election - allowing re-vote', [
                'user_id' => $user->id,
                'code_id' => $code->id,
            ]);
            
            // Reset for new vote
            $code->update([
                'has_voted' => false,
                'vote_submitted' => false,
                'can_vote_now' => 0,
                'is_code1_usable' => 1,
            ]);
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

        // Code verified successfully
        $this->markCodeAsVerified($code);

        // Record step completion
        if ($voterSlug) {
            try {
                $stepTrackingService = new VoterStepTrackingService();
                $stepTrackingService->completeStep(
                    $voterSlug,
                    $election,
                    1,
                    ['code_verified' => true, 'verified_at' => now()->toIso8601String()]
                );
            } catch (\Exception $e) {
                Log::error('❌ Failed to record step 1 for demo', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Demo code verification successful', [
            'user_id' => $user->id,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        // Redirect to demo agreement page
        $agreementUrl = $voterSlug
            ? route('demo.slug.code.agreement', ['vslug' => $voterSlug->slug])
            : route('demo.code.agreement');

        return redirect($agreementUrl)->with('success', 'Code verified successfully!');
    }

    /**
     * STEP 2: Show agreement page for demo election
     * Route: GET /demo/v/{slug}/vote/agreement
     */
    public function showAgreement(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        // Verify this is a demo election
        if ($election->type !== 'demo') {
            abort(404, 'Demo election not found');
        }

        Log::info('Demo agreement page accessed', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
        ]);

        // Verify user has completed code verification
        $code = DemoCode::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();
        if (!$code || $code->can_vote_now != 1) {
            $redirectUrl = $voterSlug
                ? route('demo.slug.code.create', ['vslug' => $voterSlug->slug])
                : route('demo.code.create');

            return redirect($redirectUrl)
                ->with('error', 'Code verification required before proceeding.');
        }

        // Check if agreement already accepted
        if ($code->has_agreed_to_vote) {
            // Record Step 2 if not already recorded
            if ($voterSlug) {
                try {
                    $stepTracker = new VoterStepTrackingService();
                    $highestStep = $stepTracker->getHighestCompletedStep($voterSlug, $election);

                    if ($highestStep < 2) {
                        $stepTracker->completeStep(
                            $voterSlug,
                            $election,
                            2,
                            ['agreement_accepted' => true, 'auto_recorded' => true]
                        );
                    }
                } catch (\Exception $e) {
                    Log::error('❌ Failed to record step 2 for demo', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $voteUrl = $voterSlug
                ? route('demo.slug.vote.create', ['vslug' => $voterSlug->slug])
                : route('demo.vote.create');

            return redirect($voteUrl)
                ->with('info', 'You have already accepted the agreement.');
        }

        return Inertia::render('DemoCode/Agreement', [
            'user_name' => $user->name,
            'voting_time_minutes' => $code->voting_time_in_minutes ?? 20,
            'agreement_text_nepali' => 'म यो डेमो मतदान प्रणालीमा स्वेच्छाले भाग लिइरहेको छु।',
            'agreement_text_english' => 'I voluntarily participate in this demo voting system.',
            'slug' => $voterSlug ? $voterSlug->slug : null,
            'useSlugPath' => $voterSlug !== null,
            'is_demo' => true,
        ]);
    }

    /**
     * STEP 2 → STEP 3: Process agreement submission for demo election
     * Route: POST /demo/v/{slug}/code/agreement
     */
    public function submitAgreement(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        // Verify this is a demo election
        if ($election->type !== 'demo') {
            abort(404, 'Demo election not found');
        }

        // Validate agreement
        $request->validate([
            'agreement' => 'required|accepted'
        ]);

        // Verify user has completed code verification
        $code = DemoCode::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();
        if (!$code || $code->can_vote_now != 1) {
            return $this->jsonOrRedirect($request, false, 'Code verification required.',
                redirect()->route('demo.slug.code.create', ['vslug' => $voterSlug->slug]));
        }

        // Mark agreement as accepted
        $code->update([
            'has_agreed_to_vote' => 1,
            'has_agreed_to_vote_at' => now(),
            'voting_started_at' => now(),
        ]);

        // Record step 2 completion
        if ($voterSlug) {
            try {
                $stepTrackingService = new VoterStepTrackingService();
                $stepTrackingService->completeStep(
                    $voterSlug,
                    $election,
                    2,
                    ['agreement_accepted' => true, 'accepted_at' => now()->toIso8601String()]
                );
            } catch (\Exception $e) {
                Log::error('❌ Failed to record step 2 for demo', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Demo agreement accepted', [
            'user_id' => $user->id,
            'slug' => $voterSlug ? $voterSlug->slug : null,
        ]);

        // Redirect to demo voting page
        $voteUrl = $voterSlug
            ? route('demo.slug.vote.create', ['vslug' => $voterSlug->slug])
            : route('demo.vote.create');

        return $this->jsonOrRedirect($request, true, 'Agreement accepted!',
            redirect($voteUrl)->with('success', 'Agreement accepted. You may now cast your demo vote.'),
            $voteUrl);
    }

    // ==========================================
    // HELPER METHODS (SAME AS CODECONTROLLER)
    // ==========================================

    private function getUser(Request $request): User
    {
        return $request->attributes->has('voter')
            ? $request->attributes->get('voter')
            : auth()->user();
    }

    private function getElection(Request $request): Election
    {
        return $request->attributes->get('election')
            ?? Election::where('type', 'demo')->first();
    }

    private function isUserEligible(User $user): bool
    {
        // Demo elections: all authenticated users are eligible
        return true;
    }

    private function getOrCreateCode(User $user, Election $election): DemoCode
    {
        // Get code for this specific election
        $code = DemoCode::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();

        // DEMO ELECTIONS: Allow re-voting by resetting
        if ($code && $code->has_voted) {
            Log::info('🔄 Demo election - resetting code for re-voting', [
                'user_id' => $user->id,
                'code_id' => $code->id,
            ]);

            $code->update([
                'has_voted' => false,
                'vote_submitted' => false,
                'can_vote_now' => 0,
                'is_code1_usable' => 1,
                'code1' => $this->generateCode(),
                'code1_sent_at' => now(),
                'has_code1_sent' => 1,
            ]);

            // Send new code
            if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    $user->notify(new SendFirstVerificationCode($user, $code->code1));
                } catch (\Exception $e) {
                    Log::error('Failed to send demo code', ['error' => $e->getMessage()]);
                }
            }

            return $code;
        }

        if (!$code) {
            // Create new code with organisation_id from election
            $code = DemoCode::create([
                'user_id' => $user->id,
                'election_id' => $election->id,
                'organisation_id' => $election->organisation_id,  // NULL for MODE 1, value for MODE 2
                'code1' => $this->generateCode(),
                'code1_sent_at' => now(),
                'has_code1_sent' => 1,
                'client_ip' => $this->clientIP,
                'voting_time_in_minutes' => $this->votingTimeInMinutes,
                'is_code1_usable' => 1,
                'can_vote_now' => 0,
            ]);

            // Send code via email
            if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    $user->notify(new SendFirstVerificationCode($user, $code->code1));
                } catch (\Exception $e) {
                    Log::error('Failed to send demo code email', ['error' => $e->getMessage()]);
                }
            }

            Log::info('New demo verification code created', [
                'user_id' => $user->id,
                'code_id' => $code->id,
                'mode' => $election->organisation_id ? 'MODE 2' : 'MODE 1',
            ]);
        } else {
            // Code exists - check if it needs resending
            $isExpired = $code->code1_sent_at && now()->diffInMinutes($code->code1_sent_at) > $this->votingTimeInMinutes;
            $codeWasUsed = $code->is_code1_usable == 0;

            if ($isExpired && !$codeWasUsed && !$code->has_voted) {
                // Resend new code
                $newCode = $this->generateCode();
                $code->update([
                    'code1' => $newCode,
                    'code1_sent_at' => now(),
                    'has_code1_sent' => 1,
                    'is_code1_usable' => 1,
                    'can_vote_now' => 0,
                ]);

                if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        $user->notify(new SendFirstVerificationCode($user, $newCode));
                    } catch (\Exception $e) {
                        Log::error('Failed to resend demo code', ['error' => $e->getMessage()]);
                    }
                }

                Log::info('Demo code regenerated and resent', [
                    'user_id' => $user->id,
                    'code_id' => $code->id,
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
            return ['success' => false, 'message' => 'Invalid verification code. Please check and try again.'];
        }

        // Check if code is expired
        if ($code->code1_sent_at && now()->diffInMinutes($code->code1_sent_at) > $this->votingTimeInMinutes) {
            return ['success' => false, 'message' => 'Verification code has expired. Please request a new code.'];
        }

        // Check IP rate limiting
        $votesFromIP = DemoCode::where('client_ip', $this->clientIP)->where('has_voted', 1)->count();
        if ($votesFromIP >= $this->maxUseClientIP) {
            return ['success' => false, 'message' => 'Too many demo votes from this IP address.'];
        }

        return ['success' => true, 'message' => 'Code verified successfully.'];
    }

    private function markCodeAsVerified(DemoCode $code): void
    {
        $code->update([
            'can_vote_now' => 1,
            'is_code1_usable' => 0,
            'code1_used_at' => now(),
            'is_codemodel_valid' => true,
            'client_ip' => $this->clientIP,
        ]);
    }

    private function generateCode(): string
    {
        return strtoupper(Str::random(6));
    }

    private function handleAlreadyVerified(Request $request, $voterSlug)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);

        if ($voterSlug) {
            try {
                $stepTrackingService = new VoterStepTrackingService();
                $highestStep = $stepTrackingService->getHighestCompletedStep($voterSlug, $election);

                if ($highestStep < 1) {
                    $stepTrackingService->completeStep(
                        $voterSlug,
                        $election,
                        1,
                        ['code_verified' => true, 'verified_at' => now()->toIso8601String()]
                    );
                }
            } catch (\Exception $e) {
                Log::error('❌ Failed to record step 1', ['error' => $e->getMessage()]);
            }
        }

        $agreementUrl = $voterSlug
            ? route('demo.slug.code.agreement', ['vslug' => $voterSlug->slug])
            : route('demo.code.agreement');

        return redirect($agreementUrl)->with('info', 'Code already verified. Continue to agreement.');
    }

    private function redirectToDashboard(string $message)
    {
        return redirect()->route('dashboard')->with('error', $message);
    }

    private function handleValidationError(\Illuminate\Validation\ValidationException $e, Request $request)
    {
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
```

### 3. Create DemoCode/Create.vue (Clone of Code/Create.vue)
```vue
<!-- resources/js/Pages/DemoCode/Create.vue -->
<!-- EXACT COPY of Code/Create.vue, but placed in DemoCode directory -->

<template>
    <election-layout>
        <!-- Workflow Step Indicator - Step 1/5 -->
        <div class="w-full bg-gradient-to-br from-gray-50 to-blue-50 py-6 md:py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <WorkflowStepIndicator workflow="VOTING" :currentStep="1" />
            </div>
        </div>

        <div class="mt-4 flex w-full flex-col justify-center">
            <!-- Demo Mode Indicator (NEW) -->
            <div v-if="is_demo" class="bg-purple-100 border-l-4 border-purple-500 p-4 mb-4 max-w-3xl mx-auto rounded-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <div>
                        <p class="text-purple-900 font-medium">
                            {{ organisation_mode === 'org-specific' 
                                ? '🏢 Organisation-Specific Demo Mode' 
                                : '🌐 Public Demo Mode' }}
                        </p>
                        <p class="text-purple-700 text-sm mt-1">
                            {{ organisation_mode === 'org-specific'
                                ? 'Testing with your organisation\'s demo data'
                                : 'Testing with public demo data - visible to everyone' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- IP Mismatch Error Display -->
            <div v-if="$page.props.errors.ip_mismatch" class="bg-amber-50 border-l-4 border-amber-500 p-6 mb-6 rounded-lg shadow-md max-w-3xl mx-auto" role="alert" aria-live="polite">
                <!-- Same content as Code/Create.vue -->
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-amber-900 mb-2">{{ $t('pages.code-create.errors.ip_mismatch_title') }}</h3>
                        <div class="text-sm text-amber-800 whitespace-pre-line">
                            {{ $page.props.errors.ip_mismatch }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header -->
            <div class="my-4 mx-auto bg-purple-600 text-white p-4 rounded-lg text-center shadow-lg max-w-md">
                <div class="text-3xl mb-2">🎮</div>
                <p class="text-xl font-bold">{{ $t('pages.code-create.header.title') }} (Demo)</p>
            </div>

            <!-- Instructions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 max-w-4xl mx-auto">
                <!-- Code Expired Warning -->
                <div v-if="codeExpired" class="p-4 bg-red-50 rounded-lg border-l-4 border-red-500 mb-4">
                    <p class="text-red-900 font-medium flex items-center">
                        <span class="inline-block w-5 h-5 bg-red-600 text-white rounded-full text-xs leading-5 mr-2 flex items-center justify-center">⏱</span>
                        {{ $i18n.locale === 'np' ? 'आपको कोड समाप्त भएको छ' : $i18n.locale === 'de' ? 'Ihr Code ist abgelaufen' : 'Your code has expired' }}
                    </p>
                    <p class="text-red-800 text-sm mt-2">
                        {{ $i18n.locale === 'np' ? 'कृपया नई कोड के लिए हमसे संपर्क करें' : $i18n.locale === 'de' ? 'Bitte kontaktieren Sie uns für einen neuen Code' : 'Please contact us for a new code' }}
                    </p>
                </div>

                <!-- Instructions -->
                <div v-if="!codeExpired" class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <p class="text-gray-900 font-medium mb-3 flex items-center">
                        <span class="inline-block w-5 h-5 bg-blue-600 text-white rounded-full text-xs leading-5 mr-2 flex items-center justify-center">!</span>
                        {{ $t('pages.code-create.instructions.nepali_section') }}
                    </p>
                    <p class="text-gray-800 leading-relaxed mb-1">
                        {{ getInstructions() }}
                    </p>
                    <p v-if="$i18n.locale !== 'en'" class="mt-4 text-sm font-semibold text-amber-800 bg-amber-50 p-3 rounded border-l-4 border-amber-400">
                        {{ $t('pages.code-create.instructions.nepali_spam_warning') }}
                    </p>
                </div>
            </div>

            <!-- Validation Errors -->
            <div class="m-auto">
                <jet-validation-errors class="mx-auto mb-4 text-center" />
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="mx-auto mt-6 w-full text-center">
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 px-6 py-8 max-w-2xl mx-auto">
                    <!-- Code Input -->
                    <div class="mb-8">
                        <label for="voting_code" class="block mb-6">
                            <div class="flex items-center justify-center mb-2">
                                <span class="text-2xl mr-2">🔑</span>
                                <p class="text-xl font-bold text-gray-900">{{ $t('pages.code-create.form.code_label') }}</p>
                            </div>
                        </label>

                        <div class="relative">
                            <!-- Code Input Field -->
                            <input
                                id="voting_code"
                                type="text"
                                v-model="form.voting_code"
                                class="w-full px-6 py-5 text-3xl font-mono text-center tracking-widest border-3 rounded-2xl focus:ring-4 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 uppercase shadow-md"
                                :class="{
                                    'border-red-300 bg-red-50': form.errors.voting_code,
                                    'border-green-400 bg-green-50': form.voting_code && form.voting_code.length === 6 && !form.errors.voting_code,
                                    'border-gray-300': !form.voting_code || form.voting_code.length !== 6 || !form.errors.voting_code
                                }"
                                :placeholder="$t('pages.code-create.form.code_placeholder')"
                                maxlength="6"
                                autocomplete="off"
                                autofocus
                                @keypress.enter="handleSubmit"
                            />

                            <!-- Character Indicators -->
                            <div class="mt-6 flex justify-center space-x-2">
                                <div v-for="i in 6" :key="i"
                                     class="w-12 h-12 rounded-lg border-2 flex items-center justify-center font-bold text-lg transition-all"
                                     :class="{
                                         'border-purple-500 bg-purple-50': (form.voting_code && form.voting_code.length >= i),
                                         'border-gray-300': !form.voting_code || form.voting_code.length < i
                                     }">
                                    <span v-if="form.voting_code && form.voting_code.length >= i"
                                          class="text-gray-900">
                                        {{ form.voting_code.charAt(i-1) }}
                                    </span>
                                    <span v-else class="text-gray-400">_</span>
                                </div>
                            </div>

                            <!-- Status Indicators -->
                            <div class="mt-6 flex items-center justify-between px-2">
                                <div class="text-sm text-gray-600">
                                    <span v-if="form.voting_code">
                                        {{ form.voting_code.length }}/6 {{ $t('pages.code-create.form.characters_label') }}
                                    </span>
                                    <span v-else>{{ $t('pages.code-create.form.enter_instruction') }}</span>
                                </div>
                                <div v-if="form.voting_code && form.voting_code.length === 6 && !form.errors.voting_code"
                                     class="flex items-center text-green-600 font-semibold">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ $t('pages.code-create.form.ready_text') }}
                                </div>
                            </div>

                            <!-- Validation Errors -->
                            <div v-if="form.errors.voting_code" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-red-700">{{ form.errors.voting_code }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-4">
                        <button
                            type="submit"
                            :disabled="!form.voting_code.trim() || form.voting_code.length !== 6 || codeExpired"
                            class="w-full font-bold py-4 px-6 rounded-lg transition-all shadow-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2"
                            :class="{
                                'bg-purple-600 hover:bg-purple-700 text-white cursor-pointer': form.voting_code.length === 6 && !codeExpired,
                                'bg-gray-300 text-gray-500 cursor-not-allowed': form.voting_code.length !== 6 || codeExpired
                            }"
                        >
                            {{ $t('pages.code-create.form.submit_button') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </election-layout>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import ElectionLayout from "@/Layouts/ElectionLayout";
import WorkflowStepIndicator from "@/Components/Workflow/WorkflowStepIndicator";

export default {
    props: {
        name: String,
        user_id: String,
        state: String,
        code_duration: Number,
        code_expires_in: Number,
        slug: String,
        useSlugPath: Boolean,
        is_demo: Boolean,
        organisation_mode: String, // 'public' or 'org-specific'
    },
    setup(props) {
        const form = useForm({
            voting_code: "",
        });

        function submit() {
            console.log(form.voting_code);

            let submitUrl;
            if (props.useSlugPath && props.slug) {
                submitUrl = `/demo/v/${props.slug}/code`;  // ← DEMO PREFIX
            } else {
                submitUrl = "/demo/codes";  // ← DEMO PREFIX
            }

            console.log('Submitting to DEMO URL:', submitUrl);
            form.post(submitUrl);
        }

        return { form, submit };
    },
    computed: {
        codeExpired() {
            return this.code_duration >= this.code_expires_in;
        }
    },
    methods: {
        getInstructions() {
            const locale = this.$i18n.locale;
            const minutesElapsed = this.code_duration;
            const minutesRemaining = Math.max(0, this.code_expires_in - this.code_duration);

            if (locale === 'np') {
                return `${this.$t('pages.code-create.instructions.nepali_intro')} ${minutesElapsed} ${this.$t('pages.code-create.instructions.nepali_ago')} ${minutesRemaining} ${this.$t('pages.code-create.instructions.nepali_remaining')}`;
            } else if (locale === 'de') {
                return `${this.$t('pages.code-create.instructions.english_intro')} ${minutesElapsed} ${this.$t('pages.code-create.instructions.english_ago')} ${minutesRemaining} ${this.$t('pages.code-create.instructions.english_remaining')}`;
            } else {
                return `${this.$t('pages.code-create.instructions.english_intro')} ${minutesElapsed} ${this.$t('pages.code-create.instructions.english_ago')} ${minutesRemaining} ${this.$t('pages.code-create.instructions.english_remaining')}`;
            }
        },
        handleSubmit() {
            if (this.form.voting_code.trim()) {
                this.submit();
            }
        }
    },
    components: {
        ElectionLayout,
        JetValidationErrors,
        WorkflowStepIndicator,
    },
};
</script>
```

### 4. Create DemoCode/Agreement.vue (similar clone)

### 5. Add Routes for DemoCode
```php
// routes/web.php

// Demo Code Routes
Route::prefix('demo')->name('demo.')->group(function () {
    // With slug
    Route::get('/v/{vslug}/code/create', [DemoCodeController::class, 'create'])
        ->name('slug.code.create');
    Route::post('/v/{vslug}/code', [DemoCodeController::class, 'store'])
        ->name('slug.code.store');
    Route::get('/v/{vslug}/vote/agreement', [DemoCodeController::class, 'showAgreement'])
        ->name('slug.code.agreement');
    Route::post('/v/{vslug}/code/agreement', [DemoCodeController::class, 'submitAgreement'])
        ->name('slug.code.agreement.submit');
    
    // Without slug
    Route::get('/code/create', [DemoCodeController::class, 'create'])
        ->name('code.create');
    Route::post('/codes', [DemoCodeController::class, 'store'])
        ->name('code.store');
    Route::get('/code/agreement', [DemoCodeController::class, 'showAgreement'])
        ->name('code.agreement');
    Route::post('/code/agreement', [DemoCodeController::class, 'submitAgreement'])
        ->name('code.agreement.submit');
});
```

### 6. Create Tests for DemoCodeController
```php
// tests/Feature/DemoCodeControllerTest.php

public function test_demo_code_page_loads()
{
    $user = User::factory()->create();
    $demoElection = Election::factory()->create(['type' => 'demo']);
    
    $response = $this->actingAs($user)
        ->get('/demo/code/create');
    
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('DemoCode/Create')
        ->where('is_demo', true)
    );
}

public function test_demo_code_verification_works()
{
    $user = User::factory()->create();
    $demoElection = Election::factory()->create(['type' => 'demo']);
    
    // Create code
    $code = DemoCode::create([
        'user_id' => $user->id,
        'election_id' => $demoElection->id,
        'code1' => 'TEST12',
        'has_code1_sent' => true,
        'is_code1_usable' => 1,
    ]);
    
    $response = $this->actingAs($user)
        ->post('/demo/codes', [
            'voting_code' => 'TEST12'
        ]);
    
    $response->assertRedirect();
    $code->refresh();
    $this->assertEquals(1, $code->can_vote_now);
}

public function test_demo_code_allows_revoting()
{
    $user = User::factory()->create();
    $demoElection = Election::factory()->create(['type' => 'demo']);
    
    // Create code that has already voted
    $code = DemoCode::create([
        'user_id' => $user->id,
        'election_id' => $demoElection->id,
        'code1' => 'TEST12',
        'has_voted' => true,
        'has_code1_sent' => true,
        'is_code1_usable' => 0,
    ]);
    
    // Get code creation page
    $response = $this->actingAs($user)
        ->get('/demo/code/create');
    
    // Should reset the code
    $code->refresh();
    $this->assertEquals(false, $code->has_voted);
    $this->assertNotEquals('TEST12', $code->code1); // New code generated
}
```

## IMPLEMENTATION CHECKLIST

- [ ] Create DemoCode model (if not exists)
- [ ] Create DemoCodeController with all 5 methods (create, store, showAgreement, submitAgreement, helper methods)
- [ ] Create DemoCode/Create.vue (clone of Code/Create.vue with purple theme)
- [ ] Create DemoCode/Agreement.vue (clone of Code/Agreement.vue)
- [ ] Add routes in web.php with `/demo` prefix
- [ ] Update ElectionMiddleware to handle demo routes
- [ ] Create tests for DemoCodeController
- [ ] Run migrations to ensure demo_codes table exists
- [ ] Test MODE 1 (public demo) and MODE 2 (org-specific demo) flows

## SUCCESS CRITERIA

- [ ] DemoCodeController mirrors CodeController exactly (same logic)
- [ ] Demo votes are stored in demo_codes table, not codes table
- [ ] MODE 1: organisation_id = NULL in demo_codes
- [ ] MODE 2: organisation_id = X in demo_codes (matches user's org)
- [ ] Users can vote multiple times in demo elections
- [ ] Demo voting UI has purple theme to distinguish from real voting
- [ ] All routes work with and without slugs
- [ ] Step tracking works correctly
- [ ] All tests pass
```