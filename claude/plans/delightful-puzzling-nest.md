# Plan: Fix Failing Election Feature Tests — Category B Scope Analysis + Category A Ownership

**Supersedes:** Election Publication Governance (now complete)  
**Date:** 2026-05-30  
**Status:** Awaiting Domain Architect approval before implementation  

---

## CATEGORY_B_SCOPE_ANALYSIS

### The Question

> Should `ConstitutionalTransitionGuard::isPreconditionMet()` bypass the tenant scope  
> when checking `has_voters`?

---

### Evidence: The Root Cause is NOT the Scope Itself

The real root cause is **`TenantContext` is a static singleton that is never reset between tests**.

```php
// app/Services/TenantContext.php
final class TenantContext
{
    private static ?string $tenantId = null;  // ← static, persists across tests

    public static function set(?string $tenantId): void { self::$tenantId = $tenantId; }
    public static function get(): ?string { return self::$tenantId; }
    public static function clear(): void { self::$tenantId = null; }
}
```

```php
// tests/TestCase.php (base class)
protected function setUp(): void
{
    parent::setUp();
    Organisation::firstOrCreate([...]);
    // ← TenantContext::clear() is NOT called here
}
```

```php
// app/Traits/BelongsToTenant.php
$orgId = \App\Services\TenantContext::get() ?? session('current_organisation_id');
//        ↑ reads static value first                 ↑ session fallback
```

**Sequence of events when tests run:**

1. Test A runs. `ElectionManagementController::index()` is called via HTTP.
2. Controller sets `TenantContext::set($election->organisation_id)` → Org A ID.
3. Test A ends. RefreshDatabase rolls back DB. But **TenantContext::$tenantId is still "Org A"**.
4. Test B runs. Creates election for Org B.
5. Sets `session(['current_organisation_id' => $orgB->id])`.
6. Calls `completeAdministration()` directly on model.
7. Guard checks `$election->memberships()`.
8. BelongsToTenant reads `TenantContext::get()` → returns **Org A** (stale from Test A!).
9. SQL: `WHERE election_id = X AND organisation_id = OrgA` → returns 0 rows (memberships are for Org B).
10. `has_voters` returns false → `InvalidTransitionException`.

---

### Answers to the Domain Architect's Questions

**Q1: Can an Election ever see memberships from another organisation?**

**No.** By domain design, `election_memberships.organisation_id` is always identical to  
`elections.organisation_id`. It is a **denormalized field** copied from the election for  
tenant scoping. Every factory and every controller sets it from `$election->organisation_id`.  
There is no path in production code where a membership would belong to a different org than its election.

**Q2: Is organisation_id part of the aggregate boundary?**

**Yes, but asymmetrically.** The election aggregate's identity boundary is `election_id`.  
The `organisation_id` on `ElectionMembership` is a tenant ownership marker (denormalized),  
not the primary aggregate boundary key. The FK `election_memberships.election_id → elections.id`  
is the true aggregate boundary. `organisation_id` exists so BelongsToTenant can filter;  
it does not add additional domain meaning beyond "this row belongs to the same tenant as its election."

**Q3: Is the tenant scope enforcing security or domain consistency?**

**Infrastructure security, not domain consistency.**  
The membership's `election_id` FK already enforces domain consistency (can't belong to wrong election).  
The `organisation_id` scope enforces *multi-tenant data isolation in HTTP contexts*  
(so one org's HTTP request can't accidentally see another org's data).  
When evaluating a business precondition ("does election X have voters?") from inside a domain  
service, the HTTP session context is irrelevant. The election's own `election_id` constraint  
is sufficient isolation.

**Q4: Show the election and membership organisation_ids in the failing test**

```php
// VotingButtonsStateMachineIntegrationTest

$this->testOrg = Organisation::factory()->create(['type' => 'platform']);
// testOrg.id = "uuid-A"

$election = Election::factory()->create([
    'organisation_id' => $this->testOrg->id,  // "uuid-A"
]);

ElectionMembership::factory()->create([
    'election_id'     => $election->id,
    'organisation_id' => $election->organisation_id,  // "uuid-A"
    'role'            => 'voter',
    'status'          => 'active',
]);

// ← All same org. Should work.
// ← But TenantContext may be stale from a prior test run → different org.
```

**Q5: SQL with and without scope (reconstructed from code)**

```sql
-- With stale TenantContext (BROKEN — stale org from previous test):
SELECT * FROM election_memberships
WHERE election_id = 'election-uuid'
AND organisation_id = 'stale-org-from-prior-test'  ← TenantContext::get()
AND role = 'voter'
AND status = 'active'
-- RETURNS 0 ROWS → has_voters = false

-- Without global scope (CORRECT):
SELECT * FROM election_memberships
WHERE election_id = 'election-uuid'
AND role = 'voter'
AND status = 'active'
-- RETURNS 1 ROW → has_voters = true
```

---

### Two Valid Fix Strategies

**Strategy 1: Fix the Test Isolation (recommended by Domain Architect's framework)**

Add `TenantContext::clear()` to `TestCase::setUp()` so static state is reset between tests.

```php
// tests/TestCase.php
protected function setUp(): void
{
    parent::setUp();
    \App\Services\TenantContext::clear();  // ← add this line
    Organisation::firstOrCreate([...]);
}
```

**Verdict:** Fixes the *source* of pollution. Does not change production code. Philosophically clean.

**Risk:** If ANY other tests rely on TenantContext persisting between setUp and the test body
(unlikely — tests that need TenantContext set it themselves in setUp), this could break them.

---

**Strategy 2: Fix the Guard's Precondition Check (Claude's original proposal)**

Add `withoutGlobalScopes()` to memberships check in `ConstitutionalTransitionGuard`.

```php
// app/Application/Election/Services/ConstitutionalTransitionGuard.php
'has_voters' => $election->voters()->withoutGlobalScopes()->exists()
    || $election->memberships()->withoutGlobalScopes()->where('role', 'voter')->where('status', 'active')->exists(),
```

**Verdict:** Fixes the production code to be session-independent. Makes the guard robust against
any caller context (HTTP, CLI, test). Consistent with how `voters()` already works.

**Risk:** If a misconfigured row existed with wrong `organisation_id`, it would now count.
In practice impossible (FK constraint + factory always sets correct org_id).

---

### RECOMMENDATION (Revised per Architect Review)

**Step 1 ONLY — prove with TenantContext::clear():**

Add ONLY `TenantContext::clear()` to `TestCase::setUp()`. Run targeted tests.  
**Do NOT touch production code until this is measured.**

- If failures disappear → root cause proven, stop. No production change needed.  
- If failures remain → only then investigate whether `withoutGlobalScopes()` is warranted.

**`withoutGlobalScopes()` on the guard is NOT approved until Step 1 is proven insufficient.**

---

## CATEGORY_A_OWNERSHIP_ANALYSIS

### The Question

> Who owns UserOrganisationRole creation — the UserFactory, or the test helper?

### Evidence

`UserFactory::configure()`:
```php
return $this->afterCreating(function (User $user) {
    UserOrganisationRole::firstOrCreate(
        ['user_id' => $user->id, 'organisation_id' => $user->organisation_id],
        ['role' => 'voter']  // default role
    );
});
```

`ElectionDashboardAccessTest::makeOfficer()`:
```php
$user = User::factory()->create(['organisation_id' => $this->org->id]);
// Factory runs → creates UserOrganisationRole(user_id=X, org_id=Y, role='voter') via firstOrCreate

UserOrganisationRole::create([  // ← then this ALSO inserts same user+org → BOOM
    'id'              => (string) Str::uuid(),
    'user_id'         => $user->id,
    'organisation_id' => $this->org->id,
    'role'            => 'voter',
]);
```

### Ownership Decision

**UserFactory OWNS the default role creation.** Its `configure()` hook ensures every user has a baseline `voter` role in their org. This is correct — TenantContext middleware requires this role to exist.

**Test helpers that need a SPECIFIC role** (e.g., 'owner', 'admin') should use `updateOrCreate` to SET the role without risking a duplicate insert.

The base `TestCase::assignRole()` already follows this pattern correctly:
```php
// tests/TestCase.php — the ESTABLISHED PATTERN
UserOrganisationRole::updateOrCreate(
    ['user_id' => $user->id, 'organisation_id' => $organisation->id],
    ['role' => $role]
);
```

**Conclusion:** Ownership is clear. Factory creates default role. Tests OVERRIDE role via `updateOrCreate`. Tests must NOT use raw `create()` for `UserOrganisationRole` when factory has already run.

---

## Approved Implementation Sequence

### Commit 1: TenantContextIsolationTest (Document the discovery)

**File:** `tests/Architecture/Election/TenantContextIsolationTest.php`

Write a test that **proves** TenantContext is not reset between test invocations and  
that BelongsToTenant's scope is affected by it:

```php
public function test_tenant_context_must_be_empty_at_start_of_each_test(): void
{
    // If this fails, TenantContext leaked from a prior test
    $this->assertNull(\App\Services\TenantContext::get(),
        'TenantContext must be null at test start — check TestCase::setUp() for TenantContext::clear()');
}

public function test_belongs_to_tenant_scope_reads_tenant_context_not_session_when_set(): void
{
    $orgA = Organisation::factory()->create();
    $orgB = Organisation::factory()->create();

    ElectionMembership::factory()->create(['organisation_id' => $orgA->id, ...]);

    // Simulate stale TenantContext from a prior test
    \App\Services\TenantContext::set($orgB->id);

    $count = ElectionMembership::where('organisation_id', $orgA->id)->count();

    // With stale TenantContext, the scope hides records that exist
    $this->assertEquals(0, $count,
        'BelongsToTenant scope uses TenantContext over session — stale context hides real data');
}
```

Run: `php artisan test tests/Architecture/Election/TenantContextIsolationTest.php --env=testing`  
Expected: **Passes** (documents the discovered behavior as architectural fact).

---

### Commit 2: Category B fix — TenantContext::clear() in TestCase ONLY

**File:** `tests/TestCase.php`

```php
protected function setUp(): void
{
    parent::setUp();
    \App\Services\TenantContext::clear();  // ← add this one line
    Organisation::firstOrCreate([...]);
}
```

Run targeted tests and **measure**:
```bash
php artisan test tests/Feature/Election/VotingButtonsStateMachineIntegrationTest.php --env=testing
php artisan test tests/Feature/Election/CapacityApprovalTest.php --env=testing
```

**STOP.** Produce CATEGORY_B_VERIFICATION_REPORT.md:
- Failures before: N
- Failures after: M  
- Were all failures resolved? YES → no production code change needed. NO → investigate further.

**Do NOT touch ConstitutionalTransitionGuard until report proves it necessary.**

---

### Commit 3: Category A fix — UserOrganisationRole ownership

**Fix Pattern:** Replace `UserOrganisationRole::create([...])` with `UserOrganisationRole::updateOrCreate([...])` in test helpers that run after `User::factory()->create()`. This aligns with the established `TestCase::assignRole()` pattern.

Files and exact locations to fix:
- `tests/Feature/Election/ElectionDashboardAccessTest.php` — `makeOfficer()` lines ~58-63 and ~44-49
- `tests/Feature/Election/VoterEligibilityTest.php` — user creation helpers (scan for `UserOrganisationRole::create`)
- `tests/Feature/Election/ElectionVoterManagementTest.php` — user creation helpers
- `tests/Feature/Election/ElectionVoterSuspensionTest.php` — user creation helpers

Run after each file fix:
```bash
php artisan test tests/Feature/Election/{FileName}.php --env=testing
```
Expected: No UniqueConstraintViolation.

**One commit per test file fixed.** Do not batch all 4 into one commit.

---

## Remaining Categories (Part 3 — separate plan)

| Category | Root Cause | Files |
|----------|-----------|-------|
| C | 403 on ElectionCreationTest — authorization not bypassed by withoutMiddleware() | ElectionCreationTest |
| D | PermissionDoesNotExist — manage_elections not seeded | TimelineCapabilityAuthorizationTest |
| E | RouteNotFoundException — route renamed from `elections.update-voting-dates` to `organisations.elections.update-voting-dates` | ElectionManagementConstitutionalTest |
| F | ModelNotFoundException + misc — to investigate | Multiple |

---

# Plan: Election Publication Governance — Authorization Fix + Domain Events + Test Coverage

## Context

**Trust work is frozen.** T-001 Domain Events are complete. T-002 has no proven business need.

The next delivery is **Election Publication Governance** — a business concern about governing the official publication status of an election result, not merely a UI publishing feature.

The core election lifecycle is:
```
Membership ✅ → Verification ✅ → Voting ✅ → Publication Governance ❌
```

Exploration found **two concrete bugs**, **zero HTTP tests** for the publication path, and a **missing domain event boundary**.

---

## Bugs Found

**Bug 1 (Security): `unpublish()` missing authorization**
- `publish()` correctly calls `$this->authorize('publishResults', $election)` — chief-only
- `unpublish()` has NO authorization check — any authenticated user can unpublish results
- File: `app/Http/Controllers/Election/ElectionManagementController.php` ~line 842

**Bug 2 (Routing): Viewboard result link is hardcoded**
- `resources/js/Pages/Election/Viewboard.vue` hardcodes `href="/election/result"` (no slug)
- File: `resources/js/Pages/Election/Viewboard.vue`

**Bug 3 (Architecture): No domain events for publication state changes**
- `publish()` changes `results_published = true` with no domain event
- `unpublish()` changes `results_published = false` with no domain event
- Future consumers (audit, notification, constitutional archive) have no extension point

**Bug 4 (Architecture): Authorization is duplicated, not unified**
- Both `publish()` and `unpublish()` belong to one domain capability: **controlling publication authority**
- Current pattern repeats `publishResults` policy in both places instead of expressing the unified intent

---

## Constraints

- **TDD first**: Tests written before any code changes
- **`--env=testing`**: All tests run with `php artisan test --env=testing`
- No new migrations
- Authorization added to `unpublish()` using existing `ElectionPolicy::publishResults()` (do not refactor policy in this sprint — that is TD-003)
- Domain events follow T-001 pattern exactly (readonly class, no Laravel, immutable)
- `unpublish()` keeps direct column update pattern for now (full state machine integration is TD-002)

---

## Domain Events to Create

Two new events following T-001 structure (pure PHP, no Eloquent, readonly):

### `ResultsPublishedEvent`
```
Fields:
  electionId     string
  publishedBy    string  — officer user ID
  publishedAt    DateTimeImmutable
  state          string  — election state after transition ('results_published')
```

### `ResultsUnpublishedEvent`
```
Fields:
  electionId     string
  unpublishedBy  string  — officer user ID
  unpublishedAt  DateTimeImmutable
```

Location: `app/Contexts/Election/Domain/Events/` (note: Election bounded context, not Trust)

---

## TDD Execution Plan

### Phase A — Red (Write All Failing Tests First)

#### File 1: `tests/Unit/Election/Events/ResultsPublishedEventTest.php`
- test_event_can_be_created_with_all_fields
- test_event_is_immutable
- test_event_contains_only_election_publication_concepts

#### File 2: `tests/Unit/Election/Events/ResultsUnpublishedEventTest.php`
- test_event_can_be_created_with_all_fields
- test_event_is_immutable

#### File 3: `tests/Feature/Election/ResultsPublicationTest.php` (HTTP tests)

| # | Test | Expected Red Reason |
|---|------|---|
| 1 | Viewboard requires auth — guest redirected | May already pass |
| 2 | Viewboard renders correct Inertia props (election, stats, readonly) | Missing test |
| 3 | Publish requires chief role — deputy gets 403 | Missing test |
| 4 | Publish transitions state machine → results_published = true | Missing test |
| 5 | Publish dispatches ResultsPublishedEvent | Event class doesn't exist yet |
| 6 | **Unpublish requires authorization — deputy gets 403** | **FAILS: bug proves auth missing** |
| 7 | Unpublish by chief sets results_published = false | Missing test |
| 8 | Unpublish dispatches ResultsUnpublishedEvent | Event class doesn't exist yet |

#### File 4: `tests/Unit/Election/ElectionPublicationTest.php` (aggregate-level)
- test_election_cannot_be_published_when_not_in_counting_state
- test_election_cannot_be_published_twice
- test_publish_sets_results_published_at_timestamp
- test_results_published_at_is_not_cleared_on_unpublish (documents the asymmetry as intentional)

Run: `php artisan test tests/Unit/Election/ tests/Feature/Election/ResultsPublicationTest.php --env=testing`
**Expected: Multiple failures.**

---

### Phase B — Green (Fix the Bugs, Create the Events)

**Step 1: Create event classes**
- `app/Contexts/Election/Domain/Events/ResultsPublishedEvent.php`
- `app/Contexts/Election/Domain/Events/ResultsUnpublishedEvent.php`
- Follow T-001 pattern: `final readonly class`, named constructor, getters only

**Step 2: Fix `unpublish()` authorization**

File: `app/Http/Controllers/Election/ElectionManagementController.php`

Add before the update:
```php
$this->authorize('publishResults', $election);
```

**Step 3: Dispatch domain events from controller**

In `publish()` after state machine transition:
```php
event(new ResultsPublishedEvent(
    electionId: $election->id,
    publishedBy: auth()->id(),
    publishedAt: new DateTimeImmutable($election->results_published_at->toDateTimeString()),
    state: $election->state,
));
```

In `unpublish()` after column update:
```php
event(new ResultsUnpublishedEvent(
    electionId: $election->id,
    unpublishedBy: auth()->id(),
    unpublishedAt: new DateTimeImmutable(),
));
```

**Step 4: Fix Viewboard routing bug**

File: `resources/js/Pages/Election/Viewboard.vue`

Investigate which route name `ResultController::index()` registers, then replace:
```html
<!-- Before (broken) -->
<a href="/election/result">View Results</a>

<!-- After (correct) -->
<Link :href="route('election.result', { election: election.slug })">View Results</Link>
```

Run: `php artisan test tests/Unit/Election/ tests/Feature/Election/ResultsPublicationTest.php --env=testing`
**Expected: All tests pass.**

---

### Phase C — Regression

```bash
php artisan test --env=testing
```
**Expected: No regressions.**

---

## Files to Create

| File | Purpose |
|------|---------|
| `app/Contexts/Election/Domain/Events/ResultsPublishedEvent.php` | Domain event: results published |
| `app/Contexts/Election/Domain/Events/ResultsUnpublishedEvent.php` | Domain event: results unpublished |
| `tests/Unit/Election/Events/ResultsPublishedEventTest.php` | Unit tests for event contract |
| `tests/Unit/Election/Events/ResultsUnpublishedEventTest.php` | Unit tests for event contract |
| `tests/Feature/Election/ResultsPublicationTest.php` | 8 HTTP tests for publication workflow |
| `tests/Unit/Election/ElectionPublicationTest.php` | 4 aggregate-level publication tests |

## Files to Modify

| File | Change |
|------|--------|
| `app/Http/Controllers/Election/ElectionManagementController.php` | Add `$this->authorize('publishResults', $election)` to `unpublish()`; dispatch domain events from both `publish()` and `unpublish()` |
| `resources/js/Pages/Election/Viewboard.vue` | Fix hardcoded href to use election slug |

---

## Test Setup Requirements (From T-001 Lessons Learned)

Each feature test requires in setUp():
1. `Organisation::factory()->create()`
2. `User::factory()->create()` for each role
3. `UserOrganisationRole::create([...])` — required by `EnsureOrganisationMember` middleware (CRITICAL: without this, middleware blocks silently)
4. `ElectionOfficer::create(['role' => 'chief', 'status' => 'active'])`
5. `ElectionOfficer::create(['role' => 'deputy', 'status' => 'active'])`

For publish test (Test 4): election must be in `counting` state. Need to verify whether `Election::factory()` accepts arbitrary `state` values or if full transition chain is required.

---

## Technical Debt Captured (Do Not Block This Sprint)

These architectural improvements are real but not delivery blockers:

| Ticket | Description |
|--------|-------------|
| **TD-001** | Move `unpublish()` into the state machine (currently direct DB update; should be `results_published → results_unpublished` transition through ConstitutionalTransitionGuard) |
| **TD-002** | Introduce `ElectionPolicy::managePublication()` that unifies publish + unpublish under a single named capability instead of reusing `publishResults` for both |
| **TD-003** | Add event listeners for `ResultsPublishedEvent` and `ResultsUnpublishedEvent` once a real consumer exists (audit dashboard, notification, constitutional archive) |

---

## Why This Scope

The Domain Architect named the business concern correctly:

> **Election Publication Governance** — governing the official publication status of a constitutional election result

This sprint delivers:
- ✅ Security fix (unauthorized unpublish blocked)
- ✅ Domain events (audit extension point)
- ✅ Aggregate tests (survive framework changes)
- ✅ HTTP test coverage (8 tests)
- ✅ Routing fix
- ✅ Explicit tech debt captured

It does NOT deliver:
- Export formats (CSV/PDF/JSON)
- Real-time results board
- Verification proof UI
- State machine refactor for unpublish
- Policy capability unification

---

## Verification

```bash
# Phase A: Red
php artisan test tests/Unit/Election/ tests/Feature/Election/ResultsPublicationTest.php --env=testing

# Phase B: Green
php artisan test tests/Unit/Election/ tests/Feature/Election/ResultsPublicationTest.php --env=testing

# Phase C: Regression
php artisan test --env=testing
```
