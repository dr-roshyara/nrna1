# 🧪 Testing Organisation Isolation

## Overview

Testing organisation isolation requires setting up multiple organisations, switching contexts, and verifying that data is properly scoped.

---

## Basic Test Setup

### Minimal Test Class

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ElectionIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $orgA;
    protected Organisation $orgB;

    protected function setUp(): void
    {
        parent::setUp();

        // Create two test organisations
        $this->orgA = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'org-a']);
        $this->orgB = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'org-b']);
    }

    // Your tests go here...
}
```

---

## Common Test Patterns

### Pattern 1: Basic Scoping Test

```php
/** @test */
public function test_all_queries_scoped_to_current_organisation()
{
    // Arrange: Set org A context
    session(['current_organisation_id' => $this->orgA->id]);

    // Create elections in both orgs
    $electionA = Election::factory()->create(['organisation_id' => $this->orgA->id]);
    $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

    // Act: Query elections in org A context
    $elections = Election::all();

    // Assert: Only org A's election is visible
    $this->assertCount(1, $elections);
    $this->assertEquals($electionA->id, $elections->first()->id);
}
```

### Pattern 2: Find Returns Null Test

```php
/** @test */
public function test_find_returns_null_for_other_org_record()
{
    // Arrange: Org A context with Org B election
    session(['current_organisation_id' => $this->orgA->id]);
    $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

    // Act: Try to find Org B election
    $found = Election::find($electionB->id);

    // Assert: Returns null (global scope blocks it)
    $this->assertNull($found);
}
```

### Pattern 3: Auto-Fill Test

```php
/** @test */
public function test_create_auto_fills_organisation_id_from_session()
{
    // Arrange: Org A context
    session(['current_organisation_id' => $this->orgA->id]);

    // Act: Create election WITHOUT explicitly setting org_id
    $election = Election::create(['name' => 'Test Election']);

    // Assert: organisation_id auto-filled from session
    $this->assertEquals($this->orgA->id, $election->organisation_id);
}
```

### Pattern 4: Count is Scoped Test

```php
/** @test */
public function test_count_respects_organisation_scope()
{
    // Arrange: Set org A context and create multiple elections
    session(['current_organisation_id' => $this->orgA->id]);

    Election::factory()->count(3)->create(['organisation_id' => $this->orgA->id]);
    Election::factory()->count(2)->create(['organisation_id' => $this->orgB->id]);

    // Act: Count elections in org A context
    $count = Election::count();

    // Assert: Only counts org A elections
    $this->assertEquals(3, $count);
}
```

### Pattern 5: No Context Test

```php
/** @test */
public function test_returns_empty_without_organisation_context()
{
    // Arrange: Create elections but no session context
    Election::factory()->create(['organisation_id' => $this->orgA->id]);
    Election::factory()->create(['organisation_id' => $this->orgB->id]);

    session()->forget('current_organisation_id');  // Clear context

    // Act: Query without context
    $elections = Election::all();

    // Assert: Returns empty (no org context = no results for security)
    $this->assertCount(0, $elections);
}
```

### Pattern 6: Multi-Org User Test

```php
/** @test */
public function test_user_with_multiple_orgs_sees_correct_data()
{
    // Arrange: User belongs to both orgs
    $user = User::factory()->create();
    $user->organisations()->attach($this->orgA->id, ['role' => 'member']);
    $user->organisations()->attach($this->orgB->id, ['role' => 'member']);

    // Create data in both orgs
    $electionA = Election::factory()->create(['organisation_id' => $this->orgA->id]);
    $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

    // Act: Switch to org A context
    session(['current_organisation_id' => $this->orgA->id]);
    $this->actingAs($user);

    // Assert: Only org A data visible
    $elections = Election::all();
    $this->assertCount(1, $elections);
    $this->assertEquals($electionA->id, $elections->first()->id);

    // Act: Switch to org B context
    session(['current_organisation_id' => $this->orgB->id]);

    // Assert: Only org B data visible
    $elections = Election::all();
    $this->assertCount(1, $elections);
    $this->assertEquals($electionB->id, $elections->first()->id);
}
```

### Pattern 7: Relationship Test

```php
/** @test */
public function test_relationships_respect_organisation_scope()
{
    // Arrange: Set org A context
    session(['current_organisation_id' => $this->orgA->id]);

    $electionA = Election::factory()->create(['organisation_id' => $this->orgA->id]);
    $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

    $postA = Post::factory()->create(['organisation_id' => $this->orgA->id, 'election_id' => $electionA->id]);
    $postB = Post::factory()->create(['organisation_id' => $this->orgB->id, 'election_id' => $electionB->id]);

    // Act: Load posts for org A election
    $posts = $electionA->posts()->get();

    // Assert: Only org A posts visible
    $this->assertCount(1, $posts);
    $this->assertEquals($postA->id, $posts->first()->id);
}
```

### Pattern 8: Filter with Conditions Test

```php
/** @test */
public function test_where_clause_respects_organisation_scope()
{
    // Arrange: Set org A context
    session(['current_organisation_id' => $this->orgA->id]);

    $activeA = Election::factory()->create(['organisation_id' => $this->orgA->id, 'status' => 'active']);
    $inactiveA = Election::factory()->create(['organisation_id' => $this->orgA->id, 'status' => 'completed']);
    $activeB = Election::factory()->create(['organisation_id' => $this->orgB->id, 'status' => 'active']);

    // Act: Query active elections in org A context
    $active = Election::where('status', 'active')->get();

    // Assert: Only org A's active election
    $this->assertCount(1, $active);
    $this->assertEquals($activeA->id, $active->first()->id);
}
```

### Pattern 9: Bypass Scope Test (Admin)

```php
/** @test */
public function test_withoutGlobalScopes_returns_all_records()
{
    // Arrange: Set org A context
    session(['current_organisation_id' => $this->orgA->id]);

    $electionA = Election::factory()->create(['organisation_id' => $this->orgA->id]);
    $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

    // Act: Bypass global scope (admin operation)
    $all = Election::withoutGlobalScopes()->get();

    // Assert: All elections visible
    $this->assertCount(2, $all);
}
```

### Pattern 10: Database Direct Test

```php
/** @test */
public function test_isolation_at_database_level()
{
    // Arrange: Org A context
    session(['current_organisation_id' => $this->orgA->id]);

    $electionA = Election::factory()->create(['organisation_id' => $this->orgA->id]);
    $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

    // Act: Check database directly
    $countA = DB::table('elections')
        ->where('organisation_id', $this->orgA->id)
        ->count();

    $countB = DB::table('elections')
        ->where('organisation_id', $this->orgB->id)
        ->count();

    // Assert: Database has correct isolation
    $this->assertEquals(1, $countA);
    $this->assertEquals(1, $countB);
}
```

---

## Testing Edge Cases

### Edge Case 1: Null Organisation ID

```php
/** @test */
public function test_models_with_null_org_id_are_filtered_out()
{
    session(['current_organisation_id' => $this->orgA->id]);

    // Manually insert record with NULL org_id (shouldn't happen)
    $model = Election::factory()->make(['organisation_id' => $this->orgA->id]);
    $model->save();

    // Should still only return org A records
    $elections = Election::all();
    $this->assertEquals(1, $elections->count());
}
```

### Edge Case 2: Empty Result Set

```php
/** @test */
public function test_returns_empty_when_no_records_exist()
{
    session(['current_organisation_id' => $this->orgA->id]);

    // Don't create any records

    $elections = Election::all();

    $this->assertCount(0, $elections);
}
```

### Edge Case 3: Switching Org Context

```php
/** @test */
public function test_switching_organisation_context_changes_results()
{
    $electionA = Election::factory()->create(['organisation_id' => $this->orgA->id]);
    $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

    // Query org A
    session(['current_organisation_id' => $this->orgA->id]);
    $this->assertEquals($electionA->id, Election::first()->id);

    // Switch to org B
    session(['current_organisation_id' => $this->orgB->id]);
    $this->assertEquals($electionB->id, Election::first()->id);

    // Back to org A
    session(['current_organisation_id' => $this->orgA->id]);
    $this->assertEquals($electionA->id, Election::first()->id);
}
```

---

## Test Helpers

### Helper Function: Create Org with User

```php
protected function createOrgWithUser($slug = 'test-org')
{
    $org = Organisation::factory()->create(['type' => 'tenant', 'slug' => $slug]);
    $user = User::factory()->create();
    $user->organisations()->attach($org->id, ['role' => 'admin']);

    return ['org' => $org, 'user' => $user];
}

// Usage:
public function test_something()
{
    ['org' => $org, 'user' => $user] = $this->createOrgWithUser();
    session(['current_organisation_id' => $org->id]);
    $this->actingAs($user);

    // Test code...
}
```

### Helper Function: Create Isolation Test Data

```php
protected function createIsolationTestData()
{
    // Create data in org A
    $electionA = Election::factory()->count(3)->create(['organisation_id' => $this->orgA->id]);
    $postsA = Post::factory()->count(5)
        ->state(fn() => ['election_id' => $electionA->first()->id, 'organisation_id' => $this->orgA->id])
        ->create();

    // Create data in org B
    $electionB = Election::factory()->count(2)->create(['organisation_id' => $this->orgB->id]);
    $postsB = Post::factory()->count(3)
        ->state(fn() => ['election_id' => $electionB->first()->id, 'organisation_id' => $this->orgB->id])
        ->create();

    return [
        'electionA' => $electionA,
        'postsA' => $postsA,
        'electionB' => $electionB,
        'postsB' => $postsB,
    ];
}
```

---

## Complete Test Class Example

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Election;
use App\Models\Post;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompleteIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $orgA;
    protected Organisation $orgB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orgA = Organisation::factory()->create(['type' => 'tenant']);
        $this->orgB = Organisation::factory()->create(['type' => 'tenant']);
    }

    /** @test */
    public function test_elections_are_scoped()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $eA = Election::factory()->create(['organisation_id' => $this->orgA->id]);
        $eB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

        $elections = Election::all();

        $this->assertCount(1, $elections);
        $this->assertEquals($eA->id, $elections->first()->id);
    }

    /** @test */
    public function test_posts_are_scoped()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $eA = Election::factory()->create(['organisation_id' => $this->orgA->id]);
        $pA = Post::factory()->create(['organisation_id' => $this->orgA->id, 'election_id' => $eA->id]);

        $eB = Election::factory()->create(['organisation_id' => $this->orgB->id]);
        $pB = Post::factory()->create(['organisation_id' => $this->orgB->id, 'election_id' => $eB->id]);

        $posts = Post::all();

        $this->assertCount(1, $posts);
        $this->assertEquals($pA->id, $posts->first()->id);
    }

    /** @test */
    public function test_find_returns_null_for_other_org()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $eB = Election::factory()->create(['organisation_id' => $this->orgB->id]);
        $found = Election::find($eB->id);

        $this->assertNull($found);
    }
}
```

---

## Running Isolation Tests

```bash
# Run all isolation tests
php artisan test tests/Feature/OrganisationIsolationTest.php

# Run specific test method
php artisan test tests/Feature/OrganisationIsolationTest.php --filter=test_all_queries_scoped

# Run with verbose output
php artisan test tests/Feature/OrganisationIsolationTest.php -v

# Run and show SQL queries
php artisan test tests/Feature/OrganisationIsolationTest.php --debug
```

---

## Assertions Reference

```php
// Count assertions
$this->assertCount(1, $items);           // Exact count
$this->assertEmpty($items);              // Count is 0
$this->assertNotEmpty($items);           // Count > 0

// Null assertions
$this->assertNull($result);              // Is null
$this->assertNotNull($result);           // Is not null

// Equality assertions
$this->assertEquals($expected, $actual);
$this->assertNotEquals($expected, $actual);

// Collection assertions
$this->assertTrue($items->contains($item));
$this->assertFalse($items->contains($item));
$this->assertTrue($items->contains('id', $id));  // By attribute
```

---

## Tips

1. **Always set session before querying:** Without `session(['current_organisation_id' => ...])`, the global scope defaults to platform context
2. **Use RefreshDatabase:** Isolates each test with a fresh database
3. **Create multiple orgs:** Test with 2+ organisations to verify isolation
4. **Test with actingAs():** When testing user-specific behavior
5. **Test edge cases:** Empty results, null values, context switching
6. **Use factories:** Don't create raw DB records - use factories for consistency

---

## Questions?

See main guide: `developer_guide/model/isolation/README.md`
