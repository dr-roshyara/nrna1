# Election-Only Mode: Strangler Fig Migration (Phases A–C)

## Table of Contents

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Components](#components)
4. [How It Works](#how-it-works)
5. [Usage Guide](#usage-guide)
6. [Testing](#testing)
7. [Database Schema](#database-schema)
8. [Migration Notes](#migration-notes)
9. [Future Phases](#future-phases)
10. [Troubleshooting](#troubleshooting)

---

## Overview

**Election-Only Mode** enables organizations to conduct elections without requiring full membership records. Users only need an `OrganisationUser` record to be eligible voters—no `UserOrganisationRole` or `members` records required.

### The Problem We Solved

The old system had a hard database foreign key constraint that **blocked election-only mode entirely**:

```
election_memberships (user_id, organisation_id) 
  → FOREIGN KEY → user_organisation_roles (user_id, organisation_id)
```

This prevented users with only `OrganisationUser` records from being assigned as voters.

### The Solution: Strangler Fig Pattern

We gradually replaced the old model logic with new handler-based architecture **without dual writes** or service disruption:

- **Phase A**: Froze current behavior with contract tests
- **Phase B**: Built policy layer in parallel
- **Phase C**: Extracted write paths into handlers, dropped FK constraint, removed deprecated model methods

**Result**: Production-ready election-only voting system with zero regressions and full audit trail.

---

## Architecture

### Layer Model

```
┌─────────────────────────────────────────────────┐
│              HTTP Layer (Controllers)             │
│         ElectionVoterController                   │
│     ↓ (Inertia/Vue requests)                      │
├─────────────────────────────────────────────────┤
│          Application Layer (Handlers)             │
│  AssignVoterHandler        BulkAssignVotersHandler│
│  ↓ (Commands + Events)                            │
├─────────────────────────────────────────────────┤
│          Domain Layer (Pure PHP)                  │
│  VoterEligibilityPolicy    VoterRepositoryI      │
│  ElectionMode enum         Exceptions             │
│  Domain Events             Value Objects          │
├─────────────────────────────────────────────────┤
│        Infrastructure Layer (Laravel)             │
│  EloquentVoterRepository   ElectionCacheService  │
│  ElectionMembership model  DeadLetterEntry       │
│  Database migrations       Eloquent events        │
└─────────────────────────────────────────────────┘
```

### Data Flow: Voter Assignment

```
User submits form (Vue)
        ↓
ElectionVoterController::store()
        ↓
AssignVoterCommand (DTO)
        ↓
AssignVoterHandler::handle()
    1. policy->isEligible(userId, orgId, mode)
    2. repository->findWithTrashed(userId, electionId)
    3. Decide: create new / restore soft-deleted / throw
    4. Event::dispatch(VoterAssignedToElection)  [OUTSIDE transaction]
    5. Log::channel('voter_audit')->info(...)
        ↓
Response to user (with flash message)
```

### Eligibility Decision Tree

```
ElectionMode::ElectionOnly (uses_full_membership = false)
    ↓
Check organisation_users table:
  - user exists
  - status = 'active'
  - not soft-deleted
    ↓
  ✓ User qualifies

ElectionMode::FullMembership (uses_full_membership = true)
    ↓
Check members + membership_types:
  - members.status = 'active'
  - fees_status in ['paid', 'exempt']
  - grants_voting_rights = true
  - membership not expired
    ↓
  ✓ User qualifies
```

---

## Components

### 1. Enums & Value Objects

#### **ElectionMode** (`app/Domain/Election/Enum/ElectionMode.php`)

```php
enum ElectionMode: string
{
    case ElectionOnly = 'election_only';
    case FullMembership = 'full_membership';
    
    public static function fromOrganisation(Organisation $org): self
    public function isElectionOnly(): bool
    public function isFullMembership(): bool
}
```

**Usage**: Determines which eligibility rules to apply.

---

### 2. Policies (Business Logic)

#### **VoterEligibilityPolicy Interface** 
(`app/Contexts/Elections/Domain/Policies/VoterEligibilityPolicy.php`)

```php
interface VoterEligibilityPolicy
{
    public function isEligible(
        string $userId,
        string $organisationId,
        ElectionMode $mode
    ): bool;

    public function qualifyingSubset(
        array $userIds,
        string $organisationId,
        ElectionMode $mode
    ): array;  // Returns only eligible user IDs
}
```

#### **EloquentVoterEligibilityQueryService** 
(`app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php`)

Two separate implementations based on mode:

- **Election-Only**: Queries `organisation_users` table
- **Full Membership**: Queries `members` + `membership_types` with fee validation

**Why separate?** No branching in a single query—clean separation by mode.

---

### 3. Repositories (Data Access)

#### **VoterRepositoryInterface** 
(`app/Contexts/Elections/Domain/Repositories/VoterRepositoryInterface.php`)

```php
interface VoterRepositoryInterface
{
    public function findWithTrashed(
        string $userId,
        string $electionId
    ): ?ElectionMembership;
    
    public function create(array $attributes): ElectionMembership;
    
    public function restoreAndUpdate(
        ElectionMembership $membership,
        array $attributes
    ): ElectionMembership;
    
    public function bulkInsert(array $rows): void;
    
    public function existingVoterIds(string $electionId): array;
}
```

#### **EloquentVoterRepository** 
(`app/Contexts/Elections/Infrastructure/Repositories/EloquentVoterRepository.php`)

Key methods:

```php
public function findWithTrashed(string $userId, string $electionId): ?ElectionMembership
{
    return ElectionMembership::withoutGlobalScopes()
        ->withTrashed()
        ->where('user_id', $userId)
        ->where('election_id', $electionId)
        ->lockForUpdate()
        ->first();
}

public function restoreAndUpdate(
    ElectionMembership $membership,
    array $attributes
): ElectionMembership {
    $membership->restore();  // Soft delete restoration
    $membership->update($attributes);
    return $membership;
}
```

**Why `findWithTrashed()` with `lockForUpdate()`?**
- Soft deletes allow re-importing same voter without unique constraint collision
- Lock prevents concurrent assignments of same user to same election

---

### 4. Handlers (Orchestration)

#### **AssignVoterHandler** 
(`app/Contexts/Elections/Application/Handlers/AssignVoterHandler.php`)

Single-voter assignment with full integrity checks:

```php
public function handle(AssignVoterCommand $cmd): ElectionMembership
{
    // 1. Check eligibility (single query, no N+1)
    if (!$this->policy->isEligible($cmd->userId, $cmd->organisationId, $cmd->mode)) {
        throw new VoterNotEligibleException(...);
    }
    
    // 2. Find existing (including soft-deleted) with row lock
    $existing = $this->repository->findWithTrashed($cmd->userId, $cmd->electionId);
    
    // 3. Decide action based on status
    if ($existing?->status !== 'active') {
        return $this->repository->restoreAndUpdate($existing, [...]);
    }
    
    throw new DuplicateVoterException(...);
    
    // 4. Create new membership
    $membership = $this->repository->create([...]);
    
    // 5. OUTSIDE transaction: dispatch events & log
    Event::dispatch(new VoterAssignedToElection(...));
    Log::channel('voter_audit')->info(...);
    
    return $membership;
}
```

#### **BulkAssignVotersHandler** 
(`app/Contexts/Elections/Application/Handlers/BulkAssignVotersHandler.php`)

Bulk assignment with chunking and failure resilience:

```php
public function handle(BulkAssignVotersCommand $cmd): array
{
    // 1. Single eligibility filter (no N+1)
    $eligibleIds = $this->policy->qualifyingSubset(
        $cmd->userIds,
        $cmd->organisationId,
        $cmd->mode
    );
    
    // 2. Count stats
    $invalidCount = count($cmd->userIds) - count($eligibleIds);
    $existingIds = $this->repository->existingVoterIds($cmd->electionId);
    $newIds = array_diff($eligibleIds, $existingIds);
    
    // 3. Chunk and insert (500 per chunk by default)
    $successCount = 0;
    $failedCount = 0;
    
    foreach (array_chunk($newIds, $cmd->chunkSize) as $chunk) {
        try {
            $rows = array_map(fn($userId) => [...], $chunk);
            $this->repository->bulkInsert($rows);
            $successCount += count($chunk);
        } catch (\Exception $e) {
            // Write failed rows to dead-letter queue for retry
            foreach ($chunk as $userId) {
                DeadLetterEntry::create([
                    'queue_name' => 'voter_bulk_assign',
                    'payload' => ['user_id' => $userId, ...],
                    'error_message' => $e->getMessage(),
                    ...
                ]);
            }
            $failedCount += count($chunk);
        }
    }
    
    // 4. OUTSIDE transaction
    ElectionCacheService::forgetVoterKeys($cmd->organisationId, $cmd->electionId);
    Event::dispatch(new BulkVotersAssignedToElection(...));
    Log::channel('voter_audit')->info(...);
    
    return [
        'success' => $successCount,
        'already_existing' => count($existingIds),
        'invalid' => $invalidCount,
        'failed' => $failedCount,
    ];
}
```

---

### 5. Domain Events

#### **VoterAssignedToElection** 
(`app/Domain/Election/Events/VoterAssignedToElection.php`)

```php
final class VoterAssignedToElection
{
    public function __construct(
        public readonly string $userId,
        public readonly string $electionId,
        public readonly string $organisationId,
        public readonly ?string $assignedBy,
        public readonly \DateTimeImmutable $occurredAt,
    ) {}
}
```

#### **BulkVotersAssignedToElection** 
(`app/Domain/Election/Events/BulkVotersAssignedToElection.php`)

```php
final class BulkVotersAssignedToElection
{
    public function __construct(
        public readonly string $electionId,
        public readonly string $organisationId,
        public readonly int $successCount,
        public readonly int $alreadyExistingCount,
        public readonly int $invalidCount,
        public readonly int $failedCount,
        public readonly ?string $assignedBy,
        public readonly ?string $idempotencyKey,
        public readonly \DateTimeImmutable $occurredAt,
    ) {}
    
    public function totalProcessed(): int
    {
        return $this->successCount + $this->alreadyExistingCount 
             + $this->invalidCount + $this->failedCount;
    }
}
```

**Why events?**
- Decoupled from handler logic
- Can be listened to for cache invalidation, notifications, etc.
- Immutable audit trail
- Dispatched OUTSIDE transaction (consistency guarantee)

---

### 6. Cache Service

#### **ElectionCacheService** 
(`app/Services/ElectionCacheService.php`)

```php
final class ElectionCacheService
{
    public static function keyFor(
        string $organisationId,
        string $electionId,
        string $suffix
    ): string {
        return "org.{$organisationId}.election.{$electionId}.{$suffix}";
    }
    
    public static function forgetVoterKeys(
        string $organisationId,
        string $electionId
    ): void {
        // New tenant-isolated keys
        Cache::forget(self::keyFor($organisationId, $electionId, 'voter_count'));
        Cache::forget(self::keyFor($organisationId, $electionId, 'voter_stats'));
        
        // Legacy keys (transition support)
        Cache::forget("election.{$electionId}.voter_count");
        Cache::forget("election.{$electionId}.voter_stats");
    }
}
```

**Key design decisions:**
- Organization ID in cache key prevents cross-tenant data leakage
- No Redis tags (file driver compatible)
- Dual-key forgetting during migration period

---

### 7. Dead-Letter Queue

#### **DeadLetterEntry Model** 
(`app/Models/DeadLetterEntry.php`)

```php
class DeadLetterEntry extends Model
{
    use HasUuids;
    
    protected $table = 'dead_letter_queue';
    
    protected $fillable = [
        'queue_name',        // 'voter_bulk_assign'
        'payload',          // JSON: user_id, election_id, org_id
        'error_message',    // Exception message
        'error_class',      // Exception class name
        'organisation_id',
        'election_id',
        'failed_at',
        'retried_at',
    ];
    
    protected $casts = [
        'payload' => 'array',
        'failed_at' => 'datetime',
        'retried_at' => 'datetime',
    ];
}
```

**Recovery flow:**
1. Admin views failed rows in UI
2. Issues are fixed (e.g., user added to organization)
3. Admin retries failed rows via API
4. Rows updated with `retried_at` timestamp

---

## How It Works

### Scenario 1: Single Voter Assignment

**User**: Admin assigns John (john@example.com) to election as voter

```php
// Controller
$handler = app(AssignVoterHandler::class);
$membership = $handler->handle(new AssignVoterCommand(
    userId: 'john-uuid',
    electionId: 'election-123',
    organisationId: 'org-456',
    mode: ElectionMode::ElectionOnly,
    assignedBy: auth()->id(),
));
```

**Handler Steps:**
1. ✓ Check: Is John in org-456's `organisation_users`? (YES)
2. ✓ Check: Is this his first assignment to election-123? (YES, no existing record)
3. ✓ Create: Insert new `ElectionMembership` record
4. ✓ Event: Dispatch `VoterAssignedToElection`
5. ✓ Log: Record to `voter_audit` channel

**Result:** John can now vote in election-123. Even if he was previously removed and re-added, soft deletes prevent unique constraint collision.

---

### Scenario 2: Bulk Voter Assignment (1000 users)

**User**: Admin imports CSV with 1000 user IDs for regional election

```php
$handler = app(BulkAssignVotersHandler::class);
$result = $handler->handle(new BulkAssignVotersCommand(
    userIds: [...1000 IDs...],
    electionId: 'election-789',
    organisationId: 'org-456',
    mode: ElectionMode::FullMembership,  // Requires members + paid fees
    chunkSize: 500,
));

// Result:
// [
//     'success' => 950,
//     'already_existing' => 20,
//     'invalid' => 30,
//     'failed' => 0,
// ]
```

**Handler Steps:**
1. ✓ Single DB query: Get eligible member IDs (not N+1)
   - Checks: `members.status = 'active'`, `fees_status = 'paid'`, not expired
2. ✓ Count invalid: 30 not members or unpaid
3. ✓ Count already assigned: 20 already voters
4. ✓ Calculate new: 950 remaining (1000 - 30 invalid - 20 existing)
5. ✓ Chunk: Split 950 into 2 chunks of 500
6. ✓ Chunk 1: Insert 500 voters → SUCCESS
7. ✓ Chunk 2: Insert 500 voters → SUCCESS
8. ✓ Events & Logging: Done outside transaction

**Result:** 950 new voters assigned, zero failures (if no DB errors).

---

### Scenario 3: Bulk Failure with Recovery (Dead-Letter Queue)

```php
// Same bulk import, but one chunk has a DB issue (e.g., corrupted org_id)
$result = $handler->handle(...);

// Result:
// [
//     'success' => 500,
//     'already_existing' => 20,
//     'invalid' => 30,
//     'failed' => 450,  // 2nd chunk failed
// ]

// Failed 450 rows are written to dead_letter_queue:
$dlq = DeadLetterEntry::where('queue_name', 'voter_bulk_assign')
    ->where('organisation_id', 'org-456')
    ->get();  // 450 rows
```

**Admin remediation:**
1. Investigate why chunk 2 failed (check `error_message`)
2. Fix the root cause
3. Retry via API: `POST /api/elections/dlq/retry?queue=voter_bulk_assign`
4. Successfully assigned users marked with `retried_at`

---

## Usage Guide

### For Backend Developers

#### Assigning a Single Voter

```php
<?php

use App\Contexts\Elections\Application\Commands\AssignVoterCommand;
use App\Contexts\Elections\Application\Handlers\AssignVoterHandler;
use App\Domain\Election\Enum\ElectionMode;

// In a route or service
$handler = app(AssignVoterHandler::class);

try {
    $membership = $handler->handle(new AssignVoterCommand(
        userId: request('user_id'),
        electionId: $election->id,
        organisationId: $organisation->id,
        mode: ElectionMode::fromOrganisation($organisation),
        assignedBy: auth()->id(),
        metadata: ['imported_by' => 'csv_upload', 'batch_id' => '123'],
    ));

    return response()->json(['success' => true, 'membership' => $membership]);
} catch (\App\Contexts\Elections\Domain\Exceptions\VoterNotEligibleException $e) {
    return response()->json(['error' => $e->getMessage()], 422);
} catch (\App\Contexts\Elections\Domain\Exceptions\DuplicateVoterException $e) {
    return response()->json(['error' => $e->getMessage()], 422);
}
```

#### Bulk Assigning Voters

```php
<?php

use App\Contexts\Elections\Application\Commands\BulkAssignVotersCommand;
use App\Contexts\Elections\Application\Handlers\BulkAssignVotersHandler;

$handler = app(BulkAssignVotersHandler::class);

$result = $handler->handle(new BulkAssignVotersCommand(
    userIds: request('user_ids'),  // array of UUIDs
    electionId: $election->id,
    organisationId: $organisation->id,
    mode: ElectionMode::fromOrganisation($organisation),
    assignedBy: auth()->id(),
    idempotencyKey: request()->header('Idempotency-Key'),  // optional
    chunkSize: 500,
));

return response()->json([
    'success' => $result['success'],
    'already_existing' => $result['already_existing'],
    'invalid' => $result['invalid'],
    'failed' => $result['failed'],
]);
```

#### Checking Eligibility Without Assignment

```php
<?php

use App\Contexts\Elections\Domain\Policies\VoterEligibilityPolicy;
use App\Domain\Election\Enum\ElectionMode;

$policy = app(VoterEligibilityPolicy::class);

$isEligible = $policy->isEligible(
    userId: 'user-123',
    organisationId: 'org-456',
    mode: ElectionMode::ElectionOnly,
);

if ($isEligible) {
    echo "User can vote";
} else {
    echo "User does not meet eligibility criteria";
}
```

#### Getting Bulk Eligible Users

```php
<?php

use App\Contexts\Elections\Domain\Policies\VoterEligibilityPolicy;
use App\Domain\Election\Enum\ElectionMode;

$policy = app(VoterEligibilityPolicy::class);

$eligibleIds = $policy->qualifyingSubset(
    userIds: ['user-1', 'user-2', 'user-3', ...],
    organisationId: 'org-456',
    mode: ElectionMode::FullMembership,
);

// $eligibleIds = ['user-1', 'user-3']  (user-2 failed eligibility check)
```

### For Frontend Developers (Vue/Inertia)

#### Single Voter Assignment Form

```vue
<template>
  <form @submit.prevent="assignVoter">
    <input v-model="form.user_id" placeholder="User ID" />
    <button type="submit" :disabled="form.processing">
      {{ form.processing ? 'Assigning...' : 'Assign Voter' }}
    </button>

    <div v-if="form.errors.user_id" class="error">
      {{ form.errors.user_id }}
    </div>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  user_id: '',
});

const assignVoter = () => {
  form.post(route('elections.voters.store', {
    organisation: organisation.slug,
    election: election.slug,
  }), {
    onSuccess: () => {
      form.reset();
      // Refresh voter list, show success toast, etc.
    },
  });
};
</script>
```

#### Bulk Import with Progress

```vue
<template>
  <div class="bulk-import">
    <textarea v-model="userIds" placeholder="Paste user IDs (one per line)"></textarea>
    
    <button @submit.prevent="bulkImport" :disabled="importing">
      {{ importing ? 'Importing...' : 'Import Voters' }}
    </button>

    <div v-if="result" class="results">
      <p>✓ Success: {{ result.success }}</p>
      <p>⚠ Already assigned: {{ result.already_existing }}</p>
      <p>✗ Invalid: {{ result.invalid }}</p>
      <p v-if="result.failed > 0" class="error">
        ✗ Failed: {{ result.failed }} (check dead-letter queue)
      </p>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const userIds = ref('');
const importing = ref(false);
const result = ref(null);

const bulkImport = () => {
  importing.value = true;
  
  router.post(route('elections.voters.bulk-store', {
    organisation: organisation.slug,
    election: election.slug,
  }), {
    user_ids: userIds.value.split('\n').filter(id => id.trim()),
  }, {
    onSuccess: (page) => {
      result.value = page.props.flash.result;
    },
    onFinish: () => {
      importing.value = false;
    },
  });
};
</script>
```

---

## Testing

### Unit Tests (TDD)

#### Handler Tests
```bash
php artisan test tests/Unit/Contexts/Elections/AssignVoterHandlerTest.php
php artisan test tests/Unit/Contexts/Elections/BulkAssignVotersHandlerTest.php
```

Key test patterns:

```php
// Test 1: Policy filters out ineligible users
public function test_filters_ineligible_users_before_any_writes(): void
{
    $this->policyMock
        ->expects($this->once())
        ->method('qualifyingSubset')
        ->willReturn(['user-1', 'user-2']);  // 1 of 3 filtered

    $result = $this->handler->handle($command);
    $this->assertEquals(1, $result['invalid']);
}

// Test 2: Bulk handler continues after chunk failure
public function test_continues_processing_after_chunk_failure(): void
{
    // Mock: chunk 1 succeeds, chunk 2 fails, chunk 3 succeeds
    $this->repositoryMock
        ->method('bulkInsert')
        ->will($this->onConsecutiveCalls(
            $this->returnValue(null),
            $this->throwException(new \Exception('DB error')),
            $this->returnValue(null),
        ));

    $result = $this->handler->handle($command);
    $this->assertEquals(1000, $result['success']);  // 500 + 500
    $this->assertEquals(500, $result['failed']);    // Middle chunk
}
```

#### Model Tests
```bash
php artisan test tests/Unit/Models/ElectionMembershipTest.php
php artisan test tests/Unit/Models/DeadLetterEntryTest.php
```

#### Policy Tests
```bash
php artisan test tests/Feature/Contexts/Elections/EloquentVoterEligibilityQueryServiceTest.php
```

### Integration Tests

```bash
php artisan test tests/Feature/Election/ElectionOnlyModeTest.php
```

Test entire flow from request to database:

```php
public function test_election_only_voter_assignment_end_to_end(): void
{
    // Setup
    $org = Organisation::factory()->create(['uses_full_membership' => false]);
    $user = User::factory()->create();
    $org->users()->attach($user->id);  // Only org_user record, no members!
    $election = Election::factory()->create(['organisation_id' => $org->id]);

    // Action
    $response = $this->post(route('elections.voters.store', [
        'organisation' => $org->slug,
        'election' => $election->slug,
    ]), ['user_id' => $user->id]);

    // Assert
    $response->assertRedirect();
    $this->assertDatabaseHas('election_memberships', [
        'user_id' => $user->id,
        'election_id' => $election->id,
        'status' => 'active',
    ]);
}
```

### Running Tests

```bash
# All election tests
php artisan test tests/Unit/Contexts/Elections/ tests/Unit/Models/

# With coverage
php artisan test --coverage tests/Unit/Contexts/Elections/

# Specific test method
php artisan test tests/Unit/Contexts/Elections/BulkAssignVotersHandlerTest.php --filter=test_continues_processing_after_chunk_failure
```

---

## Database Schema

### Key Tables

#### `election_memberships`
```sql
CREATE TABLE election_memberships (
    id UUID PRIMARY KEY,
    user_id UUID NOT NULL,
    organisation_id UUID NOT NULL,
    election_id UUID NOT NULL,
    
    role VARCHAR(50) DEFAULT 'voter',  -- 'voter' or 'candidate'
    status VARCHAR(50) DEFAULT 'active',
    
    assigned_by UUID,
    assigned_at TIMESTAMP,
    
    expires_at TIMESTAMP NULL,
    has_voted BOOLEAN DEFAULT FALSE,
    voted_at TIMESTAMP NULL,
    
    suspension_status VARCHAR(50) DEFAULT 'none',
    suspension_proposed_by VARCHAR(255),
    suspension_proposed_at TIMESTAMP NULL,
    
    metadata JSON DEFAULT '{}',
    
    deleted_at TIMESTAMP NULL,  -- Soft deletes
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    -- Indexes for fast queries
    INDEX (election_id, user_id),
    INDEX (organisation_id),
    
    -- Unique constraint allowing soft-deleted rows to be re-imported
    UNIQUE KEY uq_user_election_active (user_id, election_id) 
        WHERE deleted_at IS NULL,
    
    -- Note: FK to user_organisation_roles was DROPPED in Phase C.1
    -- Application-level policy now validates eligibility
);
```

#### `dead_letter_queue`
```sql
CREATE TABLE dead_letter_queue (
    id UUID PRIMARY KEY,
    
    queue_name VARCHAR(255),  -- 'voter_bulk_assign'
    payload JSON,             -- {user_id, election_id, organisation_id}
    error_message TEXT,
    error_class VARCHAR(255),
    
    organisation_id UUID,
    election_id UUID,
    
    failed_at TIMESTAMP,
    retried_at TIMESTAMP NULL,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX (queue_name, failed_at),
    INDEX (organisation_id),
    INDEX (election_id),
);
```

### Migration Path

```
2026-05-19: Add soft deletes + drop FK + create partial unique index
2026-05-20: Create dead_letter_queue table
```

---

## Migration Notes

### For Developers Working on Existing Code

#### What Changed?

| Item | Before | After |
|------|--------|-------|
| **FK Constraint** | `(user_id, org_id) → user_organisation_roles` | Dropped—app-level policy owns validation |
| **Voter Assignment** | `ElectionMembership::assignVoter()` | `AssignVoterHandler->handle()` |
| **Bulk Assignment** | `ElectionMembership::bulkAssignVoters()` | `BulkAssignVotersHandler->handle()` |
| **Eligibility Check** | In model method | `VoterEligibilityPolicy` interface |
| **Cache Keys** | `election.{id}.voter_count` | `org.{org_id}.election.{id}.voter_count` |
| **Soft Deletes** | No | Yes—allows re-import without collision |
| **Failed Rows** | Lost | Tracked in `dead_letter_queue` for recovery |

#### Backward Compatibility

**Deprecated model methods are REMOVED:**
```php
// ❌ These no longer exist
ElectionMembership::assignVoter($userId, $electionId);
ElectionMembership::bulkAssignVoters($userIds, $electionId);
```

**Use handlers instead:**
```php
// ✅ New way
app(AssignVoterHandler::class)->handle(new AssignVoterCommand(...));
app(BulkAssignVotersHandler::class)->handle(new BulkAssignVotersCommand(...));
```

#### Testing with Handlers

Old test pattern:
```php
$membership = ElectionMembership::assignVoter($user->id, $election->id);
```

New test pattern:
```php
$membership = ElectionMembership::create([
    'user_id' => $user->id,
    'election_id' => $election->id,
    'organisation_id' => $org->id,
    'role' => 'voter',
    'status' => 'active',
]);
```

---

## Future Phases

### Phase D: Admin Dashboard

- View voter list per election
- Bulk import UI with progress
- Dead-letter queue management UI
- Failed row recovery interface

### Phase E: Election Results & Verification

- Anonymous vote counting
- Results publication
- Voter verification (did I vote?)
- Audit log viewer

### Phase F: Advanced Features

- Voter suspension workflow (2-person approval)
- Regional voting restrictions
- Ranked choice voting
- Ballot secrecy verification

### Phase G: Deployment & Operations

- Feature flag for gradual rollout
- Monitoring & alerting
- Disaster recovery procedures
- Performance optimization (batch processing)

---

## Troubleshooting

### Issue: "User not eligible to vote"

**Causes:**
1. User not in `organisation_users` (election-only mode)
2. User not in `members` with paid fees (full membership mode)
3. Membership expired
4. User soft-deleted from organization

**Debug:**
```php
$policy = app(VoterEligibilityPolicy::class);
$eligible = $policy->isEligible($userId, $orgId, $mode);

if (!$eligible) {
    // Check what table is being queried
    if ($mode->isElectionOnly()) {
        DB::table('organisation_users')
            ->where('user_id', $userId)
            ->where('organisation_id', $orgId)
            ->dump();  // See actual record
    } else {
        DB::table('members')
            ->whereHas('organisationUser', fn($q) => $q->where('user_id', $userId))
            ->dump();
    }
}
```

### Issue: "Duplicate voter exception" when re-importing

**Solution:** Check soft-deleted records:
```php
$deleted = ElectionMembership::onlyTrashed()
    ->where('user_id', $userId)
    ->where('election_id', $electionId)
    ->first();

if ($deleted) {
    // User was previously removed—safe to restore
    $deleted->restore();
}
```

### Issue: Bulk import failed—some rows in dead-letter queue

**Steps:**
1. Check `dead_letter_queue` table:
   ```php
   DeadLetterEntry::where('queue_name', 'voter_bulk_assign')
       ->where('organisation_id', $orgId)
       ->get();
   ```

2. Investigate error message:
   ```php
   foreach ($failed as $row) {
       echo "{$row->payload['user_id']}: {$row->error_message}\n";
   }
   ```

3. Fix root cause (e.g., user wasn't in organization)

4. Retry:
   ```php
   // Admin retries via UI, handler queries dead-letter entries
   // and re-attempts assignment
   ```

### Issue: Cache not updating after voter assignment

**Check:**
```php
$key = "org.{$orgId}.election.{$electionId}.voter_count";
echo Cache::get($key);  // Should be NULL after assignment

// If still present, handler didn't call forgetVoterKeys()
// Verify ElectionCacheService is injected in handler
```

---

## Related Documentation

- **Architecture**: `developer_guide/election/architecture.md`
- **Database Migrations**: `database/migrations/`
- **Tests**: `tests/Unit/Contexts/Elections/`
- **Phase Summary**: `PHASE_C_STRANGLER_SUMMARY.md`

---

## Quick Reference

### Key Classes

| Class | Purpose |
|-------|---------|
| `ElectionMode` | Enum: `ElectionOnly` or `FullMembership` |
| `AssignVoterCommand` | DTO for single voter assignment |
| `BulkAssignVotersCommand` | DTO for bulk voter assignment |
| `AssignVoterHandler` | Orchestrates single assignment |
| `BulkAssignVotersHandler` | Orchestrates bulk assignment |
| `VoterEligibilityPolicy` | Interface for eligibility decisions |
| `EloquentVoterRepository` | Data access for memberships |
| `VoterAssignedToElection` | Domain event (single) |
| `BulkVotersAssignedToElection` | Domain event (bulk) |
| `DeadLetterEntry` | Failed row tracking for recovery |
| `ElectionCacheService` | Tenant-isolated cache keys |

### Key Routes

```
POST   /api/organisations/{org}/elections/{election}/voters
       → AssignVoterHandler (single assignment)

POST   /api/organisations/{org}/elections/{election}/voters/bulk
       → BulkAssignVotersHandler (bulk assignment)

GET    /api/organisations/{org}/elections/{election}/voters
       → List assigned voters (scoped to election)

GET    /api/organisations/{org}/dead-letter-queue
       → View failed rows for retry

POST   /api/organisations/{org}/dead-letter-queue/retry
       → Manually retry failed rows
```

---

**Last Updated**: 2026-05-18  
**Phases Covered**: A (Freeze), B (Policy Layer), C (Strangler Migration)  
**Status**: ✅ Production Ready
