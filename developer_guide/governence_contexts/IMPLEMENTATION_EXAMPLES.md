# Implementation Examples & Patterns

This guide provides copy-paste examples for common tasks in the Governance Context.

---

## Table of Contents

1. [Creating Committees](#creating-committees)
2. [Managing Committee Members](#managing-committee-members)
3. [Querying Committees](#querying-committees)
4. [API Consumption](#api-consumption)
5. [Vue Component Usage](#vue-component-usage)
6. [Event Handling](#event-handling)
7. [Error Handling](#error-handling)
8. [Testing Patterns](#testing-patterns)

---

## Creating Committees

### Simple Committee Creation

```php
use App\Contexts\Governance\Domain\Committee\Committee;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Domain\Committee\ValueObjects\CommitteeName;
use App\Contexts\Governance\Domain\Committee\ValueObjects\CommitteeType;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\Event;

// In your application service or command handler
public function createCommittee(
    string $name,
    string $type,
    string $tenantId
): CommitteeId {
    // Create value objects
    $committeeId = CommitteeId::generate();
    $committeeName = new CommitteeName($name);
    $committeeType = new CommitteeType($type);
    $tenant = TenantId::fromString($tenantId);
    
    // Create aggregate
    $committee = Committee::create(
        $committeeId,
        $committeeName,
        $committeeType,
        $tenant
    );
    
    // Dispatch events (triggers projection update)
    foreach ($committee->pullEvents() as $event) {
        Event::dispatch($event);
    }
    
    return $committeeId;
}
```

### Create with Geographic Scope

```php
public function createRegionalCommittee(
    string $name,
    string $type,
    string $geoUnitId,  // State, district, etc.
    string $tenantId
): CommitteeId {
    $committeeId = CommitteeId::generate();
    
    // Create with geographic reference
    $committee = Committee::create(
        $committeeId,
        new CommitteeName($name),
        new CommitteeType($type),
        TenantId::fromString($tenantId)
    );
    
    // Optionally set geographic scope
    // (Depends on Committee aggregate supporting this)
    
    foreach ($committee->pullEvents() as $event) {
        Event::dispatch($event);
    }
    
    return $committeeId;
}
```

---

## Managing Committee Members

### Assign a Member to Committee

```php
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Domain\Committee\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Member\MemberId;
use Illuminate\Support\Facades\Event;

public function assignMemberToCommittee(
    CommitteeRepositoryInterface $repository,
    string $committeeId,
    string $memberId,
    string $tenantId
): void {
    // Load aggregate
    $committee = $repository->findById(
        CommitteeId::fromString($committeeId)
    );
    
    if (!$committee) {
        throw new \DomainException('Committee not found');
    }
    
    // Apply business logic
    $committee->addMember(
        MemberId::fromString($memberId)
    );
    
    // Save aggregate
    $repository->save($committee);
    
    // Dispatch events
    foreach ($committee->pullEvents() as $event) {
        Event::dispatch($event);
    }
}
```

### Remove a Member from Committee

```php
public function removeMemberFromCommittee(
    CommitteeRepositoryInterface $repository,
    string $committeeId,
    string $memberId,
    string $tenantId
): void {
    $committee = $repository->findById(
        CommitteeId::fromString($committeeId)
    );
    
    if (!$committee) {
        throw new \DomainException('Committee not found');
    }
    
    $committee->removeMember(
        MemberId::fromString($memberId)
    );
    
    $repository->save($committee);
    
    foreach ($committee->pullEvents() as $event) {
        Event::dispatch($event);
    }
}
```

### Batch Assign Multiple Members

```php
public function assignMultipleMembersToCommittee(
    CommitteeRepositoryInterface $repository,
    string $committeeId,
    array $memberIds,  // ['uuid1', 'uuid2', ...]
    string $tenantId
): void {
    $committee = $repository->findById(
        CommitteeId::fromString($committeeId)
    );
    
    if (!$committee) {
        throw new \DomainException('Committee not found');
    }
    
    foreach ($memberIds as $memberId) {
        $committee->addMember(
            MemberId::fromString($memberId)
        );
    }
    
    $repository->save($committee);
    
    // Dispatch all events at once
    foreach ($committee->pullEvents() as $event) {
        Event::dispatch($event);
    }
}
```

---

## Querying Committees

### Get All Members of a Committee

```php
use App\Contexts\Governance\Application\Queries\CommitteeMemberQueryService;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

public function getCommitteeMembers(
    CommitteeMemberQueryService $queryService,
    string $committeeId,
    string $tenantId
): array {
    return $queryService->getMembersForCommittee(
        CommitteeId::fromString($committeeId),
        TenantId::fromString($tenantId)
    );
    
    // Returns:
    // [
    //     ['memberId' => 'uuid', 'assignedAt' => '2026-05-16T...'],
    //     ['memberId' => 'uuid', 'assignedAt' => '2026-05-17T...'],
    // ]
}
```

### Check if Member is Assigned to Committee

```php
use App\Models\CommitteeMemberProjection;

public function isMemberAssignedToCommittee(
    string $committeeId,
    string $memberId,
    string $tenantId
): bool {
    return CommitteeMemberProjection::where('committee_id', $committeeId)
        ->where('member_id', $memberId)
        ->where('tenant_id', $tenantId)
        ->exists();
}
```

### Get Members Sorted by Assignment Date

```php
use App\Models\CommitteeMemberProjection;
use Illuminate\Database\Eloquent\Collection;

public function getMembersSortedByAssignment(
    string $committeeId,
    string $tenantId,
    string $order = 'desc'  // 'asc' for oldest first
): Collection {
    return CommitteeMemberProjection::query()
        ->where('committee_id', $committeeId)
        ->where('tenant_id', $tenantId)
        ->orderBy('assigned_at', $order)
        ->get();
}
```

### Get Committees for a Member

```php
public function getCommitteesForMember(
    string $memberId,
    string $tenantId
): Collection {
    return CommitteeMemberProjection::query()
        ->where('member_id', $memberId)
        ->where('tenant_id', $tenantId)
        ->pluck('committee_id')
        ->unique()
        ->values();
}
```

### Count Members in Committee

```php
public function countCommitteeMembers(
    string $committeeId,
    string $tenantId
): int {
    return CommitteeMemberProjection::query()
        ->where('committee_id', $committeeId)
        ->where('tenant_id', $tenantId)
        ->count();
}
```

---

## API Consumption

### Using the API from PHP

```php
use Illuminate\Support\Facades\Http;

// List members
$response = Http::withHeaders([
    'X-Tenant-Id' => auth()->user()->organisation_id,
])->get("/api/governance/committees/{$committeeId}/members");

$members = $response->json('members');
foreach ($members as $member) {
    echo $member['memberId'];  // UUID
    echo $member['assignedAt'];  // ISO 8601 timestamp
}
```

### Add Member via API

```php
$response = Http::withHeaders([
    'X-Tenant-Id' => auth()->user()->organisation_id,
])->post("/api/governance/committees/{$committeeId}/members", [
    'memberId' => $newMemberId,
]);

if ($response->ok()) {
    // Returns 202 Accepted
    echo "Member assignment queued";
} elseif ($response->status() === 422) {
    // Validation error
    $errors = $response->json('errors');
    dd($errors);
}
```

### Remove Member via API

```php
$response = Http::withHeaders([
    'X-Tenant-Id' => auth()->user()->organisation_id,
])->delete("/api/governance/committees/{$committeeId}/members/{$memberId}");

if ($response->ok()) {
    echo "Member removal queued";
}
```

---

## Vue Component Usage

### Basic Implementation

```vue
<template>
  <div class="committee-management">
    <h1>{{ committee.name }}</h1>
    
    <!-- Vue component auto-detects tenant from page props -->
    <CommitteeMemberManager :committeeId="committee.id" />
  </div>
</template>

<script setup>
import CommitteeMemberManager from '@/Components/CommitteeMemberManager.vue';
import { defineProps } from 'vue';

const props = defineProps({
  committee: {
    type: Object,
    required: true,
  },
});
</script>
```

### With Explicit Tenant

```vue
<template>
  <CommitteeMemberManager 
    :committeeId="committee.id"
    :tenantId="auth.user.current_organisation_id"
  />
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';

const page = usePage();
</script>
```

### In Dashboard with Other Sections

```vue
<template>
  <div class="dashboard grid grid-cols-3 gap-6">
    <!-- Committee stats -->
    <div class="stats-card">
      <h3>Committee Size</h3>
      <p class="text-4xl">{{ memberCount }}</p>
    </div>
    
    <!-- Committee member manager -->
    <div class="col-span-2">
      <CommitteeMemberManager :committeeId="committee.id" />
    </div>
  </div>
</template>

<script setup>
import CommitteeMemberManager from '@/Components/CommitteeMemberManager.vue';
import { ref, onMounted } from 'vue';

const memberCount = ref(0);

onMounted(async () => {
    const response = await fetch(
        `/api/governance/committees/${committee.id}/members`,
        {
            headers: { 'X-Tenant-Id': auth.user.current_organisation_id }
        }
    );
    const data = await response.json();
    memberCount.value = data.members.length;
});
</script>
```

---

## Event Handling

### Listen for Member Assignment

```php
use App\Contexts\Governance\Domain\Committee\Events\MemberAssignedToCommittee;
use Illuminate\Support\Facades\Event;

// Register in EventServiceProvider
protected $listen = [
    MemberAssignedToCommittee::class => [
        SendWelcomeEmailListener::class,
        UpdateCommitteeStatsListener::class,
    ],
];

// Implement listener
class SendWelcomeEmailListener
{
    public function handle(MemberAssignedToCommittee $event): void
    {
        $memberId = $event->memberId->value();
        
        // Maybe send welcome email to new committee member
        // But be careful — don't load Member aggregate!
        // Just use the ID to look up email in Membership context
    }
}
```

### Listen for Member Removal

```php
use App\Contexts\Governance\Domain\Committee\Events\MemberRemovedFromCommittee;

protected $listen = [
    MemberRemovedFromCommittee::class => [
        RevokeAccessListener::class,
    ],
];

class RevokeAccessListener
{
    public function handle(MemberRemovedFromCommittee $event): void
    {
        $memberId = $event->memberId->value();
        
        // Revoke access to committee resources
        AccessControl::revoke(
            $memberId,
            'committee',
            $event->committeeId->value()
        );
    }
}
```

### React to Membership Events

```php
use App\Contexts\Membership\Domain\Member\Events\MembershipTerminated;

protected $listen = [
    MembershipTerminated::class => [
        RemoveFromCommitteesListener::class,
    ],
];

class RemoveFromCommitteesListener
{
    public function handle(MembershipTerminated $event): void
    {
        // When member is terminated in Membership context,
        // remove them from all committees in Governance context
        
        $memberId = $event->memberId;
        
        CommitteeMemberProjection::where('member_id', $memberId)
            ->delete();
        
        // Or dispatch removal commands for each committee
    }
}
```

---

## Error Handling

### Validation Errors

```php
use Illuminate\Validation\ValidationException;

public function store(Request $request): JsonResponse
{
    $request->validate([
        'memberId' => 'required|uuid',
    ]);
    
    try {
        $memberId = MemberId::fromString($request->input('memberId'));
    } catch (\Throwable $e) {
        throw ValidationException::withMessages([
            'memberId' => 'Invalid member ID format'
        ]);
    }
    
    return response()->json(['status' => 'queued'], 202);
}
```

### Domain Errors

```php
use App\Contexts\Governance\Domain\Committee\Committee;

public function assignMember($committeeId, $memberId)
{
    $committee = $this->repository->findById($committeeId);
    
    if (!$committee) {
        throw new CommitteeNotFoundException(
            "Committee {$committeeId} not found"
        );
    }
    
    try {
        $committee->addMember($memberId);
    } catch (InvalidMemberIdException $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
    
    $this->repository->save($committee);
    
    return response()->json(['status' => 'queued'], 202);
}
```

### Missing Tenant Context

```php
public function index(Request $request): JsonResponse
{
    $tenantIdValue = $request->header('X-Tenant-Id');
    
    if (!$tenantIdValue) {
        return response()->json(
            ['error' => 'Missing tenant context'],
            400
        );
    }
    
    try {
        $tenantId = TenantId::fromString($tenantIdValue);
    } catch (\Throwable $e) {
        return response()->json(
            ['error' => 'Invalid tenant ID format'],
            400
        );
    }
    
    // Proceed with tenant context
}
```

---

## Testing Patterns

### Test Assigning Member

```php
use Tests\TestCase;
use Tests\Support\DomainIdFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssignMemberTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_assign_member_to_committee(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();
        $memberId = MemberId::generate();
        
        // Act
        $response = $this->postJson(
            "/api/governance/committees/{$committeeId->value()}/members",
            ['memberId' => $memberId->value()],
            ['X-Tenant-Id' => $tenantId->value()]
        );
        
        // Assert
        $response->assertStatus(202);
        $response->assertJson(['status' => 'queued']);
        
        // Verify projection was created
        $this->assertDatabaseHas('committee_member_projection', [
            'committee_id' => $committeeId->value(),
            'member_id' => $memberId->value(),
            'tenant_id' => $tenantId->value(),
        ]);
    }
}
```

### Test Idempotency

```php
public function test_duplicate_assignment_is_idempotent(): void
{
    $tenantId = DomainIdFactory::tenant();
    $committeeId = CommitteeId::generate();
    $memberId = MemberId::generate();
    
    // Dispatch event twice
    Event::dispatch(
        new MemberAssignedToCommittee($committeeId, $memberId, $tenantId)
    );
    Event::dispatch(
        new MemberAssignedToCommittee($committeeId, $memberId, $tenantId)
    );
    
    // Should only create one record (unique constraint)
    $this->assertDatabaseCount('committee_member_projection', 1);
}
```

### Test Tenant Isolation

```php
public function test_tenant_cannot_see_other_tenant_data(): void
{
    $tenantA = DomainIdFactory::tenant();
    $tenantB = DomainIdFactory::tenant();
    $committeeA = CommitteeId::generate();
    $memberId = MemberId::generate();
    
    // Create data in Tenant A
    Event::dispatch(
        new MemberAssignedToCommittee($committeeA, $memberId, $tenantA)
    );
    
    // Query from Tenant B
    $response = $this->getJson(
        "/api/governance/committees/{$committeeA->value()}/members",
        ['X-Tenant-Id' => $tenantB->value()]
    );
    
    // Should not see data from Tenant A
    $response->assertJson(['members' => []]);
}
```

### Test Event Dispatching

```php
public function test_adding_member_dispatches_event(): void
{
    Event::fake();
    
    $committee = Committee::create(
        CommitteeId::generate(),
        new CommitteeName('Test'),
        new CommitteeType('standing'),
        TenantId::generate()
    );
    
    $memberId = MemberId::generate();
    $committee->addMember($memberId);
    
    $events = $committee->pullEvents();
    
    $this->assertCount(1, $events);
    $this->assertInstanceOf(
        MemberAssignedToCommittee::class,
        $events[0]
    );
}
```

---

## Troubleshooting Patterns

### Problem: API Says "Missing tenant context"

```php
// ✅ Make sure you're sending the header
$response = Http::withHeaders([
    'X-Tenant-Id' => auth()->user()->organisation_id,  // ← Add this!
])->get("/api/governance/committees/{$id}/members");
```

### Problem: Projection Not Updating

```php
// ✅ Make sure you're dispatching events
$committee->addMember($memberId);
foreach ($committee->pullEvents() as $event) {
    Event::dispatch($event);  // ← Required!
}

// ✅ Check event listener is registered
// In EventServiceProvider:
protected $listen = [
    MemberAssignedToCommittee::class => [
        CommitteeMemberProjectionListener::class,
    ],
];

// ✅ Check database migrations ran
php artisan migrate
```

### Problem: Vue Component Shows Empty List

```javascript
// ✅ Check network request
// Open browser DevTools → Network tab
// Look for GET /api/governance/committees/.../members
// Verify response has 'members' array

// ✅ Check tenant context
// Component needs tenant ID in page props or explicit prop
<CommitteeMemberManager 
  :committeeId="committee.id"
  :tenantId="auth.user.current_organisation_id"
/>
```

---

## Performance Tips

### Optimize Committee Member Queries

```php
// ✅ Use indexes
// Projection already has indexed (committee_id, member_id)

// ✅ Limit results if needed
CommitteeMemberProjection::where('committee_id', $committeeId)
    ->limit(100)  // Paginate for large committees
    ->get();

// ✅ Use pluck for IDs only
CommitteeMemberProjection::where('committee_id', $committeeId)
    ->pluck('member_id');  // Faster than full records
```

### Cache Projection Results

```php
use Illuminate\Support\Facades\Cache;

public function getMembersForCommittee(
    CommitteeId $committeeId,
    TenantId $tenantId
): array {
    $cacheKey = "committee:{$committeeId->value()}:members";
    
    return Cache::remember($cacheKey, 3600, function() use ($committeeId, $tenantId) {
        return $this->queryService->getMembersForCommittee(
            $committeeId,
            $tenantId
        );
    });
}
```

### Invalidate Cache on Updates

```php
use App\Contexts\Governance\Domain\Committee\Events\MemberAssignedToCommittee;

class InvalidateMembersCacheListener
{
    public function handle(MemberAssignedToCommittee $event): void
    {
        Cache::forget("committee:{$event->committeeId->value()}:members");
    }
}
```

---

## References

- **Main Developer Guide:** `README.md`
- **Context Relationships:** `CONTEXT_RELATIONSHIPS.md`
- **Source Code:** `app/Contexts/Governance/`
- **Tests:** `tests/Feature/Governance/`

---

**Last Updated:** 2026-05-16  
**Version:** 1.0  
**Status:** Production Ready
