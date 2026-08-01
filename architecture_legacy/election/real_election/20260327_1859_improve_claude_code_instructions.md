## Claude Code CLI Prompt Instructions - VoteController TDD Refactor

```bash
claude code "Refactor VoteController.php using Test-Driven Development (TDD) approach, aligning with DemoVoteController best practices while maintaining real election security (no re-voting)."

## CONTEXT
- **Reference Implementation:** `app/Http/Controllers/Demo/DemoVoteController.php` (working, tested, modern)
- **Target:** `app/Http/Controllers/VoteController.php` (legacy, needs updates)
- **Key Difference:** Real elections = ONE vote only (has_voted must be enforced at ALL entry points)
- **Architecture:** Multi-election support with election_id scoping

## CRITICAL SECURITY REQUIREMENTS
- ✅ Vote anonymity preserved (no user_id in votes/results tables)
- ✅ Real elections: Strict re-voting prevention
- ✅ Election-scoped queries (where election_id = ?)
- ✅ Organisation validation for real elections
- ✅ Mode-specific code verification (SIMPLE vs STRICT modes based on config)

## PHASE 1: CREATE TEST FILE FIRST (RED)

Create file: `tests/Feature/VoteControllerTest.php`

### Test 1: Real Election - Blocks Double Voting in create()
```php
/** @test */
public function real_election_blocks_create_page_for_already_voted_user()
{
    $user = $this->createEligibleVoter();
    $election = $this->createRealElection();
    $voterSlug = $this->createVoterSlug($user, $election);
    
    // User already voted
    Code::factory()->voted()->create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'has_voted' => true,
    ]);
    
    $response = $this->actingAs($user)
        ->get(route('slug.vote.create', ['vslug' => $voterSlug->slug]));
    
    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('error', 'You have already voted in this election.');
}
```

### Test 2: Real Election - Blocks Double Voting in first_submission()
```php
/** @test */
public function real_election_blocks_first_submission_for_already_voted_user()
{
    $user = $this->createEligibleVoter();
    $election = $this->createRealElection();
    $voterSlug = $this->createVoterSlug($user, $election);
    
    $code = Code::factory()->voted()->create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'has_voted' => true,
        'can_vote_now' => 1,
        'vote_submitted' => 1,
    ]);
    
    $response = $this->actingAs($user)
        ->post(route('slug.vote.first_submission', ['vslug' => $voterSlug->slug]), [
            'user_id' => $user->id,
            'agree_button' => 1,
            'national_selected_candidates' => [],
            'regional_selected_candidates' => [],
        ]);
    
    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHasErrors(['vote' => 'You have already voted in this election.']);
}
```

### Test 3: Real Election - Blocks Double Voting in store()
```php
/** @test */
public function real_election_blocks_final_submission_for_already_voted_user()
{
    $user = $this->createEligibleVoter();
    $election = $this->createRealElection();
    
    $code = Code::factory()->voted()->create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'has_voted' => true,
        'code_to_open_voting_form' => 'ABCD1234',
    ]);
    
    $response = $this->actingAs($user)
        ->post(route('vote.store'), [
            'voting_code' => 'ABCD1234',
        ]);
    
    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHasErrors(['vote' => 'You have already voted in this election.']);
}
```

### Test 4: Real Election - Code Fetching Must Be Election-Scoped
```php
/** @test */
public function real_election_fetches_code_by_election_id_not_relationship()
{
    $user = $this->createEligibleVoter();
    $election1 = $this->createRealElection();
    $election2 = $this->createRealElection();
    
    // Create code for election2 only
    Code::factory()->create([
        'user_id' => $user->id,
        'election_id' => $election2->id,
        'can_vote_now' => 1,
    ]);
    
    $voterSlug = $this->createVoterSlug($user, $election1);
    
    $response = $this->actingAs($user)
        ->post(route('slug.vote.first_submission', ['vslug' => $voterSlug->slug]), [
            'user_id' => $user->id,
            'agree_button' => 1,
            'national_selected_candidates' => [],
            'regional_selected_candidates' => [],
        ]);
    
    // Should fail because no code for election1
    $response->assertSessionHasErrors();
}
```

### Test 5: Organisation Validation in getElection()
```php
/** @test */
public function get_election_validates_organisation_mismatch()
{
    $user = $this->createEligibleVoter();
    $org1 = Organisation::factory()->create();
    $org2 = Organisation::factory()->create();
    
    $election = Election::factory()->create([
        'type' => 'real',
        'organisation_id' => $org1->id,
    ]);
    
    $voterSlug = VoterSlug::factory()->create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'organisation_id' => $org2->id, // Mismatch!
    ]);
    
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Organisation mismatch detected');
    
    $this->actingAs($user)
        ->get(route('slug.vote.create', ['vslug' => $voterSlug->slug]));
}
```

### Test 6: vote_pre_check() Mode-Specific Logic
```php
/** @test */
public function vote_pre_check_handles_simple_mode_correctly()
{
    config(['voting.two_codes_system' => 0]); // SIMPLE MODE
    
    $user = $this->createEligibleVoter();
    $election = $this->createRealElection();
    
    $code = Code::factory()->create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'can_vote_now' => 1,
        'code_to_open_voting_form_used_at' => null, // Not used yet
        'has_code1_sent' => 1,
    ]);
    
    $controller = new VoteController();
    $result = $this->invokePrivateMethod($controller, 'vote_pre_check', [&$code]);
    
    $this->assertEquals('code.create', $result); // Should redirect to code entry
}

/** @test */
public function vote_pre_check_handles_strict_mode_correctly()
{
    config(['voting.two_codes_system' => 1]); // STRICT MODE
    
    $user = $this->createEligibleVoter();
    $election = $this->createRealElection();
    
    $code = Code::factory()->create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'can_vote_now' => 1,
        'code_to_open_voting_form_used_at' => now(),
        'code_to_save_vote_used_at' => null, // Code2 not used yet
        'has_code1_sent' => 1,
    ]);
    
    $controller = new VoteController();
    $result = $this->invokePrivateMethod($controller, 'vote_pre_check', [&$code]);
    
    $this->assertEquals('', $result); // All checks passed
}
```

### Test 7: second_code_check() Mode-Specific Logic
```php
/** @test */
public function second_code_check_strict_mode_requires_code2_used()
{
    config(['voting.two_codes_system' => 1]); // STRICT MODE
    
    $user = $this->createEligibleVoter();
    $election = $this->createRealElection();
    
    $code = Code::factory()->create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'code_to_open_voting_form_used_at' => now(),
        'code_to_save_vote_used_at' => null, // Code2 NOT used yet
        'vote_submitted' => 1,
        'voting_time_in_minutes' => 30,
    ]);
    
    $controller = new VoteController();
    $result = $this->invokePrivateMethod($controller, 'second_code_check', [&$code]);
    
    $this->assertArrayHasKey('return_to', $result);
    $this->assertEquals('vote.create', $result['return_to']);
}
```

### Test 8: verify_first_submission() Demo/Real Route Routing
```php
/** @test */
public function verify_first_submission_routes_to_correct_demo_verify_page()
{
    $user = $this->createEligibleVoter();
    $election = Election::factory()->create(['type' => 'demo']);
    $voterSlug = $this->createVoterSlug($user, $election);
    
    $code = DemoCode::factory()->create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'can_vote_now' => 1,
    ]);
    
    $request = Request::create('/test', 'POST', [
        'user_id' => $user->id,
        'agree_button' => 1,
    ]);
    $request->attributes->set('voter_slug', $voterSlug);
    
    $controller = new VoteController();
    $result = $this->invokePrivateMethod($controller, 'verify_first_submission', [
        $request, $code, $user, $election
    ]);
    
    $this->assertInstanceOf(RedirectResponse::class, $result);
    $this->assertStringContainsString('slug.demo-vote.verify', $result->getTargetUrl());
}
```

### Test 9: save_vote() Generates receipt_hash
```php
/** @test */
public function save_vote_generates_receipt_hash_for_real_election()
{
    $user = $this->createEligibleVoter();
    $election = $this->createRealElection();
    
    $code = Code::factory()->create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'code_to_open_voting_form' => 'TEST1234',
    ]);
    
    $vote_data = [
        'national_selected_candidates' => [],
        'regional_selected_candidates' => [],
        'no_vote_posts' => [],
    ];
    
    $controller = new VoteController();
    $private_key = $this->invokePrivateMethod($controller, 'save_vote', [
        $vote_data, null, $election, $user, null
    ]);
    
    $vote = Vote::latest()->first();
    
    $this->assertNotNull($vote->receipt_hash, 'receipt_hash should be generated');
    $this->assertEquals(64, strlen($vote->receipt_hash)); // SHA256 = 64 chars
    
    // Verify the hash can be regenerated
    $expected_hash = hash('sha256', $private_key . $vote->id . config('app.key'));
    $this->assertEquals($expected_hash, $vote->receipt_hash);
}
```

### Test 10: vote_post_check() IP Rate Limiting Scoped to Election
```php
/** @test */
public function vote_post_check_ip_rate_limiting_scoped_to_election()
{
    $user1 = User::factory()->create(['can_vote' => 1]);
    $user2 = User::factory()->create(['can_vote' => 1]);
    $election1 = $this->createRealElection();
    $election2 = $this->createRealElection();
    $sameIp = '192.168.1.100';
    
    // Create max votes from this IP on election2
    for ($i = 0; $i < 7; $i++) {
        Code::factory()->voted()->create([
            'client_ip' => $sameIp,
            'election_id' => $election2->id,
            'has_voted' => true,
        ]);
    }
    
    // User on election1 with same IP
    $code = Code::factory()->create([
        'user_id' => $user1->id,
        'election_id' => $election1->id,
        'client_ip' => $sameIp,
        'has_voted' => false,
    ]);
    
    $controller = new VoteController();
    $result = $this->invokePrivateMethod($controller, 'vote_post_check', [
        $user1, $code, ['test' => 'data']
    ]);
    
    $this->assertEmpty($result['error_message'], 'IP limit should not apply to different election');
}
```

## PHASE 2: RUN TESTS (RED)

```bash
php artisan test --filter VoteControllerTest
```

All tests should FAIL (RED phase).

## PHASE 3: IMPLEMENT FIXES (GREEN)

Update `app/Http/Controllers/VoteController.php` with the following fixes:

### Fix 1: Add has_voted Block in create()
Add after code retrieval (line ~250):
```php
// ⛔ REAL ELECTIONS: Block access to vote page if already voted
if ($election->type === 'real' && $code && $code->has_voted) {
    Log::warning('⛔ Real election - blocking vote page access for voter who already voted', [
        'user_id' => $auth_user->id,
        'election_id' => $election->id,
    ]);
    return redirect()->route('dashboard')
        ->with('error', 'You have already voted in this election. Each voter can only vote once.');
}
```

### Fix 2: Fix Code Fetching in first_submission()
Replace lines ~470-480:
```php
// ✅ FIXED: Election-scoped code retrieval
if ($election->type === 'demo') {
    $code = DemoCode::where('user_id', $auth_user->id)
        ->where('election_id', $election->id)
        ->first();
} else {
    // REAL ELECTIONS: Query by election_id, not relationship
    $code = Code::where('user_id', $auth_user->id)
        ->where('election_id', $election->id)
        ->first();
}
```

### Fix 3: Uncomment vote_submitted save
```php
$code->vote_submitted = 1;
$code->vote_submitted_at = \Carbon\Carbon::now();
$code->save(); // ✅ MUST save immediately
```

### Fix 4: Add Organisation Validation to getElection()
Replace the entire `getElection()` method with the validated version from CodeController:
```php
private function getElection(Request $request): Election
{
    $election = $request->attributes->get('election')
        ?? Election::where('type', 'real')->first();
    $voterSlug = $request->attributes->get('voter_slug');
    
    if ($voterSlug && $election) {
        if ($election->id !== $voterSlug->election_id) {
            throw new \Exception('Election mismatch detected');
        }
        $orgsMatch = $election->organisation_id === $voterSlug->organisation_id;
        $isPlatformElection = $election->organisation_id == 1;
        $isPlatformSlug = $voterSlug->organisation_id == 1;
        
        if (!$orgsMatch && !$isPlatformElection && !$isPlatformSlug) {
            throw new \Exception('Organisation mismatch detected');
        }
    }
    return $election;
}
```

### Fix 5: Update vote_pre_check() with Mode-Specific Logic
Replace the entire `vote_pre_check()` method with the DemoVoteController version, adapting for Code model:
```php
public function vote_pre_check(&$code)
{
    // Guard clause 1: No code
    if ($code === null) return "code.create";
    
    // Guard clause 2: Voting window closed
    if (!$code->can_vote_now) return "dashboard";
    
    // Guard clause 3: Already voted
    if ($code->has_voted) return "dashboard";
    
    // Guard clause 4: Code1 never sent
    if (!$code->has_code1_sent) return "code.create";
    
    // Guard clause 5: Already used for voting
    if ($code->code_to_save_vote_used_at !== null) return "dashboard";
    
    // Guard clause 6: Voting window timeout
    if ($this->hasVotingWindowExpired($code)) {
        $this->expireCode($code);
        return "code.create";
    }
    
    // Mode-specific verification
    if ($this->isStrictMode()) {
        return $this->verifyStrictModeCodeState($code);
    } else {
        return $this->verifySimpleModeCodeState($code);
    }
}
```

Add helper methods:
```php
private function isStrictMode(): bool
{
    return config('voting.two_codes_system') == 1;
}

private function hasVotingWindowExpired(&$code): bool
{
    if ($code->code_to_open_voting_form_used_at === null) return false;
    $totalDuration = Carbon::parse($code->code_to_open_voting_form_used_at)->diffInMinutes(now());
    return $totalDuration > ($code->voting_time_in_minutes ?? 30);
}

private function expireCode(&$code): void
{
    $code->can_vote_now = 0;
    $code->is_code_to_open_voting_form_usable = 0;
    $code->is_code_to_save_vote_usable = 0;
    $code->has_code1_sent = 0;
    $code->has_code2_sent = 0;
    $code->save();
}

private function verifySimpleModeCodeState(&$code): string
{
    if ($code->code_to_open_voting_form_used_at === null) {
        return "code.create";
    }
    if ($code->code_to_save_vote_used_at !== null) {
        return "dashboard";
    }
    return "";
}

private function verifyStrictModeCodeState(&$code): string
{
    if ($code->code_to_save_vote_used_at !== null || $code->is_code_to_save_vote_usable == 0) {
        return "dashboard";
    }
    if ($code->code_to_open_voting_form_used_at === null) {
        return "code.create";
    }
    return "";
}
```

### Fix 6: Update second_code_check() with Mode-Specific Logic
```php
public function second_code_check(&$code)
{
    $_message = [
        'error_message' => '',
        'return_to' => '',
        'totalDuration' => 0,
    ];
    
    $code_expires_in = $code->voting_time_in_minutes ?? 30;
    $totalDuration = Carbon::parse($code->code_to_open_voting_form_used_at)->diffInMinutes(now());
    $_message['totalDuration'] = $totalDuration;
    
    // Check voting window timeout
    if ($totalDuration > $code_expires_in) {
        $this->expireCode($code);
        $_message['return_to'] = 'code.create';
        return $_message;
    }
    
    // Mode-specific checks
    if ($this->isStrictMode()) {
        if (!$code->code_to_save_vote_used_at) {
            $this->expireCode($code);
            $_message['return_to'] = 'vote.create';
            return $_message;
        }
    } else {
        if (!$code->vote_submitted) {
            $this->expireCode($code);
            $_message['return_to'] = 'vote.create';
            return $_message;
        }
    }
    
    return $_message;
}
```

### Fix 7: Update verify_first_submission() with Demo/Real Route Detection
Replace the route redirection section with:
```php
$isDemoElection = $election && $election->type === 'demo';

if ($voterSlug) {
    if ($isDemoElection) {
        $redirect = redirect()->route('slug.demo-vote.verify', ['vslug' => $voterSlug->slug]);
    } else {
        $redirect = redirect()->route('slug.vote.verify', ['vslug' => $voterSlug->slug]);
    }
    return $redirect;
} else {
    if ($isDemoElection) {
        $redirect = redirect()->route('demo-vote.verify');
    } else {
        $redirect = redirect()->route('vote.verify');
    }
    return $redirect;
}
```

### Fix 8: Add receipt_hash Generation in save_vote()
Add after vote save (around line ~1200):
```php
// Generate receipt_hash for vote verification
$private_key = hash('sha256', $vote->id . $code->id . config('app.key'));
$receipt_hash = hash('sha256', $private_key . $vote->id . config('app.key'));
$vote->receipt_hash = $receipt_hash;
$vote->save();

// Return private_key for email notification
return $private_key;
```

### Fix 9: Set organisation context in create() and store()
Add in both methods after getting election:
```php
// Set organisation context for tenant scoping
session(['current_organisation_id' => $election->organisation_id]);
```

### Fix 10: Add IP Rate Limiting Election Scope in vote_post_check()
Update the IP check:
```php
$votesFromIP = Code::where('client_ip', $clientIP)
    ->where('election_id', $code->election_id)  // ✅ Scope to current election
    ->where('has_voted', 1)
    ->count();
```

## PHASE 4: RUN TESTS (GREEN)

```bash
php artisan test --filter VoteControllerTest
```

All 10 tests should now PASS.

## PHASE 5: FULL REGRESSION TESTING

```bash
php artisan test
```

Ensure no existing tests break. Pay special attention to:
- Existing voting flows
- CodeController tests
- DemoVoteController tests

## VERIFICATION CHECKLIST

- [ ] T1: has_voted blocks create() page
- [ ] T2: has_voted blocks first_submission()
- [ ] T3: has_voted blocks final store()
- [ ] T4: Code fetched by election_id, not relationship
- [ ] T5: Organisation validation prevents cross-org access
- [ ] T6: vote_pre_check() SIMPLE mode works
- [ ] T7: vote_pre_check() STRICT mode works
- [ ] T8: second_code_check() mode-specific logic
- [ ] T9: verify_first_submission() routes to correct demo/real pages
- [ ] T10: save_vote() generates receipt_hash
- [ ] T11: IP rate limiting scoped to election_id

## ADDITIONAL HELPER METHODS TO ADD

Add these private methods for consistency with DemoVoteController:

```php
/**
 * Verify plain text code (for Code1)
 */
private function verifyPlainCode($submitted, $expected): bool
{
    return strtoupper(trim($submitted)) === strtoupper(trim($expected));
}

/**
 * Sanitize vote data (already exists, ensure it's used)
 */
private function sanitize_vote_data($vote_data) // Already exists
```

## FILES TO MODIFY

1. `tests/Feature/VoteControllerTest.php` - **CREATE**
2. `app/Http/Controllers/VoteController.php` - **MODIFY**
3. `database/factories/CodeFactory.php` - **ADD voted() state if missing**

## DO NOT CHANGE

- Inertia rendering logic
- Candidate fetching logic (national_posts, regional_posts)
- Vue component names
- Existing route names (only add new route detection)
- Vote anonymity principle (no user_id in votes/results)

## OUTPUT FORMAT

Provide:
1. Complete test file: `tests/Feature/VoteControllerTest.php`
2. Complete updated: `app/Http/Controllers/VoteController.php` with all fixes applied
3. Summary of which tests passed/failed after implementation

## EXECUTION COMMAND

Run this prompt with: `claude code` in your terminal, or copy the entire prompt into Claude Code CLI.

The implementation will follow TDD strictly: tests first (RED), implementation (GREEN), then refactor while maintaining all security requirements for real elections (no re-voting)."

## Critical Analysis: VoteController vs DemoVoteController Comparison

After analyzing both controllers against the election architecture, here are the key issues and required fixes for `VoteController`:

---

## ISSUE 1: Missing `has_voted` Block in Multiple Entry Points 🔴

**Problem:** DemoVoteController has comprehensive `has_voted` checks in `create()`, `first_submission()`, `store()`. VoteController has them but missing in some places.

**VoteController Issues:**
- `create()` has the check ✅
- `first_submission()` has the check ✅
- `store()` has the check ✅
- **BUT `showAgreement()` in CodeController was missing - already fixed**
- **`submitAgreement()` in VoteController?** Actually VoteController doesn't have agreement methods (those are in CodeController)

**Fix:** Ensure all entry points in both controllers have `has_voted` checks for real elections.

---

## ISSUE 2: Incorrect Code Model Fetching in `first_submission()` 🔴

**VoteController Line ~470-490:**
```php
// REAL ELECTIONS: Get Code through relationship
$code = $auth_user->code;  // ❌ Uses relationship, not election-scoped!
```

**DemoVoteController does it correctly:**
```php
if ($election->type === 'demo') {
    $code = DemoCode::where('user_id', $auth_user->id)
        ->where('election_id', $election->id)
        ->first();
} else {
    $code = Code::where('user_id', $auth_user->id)
        ->where('election_id', $election->id)  // ✅ Election-scoped
        ->first();
}
```

**Fix:** Replace `$auth_user->code` with election-scoped query:
```php
$code = Code::where('user_id', $auth_user->id)
    ->where('election_id', $election->id)
    ->first();
```

---

## ISSUE 3: Missing `vote_submitted` Save in `first_submission()` 🟡

**VoteController Line ~460:**
```php
$code->vote_submitted    = 1;
$code->vote_submitted_at = \Carbon\Carbon::now();
// $code->save(); // ❌ Commented out! This breaks session data retrieval
```

**DemoVoteController does it correctly:**
```php
$code->vote_submitted    = 1;
$code->vote_submitted_at = \Carbon\Carbon::now();
$code->save(); // ✅ Saves immediately
```

**Fix:** Uncomment the `save()` call.

---

## ISSUE 4: Missing `getElection()` Organisation Validation 🔴

**VoteController's `getElection()` lacks validation:**
```php
private function getElection(Request $request): Election
{
    // First, check if middleware set an election
    if ($request->attributes->has('election')) {
        return $request->attributes->get('election');  // ❌ No validation
    }
    // ... rest
}
```

**DemoVoteController has the same issue** - both need validation like CodeController.

**Fix:** Add validation from CodeController's `getElection()`:
```php
private function getElection(Request $request): Election
{
    $election = $request->attributes->get('election')
        ?? Election::where('type', 'real')->first();
    $voterSlug = $request->attributes->get('voter_slug');

    if ($voterSlug && $election) {
        if ($election->id !== $voterSlug->election_id) {
            throw new \Exception('Election mismatch detected');
        }
        $orgsMatch = $election->organisation_id === $voterSlug->organisation_id;
        $isPlatformElection = $election->organisation_id == 1;
        $isPlatformSlug = $voterSlug->organisation_id == 1;
        
        if (!$orgsMatch && !$isPlatformElection && !$isPlatformSlug) {
            throw new \Exception('Organisation mismatch detected');
        }
    }
    return $election;
}
```

---

## ISSUE 5: Missing `isUserEligibleToVote()` Election Type Check 🔴

**VoteController's method:**
```php
private function isUserEligibleToVote(User $user, Election $election): bool
{
    if ($election->isDemo()) {
        return true;
    }
    
    // REAL: Check if user has a verified code (can_vote_now=1) for this election
    return Code::withoutGlobalScopes()
        ->where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->where('can_vote_now', 1)
        ->exists();
}
```

**This is actually correct!** ✅ But ensure it's used consistently.

---

## ISSUE 6: `save_vote()` Missing Organisation Context for Real Elections 🟡

**VoteController Line ~1200-1250:**
```php
if ($election->type === 'real') {
    $vote->organisation_id = $election->organisation_id;
} else {
    $vote->organisation_id = session('current_organisation_id');
}
```

**This is correct for real elections!** ✅

But verify that `session('current_organisation_id')` is set for demo elections in VoteController's `create()` and `store()` methods.

**DemoVoteController sets it in `create()` and `store()`:**
```php
session(['current_organisation_id' => $election->organisation_id]);
```

**VoteController should also set it in both places.**

---

## ISSUE 7: Missing `receipt_hash` in VoteController's `save_vote()` 🟡

**DemoVoteController has receipt_hash generation:**
```php
$private_key = hash('sha256', $vote->id . $code->id . config('app.key'));
$receipt_hash = hash('sha256', $private_key . $vote->id . config('app.key'));
$vote->receipt_hash = $receipt_hash;
```

**VoteController lacks this entirely** - it uses old `code_for_vote` and `voting_code` fields.

**Fix:** Add receipt_hash generation to VoteController's `save_vote()` for consistency and to support `retrieve_demo_vote_record` pattern (though real votes use different retrieval).

---

## ISSUE 8: `vote_pre_check()` Missing Mode-Specific Logic 🔴

**VoteController's `vote_pre_check()` is outdated:**
```php
public function vote_pre_check(&$code){
    // Simple checks only
    if($code==null) return "code.create";
    if(!$code->can_vote_now) return "dashboard";
    if($code->has_voted) return "dashboard";
    if(!$code->has_code1_sent) return "code.create";
    // No mode-specific verification!
}
```

**DemoVoteController has comprehensive mode handling:**
- `verifySimpleModeCodeState()` - checks code1 used twice
- `verifyStrictModeCodeState()` - checks separate code1/code2
- `hasVotingWindowExpired()` - checks timing
- `expireCode()` - resets all flags

**Fix:** Backport DemoVoteController's `vote_pre_check()` logic to VoteController.

---

## ISSUE 9: `second_code_check()` Missing Mode-Specific Logic 🟡

**VoteController's `second_code_check()`:**
```php
public function second_code_check(&$code){
    // Basic checks only
    $totalDuration = Carbon::parse($code_to_open_voting_form_used_at)->diffInMinutes($current);
    if($totalDuration > $code_expires_in) {
        // expire code
    }
    if(!$code->vote_submitted){
        // redirect
    }
    return $_message;
}
```

**DemoVoteController has mode-specific checks:**
- STRICT MODE: checks `code_to_save_vote_used_at` exists
- SIMPLE MODE: checks `vote_submitted`

**Fix:** Add mode-specific logic to VoteController.

---

## ISSUE 10: `verify_first_submission()` Missing Election Type Awareness 🟡

**VoteController's `verify_first_submission()` uses hardcoded routes:**
```php
$route = $voterSlug ? 'slug.vote.verify' : 'vote.verify';
// ❌ No handling for demo elections
```

**DemoVoteController has correct routing:**
```php
if ($voterSlug) {
    if ($isDemoElection) {
        $redirect = redirect()->route('slug.demo-vote.verify', ['vslug' => $voterSlug->slug]);
    } else {
        $redirect = redirect()->route('slug.vote.verify', ['vslug' => $voterSlug->slug]);
    }
}
```

**Fix:** Add demo/real route detection.

---

## ISSUE 11: Missing `verify_submitted_code()` Helper Method 🟡

**VoteController uses inline code verification in `store()`:**
```php
$_codeVerified = $this->verify_submitted_code($this->in_code, $this->out_code);
```

**But the method exists!** ✅ Line ~2400 - it's defined.

---

## ISSUE 12: Missing `retrieve_demo_vote_record()` Equivalent for Real Elections 🟡

**DemoVoteController has sophisticated retrieval with receipt_hash validation.**
**VoteController's `retrieve_vote_record()` is simpler.**

**Consider:** Align both with the same receipt_hash pattern for consistency.

---

## SUMMARY OF CRITICAL FIXES

| # | Issue | Priority | File | Fix |
|---|-------|----------|------|-----|
| 2 | Wrong code model fetching in `first_submission()` | 🔴 Critical | VoteController | Use election-scoped query |
| 3 | Missing `vote_submitted` save | 🔴 Critical | VoteController | Uncomment `$code->save()` |
| 4 | Missing organisation validation in `getElection()` | 🔴 Critical | Both Controllers | Add validation from CodeController |
| 8 | Outdated `vote_pre_check()` | 🔴 Critical | VoteController | Backport DemoVoteController logic |
| 9 | Missing mode-specific `second_code_check()` | 🟡 Medium | VoteController | Add mode detection |
| 10 | Missing demo routes in `verify_first_submission()` | 🟡 Medium | VoteController | Add isDemoElection routing |
| 7 | Missing `receipt_hash` in `save_vote()` | 🟡 Medium | VoteController | Add receipt_hash generation |
| 1 | `has_voted` checks | ✅ Already present | - | Verify all entry points |

---

## RECOMMENDED ACTION

**Proceed with a TDD approach for VoteController similar to CodeController:**

1. **Create test file:** `tests/Feature/VoteControllerTest.php` with tests for:
   - T1: Real election - cannot vote twice (`has_voted` blocks)
   - T2: Real election - code model fetched by election_id
   - T3: Organisation validation in `getElection()`
   - T4: `vote_pre_check()` mode-specific behavior
   - T5: `second_code_check()` mode-specific behavior
   - T6: Demo vs real routing in `verify_first_submission()`
   - T7: `receipt_hash` generation and verification

2. **Implement fixes** to make tests pass

3. **Refactor** to align with DemoVoteController patterns

Would you like me to create the TDD plan and test file for VoteController similar to what we did for CodeController?
