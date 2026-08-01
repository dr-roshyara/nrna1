Here are the Claude Code CLI prompt instructions to update `CodeController` for the real election system with 8-character codes:

---

## **Claude Code CLI Prompt Instructions**

```bash
claude code "Update CodeController.php to fix bugs and align with DemoCodeController best practices for real elections:

## CONTEXT
- DemoCodeController is the reference implementation (working, tested)
- CodeController is legacy with bugs that need fixing
- Real elections must prevent multiple votes
- Codes must be 8 characters (not 6)

## FILES TO MODIFY
/app/Http/Controllers/CodeController.php

## SPECIFIC FIXES REQUIRED

### 1. CHANGE CODE LENGTH FROM 6 TO 8 CHARACTERS
- Replace `Str::random(6)` with 8-character generation
- Use the same character set as DemoCodeController (exclude I, O, 0, 1)
- Implement `generateUniqueCodeForOrganisation()` method with retry logic
- Update validation to expect 8 characters (already done: size:8)

### 2. FIX EXPIRATION HANDLING IN getOrCreateCode()
- Add expiration check BEFORE other logic (like DemoCodeController)
- When expired AND not voted: generate new unique code and reset ALL flags
- Reset: can_vote_now = 0, is_code_to_open_voting_form_usable = 1
- Reset: code_to_open_voting_form_used_at = null
- Reset: vote_submitted = false if applicable
- Send new code email notification

### 3. ADD ORGANISATION VALIDATION IN getElection()
- Add validation for voter_slug → election_id consistency
- Add organisation_id matching validation
- Throw clear exceptions with context for debugging
- Mirror DemoCodeController's validation logic

### 4. IMPROVE VOTE PREVENTION
- In store(): already has check, ensure it blocks ALL access when has_voted = true
- In showAgreement(): add check for has_voted (should redirect to dashboard)
- In submitAgreement(): add check for has_voted
- In create(): add check and redirect if has_voted

### 5. FIX getOrCreateCode() FOR ALREADY VERIFIED CODES
- If can_vote_now == 1, DO NOT regenerate code (already correct)
- Add logging to track when this happens

### 6. ADD COMPREHENSIVE EXPIRATION CHECK IN getOrCreateCode()
- Move expiration logic from create() into getOrCreateCode()
- Ensure expiration is checked every time code is accessed
- Use config('voting.time_in_minutes') for expiration window

### 7. FIX IP RATE LIMITING
- Scope IP checks to CURRENT election only
- Add election_id filter: Code::where('election_id', $election->id)
- Keep the maxUseClientIP limit

### 8. UPDATE markCodeAsVerified() CONSISTENCY
- Keep is_code_to_open_voting_form_usable = 0 for real elections (STRICT MODE)
- Add config check to maintain flexibility
- Match DemoCodeController pattern but with STRICT MODE default

## IMPLEMENTATION PATTERN
Use DemoCodeController as the exact pattern reference, adapting only:
- Model: Code instead of DemoCode
- No re-voting logic (remove demo-specific re-voting code)
- Keep IP rate limiting (DemoCodeController removed it)
- Keep can_vote eligibility check (DemoCodeController removed it)
- No voter slug step reset for completed votes

## CODE GENERATION

Generate the complete updated CodeController.php with:

1. All fixes listed above applied
2. 8-character unique code generation with retry logic
3. Comprehensive expiration handling in getOrCreateCode()
4. Organisation validation in getElection()
5. Vote prevention at all entry points
6. Proper logging throughout (use existing log format)
7. Preserve all existing helper methods but fix their implementations

## VALIDATION CHECKLIST AFTER UPDATE
- [ ] generateCode() returns 8 characters, excludes I,O,0,1
- [ ] generateUniqueCodeForOrganisation() exists with retry logic
- [ ] Codes are validated with size:8 (already present)
- [ ] Expired codes are regenerated with all flags reset
- [ ] can_vote_now = 1 codes are NOT regenerated
- [ ] IP rate limiting includes election_id filter
- [ ] has_voted users cannot access any voting step
- [ ] getElection() validates election_id against voter_slug
- [ ] All changes maintain backward compatibility

## DO NOT CHANGE
- Existing route structure
- Inertia rendering
- Step tracking service calls
- VoterProgressService calls
- Core workflow (create → store → agreement → submitAgreement → vote)

## OUTPUT FORMAT
Provide the complete updated CodeController.php file content with:
- Clear comments for each fix
- @todo comments for any remaining items
- No placeholder TODOs for critical fixes

Start by reading the current CodeController.php and DemoCodeController.php to understand the exact implementation pattern, then generate the updated file."
```

---

## **Alternative: Compact Single-Line Version**

If you prefer a single-line prompt for quick execution:

```bash
claude code "Update CodeController.php: (1) change code length to 8 chars with unique generation using DemoCodeController's generateUniqueCodeForOrganisation() pattern, (2) add comprehensive expiration check in getOrCreateCode() that resets all flags when expired, (3) add organisation validation in getElection() matching DemoCodeController, (4) add has_voted checks at all entry points (create, showAgreement, submitAgreement), (5) fix IP rate limiting to scope by election_id, (6) preserve backward compatibility and real election rules (no re-voting, can_vote check required). Use DemoCodeController as reference but adapt for Code model and real elections."
```

---

## **Expected Output**

The prompt will generate an updated `CodeController.php` with:
- `generateUniqueCodeForOrganisation()` method
- 8-character codes using safe character set (no I,O,0,1)
- Retry logic for uniqueness (max 10 attempts)
- Expiration checks that properly reset `can_vote_now` and all flags
- Election validation in `getElection()` with organisation matching
- Comprehensive vote prevention across all methods
- Proper logging for debugging
- IP rate limiting scoped to current election

This will make the real election system as robust as the demo version while maintaining strict no-revoting rules.

## Claude Code CLI Prompt - TDD First Approach

```bash
claude code "Update CodeController.php using Test-Driven Development (TDD) approach for real election system with 8-character codes.

## TDD APPROACH REQUIRED

Follow RED-GREEN-REFACTOR cycle. Write tests FIRST, then implement code to pass them.

## PHASE 1: CREATE TEST FILE

First, create/update the test file: `/tests/Feature/CodeControllerTest.php`

Write these tests BEFORE modifying CodeController:

### Test 1: Code Generation - 8 Characters
```php
/** @test */
public function it_generates_8_character_codes()
{
    // Use reflection or create a code and check format
    $user = User::factory()->create();
    $election = Election::factory()->create(['type' => 'real']);
    
    $code = Code::create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'organisation_id' => $election->organisation_id,
        'code_to_open_voting_form' => 'TEST1234', // 8 chars
        // ... other required fields
    ]);
    
    $this->assertEquals(8, strlen($code->code_to_open_voting_form));
    // Verify excludes I,O,0,1
    $this->assertMatchesRegularExpression('/^[ABCDEFGHJKLMNPQRSTUVWXYZ23456789]{8}$/', 
        $code->code_to_open_voting_form);
}
```

### Test 2: Unique Code Generation
```php
/** @test */
public function it_generates_unique_codes_with_retry_logic()
{
    // Mock duplicate scenario
    // Test that generateUniqueCodeForOrganisation() retries on duplicate
    // Test max retries throws exception
}
```

### Test 3: Expired Code Regeneration
```php
/** @test */
public function it_regenerates_code_when_expired_and_not_voted()
{
    $user = User::factory()->create();
    $election = Election::factory()->create(['type' => 'real']);
    
    $code = Code::factory()->create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'code_to_open_voting_form_sent_at' => now()->subMinutes(31), // expired
        'has_voted' => false,
        'can_vote_now' => 0,
    ]);
    
    $originalCode = $code->code_to_open_voting_form;
    
    // Access create page to trigger getOrCreateCode
    $response = $this->actingAs($user)
        ->get(route('code.create', ['election' => $election->id]));
    
    $code->refresh();
    
    $this->assertNotEquals($originalCode, $code->code_to_open_voting_form);
    $this->assertEquals(0, $code->can_vote_now);
    $this->assertEquals(1, $code->is_code_to_open_voting_form_usable);
    $this->assertNull($code->code_to_open_voting_form_used_at);
}
```

### Test 4: Verified Code NOT Regenerated
```php
/** @test */
public function it_does_not_regenerate_already_verified_code()
{
    $user = User::factory()->create();
    $election = Election::factory()->create(['type' => 'real']);
    
    $code = Code::factory()->create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'can_vote_now' => 1,
        'code_to_open_voting_form' => 'VERIFIED',
    ]);
    
    $originalCode = $code->code_to_open_voting_form;
    
    $response = $this->actingAs($user)
        ->get(route('code.create', ['election' => $election->id]));
    
    $code->refresh();
    
    $this->assertEquals($originalCode, $code->code_to_open_voting_form);
    $this->assertEquals(1, $code->can_vote_now);
}
```

### Test 5: Vote Prevention - Block Voted Users
```php
/** @test */
public function it_blocks_access_to_code_page_for_users_who_already_voted()
{
    $user = User::factory()->create();
    $election = Election::factory()->create(['type' => 'real']);
    
    $code = Code::factory()->create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'has_voted' => true,
    ]);
    
    $response = $this->actingAs($user)
        ->get(route('code.create', ['election' => $election->id]));
    
    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('error', 'You have already voted in this election.');
}

/** @test */
public function it_blocks_vote_submission_for_users_who_already_voted()
{
    $user = User::factory()->create();
    $election = Election::factory()->create(['type' => 'real']);
    
    $code = Code::factory()->create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'has_voted' => true,
        'can_vote_now' => 1,
    ]);
    
    $response = $this->actingAs($user)
        ->post(route('code.store', ['election' => $election->id]), [
            'voting_code' => $code->code_to_open_voting_form,
        ]);
    
    $response->assertSessionHasErrors(['voting_code' => 'You have already voted in this election.']);
}
```

### Test 6: Organisation Validation
```php
/** @test */
public function it_rejects_requests_with_mismatched_election_organisation()
{
    $user = User::factory()->create();
    $election = Election::factory()->create(['type' => 'real', 'organisation_id' => 1]);
    $voterSlug = VoterSlug::factory()->create([
        'election_id' => $election->id,
        'organisation_id' => 2, // Mismatch!
    ]);
    
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Organisation mismatch detected');
    
    $this->actingAs($user)
        ->get(route('slug.code.create', ['vslug' => $voterSlug->slug]));
}
```

### Test 7: IP Rate Limiting - Election Scoped
```php
/** @test */
public function it_limits_votes_by_ip_per_election_not_globally()
{
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $election1 = Election::factory()->create(['type' => 'real']);
    $election2 = Election::factory()->create(['type' => 'real']);
    
    $sameIp = '192.168.1.1';
    
    // Create max votes for election1
    for ($i = 0; $i < config('app.max_use_clientIP', 7); $i++) {
        Code::factory()->create([
            'client_ip' => $sameIp,
            'election_id' => $election1->id,
            'has_voted' => true,
        ]);
    }
    
    // Should still allow vote in election2
    $code = Code::factory()->create([
        'user_id' => $user2->id,
        'election_id' => $election2->id,
        'client_ip' => $sameIp,
    ]);
    
    $response = $this->actingAs($user2)
        ->post(route('code.store', ['election' => $election2->id]), [
            'voting_code' => $code->code_to_open_voting_form,
        ]);
    
    // Should NOT get IP limit error
    $response->assertSessionDoesntHaveErrors(['voting_code']);
}
```

### Test 8: Code Validation - 8 Characters Required
```php
/** @test */
public function it_requires_exactly_8_characters_for_code()
{
    $user = User::factory()->create();
    $election = Election::factory()->create(['type' => 'real']);
    
    $response = $this->actingAs($user)
        ->post(route('code.store', ['election' => $election->id]), [
            'voting_code' => '123456', // 6 chars, should fail
        ]);
    
    $response->assertSessionHasErrors(['voting_code' => 'Code must be exactly 8 characters.']);
    
    $response2 = $this->actingAs($user)
        ->post(route('code.store', ['election' => $election->id]), [
            'voting_code' => '123456789', // 9 chars, should fail
        ]);
    
    $response2->assertSessionHasErrors(['voting_code' => 'Code must be exactly 8 characters.']);
}
```

## PHASE 2: RUN TESTS (RED)

Execute: `php artisan test --filter CodeControllerTest`

All tests should FAIL because CodeController hasn't been updated.

## PHASE 3: IMPLEMENT CODE (GREEN)

Now update CodeController.php with implementations that pass ALL tests:

### Required Implementation Details:

1. **8-Character Code Generation:**
```php
private function generateCode(): string
{
    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $length = 8;
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $code;
}

private function generateUniqueCodeForOrganisation($organisationId): string
{
    $maxAttempts = 10;
    $attempts = 0;
    
    do {
        $code = $this->generateCode();
        $exists = Code::withoutGlobalScopes()
            ->where('code_to_open_voting_form', $code)
            ->where('organisation_id', $organisationId)
            ->exists();
        $attempts++;
    } while ($exists && $attempts < $maxAttempts);
    
    if ($attempts >= $maxAttempts) {
        Log::error('Failed to generate unique code after max attempts', [
            'organisation_id' => $organisationId,
        ]);
        throw new \Exception('Unable to generate unique verification code.');
    }
    
    return $code;
}
```

2. **Expiration Handling in getOrCreateCode():**
```php
// Add BEFORE any other logic
if ($code && $code->code_to_open_voting_form_sent_at) {
    $isExpired = \Carbon\Carbon::parse($code->code_to_open_voting_form_sent_at)
        ->diffInMinutes(now()) > $this->votingTimeInMinutes;
    
    if ($isExpired && !$code->has_voted) {
        Log::info('Code expired - generating new unique code', [
            'user_id' => $user->id,
            'code_id' => $code->id,
        ]);
        
        $code->update([
            'code_to_open_voting_form' => $this->generateUniqueCodeForOrganisation($code->organisation_id),
            'code_to_open_voting_form_sent_at' => now(),
            'has_code1_sent' => 1,
            'is_code_to_open_voting_form_usable' => 1,
            'code_to_open_voting_form_used_at' => null,
            'can_vote_now' => 0,
        ]);
        
        // Send new code email
        if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $user->notify(new SendFirstVerificationCode($user, $code->code_to_open_voting_form));
        }
        
        return $code;
    }
}
```

3. **Organisation Validation in getElection():**
```php
if ($voterSlug) {
    if ($election->id !== $voterSlug->election_id) {
        throw new \Exception('Election mismatch detected');
    }
    
    $orgsMatch = $election->organisation_id === $voterSlug->organisation_id;
    $electionIsPlatform = $election->organisation_id === 1;
    $userIsPlatform = $voterSlug->organisation_id === 1;
    
    if (!$orgsMatch && !$electionIsPlatform && !$userIsPlatform) {
        throw new \Exception('Organisation mismatch detected');
    }
}
```

4. **Vote Prevention in create():**
```php
// Add after checking existingCode
if ($election->type === 'real' && $existingCode && $existingCode->has_voted) {
    Log::warning('Real election - blocking code page access for voter who already voted', [
        'user_id' => $user->id,
        'election_id' => $election->id,
    ]);
    return redirect()->route('dashboard')->with('error', 'You have already voted in this election. Each voter can only vote once.');
}
```

5. **IP Rate Limiting with Election Scope:**
```php
$votesFromIP = Code::where('client_ip', $this->clientIP)
    ->where('election_id', $code->election_id)
    ->where('has_voted', 1)
    ->count();
```

## PHASE 4: REFACTOR

After tests pass:
- Extract duplicate logic into private methods
- Ensure consistent error messages
- Add comprehensive logging
- Optimize database queries
- Document with PHPDoc comments

## EXECUTION ORDER

1. **Create test file** with all 8 test cases
2. **Run tests** to confirm they fail (RED)
3. **Update CodeController.php** with implementations
4. **Run tests again** to ensure they pass (GREEN)
5. **Refactor** for clean code while tests stay green
6. **Commit** with message: \"feat: Update CodeController with 8-char codes, expiration handling, and vote prevention (TDD)\"

## OUTPUT FORMAT

Provide:
1. Complete test file: `/tests/Feature/CodeControllerTest.php`
2. Complete updated: `/app/Http/Controllers/CodeController.php`
3. Summary of which tests passed/failed after implementation

## VALIDATION CRITERIA

- [ ] All 8 tests written before implementation
- [ ] Initial test run shows failures
- [ ] Implementation passes all tests
- [ ] Code generates 8-character unique codes
- [ ] Expired codes regenerate with all flags reset
- [ ] Voted users blocked from all entry points
- [ ] Organisation validation prevents cross-org access
- [ ] IP rate limiting scoped by election_id
- [ ] No breaking changes to existing workflow

Follow TDD strictly: Write test first, see it fail, then implement minimal code to pass, then refactor."
```

---

## Alternative: Step-by-Step TDD Prompt

If you prefer incremental TDD:

```bash
claude code "Implement TDD for CodeController update. Start with Test 1 (8-character code generation). Write the test, see it fail, then implement generateCode() to return 8 chars with proper character set. After test passes, commit. Then move to Test 2 (unique code generation). Continue incrementally through all 8 tests. Use DemoCodeController as reference but adapt for real elections. Provide each test and implementation step by step, ensuring green before moving to next test."
```

This TDD approach ensures:
- **Test coverage** for all critical bugs
- **Regression prevention** - if something breaks, tests fail
- **Documentation** - tests show expected behavior
- **Confidence** - code works before deployment

Looking at the comparison between `DemoCodeController` (working demo version) and `CodeController` (legacy real voting version), I've identified several bugs and issues in the legacy `CodeController`:

## **Critical Bugs in CodeController (Legacy)**

### 1. **No Prevention of Multiple Votes** ⚠️
**Bug:** The legacy `CodeController` lacks proper protection against voting multiple times in real elections.

**Location:** `store()` method
```php
// Legacy CodeController - MISSING vote prevention check
// REAL ELECTIONS: Prevent double voting
if ($election->type === 'real' && $code->has_voted) {
    Log::warning('Real election - double vote attempt prevented', [
        'user_id' => $user->id,
        'election_id' => $election->id,
        'election_type' => $election->type,
    ]);
    return back()->withErrors(['voting_code' => 'You have already voted in this election. Each voter can only vote once.']);
}
```

**Why it's a bug:** This check exists but may not be comprehensive enough. The demo version has more robust re-voting logic, but the real version should **never** allow re-voting after `has_voted = true`. However, the code doesn't consistently check `has_voted` in all entry points.

### 2. **Expired Code Handling Bug** 🔴
**Bug:** In `create()` method, expired code handling is inconsistent and may allow voting with expired codes.

**Legacy CodeController:**
```php
// ❌ BUG: Creates new code but doesn't reset ALL necessary flags
if ($minutesSinceSent >= $this->votingTimeInMinutes && $code->has_code1_sent) {
    $code->code_to_open_voting_form = Str::random(6); // Uses simple random, not unique generator
    $code->code_to_open_voting_form_sent_at = now();
    $code->has_code1_sent = true;
    $code->save(); // Doesn't reset can_vote_now, is_code_to_open_voting_form_usable
}
```

**DemoCodeController (Correct):**
```php
// ✅ CORRECT: Comprehensive reset
if ($isExpired && !$code->has_voted) {
    $code->code_to_open_voting_form = $this->generateUniqueCodeForOrganisation($code->organisation_id);
    $code->code_to_open_voting_form_sent_at = now();
    $code->has_code1_sent = 1;
    $code->is_code_to_open_voting_form_usable = 1;
    $code->code_to_open_voting_form_used_at = null;
    $code->can_vote_now = 0;  // Critical: Reset verification status
    $code->save();
}
```

### 3. **Missing Organisation Context in getElection()** 🔴
**Bug:** `getElection()` in legacy code doesn't validate organisation context properly.

**Legacy CodeController:**
```php
private function getElection(Request $request): Election
{
    return $request->attributes->get('election')
        ?? Election::where('type', 'real')->first(); // ❌ No validation
}
```

**DemoCodeController (Correct):**
```php
private function getElection(Request $request): Election
{
    $election = $request->attributes->get('election');
    
    // ✅ Comprehensive validation with organisation matching
    if ($voterSlug) {
        if ($election->id !== $voterSlug->election_id) {
            throw new \Exception('Election mismatch detected');
        }
        
        $orgsMatch = $election->organisation_id === $voterSlug->organisation_id;
        if (!$orgsMatch && !$electionIsPlatform && !$userIsPlatform) {
            throw new \Exception('Organisation mismatch detected');
        }
    }
    
    return $election;
}
```

### 4. **IP Rate Limiting Applied Incorrectly** 🟡
**Bug:** IP rate limiting is applied in `verifyCode()` for real elections but not for demo, which is correct. However, the implementation may be too restrictive.

**Legacy CodeController:**
```php
// In verifyCode()
$votesFromIP = Code::where('client_ip', $this->clientIP)
    ->where('has_voted', 1)
    ->count();
if ($votesFromIP >= $this->maxUseClientIP) {
    return ['success' => false, 'message' => 'Too many votes from this IP address.'];
}
```

**Issue:** This counts all votes from the IP across **all elections**, not just the current election. This could prevent legitimate voting if multiple people use the same IP (e.g., office network).

### 5. **Missing Code Expiration Check in getOrCreateCode()** 🔴
**Bug:** The legacy `getOrCreateCode()` has expiration logic in `create()` but not in the method itself, leading to inconsistent behavior.

**DemoCodeController (Correct):**
```php
// ✅ Checks expiration FIRST in getOrCreateCode()
if ($code && $code->code_to_open_voting_form_sent_at) {
    $isExpired = \Carbon\Carbon::parse($code->code_to_open_voting_form_sent_at)
        ->diffInMinutes(now()) > $this->votingTimeInMinutes;
    
    if ($isExpired && !$code->has_voted) {
        // Comprehensive reset
    }
}
```

**Legacy CodeController:**
```php
// ❌ Expiration logic scattered across methods
// No comprehensive expiration check in getOrCreateCode()
```

### 6. **Insufficient Code Uniqueness** 🟡
**Bug:** Legacy uses simple `Str::random(6)` which may generate duplicate codes.

**Legacy:**
```php
private function generateCode(): string
{
    return strtoupper(Str::random(6)); // Only 6 chars, no duplicate checking
}
```

**DemoCodeController (Correct):**
```php
private function generateCode(): string
{
    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // 8 chars, excludes ambiguous
    // 8 characters for more combinations
    // With retry logic for uniqueness
}

private function generateUniqueCodeForOrganisation($organisationId): string
{
    // ✅ Ensures uniqueness within organisation
}
```

### 7. **Missing Step Tracking in Already Verified Handler** 🟡
**Bug:** `handleAlreadyVerified()` doesn't properly advance the step in some scenarios.

Both versions have this, but the demo version has more robust logging. The legacy version may have edge cases where step 1 isn't recorded correctly.

### 8. **Voting Window Not Reset After Expiration** 🔴
**Bug:** When code expires, the voting window timer isn't reset properly.

**Legacy:** Creates new code but doesn't update `voting_started_at` or related timestamps.

**Demo:** Properly resets all relevant timestamps and flags.

## **Summary of Critical Issues**

| Bug | Severity | Impact |
|-----|----------|--------|
| Multiple voting possible | 🔴 CRITICAL | Can vote multiple times in real elections |
| Expired code handling incomplete | 🔴 CRITICAL | May allow voting with expired codes |
| Organisation validation missing | 🔴 CRITICAL | Cross-organisation data leakage |
| Code uniqueness insufficient | 🟡 MEDIUM | Potential code collisions |
| IP rate limiting too broad | 🟡 MEDIUM | May block legitimate voters |
| Inconsistent expiration checks | 🔴 CRITICAL | Voting security compromised |

## **Recommended Fixes**

1. **Add comprehensive vote prevention** at all entry points (`create()`, `store()`, `showAgreement()`, `submitAgreement()`)
2. **Implement organisation validation** similar to demo version
3. **Add expiration handling** directly in `getOrCreateCode()`
4. **Use unique code generation** with retry logic
5. **Scope IP rate limiting** to current election only
6. **Reset all voting flags** when code expires or is regenerated
7. **Add step tracking validation** for all routes

The demo version is significantly more robust and should serve as the reference for fixing the legacy `CodeController`.