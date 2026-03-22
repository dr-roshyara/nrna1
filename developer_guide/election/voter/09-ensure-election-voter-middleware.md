# 09 — EnsureElectionVoter Middleware

**Implemented:** 2026-03-19
**Branch:** `multitenancy`
**Test file:** `tests/Feature/Middleware/EnsureElectionVoterTest.php` (7 tests, all green)

---

## What It Does

`EnsureElectionVoter` is a route middleware that guards voter-facing election routes. It sits between the `auth` middleware and your controller, and answers one question:

> "Is the authenticated user an eligible voter for *this specific election*?"

It relies on `User::isVoterInElection()`, which queries the `election_memberships` table (with a 5-minute cache). For demo elections it does nothing — those use the legacy code-based system and pass straight through.

---

## Decision Tree

```
Request arrives at guarded route
        │
        ▼
┌───────────────────┐
│ User authenticated?│── No ──▶ redirect → login
└────────┬──────────┘
         │ Yes
         ▼
┌───────────────────────┐
│ Election found in route?│── No ──▶ abort(404)
└────────┬──────────────┘
         │ Yes
         ▼
┌─────────────────────┐
│ Election type = demo? │── Yes ──▶ $next($request)  ← bypass
└────────┬────────────┘
         │ No (real election)
         ▼
┌────────────────────────────────┐
│ user->isVoterInElection($id)?  │── No ──▶ web:  redirect + error flash
│                                │         json: 403 JSON response
└────────┬───────────────────────┘
         │ Yes
         ▼
  $request->merge(['verified_election' => $election])
         │
         ▼
    $next($request)  ← proceeds to controller
```

---

## File Locations

| File | Purpose |
|------|---------|
| `app/Http/Middleware/EnsureElectionVoter.php` | Middleware class |
| `bootstrap/app.php` | Alias registration (`ensure.election.voter`) |
| `tests/Feature/Middleware/EnsureElectionVoterTest.php` | Feature tests |

---

## The Middleware Class

```php
// app/Http/Middleware/EnsureElectionVoter.php

namespace App\Http\Middleware;

use App\Models\Election;
use Closure;
use Illuminate\Http\Request;

class EnsureElectionVoter
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $election = $this->resolveElection($request);

        if (! $election) {
            abort(404, 'Election not found');
        }

        // Demo elections use the legacy code-based system — bypass.
        if ($election->type === 'demo') {
            return $next($request);
        }

        // Real election: verify the user has an active voter membership.
        if (! $user->isVoterInElection($election->id)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You are not eligible to vote in this election.',
                ], 403);
            }

            return redirect()->route('election.dashboard')
                ->with('error', 'You are not eligible to vote in this election.');
        }

        // Store the resolved election in the request for downstream controllers.
        $request->merge(['verified_election' => $election]);

        return $next($request);
    }

    private function resolveElection(Request $request): ?Election
    {
        $value = $request->route('election')
            ?? $request->route('electionId')
            ?? $request->route('id');

        if (! $value) {
            return null;
        }

        if ($value instanceof Election) {
            return $value;
        }

        return Election::withoutGlobalScopes()->find($value);
    }
}
```

### Key design notes

- **`withoutGlobalScopes()`** — The `BelongsToTenant` global scope filters by `session('current_organisation_id')`. In middleware this session key may not be set yet (it is written by `TenantContext`, which runs later in some stacks), so we bypass it and let the controller re-apply tenant scoping if needed.
- **`$request->route('election') instanceof Election`** — When Laravel route model binding resolves `{election}`, it injects the model directly. The middleware handles both the bound object and a raw UUID string so it works on any route pattern.
- **`verified_election` in request** — Storing the resolved model avoids a second DB round-trip in the controller. Retrieve it with `$request->get('verified_election')`.

---

## Registering the Alias

The alias is registered in `bootstrap/app.php` inside `->withMiddleware(fn (Middleware $m) => ...)`:

```php
$middleware->alias([
    // ... existing aliases ...
    'ensure.election.voter' => \App\Http\Middleware\EnsureElectionVoter::class,
]);
```

This was already done — do **not** add it again.

---

## Applying to Routes

### Pattern A — inline on a single route

```php
Route::get('/elections/{election}/vote', [ElectionVoteController::class, 'show'])
    ->middleware(['auth', 'verified', 'ensure.organisation', 'ensure.election.voter'])
    ->name('elections.vote');
```

### Pattern B — group (recommended for multiple voter routes)

```php
// routes/organisations.php (inside the organisations/{organisation:slug} group)

Route::prefix('/elections/{election}')
    ->middleware(['ensure.election.voter'])
    ->group(function () {
        Route::get('/vote',    [ElectionVoteController::class, 'show'])
            ->name('elections.vote');
        Route::post('/vote',   [ElectionVoteController::class, 'store'])
            ->name('elections.vote.store');
        Route::get('/ballot',  [ElectionVoteController::class, 'ballot'])
            ->name('elections.ballot');
        Route::post('/confirm',[ElectionVoteController::class, 'confirm'])
            ->name('elections.confirm');
    });
```

> **Note:** The voter management routes (`elections.voters.*`) do **not** use this middleware — they are for committee members, not voters.

### Correct middleware order

```
auth → verified → ensure.organisation → ensure.election.voter → controller
```

`ensure.organisation` ensures the user belongs to the organisation first. `ensure.election.voter` then checks election-level eligibility.

---

## Using `verified_election` in a Controller

When the middleware passes, the resolved `Election` model is available in the request. Use it instead of re-fetching:

```php
public function show(Request $request, Organisation $organisation, Election $election): Response
{
    // $election is already resolved by route model binding.
    // $request->get('verified_election') is the same object from the middleware.
    // No need to call isVoterInElection() again here — middleware handled it.

    return Inertia::render('Elections/Vote', [
        'election' => $election,
    ]);
}
```

---

## Testing Guide

### Running the tests

```bash
php artisan test tests/Feature/Middleware/EnsureElectionVoterTest.php --no-coverage
```

Expected output: **7 tests, 11 assertions, all green.**

### What is tested

| # | Test | Asserts |
|---|------|---------|
| 1 | `test_unauthenticated_user_is_redirected_to_login` | Non-authenticated request → redirect containing "login" |
| 2 | `test_nonexistent_election_returns_404` | Fake UUID in route → 404 |
| 3 | `test_demo_election_bypasses_voter_check` | Demo election, no membership → 200 |
| 4 | `test_eligible_voter_passes_through` | Real election + active membership → 200 |
| 5 | `test_ineligible_voter_is_redirected_with_error` | Real election + no membership → redirect + `error` flash |
| 6 | `test_ineligible_voter_json_request_returns_403` | JSON request + no membership → 403 with `message` key |
| 7 | `test_eligible_voter_has_verified_election_in_request` | Active membership → `verified_election` is non-null in request |

### Test setup pattern

The tests register a temporary test route in `setUp()` and rely on the same tenant-context pattern used elsewhere in this test suite:

```php
protected function setUp(): void
{
    parent::setUp();

    Election::resetPlatformOrgCache(); // clear static BelongsToTenant cache

    $this->org = Organisation::factory()->create(['type' => 'tenant']);
    session(['current_organisation_id' => $this->org->id]);

    // organisation_id MUST match $this->org — TenantContext middleware overwrites
    // session('current_organisation_id') with auth()->user()->organisation_id
    // on every request.
    $this->voter = User::factory()->create([
        'email_verified_at' => now(),
        'organisation_id'   => $this->org->id,
    ]);

    // ... attach user to org, create elections ...

    Route::middleware(['web', 'auth', 'ensure.election.voter'])
        ->get('/test-election-voter/{election}', fn () => response('OK', 200));
}
```

> **Critical:** Always set `organisation_id => $this->org->id` on the user being tested. If you omit this, `TenantContext` will overwrite the session with the platform org ID, and the controller will not find the election.

### Writing new tests

Add them to `tests/Feature/Middleware/EnsureElectionVoterTest.php`. Follow this structure:

```php
public function test_my_new_scenario(): void
{
    // Arrange — set up memberships, elections, etc.
    ElectionMembership::assignVoter($this->voter->id, $this->realElection->id);

    // Act
    $response = $this->actingAs($this->voter)
        ->withSession(['current_organisation_id' => $this->org->id])
        ->get('/test-election-voter/' . $this->realElection->id);

    // Assert
    $response->assertOk();
}
```

---

## Common Mistakes

### Forgetting `withSession()` in tests

```php
// ❌ TenantContext will overwrite session with platform org ID
$this->actingAs($this->voter)->get('/test-election-voter/' . $id);

// ✅ Pass the correct org so TenantContext writes the right value
$this->actingAs($this->voter)
    ->withSession(['current_organisation_id' => $this->org->id])
    ->get('/test-election-voter/' . $id);
```

`withSession()` provides the initial session value. `TenantContext` then overwrites it with `auth()->user()->organisation_id` — which is why the voter's `organisation_id` must also be set to `$this->org->id`.

### Applying to admin/management routes

Do **not** apply `ensure.election.voter` to committee management routes. Those use `ElectionPolicy::manage` (checked via `Gate::authorize`), not voter membership.

### Expecting 403 instead of redirect

For web requests (non-JSON), an ineligible voter gets a **redirect** with `error` flash — not a 403. A 403 is only returned for JSON/API requests. If your test hits a redirect when you expected 403, you used `->get()` instead of `->getJson()`.

---

## How It Connects to the Broader System

```
┌─────────────────────────────────────────────────────────┐
│              VOTER ELIGIBILITY CHAIN                     │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ElectionMembership::assignVoter()                       │
│    → writes row: role=voter, status=active               │
│                 │                                        │
│                 ▼                                        │
│  User::isVoterInElection($electionId)                    │
│    → queries election_memberships (cached 5 min)         │
│                 │                                        │
│                 ▼                                        │
│  EnsureElectionVoter middleware                          │
│    → calls isVoterInElection()                           │
│    → passes or blocks the request                        │
│                 │                                        │
│                 ▼                                        │
│  Controller                                              │
│    → no eligibility check needed                         │
│    → $request->get('verified_election') available        │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

Related guides:
- [03-models.md](./03-models.md) — `ElectionMembership`, `User::isVoterInElection()`
- [06-caching.md](./06-caching.md) — how the 5-minute cache works and when it is invalidated
- [07-testing.md](./07-testing.md) — TDD patterns and test suite overview
