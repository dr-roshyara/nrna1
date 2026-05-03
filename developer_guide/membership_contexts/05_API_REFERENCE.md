# API Reference: Quick Lookup

Quick reference for commonly-used classes, interfaces, and methods.

---

## 🎭 Aggregate Root: Committee

**File:** `app/Contexts/Membership/Domain/Committee/Committee.php`

### Factory Methods

```php
// Create new committee
Committee::form(
    type: CommitteeType,        // central, province, district, ward, youth, women, student
    name: CommitteeName,        // Committee name
    code: CommitteeCode,        // Unique code
    tenantId: TenantId,
    geoReference: ?GeoReference // null for central, required for geographic
): Committee

// Reconstruct from database (infrastructure layer)
Committee::reconstitute(array $data): Committee
```

### Query Methods

```php
$committee->getId(): CommitteeId
$committee->getName(): CommitteeName
$committee->getCode(): CommitteeCode
$committee->type(): CommitteeType
$committee->getStatus(): CommitteeStatus
$committee->getTenantId(): TenantId
$committee->getOperationalGeoReference(): ?GeoReference
$committee->getAssignments(): array  // CommitteeAssignment[]
```

### Command Methods

```php
// Assign member to committee
$committee->assignMember(
    memberId: MemberId,
    rolePath: RolePath,                  // "1.0.0", "2.0.0", etc.
    nominationType: NominationType,      // elected, nominated, appointed
    memberGeography: ?GeoReference = null,
    electionDate: ?DateTimeImmutable = null,
    termEndDate: ?DateTimeImmutable = null,
    appointedByUserId: ?TenantUserId = null,
    notes: ?string = null,
    metadata: array = []
): CommitteeAssignment

// Remove member from committee
$committee->removeAssignment(CommitteeAssignmentId $id): void

// Update member's role
$committee->updateMemberRole(
    CommitteeAssignmentId $id,
    RolePath $newRole
): void

// Status changes
$committee->deactivate(string $reason = ''): void
$committee->suspend(string $reason = ''): void
$committee->reactivate(): void
```

### Query by Role

```php
$committee->assignmentsByRole(RolePath $role): array
$committee->hasAssignmentWithRole(RolePath $role): bool
$committee->countAssignmentsByRole(RolePath $role): int
```

### Events

```php
$committee->pullEvents(): array  // Returns and clears recorded events
```

---

## 👤 Owned Entity: CommitteeAssignment

**File:** `app/Contexts/Membership/Domain/Committee/CommitteeAssignment.php`

### Factory

```php
CommitteeAssignment::create(
    id: CommitteeAssignmentId,
    memberId: MemberId,
    rolePath: RolePath,
    nominationType: NominationType,
    joinedDate: DateTimeImmutable,
    termEndDate: ?DateTimeImmutable = null,
    memberGeography: ?GeoReference = null,
    electionDate: ?DateTimeImmutable = null,
    appointedByUserId: ?TenantUserId = null,
    notes: ?string = null,
    metadata: array = []
): CommitteeAssignment
```

### Query

```php
$assignment->getId(): CommitteeAssignmentId
$assignment->getMemberId(): MemberId
$assignment->getRolePath(): RolePath
$assignment->getNominationType(): NominationType
$assignment->getJoinedDate(): DateTimeImmutable
$assignment->getTermEndDate(): ?DateTimeImmutable
$assignment->getMemberGeography(): ?GeoReference
$assignment->isRemoved(): bool  // true if left_date is set
```

---

## 📋 Value Objects

### CommitteeType (Enum-like)

```php
CommitteeType::central()       // National-level
CommitteeType::province()      // State-level
CommitteeType::district()      // District-level
CommitteeType::ward()          // Ward-level
CommitteeType::youth()         // Youth Wing
CommitteeType::women()         // Women's Wing
CommitteeType::student()       // Student Wing
CommitteeType::diaspora()      // Diaspora committee

$type->value(): string              // "central", "province", etc.
$type->isCentral(): bool
$type->isGeographic(): bool         // province, district, ward
$type->isWing(): bool               // youth, women, student
$type->equals(CommitteeType $other): bool
```

### RolePath (Dot-notation Roles)

```php
RolePath::chairperson()        // "1.0.0"
RolePath::viceChairperson()    // "2.0.0"
RolePath::secretary()          // "3.0.0"
RolePath::treasurer()          // "4.0.0"
RolePath::coordinator()        // "5.0.0"
RolePath::member()             // "0.0.0"

RolePath::fromString('1.0.0')

$role->value(): string
$role->equals(RolePath $other): bool
```

### GeoReference (Geographic Path)

```php
// Format: "np.{province}.{district}.{local}.{ward}"
// Example: "np.3.15.234.1" means Province 3, District 15, Local 234, Ward 1

GeoReference::fromString('np.3.15')

$geo->value(): string                  // "np.3.15"
$geo->pathPrefix(): string             // For prefix matching
$geo->getComponents(): array            // ['np', '3', '15', ...]
$geo->equals(GeoReference $other): bool
$geo->contains(GeoReference $other): bool  // Check if other is within this
```

### NominationType (Enum-like)

```php
NominationType::elected()      // "elected"
NominationType::nominated()    // "nominated"
NominationType::appointed()    // "appointed"

$type->value(): string
$type->equals(NominationType $other): bool
```

### CommitteeStatus (Enum-like)

```php
CommitteeStatus::active()      // Functioning
CommitteeStatus::inactive()    // Temporarily inactive
CommitteeStatus::suspended()   // Suspended by authority
CommitteeStatus::deleted()     // Soft deleted

$status->isActive(): bool
$status->isInactive(): bool
$status->isSuspended(): bool
$status->isDeleted(): bool
$status->equals(CommitteeStatus $other): bool
```

### TenantId (Organization Identifier)

```php
TenantId::fromString('69b96a71-ced4-49af-9653-d449111e6e2d')
TenantId::fromOrganisationId($organisationId)

$id->value(): string  // UUID string
$id->equals(TenantId $other): bool
```

### CommitteeId, MemberId, CommitteeAssignmentId (ULID Identifiers)

```php
CommitteeId::generate()        // Create new ULID
CommitteeId::fromString('01KQNEV4GREY80JNE1EE5YDSVB')

$id->value(): string
$id->equals(CommitteeId $other): bool
```

---

## 💾 Repositories

### CommitteeRepositoryInterface

**File:** `app/Contexts/Membership/Domain/Repositories/CommitteeRepositoryInterface.php`

```php
// Find single committee for tenant
findForTenant(CommitteeId $id, TenantId $tenantId): ?Committee

// Save committee (create or update)
saveForTenant(Committee $committee): void

// Find all committees for tenant
findAllForTenant(TenantId $tenantId): array  // Committee[]

// Find committees by type for tenant
findByTypeForTenant(CommitteeType $type, TenantId $tenantId): array

// Find committees by geography for tenant
findByGeographyForTenant(GeoReference $geo, TenantId $tenantId): array

// Delete committee (soft delete)
deleteForTenant(CommitteeId $id, TenantId $tenantId): void
```

**Usage:**

```php
$repo = app(CommitteeRepositoryInterface::class);

$committee = $repo->findForTenant($id, $tenantId);
if ($committee === null) {
    throw new CommitteeNotFoundException($id);
}

$repo->saveForTenant($committee);
```

---

## 🎯 Use Cases (Application Layer)

### GetCommitteeDashboard (Query)

**File:** `app/Contexts/Membership/Application/Committee/GetCommitteeDashboard.php`

```php
// Constructor
__construct(CommitteeRepositoryInterface $committees)

// Execute
execute(CommitteeId $id, TenantId $tenantId): CommitteeDashboardView

// Returns view model with:
// - Committee details (name, type, status, geography)
// - Sub-committees (geographic committees within parent)
// - Member assignments (with roles and details)
// - Level (computed: 1=central, 2=province, 3=district, 4=ward)
```

**Usage:**

```php
$view = app(GetCommitteeDashboard::class)->execute($id, $tenantId);
return Inertia::render('Committee/Dashboard', $view->toArray());
```

### CreateCommittee (Command)

**File:** `app/Contexts/Membership/Application/Committee/CreateCommittee.php`

```php
// Constructor
__construct(
    CommitteeRepositoryInterface $committees,
    EventBus $eventBus
)

// Execute
execute(CreateCommitteeCommand $command): CommitteeId

// Command contains:
// - tenantId: TenantId
// - type: CommitteeType
// - name: string
// - code: string
// - geoReference: ?string (null for central, required for geographic)
```

**Usage:**

```php
$useCase = app(CreateCommittee::class);
$id = $useCase->execute(new CreateCommitteeCommand(
    tenantId: $tenantId,
    type: CommitteeType::district(),
    name: 'Kathmandu District',
    code: 'KATH-DIST',
    geoReference: 'np.3.15'
));
```

### AssignMemberToCommittee (Command)

**File:** `app/Contexts/Membership/Application/Committee/AssignMemberToCommittee.php`

```php
// Constructor
__construct(
    CommitteeRepositoryInterface $committees,
    EventBus $eventBus
)

// Execute
execute(AssignMemberDto $dto): void

// DTO contains:
// - committeeId: CommitteeId
// - tenantId: TenantId
// - memberId: MemberId
// - rolePath: RolePath
// - nominationType: NominationType
// - memberGeography: ?GeoReference
// - electionDate: ?DateTimeImmutable
// - termEndDate: ?DateTimeImmutable
// - appointedByUserId: ?TenantUserId
// - notes: ?string
// - metadata: array
```

**Usage:**

```php
$useCase = app(AssignMemberToCommittee::class);
$useCase->execute(new AssignMemberDto(
    committeeId: $committeeId,
    tenantId: $tenantId,
    memberId: $memberId,
    rolePath: RolePath::chairperson(),
    nominationType: NominationType::elected()
));
```

---

## 🔗 Services & Adapters

### EventBus (Dispatch Events)

**File:** `app/Shared/Domain/Events/EventBus.php` (interface)  
**Implementation:** `app/Shared/Infrastructure/Events/LaravelEventBus.php`

```php
// Dispatch multiple events
dispatchAll(array $events): void

// Each event is passed to Laravel's event() dispatcher
// Listeners registered in EventServiceProvider pick them up
```

**Usage:**

```php
$eventBus = app(EventBus::class);
$eventBus->dispatchAll($committee->pullEvents());
```

### TenantContext (Get Current Organization)

**File:** `app/Services/TenantContext.php`  
**Interface:** `app/Contracts/TenantContextInterface.php`

```php
// Get current tenant ID (with 3-tier fallback)
currentTenantId(): TenantId  // Throws RuntimeException if none

// Set explicit context (for tests, CLI, jobs)
setContext(string $tenantId): void
```

**Usage:**

```php
$tenantContext = app(TenantContextInterface::class);
$tenantId = $tenantContext->currentTenantId();

// Or in tests
$tenantContext->setContext('test-org-uuid');
```

---

## 🗄️ Eloquent Models

### CommitteeModel

**File:** `app/Contexts/Membership/Infrastructure/Models/CommitteeModel.php`

```php
use BelongsToTenant;  // Automatic GlobalScope filtering

// Attributes
id, name, code, type, operational_geo_reference, 
tenant_id, status, created_at, updated_at

// Relations
public function assignments(): HasMany
public function subCommittees(): HasMany  // Where geo_reference starts with parent's
```

**Query Example:**

```php
// Automatically scoped to current tenant
$committees = CommitteeModel::where('type', 'central')->get();

// To bypass scope (careful!):
$all = CommitteeModel::withoutGlobalScopes()->get();
```

---

## 🎭 Domain Events

### CommitteeFormed

**File:** `app/Contexts/Membership/Domain/Events/CommitteeFormed.php`

```php
// Properties
getCommitteeId(): CommitteeId
getType(): CommitteeType
getName(): CommitteeName
getOccurredAt(): DateTimeImmutable
```

### CommitteeMemberAssigned

**File:** `app/Contexts/Membership/Domain/Events/CommitteeMemberAssigned.php`

```php
getCommitteeId(): CommitteeId
getMemberId(): MemberId
getRolePath(): RolePath
getNominationType(): NominationType
getJoinedDate(): DateTimeImmutable
getOccurredAt(): DateTimeImmutable
```

### CommitteeMemberRemoved

```php
getCommitteeId(): CommitteeId
getMemberId(): MemberId
getLeftDate(): DateTimeImmutable
getOccurredAt(): DateTimeImmutable
```

### CommitteeMemberRoleUpdated

```php
getCommitteeId(): CommitteeId
getMemberId(): MemberId
getOldRolePath(): RolePath
getNewRolePath(): RolePath
getOccurredAt(): DateTimeImmutable
```

---

## ⚠️ Domain Exceptions

### CommitteeNotFoundException

Thrown when committee not found:

```php
throw new CommitteeNotFoundException($committeeId);
```

### InvalidGeographyException

Thrown when geography rules violated:

```php
throw new InvalidGeographyException('Central committees cannot be geographic');
```

### InvalidMemberGeographyException

Thrown when member's location outside committee boundary:

```php
throw new InvalidMemberGeographyException(
    "Member location {$geo} outside committee boundary"
);
```

---

## 🛣️ HTTP Routes

**File:** `routes/committee/committeeRoutes.php`

```php
GET  /committee/{committeeId}/dashboard   → CommitteeDashboardController@show
POST /committee                            → CommitteeController@store
```

---

## 🎨 Vue Components

### Dashboard Component

**File:** `resources/js/Pages/Committee/Dashboard.vue`

Props from server:

```php
{
    committee: {
        id: string,
        name: string,
        code: string,
        type: string,  // "central", "district", etc.
        level: number, // 1=central, 2=province, 3=district, 4=ward
        geo_reference: string|null,
        status: string,
        formation_date: date|null
    },
    sub_committees: [
        { id, name, code, type },
        ...
    ],
    assignments: [
        { id, member_name, role, joined_date },
        ...
    ]
}
```

---

## 🧪 Testing Helpers

### Unit Tests

```php
// Domain aggregate
$committee = Committee::form(
    type: CommitteeType::central(),
    name: CommitteeName::of('Test'),
    code: CommitteeCode::of('TEST-001'),
    tenantId: TenantId::fromString('tenant-uuid'),
    geoReference: null
);

// Check invariants
$this->assertTrue($committee->getStatus()->isActive());

// Record events
$events = $committee->pullEvents();
$this->assertCount(1, $events);
```

### Repository Tests

```php
$repo = app(CommitteeRepositoryInterface::class);

// Save and find
$repo->saveForTenant($committee);
$found = $repo->findForTenant($id, $tenantId);
$this->assertNotNull($found);
```

### Feature Tests

```php
// Login and make request
$this->actingAs($user);
$response = $this->get("/committee/$id/dashboard");

$response->assertStatus(200);
$response->assertSee('Committee Name');
```

---

## 🔍 Finding Things

| Looking for | File Pattern | Example |
|-------------|--------------|---------|
| Domain logic | `Domain/` | `Committee.php` |
| Use case | `Application/Committee/` | `GetCommitteeDashboard.php` |
| Database operation | `Infrastructure/Repositories/` | `EloquentCommitteeRepository.php` |
| HTTP endpoint | `Http/Controllers/` | `CommitteeDashboardController.php` |
| Database schema | `Database/Migrations/` | `*create_committees_table.php` |
| Vue component | `resources/js/Pages/Committee/` | `Dashboard.vue` |
| Test | `tests/` | `CommitteeTest.php` |
| Value object | `Domain/ValueObjects/` | `CommitteeType.php` |
| Event | `Domain/Events/` | `CommitteeFormed.php` |

---

**Next:** Read [06_TESTING.md](./06_TESTING.md) for testing patterns
