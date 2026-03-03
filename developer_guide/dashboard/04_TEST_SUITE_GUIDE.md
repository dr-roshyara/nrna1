# Test Suite Guide: DashboardResolverPriorityTest

## Overview

The **DashboardResolverPriorityTest** is a comprehensive test suite covering all 6 priorities of the routing system with 16 test cases.

**File:** `tests/Feature/Auth/DashboardResolverPriorityTest.php`

---

## Test Coverage

### All 16 Tests

```
PRIORITY 1 (Active Voting Session) - 2 tests
  ✓ priority_1_active_voting_session_redirects_to_voting_portal
  ✓ priority_1_takes_precedence_over_multiple_active_elections

PRIORITY 2 (Active Election) - 3 tests
  ✓ priority_2_active_election_redirects_to_election_dashboard
  ✓ priority_2_skips_elections_where_user_already_voted
  ✓ priority_2_ignores_elections_outside_voting_window

PRIORITY 3 (New User Welcome) - 3 tests
  ✓ priority_3_new_user_verified_but_no_org_goes_to_welcome
  ✓ priority_3_new_user_with_platform_org_only_goes_to_welcome
  ✓ priority_3_skips_if_user_already_onboarded

PRIORITY 4 (Multiple Roles) - 2 tests
  ✓ priority_4_user_with_multiple_roles_goes_to_role_selection
  ✓ priority_4_user_with_admin_and_commission_roles_goes_to_role_selection

PRIORITY 5 (Single Role) - 2 tests
  ✓ priority_5_single_admin_role_redirects_to_organisation_page
  ✓ priority_5_single_commission_role_redirects_to_commission_dashboard

PRIORITY 6 (Fallback) - 1 test
  ✓ priority_6_user_with_no_roles_goes_to_default_dashboard

PRECEDENCE TESTS - 3 tests
  ✓ active_voting_takes_precedence_over_new_user_welcome
  ✓ active_election_takes_precedence_over_new_user_welcome
  ✓ roles_take_precedence_over_welcome_when_onboarded

TOTAL: 16 tests
```

---

## Running Tests

### Run All Dashboard Resolver Tests

```bash
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest
```

**Expected Output:**
```
PASS  Tests\Feature\Auth\DashboardResolverPriorityTest
  ✓ priority 1 active voting session redirects to voting portal
  ✓ priority 1 takes precedence over multiple active elections
  ✓ priority 2 active election redirects to election dashboard
  [... 13 more tests ...]

Tests:  16 passed (48 assertions)
Time:   2.34s
```

### Run Specific Priority Tests

```bash
# Only Priority 1 tests
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest --filter="priority_1"

# Only Priority 2 tests
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest --filter="priority_2"

# Only precedence tests
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest --filter="precedence"
```

### Run Single Test

```bash
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest::priority_1_active_voting_session_redirects_to_voting_portal
```

### Run with Verbose Output

```bash
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest -v
```

**Shows:**
- Each test name
- Pass/fail status
- Execution time

### Run with Very Verbose Output

```bash
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest -vv
```

**Shows:**
- All assertions
- Database queries (if enabled)
- Request/response details

---

## Test Structure (Arrange-Act-Assert)

### Example Test

```php
/** @test */
public function priority_1_active_voting_session_redirects_to_voting_portal()
{
    // ========== ARRANGE ==========
    // Set up preconditions

    // Create user with verified email
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'onboarded_at' => now(),
    ]);

    // Create organisation
    $org = Organisation::factory()->create();

    // Create election
    $election = Election::factory()->create([
        'organisation_id' => $org->id,
        'status' => 'active',
    ]);

    // Assign user to organisation
    DB::table('user_organisation_roles')->insert([
        'user_id' => $user->id,
        'organisation_id' => $org->id,
        'role' => 'member',
    ]);

    // Create active voting session
    DB::table('voter_slugs')->insert([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'slug' => 'active-voting-slug',
        'expires_at' => now()->addDay(),
        'current_step' => 2,      // In middle of voting
        'is_active' => true,
        'step_meta' => json_encode([]),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // ========== ACT ==========
    // Perform the action being tested

    $response = $this->actingAs($user)->get(route('dashboard'));

    // ========== ASSERT ==========
    // Verify the outcome

    // Check that response is a redirect
    $response->assertRedirect();

    // Check that it redirects to voting route
    $this->assertStringContainsString(
        'vote.start',
        $response->headers->get('Location')
    );
}
```

### Key Pattern Elements

1. **@test annotation** - Marks method as test
2. **Arrange** - Set up all preconditions (users, organisations, etc.)
3. **Act** - Perform the action (navigate to /dashboard)
4. **Assert** - Check the result (correct redirect)

---

## Database Traits

### RefreshDatabase

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardResolverPriorityTest extends TestCase
{
    use RefreshDatabase;  // Migrates & refreshes DB before each test
}
```

**What it does:**
- Runs all migrations before each test
- Starts with empty, clean database
- Rolls back after test completes
- Each test is isolated

**Why?**
- Tests don't interfere with each other
- Consistent starting state
- Easy to debug (no leftover data)
- Safe to run in parallel

---

## Common Test Assertions

### Redirect Assertions

```php
$response->assertRedirect();                              // Is a redirect?
$response->assertRedirect('/expected-path');              // Redirect to path?
$response->assertRedirect(route('named.route'));          // Redirect to route?
$response->assertRedirectToRoute('dashboard.welcome');    // Redirect to named route?
$response->assertStatus(302);                             // HTTP 302 redirect?
```

### String Assertions

```php
$this->assertStringContainsString('vote.start', $location);  // String contains?
$this->assertStringNotContainsString('dashboard.welcome', $location);  // Not contains?
```

### Database Assertions

```php
$this->assertDatabaseHas('users', [
    'id' => $user->id,
    'email_verified_at' => now(),
]);

$this->assertDatabaseCount('voter_slugs', 1);  // Exactly 1 row?

$this->assertDatabaseMissing('elections', [
    'slug' => 'non-existent',
]);
```

### Existence Assertions

```php
$this->assertTrue($condition);      // Assertion is true?
$this->assertFalse($condition);     // Assertion is false?
$this->assertNull($value);          // Value is null?
$this->assertNotNull($value);       // Value is not null?
```

---

## Test Data Factories

### Using Factories

```php
// Create single user
$user = User::factory()->create();

// Create user with specific attributes
$user = User::factory()->create([
    'email' => 'test@example.com',
    'email_verified_at' => now(),
]);

// Create multiple users
$users = User::factory(5)->create();

// Create without saving
$user = User::factory()->make();
```

### Available Factories

```php
User::factory()                    // User model
Organisation::factory()            // Organisation model
Election::factory()                // Election model
```

### Custom Factory Attributes

```php
// In database/factories/UserFactory.php
public function definition()
{
    return [
        'name' => $this->faker->name(),
        'email' => $this->faker->unique()->safeEmail(),
        'email_verified_at' => now(),  // Default verified
        'password' => Hash::make('password'),
        'onboarded_at' => now(),       // Default onboarded
    ];
}
```

---

## Testing Authenticated Users

### actingAs() Method

```php
$user = User::factory()->create();
$response = $this->actingAs($user)->get(route('dashboard'));
```

**What it does:**
- Authenticates user for this request only
- Sets auth guard to $user
- Populates `auth()->user()`
- Doesn't create real session

### Multiple Requests with Same User

```php
$user = User::factory()->create();
$this->actingAs($user);

$response1 = $this->get(route('dashboard'));
$response2 = $this->post(route('something'), []);  // Still authenticated
```

---

## Debugging Failed Tests

### When Test Fails

```bash
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest::priority_1_active_voting_session_redirects_to_voting_portal
```

**Output:**
```
FAIL  priority_1_active_voting_session_redirects_to_voting_portal
Expected response status code [301, 302, 303, 307, 308] but received 200.

The following exception occurred during the last request:
ErrorException: Undefined array key "REMOTE_ADDR" ...
```

### Common Failures

#### 1. "Expected redirect but received 200"
**Cause:** DashboardResolver returned 200 instead of redirect

**Debug:**
```php
dd($response->json());  // See actual response
dd(auth()->user());     // Check user is authenticated
```

#### 2. "Redirect location doesn't contain expected string"
**Cause:** Wrong redirect route

**Debug:**
```php
dd($response->headers->get('Location'));  // See actual redirect
```

#### 3. "Column not found" error
**Cause:** Test data using non-existent column

**Debug:**
- Check actual table schema in migration
- Update test data to use correct columns

#### 4. "Undefined method" error
**Cause:** Method doesn't exist on model or service

**Debug:**
- Check method exists in source file
- Check method name is spelled correctly
- Check method is public (not private)

---

## Test Output Examples

### Passing Tests

```
PASS  Tests\Feature\Auth\DashboardResolverPriorityTest (2.45s)
  ✓ priority 1 active voting session redirects to voting portal                    49.19s
  ✓ priority 1 takes precedence over multiple active elections                     0.16s
  ✓ priority 2 active election redirects to election dashboard                     0.24s
  ✓ priority 2 skips elections where user already voted                            0.10s
  ✓ priority 2 ignores elections outside voting window                             0.13s
  ✓ priority 3 new user verified but no org goes to welcome                        0.11s
  ✓ priority 3 new user with platform org only goes to welcome                     0.09s
  ✓ priority 3 skips if user already onboarded                                     0.11s
  ✓ priority 4 user with multiple roles goes to role selection                     0.08s
  ✓ priority 4 user with admin and commission roles goes to role selection         0.09s
  ✓ priority 5 single admin role redirects to organisation page                    0.08s
  ✓ priority 5 single commission role redirects to commission dashboard            0.09s
  ✓ priority 6 user with no roles goes to default dashboard                        0.08s
  ✓ active voting takes precedence over new user welcome                           0.08s
  ✓ active election takes precedence over new user welcome                         0.12s
  ✓ roles take precedence over welcome when onboarded                              0.13s

Tests:  16 passed (48 assertions)
Time:   2.34s
```

### Failing Tests

```
FAIL  Tests\Feature\Auth\DashboardResolverPriorityTest
  ✗ priority_1_active_voting_session_redirects_to_voting_portal

Failed asserting that 'http://localhost/dashboard' contains "vote.start".

Test:   priority_1_active_voting_session_redirects_to_voting_portal
File:   tests/Feature/Auth/DashboardResolverPriorityTest.php:71
Line:   71

Tests:  1 failed, 15 passed
```

---

## Writing New Tests

### Template

```php
/** @test */
public function [test_name]()
{
    // Arrange
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'onboarded_at' => now(),
    ]);

    // ... set up preconditions

    // Act
    $response = $this->actingAs($user)->get(route('dashboard'));

    // Assert
    $response->assertRedirect(route('expected.route'));
}
```

### Naming Convention

```
priority_[number]_[description]_[expected_outcome]

Examples:
✓ priority_1_active_voting_session_redirects_to_voting_portal
✓ priority_2_active_election_redirects_to_election_dashboard
✓ priority_3_new_user_verified_but_no_org_goes_to_welcome
```

---

## Best Practices

✅ **Use Factories** - Don't hardcode data

✅ **One Assertion Per Test** - Or group related assertions

✅ **Clear Names** - Test name describes what it tests

✅ **Arrange-Act-Assert** - Clear three-part structure

✅ **RefreshDatabase** - Fresh DB per test

✅ **Verify Preconditions** - Check setup worked

❌ **Don't Use Real Emails** - Use factories with faker

❌ **Don't Depend on Test Order** - Each test is independent

❌ **Don't Test Laravel** - Assume framework works

❌ **Don't Use Sleep** - Wait for events properly

---

**Last Updated:** March 4, 2026
