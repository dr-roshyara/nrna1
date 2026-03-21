# ElectionPolicy — Authorization Reference

## Registration

`ElectionPolicy` is registered in `AuthServiceProvider`:

```php
protected $policies = [
    \App\Models\Election::class        => \App\Policies\ElectionPolicy::class,
    \App\Models\ElectionOfficer::class => \App\Policies\ElectionOfficerPolicy::class,
];
```

---

## Policy Methods

**File:** `app/Policies/ElectionPolicy.php`

All methods check the `election_officers` table directly. No Spatie roles, no global Gates.

### `view(User $user, Election $election): bool`
Any active officer for this organisation may view voter management pages.
```php
return ElectionOfficer::where('user_id', $user->id)
    ->where('organisation_id', $election->organisation_id)
    ->where('status', 'active')
    ->exists();
```

### `viewResults(User $user, Election $election): bool`
Any active officer may view results. Delegates to `view()`.

### `manageSettings(User $user, Election $election): bool`
Chief or deputy only — manage election settings, control voting period, manage voters.
```php
return ElectionOfficer::where('user_id', $user->id)
    ->where('organisation_id', $election->organisation_id)
    ->whereIn('role', ['chief', 'deputy'])
    ->where('status', 'active')
    ->exists();
```

### `publishResults(User $user, Election $election): bool`
Chief only — publish or unpublish results.
```php
return ElectionOfficer::where('user_id', $user->id)
    ->where('organisation_id', $election->organisation_id)
    ->where('role', 'chief')
    ->where('status', 'active')
    ->exists();
```

### `manageVoters(User $user, Election $election): bool`
Chief or deputy — bulk voter operations. Delegates to `manageSettings()`.

### `create(User $user, Organisation $organisation): bool`
Organisation owner or admin may create a new election. Receives `Organisation` (not `Election`) because no election exists yet.

```php
// Note: checks UserOrganisationRole, NOT ElectionOfficer
return UserOrganisationRole::where('user_id', $user->id)
    ->where('organisation_id', $organisation->id)
    ->whereIn('role', ['owner', 'admin'])
    ->exists();
```

> **Why `UserOrganisationRole` here?** Election creation is an organisational governance decision (who can start an election). Election *management* (activation, voter control) is an election officer decision. These are intentionally separate permission sources.

---

## Usage in Routes

Routes use `.can()` which calls the policy via route model binding:

```php
Route::middleware(['auth', 'verified'])
    ->prefix('/elections/{election}')
    ->group(function () {

        Route::get('/management', [ElectionManagementController::class, 'index'])
            ->name('elections.management')
            ->can('manageSettings', 'election');   // ← chief or deputy only

        Route::get('/viewboard', [ElectionManagementController::class, 'viewboard'])
            ->name('elections.viewboard')
            ->can('viewResults', 'election');       // ← any active officer

        Route::post('/publish', [ElectionManagementController::class, 'publish'])
            ->name('elections.publish')
            ->can('publishResults', 'election');    // ← chief only
    });
```

The string `'election'` refers to the `{election}` route parameter. Laravel resolves this via implicit model binding.

---

## Usage in Controllers

The route-level `.can()` already blocks unauthorized requests before the controller runs. Controllers also call `$this->authorize()` as a defence-in-depth measure:

```php
public function publish(Election $election): RedirectResponse
{
    // Route already blocked non-chiefs, but authorize() here is defence-in-depth
    $this->authorize('publishResults', $election);
    $election->update(['results_published' => true]);
    return back()->with('success', 'Results published.');
}
```

---

## Tenant Isolation

The policy checks `election->organisation_id` against `election_officers.organisation_id`. This means:
- An officer of Org A **cannot** access Org B's election, even if they have the same role in Org A.
- Additionally, `BelongsToTenant` global scope on `Election` filters by `session('current_organisation_id')`, so route model binding itself will 404 before the policy even runs.

Cross-org isolation is covered by `test_officer_from_different_org_cannot_access_election`.

---

## Why Policies Over Gates

The earlier implementation used Gates defined in `AuthServiceProvider::boot()`:
```php
Gate::define('manage-election-settings', fn ($user) => ...);
```

**Problem:** Gates are global — they cannot receive the `Election` model, so they can't check `election->organisation_id`. Any officer of any organisation would pass.

**Solution:** Policies receive both `$user` and `$election`, enabling proper tenant-scoped authorization.
