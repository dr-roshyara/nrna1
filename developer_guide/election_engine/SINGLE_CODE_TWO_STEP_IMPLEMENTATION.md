# 🔐 Single Code, Two-Step Verification Implementation Guide

**Requirement**: Use the same `code1` twice:
1. When **opening the voting form** (/code/create)
2. When **verifying the voted persons/candidates** (/vote/verify)

**Status**: Implementation Ready
**Date**: February 2026

---

## Current State Analysis

### Broken Flow (Current)
```
Code1 sent via email
    ↓
User enters Code1 at /code/create
    ↓
Redirected to /vote/create (BROKEN: vote_pre_check redirects back)
    ↓
User submits vote (BLOCKED by vote_pre_check)
    ↓
Code2 sent via email (trying to fix with new code)
    ↓
Loop continues...
```

### Target Flow (New)
```
Code1 sent via email
    ↓
User enters Code1 at /code/create (First Use)
    ├─ Code marked as used: code1_used_at = NOW
    └─ Redirected to /vote/create ✅
    ↓
User submits vote from /vote/create
    ├─ pre_check validates timing (NOT code state)
    └─ Vote stored in session ✅
    ↓
Redirected to /vote/verify
    ├─ Ask user to enter Code1 again (Second Use)
    └─ Verify matches: $request->code1 === $code->code1 ✅
    ↓
Vote saved & confirmation shown ✅
```

---

## Phase 1: Database Setup

### Migration File
**File**: `database/migrations/2026_02_21_add_code_usage_tracking.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add to demo_codes table
        Schema::table('demo_codes', function (Blueprint $table) {
            // Track first use (entry to voting form)
            if (!Schema::hasColumn('demo_codes', 'code1_used_at')) {
                $table->timestamp('code1_used_at')->nullable()->after('code1');
            }

            // Track second use (verification)
            if (!Schema::hasColumn('demo_codes', 'code1_verified_at')) {
                $table->timestamp('code1_verified_at')->nullable()->after('code1_used_at');
            }

            // Count how many times Code1 was used
            if (!Schema::hasColumn('demo_codes', 'code1_usage_count')) {
                $table->unsignedTinyInteger('code1_usage_count')->default(0)->after('code1_verified_at');
            }

            // Remove deprecated Code2 fields (optional - can deprecate gradually)
            // $table->dropColumn(['code2', 'is_code2_usable', 'has_code2_sent', 'code2_used_at', 'has_used_code2']);
        });

        // Add to codes table (real elections)
        Schema::table('codes', function (Blueprint $table) {
            if (!Schema::hasColumn('codes', 'code1_used_at')) {
                $table->timestamp('code1_used_at')->nullable()->after('code1');
            }

            if (!Schema::hasColumn('codes', 'code1_verified_at')) {
                $table->timestamp('code1_verified_at')->nullable()->after('code1_used_at');
            }

            if (!Schema::hasColumn('codes', 'code1_usage_count')) {
                $table->unsignedTinyInteger('code1_usage_count')->default(0)->after('code1_verified_at');
            }
        });
    }

    public function down()
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            $table->dropColumn([
                'code1_used_at',
                'code1_verified_at',
                'code1_usage_count'
            ]);
        });

        Schema::table('codes', function (Blueprint $table) {
            $table->dropColumn([
                'code1_used_at',
                'code1_verified_at',
                'code1_usage_count'
            ]);
        });
    }
};
```

---

## Phase 2: Update DemoCode Model

**File**: `app/Models/DemoCode.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DemoCode extends Model
{
    protected $table = 'demo_codes';
    protected $guarded = [];

    protected $casts = [
        'code1_used_at' => 'datetime',
        'code1_verified_at' => 'datetime',
        'code1_sent_at' => 'datetime',
    ];

    /**
     * Check if Code1 can be used for entry (first step)
     */
    public function canUseCode1ForEntry(): bool
    {
        return $this->is_code1_usable == 1
            && $this->has_code1_sent == 1
            && $this->code1_used_at === null;
    }

    /**
     * Mark Code1 as used for entry
     */
    public function markCode1UsedForEntry(): void
    {
        $this->code1_used_at = Carbon::now();
        $this->is_code1_usable = 0;  // Mark as used
        $this->code1_usage_count = 1;
        $this->save();
    }

    /**
     * Check if Code1 can be used for verification (second step)
     */
    public function canUseCode1ForVerification(): bool
    {
        // Code1 must have been used for entry already
        if ($this->code1_used_at === null) {
            return false;
        }

        // Check timing window
        $totalDuration = Carbon::now()->diffInMinutes($this->code1_used_at);
        if ($totalDuration > $this->voting_time_in_minutes) {
            return false;
        }

        // Code1 hasn't been verified yet
        return $this->code1_verified_at === null;
    }

    /**
     * Mark Code1 as verified
     */
    public function markCode1Verified(): void
    {
        $this->code1_verified_at = Carbon::now();
        $this->code1_usage_count = 2;
        $this->has_voted = 1;
        $this->save();
    }

    /**
     * Reset code for new attempt
     */
    public function resetForNewAttempt(): void
    {
        $this->code1_used_at = null;
        $this->code1_verified_at = null;
        $this->code1_usage_count = 0;
        $this->is_code1_usable = 1;
        $this->can_vote_now = 0;
        $this->has_voted = 0;
        $this->vote_submitted = 0;
        $this->save();
    }
}
```

---

## Phase 3: Fix `vote_pre_check()` Method

**File**: `app/Http/Controllers/Demo/DemoVoteController.php`

**Lines**: 2448-2510

**Current broken code:**
```php
public function vote_pre_check(&$code){
    $return_to = "";
    $current = Carbon::now();
    $code1_used_at = $code->code1_used_at;
    $voting_time = $code->voting_time_in_minutes;
    $totalDuration = $current->diffInMinutes($code1_used_at);

    if($code == null){
        return $return_to = "code.create";
    }

    if(!$code->can_vote_now){
        return $return_to = "dashboard";
    }

    if($code->has_voted){
        return $return_to = "dashboard";
    }

    if(!$code->has_code1_sent){
        return $return_to = "code.create";
    }

    // ❌ THIS IS THE BUG: Checks if Code1 is still usable
    // But user ALREADY used it to get here!
    if($code->is_code1_usable ){
        return $return_to = "code.create";
    }

    // Time check
    if($totalDuration > $voting_time){
        $code->can_vote_now = 0;
        $code->is_code1_usable = 0;
        $code->is_code2_usable = 0;
        $code->has_code1_sent = 0;
        $code->has_code2_sent = 0;
        $code->save();
        $return_to = "code.create";
    }

    return $return_to;
}
```

**Corrected code:**
```php
public function vote_pre_check(&$code)
{
    $return_to = "";

    // 1. Code must exist
    if ($code == null) {
        return "code.create";
    }

    // 2. Voting window must be open
    if (!$code->can_vote_now) {
        return "dashboard";
    }

    // 3. User must not have already voted
    if ($code->has_voted) {
        return "dashboard";
    }

    // 4. Code1 must have been sent
    if (!$code->has_code1_sent) {
        return "code.create";
    }

    // 5. Code1 must have been entered for first use ✅ NEW CHECK
    if ($code->code1_used_at === null) {
        // User hasn't entered Code1 yet at /code/create
        return "code.create";
    }

    // 6. Check if voting window expired since Code1 was used
    $totalDuration = Carbon::now()->diffInMinutes($code->code1_used_at);
    if ($totalDuration > $code->voting_time_in_minutes) {
        // Window expired - reset for new attempt
        $code->resetForNewAttempt();
        return "code.create";
    }

    // ✅ All checks passed - allow vote submission
    return $return_to;
}
```

---

## Phase 4: Update Code Entry Handler

**File**: `app/Http/Controllers/Demo/DemoVoteController.php`

**Method**: Code creation/entry handler (wherever user enters Code1 at /code/create)

**Current flow:**
```php
// User submits Code1
$submitted_code = $request->input('code1');

// Verify it matches
if ($submitted_code !== $code->code1) {
    return error: "Invalid code";
}

// Set state for voting
$code->can_vote_now = 1;
// ❌ MISSING: Mark when Code1 was used
// ❌ MISSING: Mark Code1 as consumed for first use
```

**Updated flow:**
```php
// User submits Code1 at /code/create
$submitted_code = $request->input('code1');

// Verify it matches
if ($submitted_code !== $code->code1) {
    return error: "Invalid code";
}

// ✅ NEW: Mark Code1 as used for entry (first step)
$code->markCode1UsedForEntry();  // Sets code1_used_at, is_code1_usable=0, usage_count=1

// Allow voting
$code->can_vote_now = 1;
$code->save();

// Redirect to voting form
return redirect()->route('slug.vote.create', ['vslug' => $voterSlug->slug]);
```

---

## Phase 5: Update Vote Verification Handler

**File**: `app/Http/Controllers/Demo/DemoVoteController.php`

**Method**: Vote verification method (wherever user confirms vote with Code1 at /vote/verify)

**Current flow:**
```php
// Ask for Code2 (broken/not working)
$verification_code = $request->input('code2');

if ($verification_code !== $code->code2) {
    return error: "Invalid code";
}

// Save vote
```

**Updated flow:**
```php
// ✅ NEW: Ask for Code1 again (second use)
$verification_code = $request->input('verification_code');

// Check if user can use Code1 for verification
if (!$code->canUseCode1ForVerification()) {
    return error: "Code1 cannot be used for verification. Time window may have expired.";
}

// Verify Code1 matches
if ($verification_code !== $code->code1) {
    return error: "Invalid verification code. Please enter the code from your email.";
}

// ✅ Mark Code1 as verified (second use)
$code->markCode1Verified();

// Save the vote
// ... existing vote saving logic ...

// Show confirmation
return redirect()->route('vote.confirmation');
```

---

## Phase 6: Update Vote Submission UI

### Before: Separate Code2 Field
```vue
<form @submit.prevent="submit">
  <!-- Vote selections -->
  <div class="candidates">...</div>

  <!-- Code2 field (being replaced) -->
  <input v-model="code2" type="text" placeholder="Enter Code 2" />

  <button type="submit">Verify & Submit</button>
</form>
```

### After: Code1 Field (Second Use)
```vue
<form @submit.prevent="submit">
  <!-- Vote selections (read-only display) -->
  <div class="vote-summary">
    <h3>Your Selections:</h3>
    <div v-for="post in selectedVotes" :key="post.id">
      {{ post.name }}: {{ post.candidates.join(', ') }}
    </div>
  </div>

  <!-- ✅ NEW: Ask for Code1 again -->
  <div class="form-group">
    <label for="verification_code">
      Enter Your Code Again (for verification)
    </label>
    <input
      v-model="verificationCode"
      type="text"
      id="verification_code"
      placeholder="Enter the code from your email"
      required
    />
    <small>This is the same code you used to open the voting form.</small>
  </div>

  <button type="submit" class="btn-primary">
    Verify & Submit Vote
  </button>
</form>
```

---

## Phase 7: Update Routes

**File**: `routes/web.php`

```php
// Existing: Code submission route
Route::post('/v/{vslug}/demo-vote/code/verify', [DemoVoteController::class, 'verifyCode'])
    ->name('slug.code.verify');

// Existing: Vote submission route
Route::post('/v/{vslug}/demo-vote/create', [DemoVoteController::class, 'first_submission'])
    ->name('slug.vote.create.submit');

// ✅ NEW: Vote verification/confirmation route
Route::post('/v/{vslug}/demo-vote/verify', [DemoVoteController::class, 'verifyVoteWithCode1'])
    ->name('slug.vote.verify.submit');
```

---

## Phase 8: Create New Verification Method

**File**: `app/Http/Controllers/Demo/DemoVoteController.php`

**New Method**:
```php
/**
 * Verify vote with Code1 (second use)
 *
 * @param Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function verifyVoteWithCode1(Request $request)
{
    $auth_user = $this->getUser($request);
    $election = $this->getElection($request);
    $voterSlug = $request->attributes->get('voter_slug');

    // Get code
    $code = $auth_user->code;
    if (!$code) {
        return redirect()->route('slug.code.create', ['vslug' => $voterSlug->slug])
            ->withErrors(['code' => 'Code not found']);
    }

    // Validate request
    $request->validate([
        'verification_code' => 'required|string|min:8|max:20'
    ]);

    $verification_code = trim($request->input('verification_code'));

    // Check if Code1 can be used for verification
    if (!$code->canUseCode1ForVerification()) {
        $code->resetForNewAttempt();
        return redirect()->route('slug.code.create', ['vslug' => $voterSlug->slug])
            ->withErrors(['verification_code' => 'Code expired or invalid. Requesting new code.']);
    }

    // Verify Code1 matches
    if ($verification_code !== $code->code1) {
        return redirect()->back()
            ->withErrors(['verification_code' => 'Invalid code. Please try again.'])
            ->withInput();
    }

    // Get vote data from session
    $session_name = $code->session_name ?: 'vote_data_' . $auth_user->id;
    $vote_data = $request->session()->get($session_name);

    if (!$vote_data) {
        return redirect()->route('slug.vote.create', ['vslug' => $voterSlug->slug])
            ->withErrors(['vote' => 'Vote data not found. Please start over.']);
    }

    // ✅ Mark Code1 as verified
    $code->markCode1Verified();

    // Save the vote
    $this->saveVote($auth_user, $election, $vote_data);

    // Clear session
    $request->session()->forget($session_name);

    // Show confirmation
    return redirect()->route('slug.vote.confirmation', ['vslug' => $voterSlug->slug])
        ->with('success', 'Your vote has been securely saved.');
}
```

---

## Complete State Machine

```
STATE DIAGRAM: Code1 Lifecycle

Initial State
    ↓
    code1_issued(code_value, timestamp)
    ├─ is_code1_usable = 1
    ├─ code1_used_at = NULL
    ├─ code1_verified_at = NULL
    ├─ code1_usage_count = 0
    └─ Email sent with code
    ↓
USER ENTERS CODE AT /code/create
    ↓
    markCode1UsedForEntry()
    ├─ code1_used_at = NOW
    ├─ is_code1_usable = 0
    ├─ code1_usage_count = 1
    └─ can_vote_now = 1
    ↓
USER SUBMITS VOTE AT /vote/create
    ↓
    vote_pre_check() validates:
    ├─ code1_used_at is SET ✅
    ├─ Time window hasn't expired ✅
    └─ Vote stored in session ✅
    ↓
    Redirect to /vote/verify
    ↓
USER ENTERS CODE1 AGAIN AT /vote/verify
    ↓
    canUseCode1ForVerification() checks:
    ├─ code1_used_at is SET ✅
    ├─ code1_verified_at is NULL ✅
    └─ Time window valid ✅
    ↓
    markCode1Verified()
    ├─ code1_verified_at = NOW
    ├─ code1_usage_count = 2
    ├─ has_voted = 1
    └─ Vote saved ✅
    ↓
Final State - Vote Complete
```

---

## Error Scenarios & Recovery

### Scenario 1: Code1 Entered Wrong at Entry
```
User enters incorrect Code1 at /code/create
    ↓
    canUseCode1ForEntry() returns false
    ↓
    Show error: "Invalid code"
    ↓
    ✅ User can retry (code state unchanged)
```

### Scenario 2: Time Window Expires
```
User enters Code1 at time=00:00
    ↓
    code1_used_at = 00:00
    ↓
    User waits > voting_time_in_minutes
    ↓
    At /vote/verify, user enters Code1 at time=35:00
    ↓
    canUseCode1ForVerification() checks: 35 minutes > 30 minutes
    ↓
    resetForNewAttempt() called
    ↓
    ✅ Redirect to /code/create with new code
```

### Scenario 3: User Tries to Submit Twice
```
First submission:
    code1_usage_count = 2
    has_voted = 1

Second submission attempt:
    vote_pre_check() checks: has_voted == 1
    ↓
    Returns "dashboard"
    ✅ User blocked from double voting
```

---

## Testing Checklist

- [ ] Code1 sent when user requests
- [ ] Code1 entered at /code/create works
- [ ] vote_pre_check() allows proceeding to /vote/create
- [ ] vote_pre_check() doesn't trigger redirect loop
- [ ] No duplicate code email on vote submission
- [ ] Vote stored in session after submission
- [ ] /vote/verify asks for Code1 again
- [ ] Code1 verification works
- [ ] Wrong Code1 at verification shows error
- [ ] Correct Code1 saves vote
- [ ] Vote confirmation page shows
- [ ] Second vote attempt is blocked
- [ ] Time window expiration resets code
- [ ] Database tracks usage correctly (code1_usage_count)

---

## Implementation Order

1. **Run Migration** - Add new columns to track Code1 usage
2. **Update DemoCode Model** - Add helper methods
3. **Fix vote_pre_check()** - Remove bad checks
4. **Update Code Entry Handler** - Call markCode1UsedForEntry()
5. **Update Vote Verification** - Ask Code1 again
6. **Create verifyVoteWithCode1()** - New method
7. **Update UI Component** - Change from Code2 to Code1 field
8. **Update Routes** - Add verification route
9. **Test** - Full voting workflow
10. **Commit** - All changes together

---

**Status**: ✅ Ready to Implement
