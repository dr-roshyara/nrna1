# Testing Guide

## Test Files

### VotingButtonsStateMachineTest.php
Location: tests/Feature/Election/VotingButtonsStateMachineTest.php
Tests: 10 assertions covering model and controller integration

### ElectionStateMachineTest.php
Location: tests/Feature/ElectionStateMachineTest.php
Tests: 25 existing tests (regression gate)

---

## Running Tests

### All Election Tests
```bash
php artisan test tests/Feature/Election/ --no-coverage
```

### Just Voting Buttons
```bash
php artisan test tests/Feature/Election/VotingButtonsStateMachineTest.php --no-coverage
```

### Just Regression (existing tests)
```bash
php artisan test tests/Feature/ElectionStateMachineTest.php --no-coverage
```

### With Filter
```bash
php artisan test tests/Feature/Election/ --filter "open_voting" --no-coverage
```

### Expected Output
```
Tests: 35 passed (98 assertions)
```

---

## Test Structure (TDD)

### 1. Model Tests (2)
Test Election::transitionTo() directly

#### Test 1: Creates Transition Record
```php
public function test_election_transition_to_voting_creates_transition_record(): void
{
    // Arrange
    $this->assertEquals('nomination', $this->election->current_state);
    $this->assertEquals(0, ElectionStateTransition::count());

    // Act
    $transition = $this->election->transitionTo('voting', 'manual', ...);

    // Assert
    $this->assertInstanceOf(ElectionStateTransition::class, $transition);
    $this->assertEquals('nomination', $transition->from_state);
    $this->assertEquals('voting', $transition->to_state);
}
```

#### Test 2: Locks Voting and Completes Nomination
```php
public function test_election_transition_to_voting_locks_voting_and_completes_nomination(): void
{
    // Arrange
    $this->assertFalse($this->election->nomination_completed);

    // Act
    $this->election->transitionTo('voting', 'manual', ...);
    $this->election->refresh();

    // Assert
    $this->assertTrue($this->election->voting_locked);
    $this->assertTrue($this->election->nomination_completed);
}
```

---

### 2. Controller Tests (8)
Test HTTP endpoints with proper authorization

#### Test 3: openVoting() Transitions State
```php
public function test_open_voting_transitions_from_nomination_to_voting(): void
{
    // Arrange: in nomination state
    $this->assertEquals('nomination', $this->election->current_state);

    // Act
    $response = $this->actingAs($this->officer)->post(
        route('elections.open-voting', ['election' => $this->election->slug])
    );

    // Assert
    $this->election->refresh();
    $this->assertEquals('voting', $this->election->current_state);
    $response->assertStatus(302);
    $response->assertSessionHas('success');
}
```

#### Test 4: Rejects Wrong State
```php
public function test_open_voting_rejects_if_not_in_nomination_state(): void
{
    // Arrange: in voting state
    $this->election->update([
        'voting_starts_at' => now()->subHour(),
        'voting_ends_at' => now()->addHour(),
    ]);

    // Act
    $response = $this->actingAs($this->officer)->post(
        route('elections.open-voting', ['election' => $this->election->slug])
    );

    // Assert
    $response->assertStatus(302);
    $response->assertSessionHas('error');
}
```

#### Test 5-8: Similar for closeVoting()
- Creates state transition record
- Locks voting immediately
- Rejects if not in voting state
- Prevents double-close when already locked

---

## Test Setup (setUp Method)

```php
protected function setUp(): void
{
    parent::setUp();

    // Create election in nomination phase
    $this->election = Election::factory()->create([
        'administration_completed' => true,
        'nomination_completed' => false,
    ]);

    // Create authorized officer
    $this->officer = User::factory()->create();
    ElectionOfficer::create([
        'organisation_id' => $this->election->organisation_id,
        'election_id' => $this->election->id,
        'user_id' => $this->officer->id,
        'role' => 'chief',
        'status' => 'active',
    ]);
}
```

---

## Key Test Patterns

### 1. Assert State Change
```php
$this->assertEquals('nomination', $this->election->current_state);
$this->election->transitionTo('voting', ...);
$this->election->refresh();
$this->assertEquals('voting', $this->election->current_state);
```

### 2. Assert Audit Record Created
```php
$this->assertEquals(0, ElectionStateTransition::count());
$this->election->transitionTo('voting', ...);
$this->assertEquals(1, ElectionStateTransition::count());

$transition = ElectionStateTransition::first();
$this->assertEquals('nomination', $transition->from_state);
$this->assertEquals('voting', $transition->to_state);
```

### 3. Assert Error Messages
```php
$response->assertSessionHas('error', 'Cannot open voting from "voting" phase...');
```

### 4. Assert Authorization
```php
// Without chief role: 403 Forbidden
$response = $this->actingAs($unauthorizedUser)->post(route(...));
$response->assertStatus(403);

// With chief role: 200 or 302
$response = $this->actingAs($this->officer)->post(route(...));
$response->assertStatus(302);
```

---

## Regression Testing

After any changes, verify:

```bash
php artisan test tests/Feature/ElectionStateMachineTest.php --no-coverage
```

Expected: 25 tests passing (unchanged from before)

---

## Adding New Tests

### Step 1: Write Failing Test (RED)
```php
public function test_my_new_feature(): void
{
    // Write test expecting new behavior
    // This will FAIL
}
```

### Step 2: Run Test
```bash
php artisan test tests/Feature/Election/VotingButtonsStateMachineTest.php --filter "my_new_feature"
```
Verify: Test fails with expected error

### Step 3: Write Minimal Code (GREEN)
Implement only what's needed to pass the test

### Step 4: Run Test
```bash
php artisan test tests/Feature/Election/VotingButtonsStateMachineTest.php --filter "my_new_feature"
```
Verify: Test passes

### Step 5: Run All Tests
```bash
php artisan test tests/Feature/Election/ --no-coverage
```
Verify: 35 tests passing (no regressions)

### Step 6: Refactor (Optional)
Clean up code while keeping tests green

---

## Common Test Failures

### "voting_locked is null"
**Cause:** Factory doesn't set default false
**Fix:** Remove assertFalse($election->voting_locked) or use assertNotNull()

### "Failed asserting that 403 is identical to 302"
**Cause:** Officer doesn't have ElectionOfficer record
**Fix:** Create ElectionOfficer in setUp() with 'chief' role

### "Cannot open voting from nomination phase"
**Cause:** Election not in nomination state in test
**Fix:** Check setUp() creates election with correct flags

---

**Status:** 35/35 Tests Passing ✅
