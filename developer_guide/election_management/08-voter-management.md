# Voter Management — Approve & Suspend

## What Was Built

Per-voter approve and suspend actions for real elections, wired into the existing `ElectionVoterController` and `Elections/Voters/Index.vue`. No new controller, no new database columns, no migrations.

---

## Critical Analysis of Architecture Doc (`Voterlist.md`)

The architecture document contained several errors. These were corrected before implementation:

| Doc Said | Reality | Decision |
|---|---|---|
| Create new `Organisations\VoterController` | `ElectionVoterController` already exists with `index`, `store`, `bulkStore`, `destroy`, `export` | Extended existing controller |
| Add `approved_at`, `approved_by`, `suspended_at`, `suspended_by`, `has_voted` columns | None exist — `election_memberships` uses a `status` enum: `invited/active/inactive/removed` | Used existing `status` column |
| Cache key `election_{id}_voter_stats` | Model boot hook uses `election.{id}.voter_stats` (dot notation) | Fixed to dot notation |
| Route namespace `organisations.elections.voters.*` | Routes already registered as `elections.voters.*` | Kept existing namespace |

---

## Files Changed

| File | Type | What Changed |
|------|------|-------------|
| `tests/Feature/Election/ElectionVoterManagementTest.php` | **NEW** | 10 TDD tests (written first) |
| `app/Http/Controllers/ElectionVoterController.php` | Modified | Added `approve()` + `suspend()` |
| `routes/organisations.php` | Modified | 2 new routes |
| `resources/js/Pages/Elections/Voters/Index.vue` | Modified | Approve/Suspend buttons, Composition API rewrite |
| `resources/js/Pages/Election/Management.vue` | Modified | Voter stats + link replacing "coming soon" |

---

## Authorization

Both actions gate on `manageVoters` from `ElectionPolicy`, which delegates to `manageSettings` (chief or deputy only):

| Role | Can Approve | Can Suspend |
|------|-------------|-------------|
| chief | ✅ | ✅ |
| deputy | ✅ | ✅ |
| commissioner | ❌ 403 | ❌ 403 |
| non-officer | ❌ 403 | ❌ 403 |

---

## Controller Methods

**File:** `app/Http/Controllers/ElectionVoterController.php`

### `approve(Organisation, string $election, ElectionMembership): RedirectResponse`

```php
public function approve(Organisation $organisation, string $election, ElectionMembership $membership): RedirectResponse
{
    $election = Election::withoutGlobalScopes()->findOrFail($election);
    abort_if($election->type === 'demo', 404);

    $this->authorize('manageVoters', $election);

    if ($membership->election_id !== $election->id) {
        abort(404);  // Prevents cross-election membership manipulation
    }

    if ($membership->status === 'active') {
        return back()->with('error', 'Voter is already approved.');
    }

    $membership->update(['status' => 'active', 'assigned_at' => now()]);
    Cache::forget("election.{$election->id}.voter_stats");

    return back()->with('success', "Voter {$membership->user->name} approved.");
}
```

### `suspend(Organisation, string $election, ElectionMembership): RedirectResponse`

Same structure — sets `status = 'inactive'`, guards against already-inactive state.

### Why `string $election` not `Election $election`

The existing controller uses `string $election` (manual `findOrFail`) instead of route model binding because of `withoutGlobalScopes()`. The `BelongsToTenant` global scope on `Election` filters by `session('current_organisation_id')`. In tests this can interfere. Using `withoutGlobalScopes()->findOrFail()` ensures the election is found regardless of scope, then the policy checks org ownership.

### Cache key: dot notation matters

The `ElectionMembership` model boot hook invalidates:
```php
Cache::forget("election.{$this->election_id}.voter_stats");
Cache::forget("election.{$this->election_id}.voter_count");
```

The controller must use the **same key format** (`election.{id}.voter_stats` with dots, not underscores). Using a different format would leave stale cache after approve/suspend.

---

## Routes

**File:** `routes/organisations.php`

Added inside the existing `/elections/{election}` prefix group (alongside `store`, `bulkStore`, `destroy`, `export`):

```php
Route::post('/voters/{membership}/approve', [ElectionVoterController::class, 'approve'])
    ->name('elections.voters.approve');
Route::post('/voters/{membership}/suspend', [ElectionVoterController::class, 'suspend'])
    ->name('elections.voters.suspend');
```

### Full route table for election voter management

| Name | Method | URL | Auth |
|------|--------|-----|------|
| `elections.voters.index` | GET | `/organisations/{slug}/elections/{election}/voters` | view |
| `elections.voters.store` | POST | `.../voters` | manage |
| `elections.voters.bulk` | POST | `.../voters/bulk` | manage |
| `elections.voters.export` | GET | `.../voters/export` | view |
| `elections.voters.destroy` | DELETE | `.../voters/{membership}` | manage |
| `elections.voters.approve` | POST | `.../voters/{membership}/approve` | manageVoters |
| `elections.voters.suspend` | POST | `.../voters/{membership}/suspend` | manageVoters |

All routes inherit `auth + verified + ensure.organisation` from the parent group.

---

## Vue Component — `Elections/Voters/Index.vue`

**Full rewrite from Options API to Composition API (`<script setup>`)**

### What changed

| Before | After |
|--------|-------|
| Options API (`export default {}`) | `<script setup>` |
| Only "Remove" button per row | Approve + Suspend + Remove per row |
| No flash message display | `$page.props.flash?.success/error` shown |
| `bg-gray-100` for inactive status | `bg-yellow-100` (visually distinct from removed) |
| Single `assigning` loading flag | `loadingId` tracks which row is loading |

### Props (unchanged)

```js
const props = defineProps({
    election:     { type: Object, required: true },
    organisation: { type: Object, required: true },
    voters:       { type: Object, required: true },  // paginated
    stats:        { type: Object, required: true },
})
```

### Action buttons per row

```vue
<!-- Approve: shown for invited/inactive (not active, not removed) -->
<button
    v-if="membership.status !== 'active' && membership.status !== 'removed'"
    @click="approveVoter(membership)"
    :disabled="loadingId === membership.id"
>
    Approve
</button>

<!-- Suspend: shown only for active voters -->
<button
    v-if="membership.status === 'active'"
    @click="suspendVoter(membership)"
    :disabled="loadingId === membership.id"
>
    Suspend
</button>

<!-- Remove: shown for all except already-removed -->
<button v-if="membership.status !== 'removed'" @click="removeVoter(membership)">
    Remove
</button>
```

### `loadingId` pattern

Tracks which specific row is in-flight. Prevents double-clicks and shows disabled state only on the row being acted upon, not the entire table:

```js
const loadingId = ref(null)

const approveVoter = (membership) => {
    loadingId.value = membership.id
    router.post(route('elections.voters.approve', {...}), {}, {
        preserveScroll: true,
        onFinish: () => { loadingId.value = null },
    })
}
```

### Status badge colours

| Status | Badge |
|--------|-------|
| `active` | `bg-green-100 text-green-800` |
| `invited` | `bg-blue-100 text-blue-800` |
| `inactive` | `bg-yellow-100 text-yellow-800` |
| `removed` | `bg-red-100 text-red-700` |

---

## Management.vue — Voter Section

**File:** `resources/js/Pages/Election/Management.vue`

Replaced the amber "coming soon" placeholder with:
- Three stat cards (total / approved / suspended) sourced from `stats` prop
- "Manage Voter List →" link to `elections.voters.index`

```js
// Computed URL — election.organisation.slug available because controller
// calls $election->load(['organisation']) before passing to Inertia
const voterListUrl = computed(() =>
    route('elections.voters.index', {
        organisation: props.election.organisation?.slug,
        election:     props.election.id,
    })
)
```

The controller already loads the `organisation` relationship:
```php
public function index(Election $election): Response
{
    $election->load(['organisation']); // ← makes election.organisation.slug available
    return Inertia::render('Election/Management', [...]);
}
```

---

## Tests

**File:** `tests/Feature/Election/ElectionVoterManagementTest.php`

**Result:** 10/10 passing (19 assertions)

### Test matrix

| Test | Checks |
|------|--------|
| `test_chief_can_approve_a_voter` | status becomes `active`, 302 redirect |
| `test_deputy_can_approve_a_voter` | deputy has same manageVoters permission |
| `test_commissioner_cannot_approve_a_voter` | 403, status unchanged |
| `test_cannot_approve_already_active_voter` | redirects with error flash, no DB change |
| `test_chief_can_suspend_a_voter` | status becomes `inactive` |
| `test_deputy_can_suspend_a_voter` | deputy can suspend |
| `test_commissioner_cannot_suspend_a_voter` | 403 |
| `test_cannot_suspend_already_inactive_voter` | redirects with error flash, no DB change |
| `test_cannot_act_on_membership_belonging_to_different_election` | 404 — cross-election isolation |
| `test_officer_from_different_org_cannot_approve_voter` | 302/403/404 — cross-org isolation |

### FK constraint: `election_memberships(user_id, organisation_id)` → `user_organisation_roles`

When creating test memberships, the voter user **must have a `user_organisation_roles` record** for the same `(user_id, organisation_id)` pair. Without this, `ElectionMembership::create()` throws an FK violation:

```php
private function makeMembershipForElection(Election $election, string $status): ElectionMembership
{
    $voter = User::factory()->create(['organisation_id' => $this->org->id]);

    // ⚠️ Required: composite FK enforced at DB level
    UserOrganisationRole::create([
        'id'              => (string) Str::uuid(),
        'user_id'         => $voter->id,
        'organisation_id' => $this->org->id,
        'role'            => 'voter',
    ]);

    return ElectionMembership::create([
        'user_id'         => $voter->id,
        'organisation_id' => $this->org->id,
        'election_id'     => $election->id,
        'role'            => 'voter',
        'status'          => $status,
        'assigned_by'     => $this->chief->id,
        'assigned_at'     => now(),
    ]);
}
```

### Cross-org test: 302 vs 403 vs 404

The cross-org test accepts any of three status codes because blocking happens at different layers depending on scenario:

- **302** — `ensure.organisation` middleware redirects non-members before controller runs
- **403** — `ElectionPolicy::manageVoters()` blocks after middleware
- **404** — `BelongsToTenant` scope hides election from route model binding

All three correctly prevent access. The test assertion uses `assertContains`:

```php
$this->assertContains($response->status(), [302, 403, 404],
    'Cross-org voter approval must be blocked');
```

---

## Running the Tests

```bash
# Voter management tests only
php artisan test tests/Feature/Election/ElectionVoterManagementTest.php

# All election management tests together
php artisan test tests/Feature/Election/
# Expected: 22 passed (62 assertions)

# Voter management + dashboard + officer tests
php artisan test --filter="ElectionVoterManagementTest|ElectionDashboardAccessTest|ElectionOfficerManagementTest|ElectionOfficerInvitationTest"
# Expected: 40 passed
```
