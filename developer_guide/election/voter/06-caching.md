# 06 — Caching

## Why Caching Matters Here

Voter count and stats queries touch multiple rows across `election_memberships`. For elections with tens of thousands of voters these queries are expensive. The caching layer ensures dashboards and eligibility checks stay fast without hitting the database on every request.

---

## The Environment Constraint

The platform uses `CACHE_DRIVER=file` by default. The file driver **does not support `Cache::tags()`**. Calling:

```php
Cache::tags(["election.{$id}"])->flush();  // ← THROWS on file driver
```

produces:

```
Symfony\Component\HttpKernel\Exception\HttpException:
BadMethodCallException: This cache store does not support tagging.
```

This is why the system uses **Option B: explicit key invalidation** instead of tags.

---

## Option B — Explicit Key Invalidation

### Cached Keys

| Key | TTL | Set by | Cleared by |
|-----|-----|--------|-----------|
| `election.{id}.voter_count` | 5 min (300 s) | `Election::getVoterCountAttribute()` | `ElectionMembership::booted()` |
| `election.{id}.voter_stats` | 5 min (300 s) | `ElectionVoterService::getVoterStats()` | `ElectionMembership::booted()` |
| `user.{id}.voter.{electionId}` | 5 min (300 s) | `User::isVoterInElection()` | Indirectly by membership changes |

### How It Works

**Setting the cache:**

```php
// In Election model
public function getVoterCountAttribute(): int
{
    return Cache::remember(
        "election.{$this->id}.voter_count",
        300,
        fn () => $this->membershipVoters()->count()
    );
}
```

**Clearing the cache (booted hooks in ElectionMembership):**

```php
protected static function booted(): void
{
    $invalidate = function (self $membership) {
        Cache::forget("election.{$membership->election_id}.voter_count");
        Cache::forget("election.{$membership->election_id}.voter_stats");
    };

    static::saved($invalidate);    // fires on create AND update
    static::deleted($invalidate);  // fires on delete
}
```

This means:
- Add a voter → cache clears → next `voter_count` access hits DB and re-caches
- Remove a voter → same
- Bulk assign → `bulkAssignVoters()` calls `Cache::forget()` directly after `insert()`

---

## Bulk Assignment Cache Invalidation

`bulkAssignVoters()` uses raw `DB::table()->insert()` (not Eloquent `create()`) for performance. Raw inserts do not fire Eloquent model events, so the `booted()` hooks do not fire. The method therefore calls `Cache::forget()` explicitly:

```php
if (! empty($memberships)) {
    self::insert($memberships);
    Cache::forget("election.{$electionId}.voter_count");
    Cache::forget("election.{$electionId}.voter_stats");
}
```

If you ever write a bulk operation that bypasses Eloquent, you must also manually clear these keys.

---

## Testing Cache Behaviour

Use `Config::set('cache.default', 'array')` in tests. The array driver works without a filesystem, is faster, and still supports `Cache::remember()` and `Cache::forget()` exactly as the file driver does:

```php
public function test_voter_count_is_cached_after_first_access(): void
{
    Config::set('cache.default', 'array');

    ElectionMembership::assignVoter($this->member->id, $this->election->id);

    $cacheKey = "election.{$this->election->id}.voter_count";

    // Access voter_count twice
    $first  = $this->election->fresh()->voter_count;
    $second = $this->election->fresh()->voter_count;

    $this->assertEquals(1, $first);
    $this->assertEquals(1, $second);
    $this->assertTrue(Cache::has($cacheKey));
}

public function test_voter_count_cache_is_cleared_when_membership_added(): void
{
    Config::set('cache.default', 'array');

    $cacheKey = "election.{$this->election->id}.voter_count";

    // Prime the cache
    $this->election->voter_count;
    $this->assertTrue(Cache::has($cacheKey));

    // Add a voter — should clear the cache
    ElectionMembership::assignVoter($this->member->id, $this->election->id);

    $this->assertFalse(Cache::has($cacheKey));
}
```

---

## Checking for Stale Cache in Development

If a voter count looks wrong in development, clear the application cache:

```bash
php artisan cache:clear
```

Or clear a specific election's keys from Tinker:

```php
Cache::forget("election.{uuid}.voter_count");
Cache::forget("election.{uuid}.voter_stats");
```

---

## Upgrading to Redis (Future)

If the platform moves to `CACHE_DRIVER=redis`, the Option B pattern still works. No changes are needed. You may optionally switch to `Cache::tags()` for easier grouped invalidation, but it is not required. The explicit key approach is deliberately simple and environment-independent.

If you switch, the one change would be in `bulkAssignVoters()` and `booted()`:

```php
// Current (Option B — works everywhere)
Cache::forget("election.{$id}.voter_count");
Cache::forget("election.{$id}.voter_stats");

// Future option with Redis tags (do not use until CACHE_DRIVER=redis is confirmed)
Cache::tags(["election.{$id}"])->flush();
```
