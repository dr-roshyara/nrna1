# Routes & Controller Reference

## Route Structure

**File:** `routes/election/electionRoutes.php`

All election management routes share a single prefix group:

```php
Route::middleware(['auth', 'verified'])
    ->prefix('/elections/{election}')
    ->group(function () {

        // Management dashboard — chief or deputy
        Route::get('/management', [ElectionManagementController::class, 'index'])
            ->name('elections.management')
            ->can('manageSettings', 'election');

        Route::get('/status', [ElectionManagementController::class, 'status'])
            ->name('elections.status')
            ->can('manageSettings', 'election');

        // Viewboard — any active officer
        Route::get('/viewboard', [ElectionManagementController::class, 'viewboard'])
            ->name('elections.viewboard')
            ->can('viewResults', 'election');

        // Publish/unpublish results — chief only
        Route::post('/publish', [ElectionManagementController::class, 'publish'])
            ->name('elections.publish')
            ->can('publishResults', 'election');

        Route::post('/unpublish', [ElectionManagementController::class, 'unpublish'])
            ->name('elections.unpublish')
            ->can('publishResults', 'election');

        // Voting period control — chief or deputy
        Route::post('/open-voting', [ElectionManagementController::class, 'openVoting'])
            ->name('elections.open-voting')
            ->can('manageSettings', 'election');

        Route::post('/close-voting', [ElectionManagementController::class, 'closeVoting'])
            ->name('elections.close-voting')
            ->can('manageSettings', 'election');

        // Bulk voter management — chief or deputy
        Route::post('/bulk-approve-voters', [ElectionManagementController::class, 'bulkApproveVoters'])
            ->name('elections.bulk-approve-voters')
            ->can('manageVoters', 'election');

        Route::post('/bulk-disapprove-voters', [ElectionManagementController::class, 'bulkDisapproveVoters'])
            ->name('elections.bulk-disapprove-voters')
            ->can('manageVoters', 'election');

        // Activate a planned election — chief or deputy
        Route::post('/activate', [ElectionManagementController::class, 'activate'])
            ->name('elections.activate');
    });
```

### Election Creation Routes

Defined in `routes/organisations.php`, inside the existing `organisations/{organisation:slug}` prefix group:

```php
// GET  /organisations/{slug}/elections/create
Route::get('/elections/create', [ElectionManagementController::class, 'create'])
    ->name('organisations.elections.create');

// POST /organisations/{slug}/elections
Route::post('/elections', [ElectionManagementController::class, 'store'])
    ->name('organisations.elections.store');
```

These routes use `auth`, `verified`, and `ensure.organisation` middleware from the parent group. Authorization is handled inside the controller via `$this->authorize('create', [Election::class, $organisation])`.

### Why `auth` not `auth:sanctum`

`auth:sanctum` is the **mobile API guard** — it validates Bearer tokens, not session cookies. Inertia pages use session-based authentication. Using `auth:sanctum` on Inertia routes breaks all session users.

### Why no `ensure.organisation` middleware

`ensure.organisation` looks for `{organisation}` or `{slug}` in the route parameters. These routes only have `{election}`. Org isolation is handled by `ElectionPolicy` checking `election->organisation_id` against the officer's `organisation_id`.

---

## Route Name Reference

| Name | Method | URL | Policy |
|------|--------|-----|--------|
| `organisations.elections.create` | GET | `/organisations/{slug}/elections/create` | create (owner/admin) |
| `organisations.elections.store` | POST | `/organisations/{slug}/elections` | create (owner/admin) |
| `elections.management` | GET | `/elections/{election}/management` | manageSettings |
| `elections.status` | GET | `/elections/{election}/status` | manageSettings |
| `elections.viewboard` | GET | `/elections/{election}/viewboard` | viewResults |
| `elections.activate` | POST | `/elections/{election}/activate` | manageSettings (chief/deputy) |
| `elections.publish` | POST | `/elections/{election}/publish` | publishResults |
| `elections.unpublish` | POST | `/elections/{election}/unpublish` | publishResults |
| `elections.open-voting` | POST | `/elections/{election}/open-voting` | manageSettings |
| `elections.close-voting` | POST | `/elections/{election}/close-voting` | manageSettings |
| `elections.bulk-approve-voters` | POST | `/elections/{election}/bulk-approve-voters` | manageVoters |
| `elections.bulk-disapprove-voters` | POST | `/elections/{election}/bulk-disapprove-voters` | manageVoters |

---

## OrganisationController::show() — Role Props

**File:** `app/Http/Controllers/OrganisationController.php`

The organisation show page now computes and passes 9 permission flags to the Inertia view. These drive the role-based section visibility in `Show.vue`.

```php
// From UserOrganisationRole
$canManage         = in_array($userRole, ['owner', 'admin']);
$canCreateElection = in_array($userRole, ['owner', 'admin']);

// From ElectionOfficer (status = 'active' only)
$officer        = ElectionOfficer::where('user_id', $user->id)
    ->where('organisation_id', $organisation->id)
    ->where('status', 'active')
    ->first();

$isOfficer      = !is_null($officer);
$isChief        = $isOfficer && $officer->role === 'chief';
$isDeputy       = $isOfficer && $officer->role === 'deputy';
$isCommissioner = $isOfficer && $officer->role === 'commissioner';

$canActivateElection = $isChief || $isDeputy;
$canManageVoters     = $isChief || $isDeputy;
$canPublishResults   = $isChief;
```

Passed as Inertia props: `canManage`, `canCreateElection`, `canActivateElection`, `canManageVoters`, `canPublishResults`, `userRole`, `isOfficer`, `isChief`, `isDeputy`, `isCommissioner`.

See `10-role-based-organisation-dashboard.md` for the full section visibility matrix.

---

## Controller Methods

**File:** `app/Http/Controllers/Election/ElectionManagementController.php`

### `create(Organisation $organisation): Response`
Shows the election creation form. Owner/admin only.

```php
public function create(Organisation $organisation): Response
{
    $this->authorize('create', [Election::class, $organisation]);
    return Inertia::render('Organisations/Elections/Create', [
        'organisation' => $organisation,
    ]);
}
```

### `store(Request $request, Organisation $organisation): RedirectResponse`
Validates and persists the new election. Always creates `type='real'`, `status='planned'`. After creation, sends `ElectionReadyForActivation` notification to all active chiefs.

```php
// Validation rules
'name'        => ['required', 'string', 'max:255',
                  Rule::unique('elections')->where('organisation_id', $organisation->id)],
'description' => ['nullable', 'string', 'max:5000'],
'start_date'  => ['required', 'date', 'after:today'],
'end_date'    => ['required', 'date', 'after:start_date'],
'type'        => ['sometimes', 'in:real'],  // submitting type=demo → validation error
```

**Slug generation:** Uses `Str::slug($name) . '-' . Str::lower(Str::random(8))`. The random suffix avoids the need for a DB-checking loop, which would conflict with `BelongsToTenant` global scope.

**Notification:** After `Election::create()`, queries `ElectionOfficer` for active chiefs of the org and calls `Notification::send()`. Inactive chiefs are excluded.

### `activate(Election $election): RedirectResponse`
Transitions a `planned` election to `active`. Chief or deputy only (uses existing `manageSettings` policy).

Pre-conditions checked:
1. Status must not already be `active` → error flash
2. Status must not be `completed` → error flash

```php
public function activate(Election $election): RedirectResponse
{
    $this->authorize('manageSettings', $election);

    if ($election->status === 'active') {
        return back()->with('error', 'Cannot activate an election that is already active.');
    }
    if ($election->status === 'completed') {
        return back()->with('error', 'Cannot activate an election that is already completed.');
    }

    Election::withoutGlobalScopes()
        ->where('id', $election->id)
        ->update(['status' => 'active']);

    return back()->with('success', 'Election activated successfully! Voting period is now open.');
}
```

> **Why `withoutGlobalScopes()` in activate?** The `BelongsToTenant` scope on `Election` requires `session('current_organisation_id')` to match. During a POST the session context is set, but using `withoutGlobalScopes()` on the update is explicit and safe — we already have the `Election` instance from route model binding, so org isolation is already enforced.

### `index(Election $election): Response`
Management dashboard. Loads the `Election/Management` Inertia page.

```php
public function index(Election $election): Response
{
    $election->load(['organisation']);
    return Inertia::render('Election/Management', [
        'election'   => $election,
        'stats'      => $election->voter_stats,  // from ElectionMembership cache
        'canPublish' => auth()->user()->can('publishResults', $election),
    ]);
}
```

**`canPublish`** is passed so the Vue component can conditionally show the publish button without making another server round-trip.

### `viewboard(Election $election): Response`
Read-only view for all active officers (including commissioners).

```php
return Inertia::render('Election/Viewboard', [
    'election' => $election,
    'stats'    => $election->voter_stats,
    'readonly' => true,
]);
```

### `publish(Election $election): RedirectResponse`
Sets `results_published = true`. Chief only.

```php
$election->update(['results_published' => true]);
return back()->with('success', 'Results published.');
```

### `unpublish(Election $election): RedirectResponse`
Sets `results_published = false`. Chief only.

### `openVoting(Election $election): RedirectResponse`
Sets `status = 'active'` and `is_active = true`.

```php
$election->update(['status' => 'active', 'is_active' => true]);
return back()->with('success', 'Voting period opened.');
```

### `closeVoting(Election $election): RedirectResponse`
Sets `status = 'completed'` and `is_active = false`.

```php
$election->update(['status' => 'completed', 'is_active' => false]);
return back()->with('success', 'Voting period closed.');
```

### `bulkApproveVoters(Request $request, Election $election): RedirectResponse`
Sets `status = 'active'` on the provided `election_memberships` IDs.

```php
$validated = $request->validate([
    'voter_ids'   => 'required|array|min:1|max:1000',
    'voter_ids.*' => [
        'uuid',
        Rule::exists('election_memberships', 'id')
            ->where('election_id', $election->id),
    ],
]);

DB::transaction(fn() =>
    ElectionMembership::whereIn('id', $validated['voter_ids'])
        ->update(['status' => 'active'])
);
```

> **Note:** The controller requires explicit `voter_ids[]`. There is no "approve all" shortcut at the API level — the frontend must send specific IDs. This prevents accidental mass-approval. The voter list UI (pending implementation) will provide checkboxes to collect these IDs.

### `bulkDisapproveVoters(Request $request, Election $election): RedirectResponse`
Same as above but sets `status = 'inactive'`.

### `status(Election $election): JsonResponse`
Returns election metadata + voter stats as JSON. Useful for status polling.

```php
return response()->json([
    'election' => $election->only(['id', 'name', 'status', 'is_active', 'results_published']),
    'stats'    => $election->voter_stats,
]);
```

---

## Stats: `voter_stats` vs `getStatistics()`

The controller uses `$election->voter_stats` (an accessor on the `Election` model backed by `ElectionMembership`) rather than `$election->getStatistics()` (which queries the legacy `voter_registrations` table).

`voter_stats` returns:
```php
[
    'total_memberships' => int,
    'active_voters'     => int,
    'eligible_voters'   => int,
    'by_status' => [
        'active' => int, 'inactive' => int,
        'invited' => int, 'removed' => int,
    ],
    'by_role' => [
        'voter' => int, 'candidate' => int,
        'observer' => int, 'admin' => int,
    ],
]
```

This is cached for 5 minutes and invalidated by `ElectionMembership` booted hooks.
