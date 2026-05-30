# Plan: Fix Failing Election Feature Tests — Part 1 of 3

**Branch:** enhance-election-only  
**Date:** 2026-05-30  
**Approach:** TDD-first (test intent preserved, fixtures fixed)  
**Target:** tests/Feature/Election/ — 202 failing tests  
**This Part:** Categories A + B (the two most widespread root causes)

---

## Failure Classification Summary

All 202 failures across 49 test files fall into **6 distinct root causes**:

| Category | Root Cause | Tests Affected | Count |
|----------|-----------|----------------|-------|
| **A** | `UniqueConstraintViolation` on `user_organisation_roles` | ElectionDashboardAccessTest, VoterEligibilityTest, ElectionVoterManagementTest, ElectionVoterSuspensionTest | ~40 |
| **B** | `InvalidTransitionException` — `has_voters` precondition blind to tenant scope | VotingButtonsStateMachineIntegrationTest, CapacityApprovalTest | ~20 |
| **C** | `403 Forbidden` — route authorization not bypassed by withoutMiddleware() | ElectionCreationTest | ~18 |
| **D** | `PermissionDoesNotExist` — `manage_elections` Spatie permission not seeded | TimelineCapabilityAuthorizationTest | ~8 |
| **E** | `RouteNotFoundException` — route renamed from `elections.update-voting-dates` to `organisations.elections.update-voting-dates` | ElectionManagementConstitutionalTest | ~4 |
| **F** | `ModelNotFoundException` + misc — ConstitutionalParityIntegrationTest, CapacityApprovalTest, others | Various | ~112 |

---

## Part 1 Scope: Categories A and B

### Category A: UniqueConstraintViolation

**Root Cause:**

`UserFactory::configure()` automatically creates `UserOrganisationRole` via `firstOrCreate` after every user is created. But test helpers in multiple files call `UserOrganisationRole::create()` (not `firstOrCreate`) for the SAME user+org combination immediately after factory creation. The factory's auto-creation runs first, then the test helper's raw `create()` inserts a duplicate → violation.

**Pattern (broken):**
```php
// UserFactory::configure() runs automatically after create():
UserOrganisationRole::firstOrCreate(['user_id' => $user->id, 'organisation_id' => $user->organisation_id], ['role' => 'voter']);

// Then the test helper ALSO runs:
UserOrganisationRole::create([
    'id' => (string) Str::uuid(),  // explicit UUID
    'user_id' => $user->id,        // SAME user
    'organisation_id' => $org->id, // SAME org
    'role' => 'voter',             // DUPLICATE → BOOM
]);
```

**Files with this pattern:**
- `tests/Feature/Election/ElectionDashboardAccessTest.php` — `makeOfficer()` at line 58
- `tests/Feature/Election/VoterEligibilityTest.php` — `createVoter()` helpers
- `tests/Feature/Election/ElectionVoterManagementTest.php` — user creation helpers
- `tests/Feature/Election/ElectionVoterSuspensionTest.php` — user creation helpers
- `tests/Feature/Election/ResultsPublicationTest.php` — already fixed (uses `Str::uuid()` + explicit create is OK because factory uses `firstOrCreate` which runs first)

**TDD Fix Strategy:**

For each affected test helper, change `UserOrganisationRole::create(...)` to `UserOrganisationRole::updateOrCreate(...)` so it's idempotent regardless of factory state:

```php
// BEFORE (broken):
UserOrganisationRole::create([
    'id'              => (string) Str::uuid(),
    'user_id'         => $user->id,
    'organisation_id' => $this->org->id,
    'role'            => 'voter',
]);

// AFTER (TDD-safe):
UserOrganisationRole::updateOrCreate(
    ['user_id' => $user->id, 'organisation_id' => $this->org->id],
    ['role' => 'voter']
);
```

**Files to modify:**
1. `tests/Feature/Election/ElectionDashboardAccessTest.php` — lines 58-63, 44-49
2. `tests/Feature/Election/VoterEligibilityTest.php` — identify and fix `create()` calls
3. `tests/Feature/Election/ElectionVoterManagementTest.php` — identify and fix
4. `tests/Feature/Election/ElectionVoterSuspensionTest.php` — identify and fix

**Verification:** Run each test class individually with `--env=testing` and confirm no UniqueConstraintViolation.

---

### Category B: InvalidTransitionException — has_voters precondition

**Root Cause:**

`ConstitutionalTransitionGuard::isPreconditionMet()` at line 182:
```php
'has_voters' => $election->voters()->withoutGlobalScopes()->exists()
    || $election->memberships()->where('role', 'voter')->where('status', 'active')->exists(),
```

The `voters()` call correctly uses `withoutGlobalScopes()`. But the `memberships()` call does NOT. The `ElectionMembership` model likely has a `BelongsToTenant` global scope that filters by the session's `current_organisation_id`. When the `transitionTo()` is called directly on the model in tests (not via HTTP request), the tenant scope filters out the memberships the test created, making `has_voters` return false even though voters exist.

**Evidence:**  
Tests create voters correctly:
```php
ElectionMembership::factory()->create([
    'election_id'     => $election->id,
    'organisation_id' => $election->organisation_id,
    'user_id'         => $this->officer->id,
    'role'            => 'voter',
    'status'          => 'active',
]);
$election->refresh(); // refreshes before transition
```
But `completeAdministration()` → `transitionTo('complete_administration')` → guard's `isPreconditionMet` cannot see them.

**File:** `app/Application/Election/Services/ConstitutionalTransitionGuard.php` line 182

**TDD Fix Strategy:**

The fix is a **one-line production code change** — add `withoutGlobalScopes()` to the memberships check, consistent with how `voters()` is already handled:

```php
// BEFORE:
'has_voters' => $election->voters()->withoutGlobalScopes()->exists()
    || $election->memberships()->where('role', 'voter')->where('status', 'active')->exists(),

// AFTER:
'has_voters' => $election->voters()->withoutGlobalScopes()->exists()
    || $election->memberships()->withoutGlobalScopes()->where('role', 'voter')->where('status', 'active')->exists(),
```

**Why this is correct:**
- The precondition is a BUSINESS RULE check: "does this election have at least one voter?"
- Global scopes are INFRASTRUCTURE concerns: "only show this org's data to this tenant"
- Business rule validation should be scope-independent — it doesn't matter which tenant's session is active; the election's own voters must be visible

**File to modify:**
- `app/Application/Election/Services/ConstitutionalTransitionGuard.php` — line 182

**Verification:**
```bash
php artisan test tests/Feature/Election/VotingButtonsStateMachineIntegrationTest.php --env=testing
php artisan test tests/Feature/Election/CapacityApprovalTest.php --env=testing
```

---

## TDD Execution Sequence (Part 1)

### Step 1: Run baseline to confirm current state

```bash
php artisan test tests/Feature/Election/ElectionDashboardAccessTest.php tests/Feature/Election/VotingButtonsStateMachineIntegrationTest.php --env=testing
```

Expected: Failures in both. **This is the RED state.**

### Step 2: Fix Category B first (1 production code line)

Edit `app/Application/Election/Services/ConstitutionalTransitionGuard.php`:
- Add `->withoutGlobalScopes()` to `memberships()` in `isPreconditionMet()`

Run:
```bash
php artisan test tests/Feature/Election/VotingButtonsStateMachineIntegrationTest.php --env=testing
php artisan test tests/Feature/Election/CapacityApprovalTest.php --env=testing
```

Expected: Both pass. **This is GREEN for Category B.**

### Step 3: Fix Category A (test fixture idempotency)

For each affected test file, change `UserOrganisationRole::create()` → `updateOrCreate()` in test helpers.

Run after each fix:
```bash
php artisan test tests/Feature/Election/ElectionDashboardAccessTest.php --env=testing
php artisan test tests/Feature/Election/VoterEligibilityTest.php --env=testing
php artisan test tests/Feature/Election/ElectionVoterManagementTest.php --env=testing
php artisan test tests/Feature/Election/ElectionVoterSuspensionTest.php --env=testing
```

Expected: UniqueConstraintViolation errors gone. **GREEN for Category A.**

### Step 4: Regression check

```bash
php artisan test tests/Feature/Election/ --env=testing 2>&1 | grep "Tests:" | tail -1
```

Expected: Reduction in failures from 202. Part 1 targets ~60 failures.

---

## What Part 1 Does NOT Fix

The following categories are covered in Part 2 and Part 3:

| Category | Issue | Plan Part |
|----------|-------|-----------|
| C | ElectionCreationTest — 403 authorization bypass | Part 2 |
| D | TimelineCapabilityAuthorizationTest — manage_elections permission not seeded | Part 2 |
| E | ElectionManagementConstitutionalTest — wrong route name | Part 2 |
| F | ConstitutionalParityIntegrationTest + CapacityApprovalTest + others | Part 3 |

---

## Files to Modify in Part 1

| File | Change | Category |
|------|--------|----------|
| `app/Application/Election/Services/ConstitutionalTransitionGuard.php` | Add `->withoutGlobalScopes()` to memberships check (line 182) | B |
| `tests/Feature/Election/ElectionDashboardAccessTest.php` | Change `create()` → `updateOrCreate()` in makeOfficer() | A |
| `tests/Feature/Election/VoterEligibilityTest.php` | Change `create()` → `updateOrCreate()` in user helpers | A |
| `tests/Feature/Election/ElectionVoterManagementTest.php` | Change `create()` → `updateOrCreate()` in user helpers | A |
| `tests/Feature/Election/ElectionVoterSuspensionTest.php` | Change `create()` → `updateOrCreate()` in user helpers | A |

---

## Architectural Note on Category B Fix

This is a **production code fix**, not a test fix. The `has_voters` precondition check was asymmetric:
- `voters()` ✅ used `withoutGlobalScopes()`
- `memberships()` ❌ did NOT

The inconsistency means the check behaved differently depending on which code path was used and what the tenant session context was at evaluation time. The fix makes it consistent: ALL precondition checks bypass tenant scopes because they're evaluating business facts about the election itself, not filtering for a particular tenant's view.

This is the same pattern applied in `ResultsPublicationTest` where we learned that business invariant checks must be scope-independent.
