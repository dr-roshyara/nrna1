# Plan: Election State Transition Test Audit & Modernization

**Date**: 2026-05-31  
**Status**: Design Phase  
**Type**: Test Refactoring + Feature Coverage

---

## Context

The existing `ElectionTransitionToMethodTest.php` was written before the **ConstitutionalTransitionGuard** authorization layer and **ElectionConstitution** action definitions were implemented. The tests are now failing because:

1. **Authorization Gap**: Tests pass `'system'` as actor with no authenticated user or `ElectionOfficer` setup
   - `open_voting` requires `['chief']` role (not system)
   - `publish_results` requires `['chief']` role
   - Guard checks `ElectionOfficer` records for `chief`/`deputy` roles
   - Tests must authenticate users and create `ElectionOfficer::where('role', 'chief')`

2. **Precondition Gap**: Tests don't set up election state to meet preconditions
   - `open_voting` requires: `['voting_window_defined', 'timezone_set']`
   - Tests must set `voting_starts_at`, `voting_ends_at`, and `timezone` on election
   - Nomination phase must be marked as completed via precondition or transition

3. **PHPUnit Modernization**: Tests use deprecated `@test` doc comments
   - Must upgrade to `#[Test]` attributes (PHPUnit 10+)

4. **Event Property Names**: Event assertions use wrong property names
   - Event has `actorId` (not `actor_id`)
   - Tests assert `$eventData->actorId` but assertion code says `$eventData->actor_id`

5. **Missing Test Coverage**:
   - No tests for authorization failures (deputy trying chief-only action)
   - No tests for system-triggered transitions (`Transition::automatic()`)
   - No tests for precondition validation failures
   - No tests for election state validation (`nomination_completed` flag)

---

## Architecture

### State Machine Actions (from `ElectionConstitution`)
- **submit** → requires `['chief', 'deputy']` from `draft` state
- **approve** → requires `['system']` (automatic)
- **begin_setup** → requires `['chief', 'deputy']`
- **complete_administration** → requires `['chief', 'deputy']`, preconditions: `['has_posts', 'has_voters', 'has_chief']`
- **complete_nomination** → requires `['chief', 'deputy']`, preconditions: `['has_approved_candidates']`
- **open_voting** → requires `['chief']` ONLY, preconditions: `['voting_window_defined', 'timezone_set']`
- **close_voting** → requires `['chief', 'deputy']`
- **publish_results** → requires `['chief']` ONLY

### Role Checking (from `ConstitutionalTransitionGuard::userHasAnyRole()`)
1. Special case: If `'system'` is the ONLY required role and no user is authenticated → **allow**
2. If `'system'` is in required roles but there are others → check authenticated user has one of the non-system roles
3. Check Spatie permission roles via `$user->hasRole($role)`
4. Check election-specific `ElectionOfficer` records: `where('election_id', $election->id)->where('user_id', $user->id)->where('role', $role)->where('status', 'active')`

### Precondition Validation (from `ConstitutionalTransitionGuard::validatePreconditions()`)
- Delegates to `Election::whyCannotOpenVoting()`, etc. for domain-specific validation
- Must handle: timezone set, voting window defined, has_posts, has_voters, has_chief, has_approved_candidates

---

## Implementation Plan

### Phase 1: Test Modernization (TDD Red)
**Goal**: Make failing tests pass by fixing setup, not the code

#### 1.1 Create Improved `setUp()` Method
- Authenticate a test user with 'chief' role
- Create `ElectionOfficer` record with role='chief', status='active'
- Set election to state that allows transitions
- Set timezone on election
- Create posts, voters, candidates

```php
protected function setUp(): void
{
    parent::setUp();
    
    // 1. Create election in 'setup_nomination' state (ready for voting transition)
    $this->election = Election::factory()->demo()->create([
        'state' => 'setup_nomination',
        'timezone' => 'UTC',
        'voting_starts_at' => now()->addDay(),
        'voting_ends_at' => now()->addDay()->addHours(2),
        'administration_completed' => true,
        'nomination_completed' => true,
        'posts_count' => 1,
        'voters_count' => 1,
        'candidates_count' => 5,
    ]);
    
    // 2. Create and authenticate user
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    // 3. Create ElectionOfficer with 'chief' role
    ElectionOfficer::create([
        'election_id' => $this->election->id,
        'user_id' => $this->user->id,
        'role' => 'chief',
        'status' => 'active',
    ]);
}
```

#### 1.2 Upgrade Test Syntax to Attributes
Replace all `/** @test */` with `#[Test]` attribute

#### 1.3 Fix Event Property Assertions
Change `$eventData->actor_id` → `$eventData->actorId`

### Phase 2: Test Coverage Expansion (TDD Red → Green)

#### 2.1 System-Triggered Transitions
Add test for `Transition::automatic()` which doesn't require authentication
- Should allow 'system' role transitions without user
- Example: auto_submit action (if it exists)

#### 2.2 Authorization Failure Scenarios
```php
/** Test deputy cannot perform chief-only actions */
#[Test]
public function deputy_cannot_open_voting()
{
    // Change officer role to 'deputy'
    ElectionOfficer::where('election_id', $this->election->id)->update(['role' => 'deputy']);
    
    $this->expectException(InvalidTransitionException::class);
    $this->expectExceptionMessage("Required role(s): chief");
    
    $this->election->transitionTo(
        Transition::manual('open_voting', $this->user->id)
    );
}

/** Test unauthenticated user cannot perform user-role actions */
#[Test]
public function unauthenticated_user_cannot_open_voting()
{
    Auth::logout();
    
    $this->expectException(InvalidTransitionException::class);
    
    $this->election->transitionTo(
        Transition::manual('open_voting', 'some-actor-id')
    );
}
```

#### 2.3 Precondition Validation
```php
/** Test missing voting window rejects transition */
#[Test]
public function missing_voting_window_rejects_open_voting()
{
    $this->election->update([
        'voting_starts_at' => null,
        'voting_ends_at' => null,
    ]);
    
    $this->expectException(DomainException::class);
    $this->expectExceptionMessage('voting window'); // Check actual message
    
    $this->election->transitionTo(
        Transition::manual('open_voting', $this->user->id)
    );
}

/** Test missing timezone rejects transition */
#[Test]
public function missing_timezone_rejects_open_voting()
{
    $this->election->update(['timezone' => null]);
    
    $this->expectException(DomainException::class);
    
    $this->election->transitionTo(
        Transition::manual('open_voting', $this->user->id)
    );
}
```

#### 2.4 State Machine Validation
```php
/** Test cannot open voting twice */
#[Test]
public function cannot_open_voting_twice()
{
    // First transition succeeds
    $this->election->transitionTo(
        Transition::manual('open_voting', $this->user->id)
    );
    
    // Reload to get new state
    $this->election->refresh();
    
    // Second attempt fails (state is now 'voting_active', not 'setup_nomination')
    $this->expectException(InvalidTransitionException::class);
    $this->expectExceptionMessage("not allowed in state 'voting_active'");
    
    $this->election->transitionTo(
        Transition::manual('open_voting', $this->user->id)
    );
}
```

#### 2.5 Audit Trail Completeness
```php
/** Test all transition fields are recorded */
#[Test]
public function transition_records_all_fields()
{
    $transition = $this->election->transitionTo(
        Transition::manual('open_voting', $this->user->id, 'Testing voting window')
    );
    
    $this->assertDatabaseHas('election_state_transitions', [
        'election_id' => $this->election->id,
        'from_state' => 'setup_nomination',
        'to_state' => 'voting_active',
        'trigger' => 'manual',
        'actor_id' => $this->user->id,
        'reason' => 'Testing voting window',
    ]);
}
```

### Phase 3: Cleanup & Validation

#### 3.1 Remove Obsolete Tests
- If any tests are testing deprecated behaviors, mark for removal
- Example: tests that expected 'system' actor to work without authentication for 'chief'-only actions

#### 3.2 Test Documentation
Add comment block to class:
```php
/**
 * TDD: Test Election::transitionTo() with ConstitutionalTransitionGuard authorization
 * 
 * Verifies:
 * 1. Role-based authorization (chief, deputy, system)
 * 2. State machine transitions and invalid state detection
 * 3. Precondition validation (voting_window, timezone, has_posts, etc.)
 * 4. Audit trail creation
 * 5. Event dispatching with correct properties
 * 6. Cache lock concurrency control
 * 7. Transaction rollback on failure
 */
```

---

## Files to Modify

1. **`tests/Feature/Election/ElectionTransitionToMethodTest.php`** (primary)
   - Rewrite `setUp()` to authenticate user + create ElectionOfficer
   - Upgrade all `@test` to `#[Test]` attributes
   - Fix event assertions (`actor_id` → `actorId`)
   - Add new test methods for authorization, preconditions, state machine

2. **Reference files** (read-only for understanding):
   - `app/Application/Election/Services/ConstitutionalTransitionGuard.php` → Authorization logic
   - `app/Domain/Election/Constitution/ElectionConstitution.php` → Action definitions, roles, preconditions
   - `app/Models/Election.php` → transitionTo() method, validation methods
   - `app/Domain/Election/StateMachine/Transition.php` → Transition::manual() factory
   - `app/Events/ElectionStateChangedEvent.php` → Event property names
   - `app/Models/ElectionOfficer.php` → Officer roles and statuses

---

## Execution Strategy (TDD: Red → Green → Refactor)

1. **RED**: Run tests → see all failures related to authorization and preconditions
2. **GREEN**: 
   - Fix test setup (authenticate + create officer)
   - Update event assertions (property names)
   - Add precondition setup in setUp()
3. **REFACTOR**:
   - Organize test methods into logical sections (existing sections can stay)
   - Add authorization test section
   - Add precondition test section
   - Modernize PHPUnit syntax

---

## Verification

```bash
# Run all tests
php artisan test tests/Feature/Election/ElectionTransitionToMethodTest.php --env=testing

# Expected: All tests pass, covering:
✓ Basic transition functionality (with proper setup)
✓ Authorization scenarios (chief vs deputy vs system)
✓ Precondition validation
✓ State machine validation
✓ Audit trail creation
✓ Event dispatching
✓ Transaction isolation
✓ Cache lock concurrency
```

---

## Risk Assessment

**Low Risk**: Tests are isolated unit tests with no external dependencies
- Use `RefreshDatabase` → clean state between tests
- Authenticate within test scope
- No real elections created (all demo mode)

**Key Assumptions**:
- `ElectionOfficer` model exists and works as expected
- `Transition::manual()` and `Transition::automatic()` factories work as documented
- `ConstitutionalTransitionGuard` is the authorizer (not Laravel policy)
- Event property names are definitely `actorId` (not `actor_id`)
