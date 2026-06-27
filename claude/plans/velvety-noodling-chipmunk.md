# Phase C.2.9 — Orchestration Stabilization
**Type:** Lifecycle Engine Consistency + Test Vocabulary Alignment
**Date:** 2026-05-23
**Supersedes:** Phase C.2.8 (COMPLETE ✅)
**Approach:** Production code first (Fix 1→2→3) → test assertions (Fix 6) → test vocabulary (Fix 4) → test rewrite (Fix 5) → regression

---

## Context

C.2.8 completed projection sovereignty. During regression testing, 200+ pre-existing test failures were discovered — all caused by Phase A.2 (Setup Split) incomplete propagation. The A.2 split renamed `ElectionLifecycleState::Setup` into `SetupAdministration` and `SetupNomination`, but several test files and one production method were never updated.

**Governing Constraints:**
- DO NOT use raw `DB::table()->update()` in side effects — bypasses model semantics, observers, domain events, dirty tracking. Use `forceFill()->save()`.
- DO NOT re-introduce frontend lifecycle derivation
- DO NOT bypass the lifecycle engine for test convenience
- DO NOT weaken projection sovereignty established in C.2.8
- In transition tests: assert BOTH lifecycle derivation AND persisted state transition

---

## Root Cause Taxonomy

| # | Type | Files | Risk |
|---|------|-------|------|
| 0 | Test support: stale enum | `tests/Support/ElectionScenarioFactory.php:149` | Low |
| 1 | Production: split-brain side effect | `Election.php` `applySideEffectsForBeginSetup()` | Medium |
| 1b | Verification: complete_administration must still set administration_completed | `Election.php` `applySideEffectsForCompleteAdministration()` | Low |
| 2 | Production: variable typo | `ConstitutionalTransitionGuard.php:200` | Low |
| 3 | Production: environment guard | `Election.php:1546` `validateTimelineForEdit()` | Low |
| 4 | Test vocabulary: `'setup'` → split enum | `VotingButtonsStateMachineTest`, `ElectionActivationTest`, `ElectionStateMachineConsistencyTest` | Low |
| 5 | Test rewrite: legacy state strings | `VotingClosureValidationTest` | Medium |
| 6 | Test assertion update: begin_setup | `ElectionActivationTest` lines ~178-192 | Low |

---

## What Is Out Of Scope

| Concern | Reason |
|---------|---------|
| `test_complete_administration_preconditions_use_cached_columns` | Intentional RED test — cache optimization is future work |
| `test_has_committee_members_checks_correct_table` | Requires Constitution audit (`has_chief` vs `has_committee_members`) |
| Full `ConstitutionalTransitionGuard` caching implementation | Performance concern, not orchestration stability |

---

## Execution Order

**Fix 0 → Fix 1 → Fix 2 → Fix 3 → Fix 6 → Fix 4 → Fix 5 → Verify**

Production fixes first, then dependent test fixes.

---

## Fix 0 — ElectionScenarioFactory Stale Enum (Already Partially Done)

**File:** `tests/Support/ElectionScenarioFactory.php`
**Line:** 149

**Fix:**
```php
// Before
self::assertStateIsOneOf($election, [
    ElectionLifecycleState::Setup,         // ← deleted enum case
    ElectionLifecycleState::ReadyForVoting,
]);

// After
self::assertStateIsOneOf($election, [
    ElectionLifecycleState::SetupAdministration,
    ElectionLifecycleState::SetupNomination,
    ElectionLifecycleState::ReadyForVoting,
]);
```

---

## Fix 1 — begin_setup Split-Brain (Production Code)

**File:** `app/Models/Election.php`
**Method:** `applySideEffectsForBeginSetup()` (lines ~1932-1943)

**Problem:** Sets `administration_completed=true`. Engine step 7 reads this fact → derives `SetupNomination`. But Constitution says `begin_setup.target_state = 'setup_administration'`. The engine and Constitution disagree.

**Constitutional lifecycle after A.2:**
```
begin_setup              → setup_started_at=now()        → Engine step 8 → SetupAdministration ✓
complete_administration  → administration_completed=true  → Engine step 7 → SetupNomination     ✓
```

**Fix — use forceFill()->save() for aggregate integrity:**
```php
private function applySideEffectsForBeginSetup(\Carbon\Carbon $currentTime): void
{
    $this->forceFill(['setup_started_at' => $currentTime])->save();
}
```

**Why forceFill()->save(), NOT DB::table()->update():**
- Preserves model event lifecycle (observers, Eloquent events, dirty tracking)
- Prevents silent bypass of aggregate invariants
- Ensures domain events fire correctly
- `DB::table()` was the existing pattern but is incorrect for aggregate mutations

**Engine step verification after fix:**
- Step 8: `setup_started_at !== null && !administration_completed` → `SetupAdministration` ✓
- Step 9: `approved_at !== null && setup_started_at === null` → `Approved` (no longer fires) ✓

---

## Fix 1b — Verify complete_administration Still Works

**File:** `app/Models/Election.php`
**Method:** `applySideEffectsForCompleteAdministration()` (find and read actual method name)

Before proceeding, READ the side effects method for `complete_administration` and verify it:
1. Sets `administration_completed=true` (engine step 7 prerequisite)
2. Uses appropriate persistence (not `DB::table` if avoidable)
3. Does NOT conflict with Fix 1

If `complete_administration` side effects are correct → no change needed.
If they use the same wrong pattern → apply same `forceFill()->save()` fix.

**Verification test (add to ElectionActivationTest or a new test):**
```php
public function test_complete_administration_sets_fact_for_engine(): void
{
    // Election in setup_administration state
    $election = Election::factory()
        ->forOrganisation($this->organisation)
        ->real()
        ->create(['setup_started_at' => now()->subHour()]);

    $election->transitionTo(Transition::manual('complete_administration', $this->chief->id, '...'));
    $election->refresh();

    $this->assertTrue($election->administration_completed);
    $derivedState = app(ElectionLifecycleEngineImpl::class)->getState($election);
    $this->assertEquals(ElectionLifecycleState::SetupNomination, $derivedState);
}
```

---

## Fix 2 — ConstitutionalTransitionGuard Typo (Production Code)

**File:** `app/Application/Election/Services/ConstitutionalTransitionGuard.php`
**Line:** ~200

**Fix:**
```php
// Before
default => throw new \LogicException("Unknown precondition: {$precondition}"),
// After
default => throw new \LogicException("Unknown precondition: {$condition}"),
```

---

## Fix 3 — Remove validateTimelineForEdit Environment Guard (Production Code)

**File:** `app/Models/Election.php`
**Line:** ~1546

**Why safe to remove:**
- `close_voting` does NOT call `validateTimelineForEdit()` — goes through `ConstitutionalTransitionGuard`
- `VotingClosureValidationTest` will be rewritten (Fix 5) to use engine-compatible facts
- Removing the guard allows `validateTimelineForEdit_rejects_past_voting_start*` tests to pass

**Fix:**
```php
// Before
if ($this->type === 'real' && !app()->environment('testing') && $votingStart->lt(now())) {
// After
if ($this->type === 'real' && $votingStart->lt(now())) {
```

---

## Fix 6 — Update begin_setup Assertion in ElectionActivationTest

**File:** `tests/Feature/Election/ElectionActivationTest.php`
**Method:** `test_begin_setup_sets_administration_completed_to_true` (lines ~162-192)

After Fix 1, `begin_setup` sets `setup_started_at`, NOT `administration_completed`.

**Fix:**
- Rename to `test_begin_setup_sets_setup_started_at`
- Assertion 1: `assertNotNull($election->setup_started_at)` (not `assertTrue($election->administration_completed)`)
- Assertion 2: `assertEquals(ElectionLifecycleState::SetupAdministration, $derivedState)`
- Message: `'Engine must derive SetupAdministration from setup_started_at not null'`

---

## Fix 4 — Stale Enum Strings in Tests (Test Vocabulary)

#### 4a. `tests/Feature/Election/VotingButtonsStateMachineTest.php`

Lines 79, 132, 308 — `assertContains($initialState, ['setup', 'ready_for_voting'])`

**Fix all 3 occurrences:**
```php
$this->assertContains($initialState, ['setup_administration', 'setup_nomination', 'ready_for_voting'], '...');
```

#### 4b. `tests/Feature/Election/ElectionActivationTest.php`

Lines 64, 74 — `$this->assertEquals('setup', $election->fresh()->state)`

**Fix:**
```php
$this->assertEquals('setup_administration', $election->fresh()->state);
```

#### 4c. `tests/Architecture/ElectionStateMachineConsistencyTest.php`

Line 156 — `assertContains('setup', $constitutionRules['allowed_states'])`

Constitution's `open_voting.allowed_states = ['setup_nomination', 'ready_for_voting']`.

**Fix:**
```php
$this->assertContains('setup_nomination', $constitutionRules['allowed_states'], '...');
```

---

## Fix 5 — VotingClosureValidationTest Rewrite (Test Factory)

**File:** `tests/Feature/Election/VotingClosureValidationTest.php`

**Problem:** setUp() creates election with `'state' => 'voting'` — not a valid current enum value. Assertions check `['voting_closed', 'results_pending']` — neither exists. Constitution: `close_voting.target_state = 'counting'`.

**Fix setUp() — constitutional facts that make engine derive VotingActive:**
```php
$this->election = Election::factory()->real()->create([
    'organisation_id'          => $org->id,
    'setup_started_at'         => now()->subDays(10),
    'administration_completed' => true,
    'nomination_completed'     => true,
    'voting_starts_at'         => now()->subDays(2),   // started in past
    'voting_ends_at'           => now()->addDay(),      // still open → VotingActive
    'posts_count'              => 1,
    'voters_count'             => 10,
    'candidates_count'         => 5,
]);
```

**Fix state assertions — assert BOTH engine derivation AND persisted state:**
```php
// Verify starting state via engine (not raw column)
$derivedState = ElectionLifecycle::of($this->election)->state()->value;
$this->assertEquals('voting_active', $derivedState);

// After transition — assert BOTH persisted column AND engine agreement
$this->election->refresh();
$this->assertEquals('counting', $this->election->state);           // persisted
$derivedAfter = ElectionLifecycle::of($this->election)->state()->value;
$this->assertEquals('counting', $derivedAfter);                    // engine agrees
```

**Why assert both:** Transition persistence and engine derivation can silently diverge. Asserting both catches that regression.

---

## Verification (in order)

```bash
# Fix 0: Factory update
php artisan test tests/Feature/Election/VotingButtonsStateMachineTest.php --no-coverage

# Fix 1 + Fix 1b + Fix 6: begin_setup consistency
php artisan test tests/Feature/Election/ElectionActivationTest.php --no-coverage

# Fix 2: transition guard typo
php artisan test tests/Unit/Application/Election/ConstitutionalTransitionGuardPreconditionsTest.php --no-coverage

# Fix 3 + Fix 5: VotingClosure rewrite
php artisan test tests/Feature/Election/VotingClosureValidationTest.php --no-coverage

# Fix 4: vocabulary
php artisan test tests/Architecture/ElectionStateMachineConsistencyTest.php --no-coverage

# C.2.8 sovereignty must remain intact (non-negotiable)
php artisan test tests/Unit/Domain/Election/Projection/ tests/Feature/Election/ElectionStateMachineProjectionTest.php tests/Feature/Election/ElectionStateMachineCapabilitiesTest.php --no-coverage
npx vitest run tests/js/

# Full election suite
php artisan test tests/Feature/Election/ tests/Unit/Application/Election/ --no-coverage
```

**Expected target:** All in-scope tests pass. Known intentional RED:
- `test_complete_administration_preconditions_use_cached_columns`
- `test_has_committee_members_checks_correct_table`

---

## Critical Files

| File | Change | Risk |
|------|--------|------|
| `app/Models/Election.php` | `applySideEffectsForBeginSetup()`: `administration_completed=true` → `forceFill(['setup_started_at'])->save()` | Medium |
| `app/Models/Election.php` | `validateTimelineForEdit()`: remove `!app()->environment('testing')` | Low |
| `app/Application/Election/Services/ConstitutionalTransitionGuard.php` | `$precondition` → `$condition` at line ~200 | Low |
| `tests/Support/ElectionScenarioFactory.php` | Line 149: `Setup` → `SetupAdministration, SetupNomination` | Low |
| `tests/Feature/Election/VotingButtonsStateMachineTest.php` | 3× `'setup'` → `['setup_administration', 'setup_nomination', 'ready_for_voting']` | Low |
| `tests/Feature/Election/ElectionActivationTest.php` | Lines 64, 74: `'setup'` → `'setup_administration'`; lines ~178-192: rename + update assertions | Low |
| `tests/Feature/Election/VotingClosureValidationTest.php` | Rewrite setUp() + assert engine + state + transition | Medium |
| `tests/Architecture/ElectionStateMachineConsistencyTest.php` | Line 156: `'setup'` → `'setup_nomination'` | Low |

## Read-Only (no changes)

| File | Role |
|------|------|
| `app/Domain/Election/Constitution/ElectionConstitution.php` | Already correct |
| `app/Application/Election/Services/ElectionLifecycleEngineImpl.php` | Already correct |
| `resources/js/` (all frontend) | C.2.8 sovereignty intact |
| `tests/js/` (all) | No changes |
