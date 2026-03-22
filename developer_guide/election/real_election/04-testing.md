# 04 — Testing

**Test file:** `tests/Feature/Integration/VotingMembershipIntegrationTest.php`
**Tests:** 6 | **Assertions:** 13 | **Status:** All green

---

## Running the Tests

```bash
# Integration tests only
php artisan test tests/Feature/Integration/VotingMembershipIntegrationTest.php --no-coverage

# Full voter security suite
php artisan test tests/Feature/Integration/VotingMembershipIntegrationTest.php \
                 tests/Feature/Middleware/EnsureElectionVoterTest.php \
                 tests/Feature/ElectionVoterManagementTest.php \
                 tests/Unit/Models/ElectionMembershipTest.php --no-coverage
```

---

## What Is Tested

| # | Test | Asserts |
|---|------|---------|
| 1 | `test_ensure_election_voter_middleware_is_in_slug_route_chain` | `gatherMiddleware()` on `slug.code.create` contains `ensure.election.voter` |
| 2 | `test_unassigned_voter_is_blocked_at_code_create` | GET `slug.code.create` with no membership → redirect + `error` flash |
| 3 | `test_assigned_voter_can_access_code_create` | Active membership → no Layer 0 error flash |
| 4 | `test_demo_election_bypasses_voter_membership_check` | Demo slug, no membership → no "not assigned" error |
| 5 | `test_already_voted_user_is_still_blocked_after_layer_0_added` | Assigned + `has_voted=1` → blocked by existing Layer 1 (not broken by Layer 0) |
| 6 | `test_voter_removed_between_submission_and_verification_is_blocked` | Membership removed → GET `slug.vote.verify` → redirect + `error` |

---

## Test Setup Pattern

```php
protected function setUp(): void
{
    parent::setUp();

    Election::resetPlatformOrgCache(); // clear static BelongsToTenant cache

    $this->org = Organisation::factory()->create(['type' => 'tenant']);
    session(['current_organisation_id' => $this->org->id]);

    // organisation_id MUST match $this->org — TenantContext overwrites
    // session('current_organisation_id') with auth()->user()->organisation_id
    $this->voter = User::factory()->create([
        'email_verified_at' => now(),
        'organisation_id'   => $this->org->id,
    ]);

    $this->org->users()->attach($this->voter->id, [
        'id'   => (string) Str::uuid(),
        'role' => 'voter',
    ]);

    $this->realElection = Election::factory()->create([
        'organisation_id' => $this->org->id,
        'type'            => 'real',
        'status'          => 'active',
    ]);

    $this->demoElection = Election::factory()->create([
        'organisation_id' => $this->org->id,
        'type'            => 'demo',
        'status'          => 'active',
    ]);

    $this->voterSlug = VoterSlug::factory()->create([
        'user_id'         => $this->voter->id,
        'organisation_id' => $this->org->id,
        'election_id'     => $this->realElection->id,
        'is_active'       => true,
        'expires_at'      => now()->addHours(2),
    ]);
}
```

### Critical: `organisation_id` on the voter user

`TenantContext` middleware overwrites `session('current_organisation_id')` with
`auth()->user()->organisation_id` on every request. The voter must have
`organisation_id = $this->org->id` or the tenant context will point to a different
organisation and the election will not be found.

### Critical: `Election::resetPlatformOrgCache()`

`Election` uses a static property to cache the platform organisation. This cache
persists across test cases in the same process. Call `resetPlatformOrgCache()` in
`setUp()` to prevent cross-test contamination.

---

## Common Assertion Patterns

### Checking that Layer 0 blocked a voter

```php
$response->assertRedirect();
$response->assertSessionHas('error');
```

### Checking that Layer 0 did NOT block an assigned voter

Layer 0 uses specific error messages. Check for those strings rather than the
absence of any error (other middleware may add unrelated errors):

```php
$flashError = session('error');
if ($flashError) {
    $this->assertStringNotContainsStringIgnoringCase(
        'not assigned',
        $flashError,
        'Layer 0 should not block an assigned voter'
    );
    $this->assertStringNotContainsStringIgnoringCase(
        'not eligible to vote in this election',
        $flashError,
        'EnsureElectionVoter should not block an assigned voter'
    );
}
```

### Checking that a removed voter is blocked at verify

```php
$membership = ElectionMembership::assignVoter($this->voter->id, $this->realElection->id);
$membership->remove('Reason', null); // simulate admin removal

$response = $this->actingAs($this->voter)
    ->withSession(['current_organisation_id' => $this->org->id])
    ->get(route('slug.vote.verify', ['vslug' => $this->voterSlug->slug]));

$response->assertRedirect();
$response->assertSessionHas('error');
```

---

## Inserting Code Records in Tests

The `codes` table schema has changed significantly from `CodeFactory`. Do not use
`Code::factory()->create()` for minimal test records — it will fail on missing columns.

Use `DB::table('codes')->insert()` instead:

```php
\Illuminate\Support\Facades\DB::table('codes')->insert([
    'id'               => (string) \Illuminate\Support\Str::uuid(),
    'organisation_id'  => $this->org->id,
    'user_id'          => $this->voter->id,
    'election_id'      => $this->realElection->id,
    'code1'            => '000000',
    'code2'            => '000000',
    'has_voted'        => 1,
    'can_vote_now'     => 0,
    'is_code1_usable'  => 0,
    'created_at'       => now(),
    'updated_at'       => now(),
]);
```

Actual `codes` table columns (as of 2026-03-19):
```
id, organisation_id, election_id, voter_id, user_id, client_ip,
code1, code2, is_code1_usable, is_code2_usable, code1_used_at, code2_used_at,
can_vote_now, has_voted, voting_time_min, created_at, updated_at, deleted_at,
device_fingerprint_hash, device_metadata_anonymized, session_name, voting_slug
```

---

## Writing New Tests

Add to `tests/Feature/Integration/VotingMembershipIntegrationTest.php`:

```php
public function test_my_scenario(): void
{
    // Arrange
    ElectionMembership::assignVoter($this->voter->id, $this->realElection->id);

    // Act
    $response = $this->actingAs($this->voter)
        ->withSession(['current_organisation_id' => $this->org->id])
        ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

    // Assert
    // ...
}
```

Always include `->withSession(['current_organisation_id' => $this->org->id])`.
Without it, `TenantContext` will overwrite the session with the platform org ID.
