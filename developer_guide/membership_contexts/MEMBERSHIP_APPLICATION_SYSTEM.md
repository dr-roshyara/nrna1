# Membership Application System Developer Guide

## Overview

The **Membership Application System** is a governance-grade workflow for managing committee membership requests. It combines eligibility checking, state machine enforcement, and multi-tenant isolation into a clean bounded context.

Members apply to join committees. Admins review and approve/reject. On approval, a `CommitteeAssociation` is created, giving the member voting/participation rights.

---

## Architecture at a Glance

```
Member Request
    ↓
ApplyForCommitteeMembershipHandler
    ↓ (validates eligibility)
MembershipApplication (SUBMITTED state)
    ↓
ReviewMembershipApplicationHandler
    ↓ (admin decision)
MembershipApplication (APPROVED/REJECTED)
    ↓
CommitteeAssociation (if approved)
    ↓
Elections context can query who votes
```

---

## Key Concepts

### 1. MembershipApplication (Domain Aggregate)

**What it is:** The application itself. Immutable, state-driven.

**Lifecycle:**
```
SUBMITTED → (under review) → APPROVED ✓
                          → REJECTED ✗
```

**Business Rules (enforced inside aggregate):**
- RESIDENCE applications require geographic eligibility
- EXCEPTION applications require justification text
- MANUAL applications skip all eligibility checks
- State transitions are restricted (can't approve twice)
- Only one active application per (member, committee)

**Key methods:**
```php
MembershipApplication::submit(
    id, tenantId, memberId, committeeId,
    reason, exceptionJustification, isEligible
): self

$application->approve(MemberId $reviewedBy): CommitteeAssociation
$application->reject(MemberId $reviewedBy): void
```

### 2. ApplicationReason (Value Object)

Three types:

| Type | Eligibility Check | Justification | Use Case |
|------|-------------------|---------------|----------|
| RESIDENCE | ✅ Geographic | ❌ Optional | Member lives in committee's region |
| EXCEPTION | ❌ Skipped | ✅ Required | Override eligibility (special approval) |
| MANUAL | ❌ Skipped | ❌ Optional | Admin-assigned (no election) |

### 3. CommitteeAssociation (Result Model)

**What it is:** NOT the application. The *outcome* of approval. A read-only permission artifact.

```php
$association = MembershipApplication::approve($reviewer);

$association->memberId           // Who got approved
$association->committeeId        // To what committee
$association->associationType    // RESIDENCE/EXCEPTION/MANUAL
$association->associatedAt       // When approved
$association->status             // ACTIVE
```

**Used by:** Elections context queries this to determine voting eligibility. Elections context **never** sees `MembershipApplication`.

### 4. Eligibility Policy

**Responsibility:** Determine if a member's residence geography permits them to join a committee.

**Location:** Application layer (handler calls it), NOT domain.

**Implementation:** `CommitteeEligibilityPolicy`

```php
public function isEligible(GeoPathChain $committee, GeoPathChain $member): bool
```

**Rules:**
- Central committees (no geo) → all members eligible
- Geographic committees require member to have residence
- Member's geography must be under committee's geography

---

## Using the System

### For Application Layer Code

#### Applying for Membership

```php
use App\Contexts\Membership\Application\Membership\ApplyForCommitteeMembership\ApplyForCommitteeMembershipCommand;
use App\Contexts\Membership\Application\Membership\ApplyForCommitteeMembership\ApplyForCommitteeMembershipHandler;

// In controller or action
$command = new ApplyForCommitteeMembershipCommand(
    tenantId: $this->tenantId,
    memberId: $request->member_id,
    committeeId: $request->committee_id,
    reason: ApplicationReason::RESIDENCE,
    exceptionJustification: null,
    memberGeoPath: GeoPathChain::from($member->geoPath),
    committeeGeoPath: GeoPathChain::from($committee->geoPath),
);

$applicationId = $handler->handle($command);
// → Returns MembershipApplicationId
// → Application created in SUBMITTED state
// → Event published
```

#### Reviewing an Application

```php
use App\Contexts\Membership\Application\Membership\ReviewMembershipApplication\ReviewMembershipApplicationCommand;
use App\Contexts\Membership\Application\Membership\ReviewMembershipApplication\ReviewMembershipApplicationHandler;

// Admin approves
$command = new ReviewMembershipApplicationCommand(
    tenantId: $this->tenantId,
    applicationId: $application->id,
    action: 'APPROVE',
    reviewedBy: $admin->id,
);

$association = $handler->handle($command);
// → Returns CommitteeAssociation if approved
// → Returns null if rejected
// → Association persisted to CommitteeAssociationRepository
// → Event published
```

### For Domain Layer Code

#### Checking Application State

```php
// Load from repository
$application = $repository->getOrFailForTenant($id, $tenantId);

// Check status
if ($application->status()->equals(ApplicationStatus::APPROVED)) {
    // ...
}

// Check if active (can be reviewed)
if ($application->status()->isActive()) {
    // SUBMITTED or UNDER_REVIEW
}
```

#### Creating Events

Events are **recorded** inside the domain aggregate, **published** by the handler:

```php
// In MembershipApplication::submit()
$this->recordEvent(
    new CommitteeMembershipApplicationSubmitted(
        applicationId: $id->value(),
        memberId: $memberId->value(),
        committeeId: $committeeId->value(),
        reason: $reason->value,
        submittedAt: $this->submittedAt,
    )
);

// In handler (after persist)
foreach ($application->releaseEvents() as $event) {
    $this->eventBus->publish($event);
}
```

---

## Testing Patterns

### Unit Test: Domain Aggregate

```php
use PHPUnit\Framework\TestCase;

final class MembershipApplicationTest extends TestCase
{
    private TenantId $tenantId;

    protected function setUp(): void
    {
        $this->tenantId = TenantId::fromString('test-tenant-id');
    }

    public function test_can_submit_residence_application_when_eligible(): void
    {
        $application = MembershipApplication::submit(
            id: MembershipApplicationId::generate(),
            tenantId: $this->tenantId,
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            reason: ApplicationReason::RESIDENCE,
            exceptionJustification: null,
            isEligible: true,  // ← pre-computed by handler
        );

        self::assertTrue($application->status()->equals(ApplicationStatus::SUBMITTED));
        self::assertCount(1, $application->releaseEvents());
    }

    public function test_cannot_approve_rejected_application(): void
    {
        $this->expectException(DomainException::class);

        $application = MembershipApplication::submit(
            // ...
        );

        $application->reject(MemberId::generate());
        $application->approve(MemberId::generate());  // ← throws
    }
}
```

### Unit Test: Handler

```php
use Tests\Doubles\InMemoryMembershipApplicationRepository;
use Tests\Doubles\InMemoryCommitteeAssociationRepository;
use Tests\Doubles\FakeEventBus;
use Tests\Doubles\FakeCommitteeEligibilityPolicy;

final class ReviewMembershipApplicationHandlerTest extends TestCase
{
    private ReviewMembershipApplicationHandler $handler;
    private InMemoryMembershipApplicationRepository $appRepository;
    private InMemoryCommitteeAssociationRepository $assocRepository;
    private FakeEventBus $eventBus;

    protected function setUp(): void
    {
        $this->appRepository = new InMemoryMembershipApplicationRepository();
        $this->assocRepository = new InMemoryCommitteeAssociationRepository();
        $this->eventBus = new FakeEventBus();

        $this->handler = new ReviewMembershipApplicationHandler(
            $this->appRepository,
            $this->assocRepository,
            $this->eventBus,
        );
    }

    public function test_approve_creates_committee_association(): void
    {
        // 1. Apply (creates application)
        $applicationId = $this->applyHandler->handle($applyCommand);

        // 2. Review (approves application)
        $association = $this->handler->handle($reviewCommand);

        // 3. Assert association was saved
        self::assertInstanceOf(CommitteeAssociation::class, $association);
        $saved = $this->assocRepository->findActiveByCommitteeForTenant(
            $committeeId, $tenantId
        );
        self::assertCount(1, $saved);
    }
}
```

---

## Tenant Isolation

**Every repository method requires `TenantId`:**

```php
// ✅ Correct
$application = $repository->getOrFailForTenant($id, $tenantId);
$exists = $repository->existsActiveForTenant($memberId, $committeeId, $tenantId);

// ❌ Wrong (does not exist)
$application = $repository->getOrFail($id);
```

**Data Flow:**
```
Request → TenantId from session/auth
    ↓
Command includes TenantId
    ↓
Handler passes TenantId to all repository calls
    ↓
Repository enforces equality check
    ↓
Cross-tenant leaks impossible
```

---

## Domain Boundaries

### What's Inside (Membership Context)

- Application workflow (submit → approve/reject)
- Eligibility computation
- State machine enforcement
- Event emission

### What's Outside (Other Contexts)

- **Elections Context:** Queries `CommitteeAssociationRepository` to determine voting rights. Never touches `MembershipApplication`.
- **Committee Context:** Manages committee structure, governance rules. Membership applications are input to this context, not part of it.

### Integration Points

**Elections Context queries:**
```php
$associations = $associationRepository->findActiveByCommitteeForTenant(
    $committeeId, $tenantId
);

// Each association represents one member who can vote
foreach ($associations as $assoc) {
    $voter = $assoc->memberId;
    // Can vote on this committee
}
```

---

## Common Tasks

### Task: Check if member can join committee

```php
// In ApplyForCommitteeMembershipHandler
$isEligible = $eligibilityPolicy->isEligible(
    $command->committeeGeoPath,
    $command->memberGeoPath,
);

// Then pass to domain
$application = MembershipApplication::submit(
    // ...
    isEligible: $isEligible,
);
```

### Task: List pending applications for admin review

```php
// In admin controller
$applications = $repository->findPendingForTenant($tenantId);

// Filter by status
$pending = array_filter(
    $applications,
    fn($app) => $app->status()->equals(ApplicationStatus::SUBMITTED)
);
```

### Task: Get all active members of a committee (for voting)

```php
// In Elections Context
$associations = $associationRepository->findActiveByCommitteeForTenant(
    $committeeId,
    $tenantId
);

$memberIds = array_map(
    fn($assoc) => $assoc->memberId,
    $associations
);
```

### Task: Handle approval event

```php
// Listen for CommitteeMembershipApplicationApproved event
// Create CommitteeAssociation projection
// Update audit trail
// Send approval notification

public function handle(CommitteeMembershipApplicationApproved $event)
{
    // Event has: applicationId, memberId, committeeId, reviewedAt
    // Use to trigger downstream logic
}
```

---

## Database Schema (Reference)

### membership_applications

| Column | Type | Notes |
|--------|------|-------|
| id | uuid | Primary key |
| organisation_id | uuid | Tenant scoping |
| member_id | string | Who applied |
| committee_id | string | To what committee |
| reason | enum | RESIDENCE, EXCEPTION, MANUAL |
| exception_justification | text | For EXCEPTION reason |
| is_eligible | boolean | Computed at application time |
| status | enum | SUBMITTED, UNDER_REVIEW, APPROVED, REJECTED |
| submitted_at | timestamp | |
| reviewed_by | string | Who approved/rejected |
| reviewed_at | timestamp | |

### committee_associations

| Column | Type | Notes |
|--------|------|-------|
| id | uuid | Primary key |
| organisation_id | uuid | Tenant scoping |
| member_id | string | Who has association |
| committee_id | string | To what committee |
| association_type | enum | RESIDENCE, EXCEPTION, MANUAL |
| associated_at | timestamp | When approved |
| status | enum | ACTIVE, SUSPENDED, TERMINATED |

**Key Constraint:** `UNIQUE(organisation_id, member_id, committee_id)` on committee_associations

---

## Events (Reference)

### CommitteeMembershipApplicationSubmitted
- When: Application created (SUBMITTED state)
- Contains: applicationId, memberId, committeeId, reason, submittedAt
- Used by: Audit trail, notifications

### CommitteeMembershipApplicationApproved
- When: Application transitioned to APPROVED
- Contains: applicationId, reviewedBy, reviewedAt
- Used by: Create CommitteeAssociation, audit, notifications

### CommitteeMembershipApplicationRejected
- When: Application transitioned to REJECTED
- Contains: applicationId, reviewedBy, reviewedAt
- Used by: Notify member, audit trail

---

## Troubleshooting

### "Only one active application per member and committee"

**Problem:** Trying to apply but got error.

**Cause:** Member already has an active (SUBMITTED or UNDER_REVIEW) application for this committee.

**Solution:**
```php
// Check before applying
$exists = $repository->existsActiveForTenant($memberId, $committeeId, $tenantId);
if ($exists) {
    // Show error or resolve existing application
}
```

### "Member not eligible for this committee"

**Problem:** RESIDENCE application was rejected.

**Cause:** Member's residence geography is not under committee's geography.

**Solution:**
```php
// Debug eligibility
$isEligible = $policy->isEligible($committeeGeo, $memberGeo);

// Check GeoPathChain.startsWith() logic
// Ensure geopaths are correct
```

### "Exception applications require justification"

**Problem:** EXCEPTION application failed.

**Cause:** No justification text provided.

**Solution:**
```php
$command = new ApplyForCommitteeMembershipCommand(
    // ...
    reason: ApplicationReason::EXCEPTION,
    exceptionJustification: 'Special permission granted by board', // ← required
);
```

### Events not published

**Problem:** Event bus is empty after approval.

**Cause:** Events must be released AFTER persistence.

**Solution (in handler):**
```php
$application = $repository->getOrFailForTenant($id, $tenantId);
$application->approve($reviewer);
$repository->saveForTenant($application);  // ← Persist FIRST

foreach ($application->releaseEvents() as $event) {
    $this->eventBus->publish($event);       // ← Publish AFTER
}
```

---

## Next: HTTP Layer

Phase 4 (separate session) will add:
- Controller endpoints
- FormRequest validators
- Routes
- Feature tests

The bounded context is production-ready. HTTP layer will be thin adapter only.

---

## References

- **Source:** `app/Contexts/Membership/`
- **Tests:** `tests/Unit/Contexts/Membership/`
- **Related:** Committee Context, Elections Context (reads CommitteeAssociation)
