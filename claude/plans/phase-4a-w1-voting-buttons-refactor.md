# Phase 4A.1 Wave 1 — VotingButtonsStateMachineTest Architectural Refactoring

**Status:** IN PROGRESS (Factory updated, test architecture refactoring needed)
**Date:** 2026-05-20
**Scope:** Migrate VotingButtonsStateMachineTest from legacy mutable-state pattern to constitutional fact-driven pattern

---

## Current Situation

✅ **DONE:** ElectionFactory state values updated to SSOT equivalents:
- `'nomination'` → `'setup'`
- `'voting'` → `'voting_active'`
- `'results_pending'` → `'counting'`
- `'results'` → `'results_published'`
- `'pending_approval'` → `'submitted_for_approval'`
- `'administration'` → `'setup'`

❌ **BROKEN:** Test still uses legacy patterns:
- Direct state mutations: `$election->update(['state' => 'voting_active'])`
- No fact-driven setup
- No lifecycle derivation assertions
- Error message expectations mismatched with ConstitutionalTransitionGuard
- Undefined `approval_status` column issue (separate schema bug)

---

## Root Cause Analysis

The test file represents a **hybrid transitional state**:

| Pattern | Old (Legacy) | New (Constitutional) | Test Status |
|---------|--------------|----------------------|-------------|
| State setup | Direct mutation | Fact-driven | ❌ Mixed |
| State verification | `current_state` property | `ElectionLifecycle::of()` | ❌ Still using property |
| Error messages | Hardcoded expectations | Guard output | ❌ Mismatched |
| Lifecycle assertion | None | Verify facts → state | ❌ Missing |
| Authorization | Direct Gate::before | Constitutional guard | ⚠️ Bypassed |

---

## Phase 1 Refactoring (Bridge-Hardening)

### Step 1: Replace setUp() Factory Usage

**Current:**
```php
$this->election = Election::factory()->forOrganisation($this->org)->inNominationState()->create([...]);
```

**Problems:**
- `inNominationState()` is semantically misleading (creates 'setup', not 'nomination')
- No fact documentation
- No lifecycle verification

**New:**
```php
$this->election = ElectionScenarioFactory::configurationComplete($this->org);
```

**Why:**
- Explicitly sets ALL constitutional facts
- Self-documents expected state
- Includes assertDerivedState() verification
- Guaranteed valid SSOT starting point

---

### Step 2: Replace All Direct State Mutations

**Pattern (CURRENT):**
```php
$this->election->update([
    'state' => 'voting_active',
    'voting_starts_at' => now()->subHour(),
    'voting_ends_at' => now()->addHour(),
]);
```

**Problem:**
- Mutates persisted state directly
- Bypasses ConstitutionalTransitionGuard
- Recreates split-brain authority problem
- No fact-driven derivation

**New Pattern:**
```php
// Update facts only, let lifecycle derive state
$this->election->update([
    'administration_completed' => true,
    'nomination_completed' => true,
    'voting_starts_at' => now()->subHour(),
    'voting_ends_at' => now()->addHour(),
    'results_published_at' => null,
]);

// Verify derived state matches expectations
$this->assertEquals(
    'voting_active',
    ElectionLifecycle::of($this->election->fresh())->state()->value
);
```

**Why:**
- Facts are source of truth
- State is derived (not mutated)
- Matches constitutional model
- Self-verifying

---

### Step 3: Replace current_state Assertions

**Current:**
```php
$this->assertEquals('voting_active', $this->election->current_state);
```

**Problem:**
- `current_state` may read from DB column or derive from facts (unclear)
- Creates hidden dual truth
- Not semantically aligned with SSOT

**New:**
```php
$this->assertEquals(
    'voting_active',
    ElectionLifecycle::of($this->election->fresh())->state()->value
);
```

**Why:**
- Explicit: using lifecycle derivation
- Self-documenting
- Forces architectural alignment
- Clear about fact→state flow

---

### Step 4: Fix Error Message Assertions

**Current Expectation (BROKEN):**
```php
$response->assertSessionHas('error', "Action 'open_voting' is not allowed from state 'voting_active'. Allowed: close_voting");
```

**Actual Message (from ConstitutionalTransitionGuard):**
```
"Action 'open_voting' not allowed in state 'voting_active'. Allowed states: ready_for_voting"
```

**Fix Options:**

Option A: Update assertions to match actual guard output
```php
$response->assertSessionHas('error', "Action 'open_voting' not allowed in state 'voting_active'");
```

Option B: Check message contains expected parts (more resilient)
```php
$response->assertSessionHas('error');
$this->assertStringContainsString("'open_voting' not allowed", session('error'));
```

**Recommendation:** Option B (more maintainable)

---

### Step 5: Freeze Time for Consistency

**Add to setUp():**
```php
Carbon::setTestNow(now());
```

**Why:**
- Makes `now()` calls deterministic
- Prevents flaky timeline-based assertions
- Matches ElectionScenarioFactory expectations

---

## Refactoring Checklist

### Test: test_election_transition_to_voting_creates_transition_record()

- [ ] Replace setUp() factory with `ElectionScenarioFactory::configurationComplete()`
- [ ] No direct state mutation needed (scenario provides valid state)
- [ ] Verify derived state matches expected before calling transitionTo()
- [ ] Update error message assertions (if any)

### Test: test_election_transition_to_voting_locks_voting_and_completes_nomination()

- [ ] Use scenario factory
- [ ] Verify derived state
- [ ] Check transition record has correct from/to states (GOOD as-is)

### Test: test_open_voting_transitions_from_nomination_to_voting()

- [ ] Use scenario factory (setUp provides valid starting state)
- [ ] Replace current_state assertion with ElectionLifecycle::of()
- [ ] Verify derived state after transition

### Test: test_open_voting_rejects_if_not_in_nomination_state()

- [ ] Replace direct state mutation with fact-based update
- [ ] Add lifecycle verification before asserting error
- [ ] Fix error message assertion (use contains, not exact match)

### Test: test_open_voting_creates_state_transition_record()

- [ ] Use scenario factory
- [ ] No state mutation needed
- [ ] Verify transition record has correct states

### Test: test_open_voting_locks_voting_immediately()

- [ ] Use scenario factory
- [ ] Verify voting_locked flags set correctly

### Test: test_close_voting_transitions_from_voting_to_results_pending()

- [ ] Replace direct state mutation with ElectionScenarioFactory::votingActive()
- [ ] Verify derived state before transition
- [ ] Update state assertion to use ElectionLifecycle

### Test: test_close_voting_rejects_if_not_in_voting_state()

- [ ] Use scenario factory (setUp provides setup state)
- [ ] No state mutation needed
- [ ] Fix error message assertion

### Test: test_close_voting_creates_state_transition_record()

- [ ] Replace state mutation with ElectionScenarioFactory::votingActive()
- [ ] Verify transition record (GOOD as-is)

### Test: test_close_voting_prevents_double_close_when_already_locked_and_ended()

- [ ] Replace state mutation with fact-based update
- [ ] Fix error message assertion
- [ ] Add lifecycle verification

---

## Separate Issue: approval_status Column

**Error:**
```
SQLSTATE[42703]: Undefined column: approval_status
```

**Investigation Needed:**
- Which lifecycle engine code is querying this column?
- Does candidacies table need this column?
- Is this a schema migration that didn't run?

**Action:**
- Search for `approval_status` in Election.php and lifecycle code
- Create separate ticket if schema change needed
- This is NOT part of test refactoring (fix after test refactoring)

---

## Success Criteria

- [ ] All 10 tests pass
- [ ] No direct `state` mutations in test code
- [ ] All assertions use `ElectionLifecycle::of()` or scenario factories
- [ ] Error messages are checked with `assertStringContainsString()` (resilient)
- [ ] Each test verifies derived state matches facts
- [ ] Time frozen in setUp() via Carbon::setTestNow()

---

## Estimated Effort

- Step 1 (Factory replacement): ~5 mins
- Step 2 (State mutations): ~20 mins
- Step 3 (Assertion updates): ~15 mins
- Step 4 (Error messages): ~10 mins
- Step 5 (Time freezing): ~3 mins
- Debugging approval_status: TBD (separate issue)

**Total:** ~1 hour

---

## Next Wave

**Wave 2 (Authorization Alignment):**
- Replace `Gate::before()` bypass with constitutional role enforcement
- Test actual governance authorization, not just state transitions

**Wave 3 (Test Decomposition):**
- Split into: lifecycle, transition, HTTP, governance test groups
- Each test has single responsibility

---

## Architectural Principle

> Tests should NOT ask "did the state column change?"
> Tests SHOULD ask "did constitutional reality evolve correctly?"

This refactoring embodies that principle.
