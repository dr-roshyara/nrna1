# Implementation Instructions: Election SSOT Refactoring Plan

**For:** Claude Code (Next Session)  
**Status:** Ready for Execution  
**Start Date:** 2026-05-18  

---

## 📌 Context (Read This First)

The election system currently has **three competing lifecycle systems** creating non-deterministic behavior:
1. State machine (`state = 'draft'...`)
2. Status system (`status = 'active'`)
3. Flag system (`is_active = true` by default)

**Problem:** Election can be `state='draft' && status='active' && is_active=true` simultaneously.

**Solution:** Introduce `ElectionLifecycleEngine` as the single authoritative truth source.

**Read these before starting:**
- `architecture/election/election_state_machine/20260518_2300_SSOT_Architecture_Analysis.md`
- `claude/plans/20260518-1430-SSOT-Refactoring-Plan.md`

---

## 🎯 Your Task (Phase 1: Introduce SSOT Layer)

Implement the foundation that will replace all three parallel lifecycle systems with one clean source of truth.

### Why Phase 1 is Critical

- Establishes the canonical truth layer
- All other phases depend on Phase 1 being correct
- This is where the architectural shift happens
- After Phase 1, rest of refactoring is "just" replacing consumers

---

## 📋 Phase 1: Implementation Checklist

### Task 1.1: Create ElectionLifecycleState Enum

**File:** `app/Domain/Election/Enum/ElectionLifecycleState.php`

**TDD Approach:**
1. Write RED test first (test file below)
2. Implement enum to pass test
3. Verify test passes

**Test File:** `tests/Unit/Domain/Election/ElectionLifecycleStateTest.php`

```php
#[\PHPUnit\Framework\Attributes\Test]
public function enum_has_seven_cases(): void
{
    $cases = ElectionLifecycleState::cases();
    $this->assertCount(7, $cases);
}

#[\PHPUnit\Framework\Attributes\Test]
public function enum_values_are_correct(): void
{
    $this->assertEquals('draft', ElectionLifecycleState::Draft->value);
    $this->assertEquals('setup', ElectionLifecycleState::Setup->value);
    $this->assertEquals('ready_for_voting', ElectionLifecycleState::ReadyForVoting->value);
    $this->assertEquals('voting_active', ElectionLifecycleState::VotingActive->value);
    $this->assertEquals('counting', ElectionLifecycleState::Counting->value);
    $this->assertEquals('results_published', ElectionLifecycleState::ResultsPublished->value);
    $this->assertEquals('archived', ElectionLifecycleState::Archived->value);
}

#[\PHPUnit\Framework\Attributes\Test]
public function label_returns_human_readable_string(): void
{
    $this->assertEquals('Draft Setup', ElectionLifecycleState::Draft->label());
    $this->assertEquals('Voting Active', ElectionLifecycleState::VotingActive->label());
}
```

**Implementation:** Use the enum structure from SSOT_Architecture_Analysis.md section 1.1

---

### Task 1.2: Create ElectionLifecycleSnapshot DTO

**File:** `app/Domain/Election/ValueObjects/ElectionLifecycleSnapshot.php`

**Test File:** `tests/Unit/Domain/Election/ElectionLifecycleSnapshotTest.php`

```php
#[\PHPUnit\Framework\Attributes\Test]
public function snapshot_is_immutable(): void
{
    $snapshot = new ElectionLifecycleSnapshot(
        state: ElectionLifecycleState::VotingActive,
        canEdit: false,
        canVote: true,
        canManageVoters: false,
        canPublishResults: false,
        isLocked: true,
        blockedReason: null,
        allowedActions: ['close_voting'],
    );
    
    // Verify readonly properties cannot be modified
    $this->expectError(\Error::class);
    $snapshot->canVote = false;
}

#[\PHPUnit\Framework\Attributes\Test]
public function is_active_returns_true_only_for_voting_active_state(): void
{
    $activeSnapshot = new ElectionLifecycleSnapshot(
        state: ElectionLifecycleState::VotingActive,
        ...$this->baseProps()
    );
    
    $inactiveSnapshot = new ElectionLifecycleSnapshot(
        state: ElectionLifecycleState::Draft,
        ...$this->baseProps()
    );
    
    $this->assertTrue($activeSnapshot->isActive());
    $this->assertFalse($inactiveSnapshot->isActive());
}

#[\PHPUnit\Framework\Attributes\Test]
public function can_transition_to_checks_allowed_actions(): void
{
    $snapshot = new ElectionLifecycleSnapshot(
        state: ElectionLifecycleState::VotingActive,
        allowedActions: ['close_voting', 'pause'],
        ...$this->baseProps()
    );
    
    $this->assertTrue($snapshot->canTransitionTo('close_voting'));
    $this->assertFalse($snapshot->canTransitionTo('open_voting'));
}
```

**Implementation:** Use final readonly class from SSOT_Architecture_Analysis.md section 1.2

---

### Task 1.3: Create ElectionLifecycleEngine Interface

**File:** `app/Domain/Election/Services/ElectionLifecycleEngine.php`

**Test File:** `tests/Unit/Domain/Election/ElectionLifecycleEngineTest.php` (interface shape only)

```php
#[\PHPUnit\Framework\Attributes\Test]
public function interface_defines_compute_method(): void
{
    // Verify interface can be instantiated (when implementation exists)
    $engine = new ElectionLifecycleEngineImpl(...);
    $this->assertInstanceOf(ElectionLifecycleEngine::class, $engine);
}
```

**Implementation:** Use interface from SSOT_Architecture_Analysis.md section 1.3

---

### Task 1.4: Create ElectionLifecycleEngineImpl

**File:** `app/Application/Election/Services/ElectionLifecycleEngineImpl.php`

**This is the CORE of SSOT. Critical implementation details:**

**Test File:** `tests/Unit/Application/Election/ElectionLifecycleEngineImplTest.php`

**RED Tests (write all before implementation):**

```php
#[\PHPUnit\Framework\Attributes\Test]
public function derives_draft_state_for_new_election(): void
{
    $election = Election::factory()->create(['state' => 'draft']);
    
    $snapshot = $this->engine->compute($election);
    
    $this->assertEquals(ElectionLifecycleState::Draft, $snapshot->state);
}

#[\PHPUnit\Framework\Attributes\Test]
public function derives_setup_state_when_election_has_started_administration(): void
{
    $election = Election::factory()
        ->inAdministrationState()
        ->create(['administration_completed' => false]);
    
    $snapshot = $this->engine->compute($election);
    
    $this->assertEquals(ElectionLifecycleState::Setup, $snapshot->state);
}

#[\PHPUnit\Framework\Attributes\Test]
public function derives_ready_for_voting_when_setup_complete(): void
{
    $election = Election::factory()
        ->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'candidates_count' => 5,  // Has approved candidates
        ]);
    
    $snapshot = $this->engine->compute($election);
    
    $this->assertEquals(ElectionLifecycleState::ReadyForVoting, $snapshot->state);
}

#[\PHPUnit\Framework\Attributes\Test]
public function derives_voting_active_when_current_time_in_voting_window(): void
{
    $election = Election::factory()
        ->create([
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);
    
    $snapshot = $this->engine->compute($election);
    
    $this->assertEquals(ElectionLifecycleState::VotingActive, $snapshot->state);
}

#[\PHPUnit\Framework\Attributes\Test]
public function derives_voting_active_uses_organisation_timezone(): void
{
    // Create org with specific timezone
    $org = Organisation::factory()->create(['timezone' => 'Asia/Kathmandu']);
    
    // Election with voting window in that timezone
    $election = Election::factory()
        ->forOrganisation($org)
        ->create([
            'voting_starts_at' => now('Asia/Kathmandu')->subHour(),
            'voting_ends_at' => now('Asia/Kathmandu')->addHour(),
        ]);
    
    $snapshot = $this->engine->compute($election);
    
    $this->assertEquals(ElectionLifecycleState::VotingActive, $snapshot->state);
}

#[\PHPUnit\Framework\Attributes\Test]
public function derives_counting_when_voting_ended_results_not_published(): void
{
    $election = Election::factory()
        ->create([
            'voting_ends_at' => now()->subMinutes(30),
            'results_published_at' => null,
        ]);
    
    $snapshot = $this->engine->compute($election);
    
    $this->assertEquals(ElectionLifecycleState::Counting, $snapshot->state);
}

#[\PHPUnit\Framework\Attributes\Test]
public function derives_results_published_when_results_published_at_set(): void
{
    $election = Election::factory()
        ->inResultsState()
        ->create(['results_published_at' => now()]);
    
    $snapshot = $this->engine->compute($election);
    
    $this->assertEquals(ElectionLifecycleState::ResultsPublished, $snapshot->state);
}

// PERMISSION TESTS

#[\PHPUnit\Framework\Attributes\Test]
public function can_edit_true_only_in_draft_and_setup(): void
{
    $draftElection = Election::factory()->create(['state' => 'draft']);
    $setupElection = Election::factory()->inAdministrationState()->create();
    $votingElection = Election::factory()->inVotingState()->create();
    
    $this->assertTrue($this->engine->compute($draftElection)->canEdit);
    $this->assertTrue($this->engine->compute($setupElection)->canEdit);
    $this->assertFalse($this->engine->compute($votingElection)->canEdit);
}

#[\PHPUnit\Framework\Attributes\Test]
public function can_vote_true_only_in_voting_active(): void
{
    $votingElection = Election::factory()
        ->create([
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);
    
    $draftElection = Election::factory()->create(['state' => 'draft']);
    
    $this->assertTrue($this->engine->compute($votingElection)->canVote);
    $this->assertFalse($this->engine->compute($draftElection)->canVote);
}

#[\PHPUnit\Framework\Attributes\Test]
public function blocked_reason_explains_why_action_not_allowed(): void
{
    $election = Election::factory()->create(['state' => 'draft']);
    
    $snapshot = $this->engine->compute($election);
    
    $this->assertFalse($snapshot->canVote);
    $this->assertNotNull($snapshot->blockedReason);
    $this->assertStringContainsString('setup', strtolower($snapshot->blockedReason));
}

// ALLOWED ACTIONS TESTS

#[\PHPUnit\Framework\Attributes\Test]
public function allowed_actions_empty_when_archived(): void
{
    $election = Election::factory()->create(['state' => 'results']);
    
    $snapshot = $this->engine->compute($election);
    
    $this->assertEmpty($snapshot->allowedActions);
}

#[\PHPUnit\Framework\Attributes\Test]
public function allowed_actions_includes_valid_transitions(): void
{
    $setupElection = Election::factory()->inAdministrationState()->create();
    
    $snapshot = $this->engine->compute($setupElection);
    
    $this->assertContains('add_voters', $snapshot->allowedActions);
    $this->assertContains('add_posts', $snapshot->allowedActions);
}

// TRANSITION VALIDATION TESTS

#[\PHPUnit\Framework\Attributes\Test]
public function assert_can_transition_throws_for_invalid_action(): void
{
    $election = Election::factory()->create(['state' => 'draft']);
    
    $this->expectException(InvalidTransitionException::class);
    $this->engine->assertCanTransition($election, 'close_voting');
}

#[\PHPUnit\Framework\Attributes\Test]
public function assert_can_transition_passes_for_valid_action(): void
{
    $election = Election::factory()->inAdministrationState()->create();
    
    // Should not throw
    $this->engine->assertCanTransition($election, 'add_voters');
}
```

**Implementation Notes:**

1. **State Derivation Order is CRITICAL:**
   - Terminal states first (results published)
   - Time windows next (voting active window)
   - Counting state (voting ended)
   - Setup complete (ready for voting)
   - Default to Setup

2. **Use ElectionTimezoneResolver for Time Checks:**
   ```php
   $tz = $this->timezoneResolver->resolve($election);
   $now = now()->tz($tz);
   ```

3. **Permissions are Pure Branching:**
   ```php
   private function canVote(Election $election): bool
   {
       return $this->deriveState($election) === ElectionLifecycleState::VotingActive;
   }
   ```

4. **Allowed Actions Tied to State:**
   ```php
   private function getAllowedActions(ElectionLifecycleState $state): array
   {
       return match($state) {
           ElectionLifecycleState::Draft => ['submit_for_approval', 'delete'],
           ElectionLifecycleState::Setup => ['add_voters', 'add_posts', 'complete_administration'],
           // ... etc
       };
   }
   ```

5. **Use Existing ElectionTimezoneResolver:**
   - Check if `app/Infrastructure/Election/ElectionTimezoneResolver.php` exists
   - If not, create it (see SSOT plan Phase 6)
   - For now, assume organisation.timezone is set

---

### Task 1.5: Register Engine in Service Provider

**File:** `app/Providers/AppServiceProvider.php`

Add to `register()` method:

```php
$this->app->bind(
    \App\Domain\Election\Services\ElectionLifecycleEngine::class,
    \App\Application\Election\Services\ElectionLifecycleEngineImpl::class
);
```

---

### Task 1.6: Add inResultsState() Factory Method

**File:** `database/factories/ElectionFactory.php`

Add method:

```php
public function inResultsState()
{
    return $this->state(function (array $attributes) {
        return [
            'state' => 'results',
            'voting_locked' => true,
            'results_locked' => true,
            'results_published_at' => now(),
            'results_published_by' => fake()->uuid(),
        ];
    });
}
```

---

## ✅ Phase 1: Definition of Done

Before moving to Phase 2, verify:

```bash
# All RED tests written and now GREEN
php artisan test tests/Unit/Domain/Election/ElectionLifecycleStateTest.php
php artisan test tests/Unit/Domain/Election/ElectionLifecycleSnapshotTest.php
php artisan test tests/Unit/Application/Election/ElectionLifecycleEngineImplTest.php

# Phase 0 safety net still passing
php artisan test tests/Feature/Election/StateMachine/CurrentBehaviorTest.php

# Full test suite GREEN (zero regressions)
php artisan test --no-coverage
```

**Checklist:**
- [ ] ElectionLifecycleState enum created and tests GREEN
- [ ] ElectionLifecycleSnapshot DTO created and tests GREEN
- [ ] ElectionLifecycleEngine interface created
- [ ] ElectionLifecycleEngineImpl created with all state derivation logic
- [ ] Engine registered in AppServiceProvider
- [ ] inResultsState() factory method added
- [ ] All Phase 0 tests still GREEN
- [ ] Full test suite GREEN with zero regressions

---

## 🚨 Critical Rules While Implementing

1. **TDD-First Always:**
   - Write RED test first
   - Watch it fail
   - Implement minimum code to pass
   - Do not implement beyond what tests require

2. **Keep Phase 0 Tests Green:**
   - Never modify Phase 0 tests
   - If Phase 0 test fails, fix your code, not the test

3. **No Database Changes Yet:**
   - Phase 1 is pure domain/application code
   - No migrations
   - No model changes
   - (ElectionTimezoneResolver may not exist yet — that's Phase 6)

4. **State Derivation is Deterministic:**
   - Same election data → same state ALWAYS
   - Test timezone handling explicitly
   - Test time window boundaries

5. **Immutable Snapshot:**
   - ElectionLifecycleSnapshot must be readonly
   - No setters
   - Immutability is critical for SSOT

---

## 🎓 Key Concepts

### What is a "Snapshot"?

A snapshot is an immutable DTO that represents the current truth about an election's lifecycle at one moment in time. It's computed fresh each time because time and business rules change.

```php
// Not this (mutable):
$election->isActive = true;
$election->save();

// This (immutable snapshot):
$snapshot = $engine->compute($election);
if ($snapshot->isActive()) { ... }
```

### Why "deriveState()" is the Core

The `deriveState()` method is where the system COMPUTES truth from multiple signals. It's the constitutional heart of SSOT.

```php
private function deriveState(Election $election): ElectionLifecycleState
{
    // Signals: state column, timestamps, flags
    // Returns: THE TRUTH
}
```

### Why Permissions Branch on State

Once you have state, permissions are simple:

```php
canVote = (state == VotingActive)
canEdit = (state == Draft || state == Setup)
```

No scattered logic. No "but also check this flag."

---

## 🔗 Reference Files to Keep Open

While implementing:

1. **SSOT_Architecture_Analysis.md** — for design details
2. **SSOT-Refactoring-Plan.md** — for phase overview
3. **Current Election Model** — understand existing columns
4. **TransitionMatrix** — understand state names

---

## ❓ If You Get Stuck

### "What columns does Election have?"

Read the existing migration in `database/migrations/2026_03_05_000004_create_uuid_elections_table.php`

### "How do I test with timezone?"

```php
// Create org with timezone
$org = Organisation::factory()->create(['timezone' => 'Asia/Kathmandu']);

// Use it in election
$election = Election::factory()
    ->forOrganisation($org)
    ->create(['voting_starts_at' => ...]);
```

### "What if ElectionTimezoneResolver doesn't exist?"

It won't yet. That's Phase 6. For Phase 1, just use:

```php
$tz = $election->organisation->timezone ?? 'UTC';
$now = now()->tz($tz);
```

### "Should I remove status/is_active columns?"

NO. Phase 1 is INTRODUCTION only. Phase 4 is removal. Safe separation.

---

## 📝 After Phase 1: Next Step

Once Phase 1 is complete and GREEN:

1. Commit your work with message:
   ```
   feat: Phase 1 - Introduce SSOT Layer (ElectionLifecycleEngine)
   
   - Add ElectionLifecycleState enum (7 cases)
   - Add ElectionLifecycleSnapshot DTO (immutable read model)
   - Add ElectionLifecycleEngine interface + impl
   - Implement state derivation logic with timezone support
   - Register engine in service provider
   - Add inResultsState() factory method
   - All tests GREEN, zero regressions
   
   See: claude/plans/20260518-1430-SSOT-Refactoring-Plan.md
   ```

2. Then proceed to Phase 2 (Mark deprecated) — but that's another session

---

**Ready to start Phase 1. Use TDD-First approach. Write RED tests before production code.**
