# Testing Multi-Tenancy

Comprehensive guide to testing tenant isolation and multi-tenancy behavior.

## Overview

Multi-tenancy testing requires verifying:
1. **Tenant Isolation** - Data from one tenant is never visible to another
2. **Auto-Fill** - New records automatically get the correct organisation_id
3. **Query Scoping** - All queries are automatically filtered by tenant
4. **Access Control** - Cross-tenant access is denied
5. **Context Switching** - Tenant context can be switched correctly

## Test Infrastructure

### Base Test Class Helper

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;

abstract class TenantTestCase extends TestCase
{
    protected Organisation $org1;
    protected Organisation $org2;
    protected User $org1User;
    protected User $org2User;

    public function setUp(): void
    {
        parent::setUp();

        // Disable foreign key checks for testing
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Create test organizations
        $this->org1 = Organisation::create(['name' => 'Organisation 1']);
        $this->org2 = Organisation::create(['name' => 'Organisation 2']);

        // Create test users for each org
        $this->org1User = User::factory()->create(['organisation_id' => $this->org1->id]);
        $this->org2User = User::factory()->create(['organisation_id' => $this->org2->id]);
    }

    public function tearDown(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        parent::tearDown();
    }

    /**
     * Act as a specific user and set tenant context
     */
    protected function actAsUser(User $user): void
    {
        $this->actingAs($user);
        session(['current_organisation_id' => $user->organisation_id]);
    }
}
```

## Test Patterns

### Pattern 1: Tenant Isolation Test

```php
/**
 * @test
 * Test that data from different tenants is isolated
 */
public function test_elections_are_isolated_by_tenant()
{
    // Arrange: Create elections for different tenants
    $this->actAsUser($this->org1User);
    $election1 = Election::create(['name' => 'Election 1', 'organisation_id' => $this->org1->id]);

    $this->actAsUser($this->org2User);
    $election2 = Election::create(['name' => 'Election 2', 'organisation_id' => $this->org2->id]);

    // Act: Query as org1
    $this->actAsUser($this->org1User);
    $elections = Election::all();

    // Assert: Should only see org1's election
    $this->assertCount(1, $elections);
    $this->assertEquals($election1->id, $elections->first()->id);
}
```

### Pattern 2: Auto-Fill Test

```php
/**
 * @test
 * Test that organisation_id is auto-filled on creation
 */
public function test_election_auto_fills_organisation_id()
{
    // Arrange
    $this->actAsUser($this->org1User);

    // Act: Create without explicitly setting organisation_id
    $election = Election::create(['name' => 'Test Election']);

    // Assert: Should be auto-filled
    $this->assertEquals($this->org1->id, $election->organisation_id);
}
```

### Pattern 3: Cross-Tenant Access Denial Test

```php
/**
 * @test
 * Test that accessing another tenant's data returns null
 */
public function test_cannot_access_other_tenant_data()
{
    // Arrange: Create election as org1
    $this->actAsUser($this->org1User);
    $election = Election::create(['name' => 'Secret Election']);
    $electionId = $election->id;

    // Act: Try to access as org2
    $this->actAsUser($this->org2User);
    $foundElection = Election::find($electionId);

    // Assert: Should return null (not found)
    $this->assertNull($foundElection);
}
```

### Pattern 4: Bypass Test

```php
/**
 * @test
 * Test that admin can bypass scoping with withoutGlobalScopes
 */
public function test_admin_can_bypass_scoping()
{
    // Arrange: Create elections for different tenants
    $this->actAsUser($this->org1User);
    $election1 = Election::create(['name' => 'Election 1']);

    $this->actAsUser($this->org2User);
    $election2 = Election::create(['name' => 'Election 2']);

    // Act: Query with bypass (admin operation)
    $this->actAsUser($this->org1User);
    $allElections = Election::withoutGlobalScopes()->get();

    // Assert: Should see both elections
    $this->assertCount(2, $allElections);
}
```

### Pattern 5: Update Test

```php
/**
 * @test
 * Test that updates respect tenant scoping
 */
public function test_update_respects_tenant_scoping()
{
    // Arrange
    $this->actAsUser($this->org1User);
    $election = Election::create(['name' => 'Original', 'status' => 'draft']);

    // Act: Update as org1
    $election->update(['status' => 'active']);

    // Assert: Should have updated
    $this->assertEquals('active', $election->fresh()->status);

    // Act: Try to update as org2
    $this->actAsUser($this->org2User);
    $updated = Election::find($election->id);

    // Assert: Should not find it to update
    $this->assertNull($updated);
}
```

### Pattern 6: Delete Test

```php
/**
 * @test
 * Test that deletes respect tenant scoping
 */
public function test_delete_respects_tenant_scoping()
{
    // Arrange
    $this->actAsUser($this->org1User);
    $election = Election::create(['name' => 'To Delete']);
    $electionId = $election->id;

    // Act: Delete as org1
    $election->delete();

    // Assert: Should be deleted
    $this->assertNull(Election::withoutGlobalScopes()->find($electionId));
}
```

## Advanced Testing

### Testing with Related Models

```php
/**
 * @test
 * Test that related models respect tenant scoping
 */
public function test_related_models_respect_tenant_scoping()
{
    // Arrange
    $this->actAsUser($this->org1User);
    $election = Election::create(['name' => 'Election 1']);
    $post1 = Post::create(['election_id' => $election->id, 'name' => 'Position 1']);
    $candidacy1 = Candidacy::create(['post_id' => $post1->id, 'user_id' => $this->org1User->id]);

    $this->actAsUser($this->org2User);
    $election2 = Election::create(['name' => 'Election 2']);
    $post2 = Post::create(['election_id' => $election2->id, 'name' => 'Position 2']);
    $candidacy2 = Candidacy::create(['post_id' => $post2->id, 'user_id' => $this->org2User->id]);

    // Act: Query as org1
    $this->actAsUser($this->org1User);
    $elections = Election::all();
    $posts = Post::all();
    $candidacies = Candidacy::all();

    // Assert: Should only see org1's data
    $this->assertCount(1, $elections);
    $this->assertCount(1, $posts);
    $this->assertCount(1, $candidacies);
}
```

### Testing Batch Operations

```php
/**
 * @test
 * Test that batch operations respect tenant scoping
 */
public function test_batch_operations_respect_tenant_scoping()
{
    // Arrange
    $this->actAsUser($this->org1User);
    Election::create(['name' => 'Election 1', 'status' => 'draft']);
    Election::create(['name' => 'Election 2', 'status' => 'draft']);

    // Act: Batch update as org1
    Election::all()->each(function ($election) {
        $election->update(['status' => 'active']);
    });

    // Assert: All org1 elections updated
    $this->assertEquals(2, Election::where('status', 'active')->count());

    // Act: Check org2 still sees nothing
    $this->actAsUser($this->org2User);

    // Assert: Org2 should see 0
    $this->assertEquals(0, Election::count());
}
```

## Performance Testing

### Query Count Test

```php
/**
 * @test
 * Test that tenant scoping doesn't cause N+1 queries
 */
public function test_no_n_plus_one_queries()
{
    // Arrange
    $this->actAsUser($this->org1User);
    Election::factory()->times(5)->create();

    // Act & Assert
    \DB::enableQueryLog();

    $elections = Election::with('posts')->get();

    $queryCount = count(\DB::getQueryLog());

    // Should be 2 queries: elections + posts (not 6 = 1 + 5)
    $this->assertLessThanOrEqual(2, $queryCount);
}
```

### Index Performance Test

```php
/**
 * @test
 * Test that organisation_id column is indexed for performance
 */
public function test_organisation_id_column_is_indexed()
{
    // Check that index exists
    $indexes = \DB::select(\DB::raw("SHOW INDEX FROM elections WHERE Column_name = 'organisation_id'"));

    $this->assertNotEmpty($indexes, 'organisation_id column should be indexed');
}
```

## Testing Considerations

### Database State

Always reset database between tests:

```php
protected function setUp(): void
{
    parent::setUp();

    // Option 1: Use RefreshDatabase trait
    // use RefreshDatabase;

    // Option 2: Manually reset
    \DB::statement('SET FOREIGN_KEY_CHECKS=0');
    \DB::table('elections')->truncate();
    \DB::table('posts')->truncate();
    \DB::statement('SET FOREIGN_KEY_CHECKS=1');
}
```

### Session Context

Always set session context before operations:

```php
// ❌ Wrong - session not set
$election = Election::create(['name' => 'Test']);

// ✅ Correct - session set first
session(['current_organisation_id' => 1]);
$election = Election::create(['name' => 'Test']);

// ✅ Using helper
$this->actAsUser($user); // Sets both auth + session
$election = Election::create(['name' => 'Test']);
```

### Authentication Context

Always authenticate when needed:

```php
// ❌ Wrong - not authenticated
session(['current_organisation_id' => 1]);
$election = Election::create(['name' => 'Test']);

// ✅ Correct - authenticated first
$this->actingAs($user);
session(['current_organisation_id' => 1]);
$election = Election::create(['name' => 'Test']);

// ✅ Using helper (does both)
$this->actAsUser($user);
$election = Election::create(['name' => 'Test']);
```

## Test Categories

### 1. Unit Tests (Domain Logic)

Test Value Objects and Domain Services in isolation:

```php
class TenantIsolationTest extends TestCase
{
    public function test_value_object_validates_tenant_id()
    {
        // Test domain logic, no database
        $tenant = new TenantId(1);
        $this->assertEquals(1, $tenant->value());
    }
}
```

### 2. Integration Tests (Models + Database)

Test models with database:

```php
class ElectionModelTest extends TenantTestCase
{
    public function test_election_respects_tenant_scoping()
    {
        // Test with actual database
        $this->actAsUser($this->org1User);
        $election = Election::create(['name' => 'Test']);
        $this->assertEquals($this->org1->id, $election->organisation_id);
    }
}
```

### 3. Feature Tests (Full Request/Response)

Test HTTP endpoints:

```php
class ElectionApiTest extends TenantTestCase
{
    public function test_api_respects_tenant_isolation()
    {
        $response = $this->actAsUser($this->org1User)
            ->getJson('/api/elections');

        $response->assertStatus(200)
            ->assertJsonCount(0); // No elections created
    }
}
```

## Running Tests

```bash
# Run all tests
php artisan test

# Run specific test class
php artisan test tests/Feature/TenantIsolationTest.php

# Run specific test method
php artisan test tests/Feature/TenantIsolationTest.php --filter=test_tenant_isolation

# Run with verbose output
php artisan test --verbose

# Run with coverage report
php artisan test --coverage

# Run only failed tests from last run
php artisan test --only-failures
```

## Coverage Report

Generate coverage report:

```bash
php artisan test --coverage --coverage-html coverage/
```

This creates an HTML report in `coverage/` directory showing:
- Line coverage
- Branch coverage
- Method coverage

Aim for:
- **≥ 80% line coverage** for production code
- **100% coverage** for critical tenant isolation logic

## Troubleshooting Tests

### Issue: Tests fail with "Unknown column 'organisation_id'"

**Cause**: Migration hasn't been run

**Solution**:
```bash
php artisan migrate
php artisan test
```

### Issue: Tests fail with "No session context set"

**Cause**: Session not being set in test

**Solution**:
```php
// Always use actAsUser() helper or set session manually
$this->actAsUser($this->org1User);
// Now queries will work
```

### Issue: Foreign key constraint errors

**Cause**: Related records don't exist

**Solution**:
```php
// Disable FK checks in setUp/tearDown
public function setUp(): void
{
    parent::setUp();
    \DB::statement('SET FOREIGN_KEY_CHECKS=0');
}

public function tearDown(): void
{
    \DB::statement('SET FOREIGN_KEY_CHECKS=1');
    parent::tearDown();
}
```

## Best Practices

1. **Test One Thing** - Each test should verify one behavior
2. **Use Descriptive Names** - `test_election_respects_tenant_scoping()` not `test_election()`
3. **Arrange-Act-Assert** - Structure all tests this way
4. **Use Factories** - Create test data with factories, not raw array
5. **Isolate Tests** - Each test should be independent
6. **Test Edge Cases** - Null values, empty results, boundary conditions
7. **Document Why** - Comments explain business rules, not code mechanics

## Summary

| Test Type | Purpose | Example |
|-----------|---------|---------|
| **Isolation** | Verify data separation | Org1 can't see Org2 data |
| **Auto-Fill** | Verify auto-population | organisation_id auto-filled |
| **Scoping** | Verify query filtering | All queries scoped |
| **Access Control** | Verify denial | Find() returns null for other tenant |
| **Bypass** | Verify admin access | withoutGlobalScopes() works |
| **Performance** | Verify no N+1 | Query count is low |

---

**Next**: See [BEST_PRACTICES.md](./BEST_PRACTICES.md) for development best practices.
