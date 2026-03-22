# Priority 3: Election-Based Dashboard Routing

**File:** `app/Services/DashboardResolver.php`
**Related:** `app/Models/User.php`, `app/Http/Controllers/Election/ElectionManagementController.php`
**Tests:** `tests/Feature/Services/DashboardResolverElectionPriorityTest.php`

---

## What Was Built

Priority 3 in `DashboardResolver::resolve()` was broken. It had a boolean check (`hasActiveElection()`) with no count-based branching. It redirected all users with any active election to `election.dashboard` — ignoring the case where a user has multiple active elections (where they should choose, not be auto-routed).

The fix introduced count-based election routing:

| Eligible election count | Destination              | Why                                              |
|-------------------------|--------------------------|--------------------------------------------------|
| 0                       | Skip → Priority 4/5      | No active election for this user                 |
| 1                       | `election.dashboard`     | Auto-route directly to the election ballot page  |
| 2+                      | `organisations.show`     | User must choose which election to participate in|

---

## The Priority 3 Code

```php
// app/Services/DashboardResolver.php — Priority 3

$eligibleCount = $user->countActiveElections();

if ($eligibleCount > 0) {
    $activeElection = $user->getActiveElection();

    if ($activeElection) {
        $electionOrg = \App\Models\Organisation::find($activeElection->organisation_id);

        if ($electionOrg) {
            try {
                $this->tenantContext->setContext($user, $electionOrg);
            } catch (\RuntimeException $e) {
                Log::warning('DashboardResolver: TenantContext failed in Priority 3', [
                    'user_id' => $user->id,
                    'error'   => $e->getMessage(),
                ]);
            }

            if ($eligibleCount === 1) {
                $targetUrl = route('election.dashboard');
                $this->cacheResolution($user, $targetUrl);
                return redirect()->to($targetUrl);
            }

            // 2+ elections: let user pick from org page
            $targetUrl = route('organisations.show', $electionOrg->slug);
            $this->cacheResolution($user, $targetUrl);
            return redirect()->to($targetUrl);
        }
    }
}
```

**Why `setContext()` is called before the redirect:**
`election.dashboard` (`ElectionManagementController@dashboard`) reads `session('current_organisation_id')` to find the active election. If this session key is not set, the controller falls through to calling `DashboardResolver::resolve()` again, landing the user on the org page. `TenantContext::setContext()` sets that session key.

---

## The Two Key User Model Methods

Both methods live in `app/Models/User.php`.

### `getActiveElection(): ?Election`

Returns the first eligible real election for this user, or `null`.

```php
public function getActiveElection(): ?Election
{
    $orgIds = $this->organisations()
        ->where('type', 'tenant')
        ->pluck('organisations.id')
        ->toArray();

    if (empty($orgIds)) return null;

    return Election::withoutGlobalScopes()
        ->whereIn('organisation_id', $orgIds)
        ->where('status', 'active')
        ->where('type', 'real')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->whereDoesntHave('voterSlugs', function ($query) {
            $query->where('user_id', $this->id)->where('status', 'voted');
        })
        ->orderBy('start_date')
        ->first();
}
```

### `countActiveElections(): int`

Same filters as `getActiveElection()`, returns a count. Used by Priority 3 for branching.

```php
public function countActiveElections(): int
{
    $orgIds = $this->organisations()
        ->where('type', 'tenant')
        ->pluck('organisations.id')
        ->toArray();

    if (empty($orgIds)) return 0;

    // No date range filter — an election marked status='active' counts for routing
    // even if its voting window hasn't opened yet. This prevents auto-routing a
    // user to a ballot they cannot vote on yet when another election is also active.
    return Election::withoutGlobalScopes()
        ->whereIn('organisation_id', $orgIds)
        ->where('status', 'active')
        ->where('type', 'real')
        ->whereDoesntHave('voterSlugs', function ($query) {
            $query->where('user_id', $this->id)->where('status', 'voted');
        })
        ->count();
}
```

---

## Critical: Why `withoutGlobalScopes()` Is Required

The `Election` model uses the `BelongsToTenant` global scope, which filters queries by `session('current_organisation_id')`. At login time, that session key does not yet exist. Without `withoutGlobalScopes()`, every `Election::where(...)` query returns zero results — even when the user has a perfectly valid active election.

**Wrong (returns 0 at login time):**
```php
Election::whereIn('organisation_id', $orgIds)->count(); // filtered by null session
```

**Correct:**
```php
Election::withoutGlobalScopes()->whereIn('organisation_id', $orgIds)->count();
```

This was the root cause of the original Priority 3 being bypassed.

---

## Eligibility Rules

An election is counted as eligible for a user if **all** of these are true:

| Condition                      | Column / Relationship                        |
|--------------------------------|----------------------------------------------|
| Election belongs to user's org | `whereIn('organisation_id', $userOrgIds)`    |
| Election is active             | `status = 'active'`                          |
| Election is a real election    | `type = 'real'` (excludes `demo`)            |
| User has not already voted     | No `voter_slugs` row with `status = 'voted'` |

**Date range is intentionally excluded from `countActiveElections()`.** If an admin marks an election as `active` before its voting window opens, it still counts for routing. This prevents silently routing a user to a ballot they cannot vote on yet. The date range check remains in `getActiveElection()` (used to resolve which election to show on `ElectionPage`).

> **Note:** Voting history is tracked via `voter_slugs.status = 'voted'`, NOT via `election_memberships.has_voted` (that column does not exist).

---

## How `election.dashboard` Renders `ElectionPage`

`GET /election` → `ElectionManagementController@dashboard`

```
Priority 3:
  setContext() → session('current_organisation_id') = org_id
  redirect to /election
      ↓
ElectionManagementController@dashboard:
  $orgId = session('current_organisation_id')    ← set by setContext()
  if ($orgId) {
      $activeElection = Election::withoutGlobalScopes()
          ->where('organisation_id', $orgId)
          ->where('type', 'real')
          ->where('status', 'active')
          ->first();
      if ($activeElection) {
          return Inertia::render('Election/ElectionPage', [...]);   ✅
      }
  }
  // If session not set or no election found → calls resolve() again → org page
```

The session key set by `TenantContext::setContext()` is what enables the controller to find the election on the redirected request.

---

## The `onboarded_at` Migration

During test suite setup, tests failed because `User::factory()` sets `onboarded_at` but no migration existed for that column. The column existed in the production database from a previous manual migration but had no file.

**Migration created:** `database/migrations/2026_03_21_235637_add_onboarded_at_to_users_table.php`

```php
Schema::table('users', function (Blueprint $table) {
    if (! Schema::hasColumn('users', 'onboarded_at')) {
        $table->timestamp('onboarded_at')->nullable()->after('remember_token');
    }
});
```

Uses `Schema::hasColumn()` guard to be idempotent (safe to run on both fresh and existing databases).

---

## Test Suite

**File:** `tests/Feature/Services/DashboardResolverElectionPriorityTest.php`

Uses `DatabaseTransactions` (rolls back after each test, no persistent state).

### Unit-level tests (`countActiveElections`)

| Test | Scenario | Expected |
|------|----------|----------|
| `user_with_no_active_elections_counts_zero` | Org member, no elections | `countActiveElections() = 0` |
| `user_with_one_active_election_counts_one` | One active real election | `countActiveElections() = 1` |
| `user_who_already_voted_counts_zero` | Has VoterSlug `status=voted` | `countActiveElections() = 0` |
| `future_election_not_counted` | `start_date` is 5 days away | `countActiveElections() = 0` |
| `demo_election_not_counted` | `type = demo` | `countActiveElections() = 0` |

### Integration tests (full `DashboardResolver::resolve()`)

| Test | Scenario | Expected URL |
|------|----------|--------------|
| `user_with_no_eligible_elections_skips_priority_3` | 0 elections | org page (Priority 5) |
| `user_with_one_eligible_election_redirects_to_election_dashboard` | 1 election | `/election` |
| `user_with_two_eligible_elections_redirects_to_org_show` | 2 elections | `/organisations/{slug}` |
| `user_who_already_voted_skips_priority_3` | Voted, 0 eligible | org page (Priority 5) |

### Key test helpers

```php
// Creates a user with email verified and onboarded (passes Priority 1)
private function makeVerifiedUser(): User
{
    return User::factory()->create([
        'email_verified_at' => now(),
        'onboarded_at'      => now(),
    ]);
}

// Attaches user to org via pivot table (no 'assigned_at' — column doesn't exist)
private function attachToOrg(User $user, Organisation $org, string $role = 'member'): void
{
    DB::table('user_organisation_roles')->insert([
        'id'              => Str::uuid(),
        'user_id'         => $user->id,
        'organisation_id' => $org->id,
        'role'            => $role,
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);
}

// Creates an election in the current voting window
private function makeActiveElection(Organisation $org): Election
{
    return Election::withoutGlobalScopes()->create([
        'organisation_id' => $org->id,
        'type'            => 'real',
        'status'          => 'active',
        'name'            => 'Test Election ' . uniqid(),
        'slug'            => 'test-election-' . uniqid(),
        'start_date'      => now()->subDay(),
        'end_date'        => now()->addDay(),
    ]);
}

// Records a completed vote for the user (disqualifies them from countActiveElections)
private function markUserAsVoted(User $user, Election $election): void
{
    DB::table('voter_slugs')->insert([
        'id'              => Str::uuid(),
        'user_id'         => $user->id,
        'election_id'     => $election->id,
        'organisation_id' => $election->organisation_id,   // required, not nullable
        'slug'            => 'voted-slug-' . uniqid(),
        'status'          => 'voted',
        'has_voted'       => 1,
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);
}
```

> `organisation_id` is required in `voter_slugs` and is **not nullable**. Always include it when inserting test rows.

---

## Debugging Priority 3

### User lands on org page instead of ElectionPage

Check the logs at `storage/logs/laravel.log`:

```
PRIORITY 3 HIT: Eligible elections found  → fired ✅
PRIORITY 3: Single election → election.dashboard  → fired ✅
DASHBOARD_DEBUG { orgId: "...", ... }  → session was set ✅
DASHBOARD_DEBUG2 { activeElection: null }  → election NOT found in dashboard controller ❌
```

If `activeElection` is `null` in `DASHBOARD_DEBUG2`, check the election status:

```bash
php artisan tinker --execute="
App\Models\Election::withoutGlobalScopes()
    ->where('organisation_id', '<org_id>')
    ->where('type', 'real')
    ->get(['name', 'status', 'start_date', 'end_date'])
    ->each(fn(\$e) => print(\$e->name . ' | ' . \$e->status . PHP_EOL));
"
```

The most common cause: **the election was deactivated** (status changed to `planned` or `completed`). Priority 3 fires on login but then the dashboard controller re-queries at the moment of the follow-up GET request and finds no active election.

### `countActiveElections()` returns 0 unexpectedly

```bash
php artisan tinker --execute="
\$user = App\Models\User::where('email', 'you@example.com')->first();
echo \$user->countActiveElections();
// Also check what orgs the user belongs to
\$user->organisations()->get(['slug', 'type'])->each(fn(\$o) => print(\$o->slug . ' | ' . \$o->type . PHP_EOL));
"
```

Common causes:
- User belongs to a `platform` org (not `tenant`) — filtered by `->where('type', 'tenant')`
- Election `type` is `demo` — filtered by `->where('type', 'real')`
- Election `start_date` is in the future or `end_date` is in the past
- User has a `voter_slugs` row with `status = 'voted'` for that election

### `TenantContext::setContext()` throws (caught silently)

Priority 3 wraps `setContext()` in a try-catch. If it throws, the session key is never set and `election.dashboard` falls through to calling `resolve()` again — landing the user on the org page.

`setContext()` throws a `RuntimeException` if `$user->belongsToOrganisation($org->id)` returns `false`. Verify membership:

```bash
php artisan tinker --execute="
\$user = App\Models\User::where('email', 'you@example.com')->first();
\$org  = App\Models\Organisation::where('slug', 'your-org')->first();
echo \$user->belongsToOrganisation(\$org->id) ? 'TRUE' : 'FALSE';
"
```

---

## Resolution Cache

`DashboardResolver` caches the resolved URL in the application cache (key: `login_routing_cache_{user_id}`) for 5 minutes (configurable via `login-routing.cache.dashboard_resolution_ttl`).

If the cache holds a stale URL (e.g., org page from before an election was activated), the user will be sent to the old destination until the TTL expires or the session goes stale.

To force a fresh resolution during debugging, flush the cache:

```bash
php artisan cache:clear
```

Or wait 5 minutes for the TTL to expire.

---

## Interaction with Other Priorities

Priority 3 sits between Priority 2 (active voting session) and Priority 4 (missing organisation). The election officer role has no effect on Priority 3 — it fires for any user (member, officer, admin) who has an eligible unvoted real election in their organisation.

```
P1: Email not verified → /email/verify
P2: Mid-vote (active VoterSlug) → resume voting
P3: Active real election (this guide) → /election or /organisations/{slug}
P4: No organisations → handleMissingOrganisation()
P5: Has org, no election → /organisations/{slug}
P6–P9: Role-based and fallback routing
```
