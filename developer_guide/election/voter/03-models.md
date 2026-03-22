# 03 — Models

## `ElectionMembership`

**File:** `app/Models/ElectionMembership.php`

---

### Relationships

```php
$membership->user           // BelongsTo User
$membership->organisation   // BelongsTo Organisation
$membership->election       // BelongsTo Election (withoutGlobalScopes)
$membership->assigner       // BelongsTo User (via assigned_by)
```

**Important:** `election()` calls `.withoutGlobalScopes()` internally. The `Election` model uses the `BelongsToTenant` global scope, which filters by `session('current_organisation_id')`. In contexts where no session org is set (tests, CLI commands, queued jobs), the scope would return `null` without this. Any relationship or query that loads `Election` from outside a web request must use `withoutGlobalScopes()`.

---

### Scopes

All scopes are chainable:

```php
ElectionMembership::active()->get();                   // status = active
ElectionMembership::voters()->get();                   // role = voter
ElectionMembership::candidates()->get();               // role = candidate
ElectionMembership::forElection($electionId)->get();   // election_id = ?
ElectionMembership::forOrganisation($orgId)->get();    // organisation_id = ?

// Eligible: active AND (expires_at is null OR expires_at > now)
ElectionMembership::eligible()->get();

// Combine freely
ElectionMembership::forElection($id)->voters()->eligible()->get();
```

---

### `assignVoter()` — Safe Single Assignment

```php
$membership = ElectionMembership::assignVoter(
    userId:     $user->id,
    electionId: $election->id,
    assignedBy: auth()->id(),   // optional
    metadata:   ['source' => 'manual_import']  // optional
);
```

What it does inside a `DB::transaction` with up to 5 retries on deadlock:

1. Locks the `elections` row (`lockForUpdate`)
2. Checks `user_organisation_roles` — throws `InvalidArgumentException` if user is not an org member
3. Looks for an existing row with `(user_id, election_id)` — also locked
4. If existing row is **inactive**: reactivates it (updates status + assigned_by + assigned_at)
5. If existing row is **active**: throws `InvalidArgumentException` ("already an active voter")
6. If no existing row: creates a new one

Returns the `ElectionMembership` instance in all success cases.

---

### `bulkAssignVoters()` — Optimised Batch Import

```php
$result = ElectionMembership::bulkAssignVoters(
    userIds:    $arrayOfUserIds,
    electionId: $election->id,
    assignedBy: auth()->id()  // optional
);

// $result:
// [
//     'success'          => 47,   // rows inserted
//     'already_existing' => 3,    // already had a membership, skipped
//     'invalid'          => 2,    // not org members, skipped
// ]
```

Internals (single transaction):
- One `whereIn` query to find which user IDs are valid org members
- One `whereIn` query to find which are already assigned
- Single `INSERT` for all new rows (no N+1)
- Generates UUIDs in PHP (avoids multiple round-trips)
- Invalidates cache after insert

**Does not** reactivate inactive memberships — only inserts genuinely new rows.

---

### `isEligible()` — Instance Method

```php
if ($membership->isEligible()) {
    // can vote
}
```

Returns `true` when `status === 'active'` AND (`expires_at` is null OR `expires_at` is in the future).

---

### `markAsVoted()` — Instance Method

```php
$membership->markAsVoted();
```

Sets `last_activity_at = now()` and `status = inactive`. Used after a vote is recorded to prevent a second vote submission through the same membership.

---

### `remove()` — Instance Method

```php
$membership->remove('Voter requested removal');
```

Sets `status = removed` and appends `removed_at` and `removed_reason` to the `metadata` JSON column. Does not hard-delete the row — the history is preserved for audits.

---

### Cache Invalidation Hooks

In `booted()`, the model registers `saved` and `deleted` observers:

```php
protected static function booted(): void
{
    $invalidate = function (self $membership) {
        Cache::forget("election.{$membership->election_id}.voter_count");
        Cache::forget("election.{$membership->election_id}.voter_stats");
    };

    static::saved($invalidate);
    static::deleted($invalidate);
}
```

Any time a membership row is created, updated, or deleted, the relevant election's cached counts are cleared. This happens automatically — you do not need to manually clear cache after assignment operations.

---

## `Election` — Added Methods

**File:** `app/Models/Election.php`

```php
// All memberships for this election
$election->memberships()           // HasMany ElectionMembership

// Active voter memberships
$election->membershipVoters()      // chained: ->where('role','voter')->where('status','active')

// Active voters that are not expired
$election->eligibleVoters()        // chained: adds expires_at check

// Cached voter count (5 min TTL, invalidated by booted() hooks)
$election->voter_count             // int accessor
```

**Naming note:** The method is `membershipVoters()`, not `voters()`. The `Election` model already has a `voters(): HasMany` method that returns the `Voter` model (three-tier hierarchy system). Using `voters()` for the new membership-based relationship would cause a PHP fatal error (`Cannot redeclare`).

---

## `User` — Added Methods

**File:** `app/Models/User.php`

```php
// All ElectionMembership rows for this user
$user->electionMemberships()       // HasMany ElectionMembership

// All elections the user has any membership in (belongsToMany via election_memberships)
$user->elections()                 // BelongsToMany Election (withoutGlobalScopes)

// Elections where the user is an active voter
$user->voterElections()            // filtered: role=voter, status=active

// Quick boolean check (cached 5 minutes)
$user->isVoterInElection($electionId)   // bool
```

The `elections()` and `voterElections()` relationships call `.withoutGlobalScopes()` on the `Election` model for the same reason as `ElectionMembership::election()` — the `BelongsToTenant` scope would otherwise filter out elections in non-web contexts.

---

## What NOT to Do

```php
// DON'T — bypasses all integrity checks
ElectionMembership::create([
    'user_id'         => $userId,
    'organisation_id' => $orgId,
    'election_id'     => $electionId,
]);

// DO — uses transaction, validates membership, handles duplicates
ElectionMembership::assignVoter($userId, $electionId, auth()->id());
```

Direct `create()` calls skip the org-membership check, the duplicate check, and the reactivation logic. Only use `create()` in test factories or migrations where you control the data completely.

```php
// DON'T — missing withoutGlobalScopes in CLI/job context
$membership->election   // may return null in queued jobs

// DO
$membership->election()->withoutGlobalScopes()->first()
// Or rely on the built-in relationship which already has withoutGlobalScopes()
$membership->election  // safe — the relationship definition includes it
```
