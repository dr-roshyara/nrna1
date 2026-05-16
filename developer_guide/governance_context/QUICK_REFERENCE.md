# Governance Context - Quick Reference

## File Locations

| Component | File Path |
|-----------|-----------|
| **Committee Aggregate** | `app/Contexts/Governance/Domain/Committee/Committee.php` |
| **CommitteeRole Enum** | `app/Contexts/Governance/Domain/Committee/Enums/CommitteeRole.php` |
| **Domain Event** | `app/Contexts/Governance/Domain/Committee/Events/MemberAssignedToCommittee.php` |
| **Repository Interface** | `app/Contexts/Governance/Domain/Committee/CommitteeRepositoryInterface.php` |
| **Repository Implementation** | `app/Contexts/Governance/Infrastructure/Repositories/EloquentCommitteeRepository.php` |
| **Event Listener** | `app/Contexts/Governance/Infrastructure/Projection/CommitteeMemberProjectionListener.php` |
| **Read Model** | `app/Models/CommitteeMemberProjection.php` |
| **Query Service** | `app/Contexts/Governance/Application/Queries/CommitteeMemberQueryService.php` |
| **API Controller** | `app/Http/Controllers/Api/Governance/CommitteeMemberController.php` |
| **Vue Component** | `resources/js/Components/CommitteeMemberManager.vue` |
| **Service Provider** | `app/Contexts/Governance/Infrastructure/Providers/GovernanceServiceProvider.php` |
| **Test** | `tests/Unit/Governance/Domain/CommitteeRoleAssignmentTest.php` |

## Database Tables

### committee_member_projection
```sql
CREATE TABLE committee_member_projection (
  id VARCHAR(36) PRIMARY KEY,           -- UUID
  tenant_id VARCHAR(36) NOT NULL,       -- Organisation UUID
  committee_id VARCHAR(26) NOT NULL,    -- ULID
  member_id VARCHAR(36) NOT NULL,       -- User UUID
  member_name VARCHAR(255),             -- Denormalized from users
  member_email VARCHAR(255),            -- Denormalized from users
  role VARCHAR(50) DEFAULT 'member',    -- ENUM: chair, deputy, member, observer
  assigned_at TIMESTAMP,                -- When member was assigned
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  UNIQUE(committee_id, member_id)
);
```

## API Endpoints

### Add Member with Role
```
POST /api/governance/committees/{committeeId}/members

Headers:
  X-Tenant-Id: {tenantId}
  Content-Type: application/json

Body:
{
  "memberId": "550e8400-e29b-41d4-a716-446655440000",
  "role": "chair"  # Optional, defaults to "member"
}

Response (201):
{
  "status": "created",
  "committeeId": "01KRQ774D3PB5JW6A5AWZ87T50",
  "memberId": "550e8400-e29b-41d4-a716-446655440000",
  "memberName": "John Smith",
  "memberEmail": "john@example.com",
  "role": "chair"
}

Error (400):
{
  "error": "Invalid role",
  "valid_roles": ["chair", "deputy", "member", "observer"]
}
```

### List Committee Members
```
GET /api/governance/committees/{committeeId}/members

Headers:
  X-Tenant-Id: {tenantId}

Response (200):
{
  "committeeId": "01KRQ774D3PB5JW6A5AWZ87T50",
  "members": [
    {
      "memberId": "550e8400-e29b-41d4-a716-446655440000",
      "memberName": "John Smith",
      "memberEmail": "john@example.com",
      "role": "chair",
      "assignedAt": "2026-05-16T10:30:00Z"
    }
  ]
}
```

### Remove Member
```
DELETE /api/governance/committees/{committeeId}/members/{memberId}

Headers:
  X-Tenant-Id: {tenantId}

Response (200):
{
  "status": "deleted"
}
```

## CommitteeRole Enum Values

```php
CommitteeRole::CHAIR     // 'chair' - Leadership position
CommitteeRole::DEPUTY    // 'deputy' - Deputy/Assistant position
CommitteeRole::MEMBER    // 'member' - Regular member (default)
CommitteeRole::OBSERVER  // 'observer' - Observer position
```

## Event Registration

In `app/Providers/EventServiceProvider.php`:

```php
Event::listen(MemberAssignedToCommittee::class, 
    [CommitteeMemberProjectionListener::class, 'onMemberAssigned']);

Event::listen(MemberRemovedFromCommittee::class, 
    [CommitteeMemberProjectionListener::class, 'onMemberRemoved']);
```

## Service Container Binding

In `app/Contexts/Governance/Infrastructure/Providers/GovernanceServiceProvider.php`:

```php
$this->app->bind(
    CommitteeRepositoryInterface::class, 
    EloquentCommitteeRepository::class
);
```

## Unit Tests

Run all governance tests:
```bash
php artisan test tests/Unit/Governance/Domain/CommitteeRoleAssignmentTest.php
```

Expected output:
```
✓ test_it_assigns_member_with_role
✓ test_duplicate_member_assignment_is_rejected
✓ test_member_assignment_preserves_role_type
✓ test_all_role_types_are_assignable

4 tests passed
```

## Frontend Component Props

**CommitteeMemberManager.vue**

```vue
<CommitteeMemberManager
  :committeeId="'01KRQ774D3PB5JW6A5AWZ87T50'"
  :organisationId="'a1ca231c-59aa-4950-8b23-75b16d5c176a'"
/>
```

Props:
- `committeeId` (required): ULID of committee
- `organisationId` (optional): UUID of organisation (used for member search route)
- `tenantId` (optional): UUID for X-Tenant-Id header

## Data Flow Summary

```
User selects member + role in frontend
       ↓
    POST /api/governance/committees/{id}/members
       ↓
CommitteeMemberController.store()
    - Validates role enum
    - Loads Committee aggregate
    - Calls: committee.addMember(memberId, role)
       ↓
Committee.addMember()
    - Checks: !isMemberAssigned(memberId)
    - Records event: MemberAssignedToCommittee
       ↓
Repository.save()
    - Pulls events
    - Dispatches via Laravel Event system
       ↓
CommitteeMemberProjectionListener.onMemberAssigned()
    - Fetches member from users table
    - Writes to committee_member_projection
       ↓
Query returns denormalized data to frontend
       ↓
Vue displays role badge in members table
```

## Common Queries

### Get all chairs in a committee
```php
CommitteeMemberProjection::where('committee_id', $committeeId)
    ->where('role', 'chair')
    ->get();
```

### Count members by role
```php
CommitteeMemberProjection::where('committee_id', $committeeId)
    ->groupBy('role')
    ->selectRaw('role, count(*) as count')
    ->pluck('count', 'role');
```

### Get members assigned in last 7 days
```php
CommitteeMemberProjection::where('committee_id', $committeeId)
    ->where('assigned_at', '>=', now()->subDays(7))
    ->orderBy('assigned_at', 'desc')
    ->get();
```

## Migration Checklist

When deploying Phase 1:

- [ ] Run migration: `2026_05_16_000000_create_committee_member_projection_table.php`
- [ ] Run migration: `2026_05_16_000001_change_committee_id_to_string_in_projection.php`
- [ ] Run migration: `2026_05_16_000003_add_role_to_committee_member_projection.php`
- [ ] Verify EventServiceProvider has listener registrations
- [ ] Verify GovernanceServiceProvider has repository binding
- [ ] Test: Add member with role via API
- [ ] Test: Verify role appears in members list
- [ ] Test: Verify role appears in Vue component

## Debugging

### Enable debug logging for domain events
```php
// In your controller or handler
Log::info('Domain Event Dispatched', [
    'event' => class_basename($event),
    'committeeId' => $event->committeeId->value(),
    'memberId' => $event->memberId->value(),
    'role' => $event->role()->value,
]);
```

### Check if listener was called
```php
// Add to CommitteeMemberProjectionListener.onMemberAssigned()
Log::info('Projection Listener Called', [
    'memberId' => $event->memberId->value(),
    'role' => $event->role()->value,
]);
```

### Query projection table directly
```bash
psql production_db
SELECT * FROM committee_member_projection WHERE committee_id = '01KRQ774D3PB5JW6A5AWZ87T50';
```

