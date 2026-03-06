# Model Relationship Testing Guide

Comprehensive guide for testing model relationships in Phase A.

---

## Test-Driven Development (TDD) Workflow

### Step 1: Write Failing Test

```php
// tests/Unit/Models/YourModelTest.php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\YourModel;
use App\Models\RelatedModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class YourModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function model_belongs_to_parent()
    {
        // Arrange
        $parent = ParentModel::factory()->create();
        $model = YourModel::create([
            'id' => Str::uuid()->toString(),
            'parent_id' => $parent->id,
            'name' => 'Test Model',
        ]);

        // Act & Assert
        $this->assertEquals($parent->id, $model->parent->id);
    }
}
```

Run the test:
```bash
php artisan test tests/Unit/Models/YourModelTest.php
```

Expected result: **FAIL** (relationship not implemented yet)

---

### Step 2: Implement Relationship

```php
// app/Models/YourModel.php

class YourModel extends Model
{
    public function parent()
    {
        return $this->belongsTo(ParentModel::class)
                    ->withoutGlobalScopes();
    }
}
```

---

### Step 3: Verify Test Passes

Run the test again:
```bash
php artisan test tests/Unit/Models/YourModelTest.php
```

Expected result: **PASS** ✅

---

### Step 4: Commit

```bash
git add tests/Unit/Models/YourModelTest.php app/Models/YourModel.php
git commit -m "feat: Implement YourModel relationships with tests"
```

---

## Testing Different Relationship Types

### Testing BelongsTo Relationship

```php
/** @test */
public function post_belongs_to_organisation()
{
    // Arrange - Create parent
    $org = Organisation::factory()->tenant()->create();

    // Act - Create child with FK
    $post = Post::create([
        'id' => Str::uuid()->toString(),
        'organisation_id' => $org->id,
        'election_id' => Str::uuid()->toString(),
        'name' => 'Test Post',
        'is_national_wide' => true,
        'required_number' => 1,
    ]);

    // Assert - Verify relationship
    $this->assertEquals($org->id, $post->organisation->id);
    $this->assertIsObject($post->organisation);
    $this->assertInstanceOf(Organisation::class, $post->organisation);
}
```

**Key Points:**
- Create parent first
- Create child with FK to parent
- Assert child can access parent via relationship
- Verify type with `assertInstanceOf`

---

### Testing HasMany Relationship

```php
/** @test */
public function election_has_many_posts()
{
    // Arrange
    $org = Organisation::factory()->tenant()->create();
    $election = Election::factory()->forOrganisation($org)->create();

    // Create multiple posts
    $post1 = Post::create([
        'id' => Str::uuid()->toString(),
        'organisation_id' => $org->id,
        'election_id' => $election->id,
        'name' => 'Post 1',
        'is_national_wide' => true,
        'required_number' => 1,
    ]);

    $post2 = Post::create([
        'id' => Str::uuid()->toString(),
        'organisation_id' => $org->id,
        'election_id' => $election->id,
        'name' => 'Post 2',
        'is_national_wide' => true,
        'required_number' => 1,
    ]);

    // Assert - Count and verify
    $this->assertCount(2, $election->posts);
    $this->assertContains($post1->id, $election->posts->pluck('id'));
    $this->assertContains($post2->id, $election->posts->pluck('id'));
}
```

**Key Points:**
- Create multiple children
- Use `assertCount()` to verify count
- Use `assertContains()` to verify specific records
- Use `->pluck()` to extract IDs for comparison

---

### Testing BelongsToMany Relationship

```php
/** @test */
public function organisation_belongs_to_many_users_via_pivot()
{
    // Arrange
    $org = Organisation::factory()->tenant()->create();

    // Create users directly via DB (factory issues with org_id)
    $user1Id = Str::uuid()->toString();
    $user2Id = Str::uuid()->toString();

    DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
        $user1Id,
        $org->id,
        'User 1',
        'user1@example.com',
        bcrypt('password'),
        now(),
        now(),
    ]);

    DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
        $user2Id,
        $org->id,
        'User 2',
        'user2@example.com',
        bcrypt('password'),
        now(),
        now(),
    ]);

    $user1 = User::find($user1Id);
    $user2 = User::find($user2Id);

    // Create pivot records
    UserOrganisationRole::create([
        'user_id' => $user1->id,
        'organisation_id' => $org->id,
        'role' => 'admin',
    ]);

    UserOrganisationRole::create([
        'user_id' => $user2->id,
        'organisation_id' => $org->id,
        'role' => 'member',
    ]);

    // Assert - Verify belongsToMany relationship
    $users = $org->users()->get();
    $this->assertCount(2, $users);

    // Verify pivot data
    $admin = $users->where('id', $user1->id)->first();
    $this->assertEquals('admin', $admin->pivot->role);
}
```

**Key Points:**
- Create pivot records explicitly
- Access pivot data via `->pivot` attribute
- Verify belongsToMany can filter by pivot: `->wherePivot('role', 'admin')`

---

### Testing HasManyThrough Relationship

```php
/** @test */
public function election_has_many_candidacies_through_posts()
{
    // Arrange
    $org = Organisation::factory()->tenant()->create();
    $election = Election::factory()->forOrganisation($org)->create();

    // Create intermediate (post)
    $post = Post::create([
        'id' => Str::uuid()->toString(),
        'organisation_id' => $org->id,
        'election_id' => $election->id,
        'name' => 'President',
        'is_national_wide' => true,
        'required_number' => 1,
    ]);

    // Create final (candidacy through post)
    $candidacy = Candidacy::create([
        'id' => Str::uuid()->toString(),
        'organisation_id' => $org->id,
        'post_id' => $post->id,
        'name' => 'John Smith',
        'description' => 'Candidate bio',
        'position_order' => 1,
        'status' => 'approved',
    ]);

    // Assert - Access through intermediate
    $this->assertCount(1, $election->candidacies);
    $this->assertEquals($candidacy->id, $election->candidacies->first()->id);

    // Verify we can chain constraints
    $approved = $election->candidacies()
        ->where('status', 'approved')
        ->get();
    $this->assertCount(1, $approved);
}
```

**Key Points:**
- Create intermediate model first
- Create final model with FK to intermediate
- Test hasManyThrough can access final through intermediate
- Test chaining additional constraints

---

## Testing with Global Scopes

### ❌ Wrong Way - Test Fails

```php
/** @test */
public function organisation_has_many_elections()
{
    $org = Organisation::factory()->tenant()->create();

    Election::factory()->forOrganisation($org)->create();
    Election::factory()->forOrganisation($org)->create();

    // WRONG - No session context set!
    $elections = Election::all();

    // This will fail because BelongsToTenant scope filters results
    $this->assertCount(2, $elections);  // FAILS - empty array
}
```

### ✅ Correct Way - Test Passes

```php
/** @test */
public function organisation_has_many_elections()
{
    $org = Organisation::factory()->tenant()->create();

    Election::factory()->forOrganisation($org)->create();
    Election::factory()->forOrganisation($org)->create();

    // Method 1: Set session context
    session(['current_organisation_id' => $org->id]);
    $elections = Election::all();
    $this->assertCount(2, $elections);

    // Method 2: Bypass scope explicitly
    $elections = Election::withoutGlobalScopes()
        ->where('organisation_id', $org->id)
        ->get();
    $this->assertCount(2, $elections);

    // Method 3: Use relationship
    $elections = $org->elections;
    $this->assertCount(2, $elections);
}
```

**Key Points:**
- Set `session(['current_organisation_id' => $org->id])` if needed
- Use `withoutGlobalScopes()` for direct queries in tests
- Use parent relationships instead (`$org->elections`)

---

## Testing Scopes

### Testing Query Scope

```php
/** @test */
public function post_scope_for_organisation_filters_correctly()
{
    // Arrange - Create two organisations with posts
    $org1 = Organisation::factory()->tenant()->create();
    $org2 = Organisation::factory()->tenant()->create();

    $election1 = Election::factory()->forOrganisation($org1)->create();
    $election2 = Election::factory()->forOrganisation($org2)->create();

    $post1 = Post::create([
        'id' => Str::uuid()->toString(),
        'organisation_id' => $org1->id,
        'election_id' => $election1->id,
        'name' => 'Org1 Post',
        'is_national_wide' => true,
        'required_number' => 1,
    ]);

    $post2 = Post::create([
        'id' => Str::uuid()->toString(),
        'organisation_id' => $org2->id,
        'election_id' => $election2->id,
        'name' => 'Org2 Post',
        'is_national_wide' => true,
        'required_number' => 1,
    ]);

    // Act - Use scope
    $org1_posts = Post::forOrganisation($org1->id)->get();
    $org2_posts = Post::forOrganisation($org2->id)->get();

    // Assert - Verify filtering
    $this->assertCount(1, $org1_posts);
    $this->assertEquals($post1->id, $org1_posts->first()->id);

    $this->assertCount(1, $org2_posts);
    $this->assertEquals($post2->id, $org2_posts->first()->id);
}
```

**Key Points:**
- Create test data in multiple organisations
- Use scope to filter
- Verify only expected records are returned
- Verify filtering is accurate

---

## Testing Constraints on Relationships

### Testing Approved Candidacies Only

```php
/** @test */
public function post_can_filter_approved_candidacies()
{
    // Arrange
    $org = Organisation::factory()->tenant()->create();
    $election = Election::factory()->forOrganisation($org)->create();
    $post = Post::create([
        'id' => Str::uuid()->toString(),
        'organisation_id' => $org->id,
        'election_id' => $election->id,
        'name' => 'President',
        'is_national_wide' => true,
        'required_number' => 1,
    ]);

    // Create mixed candidacies
    $approved = Candidacy::create([
        'id' => Str::uuid()->toString(),
        'organisation_id' => $org->id,
        'post_id' => $post->id,
        'name' => 'Approved Candidate',
        'status' => 'approved',
        'position_order' => 1,
    ]);

    $pending = Candidacy::create([
        'id' => Str::uuid()->toString(),
        'organisation_id' => $org->id,
        'post_id' => $post->id,
        'name' => 'Pending Candidate',
        'status' => 'pending',
        'position_order' => 2,
    ]);

    // Act & Assert
    $all = $post->candidacies()->withoutGlobalScopes()->get();
    $this->assertCount(2, $all);

    $approved_only = $post->approvedCandidacies()->withoutGlobalScopes()->get();
    $this->assertCount(1, $approved_only);
    $this->assertEquals($approved->id, $approved_only->first()->id);
}
```

**Key Points:**
- Create test data with different statuses
- Test both unrestricted and filtered queries
- Verify filtering excludes unwanted records

---

## Testing Relationship Uniqueness

### Testing Unique Constraint on Pivot

```php
/** @test */
public function unique_constraint_prevents_duplicate_user_org_pair()
{
    // Arrange
    $org = Organisation::factory()->tenant()->create();
    $user1Id = Str::uuid()->toString();

    DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
        $user1Id,
        $org->id,
        'Test User',
        'test@example.com',
        bcrypt('password'),
        now(),
        now(),
    ]);
    $user = User::find($user1Id);

    // Create first role
    UserOrganisationRole::create([
        'user_id' => $user->id,
        'organisation_id' => $org->id,
        'role' => 'admin',
    ]);

    // Act & Assert - Attempt to create duplicate
    $this->expectException(QueryException::class);
    UserOrganisationRole::create([
        'user_id' => $user->id,
        'organisation_id' => $org->id,
        'role' => 'member',  // Different role, same user-org pair
    ]);
}
```

**Key Points:**
- Create first record successfully
- Attempt to create duplicate
- Expect exception (QueryException)
- Unique constraint should prevent duplicate

---

## Running Tests

### Run Single Test File
```bash
php artisan test tests/Unit/Models/ElectionTest.php
```

### Run Single Test Method
```bash
php artisan test tests/Unit/Models/ElectionTest.php --filter="election_has_many_posts"
```

### Run All Model Tests
```bash
php artisan test tests/Unit/Models/
```

### Run Tests with Coverage
```bash
php artisan test tests/Unit/Models/ --coverage
```

### Run Tests Verbosely
```bash
php artisan test tests/Unit/Models/ --verbose
```

---

## Test Data Factories

### Using Factories

```php
// Create with factory
$org = Organisation::factory()->tenant()->create();
$election = Election::factory()->forOrganisation($org)->create();

// Create with specific attributes
$election = Election::factory()->forOrganisation($org)->create([
    'name' => 'Custom Election Name',
    'type' => 'demo',
]);

// Create multiple
$elections = Election::factory()->count(5)->forOrganisation($org)->create();
```

### Creating Test Data Directly

```php
// Simple approach for models without factory issues
$post = Post::create([
    'id' => Str::uuid()->toString(),
    'organisation_id' => $org->id,
    'election_id' => $election->id,
    'name' => 'Test Post',
    'is_national_wide' => true,
    'required_number' => 1,
]);

// Raw DB insert for problematic models (e.g., User with organisation_id)
DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
    Str::uuid()->toString(),
    $org->id,
    'Test User',
    'test@example.com',
    bcrypt('password'),
    now(),
    now(),
]);
```

---

## Assertion Reference

### Collection Assertions

```php
// Count
$this->assertCount(2, $collection);
$this->assertCount(0, $collection);  // Empty

// Contains
$this->assertContains($item, $collection);
$this->assertTrue($collection->contains($item));

// Not empty
$this->assertNotEmpty($collection);
$this->assertTrue($collection->isNotEmpty());
```

### Object Assertions

```php
// Type checking
$this->assertInstanceOf(Election::class, $object);
$this->assertIsObject($object);

// Equality
$this->assertEquals($expected_id, $object->id);
$this->assertSame($expected_value, $object->value);

// Nullability
$this->assertNull($object->nullable_field);
$this->assertNotNull($object->required_field);
```

### Relationship Assertions

```php
// Relationship exists and is iterable
$this->assertIsIterable($model->relationships);

// Relationship returns correct instance
$this->assertInstanceOf(RelatedModel::class, $model->related);

// Relationship count
$this->assertCount(3, $model->relationships);
```

---

## Common Test Patterns

### Pattern 1: Three-Part Test (Arrange-Act-Assert)

```php
/** @test */
public function model_relationship_works()
{
    // Arrange - Set up test data
    $org = Organisation::factory()->tenant()->create();
    $election = Election::factory()->forOrganisation($org)->create();

    // Act - Use the feature
    $posts = $election->posts;

    // Assert - Verify results
    $this->assertIsIterable($posts);
}
```

### Pattern 2: Isolation Test

```php
/** @test */
public function different_organisations_are_isolated()
{
    // Create two isolated organisations
    $org1 = Organisation::factory()->tenant()->create();
    $org2 = Organisation::factory()->tenant()->create();

    // Add data to both
    $election1 = Election::factory()->forOrganisation($org1)->create();
    $election2 = Election::factory()->forOrganisation($org2)->create();

    // Verify they don't see each other's data
    $org1_elections = Election::withoutGlobalScopes()
        ->where('organisation_id', $org1->id)
        ->get();

    $this->assertCount(1, $org1_elections);
    $this->assertEquals($election1->id, $org1_elections->first()->id);
}
```

### Pattern 3: Constraint Test

```php
/** @test */
public function constraint_filters_correctly()
{
    $post = Post::create([...]);

    // Create mixed data
    $approved = Candidacy::create([..., 'status' => 'approved']);
    $pending = Candidacy::create([..., 'status' => 'pending']);

    // Test filtering
    $all = $post->candidacies()->withoutGlobalScopes()->get();
    $filtered = $post->approvedCandidacies()->withoutGlobalScopes()->get();

    $this->assertCount(2, $all);
    $this->assertCount(1, $filtered);
}
```

---

## Debugging Tests

### Print Debug Info

```php
/** @test */
public function debug_relationships()
{
    $org = Organisation::factory()->tenant()->create();

    // Print SQL query
    \Illuminate\Support\Facades\DB::enableQueryLog();
    $elections = $org->elections;
    dump(\Illuminate\Support\Facades\DB::getQueryLog());

    // Print collection
    dump($elections->toArray());

    // Print single record
    dump($elections->first()->toArray());
}
```

### Use Laravel's Assertion Helpers

```php
// Get detailed failure message
$this->assertEquals(
    $expected,
    $actual,
    'Custom error message describing what went wrong'
);

// Stop on first failure
php artisan test --stop-on-failure
```

---

**Last Updated:** 2026-03-06
**Phase:** A (Testing Guide)
