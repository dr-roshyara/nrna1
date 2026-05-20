# Phase 3.1.E: Election Test Suite Stabilization + Remaining Controller Migration
**Type:** TDD Stabilization + Phase 3.1 Completion
**Scope:** Fix 192 failing Elections Feature tests + complete ElectionManagementController migration
**Approach:** Fix root causes in dependency order, then finish Phase 3.1 controller migration
**Date:** 2026-05-19
**Supersedes:** Phase 2.4 (complete). This plan closes Phase 3.1 and unblocks Phase 3.2 Strict Mode Activation.

---

## Context

Phase 3.1.A–D completed the SSOT controller migration and CI-level enforcement. However, running the Elections Feature test suite revealed 192 failing tests caused by 4 independent root causes introduced during the Phase C strangler refactoring. These must be resolved before Phase 3.2 (Strict Mode) can be activated safely.

Additionally, `ElectionManagementController` still has 6 deprecated-pattern sites that were not addressed in Phase 3.1.C. Both the test stabilization and the controller migration must complete before declaring Phase 3.1 done.

**Key Audit Findings (from code exploration):**

- `ElectionVoterController` — CLEAN. All `->status` usages are on `ElectionMembership` (pivot), not `Election`. Zero Phase 3.1 migration items.
- `CommitteeMemberController (Api/Governance)` — CLEAN. No election lifecycle references.
- `ElectionManagementController` — 6 remaining migration targets (lines 67, 239–241, 347, 527–528, 546–547, 791).

---

## Root Cause Analysis — 192 Failing Tests

| # | Root Cause | Exception | Test Files | Approx Count |
|---|-----------|-----------|------------|-------------|
| RC-1 | `ElectionMembership::assignVoter()` / `bulkAssignVoters()` static methods do not exist (moved to CQRS handlers, tests not updated) | `BadMethodCallException` | `ElectionMembershipInfrastructureTest`, `ElectionMembershipPersistenceTest` | ~20 |
| RC-2 | Partial unique index `WHERE deleted_at IS NULL` in `harden_election_memberships` migration is MySQL-incompatible (XAMPP/MariaDB) | `QueryException` during `RefreshDatabase` | All tests using `election_memberships` table | ~100+ (cascade) |
| RC-3 | Hardcoded `'slug' => 'general-election-2026'` in `Election::create()` hits unique constraint across test methods | `UniqueConstraintViolationException` | `ElectionActivationTest`, `ElectionPolicyTest`, many more | ~60 |
| RC-4 | `Election::factory()` without `->forOrganisation()` sets `organisation_id = null`, breaking route lookups and state machine | `DomainException`, `InvalidTransitionException`, `QueryException` | `VotingButtonsStateMachineTest`, `StateMachine\CurrentBehaviorTest` | ~20 |

---

## Fix Plan (ordered by dependency)

### Fix 1: MySQL-Compatible Unique Index (RC-2) — Critical Blocker

**Why first:** This migration failure during `RefreshDatabase` cascades to 100+ test failures. Nothing else can be verified until this is fixed.

**Problem:**
`database/migrations/2026_05_19_000001_harden_election_memberships_for_election_only_mode.php`
creates a partial unique index (`WHERE deleted_at IS NULL`) which MySQL/MariaDB does not support. The migration already ran on production but silently skipped the index creation on MySQL (the `CREATE UNIQUE INDEX ... WHERE` statement fails, but migration marked as ran).

**Solution:** Create a new corrective migration:
`database/migrations/2026_05_19_100000_fix_election_memberships_unique_index_mysql_compat.php`

```php
// Drop partial index if it exists (no-op on MySQL where it never created)
DB::statement('DROP INDEX IF EXISTS uq_user_election_active ON election_memberships');

// Add MySQL-compatible standard unique constraint
Schema::table('election_memberships', function (Blueprint $table) {
    $table->unique(['user_id', 'election_id'], 'uq_user_election');
});
```

**Why this is safe:** `AssignVoterHandler::handle()` already calls `findWithTrashed()` and `restoreAndUpdate()` when a soft-deleted record exists. Re-import cases are handled at the application layer. The partial index was defensive database enforcement that is now redundant.

---

### Fix 2: Update Tests to Use CQRS Handlers (RC-1)

**Files to modify:**
- `tests/Feature/Election/ElectionMembershipInfrastructureTest.php`
- `tests/Feature/Election/ElectionMembershipPersistenceTest.php`

**Strategy:** Replace all calls to `ElectionMembership::assignVoter(...)` and `ElectionMembership::bulkAssignVoters(...)` with the appropriate handler invocations:

```php
// OLD (broken):
ElectionMembership::assignVoter($election, $user, 'voter');

// NEW (correct):
$handler = app(AssignVoterHandler::class);
$handler->handle(new AssignVoterCommand(
    userId: $user->id,
    electionId: $election->id,
    organisationId: $organisation->id,
    mode: ElectionMode::fromOrganisation($organisation),
    assignedBy: null,
));
```

Cache invalidation tests (`ElectionMembershipInfrastructureTest`) should verify that the handler pipeline (not the model) triggers cache invalidation via `ElectionCacheService::forgetVoterKeys()`.

---

### Fix 3: Remove Hardcoded Election Slugs (RC-3)

**Files to modify:**
- `tests/Feature/Election/ElectionActivationTest.php`
- Any test file creating elections with hardcoded slug strings

**Fix:** Remove `'slug' => 'general-election-2026'` from test fixtures. The `Election` model auto-generates slugs from the name. Let the factory handle slug generation:

```php
// OLD:
Election::create([..., 'slug' => 'general-election-2026', ...]);

// NEW:
Election::factory()->forOrganisation($this->org)->create([
    'name' => 'General Election 2026',
    // slug auto-generated
]);
```

---

### Fix 4: Add forOrganisation() to Factory Calls (RC-4)

**Files to modify:**
- `tests/Feature/Election/VotingButtonsStateMachineTest.php`
- `tests/Feature/Election/StateMachine/CurrentBehaviorTest.php`

**Fix:** All `Election::factory()` calls that omit `->forOrganisation($this->org)` must have it added. Without it, `organisation_id` defaults to `null`, breaking state machine transitions and route lookups.

```php
// OLD:
$this->election = Election::factory()->inNominationState()->create([...]);

// NEW:
$this->election = Election::factory()
    ->forOrganisation($this->org)
    ->inNominationState()
    ->create([...]);
```

---

### Fix 5: Complete ElectionManagementController Phase 3.1 Migration

**File:** `app/Http/Controllers/Election/ElectionManagementController.php`

Both `ElectionLifecycle` (line 17) and `ElectionClockService` (line 16) are already imported. No new imports needed.

| Line | Current (deprecated) | Replace with |
|------|---------------------|--------------|
| 67 | `$e->status` in list map | `ElectionLifecycle::of($e)->state()->value` |
| 239 | `->where('status', 'active')` | `->where('state', ElectionState::ActiveVoting->value)` or lifecycle-derived scope |
| 240–241 | `->where('start_date', '<=', now())->where('end_date', '>=', now())` | `ElectionClockService::scopeActiveWindow($query)` — or inline as `ElectionClockService::now()` comparisons |
| 347 | `->where('is_active', true)` | Remove or replace with state-based scope; method is marked `@deprecated` |
| 527–528, 546–547 | Raw `now()` date comparisons in `listDemoElections()` | `ElectionClockService::hasVotingStarted($election)` per call |
| 791 | `'status' => $election->status, 'is_active' => $election->is_active` in JSON endpoint | `'state' => ElectionLifecycle::of($election)->state()->value, 'is_active' => ElectionLifecycle::of($election)->isActive()` |

---

## Critical Files

| File | Role |
|------|------|
| `database/migrations/2026_05_19_000001_harden_election_memberships_for_election_only_mode.php` | Contains MySQL-incompatible partial index (do not modify — create corrective migration instead) |
| `database/migrations/2026_05_19_100000_fix_election_memberships_unique_index_mysql_compat.php` | **NEW** — corrective migration to create |
| `tests/Feature/Election/ElectionMembershipInfrastructureTest.php` | RC-1 fix — update to use AssignVoterHandler |
| `tests/Feature/Election/ElectionMembershipPersistenceTest.php` | RC-1 fix — update to use AssignVoterHandler |
| `tests/Feature/Election/ElectionActivationTest.php` | RC-3 fix — remove hardcoded slug |
| `tests/Feature/Election/VotingButtonsStateMachineTest.php` | RC-4 fix — add forOrganisation() |
| `tests/Feature/Election/StateMachine/CurrentBehaviorTest.php` | RC-4 fix — add forOrganisation() |
| `app/Http/Controllers/Election/ElectionManagementController.php` | Fix 5 — 6 remaining Phase 3.1 migration sites |
| `app/Application/Election/Facades/ElectionLifecycle.php` | Reference — already imported in ManagementController |
| `app/Services/ElectionClockService.php` | Reference — already imported in ManagementController |
| `app/Contexts/Elections/Application/Handlers/AssignVoterHandler.php` | Reference — used in RC-1 test updates |
| `app/Application/Election/Deprecation/DeprecationPolicy.php` | Phase 3.2 gate — MODE='warning', ready to change to 'strict' after fixes |

---

## Execution Order

```bash
# Step 1: Create and run corrective migration (RC-2)
php artisan make:migration fix_election_memberships_unique_index_mysql_compat
php artisan migrate

# Step 2: Verify migration fixed cascade failures
php artisan test tests/Feature/Election/StateMachine/ --no-coverage

# Step 3: Fix handler-based tests (RC-1)
# Edit ElectionMembershipInfrastructureTest + ElectionMembershipPersistenceTest
php artisan test tests/Feature/Election/ElectionMembershipInfrastructureTest.php tests/Feature/Election/ElectionMembershipPersistenceTest.php --no-coverage

# Step 4: Fix hardcoded slugs (RC-3)
# Edit ElectionActivationTest + other affected files
php artisan test tests/Feature/Election/ElectionActivationTest.php --no-coverage

# Step 5: Fix factory forOrganisation (RC-4)
php artisan test tests/Feature/Election/VotingButtonsStateMachineTest.php tests/Feature/Election/StateMachine/CurrentBehaviorTest.php --no-coverage

# Step 6: Complete ElectionManagementController Phase 3.1 migration (Fix 5)
php artisan test tests/Feature/Election/ tests/Unit/Application/Election/ tests/Architecture/ --no-coverage

# Step 7: Verify strict mode readiness
php artisan election:constitution:health
```

---

## Definition of Done

- [x] **RC-1 Fixed:** All 20 membership tests now passing (uses CQRS handlers)
- [ ] All Elections Feature tests GREEN (from 192 → 182 remaining failures)
- [ ] All Architecture invariant tests still GREEN (6/6)
- [ ] All Unit Application Election tests still GREEN (60/60)
- [ ] `php artisan election:constitution:health` → READY FOR STRICT MODE
- [ ] `ElectionManagementController` has zero deprecated pattern accesses
- [ ] Ready to change `DeprecationPolicy::MODE` from `'warning'` to `'strict'` (Phase 3.2)

## Execution Progress (Updated 2026-05-20)

### Completed
✅ **Fix 1: Create corrective migration** - RC-2 addressed (PostgreSQL compatible)
✅ **Fix 2: Update tests to use CQRS handlers** - RC-1 COMPLETE (20 tests passing)
  - ElectionMembershipInfrastructureTest: 9/9 passing
  - ElectionMembershipPersistenceTest: 11/11 passing
  - Handlers properly integrated with tenant isolation

### In Progress
⏳ **Fix 3 & 4:** RC-3 (hardcoded slugs) and RC-4 (forOrganisation) - Investigation needed
   - Initial findings suggest different root cause: unique constraint violations across tests
   - More thorough analysis needed before fix

### Remaining
- Fix 5: Complete ElectionManagementController Phase 3.1 migration (6 deprecated sites)
