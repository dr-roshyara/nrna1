# Testing Patterns & Troubleshooting Guide

**Best practices for testing DeviceFingerprint, models, and multi-tenancy scenarios**

---

## TDD Workflow (The 5-Step Pattern)

### Step 1: Write Failing Test (RED)

```php
/** @test */
public function it_generates_consistent_hash_for_same_device()
{
    $request = new Request([], [], [], [], [], [
        'REMOTE_ADDR' => '192.168.1.1',
        'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.9',
    ]);

    $service = new DeviceFingerprint();

    $hash1 = $service->generate($request);
    $hash2 = $service->generate($request);

    $this->assertEquals($hash1, $hash2);  // ← Will fail - method doesn't exist
}
```

**Run:** `php artisan test --filter=test_name`
**Expected:** FAIL - method not found

### Step 2: Verify Test Fails (Confirm RED)

```
❌ FAILED Tests\Unit\Services\DeviceFingerprintTest::test_it_generates_consistent_hash_for_same_device

Call to undefined method DeviceFingerprint::generate()
```

### Step 3: Write Minimal Implementation (GREEN)

```php
class DeviceFingerprint
{
    public function generate(Request $request, array $additional = []): string
    {
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent', '');
        $salt = config('app.key');

        $fingerprint = $ip . $userAgent . $salt;
        foreach ($additional as $value) {
            $fingerprint .= (string) $value;
        }

        return hash('sha256', $fingerprint);
    }
}
```

### Step 4: Run Test Again (Verify GREEN)

```
✅ PASSED Tests\Unit\Services\DeviceFingerprintTest::test_it_generates_consistent_hash_for_same_device

OK (1 test, 1 assertion)
```

### Step 5: Refactor & Commit

```bash
git add app/Services/DeviceFingerprint.php tests/Unit/Services/DeviceFingerprintTest.php
git commit -m "feat: implement device fingerprint generation with tests

- Add DeviceFingerprint::generate() for SHA256-based device identification
- Tests verify hash consistency for same device
- Privacy-preserving: hashes IP and user agent, not stored raw
- Registration: Add to AppServiceProvider singleton"
```

---

## Handling Global Scopes in Tests

### The Problem

Models with `BelongsToTenant` trait add automatic WHERE clauses:

```php
class Code extends Model
{
    use BelongsToTenant;  // Adds global scope
}

// This query:
$code = Code::find($codeId);

// Actually runs:
SELECT * FROM codes WHERE id = ? AND organisation_id = {current_org_id}

// If current_org_id doesn't match, returns NULL!
```

### The Solution

Use `withoutGlobalScopes()` in tests:

```php
// ❌ WRONG - Returns null due to scope
$code = Code::find($testCodeId);

// ✅ CORRECT - Bypasses scope
$code = Code::withoutGlobalScopes()->find($testCodeId);
```

### When to Use

**Use withoutGlobalScopes() for:**
- Raw database inserts (test setup)
- Cross-tenant queries
- Service layer testing
- Accessing test-created records

**Don't use for:**
- Integration tests (want real scoping)
- Testing permission/security logic
- Production queries

---

## Raw Database Inserts for Test Data

### Why Raw Inserts?

Factory pattern often breaks in tests due to:
- Boot hooks interfering
- Circular dependencies
- Lazy resolution issues
- Randomization making tests non-deterministic

### Raw Insert Pattern

```php
protected function createCodeWithDevice(
    string $deviceHash,
    string $electionId,
    string $orgId
): Code
{
    $faker = FakerFactory::create();

    // 1. Ensure election exists
    if (!DB::table('elections')->where('id', $electionId)->exists()) {
        DB::table('elections')->insert([
            'id' => $electionId,
            'organisation_id' => $orgId,
            'name' => $faker->word(),
            'slug' => $faker->slug(),
            'type' => 'demo',
            'is_active' => 1,
            'start_date' => now(),
            'end_date' => now()->addDays(7),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // 2. Create user
    $userId = Str::uuid()->toString();
    DB::table('users')->insert([
        'id' => $userId,
        'organisation_id' => $orgId,
        'name' => $faker->name(),
        'email' => $faker->email(),
        'password' => 'hashed',
        'remember_token' => null,
        'email_verified_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // 3. Create code
    $codeId = Str::uuid()->toString();
    DB::table('codes')->insert([
        'id' => $codeId,
        'organisation_id' => $orgId,
        'user_id' => $userId,
        'election_id' => $electionId,
        'device_fingerprint_hash' => $deviceHash,
        'code1' => (string) rand(100000, 999999),
        'code2' => (string) rand(100000, 999999),
        'is_code1_usable' => 1,
        'is_code2_usable' => 0,
        'can_vote_now' => 0,
        'has_voted' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // 4. Return without global scopes
    return Code::withoutGlobalScopes()->find($codeId)
        ?? throw new \Exception("Code not found: $codeId");
}
```

### Advantages

| Advantage | Benefit |
|-----------|---------|
| **Deterministic** | Same input = same output |
| **Fast** | Single INSERT, no factory overhead |
| **Isolated** | No boot hooks interfering |
| **Explicit** | All fields visible in code |
| **Debuggable** | Easy to see what's being created |

---

## Testing Multi-Tenancy

### Scenario 1: Scoped Queries

```php
public function test_codes_are_scoped_to_organisation()
{
    $org1 = Organisation::factory()->tenant()->create();
    $org2 = Organisation::factory()->tenant()->create();

    $code1 = Code::factory()
        ->for($org1)
        ->create();

    $code2 = Code::factory()
        ->for($org2)
        ->create();

    // Without tenant context, no codes found
    $this->assertEquals(0, Code::count());

    // With tenant context, only own code found
    session(['current_organisation_id' => $org1->id]);
    $this->assertEquals(1, Code::count());

    // Switch tenant
    session(['current_organisation_id' => $org2->id]);
    $this->assertEquals(1, Code::count());
}
```

### Scenario 2: Cross-Tenant Access Prevention

```php
public function test_user_cannot_access_other_org_codes()
{
    $org1 = Organisation::factory()->tenant()->create();
    $org2 = Organisation::factory()->tenant()->create();

    $code = Code::factory()->for($org1)->create();

    // Set context to org2
    session(['current_organisation_id' => $org2->id]);

    // Code should not be found
    $found = Code::find($code->id);
    $this->assertNull($found);

    // But can find with withoutGlobalScopes (test-only)
    $found = Code::withoutGlobalScopes()->find($code->id);
    $this->assertNotNull($found);
}
```

### Scenario 3: Per-Organisation Vote Limits

```php
public function test_vote_limits_per_organisation()
{
    config(['voting.max_votes_per_device' => 3]);

    $org1 = Organisation::factory()->create(['voting_settings' => ['max_votes_per_device' => 2]]);
    $org2 = Organisation::factory()->create();

    $deviceHash = 'test-device-123';

    // Create 2 codes for org1
    $this->createCodeWithDevice($deviceHash, Str::uuid(), $org1->id);
    $this->createCodeWithDevice($deviceHash, Str::uuid(), $org1->id);

    // Create 3 codes for org2
    $this->createCodeWithDevice($deviceHash, Str::uuid(), $org2->id);
    $this->createCodeWithDevice($deviceHash, Str::uuid(), $org2->id);
    $this->createCodeWithDevice($deviceHash, Str::uuid(), $org2->id);

    $service = new DeviceFingerprint();

    // Org1 limit: 2 (from override)
    $result1 = $service->canVote($deviceHash, Str::uuid(), $org1);
    $this->assertFalse($result1['allowed']);
    $this->assertEquals(2, $result1['max']);

    // Org2 limit: 3 (from config)
    $result2 = $service->canVote($deviceHash, Str::uuid(), $org2);
    $this->assertTrue($result2['allowed']);
    $this->assertEquals(3, $result2['max']);
}
```

---

## Testing Vote Anonymity

### Test 1: No Direct User-Vote Relationship

```php
public function test_vote_has_no_user_relationship()
{
    $vote = Vote::first();

    // This method should NOT exist
    $this->assertFalse(method_exists($vote, 'user'));

    // Query should NOT have user_id column
    $columns = DB::getSchemaBuilder()->getColumnListing('votes');
    $this->assertNotContains('user_id', $columns);
}
```

### Test 2: Votes Verified via VoterSlug Only

```php
public function test_vote_verification_chain_is_anonymous()
{
    $voterSlug = VoterSlug::factory()->create();
    $vote = Vote::factory()->for($voterSlug)->create();

    // ✅ Can verify vote came from voter_slug
    $this->assertEquals($voterSlug->id, $vote->voter_slug_id);

    // ❌ Cannot determine which user cast vote
    $this->assertNull($vote->user_id ?? null);
    $this->assertFalse(method_exists($vote, 'user'));
}
```

### Test 3: Results Don't Expose Voter

```php
public function test_result_has_no_voter_information()
{
    $result = Result::first();

    // NO user linkage
    $this->assertFalse(method_exists($result, 'user'));
    $this->assertFalse(method_exists($result, 'voter'));

    // Result shows: which candidate got selected
    // Result does NOT show: who selected them
    $this->assertIsNotNull($result->candidacy_id);
    $this->assertNull($result->user_id ?? null);
}
```

---

## Testing Device Fingerprinting

### Test 1: Consistent Hash Generation

```php
public function test_consistent_hash_for_same_device()
{
    $request = new Request([], [], [], [], [], [
        'REMOTE_ADDR' => '192.168.1.1',
        'HTTP_USER_AGENT' => 'Mozilla/5.0...',
    ]);

    $service = new DeviceFingerprint();
    $hash1 = $service->generate($request);
    $hash2 = $service->generate($request);

    $this->assertEquals($hash1, $hash2);
}
```

### Test 2: Different Hash for Different Devices

```php
public function test_different_hash_for_different_devices()
{
    $request1 = new Request([], [], [], [], [], [
        'REMOTE_ADDR' => '192.168.1.1',
        'HTTP_USER_AGENT' => 'Mozilla/5.0...',
    ]);

    $request2 = new Request([], [], [], [], [], [
        'REMOTE_ADDR' => '192.168.1.2',  // Different IP
        'HTTP_USER_AGENT' => 'Mozilla/5.0...',
    ]);

    $service = new DeviceFingerprint();
    $hash1 = $service->generate($request1);
    $hash2 = $service->generate($request2);

    $this->assertNotEquals($hash1, $hash2);
}
```

### Test 3: Vote Limit Enforcement

```php
public function test_vote_limit_prevents_excessive_voting()
{
    config(['voting.max_votes_per_device' => 3]);

    $deviceHash = 'test-device';
    $electionId = Str::uuid();
    $orgId = Organisation::getDefaultPlatform()->id;

    // Create 3 codes (max)
    for ($i = 0; $i < 3; $i++) {
        $this->createCodeWithDevice($deviceHash, $electionId, $orgId);
    }

    $service = new DeviceFingerprint();

    // Can still vote
    $result = $service->canVote($deviceHash, $electionId);
    $this->assertTrue($result['allowed']);

    // But no votes left
    $this->assertEquals(0, $result['remaining']);

    // Create 4th code
    $this->createCodeWithDevice($deviceHash, $electionId, $orgId);

    // Now blocked
    $result = $service->canVote($deviceHash, $electionId);
    $this->assertFalse($result['allowed']);
}
```

### Test 4: Anomaly Detection

```php
public function test_anomaly_detection_on_rapid_codes()
{
    config([
        'voting.device_anomaly_threshold' => 5,
        'voting.device_time_window_minutes' => 15,
    ]);

    $deviceHash = 'test-device';
    $electionId = Str::uuid();
    $orgId = Organisation::getDefaultPlatform()->id;

    // Create 5 codes rapidly
    for ($i = 0; $i < 5; $i++) {
        $this->createCodeWithDevice($deviceHash, $electionId, $orgId);
    }

    $service = new DeviceFingerprint();
    $anomaly = $service->detectAnomaly($deviceHash, $electionId);

    $this->assertTrue($anomaly['detected']);
    $this->assertEquals(5, $anomaly['count']);
    $this->assertEquals(5, $anomaly['threshold']);
}
```

---

## Troubleshooting Common Test Failures

### Issue 1: "Unknown column" Error

```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'device_fingerprint_hash'
```

**Cause:** Migration not run before test

**Solution:**
```bash
php artisan migrate
php artisan test
```

### Issue 2: "Code not found" (Returns Null)

```
Expected Code instance, got null
```

**Cause:** Global scope filtering test record

**Solution:**
```php
// ❌ Wrong
$code = Code::find($testCodeId);

// ✅ Right
$code = Code::withoutGlobalScopes()->find($testCodeId);
```

### Issue 3: "NOT NULL constraint failed"

```
SQLSTATE: NOT NULL constraint failed: codes.organisation_id
```

**Cause:** Raw insert missing required column

**Solution:**
```php
// Add ALL NOT NULL columns
DB::table('codes')->insert([
    'id' => $codeId,
    'organisation_id' => $orgId,     // ← Add this
    'user_id' => $userId,            // ← Add this
    'election_id' => $electionId,    // ← Add this
    // ... rest of fields ...
]);
```

### Issue 4: "Factory Resolution Failed"

```
Illuminate\Database\Eloquent\Factories\FactoryNotFoundException
```

**Cause:** Factory dependency not properly scoped

**Solution:**
```php
// ❌ Wrong
Code::factory()->create();

// ✅ Right
Code::factory()
    ->for(User::factory()->forOrganisation($org))
    ->create(['organisation_id' => $org->id]);
```

### Issue 5: "Hash Mismatch" (Non-Deterministic)

```
Expected: 'a7f3e9c2d1b4f5a8e9c2d1b4f5a8...'
Got:      'f9e8d7c6b5a4f3e2d1c0b9a8f7e6d...'
```

**Cause:** Random salt or user agent in generation

**Solution:** Use fixed Request object in tests:

```php
// ✅ Fixed request object
$request = new Request([], [], [], [], [], [
    'REMOTE_ADDR' => '192.168.1.1',
    'HTTP_USER_AGENT' => 'Mozilla/5.0...',
    'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.9',
]);

// Generate twice
$hash1 = $service->generate($request);
$hash2 = $service->generate($request);

// Same hash both times
$this->assertEquals($hash1, $hash2);
```

---

## Running Tests

### Run All Tests

```bash
php artisan test
```

### Run Specific Test Class

```bash
php artisan test tests/Unit/Services/DeviceFingerprintTest.php
```

### Run Specific Test Method

```bash
php artisan test --filter=test_it_generates_consistent_hash_for_same_device
```

### Run with Coverage Report

```bash
php artisan test --coverage
```

### Run Without Stopping on Failure

```bash
php artisan test --no-stop-on-failure
```

---

## Best Practices

### ✅ DO

- Write tests FIRST (RED → GREEN → REFACTOR)
- Use descriptive test names (what_behavior_produces_what_result)
- Test one thing per test
- Use fixtures and factories for consistent data
- Mock external services
- Verify both success and failure paths
- Keep tests isolated (no dependencies between tests)

### ❌ DON'T

- Skip assertions
- Use sleep() in tests
- Create tests that depend on other tests
- Test implementation details (test behavior)
- Over-mock (mock only external dependencies)
- Commit failing tests
- Test multiple concepts in one test

---

## Assertion Reference

```php
// Equality
$this->assertEquals($expected, $actual);
$this->assertNotEquals($expected, $actual);

// Boolean
$this->assertTrue($condition);
$this->assertFalse($condition);

// Collections
$this->assertContains($needle, $haystack);
$this->assertNotContains($needle, $haystack);
$this->assertCount($count, $array);

// Objects
$this->assertNull($value);
$this->assertNotNull($value);
$this->assertInstanceOf(Class::class, $object);

// Arrays/Objects
$this->assertArrayHasKey($key, $array);
$this->assertObjectHasAttribute($attribute, $object);

// Strings
$this->assertStringContainsString($needle, $haystack);
$this->assertStringNotContainsString($needle, $haystack);
```

---

**Reference:** See `INDEX.md` for complete documentation
