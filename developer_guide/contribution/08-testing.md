# 08 — Testing

## Test Suite Summary

| File | Tests | Focus |
|------|-------|-------|
| `tests/Feature/Contribution/PointsCalculatorTest.php` | 6 | Formula correctness |
| `tests/Feature/Contribution/ContributionPointsServiceTest.php` | 5 | Orchestration, ledger, weekly cap |
| `tests/Feature/Contribution/LeaderboardServiceTest.php` | 5 | Privacy, org scoping, ranking |
| **Total** | **16** | **28 assertions** |

### Run All

```bash
php artisan test tests/Feature/Contribution/ --no-coverage
```

### Run Individual Suites

```bash
php artisan test tests/Feature/Contribution/PointsCalculatorTest.php
php artisan test tests/Feature/Contribution/ContributionPointsServiceTest.php
php artisan test tests/Feature/Contribution/LeaderboardServiceTest.php
```

---

## PointsCalculatorTest

Tests the `GaneshStandardFormula` class in isolation (no database).

| Test | What It Verifies |
|------|-----------------|
| `micro_track_contribution_calculates_correctly` | 3 hrs × self_report → 15 pts (base 30 × 0.5 verification) |
| `weekly_cap_enforces_on_micro_track` | With 80 weekly points already earned, micro result is capped to remaining 20 |
| `standard_track_with_photo_verification` | 10 hrs + 3 unique skills + photo → 157 pts (includes tier bonus + synergy) |
| `major_track_with_institutional_verification_and_recurring_bonus` | 40 hrs + institutional + recurring + outcome bonus → 1180 pts |
| `synergy_multiplier_rewards_unique_skills` | Validates 1.0x/1.2x/1.5x thresholds |
| `points_are_stored_as_integers` | Result is always `int`, never float |

---

## ContributionPointsServiceTest

Tests the full orchestration with database (uses `RefreshDatabase`).

| Test | What It Verifies |
|------|-----------------|
| `it_calculates_points_and_writes_to_ledger` | awardPoints() creates a ledger row and updates contribution.calculated_points |
| `weekly_cap_is_enforced_across_multiple_contributions` | Second micro contribution respects remaining cap after first award |
| `it_returns_zero_points_when_weekly_cap_already_reached` | If 100 weekly points used, next micro returns 0 (ledger still written) |
| `non_micro_tracks_are_not_subject_to_weekly_cap` | Standard track returns full points even with 100 weekly micro points |
| `get_weekly_points_sums_only_current_week_earned_entries` | Points from previous week are excluded; only 'earned' action counted |

### Setup Pattern

Tests create:
- An organisation (via factory)
- A user
- Contribution records (via factory)
- Pre-seeded ledger entries (for cap testing)

### Key Assertion: Ledger Immutability

```php
// Even zero-point awards write a ledger entry
$this->assertDatabaseHas('points_ledger', [
    'contribution_id' => $contribution->id,
    'points'          => 0,
    'action'          => 'earned',
]);
```

---

## LeaderboardServiceTest

Tests privacy filtering and org scoping with database.

| Test | What It Verifies |
|------|-----------------|
| `public_users_appear_with_real_name` | User with visibility='public' shows their actual name |
| `anonymous_users_appear_as_contributor_number` | User with visibility='anonymous' shows "Contributor #N" |
| `private_users_are_excluded_from_leaderboard` | User with visibility='private' does not appear at all |
| `leaderboard_is_sorted_by_total_points_descending` | Highest points = rank 1 |
| `leaderboard_is_scoped_to_organisation` | Points from org A don't appear in org B's leaderboard |

### Privacy Test Pattern

```php
// Create user with specific visibility
$user = User::factory()->create(['leaderboard_visibility' => 'anonymous']);

// Create ledger entries for this user
PointsLedger::factory()->create([
    'user_id'         => $user->id,
    'organisation_id' => $org->id,
    'action'          => 'earned',
    'points'          => 50,
]);

// Assert display name
$board = $this->service->get($org->id);
$entry = $board->firstWhere('user_id', $user->id);
$this->assertStringStartsWith('Contributor #', $entry['display_name']);
```

---

## Factories

### ContributionFactory

**File:** `database/factories/ContributionFactory.php`

Generates valid contribution records with sensible defaults:
- Random track (micro/standard/major)
- Random proof type
- Random effort_units (1–40)
- Status defaults to 'pending'
- Associates with org and user

### PointsLedgerFactory

**File:** `database/factories/PointsLedgerFactory.php`

Generates ledger entries:
- Random points (1–500)
- Action defaults to 'earned'
- Associates with org, user, and contribution

---

## Adding New Tests

When extending the contribution system, follow these patterns:

1. **Formula changes** → Add to `PointsCalculatorTest` (no DB needed)
2. **Workflow changes** → Add to `ContributionPointsServiceTest` (needs DB)
3. **Leaderboard changes** → Add to `LeaderboardServiceTest` (needs DB)
4. **New endpoint** → Create a new test class in `tests/Feature/Contribution/`

Always test with multiple organisations to verify tenant isolation.
