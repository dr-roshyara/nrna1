# 08 — Troubleshooting

## Error: Missing index for constraint (1822)

```
SQLSTATE[HY000]: General error: 1822 Failed to add the foreign key constraint.
Missing index for constraint 'election_memberships_election_id_organisation_id_foreign'
on table 'election_memberships' required index of columns: 'id', 'organisation_id'
```

**Cause:** The migration `2026_03_17_213211_add_composite_unique_to_elections_table.php` was not run before `2026_03_17_213212_create_election_memberships_table.php`.

**Fix:**
```bash
php artisan migrate
```

If migrations are already in a broken state:
```bash
php artisan migrate:rollback   # rolls back last batch
php artisan migrate            # re-runs both in correct order
```

---

## Error: Foreign key constraint fails (1452)

```
SQLSTATE[23000]: Integrity constraint violation: 1452
Cannot add or update a child row: a foreign key constraint fails
(election_memberships, CONSTRAINT election_memberships_user_id_organisation_id_foreign
FOREIGN KEY (user_id, organisation_id) REFERENCES user_organisation_roles ...)
```

**Cause:** You tried to create an `ElectionMembership` for a user who is not in `user_organisation_roles` for that organisation. This is the composite FK doing its job.

**Common triggers:**
- Using `ElectionMembership::create()` directly instead of `assignVoter()`
- The user was removed from the organisation before the membership was created
- In tests: forgetting to call `$org->users()->attach()`

**Fix:**
- Always use `ElectionMembership::assignVoter()` for voter assignment
- Verify the user is in the organisation first:
  ```php
  DB::table('user_organisation_roles')
      ->where('user_id', $userId)
      ->where('organisation_id', $orgId)
      ->exists();
  ```

---

## Error: Duplicate entry (1062)

```
SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry
'user-uuid-election-uuid' for key 'unique_user_election'
```

**Cause:** You tried to insert a second row for the same `(user_id, election_id)` pair.

**Fix:** Use `assignVoter()` — it checks for existing rows and either reactivates or throws a meaningful `InvalidArgumentException`. It never hits this constraint in normal usage.

---

## `$membership->election` Returns `null` in a Job or Command

**Cause:** The `Election` model has the `BelongsToTenant` global scope. In jobs and commands, `session('current_organisation_id')` is null. The scope then filters by the platform org ID, which does not match most elections.

**The relationship definition already includes `withoutGlobalScopes()`**, so `$membership->election` (lazy-loaded) is safe. The issue typically appears when you write a standalone `Election::find()` or `Election::where()` query.

**Fix:**
```php
// Wrong — affected by scope in non-web context
$election = Election::find($electionId);

// Correct
$election = Election::withoutGlobalScopes()->find($electionId);
```

---

## `$user->voterElections()->count()` Returns 0 When It Should Not

**Cause:** The `elections()` `belongsToMany` on `User` was not calling `.withoutGlobalScopes()`. This was a bug that existed in the initial implementation and was caught during TDD.

**The current implementation already has the fix.** If you see this, verify that `User::elections()` in `app/Models/User.php` looks like:

```php
public function elections()
{
    return $this->belongsToMany(Election::class, 'election_memberships')
        ->withPivot(['role', 'status', 'assigned_at'])
        ->withTimestamps()
        ->withoutGlobalScopes();   // ← must be here
}
```

---

## Cache Returns Stale Voter Count

**Symptoms:** Dashboard shows voter count from before a voter was added or removed.

**Cause:** The cache was not invalidated. This can happen if:
- A raw `DB::table('election_memberships')->insert()` was used (bypasses model events)
- The `booted()` hooks were accidentally removed

**Manual fix:**
```bash
php artisan tinker
>>> Cache::forget("election.your-election-uuid.voter_count");
>>> Cache::forget("election.your-election-uuid.voter_stats");
```

**Permanent fix:** Always use `ElectionMembership::assignVoter()` or `ElectionMembership::bulkAssignVoters()`. If you must use raw inserts, add `Cache::forget()` calls immediately after.

---

## `BadMethodCallException: This cache store does not support tagging`

```
BadMethodCallException: This cache store does not support tagging.
```

**Cause:** `Cache::tags()` was called somewhere. The system is designed for `CACHE_DRIVER=file`, which does not support tags.

**Fix:** Replace any `Cache::tags(...)->...` calls with explicit `Cache::remember()` / `Cache::forget()` calls using the key naming convention `"election.{id}.{key_name}"`.

---

## Test Fails: `ErrorException: Attempt to read property "id" on null`

At `$membership->election->id`

**Cause:** In tests, `session()` is empty, so the `BelongsToTenant` scope on `Election` filters to the platform org, returning null.

**This is already fixed in the current implementation** — `ElectionMembership::election()` calls `.withoutGlobalScopes()`. If you see this, check that your local version of the model matches the repository.

---

## Test Fails: `SQLSTATE[HY000]: Field 'id' doesn't have a default value`

**Cause:** Calling `$org->users()->attach($userId, ['role' => 'voter'])` without providing an `id`. The `user_organisation_roles` pivot table requires a UUID `id`.

**Fix:**
```php
$this->org->users()->attach($userId, [
    'id'   => (string) Str::uuid(),
    'role' => 'voter',
]);
```

---

## Integrity Monitoring Reports Orphaned Rows

Running `php artisan elections:validate-memberships` and getting violations means `election_memberships` has rows where `(user_id, organisation_id)` no longer exists in `user_organisation_roles`.

This should not happen because of `ON DELETE CASCADE` on FK 1. If it does, it may mean:
- The FK was not enforced (e.g. MyISAM engine was used — always use InnoDB)
- The membership was inserted directly without the FK path (bypassing Eloquent and the DB constraint simultaneously)

**Fix:**
```bash
php artisan elections:validate-memberships
# Reviews output and decides whether to auto-remove or investigate
```

The command logs the orphaned IDs to `laravel.log` for investigation.
