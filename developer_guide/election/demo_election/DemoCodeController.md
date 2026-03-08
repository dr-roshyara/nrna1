# DemoCodeController Developer Guide

## Overview

The `DemoCodeController` manages the **code verification workflow** for demo elections. It's a 1:1 mirror of the real `CodeController` with modifications for testing and unlimited re-voting.

**File Location:** `app/Http/Controllers/Demo/DemoCodeController.php`

**Key Responsibility:** Handle STEP 1 and STEP 2 of the voting workflow:
- STEP 1: Code entry and verification
- STEP 2: Terms & conditions agreement

---

## Architecture

### Class Structure

```php
class DemoCodeController extends Controller
{
    private $clientIP;           // User's IP address (for rate limiting)
    private $maxUseClientIP;     // Max uses from same IP
    private $votingTimeInMinutes; // Code expiration window (30 min)

    // Public methods
    public function create(Request $request)     // Display code entry form
    public function store(Request $request)      // Verify submitted code
    public function showAgreement(Request $request) // Display agreement page
    public function agreeToTerms(Request $request)  // Process agreement

    // Private methods
    private function getOrCreateCode()           // Core code lifecycle
    private function verifyCode()                // Code validation
    private function markCodeAsVerified()        // Update database
}
```

---

## Code Lifecycle States

The `DemoCode` model has 3 distinct states. Understanding these is **critical** for debugging.

### STATE 1: Fresh Code (Initial)

**Scenario:** New user accesses voting for first time

**Flags:**
```php
is_code1_usable     = 1          // Code has NOT been used
code1_used_at       = NULL       // Never verified
can_vote_now        = 0          // Not allowed to vote yet
code1               = "ABC123"   // Random 6-char code
code1_sent_at       = NOW()      // Just generated
has_code1_sent      = 1          // Email sent
has_voted           = false      // Voting incomplete
```

**User Action:** Receives code via email → Enters code in form

**Flow:**
```
GET /v/{slug}/demo-code/create
  ↓
DemoCodeController::create()
  ├─ Checks for existing code (none)
  ├─ Calls getOrCreateCode()
  │   └─ Creates new DemoCode record
  ├─ Sends email with code
  └─ Renders form

POST /v/{slug}/demo-code
  ↓
DemoCodeController::store()
  ├─ Validates code matches
  ├─ Calls verifyCode()
  │   └─ Checks is_code1_usable=1
  ├─ Calls markCodeAsVerified()
  │   └─ Sets is_code1_usable=0, code1_used_at=NOW()
  │   └─ Sets can_vote_now=1
  └─ Redirects to agreement page
```

---

### STATE 2: Code Verified (Mid-Voting) ← CRITICAL STATE

**Scenario:** User verified code but hasn't completed voting yet

**Flags:**
```php
is_code1_usable     = 0          // Code HAS been used
code1_used_at       = TIMESTAMP  // When user verified
can_vote_now        = 1          // Allowed to vote
code1               = "ABC123"   // Same code
has_voted           = false      // Voting NOT complete
vote_submitted      = 0          // No votes submitted yet
```

**Voter Slug State:**
```php
current_step        = 2          // On agreement page
step_meta           = {}         // No step tracking yet
```

**User Location:** Agreement page → Should NOT reset

⚠️ **THIS IS THE STATE THAT HAD THE BUG**

**Issue (Pre-Fix):**
```
USER ACCESSES: GET /v/{slug}/demo-code/create (wrong page for state 2)

OLD BEHAVIOR:
  create() {
      if ($voterSlug && $election->type === 'demo') {
          $voterSlug->current_step = 1;  // ❌ WRONG! Resets mid-voting
          $voterSlug->save();
      }
  }
  // Result: User stuck in loop
```

**Fix Applied:**
```php
if ($voterSlug && $election->type === 'demo' && $code->has_voted) {
    // Only reset if voting COMPLETED
    $voterSlug->current_step = 1;
    // Clear step history
    VoterSlugStep::where('voter_slug_id', ...)
        ->where('election_id', ...)
        ->delete();
}
```

**Expected Behavior (Post-Fix):**
```
USER IN STATE 2:
  - can_vote_now = 1 (verified)
  - has_voted = false (not voted)

IF user navigates to /demo-code/create:
  ✅ Step is NOT reset (has_voted=false)
  ✅ No new code generated
  ✅ Page renders normally
  ✅ User can navigate to agreement page
```

---

### STATE 3: Vote Completed (Re-Voting Ready)

**Scenario:** User completed voting in demo election and returns

**Flags:**
```php
is_code1_usable     = 0          // Code exhausted
code1_used_at       = TIMESTAMP  // Verified timestamp
can_vote_now        = 1          // Was allowed to vote
has_voted           = true       // ← KEY FLAG FOR RE-VOTING
has_agreed_to_vote  = 1          // Accepted terms
code2_used_at       = TIMESTAMP  // Submitted votes
```

**Voter Slug State:**
```php
current_step        = 5          // Completed election
```

**User Action:** Returns to `/v/{slug}/demo-code/create` to vote again

**Flow (Demo Re-Voting):**
```
GET /v/{slug}/demo-code/create
  ↓
getOrCreateCode()
  ├─ Gets existing code (has_voted=true)
  ├─ Checks: has_voted == true? ✅ YES
  ├─ Generates NEW code:
  │   ├─ code1 = random 6 chars
  │   ├─ code1_sent_at = NOW()
  │   ├─ is_code1_usable = 1 (fresh)
  │   ├─ code1_used_at = NULL (not used)
  │   ├─ can_vote_now = 0 (needs new verify)
  │   └─ has_voted = false (reset for new vote)
  ├─ Sends new code via email
  └─ Returns regenerated code

  // Voter slug step is also reset
  if ($code->has_voted) {
      $voterSlug->current_step = 1;
      VoterSlugStep::where(...)->delete();
  }

Renders: Fresh code form (like new user)
```

---

## Code Expiration Logic

**Voting Window:** 30 minutes (`$this->votingTimeInMinutes`)

**Expiration Check (Added in Fix):**

```php
// In getOrCreateCode(), FIRST check expiration
if ($code && $code->code1_sent_at) {
    $isExpired = now()->diffInMinutes($code->code1_sent_at)
                 > $this->votingTimeInMinutes; // > 30 min

    if ($isExpired && !$code->has_voted) {
        // Generate new code
        $code->code1 = $this->generateCode();
        $code->code1_sent_at = now();
        $code->is_code1_usable = 1;
        $code->code1_used_at = null;
        $code->can_vote_now = 0;
        $code->save();

        // Send new code
        $user->notify(new SendFirstVerificationCode(...));

        return $code;
    }
}
```

**When Expiration Applies:**
- ✅ Code sent > 30 minutes ago
- ✅ User hasn't voted yet (`has_voted=false`)
- ❌ NOT applied to completed votes (preserves audit trail)

**User Experience:**
```
1. User gets code email
2. Waits 31 minutes (beyond 30-min window)
3. Accesses /demo-code/create
4. getOrCreateCode() detects expiration
5. New code generated + new email sent
6. User must verify new code
7. Original code is invalidated
```

---

## Key Methods

### 1. `create(Request $request)`

**Purpose:** Display the code entry form (STEP 1)

**Route:** `GET /v/{slug}/demo-code/create`

**Flow:**
```php
public function create(Request $request)
{
    // 1. Get current user, election, voter slug
    $user = $this->getUser($request);
    $election = $this->getElection($request);
    $voterSlug = $request->attributes->get('voter_slug');

    // 2. Set organisation context (for multi-tenant scoping)
    session(['current_organisation_id' => $election->organisation_id]);

    // 3. Get or create code
    $code = $this->getOrCreateCode($user, $election);

    // 4. CRITICAL FIX: Reset step ONLY if user completed voting
    if ($voterSlug && $election->type === 'demo' && $code->has_voted) {
        $voterSlug->current_step = 1;
        $voterSlug->save();

        // Clear step tracking for fresh re-vote
        VoterSlugStep::where('voter_slug_id', $voterSlug->id)
            ->where('election_id', $election->id)
            ->delete();
    }

    // 5. Check if code expired (in create() for timing checks)
    if ($minutesSinceSent >= $this->votingTimeInMinutes && $code->has_code1_sent) {
        // Regenerate and send new code
        $code->code1 = Str::random(6);
        $code->code1_sent_at = now();
        $code->save();
        $user->notify(new SendFirstVerificationCode($user, $code->code1));
    }

    // 6. Render form
    return Inertia::render('Code/DemoCode/Create', [
        'name' => $user->name,
        'has_valid_email' => filter_var($user->email, FILTER_VALIDATE_EMAIL),
        'verification_code' => !$hasValidEmail ? $code->code1 : null,
        'election_type' => 'demo',
    ]);
}
```

**Key Decision Points:**
- ✅ Use `withoutGlobalScopes()` for demo elections (organisation_id=NULL)
- ✅ Check step reset ONLY on has_voted
- ✅ Check expiration in both create() and getOrCreateCode()
- ✅ Don't render without a valid code record

**Common Issues:**
```
Issue: "Code not sending after regeneration"
Solution: Check $user->email is valid
          Verify SendFirstVerificationCode notification

Issue: "User stuck on code page"
Solution: Ensure has_voted flag is correct
          Check voter slug step is being updated
```

---

### 2. `getOrCreateCode(User $user, Election $election): DemoCode`

**Purpose:** Core code lifecycle management

**Called From:** `create()`, `store()`

**Logic Flow:**

```php
private function getOrCreateCode(User $user, Election $election): DemoCode
{
    // 1. Get existing code (if any)
    $code = DemoCode::withoutGlobalScopes()
        ->where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->first();

    // 2. CHECK EXPIRATION FIRST (CRITICAL - added in fix)
    if ($code && $code->code1_sent_at) {
        $isExpired = now()->diffInMinutes($code->code1_sent_at)
                    > $this->votingTimeInMinutes;

        if ($isExpired && !$code->has_voted) {
            // Regenerate immediately
            $code->code1 = $this->generateCode();
            $code->code1_sent_at = now();
            $code->is_code1_usable = 1;
            $code->code1_used_at = null;
            $code->save();

            // Send email
            if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                $user->notify(new SendFirstVerificationCode($user, $code->code1));
            }

            return $code;
        }
    }

    // 3. CHECK RE-VOTING (for demo only)
    if ($code && $election->type === 'demo' && $code->has_voted) {
        // Regenerate for re-voting
        $code->update([
            'has_voted' => false,
            'vote_submitted' => false,
            'can_vote_now' => 0,
            'is_code1_usable' => 1,
            'code1' => $this->generateCode(),
            'code1_sent_at' => now(),
            'code1_used_at' => null,
        ]);

        // Send new code
        if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $user->notify(new SendFirstVerificationCode($user, $code->code1));
        }

        return $code;
    }

    // 4. IF NO CODE EXISTS - CREATE NEW ONE
    if (!$code) {
        $code = DemoCode::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'code1' => $this->generateCode(),
            'code1_sent_at' => now(),
            'has_code1_sent' => 1,
            'is_code1_usable' => 1,
            'can_vote_now' => 0,
        ]);

        // Send code
        if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $user->notify(new SendFirstVerificationCode($user, $code->code1));
        }

        return $code;
    }

    // 5. EXISTING CODE CHECKS
    if ($code->can_vote_now == 1) {
        // Already verified - return as-is
        return $code;
    }

    // 6. CODE RESEND LOGIC (for unused expired codes)
    $isExpired = $code->code1_sent_at
                 && now()->diffInMinutes($code->code1_sent_at) > $this->votingTimeInMinutes;
    $codeIsUsed = ($code->is_code1_usable == 0 || $code->code1_used_at !== null);

    // NEW CHECK: Mid-voting state preservation
    if ($codeIsUsed && !$code->has_voted && $election->type === 'demo') {
        // Code used but voting incomplete - preserve state
        return $code;
    }

    // Only resend if expired AND not used AND not voted
    if ($isExpired && !$codeIsUsed && !$code->has_voted) {
        $newCode = $this->generateCode();
        $code->update([
            'code1' => $newCode,
            'code1_sent_at' => now(),
            'is_code1_usable' => 1,
            'can_vote_now' => 0,
        ]);

        // Send new code
        if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $user->notify(new SendFirstVerificationCode($user, $newCode));
        }
    }

    return $code;
}
```

**Decision Tree:**
```
Has code?
├─ Yes:
│   ├─ Expired?
│   │   ├─ Yes & not voted: REGENERATE + email
│   │   └─ No
│   ├─ Has voted?
│   │   ├─ Yes: REGENERATE for re-voting
│   │   └─ No
│   ├─ Can vote now (verified)?
│   │   ├─ Yes: RETURN as-is
│   │   └─ No
│   └─ Resend expired unused code?
│       ├─ Yes: REGENERATE + email
│       └─ No: RETURN as-is
└─ No: CREATE new + email
```

---

### 3. `store(Request $request)`

**Purpose:** Verify submitted code (STEP 1 → STEP 2)

**Route:** `POST /v/{slug}/demo-code`

**Flow:**
```php
public function store(Request $request)
{
    // 1. Get user, election, code
    $user = $this->getUser($request);
    $election = $this->getElection($request);
    $code = DemoCode::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->first();

    // 2. Validate code matches
    $submittedCode = trim(strtoupper($request->input('voting_code')));
    $verificationResult = $this->verifyCode($code, $submittedCode, $user);

    if (!$verificationResult['success']) {
        return back()->withErrors(['voting_code' => $verificationResult['message']])
                   ->withInput();
    }

    // 3. Mark as verified (update flags)
    $this->markCodeAsVerified($code);

    // 4. Record step completion
    VoterStepTrackingService::recordStepCompletion(
        voter_slug_id: $voterSlug->id,
        election_id: $election->id,
        step: 1,
    );

    // 5. Redirect to agreement
    return redirect(route('slug.demo-code.agreement',
        ['vslug' => $voterSlug->slug]));
}
```

**Validations:**
- ✅ Code matches `code1`
- ✅ Code not expired (20 min)
- ✅ Code is usable (`is_code1_usable=1`)
- ✅ Not already verified (`can_vote_now ≠ 1`)
- ❌ NO IP rate limiting for demo (allows testing from same machine)

---

### 4. `showAgreement()` & `agreeToTerms()`

**Purpose:** STEP 2 - Terms & conditions

**Route:**
- `GET /v/{slug}/demo-code/agreement` - Show form
- `POST /v/{slug}/demo-code/agreement` - Process agreement

**Key Logic:**
```php
public function showAgreement(Request $request)
{
    // Check code is verified
    $code = DemoCode::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->first();

    // Must have verified code to see agreement
    if (!$code || $code->can_vote_now != 1) {
        return redirect(route('slug.demo-code.create'))
            ->with('error', 'Please verify your code first');
    }

    // Show agreement page
    return Inertia::render('Code/DemoCode/Agreement', [...]);
}

public function agreeToTerms(Request $request)
{
    // Validate checkbox
    $request->validate([
        'agreed' => 'required|accepted',
    ]);

    // Record agreement
    $code->update([
        'has_agreed_to_vote' => 1,
        'agreed_at' => now(),
    ]);

    // Record step
    VoterStepTrackingService::recordStepCompletion($voterSlug->id, $election->id, 2);

    // Redirect to voting
    return redirect(route('slug.demo-vote.create'));
}
```

---

## Testing

### Unit Tests

**File:** `tests/Feature/Demo/CircularRedirectFixTest.php`

**Test Cases:**
```php
test_step_not_reset_when_user_mid_voting()
// Validates step NOT reset for can_vote_now=1, has_voted=false

test_step_reset_when_user_completed_voting()
// Validates step IS reset for has_voted=true

test_code_not_regenerated_when_mid_voting()
// Validates code usability check preserves state

test_all_three_code_states()
// Validates all state transitions work correctly
```

### Integration Tests

**File:** `tests/Feature/Demo/VotingWorkflowIntegrationTest.php`

**Critical Test:**
```php
// "simple mode no redirect loop" - validates the fix
$this->assertTrue($noInfiniteLoop, 'Should not have circular redirect');
```

**Running Tests:**
```bash
# All demo tests
php artisan test tests/Feature/Demo/

# Specific controller tests
php artisan test tests/Feature/Demo/CircularRedirectFixTest.php

# Integration workflow
php artisan test tests/Feature/Demo/VotingWorkflowIntegrationTest.php
```

---

## Debugging Guide

### Issue: Circular Redirect Loop

**Symptoms:**
- User refreshes page every 2 seconds
- Stuck between `/demo-code/create` and `/demo-code/agreement`
- Logs show: "Reset voter slug step" repeatedly

**Diagnosis:**
```php
// Check voter slug state
VoterSlug::find($id)->first(); // current_step, election_id

// Check code state
DemoCode::where('user_id', $id)->where('election_id', $id)->first();
// Look for: can_vote_now=1, has_voted=false (STATE 2)
```

**Root Cause (Pre-Fix):**
```php
// OLD CODE (WRONG)
if ($voterSlug && $election->type === 'demo') {
    $voterSlug->current_step = 1; // Always resets!
}
```

**Solution (Applied):**
```php
// NEW CODE (CORRECT)
if ($voterSlug && $election->type === 'demo' && $code->has_voted) {
    $voterSlug->current_step = 1; // Only reset if voting complete
}
```

---

### Issue: Code Not Sending

**Symptoms:**
- User doesn't receive code email
- Logs show: "Email send failed"

**Checklist:**
1. ✅ Is user email valid?
   ```php
   filter_var($user->email, FILTER_VALIDATE_EMAIL) === true
   ```

2. ✅ Is notification configured?
   ```bash
   # Check .env
   MAIL_MAILER=smtp
   MAIL_HOST=...
   MAIL_PORT=...
   ```

3. ✅ Is SendFirstVerificationCode defined?
   ```php
   // app/Notifications/SendFirstVerificationCode.php
   class SendFirstVerificationCode extends Notification { ... }
   ```

4. ✅ Check logs
   ```bash
   tail -f storage/logs/laravel.log | grep "SendFirstVerificationCode"
   ```

---

### Issue: User Stuck on Agreement Page

**Symptoms:**
- User can't proceed to voting
- Route returns 403 or redirects back

**Diagnosis:**
```php
// Check step tracking
VoterSlugStep::where('voter_slug_id', $id)
    ->where('step', 2)
    ->exists(); // Should be true

// Check code state
$code->can_vote_now; // Should be 1
$code->has_agreed_to_vote; // Should be 1
```

**Common Causes:**
- Step 1 not recorded (code verification didn't complete)
- Code expired while on agreement page
- Database corruption in voter_slug_steps

**Fix:**
```php
// Manually record step if needed (emergency)
VoterSlugStep::create([
    'voter_slug_id' => $id,
    'election_id' => $id,
    'step' => 1,
    'completed_at' => now(),
]);
```

---

### Issue: Re-Voting Not Working

**Symptoms:**
- Demo user completes vote but can't vote again
- Getting "already voted" error

**Check:**
```php
// Verify has_voted flag
$code->has_voted; // Should be 1 after voting

// Verify re-voting logic triggers
if ($code && $election->type === 'demo' && $code->has_voted) {
    // This should regenerate
    Log::info('Regenerating for re-vote...');
}
```

**Ensure:**
- Election type is `'demo'` (not `'real'`)
- User completes full voting flow (not just code verification)
- VoterSlugStep records step 5 completion

---

## Common Patterns

### Pattern 1: Getting User Election Context

```php
$user = $this->getUser($request);
$election = $this->getElection($request);
$voterSlug = $request->attributes->get('voter_slug');

// Always validate
if (!$user || !$election) {
    return abort(403, 'Invalid election context');
}
```

### Pattern 2: Multi-Tenant Scoping

```php
// Set organisation context (required for queries)
session(['current_organisation_id' => $election->organisation_id]);

// All DemoCode queries now scoped to this organisation
// via global scope: where('organisation_id', $org_id)
```

### Pattern 3: Code Generation

```php
private function generateCode(): string
{
    // 6-character uppercase alphanumeric code
    return strtoupper(Str::random(6));
    // Example: "ABC123", "XYZ789"
}
```

### Pattern 4: Email Notifications

```php
// Send code via email
if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
    try {
        $user->notify(new SendFirstVerificationCode($user, $code->code1));
        Log::info('Code sent', ['code' => $code->code1]);
    } catch (\Exception $e) {
        Log::error('Failed to send code', ['error' => $e->getMessage()]);
        // Don't fail hard - show code on page as fallback
    }
}
```

---

## Recent Changes (Feb 2026)

### Circular Redirect Fix

**Commit:** `1c2d6ddad` and `5c37100c9`

**Changes:**
1. **Conditional Step Reset** - Only reset when `has_voted=true`
2. **Code Usability Check** - Preserve mid-voting state
3. **Expiration Check First** - Check expiration before other logic

**Impact:**
- ✅ Eliminated infinite redirect loop
- ✅ Preserved mid-voting sessions
- ✅ Maintained demo re-voting feature
- ✅ 12/13 integration tests passing

**Tests:**
- `CircularRedirectFixTest.php` - 4/4 passing
- `VotingWorkflowIntegrationTest.php` - 12/13 passing

---

## Extending DemoCodeController

### Adding a New Code Format

```php
// In getOrCreateCode()
if ($election->code_format === 'numeric') {
    $code->code1 = rand(100000, 999999); // 6-digit number
} else {
    $code->code1 = strtoupper(Str::random(6)); // Default
}
```

### Adding Multi-Factor Code

```php
// Add code2 requirement
$code->update([
    'code2' => $this->generateCode(),
    'code2_sent_at' => now(),
    'is_code2_usable' => 1,
]);

// In verifyCode()
if ($code->code2 !== $submittedCode2) {
    return ['success' => false, 'message' => 'Code 2 invalid'];
}
```

### Adding IP Rate Limiting (Real Elections)

```php
if ($election->type === 'real') {
    // Check IP rate limit
    $attempts = RateLimiter::attempts($this->clientIP);

    if ($attempts > $this->maxUseClientIP) {
        return back()->withErrors(['voting_code' => 'Too many attempts']);
    }
}
```

---

## Database Schema Reference

### DemoCode Table

```sql
CREATE TABLE demo_codes (
    id BIGINT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    election_id BIGINT NOT NULL,
    organisation_id BIGINT NULLABLE,

    -- Code management
    code1 VARCHAR(10),
    is_code1_usable BOOLEAN DEFAULT 1,
    code1_sent_at TIMESTAMP,
    code1_used_at TIMESTAMP NULLABLE,
    has_code1_sent BOOLEAN DEFAULT 0,

    -- Code2 (two-code mode)
    code2 VARCHAR(10) NULLABLE,
    is_code2_usable BOOLEAN DEFAULT 1,
    code2_sent_at TIMESTAMP NULLABLE,
    code2_used_at TIMESTAMP NULLABLE,

    -- Voting state
    can_vote_now BOOLEAN DEFAULT 0,
    has_voted BOOLEAN DEFAULT 0,
    has_agreed_to_vote BOOLEAN DEFAULT 0,
    vote_submitted BOOLEAN DEFAULT 0,

    -- Timestamps
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    -- Indexes
    UNIQUE KEY unique_user_election (user_id, election_id),
    INDEX idx_election (election_id),
    INDEX idx_can_vote (can_vote_now),
    INDEX idx_has_voted (has_voted)
);
```

### VoterSlug Table

```sql
CREATE TABLE voter_slugs (
    id BIGINT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    election_id BIGINT NOT NULL,
    organisation_id BIGINT NULLABLE,

    slug VARCHAR(255) UNIQUE,
    current_step INT DEFAULT 1,
    step_meta JSON,

    expires_at TIMESTAMP,
    is_active BOOLEAN DEFAULT 1,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## Related Files

**Dependencies:**
- `app/Models/DemoCode.php` - Data model
- `app/Models/User.php` - User model
- `app/Models/Election.php` - Election model
- `app/Models/VoterSlug.php` - Voter slug tracking
- `app/Notifications/SendFirstVerificationCode.php` - Email notification
- `app/Services/VoterStepTrackingService.php` - Step recording

**Routes:**
- `routes/election/electionRoutes.php` - Route definitions

**Tests:**
- `tests/Feature/Demo/CircularRedirectFixTest.php` - Unit tests
- `tests/Feature/Demo/VotingWorkflowIntegrationTest.php` - Integration tests

---

## Summary

The `DemoCodeController` is the **entry point** for demo elections. It manages three critical states:

1. **STATE 1 (Fresh)** - New code generation
2. **STATE 2 (Verified)** - Mid-voting (the fixed state)
3. **STATE 3 (Completed)** - Re-voting support

**Key Take-Away:** The recent fix ensures **STATE 2 is preserved** by only resetting the voter step when `has_voted=true`. This eliminates the circular redirect loop while maintaining all functionality.

For questions or issues, refer to the test cases in `CircularRedirectFixTest.php` which document all expected behaviors.
