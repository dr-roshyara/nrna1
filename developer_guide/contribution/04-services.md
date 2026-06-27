# 04 — Services

## ContributionPointsService

**File:** `app/Services/ContributionPointsService.php`

This service orchestrates the full points workflow: calculate → update contribution → write ledger entry.

### Constructor

```php
public function __construct(
    private readonly GaneshStandardFormula $formula,
) {}
```

Injected via Laravel's service container. No manual binding needed — the formula has no constructor dependencies.

### awardPoints(Contribution $contribution): int

Called when an admin approves a contribution.

**What it does:**
1. Fetches the user's current weekly points total
2. Passes contribution data + weekly total to `GaneshStandardFormula::calculate()`
3. Updates `calculated_points` on the contribution record
4. Writes an immutable `PointsLedger` entry (even if points = 0, for audit completeness)
5. Returns the calculated points

**Usage:**
```php
$service = app(ContributionPointsService::class);
$points = $service->awardPoints($contribution);
```

### getWeeklyPoints(string $userId, string $organisationId): int

Returns the sum of all `earned` points for a user within the current ISO week.

**Key details:**
- Uses `withoutGlobalScopes()` to bypass the `BelongsToTenant` scope (necessary because the scope might filter based on session state, but this query needs explicit org filtering)
- Filters by `action = 'earned'` only (not spent/adjusted/appealed)
- Uses `whereBetween` with `now()->startOfWeek()` and `now()->endOfWeek()`

---

## LeaderboardService

**File:** `app/Services/LeaderboardService.php`

Aggregates points per user for a given organisation, respecting privacy settings.

### get(string $organisationId): Collection

Returns a Collection of arrays, each containing:

```php
[
    'user_id'      => string,
    'display_name' => string,   // real name or "Contributor #N"
    'total_points' => int,
    'rank'         => int,      // 1-based
]
```

**Privacy rules:**

| leaderboard_visibility | Behavior |
|----------------------|----------|
| `public` | Real name shown |
| `anonymous` | Displayed as "Contributor #1", "Contributor #2", etc. (numbered in sequence based on anonymous users encountered, not rank) |
| `private` | Excluded entirely from query results |

**Implementation notes:**
- Uses a raw `DB::table()` query joining `points_ledger` and `users`
- Filters to `action = 'earned'` and visibility IN ('public', 'anonymous')
- Groups by user, sums points, orders descending
- Anonymous counter is sequential — the first anonymous user encountered gets #1, the second gets #2, regardless of their rank position

---

## GaneshStandardFormula

**File:** `app/Services/GaneshStandardFormula.php`

See [03-ganesh-standard-formula.md](03-ganesh-standard-formula.md) for full documentation.

Public API:
- `calculate(array $input, int $weeklyPoints): int` — Full point calculation
- `calculateSynergy(array $skills): float` — Synergy multiplier only (public for testing)
