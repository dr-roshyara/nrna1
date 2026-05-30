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
