# Testing Guide: Election-Only Mode

## Test Structure

### Unit Tests (Fast, Isolated)

Test individual components in isolation using mocks.

```
tests/Unit/
├── Contexts/Elections/
│   ├── AssignVoterHandlerTest.php
│   ├── BulkAssignVotersHandlerTest.php
│   └── EloquentVoterRepositoryTest.php
├── Models/
│   ├── ElectionMembershipTest.php
│   └── DeadLetterEntryTest.php
├── Domain/
│   └── Election/
│       └── ElectionModeTest.php
└── Services/
    └── ElectionCacheServiceTest.php
```

### Feature/Integration Tests (Slower, Real Database)

Test complete flows using real database.

```
tests/Feature/
├── Election/
│   ├── ElectionOnlyModeTest.php
│   └── ElectionMembershipIntegrationTest.php
└── Contexts/Elections/
    └── EloquentVoterEligibilityQueryServiceTest.php
```

---

## Unit Test Patterns

### Pattern 1: Handler Testing with Mocks

Test handler logic without touching database.

```php
<?php

namespace Tests\Unit\Contexts\Elections;

use App\Contexts\Elections\Application\Commands\AssignVoterCommand;
use App\Contexts\Elections\Application\Handlers\AssignVoterHandler;
use App\Contexts\Elections\Domain\Policies\VoterEligibilityPolicy;
use App\Contexts\Elections\Domain\Repositories\VoterRepositoryInterface;
use App\Domain\Election\Enum\ElectionMode;
use PHPUnit\Framework\TestCase;

class AssignVoterHandlerTest extends TestCase
{
    use RefreshDatabase;  // For DeadLetterEntry expectations
    
    private MockObject $policyMock;
    private MockObject $repositoryMock;
    private AssignVoterHandler $handler;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->policyMock = $this->createMock(VoterEligibilityPolicy::class);
        $this->repositoryMock = $this->createMock(VoterRepositoryInterface::class);
        
        $this->handler = new AssignVoterHandler(
            $this->policyMock,
            $this->repositoryMock,
        );
    }
    
    #[\PHPUnit\Framework\Attributes\Test]
    public function throws_when_user_not_eligible(): void
    {
        $this->policyMock
            ->expects($this->once())
            ->method('isEligible')
            ->with('user-123', 'org-456', ElectionMode::ElectionOnly)
            ->willReturn(false);  // User not eligible
        
        $command = new AssignVoterCommand(
            userId: 'user-123',
            electionId: 'election-789',
            organisationId: 'org-456',
            mode: ElectionMode::ElectionOnly,
        );
        
        $this->expectException(VoterNotEligibleException::class);
        $this->handler->handle($command);
    }
    
    #[\PHPUnit\Framework\Attributes\Test]
    public function creates_new_membership_for_eligible_user(): void
    {
        $membership = ElectionMembership::factory()->make();
        
        $this->policyMock
            ->method('isEligible')
            ->willReturn(true);  // User is eligible
        
        $this->repositoryMock
            ->method('findWithTrashed')
            ->willReturn(null);  // No existing record
        
        $this->repositoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($membership);
        
        $command = new AssignVoterCommand(
            userId: 'user-123',
            electionId: 'election-789',
            organisationId: 'org-456',
            mode: ElectionMode::ElectionOnly,
        );
        
        $result = $this->handler->handle($command);
        
        $this->assertEquals($membership->id, $result->id);
    }
}
```

### Pattern 2: Bulk Handler with Multiple Scenarios

```php
#[\PHPUnit\Framework\Attributes\Test]
public function handles_chunk_failure_with_dead_letter_queue(): void
{
    $users = array_map(fn($i) => "user-$i", range(1, 1000));
    $orgId = '11111111-1111-1111-1111-111111111111';
    $electionId = '22222222-2222-2222-2222-222222222222';
    
    $this->policyMock
        ->method('qualifyingSubset')
        ->willReturn($users);
    
    $this->repositoryMock
        ->method('existingVoterIds')
        ->willReturn([]);
    
    // First chunk succeeds, second fails
    $this->repositoryMock
        ->method('bulkInsert')
        ->will($this->onConsecutiveCalls(
            $this->returnValue(null),
            $this->throwException(new \Exception('DB error')),
        ));
    
    $command = new BulkAssignVotersCommand(
        userIds: $users,
        electionId: $electionId,
        organisationId: $orgId,
        mode: ElectionMode::ElectionOnly,
        chunkSize: 500,
    );
    
    $result = $this->handler->handle($command);
    
    // Verify results
    $this->assertEquals(500, $result['success']);
    $this->assertEquals(500, $result['failed']);
    
    // Verify DLQ entries created for failed chunk
    $dlqEntries = DeadLetterEntry::where('organisation_id', $orgId)->get();
    $this->assertEquals(500, $dlqEntries->count());
}
```

---

## Feature Test Patterns

### Pattern 1: Election-Only Mode Integration

Test complete flow with real database but separate org per test.

```php
<?php

namespace Tests\Feature\Election;

use App\Domain\Election\Enum\ElectionMode;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionOnlyModeTest extends TestCase
{
    use RefreshDatabase;
    
    private Organisation $org;
    private Election $election;
    private User $user;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Election-only org (uses_full_membership = false)
        $this->org = Organisation::factory()
            ->create(['uses_full_membership' => false]);
        
        $this->election = Election::factory()
            ->create([
                'organisation_id' => $this->org->id,
                'state' => Election::STATE_ADMINISTRATION,  // Allow voter import
            ]);
        
        // User with ONLY organisation_user record (not full member)
        $this->user = User::factory()->create();
        $this->org->users()->attach($this->user->id, [
            'status' => 'active',
            'deleted_at' => null,
        ]);
    }
    
    #[\PHPUnit\Framework\Attributes\Test]
    public function user_without_membership_can_be_assigned_as_voter(): void
    {
        // Verify user is NOT in members table (election-only mode)
        $this->assertDatabaseMissing('members', [
            'user_id' => $this->user->id,
        ]);
        
        // Assign as voter
        $response = $this->actingAs(auth()->user())
            ->post(route('api.organisations.elections.voters.store', [
                'organisation' => $this->org->slug,
                'election' => $this->election->slug,
            ]), [
                'user_id' => $this->user->id,
            ]);
        
        $response->assertSuccessful();
        
        // Verify membership created
        $this->assertDatabaseHas('election_memberships', [
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'role' => 'voter',
            'status' => 'active',
        ]);
    }
    
    #[\PHPUnit\Framework\Attributes\Test]
    public function bulk_import_of_1000_users_succeeds(): void
    {
        // Create 1000 users in org
        $users = User::factory(1000)->create();
        foreach ($users as $user) {
            $this->org->users()->attach($user->id, ['status' => 'active']);
        }
        
        $userIds = $users->pluck('id')->toArray();
        
        // Bulk import
        $response = $this->actingAs(auth()->user())
            ->post(route('api.organisations.elections.voters.bulk-store', [
                'organisation' => $this->org->slug,
                'election' => $this->election->slug,
            ]), [
                'user_ids' => $userIds,
            ]);
        
        $response->assertSuccessful();
        $result = $response->json('result');
        
        $this->assertEquals(1000, $result['success']);
        $this->assertEquals(0, $result['failed']);
        $this->assertDatabaseCount('election_memberships', 1000);
    }
    
    #[\PHPUnit\Framework\Attributes\Test]
    public function soft_deleted_voter_can_be_reimported(): void
    {
        // Create and delete a voter
        $membership = ElectionMembership::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
            'status' => 'active',
        ]);
        $membership->delete();  // Soft delete
        
        // Reimport same user
        $response = $this->actingAs(auth()->user())
            ->post(route('api.organisations.elections.voters.store', [
                'organisation' => $this->org->slug,
                'election' => $this->election->slug,
            ]), [
                'user_id' => $this->user->id,
            ]);
        
        $response->assertSuccessful();
        
        // Verify still only 1 record (restored, not duplicate)
        $this->assertDatabaseCount('election_memberships', 1);
        
        // Verify restored
        $restored = ElectionMembership::find($membership->id);
        $this->assertNull($restored->deleted_at);
        $this->assertEquals('active', $restored->status);
    }
}
```

---

## Test Data Factories

### Creating Test Data

```php
// Using factories
$org = Organisation::factory()
    ->create(['uses_full_membership' => false]);

$election = Election::factory()
    ->create(['organisation_id' => $org->id]);

$users = User::factory(100)->create();

// Attach users to org (election-only mode)
foreach ($users as $user) {
    $org->users()->attach($user->id, [
        'status' => 'active',
    ]);
}

// Create memberships (for testing other logic)
$memberships = ElectionMembership::factory(50)
    ->create([
        'election_id' => $election->id,
        'organisation_id' => $org->id,
    ]);
```

### Full Membership Mode

```php
// Create org with full membership mode
$org = Organisation::factory()
    ->create(['uses_full_membership' => true]);

$users = User::factory(100)->create();

foreach ($users as $user) {
    // User must be member to be eligible voter
    $org->users()->attach($user->id);
    
    Member::factory()
        ->create([
            'organisation_user_id' => $user->organisationUser($org->id)->id,
            'status' => 'active',
            'fees_status' => 'paid',  // Must have paid fees
        ]);
}
```

---

## Running Tests

### Run All Unit Tests

```bash
php artisan test tests/Unit/Contexts/Elections/
```

### Run All Election-Only Mode Tests

```bash
php artisan test tests/Feature/Election/ElectionOnlyModeTest.php
```

### Run Specific Test Method

```bash
php artisan test tests/Unit/Contexts/Elections/BulkAssignVotersHandlerTest.php \
  --filter=test_handles_chunk_failure_with_dead_letter_queue
```

### Run with Coverage

```bash
php artisan test \
  --coverage \
  --coverage-clover=coverage.xml \
  tests/Unit/Contexts/Elections/ \
  tests/Feature/Election/
```

### Run Tests in Parallel (PHPUnit 10+)

```bash
php artisan test \
  --parallel \
  --processes=4 \
  tests/
```

---

## Testing Checklist

When adding new features or fixing bugs, verify:

### Unit Test Coverage
- [ ] Handler logic (mocked policy & repository)
- [ ] Policy eligibility rules (both modes)
- [ ] Repository persistence methods
- [ ] Domain exceptions thrown correctly
- [ ] Domain events dispatched outside transaction
- [ ] Cache keys generated correctly
- [ ] Dead-letter entry structure

### Integration Test Coverage
- [ ] Single voter assignment flow
- [ ] Bulk voter assignment flow
- [ ] Soft-deleted voter re-import
- [ ] Cross-election voter isolation
- [ ] Cross-organisation voter isolation
- [ ] Permission checks (admin only)
- [ ] Election state validation
- [ ] Audit logging

### Edge Cases
- [ ] Empty bulk import (0 users)
- [ ] Very large bulk import (10,000+ users)
- [ ] Concurrent assignments (race condition)
- [ ] Expired memberships filtered
- [ ] Soft-deleted users not eligible
- [ ] Duplicate voter in same import
- [ ] User removed from org mid-import
- [ ] Cache invalidation on failure

---

## Debugging Tests

### View Database State Mid-Test

```php
// In test, add breakpoint or:
dd(ElectionMembership::all());

// Or use a helper:
$this->dumpDatabase('election_memberships');
```

### Mock Debugging

```php
// See what methods were called on mock:
$this->policyMock->method('qualifyingSubset')->after(
    function($mock) {
        echo "Policy called with: ";
        var_dump(func_get_args());
    }
);
```

### Test Database State

```php
// Verify a condition:
$this->assertTrue(
    ElectionMembership::where('user_id', $userId)
        ->where('election_id', $electionId)
        ->exists(),
    'Membership should exist after assignment'
);

// With helpful message
$this->assertEquals(
    950,
    $result['success'],
    'Expected 950 successful assignments but got ' . $result['success']
);
```

---

## Performance Testing

### Test Import Performance

```php
#[\PHPUnit\Framework\Attributes\Test]
public function bulk_import_1000_users_completes_in_acceptable_time(): void
{
    // Setup
    $users = User::factory(1000)->create();
    foreach ($users as $user) {
        $org->users()->attach($user->id);
    }
    
    // Time the import
    $start = microtime(true);
    
    $handler = app(BulkAssignVotersHandler::class);
    $result = $handler->handle($command);
    
    $elapsed = microtime(true) - $start;
    
    // Assert completes in < 5 seconds
    $this->assertLessThan(5.0, $elapsed,
        "Bulk import took {$elapsed}s, should be under 5s"
    );
}
```

---

## CI/CD Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_PASSWORD: root
          MYSQL_ROOT_PASSWORD: root
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
    
    steps:
      - uses: actions/checkout@v3
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      
      - run: composer install
      
      - run: |
          php artisan migrate --env=testing
          php artisan test tests/Unit/Contexts/Elections/
          php artisan test tests/Feature/Election/
```

---

## Test Troubleshooting

### Issue: "Call to undefined method ElectionMembership::assignVoter()"

**Cause**: Using deprecated method in test

**Fix**:
```php
// Old (❌ doesn't exist anymore)
$membership = ElectionMembership::assignVoter($userId, $electionId);

// New (✅ use handler or factory)
$membership = ElectionMembership::create([
    'user_id' => $userId,
    'election_id' => $electionId,
    'organisation_id' => $org->id,
    'status' => 'active',
]);

// Or:
$handler = app(AssignVoterHandler::class);
$membership = $handler->handle(new AssignVoterCommand(...));
```

### Issue: "Foreign key constraint failed"

**Cause**: Missing `organisation_users` record

**Fix**:
```php
// In test setup:
$this->org->users()->attach($this->user->id, [
    'status' => 'active',
    'deleted_at' => null,
]);
```

### Issue: "Duplicate entry" in bulk import

**Cause**: User already assigned to same election

**Fix**:
```php
// Use a fresh election for each test:
protected function setUp(): void
{
    parent::setUp();
    $this->election = Election::factory()->create([
        'organisation_id' => $this->org->id,
    ]);
}
```

---

**Last Updated**: 2026-05-18
