# 03 — `ElectionMembership.has_voted` as Single Source of Truth

**Migration:** `database/migrations/2026_03_22_000001_add_voted_fields_to_election_memberships.php`
**Model:** `app/Models/ElectionMembership.php`

---

## The Problem Before This Change

`hasVoted` was determined by querying `voter_slugs.status='voted'`:

```php
// Before — scattered VoterSlug query (3 places in the codebase)
VoterSlug::withoutGlobalScopes()
    ->where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->where('status', 'voted')
    ->exists();
```

`VoterSlug` is a **session-tracking table** — it records the voter's active code-entry session (step 1 → submission). Using it as the source of truth for a permanent fact ("did this person vote?") was architecturally wrong. `ElectionMembership` already owns the user↔election relationship and is the correct domain model for this.

---

## What Changed

### Migration

Two columns added to `election_memberships`:

| Column | Type | Default | Purpose |
|--------|------|---------|---------|
| `has_voted` | `boolean` | `false` | Whether the user has cast their vote |
| `voted_at` | `timestamp` | `NULL` | When the vote was cast |

Index added: `idx_em_has_voted` on `has_voted` for `scopeNotVoted()` queries.

**Backfill** on migration: existing rows where a `voter_slugs` record with `status='voted'` exists are backfilled using a safe `EXISTS` + `MIN(updated_at)` subquery (not a JOIN, to avoid duplicate-row issues):

```sql
UPDATE election_memberships em
SET em.has_voted = 1,
    em.voted_at  = (SELECT MIN(vs.updated_at) FROM voter_slugs vs
                    WHERE vs.user_id = em.user_id AND vs.election_id = em.election_id
                      AND vs.status = 'voted'),
    em.status = 'inactive'
WHERE em.has_voted = 0
  AND EXISTS (SELECT 1 FROM voter_slugs vs
              WHERE vs.user_id = em.user_id AND vs.election_id = em.election_id
                AND vs.status = 'voted')
```

### Model — `markAsVoted()`

```php
// Before
public function markAsVoted(): void
{
    $this->update(['last_activity_at' => now(), 'status' => 'inactive']);
}

// After
public function markAsVoted(): void
{
    $this->update([
        'has_voted'        => true,
        'voted_at'         => now(),
        'status'           => 'inactive',
        'last_activity_at' => now(),
    ]);
}
```

### Model — `$fillable` and `$casts`

```php
protected $fillable = [
    // ... existing fields
    'has_voted',
    'voted_at',
];

protected $casts = [
    // ... existing casts
    'has_voted' => 'boolean',
    'voted_at'  => 'datetime',
];

protected $attributes = [
    'role'      => 'voter',
    'status'    => 'active',
    'metadata'  => '{}',
    'has_voted' => false,   // ← reflects DB default so Eloquent doesn't return null after create()
];
```

### Model — `scopeNotVoted()`

```php
public function scopeNotVoted($query)
{
    return $query->where('has_voted', false);
}
```

Use: `ElectionMembership::notVoted()->where('election_id', $id)->get()`

### Model — Cache invalidation fix

The `booted()` hook now also clears the per-user voter eligibility cache key (`isVoterInElection()` uses a 5-minute cache):

```php
protected static function booted(): void
{
    $invalidate = function (self $membership) {
        Cache::forget("election.{$membership->election_id}.voter_count");
        Cache::forget("election.{$membership->election_id}.voter_stats");
        Cache::forget("user.{$membership->user_id}.voter.{$membership->election_id}"); // ← added
    };

    static::saved($invalidate);
    static::deleted($invalidate);
}
```

Without this, `isVoterInElection()` could return `true` from cache for up to 5 minutes after `markAsVoted()` flipped `status=inactive`.

---

## Consumers Updated

### `ElectionVotingController` — `show()` and `start()`

```php
// Before
$hasVoted = VoterSlug::withoutGlobalScopes()
    ->where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->where('status', 'voted')
    ->exists();

// After
$membership = $user->electionMemberships()
    ->where('election_id', $election->id)
    ->first();

$hasVoted   = $membership?->has_voted ?? false;
$isEligible = $membership !== null
    && $membership->role   === 'voter'
    && $membership->status !== 'removed';
```

### `User::countActiveElections()`

Used by `DashboardResolver` for Priority 3 routing.

```php
// Before — cross-table VoterSlug query
->whereDoesntHave('voterSlugs', function ($query) {
    $query->where('user_id', $this->id)->where('status', 'voted');
})

// After — membership-based (semantically equivalent)
->whereDoesntHave('memberships', function ($query) {
    $query->where('user_id', $this->id)->where('has_voted', true);
})
```

Note: `whereDoesntHave` (not `whereHas`) is correct here. It means "show active elections where I do **not** have a voted membership" — elections with no membership at all are still counted, preserving the original routing behaviour.

---

## What VoterSlug Still Owns

`VoterSlug` was **not removed or changed**. It remains the session-tracking layer:

| Concern | Owner |
|---------|-------|
| Active voting session (code entered, step tracking, expiry) | `VoterSlug` |
| Durable voting status ("did this person vote?") | `ElectionMembership.has_voted` |

The voting flow (code entry → `markAsVoted()`) continues to write `VoterSlug.status='voted'` **and** `ElectionMembership.has_voted=true`. Both are written; only membership is read for status checks.
