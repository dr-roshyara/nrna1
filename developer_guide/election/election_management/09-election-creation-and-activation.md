# Election Creation & Activation

## Overview

Real elections follow a two-step lifecycle:

```
Owner/Admin creates election (status = 'planned')
    ↓
System emails all active chiefs
    ↓
Chief/Deputy sets up posts, candidates, voters
    ↓
Chief/Deputy activates election (status = 'active')
    ↓
Voting period begins
```

The two steps are intentionally owned by different roles:
- **Creation** is an organisational governance decision → `owner` or `admin` via `UserOrganisationRole`
- **Activation** is an election operations decision → `chief` or `deputy` via `ElectionOfficer`

---

## Election Creation

### Who can create

Only **organisation owners and admins** (`user_organisation_roles.role` in `['owner', 'admin']`).

Election officers (chief/deputy/commissioner) cannot create elections. They manage them after creation.

### Route

```
GET  /organisations/{slug}/elections/create  → ElectionManagementController::create()
POST /organisations/{slug}/elections         → ElectionManagementController::store()
```

Named routes: `organisations.elections.create`, `organisations.elections.store`

### Validation rules

| Field | Rules |
|-------|-------|
| `name` | required, string, max:255, unique within the organisation |
| `description` | nullable, string, max:5000 |
| `start_date` | required, date, after:today |
| `end_date` | required, date, after:start_date |
| `type` | optional; if present, must be `'real'` (submitting `type=demo` is a validation error) |

The `type` is always stored as `'real'` regardless of input. `status` is always `'planned'`.

### Slug generation

```php
$base = Str::slug($name) ?: 'election';
return $base . '-' . Str::lower(Str::random(8));
```

A random 8-character suffix is appended rather than checking for uniqueness via DB query. This avoids conflicts with the `BelongsToTenant` global scope on `Election`, which would make a uniqueness loop unreliable.

### After creation: email notification

Immediately after `Election::create()`, the controller sends `ElectionReadyForActivation` to all **active** chiefs of the organisation:

```php
$activeChiefs = ElectionOfficer::with('user')
    ->where('organisation_id', $organisation->id)
    ->where('role', 'chief')
    ->where('status', 'active')
    ->get()
    ->pluck('user')
    ->filter();

if ($activeChiefs->isNotEmpty()) {
    Notification::send($activeChiefs, new ElectionReadyForActivation($election));
}
```

Inactive chiefs receive no notification. The notification is queued (`ShouldQueue`).

### UI visibility

The "Create Election" action card on the organisation dashboard (`ActionButtons.vue`) is only shown when `canCreateElection === true`. This prop is computed in `OrganisationController::show()`:

```php
$canCreateElection = in_array($userRole, ['owner', 'admin']);
```

and passed through `Show.vue` → `ActionButtons.vue`.

---

## ElectionReadyForActivation Notification

**File:** `app/Notifications/ElectionReadyForActivation.php`

- **Channel:** `mail`
- **Queued:** yes (`ShouldQueue`)
- **Subject:** `"Election Ready for Activation: {election name}"`
- **Action button:** links to `route('elections.management', $election->id)`

The mail includes the election's scheduled start and end dates.

---

## Election Activation

### Who can activate

**Chief** or **deputy** only — authorized via the existing `manageSettings` policy method (`ElectionPolicy`).

Owners/admins cannot activate (they created the election but hand control to officers).
Commissioners cannot activate.

### Route

```
POST /elections/{election}/activate  → ElectionManagementController::activate()
```

Named route: `elections.activate`

### Pre-conditions

The `activate()` method checks:

| Condition | Response on failure |
|-----------|---------------------|
| `status === 'active'` | `back()->with('error', 'Cannot activate an election that is already active.')` |
| `status === 'completed'` | `back()->with('error', 'Cannot activate an election that is already completed.')` |

On success: status → `'active'`, flash `'success'`.

### What activation does NOT do (yet)

- No check for posts/candidates/voters — that is a future enhancement
- No `activated_at` / `activated_by` audit columns — elections table does not have these yet

### Calling from Vue

```js
const activateElection = () => {
    if (!confirm('Are you sure you want to activate this election?')) return

    isActivating.value = true
    router.post(route('elections.activate', props.election.id), {}, {
        preserveScroll: true,
        onFinish: () => { isActivating.value = false }
    })
}
```

Flash messages (`success` or `error`) are displayed via `page.props.flash`.

---

## Status Lifecycle

```
planned  ──activate()──▶  active  ──closeVoting()──▶  completed
                           ▲                                │
                           └────openVoting()───────────────┘
```

| Transition | Method | Who |
|-----------|--------|-----|
| created → planned | `store()` | owner/admin |
| planned → active | `activate()` | chief/deputy |
| active → completed | `closeVoting()` | chief/deputy |
| completed → active | `openVoting()` | chief/deputy |

---

## Policy Summary

```php
// Creation — checks UserOrganisationRole
public function create(User $user, Organisation $organisation): bool
{
    return UserOrganisationRole::where('user_id', $user->id)
        ->where('organisation_id', $organisation->id)
        ->whereIn('role', ['owner', 'admin'])
        ->exists();
}

// Activation — uses existing manageSettings (checks ElectionOfficer)
public function manageSettings(User $user, Election $election): bool
{
    return ElectionOfficer::where('user_id', $user->id)
        ->where('organisation_id', $election->organisation_id)
        ->whereIn('role', ['chief', 'deputy'])
        ->where('status', 'active')
        ->exists();
}
```

---

## Common Pitfalls

### BelongsToTenant and Election queries

`Election` has a `BelongsToTenant` global scope that filters by `session('current_organisation_id')`. Any `Election::where(...)` without `withoutGlobalScopes()` will be silently filtered. Always use:

```php
Election::withoutGlobalScopes()->where('organisation_id', $org->id)->...
```

in admin/controller contexts that need to bypass session filtering.

### Slug uniqueness loop + BelongsToTenant

Do **not** write:
```php
while (Election::where('slug', $slug)->exists()) { ... }  // ❌ infinite loop risk
```

The global scope makes `Election::where('slug', $slug)->exists()` check only elections visible to the current session org — meaning a slug from a different org could appear "free" and be assigned, causing a DB unique constraint violation. Use a random suffix instead.

### `withoutGlobalScopes()` in `activate()`

The `activate()` controller receives the `$election` via route model binding (already resolved), but the `update()` call must bypass the scope to avoid session-dependency:

```php
Election::withoutGlobalScopes()
    ->where('id', $election->id)
    ->update(['status' => 'active']);
```
