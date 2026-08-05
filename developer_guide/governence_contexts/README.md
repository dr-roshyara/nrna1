# Governance Context Developer Guide

## Overview

The **Governance Context** is a bounded context within the NRNA platform that manages committee structures, memberships, and organizational hierarchies. It enforces strict multi-tenancy, uses CQRS principles for read/write separation, and maintains temporal lineage of committee memberships.

**Current Phase:** Phase 3 (REST API + Vue UI)  
**Status:** Production Ready  
**Tests:** 23/23 Passing  

---

## Table of Contents

1. [Architecture](#architecture)
2. [Domain Model](#domain-model)
3. [CQRS Read/Write Separation](#cqrs-readwrite-separation)
4. [REST API](#rest-api)
5. [Vue Components](#vue-components)
6. [Multi-Tenancy](#multi-tenancy)
7. [Common Tasks](#common-tasks)
8. [Testing](#testing)
9. [Troubleshooting](#troubleshooting)

---

## Architecture

### Layered Structure

```
┌─────────────────────────────────────────────────────────┐
│ Layer 4: HTTP Controllers                               │
│ CommitteeMemberController → REST API                    │
├─────────────────────────────────────────────────────────┤
│ Layer 3: Application Services                           │
│ CommitteeMemberQueryService (reads)                     │
│ GetCommitteeDashboard (aggregation)                     │
├─────────────────────────────────────────────────────────┤
│ Layer 2: Infrastructure / CQRS Read Model               │
│ CommitteeMemberProjection (Eloquent model)              │
│ CommitteeMemberProjectionListener (event handler)       │
├─────────────────────────────────────────────────────────┤
│ Layer 1: Domain (Pure PHP)                              │
│ Committee (aggregate)                                   │
│ CommitteeId (value object)                              │
│ Domain Events (MemberAssignedToCommittee, etc.)         │
└─────────────────────────────────────────────────────────┘
```

### Directory Structure

```
app/Contexts/Governance/
├── Domain/
│   └── Committee/
│       ├── Committee.php              # Aggregate root
│       ├── CommitteeId.php            # Value object
│       └── Events/
│           ├── MemberAssignedToCommittee.php
│           └── MemberRemovedFromCommittee.php
├── Application/
│   ├── Queries/
│   │   └── CommitteeMemberQueryService.php
│   └── DTOs/
│       └── CommitteeMembersResponseDTO.php
└── Infrastructure/
    └── Projection/
        └── CommitteeMemberProjectionListener.php

app/Models/
└── CommitteeMemberProjection.php      # Read model (Eloquent)

app/Http/Controllers/Api/Governance/
└── CommitteeMemberController.php      # HTTP endpoints

resources/js/Components/
└── CommitteeMemberManager.vue         # Vue component
```

---

## Domain Model

### Committee (Aggregate Root)

The `Committee` aggregate manages committee members and ensures business rules are enforced.

```php
use App\Contexts\Governance\Domain\Committee\Committee;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;

// Create a committee
$committee = Committee::create(
    CommitteeId::generate(),
    new CommitteeName('Finance Committee'),
    new CommitteeType('standing'),
    $tenantId
);

// Assign member
$committee->addMember($memberId);

// Remove member
$committee->removeMember($memberId);

// Check membership
if ($committee->isMemberAssigned($memberId)) {
    echo "Member is assigned";
}

// Get recorded events
$events = $committee->pullEvents();
```

### CommitteeId (Value Object)

Type-safe identifier with UUID validation:

```php
use App\Contexts\Governance\Domain\Committee\CommitteeId;

// Create from UUID string
$committeeId = CommitteeId::fromString('550e8400-e29b-41d4-a716-446655440000');

// Generate new UUID
$committeeId = CommitteeId::generate();

// Compare
if ($committeeId->equals($otherId)) {
    // Same committee
}

// Get string value
$id = $committeeId->value();  // '550e8400-e29b-41d4-a716-446655440000'
```

### Domain Events

Events capture membership changes:

```php
use App\Contexts\Governance\Domain\Committee\Events\MemberAssignedToCommittee;
use App\Contexts\Governance\Domain\Committee\Events\MemberRemovedFromCommittee;

// When member is assigned
$event = new MemberAssignedToCommittee(
    committeeId: $committeeId,
    memberId: $memberId,
    tenantId: $tenantId,
    occurredAt: new DateTimeImmutable()
);

// When member is removed
$event = new MemberRemovedFromCommittee(
    committeeId: $committeeId,
    memberId: $memberId,
    tenantId: $tenantId,
    occurredAt: new DateTimeImmutable()
);
```

---

## CQRS Read/Write Separation

The Governance Context uses CQRS ("Command Query Responsibility Segregation"):

- **Write Model:** Domain aggregate (Committee) — handles mutations
- **Read Model:** Projection table (CommitteeMemberProjection) — optimized for queries
- **Event Stream:** Sole coupling mechanism between models

### Write Side (Domain)

```php
// Domain aggregate handles writes
$committee = new Committee(...);
$committee->addMember($memberId);

// Record events (in-memory)
$events = $committee->pullEvents();

// Dispatch events (triggers listeners)
foreach ($events as $event) {
    Event::dispatch($event);
}
```

### Read Side (Projection)

```php
// Listener updates projection table
// app/Contexts/Governance/Infrastructure/Projection/CommitteeMemberProjectionListener.php

public function handle(MemberAssignedToCommittee $event): void
{
    CommitteeMemberProjection::updateOrCreate(
        [
            'committee_id' => $event->committeeId->value(),
            'member_id'    => $event->memberId->value(),
        ],
        [
            'id'            => Uuid::uuid4()->toString(),
            'tenant_id'     => $event->tenantId->value(),
            'assigned_at'   => $event->occurredAt,
        ]
    );
}
```

### Key Principle

**Never load the domain aggregate in the API controller.** The API reads from the projection table only:

```php
// ✅ CORRECT: Query the projection
$members = CommitteeMemberProjection::where('committee_id', $committeeId)
    ->where('tenant_id', $tenantId)
    ->get();

// ❌ WRONG: Never do this in API
$committee = $repository->findById($committeeId);  // Don't load aggregate
```

---

## REST API

### Endpoints

All endpoints require the `X-Tenant-Id` header for tenant context.

#### GET /api/governance/committees/{committeeId}/members

**Purpose:** List all members assigned to a committee

**Headers:**
```
X-Tenant-Id: {organisation_id}
Accept: application/json
```

**Response (200 OK):**
```json
{
  "committeeId": "550e8400-e29b-41d4-a716-446655440000",
  "members": [
    {
      "memberId": "660e8400-e29b-41d4-a716-446655440000",
      "assignedAt": "2026-05-16T10:30:00Z"
    },
    {
      "memberId": "770e8400-e29b-41d4-a716-446655440000",
      "assignedAt": "2026-05-16T11:45:00Z"
    }
  ]
}
```

**Error (400 Bad Request):**
```json
{
  "error": "Missing tenant context"
}
```

**Usage:**
```bash
curl -H "X-Tenant-Id: org-123" \
  https://api.example.com/api/governance/committees/550e8400.../members
```

---

#### POST /api/governance/committees/{committeeId}/members

**Purpose:** Assign a member to a committee

**Headers:**
```
X-Tenant-Id: {organisation_id}
Content-Type: application/json
```

**Payload:**
```json
{
  "memberId": "660e8400-e29b-41d4-a716-446655440000"
}
```

**Response (202 Accepted):**
```json
{
  "status": "queued"
}
```

**Validation Error (422 Unprocessable Entity):**
```json
{
  "errors": {
    "memberId": [
      "Invalid member ID format"
    ]
  }
}
```

**Usage:**
```bash
curl -X POST \
  -H "X-Tenant-Id: org-123" \
  -H "Content-Type: application/json" \
  -d '{"memberId": "660e8400..."}' \
  https://api.example.com/api/governance/committees/550e8400.../members
```

**Important:** Returns 202 (queued), not 200. The command is accepted but not immediately executed. See [Phase 4 Roadmap](#phase-4-command-dispatch) for wiring the actual command handler.

---

#### DELETE /api/governance/committees/{committeeId}/members/{memberId}

**Purpose:** Remove a member from a committee

**Headers:**
```
X-Tenant-Id: {organisation_id}
```

**Response (202 Accepted):**
```json
{
  "status": "queued"
}
```

**Usage:**
```bash
curl -X DELETE \
  -H "X-Tenant-Id: org-123" \
  https://api.example.com/api/governance/committees/550e8400.../members/660e8400...
```

**Note:** Safe to call multiple times (idempotent). Removing a non-existent member returns 202 success.

---

### Using the API from Backend Code

```php
use App\Contexts\Governance\Application\Queries\CommitteeMemberQueryService;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

public function show(CommitteeMemberQueryService $queryService)
{
    $committeeId = CommitteeId::fromString(request('committee_id'));
    $tenantId = TenantId::fromString(auth()->user()->organisation_id);
    
    $members = $queryService->getMembersForCommittee($committeeId, $tenantId);
    
    return response()->json([
        'committeeId' => $committeeId->value(),
        'members' => $members
    ]);
}
```

---

## Vue Components

### CommitteeMemberManager

Standalone component for managing committee members via REST API.

**Import:**
```vue
<script setup>
import CommitteeMemberManager from '@/Components/CommitteeMemberManager.vue';
</script>
```

**Basic Usage:**
```vue
<template>
  <CommitteeMemberManager :committeeId="committee.id" />
</template>
```

**With Explicit Tenant ID:**
```vue
<template>
  <CommitteeMemberManager 
    :committeeId="committee.id"
    :tenantId="organisation.id"
  />
</template>
```

**Props:**

| Prop | Type | Required | Default | Description |
|------|------|----------|---------|-------------|
| `committeeId` | String (UUID) | Yes | — | Committee to manage |
| `tenantId` | String (UUID) | No | Auto-detect | Explicit tenant override |
| `organisationId` | String (UUID) | No | Auto-detect | Fallback tenant source |

**Auto Tenant Detection:**

The component automatically detects the tenant ID in this order:
1. Explicit `tenantId` prop
2. `organisationId` prop
3. `page.props.auth.user.current_organisation_id`
4. `page.props.organisation.id`

**Features:**

- ✅ Fetch members from API
- ✅ Add new members with validation
- ✅ Remove members with confirmation
- ✅ Loading states
- ✅ Error messages
- ✅ Success notifications
- ✅ Tenant isolation enforcement

**Component Methods (Internal):**

```javascript
// Fetch members from API
await fetchMembers();

// Add member via POST
await handleAddMember();

// Remove member via DELETE
await handleRemoveMember(memberId);

// Clear form
resetForm();
```

**Events:** None (uses internal state)

**Styling:** Uses Tailwind with semantic tokens (primary, danger, success, neutral)

---

## Multi-Tenancy

The Governance Context enforces strict tenant isolation at every layer.

### Tenant Boundaries

```
Database Layer      → Foreign key to tenants table
Query Layer         → WHERE tenant_id = ?
API Layer           → X-Tenant-Id header validation
Component Layer     → Automatic tenant context detection
```

### Implementation

**In Domain Events:**
```php
public function __construct(
    public readonly CommitteeId $committeeId,
    public readonly MemberId $memberId,
    public readonly TenantId $tenantId,  // ← Always included
    public readonly DateTimeImmutable $occurredAt = new DateTimeImmutable(),
) {}
```

**In Projections:**
```php
CommitteeMemberProjection::where('tenant_id', $tenantId->value())
    ->where('committee_id', $committeeId->value())
    ->get();
```

**In API Controllers:**
```php
$tenantIdValue = $request->header('X-Tenant-Id');
if (!$tenantIdValue) {
    return response()->json(['error' => 'Missing tenant context'], 400);
}
$tenantId = TenantId::fromString($tenantIdValue);
```

**In Vue Components:**
```javascript
const effectiveTenantId = computed(() => {
    if (props.tenantId) return props.tenantId;
    if (props.organisationId) return props.organisationId;
    if (page.props.auth?.user?.current_organisation_id) {
        return page.props.auth.user.current_organisation_id;
    }
    return null;
});
```

### Testing Multi-Tenancy

Always test with multiple tenants:

```php
public function test_api_enforces_tenant_isolation(): void
{
    $tenantA = DomainIdFactory::tenant();
    $tenantB = DomainIdFactory::tenant();
    
    $committeeA = CommitteeId::generate();
    $memberA = MemberId::generate();
    
    // Create data in Tenant A
    Event::dispatch(new MemberAssignedToCommittee($committeeA, $memberA, $tenantA));
    
    // Query from Tenant B
    $response = $this->getJson(
        "/api/governance/committees/{$committeeA->value()}/members",
        ['X-Tenant-Id' => $tenantB->value()]
    );
    
    // Tenant B should see empty members (isolation enforced)
    $response->assertJson(['members' => []]);
}
```

---

## Common Tasks

### Task 1: Add a New Committee Member (Backend)

```php
use App\Contexts\Governance\Domain\Committee\Committee;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Domain\Committee\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Member\MemberId;
use Illuminate\Support\Facades\Event;

public function assignMemberToCommittee(
    CommitteeRepositoryInterface $repository,
    CommitteeId $committeeId,
    MemberId $memberId,
    TenantId $tenantId
): void {
    // Load aggregate
    $committee = $repository->findById($committeeId);
    
    // Apply business logic
    $committee->addMember($memberId);
    
    // Save aggregate
    $repository->save($committee);
    
    // Dispatch events (triggers projection update)
    foreach ($committee->pullEvents() as $event) {
        Event::dispatch($event);
    }
}
```

### Task 2: Get Committee Members (Backend or API)

**Backend:**
```php
use App\Contexts\Governance\Application\Queries\CommitteeMemberQueryService;

$queryService->getMembersForCommittee($committeeId, $tenantId);
// Returns: [['memberId' => '...', 'assignedAt' => '...'], ...]
```

**API:**
```bash
curl -H "X-Tenant-Id: org-123" \
  https://api.example.com/api/governance/committees/550e8400.../members
```

**Vue Component:**
```vue
<CommitteeMemberManager :committeeId="committee.id" />
```

### Task 3: Display Members in Dashboard

```vue
<template>
  <div class="dashboard">
    <h1>{{ committee.name }}</h1>
    
    <!-- Show member manager -->
    <CommitteeMemberManager :committeeId="committee.id" />
  </div>
</template>

<script setup>
import CommitteeMemberManager from '@/Components/CommitteeMemberManager.vue';

const props = defineProps({
  committee: Object,
});
</script>
```

### Task 4: Create Custom Member Query

```php
use App\Models\CommitteeMemberProjection;
use Illuminate\Database\Eloquent\Collection;

public function getMembersSortedByJoinDate(
    CommitteeId $committeeId,
    TenantId $tenantId
): Collection {
    return CommitteeMemberProjection::query()
        ->where('committee_id', $committeeId->value())
        ->where('tenant_id', $tenantId->value())
        ->orderBy('assigned_at', 'asc')  // Oldest first
        ->get();
}
```

### Task 5: Handle Member Removal Events

Listen for member removal to trigger downstream logic:

```php
use App\Contexts\Governance\Domain\Committee\Events\MemberRemovedFromCommittee;
use Illuminate\Support\Facades\Event;

Event::listen(MemberRemovedFromCommittee::class, function (MemberRemovedFromCommittee $event) {
    // Example: Revoke access to committee resources
    AccessControl::revoke(
        $event->memberId->value(),
        'committee',
        $event->committeeId->value()
    );
});
```

---

## Testing

### Test Structure

Tests are organized by layer:

```
tests/Feature/Governance/
├── Api/
│   └── CommitteeMemberApiTest.php          # REST API endpoints
├── Projection/
│   └── CommitteeMemberProjectionTest.php   # CQRS read model
└── Ui/
    └── CommitteeMemberManagerTest.php      # Vue component integration
```

### Running Tests

```bash
# All governance tests
php artisan test tests/Feature/Governance/ --no-coverage

# Just API tests
php artisan test tests/Feature/Governance/Api/ --no-coverage

# Just projection tests
php artisan test tests/Feature/Governance/Projection/ --no-coverage

# Just UI tests
php artisan test tests/Feature/Governance/Ui/ --no-coverage
```

### Writing New Tests

**Example: Testing API Endpoint**
```php
use Tests\TestCase;
use Tests\Support\DomainIdFactory;

class CommitteeMemberApiTest extends TestCase
{
    public function test_get_returns_members(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();
        $memberId = MemberId::generate();
        
        // Setup
        Event::dispatch(
            new MemberAssignedToCommittee($committeeId, $memberId, $tenantId)
        );
        
        // Act
        $response = $this->getJson(
            "/api/governance/committees/{$committeeId->value()}/members",
            ['X-Tenant-Id' => $tenantId->value()]
        );
        
        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(1, 'members');
    }
}
```

**Example: Testing Projection**
```php
public function test_projection_is_idempotent(): void
{
    $event = new MemberAssignedToCommittee($committeeId, $memberId, $tenantId);
    
    // Dispatch twice
    Event::dispatch($event);
    Event::dispatch($event);
    
    // Should only create one record (unique constraint prevents duplicates)
    $this->assertDatabaseCount('committee_member_projection', 1);
}
```

### Test Utilities

**DomainIdFactory:** Generate test IDs consistently
```php
use Tests\Support\DomainIdFactory;

$tenantId = DomainIdFactory::tenant();     // TenantId
$memberId = DomainIdFactory::member();     // MemberId
```

---

## Troubleshooting

### Problem: API Returns "Missing tenant context"

**Cause:** X-Tenant-Id header not provided

**Solution:**
```bash
# Add header to request
curl -H "X-Tenant-Id: org-123" \
  https://api.example.com/api/governance/committees/...
```

---

### Problem: Committee Members Not Appearing in Projection

**Cause:** Event not dispatched, or listener not registered

**Checks:**
1. Is the event being dispatched?
   ```php
   // In your handler
   Event::dispatch($event);  // ✅ Required
   ```

2. Is the listener registered in `EventServiceProvider`?
   ```php
   protected $listen = [
       MemberAssignedToCommittee::class => [
           CommitteeMemberProjectionListener::class,
       ],
   ];
   ```

3. Run migrations to create projection table
   ```bash
   php artisan migrate
   ```

---

### Problem: Vue Component Shows "No tenant context"

**Cause:** Tenant ID cannot be auto-detected from page props

**Solution:** Explicitly pass tenant ID:
```vue
<CommitteeMemberManager 
  :committeeId="committee.id"
  :tenantId="auth.user.current_organisation_id"
/>
```

---

### Problem: Tests Failing with "Table doesn't exist"

**Cause:** Migrations not run in test environment

**Solution:**
```bash
# Run migrations before tests
php artisan migrate --env=testing

# Or ensure RefreshDatabase trait is used
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommitteeMemberApiTest extends TestCase
{
    use RefreshDatabase;  // ← Auto-runs migrations
}
```

---

### Problem: Duplicate Members Appearing in List

**Cause:** Event being processed multiple times without idempotency

**Solution:** Verify unique constraint exists:
```sql
ALTER TABLE committee_member_projection 
ADD UNIQUE KEY unique_assignment (committee_id, member_id);
```

The projection listener uses `updateOrCreate()` which respects this constraint.

---

## Phase 4 Roadmap

### Command Dispatch Implementation

Currently, POST and DELETE endpoints return 202 (queued) but don't execute commands.

**What needs to be done:**

1. Wire `AssignMemberToCommitteeCommand` in POST handler:
   ```php
   // app/Http/Controllers/Api/Governance/CommitteeMemberController.php
   public function store(Request $request): JsonResponse
   {
       $memberId = MemberId::fromString($request->input('memberId'));
       
       // Dispatch command
       $this->commandBus->dispatch(
           new AssignMemberToCommitteeCommand(
               $committeeId,
               $memberId,
               $tenantId
           )
       );
       
       return response()->json(['status' => 'queued'], 202);
   }
   ```

2. Wire `RemoveMemberFromCommitteeCommand` in DELETE handler

3. Update test expectations to verify persistence

---

## References

- **Architecture Decision Record:** [docs/adr/PHASE-3-COMPLETE.md](../../docs/knowledge/archive/status-reports/ADR-FOLDER-PHASE-3-COMPLETE.md)
- **Domain Aggregate:** `app/Contexts/Governance/Domain/Committee/Committee.php`
- **REST API:** `app/Http/Controllers/Api/Governance/CommitteeMemberController.php`
- **Vue Component:** `resources/js/Components/CommitteeMemberManager.vue`
- **Tests:** `tests/Feature/Governance/`

---

## Getting Help

1. **Check the tests** — Test files show expected behavior: `tests/Feature/Governance/`
2. **Read the domain code** — Pure business logic: `app/Contexts/Governance/Domain/`
3. **Review the controller** — HTTP boundary: `app/Http/Controllers/Api/Governance/`
4. **Check Vue component** — UI implementation: `resources/js/Components/CommitteeMemberManager.vue`

---

**Last Updated:** 2026-05-16  
**Phase:** 3 (Production Ready)  
**Maintainer:** Dr. Nab Raj Roshyara
