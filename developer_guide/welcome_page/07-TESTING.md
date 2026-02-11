# Testing Documentation

## Overview

Testing follows **Test-Driven Development (TDD)** with three levels:

| Level | Type | Tools | Coverage |
|-------|------|-------|----------|
| **Unit** | Service logic | PHPUnit | Services, DTOs, Value Objects |
| **Integration** | Request → Response | PHPUnit + Inertia | Controllers, middleware |
| **Feature** | User scenarios | PHPUnit | Complete user journeys |
| **Performance** | N+1 queries, timeouts | PHPUnit + Query Log | Database efficiency |

**Target Coverage:** ≥ 80% for all services

---

## Critical Performance Tests

### ⚠️ N+1 Query Prevention (MANDATORY)

The dashboard had a **30+ second timeout** caused by N+1 queries (50+ queries instead of 6).

**Every test must verify query count:**

```php
/** @test */
public function dashboard_welcome_executes_only_6_queries()
{
    $user = User::factory()->create(['gdpr_consent_accepted_at' => now()]);

    \DB::enableQueryLog();

    $response = $this->actingAs($user)->get('/dashboard/welcome');

    $queryCount = count(\DB::getQueryLog());

    // MUST be 6, NOT 50+
    $this->assertLessThanOrEqual(6, $queryCount,
        "Expected ≤6 queries, got {$queryCount}. N+1 problem detected!");

    $response->assertStatus(200);
}

/** @test */
public function dashboard_welcome_completes_in_under_200ms()
{
    $user = User::factory()->create(['gdpr_consent_accepted_at' => now()]);

    $startTime = microtime(true);
    $this->actingAs($user)->get('/dashboard/welcome');
    $elapsed = (microtime(true) - $startTime) * 1000;

    // Should be ~180ms, never 30+ seconds
    $this->assertLessThan(200, $elapsed,
        "Response took {$elapsed}ms. Performance degradation detected!");
}
```

### Safe Relationship Loading Pattern

All services must use this pattern:

```php
// ✅ CORRECT: Check if relationship is already loaded
$organizations = $user->relationLoaded('organizations')
    ? $user->organizations
    : $user->organizations()->get();

// ❌ WRONG: Blindly queries (N+1)
$organizations = $user->organizations()->get();
```

### Method Existence Checks

All eager loads must verify methods exist:

```php
// ✅ CORRECT: Only load relationships that exist
$relationships = [];
if (method_exists(User::class, 'organizations')) {
    $relationships[] = 'organizations';
}

// ❌ WRONG: Causes RelationNotFoundException
$relationships = ['organizations', 'nonexistent_method'];
```

---

## Unit Tests

### Test Services

All services in `app/Services/Dashboard/` must have unit tests.

#### Testing RoleDetectionService

**File:** `tests/Unit/Services/Dashboard/RoleDetectionServiceTest.php`

```php
<?php

namespace Tests\Unit\Services\Dashboard;

use App\Models\User;
use App\Services\Dashboard\RoleDetectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleDetectionServiceTest extends TestCase
{
    use RefreshDatabase;

    private RoleDetectionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(RoleDetectionService::class);
    }

    /** @test */
    public function detects_admin_role()
    {
        $user = User::factory()->create();
        // Add organizationRoles (mocked for this test)
        $user->organizationRoles()->create(['name' => 'admin']);

        $roles = $this->service->getDashboardRoles($user);

        $this->assertContains('admin', $roles);
    }

    /** @test */
    public function detects_commission_role()
    {
        $user = User::factory()->create([
            'is_committee_member' => true
        ]);

        $roles = $this->service->getDashboardRoles($user);

        $this->assertContains('commission', $roles);
    }

    /** @test */
    public function detects_voter_role()
    {
        $user = User::factory()->create();
        // Add voter registrations
        $user->voterRegistrations()->create();

        $roles = $this->service->getDashboardRoles($user);

        $this->assertContains('voter', $roles);
    }

    /** @test */
    public function returns_correct_primary_role()
    {
        $user = User::factory()->create([
            'is_committee_member' => true
        ]);
        $user->organizationRoles()->create(['name' => 'admin']);

        $primary = $this->service->getPrimaryRole($user);

        // Admin > Commission > Voter
        $this->assertEquals('admin', $primary);
    }

    /** @test */
    public function detects_composite_state_for_new_user()
    {
        $user = User::factory()->create();

        $state = $this->service->detectCompositeState($user);

        $this->assertEquals('new_user_no_roles', $state);
    }

    /** @test */
    public function detects_admin_setup_started_state()
    {
        $user = User::factory()->create();
        $user->organizationRoles()->create(['name' => 'admin']);
        // Create organization
        $user->organizations()->create(['name' => 'Test Org']);

        $state = $this->service->detectCompositeState($user);

        $this->assertEquals('admin_setup_started', $state);
    }
}
```

#### Testing ConfidenceCalculator

**File:** `tests/Unit/Services/Dashboard/ConfidenceCalculatorTest.php`

```php
<?php

namespace Tests\Unit\Services\Dashboard;

use App\Models\User;
use App\Services\Dashboard\ConfidenceCalculator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfidenceCalculatorTest extends TestCase
{
    use RefreshDatabase;

    private ConfidenceCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = app(ConfidenceCalculator::class);
    }

    /** @test */
    public function new_user_has_low_confidence()
    {
        $user = User::factory()->create([
            'created_at' => now()
        ]);

        $score = $this->calculator->calculate($user);

        $this->assertLessThan(50, $score);
    }

    /** @test */
    public function established_user_has_higher_confidence()
    {
        $user = User::factory()->create([
            'created_at' => now()->subYears(2)
        ]);
        $user->update(['last_login_at' => now()->subDays(1)]);

        $score = $this->calculator->calculate($user);

        $this->assertGreaterThan(50, $score);
    }

    /** @test */
    public function multiple_roles_increase_confidence()
    {
        $user = User::factory()->create([
            'is_committee_member' => true,
            'created_at' => now()->subMonths(6)
        ]);
        $user->organizationRoles()->create(['name' => 'admin']);

        $score = $this->calculator->calculate($user);

        $this->assertGreaterThan(50, $score);
    }

    /** @test */
    public function score_ranges_0_to_100()
    {
        $users = User::factory()->count(10)->create();

        foreach ($users as $user) {
            $score = $this->calculator->calculate($user);
            $this->assertGreaterThanOrEqual(0, $score);
            $this->assertLessThanOrEqual(100, $score);
        }
    }

    /** @test */
    public function returns_correct_ui_mode()
    {
        // Score 20 → simplified
        $simplified = $this->calculator->getUIMode(20);
        $this->assertEquals('simplified', $simplified);

        // Score 55 → standard
        $standard = $this->calculator->getUIMode(55);
        $this->assertEquals('standard', $standard);

        // Score 80 → advanced
        $advanced = $this->calculator->getUIMode(80);
        $this->assertEquals('advanced', $advanced);
    }
}
```

#### Testing OnboardingTracker

**File:** `tests/Unit/Services/Dashboard/OnboardingTrackerTest.php`

```php
<?php

namespace Tests\Unit\Services\Dashboard;

use App\Models\User;
use App\Services\Dashboard\OnboardingTracker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingTrackerTest extends TestCase
{
    use RefreshDatabase;

    private OnboardingTracker $tracker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tracker = app(OnboardingTracker::class);
    }

    /** @test */
    public function new_user_is_step_1()
    {
        $user = User::factory()->create();

        $step = $this->tracker->getNextStep($user);

        $this->assertEquals(1, $step);
    }

    /** @test */
    public function user_with_organization_is_step_2()
    {
        $user = User::factory()->create();
        $user->organizations()->create(['name' => 'Test Org']);

        $step = $this->tracker->getNextStep($user);

        $this->assertEquals(2, $step);
    }

    /** @test */
    public function step_2_requires_minimum_2_members()
    {
        $user = User::factory()->create();
        $org = $user->organizations()->create(['name' => 'Test Org']);

        // Only creator (1 member)
        $step = $this->tracker->getNextStep($user);
        $this->assertEquals(2, $step); // Still on step 2

        // Add second member
        $org->members()->create(['user_id' => User::factory()->create()->id]);

        // Now should move to step 3
        $step = $this->tracker->getNextStep($user);
        $this->assertEquals(3, $step);
    }

    /** @test */
    public function step_4_requires_minimum_2_voters()
    {
        $user = User::factory()->create();
        $org = $user->organizations()->create(['name' => 'Test Org']);
        $org->members()->create(['user_id' => User::factory()->create()->id]);
        $election = $org->elections()->create(['name' => 'Test Election']);

        // Only creator as voter (1 voter)
        $step = $this->tracker->getNextStep($user);
        $this->assertEquals(4, $step); // Still on step 4

        // Add second voter
        $election->voters()->create(['user_id' => User::factory()->create()->id]);

        // Now should move to step 5
        $step = $this->tracker->getNextStep($user);
        $this->assertEquals(5, $step);
    }

    /** @test */
    public function step_details_include_progress_percentage()
    {
        $details = $this->tracker->getStepDetails(1);

        $this->assertArrayHasKey('progress', $details);
        $this->assertEquals(0, $details['progress']);
    }

    /** @test */
    public function step_3_is_50_percent()
    {
        $details = $this->tracker->getStepDetails(3);

        $this->assertEquals(50, $details['progress']);
    }
}
```

---

## Integration Tests

### Test Complete Request Flow

Integration tests verify the entire request → response cycle.

#### Testing Dashboard Welcome Route

**File:** `tests/Feature/Dashboard/WelcomePageTest.php`

```php
<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_user_cannot_access_welcome()
    {
        $response = $this->get('/dashboard/welcome');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_welcome()
    {
        $user = User::factory()->create([
            'gdpr_consent_accepted_at' => now()
        ]);

        $response = $this->actingAs($user)->get('/dashboard/welcome');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Dashboard/Welcome')
        );
    }

    /** @test */
    public function user_without_gdpr_consent_is_redirected()
    {
        $user = User::factory()->create([
            'gdpr_consent_accepted_at' => null
        ]);

        $response = $this->actingAs($user)->get('/dashboard/welcome');

        $response->assertRedirect('/consent/required');
    }

    /** @test */
    public function welcome_page_includes_user_state()
    {
        $user = User::factory()->create([
            'gdpr_consent_accepted_at' => now(),
            'display_name' => 'Max Müller'
        ]);

        $response = $this->actingAs($user)->get('/dashboard/welcome');

        $response->assertInertia(fn ($page) =>
            $page->has('userState', fn ($state) =>
                $state->has('roles')
                    ->has('primary_role')
                    ->has('confidence_score')
                    ->has('onboarding_step')
            )
        );
    }

    /** @test */
    public function welcome_page_includes_trust_signals()
    {
        $user = User::factory()->create([
            'gdpr_consent_accepted_at' => now()
        ]);

        $response = $this->actingAs($user)->get('/dashboard/welcome');

        $response->assertInertia(fn ($page) =>
            $page->has('trustSignals', fn ($signals) =>
                $signals->where('type', 'compliance')->count() > 0
            )
        );
    }

    /** @test */
    public function new_user_sees_appropriate_actions()
    {
        $user = User::factory()->create([
            'gdpr_consent_accepted_at' => now()
        ]);

        $response = $this->actingAs($user)->get('/dashboard/welcome');

        $response->assertInertia(fn ($page) =>
            $page->has('contentBlocks.0.content.cards', fn ($cards) =>
                collect($cards)->contains(fn ($card) =>
                    $card['id'] === 'create_organization'
                )
            )
        );
    }

    /** @test */
    public function admin_user_sees_organization_status_block()
    {
        $user = User::factory()->create([
            'gdpr_consent_accepted_at' => now()
        ]);
        $user->organizationRoles()->create(['name' => 'admin']);
        $user->organizations()->create(['name' => 'Test Org']);

        $response = $this->actingAs($user)->get('/dashboard/welcome');

        $response->assertInertia(fn ($page) =>
            $page->has('contentBlocks', fn ($blocks) =>
                collect($blocks)->contains(fn ($block) =>
                    $block['id'] === 'organization_status'
                )
            )
        );
    }
}
```

#### Testing Three-Role System

**File:** `tests/Feature/Dashboard/RoleSystemTest.php`

```php
<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleSystemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_user_sees_admin_actions()
    {
        $user = User::factory()->create([
            'gdpr_consent_accepted_at' => now()
        ]);
        $user->organizationRoles()->create(['name' => 'admin']);

        $response = $this->actingAs($user)->get('/dashboard/welcome');

        $response->assertInertia(fn ($page) =>
            $page->where('userState.primary_role', 'admin')
                ->where('userState.roles', fn ($roles) =>
                    in_array('admin', $roles)
                )
        );
    }

    /** @test */
    public function commission_user_sees_election_management()
    {
        $user = User::factory()->create([
            'gdpr_consent_accepted_at' => now(),
            'is_committee_member' => true
        ]);

        $response = $this->actingAs($user)->get('/dashboard/welcome');

        $response->assertInertia(fn ($page) =>
            $page->has('contentBlocks', fn ($blocks) =>
                collect($blocks)->contains(fn ($block) =>
                    $block['id'] === 'role_based_actions' &&
                    collect($block['content']['cards'])->contains(fn ($card) =>
                        $card['id'] === 'manage_election'
                    )
                )
            )
        );
    }

    /** @test */
    public function voter_user_sees_vote_actions()
    {
        $user = User::factory()->create([
            'gdpr_consent_accepted_at' => now()
        ]);
        $user->voterRegistrations()->create();

        $response = $this->actingAs($user)->get('/dashboard/welcome');

        $response->assertInertia(fn ($page) =>
            $page->where('userState.roles', fn ($roles) =>
                in_array('voter', $roles)
            )
        );
    }

    /** @test */
    public function user_with_multiple_roles_shows_all_actions()
    {
        $user = User::factory()->create([
            'gdpr_consent_accepted_at' => now(),
            'is_committee_member' => true
        ]);
        $user->organizationRoles()->create(['name' => 'admin']);
        $user->voterRegistrations()->create();

        $response = $this->actingAs($user)->get('/dashboard/welcome');

        $response->assertInertia(fn ($page) =>
            $page->where('userState.primary_role', 'admin')
                ->where('userState.has_multiple_roles', true)
                ->where('userState.roles', fn ($roles) =>
                    count($roles) === 3 &&
                    in_array('admin', $roles) &&
                    in_array('commission', $roles) &&
                    in_array('voter', $roles)
                )
        );
    }
}
```

---

## Feature Tests

### Test Complete User Journeys

Feature tests simulate realistic user scenarios.

#### New User Onboarding

**File:** `tests/Feature/Dashboard/OnboardingJourneyTest.php`

```php
<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingJourneyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_user_onboarding_flow()
    {
        // Step 1: New user created
        $user = User::factory()->create([
            'gdpr_consent_accepted_at' => now(),
            'display_name' => 'Alice Smith'
        ]);

        $response = $this->actingAs($user)->get('/dashboard/welcome');
        $response->assertInertia(fn ($page) =>
            $page->where('userState.onboarding_step', 1)
        );

        // Step 2: Creates organization
        $org = $user->organizations()->create(['name' => 'Alice\'s Voters']);

        $response = $this->actingAs($user)->get('/dashboard/welcome');
        $response->assertInertia(fn ($page) =>
            $page->where('userState.onboarding_step', 2)
        );

        // Step 3: Adds members
        $member = User::factory()->create();
        $org->members()->create(['user_id' => $member->id]);

        $response = $this->actingAs($user)->get('/dashboard/welcome');
        $response->assertInertia(fn ($page) =>
            $page->where('userState.onboarding_step', 3)
        );

        // Step 4: Creates election
        $election = $org->elections()->create([
            'name' => 'Board Election 2026'
        ]);

        $response = $this->actingAs($user)->get('/dashboard/welcome');
        $response->assertInertia(fn ($page) =>
            $page->where('userState.onboarding_step', 4)
        );

        // Step 5: Adds voters
        $voter = User::factory()->create();
        $election->voters()->create(['user_id' => $voter->id]);

        $response = $this->actingAs($user)->get('/dashboard/welcome');
        $response->assertInertia(fn ($page) =>
            $page->where('userState.onboarding_step', 5)
        );
    }

    /** @test */
    public function onboarding_progress_updates_dynamically()
    {
        $user = User::factory()->create([
            'gdpr_consent_accepted_at' => now()
        ]);

        // Progress starts at 0%
        $response = $this->actingAs($user)->get('/dashboard/welcome');
        $response->assertInertia(fn ($page) =>
            $page->has('contentBlocks', fn ($blocks) =>
                collect($blocks)->where('id', 'organization_status')
                    ->first()['content']['progress'] === 0
            )
        );

        // Create organization → 25%
        $user->organizations()->create(['name' => 'Test']);
        $response = $this->actingAs($user)->get('/dashboard/welcome');
        // Progress should be 25%
    }
}
```

---

## Frontend Safety Testing

### Vue Array Safety

All optional arrays (contentBlocks, trustSignals, pending_actions) must validate data:

```javascript
// ✅ CORRECT: Check array exists before calling methods
if (Array.isArray(this.contentBlocks) && this.contentBlocks.length > 0) {
  // Safe to iterate
}

// ❌ WRONG: TypeError if contentBlocks is null/undefined
this.contentBlocks.some(block => block.type === 'actions')
```

### Test Props Validation

```php
/** @test */
public function welcome_page_handles_missing_content_blocks()
{
    $user = User::factory()->create(['gdpr_consent_accepted_at' => now()]);

    $response = $this->actingAs($user)->get('/dashboard/welcome');

    // contentBlocks should have safe default (empty array)
    $response->assertInertia(fn ($page) =>
        $page->where('contentBlocks', [])
            ->orWhere('contentBlocks', fn ($blocks) =>
                is_array($blocks)
            )
    );
}

/** @test */
public function trust_signals_defaults_to_empty_array()
{
    $user = User::factory()->create(['gdpr_consent_accepted_at' => now()]);

    $response = $this->actingAs($user)->get('/dashboard/welcome');

    $response->assertInertia(fn ($page) =>
        $page->has('trustSignals', fn ($signals) =>
            is_array($signals)
        )
    );
}
```

### Test Circular Reference Prevention

```php
/** @test */
public function user_model_hides_relationship_properties()
{
    $user = User::factory()->create(['gdpr_consent_accepted_at' => now()]);

    $response = $this->actingAs($user)->get('/dashboard/welcome');

    $json = $response->content();

    // User object should NOT contain these relationships
    $this->assertStringNotContainsString('"organizations":', $json);
    $this->assertStringNotContainsString('"organizationRoles":', $json);
    $this->assertStringNotContainsString('"commissions":', $json);
    $this->assertStringNotContainsString('"roles":', $json);
}
```

---

## Running Tests

### Run All Tests

```bash
php artisan test
```

### Run Specific Test Suite

```bash
# Unit tests only
php artisan test tests/Unit/

# Feature tests only
php artisan test tests/Feature/

# Dashboard tests only
php artisan test tests/Feature/Dashboard/
```

### Run Single Test

```bash
# Run specific test class
php artisan test tests/Unit/Services/Dashboard/RoleDetectionServiceTest.php

# Run specific test method
php artisan test tests/Unit/Services/Dashboard/RoleDetectionServiceTest.php --filter=detects_admin_role
```

### Run with Coverage Report

```bash
php artisan test --coverage
```

### Run with Parallel Execution (faster)

```bash
php artisan test --parallel
```

---

## Testing Checklist

### For RoleDetectionService

- [ ] Detects admin role correctly
- [ ] Detects commission role correctly
- [ ] Detects voter role correctly
- [ ] Returns correct primary role (admin > commission > voter)
- [ ] Detects all composite states
- [ ] Handles users with multiple roles

### For ConfidenceCalculator

- [ ] Scores range 0-100
- [ ] New users have low confidence
- [ ] Established users have higher confidence
- [ ] Multiple roles increase confidence
- [ ] Returns correct UI mode (simplified/standard/advanced)

### For OnboardingTracker

- [ ] New user is step 1
- [ ] Step 2 requires minimum 2 members
- [ ] Step 3 requires election
- [ ] Step 4 requires minimum 2 voters
- [ ] Step 5 is complete
- [ ] Progress percentages are correct (0%, 25%, 50%, 75%, 100%)

### For Welcome Route

- [ ] Unauthenticated users redirected
- [ ] GDPR consent required
- [ ] User data pseudonymized
- [ ] Trust signals included
- [ ] Content blocks rendered
- [ ] Roles detected correctly
- [ ] Actions displayed appropriately

### For Three-Role System

- [ ] Admin sees organization actions
- [ ] Commission sees election management
- [ ] Voter sees voting actions
- [ ] Users with multiple roles see all applicable actions
- [ ] Primary role is correctly determined

---

## Debugging Failed Tests

### Test Fails: "Undefined method"

**Problem:** Service method doesn't exist

**Solution:** Check service implementation matches test expectations

### Test Fails: "Inertia assertion error"

**Problem:** Page props don't match assertion

**Solution:** Dump actual page data:
```php
$response->assertInertia(fn ($page) =>
    dd($page->all()) // Dumps all props
);
```

### Test Fails: "Database assertion"

**Problem:** Model not created or deleted

**Solution:** Check database state in test:
```php
$this->assertDatabaseHas('users', [
    'id' => $user->id,
    'display_name' => 'Max'
]);
```

---

## CI/CD Testing

### GitHub Actions Example

**File:** `.github/workflows/tests.yml`

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest

    services:
      postgres:
        image: postgres:15
        env:
          POSTGRES_PASSWORD: postgres

    steps:
      - uses: actions/checkout@v3

      - uses: php-actions/setup-php@v1
        with:
          php-version: '8.2'

      - run: composer install

      - run: php artisan migrate

      - run: php artisan test --coverage

      - uses: codecov/codecov-action@v3
```

---

## Summary

Testing ensures:

1. **RoleDetectionService** - Correct role detection (admin, commission, voter)
2. **ConfidenceCalculator** - Accurate experience scoring (0-100)
3. **OnboardingTracker** - Proper step progression (1-5)
4. **Welcome Route** - Correct data transmission and rendering
5. **Three-Role System** - Multi-role handling and priority
6. **GDPR Compliance** - Consent and pseudonymization
7. **Content Blocks** - Dynamic rendering based on user state

All tests must pass before deployment.
