# Phase 2: Temporal Lifecycle - Development Roadmap

## Vision

Extend Phase 1 (roles) with temporal state management. Members move through lifecycle states: `active` → `suspended` → `terminated`. Each transition is a domain event.

## Current State (Phase 1)

- ✅ Role assignment (who, in what capacity)
- ✅ Member uniqueness per committee
- ❌ Lifecycle state (when, how long, why)
- ❌ Role-based permissions

## Phase 2 Goals

1. **Temporal States:** Track member lifecycle
2. **State Transitions:** Suspend and terminate members
3. **Audit Trail:** Know who made changes and when
4. **Query by State:** "Show active members only" queries
5. **Temporal Queries:** "Who was a member on date X?"

---

## Implementation Plan

### Step 1: Database Schema Extension

**Migration:** `2026_05_XX_000000_add_temporal_to_committee_member_projection.php`

```php
Schema::table('committee_member_projection', function (Blueprint $table) {
    // Temporal state
    $table->enum('status', ['active', 'suspended', 'terminated'])
        ->default('active')
        ->after('role');
    
    // Status change tracking
    $table->timestamp('status_changed_at')
        ->nullable()
        ->after('status');
    
    // Suspension details
    $table->text('suspension_reason')
        ->nullable()
        ->after('status_changed_at');
    
    $table->timestamp('suspension_until')
        ->nullable()
        ->after('suspension_reason');
    
    // Termination details
    $table->timestamp('terminated_at')
        ->nullable()
        ->after('suspension_until');
    
    $table->text('termination_reason')
        ->nullable()
        ->after('terminated_at');
    
    // Audit trail
    $table->uuid('suspended_by_user_id')
        ->nullable()
        ->after('termination_reason');
    
    $table->uuid('terminated_by_user_id')
        ->nullable()
        ->after('suspended_by_user_id');
    
    // Index for common queries
    $table->index(['committee_id', 'status']);
    $table->index(['status_changed_at']);
});
```

### Step 2: Domain Events

**File:** `app/Contexts/Governance/Domain/Committee/Events/MemberSuspendedFromCommittee.php`

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Committee\Events;

use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

final class MemberSuspendedFromCommittee
{
    public function __construct(
        private CommitteeId $committeeId,
        private MemberId $memberId,
        private TenantId $tenantId,
        private string $reason,
        private ?DateTimeImmutable $suspensionUntil = null,
        private DateTimeImmutable $occurredAt,
    ) {}

    public function committeeId(): CommitteeId { return $this->committeeId; }
    public function memberId(): MemberId { return $this->memberId; }
    public function tenantId(): TenantId { return $this->tenantId; }
    public function reason(): string { return $this->reason; }
    public function suspensionUntil(): ?DateTimeImmutable { return $this->suspensionUntil; }
    public function occurredAt(): DateTimeImmutable { return $this->occurredAt; }
}
```

**File:** `app/Contexts/Governance/Domain/Committee/Events/MemberTerminatedFromCommittee.php`

```php
<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Committee\Events;

use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

final class MemberTerminatedFromCommittee
{
    public function __construct(
        private CommitteeId $committeeId,
        private MemberId $memberId,
        private TenantId $tenantId,
        private string $reason,
        private DateTimeImmutable $occurredAt,
    ) {}

    public function committeeId(): CommitteeId { return $this->committeeId; }
    public function memberId(): MemberId { return $this->memberId; }
    public function tenantId(): TenantId { return $this->tenantId; }
    public function reason(): string { return $this->reason; }
    public function occurredAt(): DateTimeImmutable { return $this->occurredAt; }
}
```

### Step 3: Aggregate Methods

Update `Committee` aggregate in `app/Contexts/Governance/Domain/Committee/Committee.php`:

```php
<?php

// ... existing code ...

private array $members = [];
private array $suspensions = [];  // member_id => suspension_date
private array $terminations = []; // member_id => termination_date

public function suspendMember(
    MemberId $memberId,
    string $reason,
    ?DateTimeImmutable $suspensionUntil = null
): void
{
    if (!$this->isMemberAssigned($memberId)) {
        throw new \DomainException('Member is not assigned to this committee');
    }

    if (isset($this->suspensions[$memberId->value()])) {
        throw new \DomainException('Member is already suspended');
    }

    $this->suspensions[$memberId->value()] = $suspensionUntil;

    $this->recordEvent(new MemberSuspendedFromCommittee(
        $this->id,
        $memberId,
        $this->tenantId,
        $reason,
        $suspensionUntil,
        new DateTimeImmutable()
    ));
}

public function terminateMember(
    MemberId $memberId,
    string $reason
): void
{
    if (!$this->isMemberAssigned($memberId)) {
        throw new \DomainException('Member is not assigned to this committee');
    }

    $this->terminations[$memberId->value()] = new DateTimeImmutable();
    unset($this->members[$memberId->value()]);

    $this->recordEvent(new MemberTerminatedFromCommittee(
        $this->id,
        $memberId,
        $this->tenantId,
        $reason,
        new DateTimeImmutable()
    ));
}

public function getMemberStatus(MemberId $memberId): ?string
{
    if (isset($this->terminations[$memberId->value()])) {
        return 'terminated';
    }

    if (isset($this->suspensions[$memberId->value()])) {
        return 'suspended';
    }

    if ($this->isMemberAssigned($memberId)) {
        return 'active';
    }

    return null;
}

public function isActiveMember(MemberId $memberId): bool
{
    return $this->isMemberAssigned($memberId) &&
           !isset($this->suspensions[$memberId->value()]) &&
           !isset($this->terminations[$memberId->value()]);
}
```

### Step 4: Event Listeners

Update `CommitteeMemberProjectionListener`:

```php
<?php

// ... existing code ...

public function onMemberSuspended(MemberSuspendedFromCommittee $event): void
{
    CommitteeMemberProjection::where('committee_id', $event->committeeId->value())
        ->where('member_id', $event->memberId->value())
        ->update([
            'status' => 'suspended',
            'status_changed_at' => now(),
            'suspension_reason' => $event->reason(),
            'suspension_until' => $event->suspensionUntil()?->format('Y-m-d H:i:s'),
        ]);
}

public function onMemberTerminated(MemberTerminatedFromCommittee $event): void
{
    CommitteeMemberProjection::where('committee_id', $event->committeeId->value())
        ->where('member_id', $event->memberId->value())
        ->update([
            'status' => 'terminated',
            'status_changed_at' => now(),
            'terminated_at' => now(),
            'termination_reason' => $event->reason(),
        ]);
}
```

### Step 5: API Controller Methods

Add to `CommitteeMemberController`:

```php
<?php

public function suspend(string $committeeId, string $memberId, Request $request)
{
    $request->validate([
        'reason' => 'required|string',
        'suspensionUntil' => 'nullable|date_format:Y-m-d',
    ]);

    // ... load aggregate, call suspendMember(), save ...

    return response()->json(['status' => 'suspended'], 200);
}

public function terminate(string $committeeId, string $memberId, Request $request)
{
    $request->validate([
        'reason' => 'required|string',
    ]);

    // ... load aggregate, call terminateMember(), save ...

    return response()->json(['status' => 'terminated'], 200);
}

public function reactivate(string $committeeId, string $memberId, Request $request)
{
    $request->validate([
        'reason' => 'required|string',
    ]);

    // ... load aggregate, call reactivateMember(), save ...

    return response()->json(['status' => 'reactivated'], 200);
}
```

### Step 6: Query Service Extension

Add temporal queries to `CommitteeMemberQueryService`:

```php
<?php

public function getActiveMembersForCommittee(
    CommitteeId $committeeId,
    TenantId $tenantId
): array
{
    return CommitteeMemberProjection::query()
        ->where('committee_id', $committeeId->value())
        ->where('tenant_id', $tenantId->value())
        ->where('status', 'active')
        ->orderBy('assigned_at', 'desc')
        ->get()
        ->toArray();
}

public function getMembersByStatus(
    CommitteeId $committeeId,
    TenantId $tenantId,
    string $status
): array
{
    return CommitteeMemberProjection::query()
        ->where('committee_id', $committeeId->value())
        ->where('tenant_id', $tenantId->value())
        ->where('status', $status)
        ->orderBy('status_changed_at', 'desc')
        ->get()
        ->toArray();
}

public function getActiveMembersAsOfDate(
    CommitteeId $committeeId,
    TenantId $tenantId,
    DateTimeInterface $date
): array
{
    return CommitteeMemberProjection::query()
        ->where('committee_id', $committeeId->value())
        ->where('tenant_id', $tenantId->value())
        ->where('assigned_at', '<=', $date)
        ->where(function ($q) use ($date) {
            $q->where('terminated_at', '>', $date)
              ->orWhereNull('terminated_at');
        })
        ->get()
        ->toArray();
}
```

### Step 7: Vue Component Updates

Update `CommitteeMemberManager.vue`:

```vue
<template>
  <!-- Status badge in table -->
  <td class="px-6 py-4">
    <span :class="statusBadgeClass(member.status)">
      {{ member.status }}
    </span>
  </td>

  <!-- Suspension reason (if applicable) -->
  <td class="px-6 py-4" v-if="member.status === 'suspended'">
    <div class="text-xs text-neutral-600">
      {{ member.suspension_reason }}
      <span v-if="member.suspension_until">
        until {{ formatDate(member.suspension_until) }}
      </span>
    </div>
  </td>

  <!-- Action buttons -->
  <td class="px-6 py-4">
    <template v-if="member.status === 'active'">
      <button @click="showSuspendDialog(member)">Suspend</button>
    </template>
    <template v-else-if="member.status === 'suspended'">
      <button @click="reactivateMember(member)">Reactivate</button>
      <button @click="showTerminateDialog(member)">Terminate</button>
    </template>
    <template v-else-if="member.status === 'terminated'">
      <span class="text-xs text-neutral-500">Terminated</span>
    </template>
  </td>
</template>

<script setup>
const statusBadgeClass = (status) => ({
  'active': 'bg-green-100 text-green-700',
  'suspended': 'bg-yellow-100 text-yellow-700',
  'terminated': 'bg-red-100 text-red-700',
}[status] || 'bg-neutral-100 text-neutral-700');
</script>
```

### Step 8: Tests

Create `tests/Unit/Governance/Domain/CommitteeMemberLifecycleTest.php`:

```php
<?php

final class CommitteeMemberLifecycleTest extends TestCase
{
    public function test_member_can_be_suspended(): void
    {
        $committee = Committee::create($committeeId, $tenantId);
        $committee->addMember($memberId, CommitteeRole::MEMBER);
        
        $committee->suspendMember($memberId, 'Inactive for 3 months');
        
        $events = $committee->pullEvents();
        $this->assertCount(2, $events); // addMember + suspendMember
        $this->assertInstanceOf(MemberSuspendedFromCommittee::class, $events[1]);
    }

    public function test_member_cannot_be_suspended_twice(): void
    {
        // ... setup ...
        
        $committee->suspendMember($memberId, 'First suspension');
        
        $this->expectException(DomainException::class);
        $committee->suspendMember($memberId, 'Second suspension');
    }

    public function test_suspended_member_can_be_reactivated(): void
    {
        // ... setup and suspend ...
        
        $committee->reactivateMember($memberId, 'Request to return');
        
        $this->assertTrue($committee->isActiveMember($memberId));
    }

    public function test_member_status_progression(): void
    {
        // ... test: active → suspended → terminated ...
    }
}
```

---

## Rollout Strategy

1. **Create migrations** (Step 1)
2. **Write domain tests** (Step 8)
3. **Implement domain layer** (Steps 2-3)
4. **Implement listeners** (Step 4)
5. **Add API endpoints** (Step 5)
6. **Extend query service** (Step 6)
7. **Update Vue component** (Step 7)
8. **Run full test suite**
9. **Deploy to staging**
10. **Deploy to production**

---

## Breaking Changes

None—Phase 2 is backward compatible:
- ✅ Existing members default to `status='active'`
- ✅ Existing API calls still work (no required new parameters)
- ✅ Existing queries still work (can filter by status='active')

---

## Estimated Effort

- Design & planning: 2 hours
- Implementation: 8-10 hours
- Testing: 4-5 hours
- Documentation: 2 hours
- **Total: ~18 hours**

---

## Questions for Stakeholder Review

1. Should suspension support automatic expiration (suspension_until)?
2. Should we track who suspended/terminated (audit user ID)?
3. Should there be a "reason" field for all status changes?
4. Should suspension prevent voting, or just mark inactive?
5. Should terminated members be hard-deleted or soft-deleted?

---

## Related Documentation

- [Phase 1: Role-Based Assignment](README.md)
- [Quick Reference](QUICK_REFERENCE.md)

