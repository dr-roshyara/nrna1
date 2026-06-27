# Governance Context Developer Guide

## Overview

The **Governance Context** manages organizational committee structures, member assignments, and role-based governance operations. It implements a **CQRS pattern** (Command Query Responsibility Segregation) with **event-driven architecture**.

### Core Principle
> Committees are not just lists of members—they are *governance aggregates* that enforce domain rules about who can participate and in what capacity.

---

## Architecture Layers

```
┌─────────────────────────────────────────────────────────┐
│              HTTP / Vue Frontend                         │
│         CommitteeMemberManager.vue                       │
└──────────────────┬──────────────────────────────────────┘
                   │ (POST /api/governance/committees/{id}/members)
┌──────────────────▼──────────────────────────────────────┐
│         API Layer (Controllers)                          │
│     CommitteeMemberController                           │
│  - Validates input (CommitteeRole enum)                 │
│  - Loads Committee aggregate via repository             │
│  - Calls domain method: addMember(memberId, role)       │
└──────────────────┬──────────────────────────────────────┘
                   │ (Aggregate enforces rules)
┌──────────────────▼──────────────────────────────────────┐
│       Domain Layer (Business Logic)                      │
│        Committee Aggregate                              │
│  - addMember(MemberId, CommitteeRole): void             │
│  - Prevents duplicate member assignment                 │
│  - Generates MemberAssignedToCommittee event            │
└──────────────────┬──────────────────────────────────────┘
                   │ (Events dispatched)
┌──────────────────▼──────────────────────────────────────┐
│     Infrastructure Layer (Event Listeners)              │
│   CommitteeMemberProjectionListener                     │
│  - Listens for MemberAssignedToCommittee event          │
│  - Fetches member data from users table                 │
│  - Writes denormalized data to projection table         │
└──────────────────┬──────────────────────────────────────┘
                   │ (Projection updated)
┌──────────────────▼──────────────────────────────────────┐
│      Read Model (Query-Optimized)                       │
│    committee_member_projection table                    │
│  - Denormalized member data with role                   │
│  - Used for fast reads (no joins)                       │
│  - Source of truth for read operations                  │
└─────────────────────────────────────────────────────────┘
```

---

## Phase 1: Role-Based Member Assignment

### What's Implemented

✅ **Domain Layer**
- `CommitteeRole` enum with 4 cases: CHAIR, DEPUTY, MEMBER, OBSERVER
- `Committee` aggregate enforces member uniqueness per committee
- `MemberAssignedToCommittee` domain event carries role information

✅ **Projection Layer**
- `committee_member_projection` table stores denormalized member data
- Role column added (string, default 'member')
- `CommitteeMemberProjectionListener` synchronizes from domain events

✅ **API Contract**
- `CommitteeMemberController.store()` accepts `role` parameter
- Validates role is valid CommitteeRole enum value
- Returns 201 with role in response

✅ **Frontend**
- `CommitteeMemberManager.vue` includes role dropdown selector
- Displays role badges in members table (color-coded)
- Leadership roles (Chair, Deputy) highlighted differently

### Data Flow: Adding a Member with Role

```
Frontend (Vue)
  ↓ (POST /api/governance/committees/{id}/members)
    { memberId: "uuid", role: "chair" }
  ↓
API Controller (CommitteeMemberController.store)
  ├─ Validates CommitteeRole.from('chair') ✓
  ├─ Loads Committee aggregate
  └─ Calls: committee.addMember(memberId, CommitteeRole::CHAIR)
     ↓
Domain Aggregate (Committee)
  ├─ Checks: isMemberAssigned(memberId)? → throw DomainException if yes
  ├─ Updates internal state: $members[memberId] = CommitteeRole::CHAIR
  └─ Records event: new MemberAssignedToCommittee(...)
     ↓
Repository (EloquentCommitteeRepository.save)
  ├─ Pulls events from aggregate
  └─ Dispatches via Laravel Event system
     ↓
Event Listener (CommitteeMemberProjectionListener.onMemberAssigned)
  ├─ Fetches member from users table
  ├─ Denormalizes: name, email
  └─ Writes to committee_member_projection
     ├─ committee_id (ULID)
     ├─ member_id (UUID)
     ├─ member_name (string)
     ├─ member_email (string)
     ├─ role (string: 'chair', 'deputy', 'member', 'observer')
     └─ assigned_at (timestamp)
     ↓
Query Service (CommitteeMemberQueryService.getMembersForCommittee)
  ├─ Queries projection table directly (no joins)
  ├─ Returns denormalized data with role
  └─ Frontend displays role badge
```

---

## Key Files

### Domain Layer

**File:** `app/Contexts/Governance/Domain/Committee/Committee.php`
- **Purpose:** Committee aggregate root
- **Key Method:** `addMember(MemberId $memberId, CommitteeRole $role): void`
- **Invariants:** One role per member, prevents duplicates

**File:** `app/Contexts/Governance/Domain/Committee/Enums/CommitteeRole.php`
- **Purpose:** Type-safe role enum
- **Cases:** CHAIR, DEPUTY, MEMBER, OBSERVER
- **Usage:** `CommitteeRole::CHAIR->value` returns `'chair'`

**File:** `app/Contexts/Governance/Domain/Committee/Events/MemberAssignedToCommittee.php`
- **Purpose:** Domain event fired when member assigned
- **Data:** committeeId, memberId, tenantId, role, occurredAt
- **Pattern:** Event carries all data needed for projection

**File:** `app/Contexts/Governance/Domain/Committee/CommitteeRepositoryInterface.php`
- **Purpose:** Domain port (no Laravel dependencies)
- **Methods:**
  - `findById(CommitteeId, TenantId): ?Committee`
  - `save(Committee, TenantId): void`

### Infrastructure Layer

**File:** `app/Contexts/Governance/Infrastructure/Repositories/EloquentCommitteeRepository.php`
- **Purpose:** Aggregate persistence adapter
- **Responsibility:** Load aggregates, save events
- **Key:** `save()` pulls events and dispatches them

**File:** `app/Contexts/Governance/Infrastructure/Projection/CommitteeMemberProjectionListener.php`
- **Purpose:** Synchronizes domain events → read model
- **Method:** `onMemberAssigned(MemberAssignedToCommittee $event): void`
- **Logic:** Fetches member denormalization, writes projection

**File:** `app/Models/CommitteeMemberProjection.php`
- **Purpose:** Read model (Eloquent)
- **Table:** `committee_member_projection`
- **Columns:** id, tenant_id, committee_id, member_id, member_name, member_email, role, assigned_at
- **Casts:** role as CommitteeRole enum

### Application Layer

**File:** `app/Contexts/Governance/Application/Queries/CommitteeMemberQueryService.php`
- **Purpose:** Query-side service
- **Method:** `getMembersForCommittee(CommitteeId, TenantId): array`
- **Returns:** Denormalized member list with roles

### API Layer

**File:** `app/Http/Controllers/Api/Governance/CommitteeMemberController.php`
- **Purpose:** HTTP boundary
- **Methods:**
  - `index()` - List members (read-only)
  - `store()` - Add member with role
  - `destroy()` - Remove member

**File:** `app/Contexts/Governance/Infrastructure/Providers/GovernanceServiceProvider.php`
- **Purpose:** Service container bindings
- **Binding:** `CommitteeRepositoryInterface → EloquentCommitteeRepository`

### Frontend

**File:** `resources/js/Components/CommitteeMemberManager.vue`
- **Purpose:** Member management UI
- **Features:**
  - Member search by name/email
  - Role dropdown selector (CHAIR, DEPUTY, MEMBER, OBSERVER)
  - Members table with role column
  - Color-coded role badges

---

## Testing

### Unit Tests (Domain Layer)

**File:** `tests/Unit/Governance/Domain/CommitteeRoleAssignmentTest.php`

Tests verify domain contract:
```php
// All 4 tests passing:
✓ test_it_assigns_member_with_role()
✓ test_duplicate_member_assignment_is_rejected()
✓ test_member_assignment_preserves_role_type()
✓ test_all_role_types_are_assignable()
```

Run tests:
```bash
php artisan test tests/Unit/Governance/Domain/CommitteeRoleAssignmentTest.php
```

---

## Usage Examples

### 1. Assign Member with Role (API)

```bash
curl -X POST http://localhost:8000/api/governance/committees/{committeeId}/members \
  -H "X-Tenant-Id: a1ca231c-59aa-4950-8b23-75b16d5c176a" \
  -H "Content-Type: application/json" \
  -d '{
    "memberId": "550e8400-e29b-41d4-a716-446655440000",
    "role": "chair"
  }'
```

**Response (201 Created):**
```json
{
  "status": "created",
  "committeeId": "01KRQ774D3PB5JW6A5AWZ87T50",
  "memberId": "550e8400-e29b-41d4-a716-446655440000",
  "memberName": "John Smith",
  "memberEmail": "john@example.com",
  "role": "chair"
}
```

### 2. Get Committee Members with Roles (API)

```bash
curl http://localhost:8000/api/governance/committees/{committeeId}/members \
  -H "X-Tenant-Id: a1ca231c-59aa-4950-8b23-75b16d5c176a"
```

**Response:**
```json
{
  "committeeId": "01KRQ774D3PB5JW6A5AWZ87T50",
  "members": [
    {
      "memberId": "550e8400-e29b-41d4-a716-446655440000",
      "memberName": "John Smith",
      "memberEmail": "john@example.com",
      "role": "chair",
      "assignedAt": "2026-05-16T10:30:00Z"
    },
    {
      "memberId": "660e8400-e29b-41d4-a716-446655440001",
      "memberName": "Jane Doe",
      "memberEmail": "jane@example.com",
      "role": "member",
      "assignedAt": "2026-05-16T10:25:00Z"
    }
  ]
}
```

### 3. In Frontend (Vue)

The `CommitteeMemberManager.vue` component automatically:
1. Displays role dropdown when member is selected
2. Sends role in POST request
3. Shows role badges in members table (color-coded)

---

## Role Semantics (Current & Planned)

### Current Status (Phase 1)
- ✅ Role assignment validated at domain level
- ✅ Role persisted and returned in API
- ✅ Role displayed in UI with visual indicators
- ❌ Role-based permissions NOT enforced yet

### Planned Enforcement (Phase 2+)

| Role | Intended Semantics | Planned Enforcement |
|------|-------------------|-------------------|
| **CHAIR** | Leads committee, final authority | Vote eligibility, approval authority |
| **DEPUTY** | Assists chair, takes over if needed | Can act for chair, voting eligibility |
| **MEMBER** | Regular participant | Standard voting rights |
| **OBSERVER** | Attends but limited participation | No voting, read-only access |

---

## Extending for Phase 2: Temporal Lifecycle

To add temporal state (active, suspended, terminated):

1. **Add columns to projection:**
   ```php
   // Migration: add_status_to_committee_member_projection.php
   $table->string('status')->default('active'); // active, suspended, terminated
   $table->timestamp('status_changed_at')->nullable();
   $table->timestamp('terminated_at')->nullable();
   ```

2. **Add domain events:**
   ```php
   // Domain events
   class MemberSuspendedFromCommittee extends DomainEvent { }
   class MemberTerminatedFromCommittee extends DomainEvent { }
   ```

3. **Add aggregate methods:**
   ```php
   // Committee aggregate
   public function suspendMember(MemberId $memberId): void { }
   public function terminateMember(MemberId $memberId): void { }
   ```

4. **Add listeners:**
   ```php
   // CommitteeMemberProjectionListener
   public function onMemberSuspended(MemberSuspendedFromCommittee $event): void { }
   public function onMemberTerminated(MemberTerminatedFromCommittee $event): void { }
   ```

---

## Key Invariants (Do Not Violate)

1. **One role per member per committee**
   - Enforced in Committee.addMember() domain method
   - Database constraint: unique(committee_id, member_id)

2. **Projection written only by listeners**
   - Never write directly to committee_member_projection in application code
   - All writes go through domain events

3. **Role must be valid CommitteeRole enum**
   - Validated in controller before reaching domain
   - Enforces CHAIR | DEPUTY | MEMBER | OBSERVER only

4. **Tenant isolation**
   - All queries scoped by tenant_id
   - No cross-tenant data visibility

---

## Common Tasks

### Add a Member to a Committee (Programmatically)

```php
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Domain\Committee\Enums\CommitteeRole;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

$committeeId = CommitteeId::fromString('01KRQ774D3PB5JW6A5AWZ87T50');
$tenantId = TenantId::fromString('a1ca231c-59aa-4950-8b23-75b16d5c176a');
$memberId = MemberId::fromString('550e8400-e29b-41d4-a716-446655440000');

// Load aggregate
$committee = $this->repository->findById($committeeId, $tenantId);

// Apply domain operation
$committee->addMember($memberId, CommitteeRole::CHAIR);

// Save (triggers event dispatch)
$this->repository->save($committee, $tenantId);

// Listener automatically writes projection
```

### Query Members by Role

```php
use App\Models\CommitteeMemberProjection;

// Get all chairs
$chairs = CommitteeMemberProjection::where('committee_id', $committeeId)
    ->where('role', 'chair')
    ->get();

// Count members by role
$roleCounts = CommitteeMemberProjection::where('committee_id', $committeeId)
    ->groupBy('role')
    ->selectRaw('role, count(*) as count')
    ->get();
```

### Validate Role in Requests

```php
use App\Contexts\Governance\Domain\Committee\Enums\CommitteeRole;

try {
    $role = CommitteeRole::from($request->input('role'));
    // role is valid CommitteeRole
} catch (\ValueError $e) {
    // Invalid role - not in enum
    return response()->json([
        'error' => 'Invalid role',
        'valid_roles' => array_map(fn($c) => $c->value, CommitteeRole::cases())
    ], 400);
}
```

---

## Troubleshooting

### Issue: Role shows as 'N/A' in frontend
**Cause:** Projection not updated by listener
**Solution:**
1. Check event listener is registered in `EventServiceProvider.php`
2. Verify `CommitteeMemberProjectionListener::onMemberAssigned()` is being called
3. Check database: is role column present in `committee_member_projection`?

### Issue: "Member already assigned to this committee" error
**Cause:** Duplicate member assignment attempt
**Expected Behavior:** This is correct—domain enforces uniqueness
**Resolution:** Either select different member, or remove first before reassigning

### Issue: Invalid role error in API
**Cause:** Role value not in CommitteeRole enum
**Solution:** Use only: 'chair', 'deputy', 'member', 'observer'

---

## References

- **CQRS Pattern:** Command Query Responsibility Segregation
- **Domain-Driven Design:** Aggregate roots, value objects, domain events
- **Event Sourcing:** Aggregates record domain events for audit/replay
- **Denormalization:** Projection table optimizes reads (no joins needed)
- **Idempotency:** Event listeners designed to handle duplicate event delivery

