# Demo Election — Testing Guide

---

## Test Locations

All demo election tests live in `tests/Feature/Demo/`.

| Test File | What It Tests |
|-----------|--------------|
| `PublicDemoFlowTest.php` | Full 5-step public demo (anonymous, no login) |
| `DemoVoteControllerCreateTest.php` | Vote creation page (Step 3) for auth-based flow |
| `AgreementSubmissionTest.php` | Step 2 agreement acceptance |
| `CodeCreatePageTest.php` | Step 1 code entry page rendering |
| `MarkCodeAsVerifiedTest.php` | `can_vote_now` flag flip on correct code |
| `MarkUserAsVotedTest.php` | `has_voted` flag after final submission |
| `VoteAnonymityTest.php` | Verifies no `user_id` in demo_votes |
| `VotingWorkflowIntegrationTest.php` | End-to-end auth-based flow |
| `ExpiredCodeRestartTest.php` | Code expiry handling |
| `DeviceDuplicateDetectionTest.php` | Device fingerprint checks |
| `EndpointRoutingTest.php` | Route correctness |
| `SimpleTest.php` | Sanity check (user creation) |

---

## Running Tests

```bash
# Run all demo tests
php artisan test tests/Feature/Demo/ --no-coverage

# Run only the public demo tests
php artisan test tests/Feature/Demo/PublicDemoFlowTest.php --no-coverage

# Run a single test method
php artisan test tests/Feature/Demo/PublicDemoFlowTest.php \
    --filter step1_accepts_the_displayed_code_and_advances \
    --no-coverage
```

---

## PublicDemoFlowTest — Full Coverage

**File:** `tests/Feature/Demo/PublicDemoFlowTest.php`

This suite covers the entire anonymous voting flow.

### Setup

```php
protected function setUp(): void
{
    parent::setUp();

    // Platform organisation (needed by DemoElectionResolver::getPublicDemoElection)
    $this->platformOrg = Organisation::factory()->create([
        'type' => 'platform',
        'is_default' => true,
    ]);

    // Demo election for the platform org
    $this->demoElection = Election::factory()->create([
        'type' => 'demo',
        'status' => 'active',
        'organisation_id' => $this->platformOrg->id,
    ]);
}
```

### Test cases

| Test | Asserts |
|------|---------|
| `anonymous_visitor_can_start_public_demo` | GET `/public-demo/start` creates a `PublicDemoSession` and redirects to the code step |
| `same_session_reuses_existing_demo_session` | `firstOrCreate` returns the same row for the same session token |
| `step1_renders_code_entry_page_with_code_displayed` | Inertia renders `Code/DemoCode/Create` with `verification_code` and `show_code_fallback = true` |
| `step1_accepts_the_displayed_code_and_advances` | Correct code → `code_verified = true`, `current_step = 2`, redirect to agreement |
| `step1_rejects_wrong_code` | Wrong code → session errors, `code_verified` stays false |
| `step2_renders_agreement_page` | Inertia renders `Code/DemoCode/Agreement` |
| `step2_agreement_accepted_advances_to_vote` | `agreed = true`, `current_step = 3`, redirect to vote |
| `step3_renders_vote_page` | Inertia renders `Vote/DemoVote/Create` |
| `step3_vote_saved_and_advances_to_verify` | Selections stored as JSON, `current_step = 4` |
| `step4_renders_verify_page` | Inertia renders `Vote/DemoVote/Verify` |
| `step4_final_submit_records_vote_and_advances` | `has_voted = true`, `voted_at` set, `current_step = 5` |
| `step5_thank_you_page_renders` | Inertia renders `Vote/DemoVote/ThankYou` |
| `two_independent_sessions_get_isolated_demo_sessions` | Two different session tokens → two separate rows |

---

## Writing Tests for Demo Features

### Standard setup for auth-based demo tests

```php
use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\DemoCode;
use App\Models\DemoVoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyDemoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->organisation->id]);

        $this->user = User::factory()->create([
            'region' => 'Test Region',
            'email_verified_at' => now(),
        ]);

        $this->election = Election::factory()->create([
            'type' => 'demo',
            'status' => 'active',
            'organisation_id' => $this->organisation->id,
        ]);

        // DemoCode already at Step 2 complete
        DemoCode::factory()->create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'can_vote_now' => true,
            'has_agreed_to_vote' => true,
        ]);

        // Active voter slug
        $this->slug = DemoVoterSlug::factory()->create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'is_active' => true,
            'status' => 'active',
        ]);
    }

    public function test_something(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        $response->assertStatus(200);
    }
}
```

### Standard setup for public demo tests

```php
use App\Models\PublicDemoSession;

class MyPublicDemoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create([
            'type' => 'platform',
            'is_default' => true,
        ]);

        $this->election = Election::factory()->create([
            'type' => 'demo',
            'status' => 'active',
            'organisation_id' => $this->org->id,
        ]);
    }

    public function test_something(): void
    {
        $session = PublicDemoSession::factory()->create([
            'election_id' => $this->election->id,
            'current_step' => 3,
            'code_verified' => true,
            'agreed' => true,
        ]);

        $response = $this->get(
            route('public-demo.vote.show', $session->session_token)
        );

        $response->assertStatus(200);
    }
}
```

---

## Common Test Pitfalls

### `BelongsToTenant` global scope filtering results

When `current_organisation_id` is not set in session, global scopes may filter out records.

**Fix:** Always set session context in `setUp()`:
```php
session(['current_organisation_id' => $this->organisation->id]);
```

Or use `withoutGlobalScopes()` in your factories/assertions:
```php
DemoCandidacy::withoutGlobalScopes()->where(...)->get();
```

### Unique constraint violations on voter slugs

`demo_voter_slugs` has a unique index on `(user_id, election_id)`. Creating two slugs for the same user/election will fail.

**Fix:** Delete existing slugs before creating test slugs:
```php
DemoVoterSlug::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->forceDelete();
```

### PublicDemoSession factory creates its own Election

If you don't pass `election_id`, the factory creates a new Election. Posts and candidacies for that election won't exist unless you create them.

**Fix:** Always pass `election_id` explicitly and create posts/candidacies for it:
```php
$session = PublicDemoSession::factory()->create([
    'election_id' => $this->demoElection->id,
]);
```

---

## Test Coverage Status

| Area | Coverage |
|------|---------|
| Public demo flow (all 5 steps) | ✅ 13 tests |
| Auth-based code verification | ✅ Covered |
| Auth-based agreement | ✅ Covered |
| Vote anonymity | ✅ Covered |
| Session isolation | ✅ Covered |
| Regional filtering | 🚧 Partial |
| Rate limiting | 🚧 Not yet tested |
| Session expiry | 🚧 Not yet tested |
