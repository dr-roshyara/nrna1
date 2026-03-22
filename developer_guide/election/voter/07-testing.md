# 07 — Testing

## Test File

`tests/Unit/Models/ElectionMembershipTest.php`

---

## Running the Tests

```bash
# Full test suite for this feature
php artisan test tests/Unit/Models/ElectionMembershipTest.php --no-coverage

# Expected output
Tests:  25 passed (53 assertions)
```

---

## TDD History

This feature was built strictly test-first. Here is the sequence:

1. Architecture spec read (`architecture/election/voter/20260317_2208_Voter_model.md`)
2. Compatibility audit performed — two blockers identified (`COMPATIBILITY_REPORT.md`)
3. Blockers resolved: composite unique key on `elections`, cache strategy changed to Option B
4. **Test file written in full** — 25 tests, all confirmed RED (failing with "Class not found")
5. Migrations created
6. `ElectionMembership` model created
7. `Election` model updated (added `memberships()`, `membershipVoters()`, `eligibleVoters()`, `getVoterCountAttribute()`)
8. `User` model updated (added `electionMemberships()`, `elections()`, `voterElections()`, `isVoterInElection()`)
9. Two tests still failing: `election()` and `elections()` relationships return null in test context → fixed with `.withoutGlobalScopes()`
10. **All 25 tests GREEN**

---

## Test Structure

### setUp

```php
protected function setUp(): void
{
    parent::setUp();

    $this->org = Organisation::factory()->create(['type' => 'tenant']);

    $this->member = User::factory()->create(['email_verified_at' => now()]);
    $this->org->users()->attach($this->member->id, [
        'id'   => (string) Str::uuid(),
        'role' => 'voter',
    ]);

    $this->election = Election::factory()->create([
        'organisation_id' => $this->org->id,
    ]);
}
```

Key points:
- Organisation is type `tenant` — required by factory
- Member is attached via `users()->attach()` which writes to `user_organisation_roles` (required by the composite FK)
- The `id` field is required in the attach data because `user_organisation_roles` has a UUID primary key

---

## What Each Test Group Covers

### `assignVoter()` — 5 tests

| Test | What it proves |
|------|----------------|
| `test_assign_voter_creates_active_membership` | Happy path: correct row with correct values |
| `test_assign_voter_rejects_user_not_in_organisation` | FK enforcement at application layer |
| `test_assign_voter_throws_when_election_not_found` | `ModelNotFoundException` on bad UUID |
| `test_assign_voter_throws_if_already_active` | Duplicate prevention |
| `test_assign_voter_reactivates_inactive_membership` | Reactivation instead of duplicate |

### `bulkAssignVoters()` — 3 tests

| Test | What it proves |
|------|----------------|
| `test_bulk_assign_voters_inserts_only_new_members` | Skips already-assigned users |
| `test_bulk_assign_voters_skips_invalid_users` | Skips non-org-members, counts them |
| `test_bulk_assign_voters_returns_correct_counts` | Return value has `success / already_existing / invalid` |

### `isEligible()` — 3 tests

| Test | What it proves |
|------|----------------|
| `test_active_membership_without_expiry_is_eligible` | Null `expires_at` means always eligible |
| `test_inactive_membership_is_not_eligible` | Status check |
| `test_membership_with_past_expires_at_is_not_eligible` | Expiry check |

### Business logic — 2 tests

| Test | What it proves |
|------|----------------|
| `test_mark_as_voted_updates_last_activity_and_sets_inactive` | `markAsVoted()` side effects |
| `test_remove_sets_status_to_removed_and_stores_reason_in_metadata` | `remove()` side effects |

### Relationships — 3 tests

| Test | What it proves |
|------|----------------|
| `test_membership_belongs_to_user` | `$membership->user` loads the correct user |
| `test_membership_belongs_to_election` | `$membership->election` loads the correct election (requires `withoutGlobalScopes`) |
| `test_user_has_voter_elections_relationship` | `$user->voterElections()` counts all voter elections |

### Scopes — 3 tests

| Test | What it proves |
|------|----------------|
| `test_election_eligible_voters_excludes_expired_memberships` | `eligibleVoters()` on Election |
| `test_eligible_scope_excludes_inactive_memberships` | `scopeEligible()` on ElectionMembership |
| `test_scope_voters_returns_only_voter_role` | `scopeVoters()` |
| `test_scope_for_election_isolates_memberships_per_election` | `scopeForElection()` |

### Database constraints — 2 tests

| Test | What it proves |
|------|----------------|
| `test_composite_fk_rejects_membership_with_wrong_organisation` | The FK at the DB level, not PHP |
| `test_cascade_delete_removes_memberships_when_user_leaves_organisation` | `ON DELETE CASCADE` on FK 1 |

### Cache — 3 tests

| Test | What it proves |
|------|----------------|
| `test_voter_count_is_cached_after_first_access` | `voter_count` is stored in cache |
| `test_voter_count_cache_is_cleared_when_membership_added` | `booted()` hooks fire on `saved` |
| `test_cache_works_with_file_driver_no_tags_required` | No `Cache::tags()` dependency |

---

## Common Test Pitfalls

### Pitfall 1: Missing `id` in `users()->attach()`

The `user_organisation_roles` table has a UUID `id` column as part of its primary key. If you forget to pass it in `attach()`, the insert fails:

```php
// Wrong — missing 'id'
$this->org->users()->attach($user->id, ['role' => 'voter']);

// Correct
$this->org->users()->attach($user->id, [
    'id'   => (string) Str::uuid(),
    'role' => 'voter',
]);
```

### Pitfall 2: `BelongsToTenant` Scope Filtering Elections

Tests run without a session, so `session('current_organisation_id')` is null. The `BelongsToTenant` scope then substitutes the platform org's ID. If your election belongs to a different org (which it will in tests), `Election::find()` returns null.

Fix: always use `.withoutGlobalScopes()` when fetching elections in tests, or rely on the relationship methods that already include it (`$membership->election`, `$user->elections()`).

### Pitfall 3: Using `Cache::tags()` in Tests

Never test with `Cache::tags()`. It will work in tests that use the `array` driver but fail in production with the `file` driver. Use `Cache::remember()` + `Cache::forget()` throughout.

### Pitfall 4: Raw `DB::table()->insert()` Skips Model Events

If you write a raw insert in a test to set up state, the `booted()` hooks will not fire. If you need cache invalidation to happen, use `ElectionMembership::create()` or `ElectionMembership::assignVoter()` instead.

---

## Adding New Tests

When you add a new method to `ElectionMembership`, follow this pattern:

1. Write the test first — confirm it fails for the right reason
2. Implement the minimum code to make it pass
3. Confirm all 25 existing tests still pass

Template for a new test:

```php
public function test_your_new_method_does_what_it_should(): void
{
    // Arrange
    $membership = ElectionMembership::assignVoter(
        $this->member->id,
        $this->election->id
    );

    // Act
    $membership->yourNewMethod();

    // Assert
    $this->assertEquals('expected_value', $membership->fresh()->some_column);
    $this->assertDatabaseHas('election_memberships', [
        'id'          => $membership->id,
        'some_column' => 'expected_value',
    ]);
}
```
