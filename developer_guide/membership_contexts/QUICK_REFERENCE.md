# Membership Application System — Quick Reference

## File Locations

```
Domain Layer:
  app/Contexts/Membership/Domain/Membership/
    ├── MembershipApplication.php                 (aggregate)
    ├── CommitteeAssociation.php                  (result model)
    ├── ValueObjects/
    │   ├── MembershipApplicationId.php
    │   ├── ApplicationStatus.php                 (state machine)
    │   ├── ApplicationReason.php
    │   └── MembershipStatus.php
    └── Events/
        ├── CommitteeMembershipApplicationSubmitted.php
        ├── CommitteeMembershipApplicationApproved.php
        └── CommitteeMembershipApplicationRejected.php

Application Layer:
  app/Contexts/Membership/Application/Membership/
    ├── Ports/
    │   ├── MembershipApplicationRepositoryPort.php
    │   └── CommitteeAssociationRepositoryPort.php
    ├── ApplyForCommitteeMembership/
    │   ├── ApplyForCommitteeMembershipCommand.php
    │   └── ApplyForCommitteeMembershipHandler.php
    └── ReviewMembershipApplication/
        ├── ReviewMembershipApplicationCommand.php
        └── ReviewMembershipApplicationHandler.php

Test Doubles:
  tests/Doubles/
    ├── InMemoryMembershipApplicationRepository.php
    ├── InMemoryCommitteeAssociationRepository.php
    ├── FakeEventBus.php
    └── FakeCommitteeEligibilityPolicy.php

Tests:
  tests/Unit/Contexts/Membership/
    ├── Domain/Membership/MembershipApplicationTest.php              (11 tests)
    └── Application/Membership/
        ├── ApplyForCommitteeMembershipHandlerTest.php              (5 tests)
        └── ReviewMembershipApplicationHandlerTest.php              (4 tests)
```

---

## Core Classes at a Glance

### MembershipApplication

```php
// Create
$app = MembershipApplication::submit(
    id: MembershipApplicationId::generate(),
    tenantId: TenantId::fromString($orgId),
    memberId: MemberId::fromString($memberId),
    committeeId: CommitteeId::fromString($committeeId),
    reason: ApplicationReason::RESIDENCE,
    exceptionJustification: null,
    isEligible: true,
);

// Approve → returns CommitteeAssociation
$association = $app->approve(MemberId::fromString($reviewerId));

// Reject
$app->reject(MemberId::fromString($reviewerId));

// Query
$app->status();                    // ApplicationStatus enum
$app->memberId();                  // MemberId
$app->committeeId();               // CommitteeId
$app->reason();                    // ApplicationReason
$app->releaseEvents();             // Domain events
```

### ApplicationStatus (State Machine)

```
SUBMITTED ──→ UNDER_REVIEW ──→ APPROVED ✓
         ├──────────────────→ REJECTED ✗
         └─────────────────→ REJECTED ✗
```

Methods:
```php
$status->equals(ApplicationStatus::APPROVED)
$status->canTransitionTo(ApplicationStatus::REJECTED)  // bool
$status->isActive()  // true if SUBMITTED or UNDER_REVIEW
```

### ApplicationReason

```php
ApplicationReason::RESIDENCE    // Check geography
ApplicationReason::EXCEPTION    // Require justification
ApplicationReason::MANUAL       // Skip all checks
```

### CommitteeAssociation

```php
// Created by: MembershipApplication::approve()
// Persisted to: CommitteeAssociationRepository
// Queried by: Elections context for voting rights

$assoc->memberId               // MemberId
$assoc->committeeId            // CommitteeId
$assoc->associationType        // ApplicationReason
$assoc->associatedAt           // DateTimeImmutable
$assoc->status                 // MembershipStatus (ACTIVE)
```

---

## Handler Signatures

### ApplyForCommitteeMembershipHandler

```php
public function handle(ApplyForCommitteeMembershipCommand $command): MembershipApplicationId
```

**Input:** Member applies to join committee
**Output:** Application ID (now in SUBMITTED state)
**Side effects:** Save application, publish event

### ReviewMembershipApplicationHandler

```php
public function handle(ReviewMembershipApplicationCommand $command): ?CommitteeAssociation
```

**Input:** Admin approves or rejects application
**Output:** CommitteeAssociation if approved, null if rejected
**Side effects:** 
- Save updated application
- Save association (if approved)
- Publish event

---

## Repository Ports

### MembershipApplicationRepositoryPort

```php
saveForTenant(MembershipApplication $app): void
getOrFailForTenant(MembershipApplicationId $id, TenantId $tenant): MembershipApplication
existsActiveForTenant(MemberId $m, CommitteeId $c, TenantId $t): bool
```

### CommitteeAssociationRepositoryPort

```php
saveForTenant(CommitteeAssociation $assoc, TenantId $tenant): void
findActiveByCommitteeForTenant(CommitteeId $c, TenantId $t): CommitteeAssociation[]
findActiveByMemberForTenant(MemberId $m, TenantId $t): CommitteeAssociation[]
```

---

## Common Patterns

### Apply → Review → Associate

```php
// Step 1: Apply
$applyCmd = new ApplyForCommitteeMembershipCommand(
    tenantId: $tenantId,
    memberId: $memberId,
    committeeId: $committeeId,
    reason: ApplicationReason::RESIDENCE,
    exceptionJustification: null,
    memberGeoPath: $memberGeo,
    committeeGeoPath: $committeeGeo,
);
$appId = $applyHandler->handle($applyCmd);

// Step 2: Review
$reviewCmd = new ReviewMembershipApplicationCommand(
    tenantId: $tenantId,
    applicationId: $appId->value(),
    action: 'APPROVE',  // or 'REJECT'
    reviewedBy: $adminId,
);
$association = $reviewHandler->handle($reviewCmd);

// Step 3: Use in Elections
$voters = $associationRepo->findActiveByCommitteeForTenant($committeeId, $tenantId);
```

### Check Eligibility Before Applying

```php
$isEligible = $eligibilityPolicy->isEligible(
    GeoPathChain::from($committee->geoPath),
    GeoPathChain::from($member->geoPath),
);

if (!$isEligible && $reason === ApplicationReason::RESIDENCE) {
    // Show error or offer exception path
}
```

### List Pending Applications

```php
$applications = $appRepo->findAll();  // (add method if needed)
$pending = array_filter(
    $applications,
    fn($a) => $a->status()->isActive(),
);
```

---

## Event Handlers (Listen For)

### CommitteeMembershipApplicationSubmitted

Member applied. Create notification, audit entry.

```php
$event->applicationId
$event->memberId
$event->committeeId
$event->reason
$event->submittedAt
```

### CommitteeMembershipApplicationApproved

Member approved. Create association, notify member, update voting list.

```php
$event->applicationId
$event->reviewedBy
$event->reviewedAt
```

### CommitteeMembershipApplicationRejected

Member rejected. Notify member, update status.

```php
$event->applicationId
$event->reviewedBy
$event->reviewedAt
```

---

## Test Double Doubles

### FakeCommitteeEligibilityPolicy

```php
$policy = new FakeCommitteeEligibilityPolicy();
$policy->setEligible(true);        // or false
$policy->wasEligibilityCalled();   // bool
$policy->reset();                  // Clear call tracking
```

### InMemoryMembershipApplicationRepository

```php
$repo = new InMemoryMembershipApplicationRepository();
$repo->saveForTenant($app);
$repo->getOrFailForTenant($id, $tenantId);
$repo->existsActiveForTenant($memberId, $committeeId, $tenantId);
```

### InMemoryCommitteeAssociationRepository

```php
$repo = new InMemoryCommitteeAssociationRepository();
$repo->saveForTenant($assoc, $tenantId);
$repo->findActiveByCommitteeForTenant($committeeId, $tenantId);
$repo->findActiveByMemberForTenant($memberId, $tenantId);
```

### FakeEventBus

```php
$bus = new FakeEventBus();
$bus->publish($event);
$bus->hasPublished('EventClassName');  // bool
$bus->reset();                         // Clear published events
```

---

## Tenant Isolation Checklist

- [ ] Command includes `TenantId`
- [ ] All repository calls pass `TenantId`
- [ ] Handler enforces tenant on lookups
- [ ] Test double enforces tenant equality
- [ ] No cross-tenant queries possible

---

## Test Count: 20 GREEN

| Layer | Tests | File |
|-------|-------|------|
| Domain | 11 | `MembershipApplicationTest.php` |
| Apply | 5 | `ApplyForCommitteeMembershipHandlerTest.php` |
| Review | 4 | `ReviewMembershipApplicationHandlerTest.php` |
| **Total** | **20** | **All ✅ GREEN** |

---

## Next: Phase 4 HTTP Layer

- Controller (thin adapter)
- FormRequest (DTOs)
- Routes
- Feature tests

Bounded context ready. HTTP next.
