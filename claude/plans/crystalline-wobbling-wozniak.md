# Plan: Fix Org Demo Vote Persistence to demo_votes + demo_results

**Date:** 2026-05-30
**Status:** Plan Phase
**Approach:** TDD — tests first, --env=testing

---

## Context

There are TWO demo flows in the system:

| Flow | Route prefix | Auth | Vote saving |
|------|-------------|------|-------------|
| Public demo | `/public-demo/*` | Anonymous | NOT required (Rule 1) |
| Org demo | `/election/demo/*` | Logged in | REQUIRED (Rule 2) |

The public demo `verifyConfirm()` only updates the `PublicDemoSession` model — it never calls `save_vote()`. This is correct per Rule 1.

The org demo `DemoVoteController@store()` → `save_vote()` does call save logic, but `organisation_id` is taken from `session('current_organisation_id')`. If that session key is absent (e.g., the session hasn't set it), votes are stored with `organisation_id = NULL`, and the results page (which uses `BelongsToTenant` global scope filtering by the session org) never finds them.

**Root cause for zero results on `/demo/result`:**  
`save_vote()` at lines 2698–2708 uses `session('current_organisation_id')` for demo elections. No fallback to `$election->organisation_id`. Votes saved with `NULL` org_id are invisible to the scoped result query.

---

## What Needs to Change

### Fix 1 — `DemoVoteController@save_vote()` — fallback `organisation_id`

**File:** `app/Http/Controllers/Demo/DemoVoteController.php`  
**Lines:** ~2698–2708 (vote record) and ~2904–2908 (result record)

Change the `else` branch from:
```php
$vote->organisation_id = session('current_organisation_id');
```
to:
```php
$vote->organisation_id = session('current_organisation_id') ?? $election->organisation_id;
```

Apply the same fix to the `$result->organisation_id` assignment:
```php
$result->organisation_id = session('current_organisation_id') ?? $election->organisation_id;
```

This ensures:
- Logged-in org demo → uses session org_id (correct)
- Session missing org_id → falls back to election's org_id (default organisation)
- Public demo → irrelevant (save_vote is never called from verifyConfirm)

---

## TDD Plan (tests first, --env=testing)

### Test file to create
`tests/Feature/Demo/OrgDemoVotePersistenceTest.php`

### Tests to write (RED first, then GREEN)

#### Test 1 — vote saved to demo_votes with organisation_id
```
Given: logged-in user, org demo election with a post + candidates
When: POST to /demo/vote/final with valid code
Then: demo_votes has 1 row, organisation_id = election->organisation_id
```

#### Test 2 — demo_results rows created per candidate
```
Given: same setup + 1 candidate selected for 1 post
When: POST to /demo/vote/final
Then: demo_results has 1 row with vote_id, post_id, candidacy_id, organisation_id
```

#### Test 3 — results page shows vote counts after saving
```
Given: demo_votes row exists with correct election_id + org_id
When: GET /demo/result (logged-in, session has current_organisation_id)
Then: response is 200, Inertia component Demo/Result/Index, total_votes = 1
```

#### Test 4 — organisation_id falls back to election's org_id when session missing
```
Given: no current_organisation_id in session
When: save_vote() called with a demo election
Then: demo_votes.organisation_id = $election->organisation_id (not NULL)
```

### Setup pattern (following PublicDemoResultsTest pattern)
- `RefreshDatabase`
- `Organisation::withoutGlobalScopes()->create(...)` for platform org
- `Election::withoutGlobalScopes()->create([..., 'type' => 'demo', ...])` 
- `DemoPost::withoutGlobalScopes()->create([..., 'organisation_id' => ...])` 
- `DemoCandidacy::withoutGlobalScopes()->create([..., 'organisation_id' => ...])`
- `DemoCode::create([...])` for the code the voter uses
- `actingAs($user)` + `session(['current_organisation_id' => $org->id])`
- Mock `DemoElectionResolver` if needed to avoid Postgres state issues

---

## Files to Modify (implementation, after tests pass)

| File | Change |
|------|--------|
| `app/Http/Controllers/Demo/DemoVoteController.php` | Lines ~2698–2708: `session(...)` → `session(...) ?? $election->organisation_id` |
| `app/Http/Controllers/Demo/DemoVoteController.php` | Lines ~2904–2908: same fallback for `$result->organisation_id` |

No other files need changing. The results page (`DemoResultController@index`) already queries `demo_votes` with BelongsToTenant scope — once votes are saved with the correct `organisation_id`, they will appear automatically.

---

## Verification (after implementation)

```bash
# 1. Run the new tests
php artisan test --env=testing tests/Feature/Demo/OrgDemoVotePersistenceTest.php

# 2. Run the full demo test suite to catch regressions
php artisan test --env=testing tests/Feature/Demo/

# 3. Manual: log in, go to /election/demo/start, complete voting, check /demo/result
```

Expected after fix:
- `demo_votes` row has `organisation_id = <platform_org_id>` (not NULL)
- `demo_results` rows have same `organisation_id`
- `/demo/result` shows vote counts > 0
- `/public-demo/results` continues to show 0 (anonymous, no votes saved — correct per Rule 1)
