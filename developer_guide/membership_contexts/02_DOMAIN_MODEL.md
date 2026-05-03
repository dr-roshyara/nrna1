# Domain Model: Committee, Member, and Assignment

## 🎯 Core Aggregate Root: Committee

The **Committee** is the primary aggregate root managing all committee-related logic. It encapsulates:

- Committee metadata (name, code, type, status)
- Geographic constraints (operational location)
- Member assignments (roles, nomination type, terms)
- Structural rules (via Strategy pattern)
- State changes (domain events)

### Committee Types (Strategy Pattern)

```php
CommitteeType::central()      // National-level (no geography)
CommitteeType::province()     // Province-level (geography required)
CommitteeType::district()     // District-level (geography required)
CommitteeType::ward()         // Ward-level (geography required)
CommitteeType::youth()        // Youth Wing (18-35, optional geography)
CommitteeType::women()        // Women's Wing (women, optional geography)
CommitteeType::student()      // Student Wing (student status, optional geography)
CommitteeType::diaspora()     // Diaspora committee (optional geography)
```

Each type has **different business rules**:

```php
// CentralCommitteeStructure rules:
- Chairperson: max 1
- Vice Chairperson: max 1
- Secretary: max 1
- Geography: FORBIDDEN (must be null)

// GeographicCommitteeStructure (Province/District/Ward):
- Chairperson: max 1
- Vice Chairperson: max 2
- Secretary: max 2
- Treasurer: max 1
- Members: unlimited
- Geography: REQUIRED (must have value like "np.3.15")

// YouthWingStructure:
- Age requirement: 18-35 years
- Geography: optional
- Roles: chairperson, coordinator, member

// WomenWingStructure:
- Gender: women only
- Age: typically 18+ (configurable)
- Geography: optional

// StudentWingStructure:
- Student status: required
- Geography: optional
```

---

## 📋 Creating a Committee

### Factory Method (Domain Layer)

```php
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeCode;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;

// Create a central committee (no geography)
$committee = Committee::form(
    type: CommitteeType::central(),
    name: CommitteeName::of('Central Executive Committee'),
    code: CommitteeCode::of('CENTRAL-001'),
    tenantId: TenantId::fromString('org-123'),
    geoReference: null  // null for central committees
);

// Create a district committee (with geography)
$committee = Committee::form(
    type: CommitteeType::district(),
    name: CommitteeName::of('Kathmandu District Committee'),
    code: CommitteeCode::of('KATH-DIST-001'),
    tenantId: TenantId::fromString('org-123'),
    geoReference: GeoReference::fromString('np.3.15')  // Province 3, District 15
);
```

### What Happens During Creation

1. **Validate geography** — Central committees cannot have geography, geographic committees must have geography
2. **Create aggregate** — Initialize Committee instance with structural strategy
3. **Record event** — Record `CommitteeFormed` event for audit trail
4. **Return aggregate** — Domain layer returns aggregate (no database yet)

```php
// Inside Committee::form() method (simplified)
public static function form(
    CommitteeType $type,
    CommitteeName $name,
    CommitteeCode $code,
    TenantId $tenantId,
    ?GeoReference $geoReference = null,
): self {
    // Validate geography rules
    if ($type->isCentral() && $geoReference !== null) {
        throw new InvalidGeographyException('Central committees cannot be geographic');
    }
    
    if (!$type->isCentral() && $geoReference === null) {
        throw new InvalidGeographyException('Geographic committees require a location');
    }
    
    // Create instance with strategy
    $committee = new self(
        id: CommitteeId::generate(),
        type: $type,
        name: $name,
        code: $code,
        tenantId: $tenantId,
        operationalGeoReference: $geoReference,
        structure: self::strategyFor($type)
    );
    
    // Record domain event
    $committee->recordEvent(new CommitteeFormed(
        committeeId: $committee->getId(),
        type: $type,
        name: $name
    ));
    
    return $committee;
}
```

---

## 👥 Assigning Members to Committees

### The Assignment Workflow

```
Member (exists)
    ↓
    Assign to Committee with Role
    ↓
    Validate:
        ✓ Member exists
        ✓ Committee exists
        ✓ Role is valid for committee type
        ✓ Role limit not exceeded
        ✓ Member's geography within committee's geography (if geographic)
    ↓
    Create CommitteeAssignment owned entity
    ↓
    Add to committee's assignments collection
    ↓
    Record CommitteeMemberAssigned event
```

### Assigning a Member

```php
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Contexts\Membership\Domain\ValueObjects\NominationType;

$committee->assignMember(
    memberId: MemberId::fromString('member-123'),
    rolePath: RolePath::chairperson(),           // "1.0.0"
    nominationType: NominationType::elected(),
    memberGeography: GeoReference::fromString('np.3.15'),  // Member's location
    electionDate: new DateTimeImmutable('2026-05-01'),
    termEndDate: new DateTimeImmutable('2028-05-01'),
    appointedByUserId: TenantUserId::fromString('user-456'),
    notes: 'Elected in general assembly',
    metadata: ['election_position' => 1, 'votes_received' => 450]
);

// This creates a CommitteeAssignment owned entity and records event
```

### What Happens During Assignment

1. **Validate role** — Strategy checks if role exists for committee type
2. **Validate role limit** — Check if maximum assignments for this role already reached
3. **Validate geography** — If member's location outside committee's boundary, reject (for geographic committees)
4. **Create assignment** — Instantiate CommitteeAssignment with all details
5. **Add to collection** — Add assignment to committee's assignments
6. **Record event** — Record `CommitteeMemberAssigned` event
7. **Return assignment** — Return the CommitteeAssignment entity

```php
// Pseudocode for Committee::assignMember()
public function assignMember(
    MemberId $memberId,
    RolePath $rolePath,
    NominationType $nominationType,
    ?GeoReference $memberGeography = null,
    ...
): CommitteeAssignment {
    // Validate role exists and is available
    $this->structure->validateRoleAssignment($rolePath);
    
    // Validate role is not already assigned to max capacity
    $count = count(array_filter(
        $this->assignments,
        fn($a) => $a->getRolePath()->equals($rolePath) && !$a->isRemoved()
    ));
    if ($count >= $this->structure->maxAssignmentsForRole($rolePath)) {
        throw new InvalidMemberAssignmentException(
            "Role {$rolePath->value()} already has maximum assignments"
        );
    }
    
    // Validate geography if required
    if (!$this->type->isCentral() && $memberGeography === null) {
        throw new InvalidMemberGeographyException('Geographic committees require member location');
    }
    
    if (!$this->type->isCentral() && !$this->contains($memberGeography)) {
        throw new InvalidMemberGeographyException(
            "Member location {$memberGeography->value()} outside committee boundary"
        );
    }
    
    // Create assignment
    $assignment = CommitteeAssignment::create(
        id: CommitteeAssignmentId::generate(),
        memberId: $memberId,
        rolePath: $rolePath,
        nominationType: $nominationType,
        joinedDate: new DateTimeImmutable(),
        ...
    );
    
    // Add to collection
    $this->assignments[] = $assignment;
    
    // Record event
    $this->recordEvent(new CommitteeMemberAssigned(
        committeeId: $this->id,
        memberId: $memberId,
        rolePath: $rolePath
    ));
    
    return $assignment;
}
```

---

## 🎭 Role Paths (Hierarchical Roles)

Roles are represented as **dot-notation paths**, not strings:

```php
// Role path format: position1.position2.position3...
// Examples:
RolePath::chairperson()           // "1.0.0"
RolePath::viceChairperson()       // "2.0.0"
RolePath::secretary()             // "3.0.0"
RolePath::treasurer()             // "4.0.0"
RolePath::coordinator()           // "5.0.0"
RolePath::member()                // "0.0.0"

// Why dot-notation?
// - Sortable (chairperson 1.0.0 > vice chair 2.0.0 > secretary 3.0.0)
// - Hierarchical (can have sub-roles in future: 1.1.0, 1.1.1)
// - Translatable (path stays constant, label changes by language)
// - Type-safe (no typos like "chairperson" vs "chairperson")
```

### Query by Role

```php
// Find all assignments with a specific role
$chairpersons = $committee->assignmentsByRole(RolePath::chairperson());

// Check if role is vacant
$hasChairperson = $committee->hasAssignmentWithRole(RolePath::chairperson());

// Get assignment count by role
$count = $committee->countAssignmentsByRole(RolePath::secretary());
```

---

## 🏠 Owned Entity: CommitteeAssignment

**CommitteeAssignment** is an owned entity (part of Committee aggregate), not a separate aggregate.

```php
use App\Contexts\Membership\Domain\Committee\CommitteeAssignment;

$assignment = CommitteeAssignment::create(
    id: CommitteeAssignmentId::generate(),
    memberId: MemberId::fromString('member-123'),
    rolePath: RolePath::chairperson(),
    nominationType: NominationType::elected(),
    joinedDate: new DateTimeImmutable('2026-05-01'),
    termEndDate: new DateTimeImmutable('2028-05-01'),
    memberGeography: GeoReference::fromString('np.3.15'),
    electionDate: new DateTimeImmutable('2026-05-01'),
    appointedByUserId: TenantUserId::fromString('user-456'),
    notes: 'Elected in general assembly',
    metadata: ['position_rank' => 1, 'votes' => 450]
);

// Query assignment properties
$assignment->getMemberId();
$assignment->getRolePath();
$assignment->getNominationType();      // elected, nominated, appointed
$assignment->getJoinedDate();
$assignment->getTermEndDate();
$assignment->getApointedByUserId();
$assignment->getMemberGeography();
$assignment->isRemoved();              // true if left_date is set

// Modify assignment
$assignment->updateRole(RolePath::viceChairperson());
$assignment->updateTermEndDate(new DateTimeImmutable('2029-05-01'));
$assignment->remove(new DateTimeImmutable());  // Mark as removed
```

### Why Owned Entity, Not Aggregate?

1. **No independent persistence** — Assignments are always accessed through Committee, never directly
2. **Shared invariants** — Assignment validity depends on Committee's rules (role limits, geography)
3. **Lifecycle tied** — Assignments die when Committee is deleted
4. **Single responsibility** — Committee manages the collection; Assignment represents individual membership

---

## 📊 Committee Status Lifecycle

```
                     ┌─────────┐
                     │  ACTIVE │
                     │ (default)
                     └────┬────┘
                          │
              ┌───────────┬┴──────────────┐
              ▼           ▼              ▼
          INACTIVE    SUSPENDED        DELETED
         (voluntary) (enforcement)    (permanent)
```

```php
use App\Contexts\Membership\Domain\ValueObjects\CommitteeStatus;

CommitteeStatus::active()      // Functioning normally
CommitteeStatus::inactive()    // Temporarily inactive (can reactivate)
CommitteeStatus::suspended()   // Suspended by higher authority (penalties, disputes)
CommitteeStatus::deleted()     // Permanently removed (soft delete)

// Check status
$committee->getStatus()->isActive();
$committee->getStatus()->isSuspended();

// Change status
$committee->deactivate();    // active → inactive
$committee->suspend();       // active/inactive → suspended
$committee->reactivate();    // inactive → active
$committee->delete();        // any → deleted (via soft delete)
```

---

## 🔗 Relationships: The Full Picture

```
┌─────────────────────────────────────────────────────────┐
│                    COMMITTEE                            │
│  - Type: central/province/district/ward/wings           │
│  - Status: active/inactive/suspended/deleted            │
│  - Geography: null (central) or "np.3.15" (geographic)  │
│  - Tenant: belongs to one organization                  │
└──────────────────────┬──────────────────────────────────┘
                       │
                       │ owns
                       ▼
        ┌──────────────────────────────┐
        │  COMMITTEE ASSIGNMENT         │
        │  (Collection: n per committee)│
        │                              │
        │  - Member ID (reference)     │
        │  - Role Path (1.0.0, etc)    │
        │  - Nomination Type           │
        │  - Joined Date               │
        │  - Term End Date             │
        │  - Appointment Details       │
        └──────────────────────────────┘
                       │
                       │ references (does NOT own)
                       ▼
        ┌──────────────────────────────┐
        │         MEMBER               │
        │   (Separate aggregate in     │
        │    Membership context)       │
        │                              │
        │  - Personal Info             │
        │  - Registration Status       │
        │  - Geographic Cache          │
        └──────────────────────────────┘
```

### Key Points

1. **Committee owns AssignmentS** — Assignments exist only within a Committee
2. **Assignment references Member** — Does not own; Member is separate aggregate
3. **No direct access to Member** — Must go through Application layer → Member Repository
4. **Can have 0..n assignments** — Committee can have no members (newly formed) or many

---

## 🧪 Domain Events (State Changelog)

### Events Raised by Committee

```php
namespace App\Contexts\Membership\Domain\Events;

// Event 1: When committee is created
CommitteeFormed {
    committeeId: CommitteeId
    type: CommitteeType
    name: CommitteeName
    tenantId: TenantId
    geoReference: ?GeoReference
    occurredAt: DateTimeImmutable
}

// Event 2: When member is assigned
CommitteeMemberAssigned {
    committeeId: CommitteeId
    memberId: MemberId
    rolePath: RolePath
    nominationType: NominationType
    joinedDate: DateTimeImmutable
    occurredAt: DateTimeImmutable
}

// Event 3: When member is removed
CommitteeMemberRemoved {
    committeeId: CommitteeId
    memberId: MemberId
    leftDate: DateTimeImmutable
    occurredAt: DateTimeImmutable
}

// Event 4: When role is changed
CommitteeMemberRoleUpdated {
    committeeId: CommitteeId
    memberId: MemberId
    oldRolePath: RolePath
    newRolePath: RolePath
    occurredAt: DateTimeImmutable
}
```

### How Events Work

```php
// 1. Domain aggregate records event (not dispatches)
$committee->recordEvent(new CommitteeFormed(...));

// 2. Application layer pulls events
$events = $committee->pullEvents();  // Returns array, clears internal list

// 3. Application dispatches via EventBus (after transaction commits)
$this->eventBus->dispatchAll($events);

// 4. Infrastructure listeners react
// - Log to audit trail
// - Send notifications
// - Update search index
// - Trigger webhooks
```

---

## ⚠️ Invariants (Business Rules That Never Break)

### Central Committee Invariants

```
✓ Has exactly 1 Chairperson (or 0 if vacant)
✓ Has at most 1 Vice Chairperson
✓ Has at most 1 Secretary
✓ Has exactly 0 assignments with geography
✓ Type is always CommitteeType::central()
```

### Geographic Committee Invariants

```
✓ Has exactly 1 Chairperson (or 0 if vacant)
✓ Has at most 2 Vice Chairpersons
✓ Has at most 2 Secretaries
✓ Has exactly 1 Treasurer
✓ All members within committee's geographic boundary
✓ operationalGeoReference is set (never null)
✓ Type is one of: province, district, ward
```

### Validation Happens in Domain Layer

```php
// NEVER: $committee->assignments = [];  // Don't do this
// Instead, use domain methods:
$committee->removeAssignment($assignment);  // Validates invariants

// NEVER: $committee->operationalGeoReference = null;  // Breaks for geographic
// Instead: Use factory method to create with correct geography
```

---

## 🔄 Immutability & Modifications

### Read-Only Access

```php
$committee->getId();                      // CommitteeId
$committee->getName();                    // CommitteeName
$committee->getCode();                    // CommitteeCode
$committee->type();                       // CommitteeType
$committee->getStatus();                  // CommitteeStatus
$committee->getTenantId();                // TenantId
$committee->getOperationalGeoReference(); // ?GeoReference
$committee->getAssignments();             // array of CommitteeAssignment
```

### Modifications (via Methods, Not Direct Property Access)

```php
$committee->assignMember(...);       // Add/update assignment
$committee->removeAssignment($id);   // Remove assignment
$committee->updateStatus(...);       // Change status
$committee->updateAssignment($id, $rolePath);  // Change role
```

### Value Objects Are Immutable

```php
$type = CommitteeType::central();
$type2 = $type->asProvince();  // Returns NEW instance, original unchanged

$role = RolePath::chairperson();
$role->equals(RolePath::chairperson());  // true
$role->equals(RolePath::secretary());     // false
```

---

## 📈 Design Patterns Used

### 1. **Aggregate Root Pattern**
- **What:** Committee is the entry point to all related data
- **Why:** Guarantees consistency; no orphaned assignments
- **Usage:** Always access assignments through Committee, never directly

### 2. **Strategy Pattern**
- **What:** Different structures for different committee types
- **Why:** Role limits, validation rules vary by type; avoid huge if/else
- **Usage:** `$committee->structure->validateRoleAssignment(...)`

### 3. **Value Object Pattern**
- **What:** CommitteeType, RolePath, GeoReference instead of strings
- **Why:** Type safety, validation, business meaning
- **Usage:** Everywhere! No plain strings or integers in domain

### 4. **Event Sourcing (Partial)**
- **What:** Domain events record state changes
- **Why:** Complete audit trail; enables event-driven features
- **Usage:** `$committee->recordEvent(...)` then `$committee->pullEvents()`

### 5. **Repository Pattern**
- **What:** CommitteeRepositoryInterface abstracts persistence
- **Why:** Domain doesn't know about Eloquent or SQL
- **Usage:** `$repo->findForTenant($id, $tenantId)` returns aggregate

---

## 🎯 Key Takeaways

| Concept | Example | Purpose |
|---------|---------|---------|
| **Aggregate** | Committee | Contains all logic for a committee |
| **Owned Entity** | CommitteeAssignment | Belongs only to Committee |
| **Value Object** | CommitteeType, RolePath | Immutable, strongly-typed |
| **Strategy** | CommitteeStructure | Different rules per type |
| **Domain Event** | CommitteeFormed | Records what happened |
| **Invariant** | "Max 1 chairperson" | Always enforced |
| **Repository** | CommitteeRepositoryInterface | Abstract persistence |

---

**Next:** Read [04_TENANT_ISOLATION.md](./04_TENANT_ISOLATION.md) to understand multi-tenancy safety
