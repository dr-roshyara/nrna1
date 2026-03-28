# 04 — Testing

---

## Test Files

| File | Suite | Tests | What it covers |
|------|-------|-------|---------------|
| `tests/Unit/Models/ElectionMembershipTest.php` | Unit | 36 | `markAsVoted()`, `has_voted` default, `scopeNotVoted()`, relationships, scopes, `assignVoter()`, `bulkAssignVoters()` |
| `tests/Feature/Election/ElectionShowControllerTest.php` | Feature | 12 | `GET /elections/{slug}` and `POST /elections/{slug}/start` — eligibility, hasVoted, redirects, slug reuse |
| `tests/Feature/Services/DashboardResolverElectionPriorityTest.php` | Feature | 9 | `countActiveElections()` routing — counts, priority 3 routing, voted user exclusion |

---

## Run All

```bash
php artisan test \
  tests/Unit/Models/ElectionMembershipTest.php \
  tests/Feature/Election/ElectionShowControllerTest.php \
  tests/Feature/Services/DashboardResolverElectionPriorityTest.php \
  --no-coverage
```

Expected: **57 passed, 178 assertions.**

---

## Key Unit Tests (ElectionMembershipTest)

### `mark_as_voted_sets_has_voted_true_and_stamps_voted_at`

Verifies `markAsVoted()` sets all three fields atomically:

```php
$membership->markAsVoted();
$membership->refresh();

$this->assertTrue($membership->has_voted);
$this->assertNotNull($membership->voted_at);
$this->assertEquals('inactive', $membership->status);
```

### `new_membership_defaults_has_voted_to_false`

Verifies a freshly-created membership has `has_voted=false` without needing a DB refresh. This works because `$attributes['has_voted'] = false` is set on the model.

```php
$membership = ElectionMembership::create([...]);
$this->assertFalse($membership->has_voted); // no ->refresh() needed
```

### `scope_not_voted_excludes_members_who_have_voted`

Verifies `notVoted()` scope correctly filters:

```php
$mA->markAsVoted();

$ids = ElectionMembership::notVoted()
    ->where('election_id', $election->id)
    ->pluck('user_id')->toArray();

$this->assertContains($userB->id, $ids);
$this->assertNotContains($userA->id, $ids);
```

---

## Key Feature Tests

### `start_for_already_voted_redirects_with_info` (ElectionShowControllerTest)

Uses `markVoted()` helper which now sets `ElectionMembership.has_voted=true` as the primary action:

```php
private function markVoted(User $user, Election $election): void
{
    // Primary: ElectionMembership (source of truth)
    ElectionMembership::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->update(['has_voted' => true, 'voted_at' => now(), 'status' => 'inactive']);

    // Also insert VoterSlug for audit trail
    DB::table('voter_slugs')->insert([...]);
}
```

### `user_who_already_voted_counts_zero` (DashboardResolverElectionPriorityTest)

Uses `markUserAsVoted()` helper which calls `ElectionMembership::updateOrCreate(...)` with `has_voted=true`. This ensures `countActiveElections()` returns 0 for that user.

---

## Test Database

Unit tests use `RefreshDatabase` — full migration suite runs on `nrna_test` database.
Feature tests use `DatabaseTransactions` — faster, wraps each test in a transaction that rolls back.

If `nrna_test` is missing the new columns, run:

```bash
php artisan migrate --env=testing --path=database/migrations/2026_03_22_000001_add_voted_fields_to_election_memberships.php
```

---

## Adding New Tests

When writing tests that involve a voter who has voted, always set `ElectionMembership.has_voted=true` — do not rely solely on `VoterSlug.status='voted'` for the assertion. Example helper pattern:

```php
private function markVoted(User $user, Election $election): void
{
    ElectionMembership::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->update(['has_voted' => true, 'voted_at' => now(), 'status' => 'inactive']);
}
```
