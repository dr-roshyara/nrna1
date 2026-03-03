  ⎿  

  ⎿  Bash(pwd)
     Waiting…
  ⎿  

  ⎿  Response:
       Perfect. Now I'll create a comprehensive implementation plan:

       Design Analysis Complete

       I've thoroughly analyzed the codebase and identified the root causes and solution architecture for the demo code creation circular redirect loop.

       ---
       Root Cause Analysis

       1. Unconditional Step Reset (Lines 101-115 in DemoCodeController.php)

       The create() method always resets current_step = 1 whenever accessed, even if the user has already verified their code and should be on the agreement page.

       // ❌ PROBLEMATIC CODE
       if ($voterSlug && $election->type === 'demo') {
           $voterSlug->current_step = 1; // Always resets to step 1
           $voterSlug->save();
       }

       2. Missing Code Usability Check

       The method doesn't check if the code has been used (is_code1_usable == 0 OR code1_used_at !== null), which is critical for determining if a user should get a new code.

       3. No Redirect for Already-Verified Codes

       While there's a warning logged (lines 88-93), there's no automatic redirect to the agreement page when can_vote_now == 1, leaving users stuck on the create page.

       4. Configuration Mismatch

       config/election_steps.php contains only real election routes (slug.code.), not demo routes (slug.demo-code.), causing the middleware to fail for demo elections.

       ---
       Solution Architecture

       Phase 1: Fix Code Usability Logic (Primary Fix)

       Objective: Implement proper code lifecycle management based on state flags.

       Logic Flow:
       1. Get or create code for user/election
       2. Check code state:
          a. is_code1_usable == 1 AND code1_used_at == NULL → Code is fresh/unused
          b. is_code1_usable == 0 OR code1_used_at !== NULL → Code already used

       3. If code is used:
          a. Check has_voted flag:
             - has_voted == true → User completed voting, generate new code for re-voting
             - has_voted == false → User is mid-voting, DON'T reset anything

       4. If can_vote_now == 1:
          - User already verified, redirect to agreement page
          - DON'T show create page

       Key State Transitions:
       STATE 1: Fresh Code
       - is_code1_usable = 1
       - code1_used_at = NULL
       - can_vote_now = 0
       - has_voted = false
       → User should verify code

       STATE 2: Code Verified
       - is_code1_usable = 0 (or 1 in SIMPLE mode)
       - code1_used_at = <timestamp>
       - can_vote_now = 1
       - has_voted = false
       → User should be on agreement page (redirect if on create page)

       STATE 3: Vote Completed
       - is_code1_usable = 0
       - code1_used_at = <timestamp>
       - can_vote_now = 1
       - has_voted = true
       → Demo: Generate new code for re-voting
       → Real: Block access

       Phase 2: Remove Unconditional Step Reset

       Current Code (Lines 101-115):
       // ❌ WRONG: Always resets step
       if ($voterSlug && $election->type === 'demo') {
           $voterSlug->current_step = 1;
           $voterSlug->save();
       }

       Fixed Logic:
       // ✅ RIGHT: Only reset if user has completed voting
       if ($voterSlug && $election->type === 'demo' && $code->has_voted) {
           $voterSlug->current_step = 1;
           $voterSlug->save();

           // Also reset voter_slug_steps to allow re-voting
           VoterSlugStep::where('voter_slug_id', $voterSlug->id)
               ->where('election_id', $election->id)
               ->delete();
       }

       Phase 3: Add Auto-Redirect for Verified Codes

       Current Code (Lines 88-93):
       // ⚠️ Only logs warning, no redirect
       if ($existingCode && $existingCode->can_vote_now == 1) {
           Log::warning('User already has verified code...');
       }

       Fixed Logic:
       // ✅ Check BEFORE getOrCreateCode() to avoid unnecessary processing
       if ($existingCode && $existingCode->can_vote_now == 1 && !$existingCode->has_voted) {
           // User verified but hasn't voted yet - redirect to agreement
           $agreementUrl = $voterSlug
               ? route('slug.demo-code.agreement', ['vslug' => $voterSlug->slug])
               : route('demo-code.agreement');

           return redirect($agreementUrl)
               ->with('info', 'Code already verified. Continue to agreement.');
       }

       Phase 4: Update Configuration (election_steps.php)

       Current Config:
       return [
           1 => 'slug.code.create',    // Real elections only
           2 => 'slug.code.agreement',
           3 => 'slug.vote.create',
           4 => 'slug.vote.verify',
           5 => 'slug.vote.complete',
       ];

       Fixed Config (Two Options):

       Option A: Separate Demo Steps:
       return [
           'real' => [
               1 => 'slug.code.create',
               2 => 'slug.code.agreement',
               3 => 'slug.vote.create',
               4 => 'slug.vote.verify',
               5 => 'slug.vote.complete',
           ],
           'demo' => [
               1 => 'slug.demo-code.create',
               2 => 'slug.demo-code.agreement',
               3 => 'slug.demo-vote.create',
               4 => 'slug.demo-vote.verify',
               5 => 'slug.demo-vote.complete',
           ],
       ];

       Option B: Dynamic Route Mapping (Recommended):
       return [
           1 => 'code.create',    // Prefix will be added based on election type
           2 => 'code.agreement',
           3 => 'vote.create',
           4 => 'vote.verify',
           5 => 'vote.complete',
       ];

       Then modify EnsureVoterStepOrder.php:
       $election = \App\Models\Election::withoutGlobalScopes()->find($vslug->election_id);
       $routePrefix = $election->type === 'demo' ? 'slug.demo-' : 'slug.';
       $map = collect(config('election_steps'))->mapWithKeys(function ($route, $step) use ($routePrefix) {
           return [$step => $routePrefix . $route];
       });

       ---
       Implementation Plan

       Step 1: Refactor create() Method in DemoCodeController

       Changes to Lines 56-189:

       1. Move existing code check earlier (before getOrCreateCode)
       2. Add redirect for verified codes (can_vote_now == 1 && !has_voted)
       3. Conditional step reset (only if has_voted == true)
       4. Proper code usability check in getOrCreateCode method

       Step 2: Update getOrCreateCode() Method

       Changes to Lines 677-898:

       1. Check code usability first:
       if ($code && ($code->is_code1_usable == 0 || $code->code1_used_at !== null)) {
           // Code has been used
           if ($code->has_voted && $election->type === 'demo') {
               // Reset for re-voting
           } else {
               // User is mid-voting, don't reset
           }
       }
       2. Separate re-voting logic from code regeneration logic
       3. Only send new code when truly needed (expired or re-voting)

       Step 3: Update Configuration File

       File: config/election_steps.php

       Add demo route mappings (use Option B for maintainability).

       Step 4: Update Middleware

       File: app/Http/Middleware/EnsureVoterStepOrder.php

       Modify to dynamically resolve routes based on election type.

       ---
       Verification Strategy

       Test Case 1: First-Time Voter

       1. User visits /v/{slug}/demo-code/create
       2. Code is generated and sent
       3. User verifies code → Redirects to agreement ✅
       4. User refreshes /demo-code/create → Auto-redirects to agreement ✅

       Test Case 2: Mid-Voting User

       1. User verifies code (can_vote_now = 1, has_voted = false)
       2. User is on agreement page
       3. User manually navigates to /demo-code/create
       4. System detects verified code → Auto-redirects to agreement ✅
       5. No step reset occurs ✅

       Test Case 3: Re-Voting User (Demo Only)

       1. User completes vote (has_voted = true)
       2. User returns to /demo-code/create
       3. System detects completed vote → Generates NEW code ✅
       4. Resets voter_slug steps ✅
       5. Sends new email with new code ✅

       Test Case 4: Expired Code

       1. Code sent 30+ minutes ago
       2. User hasn't verified yet (can_vote_now = 0)
       3. User visits /demo-code/create
       4. System detects expiry → Generates new code ✅
       5. No redirect occurs (user needs to verify new code) ✅

       ---
       Code Change Summary

       Files to Modify:

       1. app/Http/Controllers/Demo/DemoCodeController.php
         - create() method (Lines 56-189)
         - getOrCreateCode() method (Lines 677-898)
       2. config/election_steps.php
         - Add demo route mappings
       3. app/Http/Middleware/EnsureVoterStepOrder.php (Optional)
         - Dynamic route resolution based on election type

       Files to Test:

       1. VoterStepTrackingService.php - Ensure step tracking works with resets
       2. VoterSlug.php - Verify step reset logic
       3. DemoCode.php - Check state flag consistency

       ---
       Risk Assessment

       Low Risk Changes:

       - Adding redirect for verified codes (backward compatible)
       - Configuration update (additive only)

       Medium Risk Changes:

       - Conditional step reset (affects re-voting flow)
       - Code usability check logic (needs thorough testing)

       High Risk Changes:

       - None (all changes are defensive and preserve existing functionality)

       ---
       Rollback Strategy

       If issues occur:
       1. Revert create() method changes
       2. Keep existing unconditional step reset (Line 101-115)
       3. Monitor logs for redirect loops
       4. Apply hotfix to add simple redirect check

       ---
       Critical Files for Implementation

       - C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\app\Http\Controllers\Demo\DemoCodeController.php - Core controller logic to modify (create() and getOrCreateCode() methods)
       - C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\config\election_steps.php - Configuration file to add demo route mappings
       - C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\app\Http\Middleware\EnsureVoterStepOrder.php - Middleware to update for dynamic route resolution
       - C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\app\Models\DemoCode.php - Model to understand state flags and add helper methods
       - C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\app\Services\VoterStepTrackingService.php - Service to verify step tracking compatibility with resets
  ⎿  Done (13 tool uses · 62.8k tokens · 3m 7s)

∴ Thinking…

  Great! Now I have comprehensive exploration and planning completed. Let me read the critical files identified to deepen my understanding before writing the final plan.

  The Plan agent has provided excellent analysis and a detailed solution. Now I need to:

  1. Read the critical files to verify the analysis
  2. Write the final plan to the plan file
  3. Call ExitPlanMode

  Let me read the DemoCodeController to see the exact current implementation.

● Now let me read the critical controller file to verify the analysis and finalize the plan.
Excellent work! The exploration and planning are thorough. Now let's finalize the plan. Based on the analysis, here's the complete implementation plan for Claude:

---

**Final Implementation Plan for Claude:**

## Task: Fix Circular Dependency in Demo Code Creation Flow

### Problem Summary
Users with verified codes are stuck in an infinite loop between `/demo-code/create` and `/demo-code/agreement` due to:
1. Unconditional step reset in `create()` method
2. Missing code usability checks (`is_code1_usable`, `code1_used_at`)
3. No redirect for already-verified codes
4. Configuration mismatch in `election_steps.php`

### Required Changes

#### 1. Modify `DemoCodeController.php` - `create()` Method

Replace the current implementation with this logic flow:

```php
public function create(Request $request)
{
    $user = $this->getUser($request);
    $election = $this->getElection($request);
    $voterSlug = $request->attributes->get('voter_slug');

    // STEP 1: Check for existing code and redirect if verified
    $existingCode = DemoCode::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->first();

    // If code exists and is verified but user hasn't voted, redirect to agreement
    if ($existingCode && $existingCode->can_vote_now == 1 && !$existingCode->has_voted) {
        Log::info('🔄 Redirecting verified user to agreement', [
            'user_id' => $user->id,
            'code_id' => $existingCode->id,
        ]);
        
        $agreementUrl = $voterSlug
            ? route('slug.demo-code.agreement', ['vslug' => $voterSlug->slug])
            : route('demo-code.agreement');
            
        return redirect($agreementUrl)->with('info', 'Code already verified. Continue to agreement.');
    }

    // STEP 2: Get or create code
    $code = $this->getOrCreateCode($user, $election);

    // STEP 3: Check code usability - if code is used, generate new one
    if ($code->is_code1_usable == 0 || $code->code1_used_at !== null) {
        Log::info('🔄 Code unusable - generating new one', [
            'user_id' => $user->id,
            'code_id' => $code->id,
            'is_code1_usable' => $code->is_code1_usable,
            'code1_used_at' => $code->code1_used_at,
        ]);
        
        $code->code1 = strtoupper(Str::random(6));
        $code->code1_sent_at = now();
        $code->has_code1_sent = true;
        $code->is_code1_usable = 1;
        $code->code1_used_at = null;
        $code->can_vote_now = 0;
        $code->save();
        
        // Send new code via email
        try {
            $user->notify(new SendFirstVerificationCode($user, $code->code1));
            Log::info('📧 New code sent (previous code was unusable)');
        } catch (\Exception $e) {
            Log::error('❌ Failed to send new code: ' . $e->getMessage());
        }
    }

    // STEP 4: Conditional step reset - ONLY if user has already voted
    if ($voterSlug && $election->type === 'demo' && $code->has_voted) {
        $oldStep = $voterSlug->current_step;
        $voterSlug->current_step = 1;
        $voterSlug->save();
        
        // Also clear step tracking records for clean re-vote
        if (class_exists('App\Models\VoterSlugStep')) {
            \App\Models\VoterSlugStep::where('voter_slug_id', $voterSlug->id)
                ->where('election_id', $election->id)
                ->delete();
        }
        
        Log::info('🔄 Reset voter slug for re-voting (user already voted)', [
            'voter_slug_id' => $voterSlug->id,
            'old_step' => $oldStep,
            'user_id' => $user->id,
        ]);
    } else {
        Log::info('⏭️ Skipping reset - user has not completed voting', [
            'user_id' => $user->id,
            'has_voted' => $code->has_voted ?? false,
        ]);
    }

    // STEP 5: Continue with page rendering (NO REDIRECTS)
    // ... rest of the existing rendering logic ...
}
```

#### 2. Update `getOrCreateCode()` Method

```php
private function getOrCreateCode(User $user, Election $election): DemoCode
{
    // Get existing code
    $code = DemoCode::withoutGlobalScopes()
        ->where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->first();
    
    // If no code exists, create new one
    if (!$code) {
        $code = DemoCode::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'code1' => strtoupper(Str::random(6)),
            'code1_sent_at' => now(),
            'has_code1_sent' => 1,
            'client_ip' => $this->clientIP,
            'voting_time_in_minutes' => $this->votingTimeInMinutes,
            'is_code1_usable' => 1,
            'can_vote_now' => 0,
            'has_voted' => false,
        ]);
        
        // Send email notification
        $this->sendVerificationEmail($user, $code);
        
        return $code;
    }
    
    // DEMO: Allow re-voting by resetting flags
    if ($code->has_voted && $election->type === 'demo') {
        Log::info('🔄 Preparing for re-vote - resetting voting flags', [
            'user_id' => $user->id,
            'code_id' => $code->id,
        ]);
        
        $code->update([
            'has_voted' => false,
            'vote_submitted' => false,
            'can_vote_now' => 0,
        ]);
    }
    
    return $code;
}

private function sendVerificationEmail(User $user, DemoCode $code): void
{
    if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
        try {
            $user->notify(new SendFirstVerificationCode($user, $code->code1));
            Log::info('📧 Verification email sent', [
                'user_id' => $user->id,
                'code_id' => $code->id,
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Failed to send verification email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
```

#### 3. Update `config/election_steps.php`

```php
<?php

return [
    // Step mapping for both real and demo elections
    // The actual route name will be built dynamically based on election type
    
    1 => 'code.create',      // Base route - will become slug.code.create or slug.demo-code.create
    2 => 'code.agreement',   // Base route - will become slug.code.agreement or slug.demo-code.agreement
    3 => 'vote.create',      // Base route - will become slug.vote.create or slug.demo-vote.create
    4 => 'vote.verify',      // Base route - will become slug.vote.verify or slug.demo-vote.verify
    5 => 'vote.complete',    // Base route - will become slug.vote.complete or slug.demo-vote.complete
];
```

#### 4. Update `EnsureVoterStepOrder.php` Middleware

In the `handle()` method, add dynamic route resolution:

```php
public function handle($request, $next)
{
    $vslug = $request->attributes->get('voter_slug');
    
    if (!$vslug) {
        return $next($request);
    }
    
    // Get election to determine type
    $election = Election::withoutGlobalScopes()->find($vslug->election_id);
    
    // Build route map based on election type
    $routePrefix = $election && $election->type === 'demo' ? 'slug.demo-' : 'slug.';
    
    $stepRoutes = collect(config('election_steps'))->mapWithKeys(function ($route, $step) use ($routePrefix) {
        // Special handling for different route patterns
        if ($route === 'code.create') {
            return [$step => $routePrefix . 'code.create'];
        }
        if ($route === 'code.agreement') {
            return [$step => $routePrefix . 'code.agreement'];
        }
        if ($route === 'vote.create') {
            $prefix = $election && $election->type === 'demo' ? 'slug.demo-vote.create' : 'slug.vote.create';
            return [$step => $prefix];
        }
        // ... handle other routes similarly
        return [$step => $routePrefix . $route];
    })->toArray();
    
    // Rest of the existing middleware logic using $stepRoutes instead of config('election_steps')
    // ...
}
```

### Testing Checklist

After implementation, verify:

- [ ] First-time user: Code created, email sent, stays on create page
- [ ] After code verification: Redirects to agreement page
- [ ] Manually navigating back to create: Redirects to agreement
- [ ] After voting: Can revote (new code generated, email sent)
- [ ] Expired code: New code generated automatically
- [ ] No infinite loops in logs

### Rollback Plan

If issues occur:
1. Revert changes to `DemoCodeController.php`
2. Keep the configuration change (it's additive and safe)
3. Monitor logs for redirect patterns

---

**Please implement these changes and test the flow.**
You're right! I have the complete DemoCodeController. Let me review it and provide the exact code changes needed based on the actual file:

---

## Exact Code Changes for `DemoCodeController.php`

### 1. Update the `create()` method - Around line 56-189

**Find this section (around line 73-93):**
```php
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
```

**Replace with:**
```php
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

// 🚀 NEW: If code is already verified and user hasn't voted, redirect to agreement
if ($existingCode && $existingCode->can_vote_now == 1 && !$existingCode->has_voted) {
    Log::info('🔄 [DEMO-CREATE] Redirecting verified user to agreement page', [
        'user_id' => $user->id,
        'code_id' => $existingCode->id,
    ]);
    
    $agreementUrl = $voterSlug
        ? route('slug.demo-code.agreement', ['vslug' => $voterSlug->slug])
        : route('demo-code.agreement');
        
    return redirect($agreementUrl)->with('info', 'Code already verified. Continue to agreement.');
}
```

### 2. Update the code usability check - After `getOrCreateCode()` call (around line 95-100)

**Find this section:**
```php
// Get or create code record for this election
$code = $this->getOrCreateCode($user, $election);
```

**Add this after it:**
```php
// Get or create code record for this election
$code = $this->getOrCreateCode($user, $election);

// 🚀 NEW: Check if code is usable - if not, generate new one
if ($code->is_code1_usable == 0 || $code->code1_used_at !== null) {
    Log::info('🔄 [DEMO-CREATE] Code unusable - generating new one', [
        'user_id' => $user->id,
        'code_id' => $code->id,
        'is_code1_usable' => $code->is_code1_usable,
        'code1_used_at' => $code->code1_used_at,
    ]);
    
    // Generate new code and reset flags
    $code->code1 = strtoupper(Str::random(6));
    $code->code1_sent_at = now();
    $code->has_code1_sent = true;
    $code->is_code1_usable = 1;
    $code->code1_used_at = null;
    $code->can_vote_now = 0;
    $code->save();
    
    // Send new code via email
    try {
        $user->notify(new SendFirstVerificationCode($user, $code->code1));
        Log::info('📧 [DEMO-CREATE] New code sent (previous code was unusable)', [
            'user_id' => $user->id,
            'code_id' => $code->id,
        ]);
    } catch (\Exception $e) {
        Log::error('❌ [DEMO-CREATE] Failed to send new code', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
    }
}
```

### 3. Update the step reset logic - Around line 101-115

**Find this section:**
```php
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
```

**Replace with:**
```php
// 🚀 FIXED: Only reset voter slug step if user has ALREADY VOTED (re-voting scenario)
if ($voterSlug && $election->type === 'demo') {
    // Check if user has already completed voting
    $hasCompletedVoting = $code && $code->has_voted === true;
    
    if ($hasCompletedVoting) {
        $oldStep = $voterSlug->current_step;
        $voterSlug->current_step = 1; // Reset to step 1 (code entry)
        $voterSlug->save();
        
        // Also clear step tracking records for clean re-vote
        if (class_exists('App\Models\VoterSlugStep')) {
            \App\Models\VoterSlugStep::where('voter_slug_id', $voterSlug->id)
                ->where('election_id', $election->id)
                ->delete();
        }
        
        Log::info('🔄 [DEMO] Reset voter slug step for re-voting (user already voted)', [
            'voter_slug_id' => $voterSlug->id,
            'old_step' => $oldStep,
            'user_id' => $user->id,
            'code_id' => $code->id,
            'has_voted' => $code->has_voted,
        ]);
    } else {
        Log::info('⏭️ [DEMO] Skipping reset - user has not completed voting yet', [
            'user_id' => $user->id,
            'can_vote_now' => $code ? $code->can_vote_now : 'no_code',
            'has_voted' => $code ? $code->has_voted : 'no_code',
        ]);
    }
}
```

### 4. Update the `getOrCreateCode()` method - Around line 677-898

**Find the re-voting section (around line 800-830):**
```php
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
        'organisation_id' => $election->organisation_id,
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

    // Send new code via email
    if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
        try {
            $user->notify(new SendFirstVerificationCode($user, $code->code1));
            Log::info('✅ [DEMO] New demo voting code sent', [
                'user_id' => $user->id,
                'code_id' => $code->id,
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
```

**This section is already correct!** It properly handles re-voting by resetting flags and generating a new code. The issue is that the step reset in `create()` was happening for ALL users, including those who haven't voted yet.

### Summary of Changes:

1. **Added redirect** for verified codes (`can_vote_now == 1 && !has_voted`)
2. **Added code usability check** to regenerate unusable codes
3. **Fixed step reset** to only happen when user has already voted (`has_voted == true`)
4. **No changes needed** to the re-voting logic in `getOrCreateCode()` - it's already correct

These changes will break the circular dependency loop while preserving all intended functionality.
