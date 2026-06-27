# Phase F1 & F2 Developer Guide
## Organisation Dashboard Membership Integration (Complete)

**Status:** ✅ COMPLETE (F2-DONE tag)  
**Branches:** `phase-f1-dashboard-composition`  
**Latest Commit:** `5b4f282c0` (F2-DONE)

---

## Table of Contents

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Phase F1: Composition Layer](#phase-f1-composition-layer)
4. [Phase F2: Real Infrastructure](#phase-f2-real-infrastructure)
5. [How It Works](#how-it-works)
6. [Key Components](#key-components)
7. [Development Patterns](#development-patterns)
8. [Testing Strategy](#testing-strategy)
9. [Extending the System](#extending-the-system)

---

## Overview

Phases F1 and F2 implement a **membership eligibility dashboard widget** that displays committee membership information directly on the organisation show page without adding new routes or bounded contexts.

**What the user sees:**
- Active memberships (committees they belong to)
- Pending applications (committees they've applied to but await approval)
- Eligible committees (committees they're allowed to join based on geography and status)

**What happens behind the scenes:**
```
User visits /organisations/{slug}
    ↓
OrganisationController::show()
    ↓
MyCommitteesQueryService::getForMember()  [Phase C eligibility logic]
    ↓
Eligibility checks:
├─ CommitteeEligibilityPolicy.isEligible()
├─ CommitteeGeoPathProviderPort.resolveForCommittee()  [F2 real implementation]
├─ MemberGeoPathProviderPort.resolveForMember()         [F2 real implementation]
├─ CommitteeAssociationRepositoryPort.getForMember()
└─ MembershipApplicationRepositoryPort.existsActive()  [F2 real implementation]
    ↓
MembershipWidget.vue renders with:
├─ Active count
├─ Pending count
├─ Eligible count
└─ Detailed list (name, code, status, action buttons)
```

---

## Architecture

### Layering

```
HTTP Layer (Controller)
    ↓
Application Layer (Query Service)
    ↓
Domain Layer (Eligibility Policy)
    ↓
Infrastructure Layer (Repositories & Providers)
    ↓
Database / User Model
```

### Key Principle: Ports & Adapters

F1 introduced **stub implementations** for missing infrastructure ports. F2 replaced them with **real implementations** backed by actual data sources:

| Port Interface | F1 Stub | F2 Real Implementation | Data Source |
|---|---|---|---|
| `MembershipApplicationRepositoryPort` | `StubMembershipApplicationRepository` | `EloquentCommitteeMembershipApplicationRepository` | `committee_membership_applications` table |
| `CommitteeGeoPathProviderPort` | `StubCommitteeGeoPathProvider` | `EloquentCommitteeGeoPathProvider` | `GeoSemanticProjectionBuilder` |
| `MemberGeoPathProviderPort` | `SessionMemberGeoPathProvider` (1-way) | `SessionMemberGeoPathProvider` (real) | User's `residence_geo_unit_id` FK |

**Why this approach?**
- F1 was feature-complete and testable with safe defaults (no active applications, root geo path)
- F2 adds real database backing without changing the composition layer
- The service provider (IoC container) is the single point where implementations swap
- Zero coupling between controller, query service, and infrastructure implementation choice

---

## Phase F1: Composition Layer

### Components

**File:** `app/Http/Controllers/OrganisationController.php`

```php
public function __construct(
    private readonly MyCommitteesQueryService $membershipService,
    private readonly MemberGeoPathProviderPort $geoPathProvider,
) {}

public function show(Organisation $organisation)
{
    // ... existing code ...
    
    // F1: Compose membership read model
    $memberId     = MemberId::fromString((string) $user->id);
    $tenantId     = TenantId::fromOrganisationId((string) $organisation->id);
    $memberGeoPath = $this->geoPathProvider->resolveForMember($memberId, $tenantId);
    
    $membershipData = array_map(fn ($view) => [
        'committee_id'            => $view->committeeId,
        'committee_name'          => $view->committeeName,
        'committee_code'          => $view->committeeCode,
        'governance_level'        => $view->governanceLevel,
        'has_active_association'  => $view->hasActiveAssociation,
        'has_pending_application' => $view->hasPendingApplication,
        'can_apply'               => $view->canApply,
        'application_status'      => $view->applicationStatus,
    ], $this->membershipService->getForMember($memberId, $tenantId, $memberGeoPath));

    return inertia('Organisations/Show', [
        'organisation' => $organisation,
        'membership'   => $membershipData,
        // ... other props ...
    ]);
}
```

**Responsibility:** Pure orchestration. No business logic — just wiring up services and transforming to view-friendly primitives.

### Vue Component

**File:** `resources/js/Pages/Organisations/Partials/MembershipWidget.vue`

```vue
<template>
  <div class="portal-card portal-card--membership-detail">
    <div class="portal-card__icon-wrap">
      <!-- Committee icon SVG -->
    </div>
    <div class="portal-card__body">
      <h3>Membership &amp; Committees</h3>
      <p v-if="hasData">
        <template v-if="activeCount > 0">{{ activeCount }} active</template>
        <template v-if="pendingCount > 0"> · {{ pendingCount }} pending</template>
        <template v-if="eligibleCount > 0"> · {{ eligibleCount }} eligible</template>
      </p>
      <p v-else>View your committee memberships and applications</p>
    </div>
    <div class="portal-card__arrow">→</div>
  </div>
</template>

<script setup>
const props = defineProps({ membership: Array })
const activeCount   = computed(() => props.membership.filter(c => c.has_active_association).length)
const pendingCount  = computed(() => props.membership.filter(c => c.has_pending_application).length)
const eligibleCount = computed(() => props.membership.filter(c => c.can_apply).length)
const hasData       = computed(() => activeCount.value + pendingCount.value + eligibleCount.value > 0)
</script>
```

**Responsibility:** Pure presentation. No API calls, no side effects — just rendering props to UI.

---

## Phase F2: Real Infrastructure

### F2 What Changed

F2 replaced all three stub implementations with real database-backed code. The **composition layer (F1) did not change** — only the infrastructure layer.

**Principle:** Swap implementations via service provider bindings. Controller and services remain untouched.

### Key Files Added/Modified

#### 1. Database Migration: User Geo Context

**File:** `database/migrations/2026_05_14_000001_add_residence_geo_unit_id_to_users.php`

```php
Schema::table('users', function (Blueprint $table) {
    $table->unsignedBigInteger('residence_geo_unit_id')
          ->nullable()
          ->after('state');
    $table->foreign('residence_geo_unit_id')
          ->references('id')
          ->on('geo_administrative_units')
          ->nullOnDelete();
});
```

**Purpose:** Enable each user to have a geographic context (e.g., "lives in Bavaria"). Nullable for users without geographic assignment.

**User Model Update:**
```php
// app/Models/User.php
protected $fillable = [
    'id', 'name', 'email', 'password', 'residence_geo_unit_id', // ← Added
    // ...
];
```

#### 2. Tenant Migration: Application Tracking

**File:** `app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/2026_05_14_000001_create_committee_membership_applications_table.php`

```php
Schema::create('committee_membership_applications', function (Blueprint $table) {
    $table->string('id', 26)->primary();
    $table->string('organisation_id', 50);
    $table->string('member_id', 50);
    $table->string('committee_id', 26);
    $table->string('reason', 20);  // ApplicationReason enum
    $table->text('exception_justification')->nullable();
    $table->string('status', 20);  // ApplicationStatus enum
    $table->timestamp('submitted_at')->nullable();
    $table->string('reviewed_by', 50)->nullable();
    $table->timestamp('reviewed_at')->nullable();
    $table->timestamps();

    // Indexes for common queries
    $table->index(['organisation_id', 'member_id', 'committee_id'], 'idx_cma_org_member_committee');
    $table->index(['organisation_id', 'status'], 'idx_cma_org_status');
});
```

**Purpose:** Track when members apply to committees, with status (SUBMITTED, UNDER_REVIEW, APPROVED, REJECTED) and timestamps.

**Multi-tenancy:** Loaded in `MembershipServiceProvider::boot()` from `Tenant/` migrations directory. Scoped by `organisation_id`.

#### 3. Domain: Reconstitute Factory

**File:** `app/Contexts/Membership/Domain/Membership/MembershipApplication.php`

Added static factory to hydrate from database without firing events:

```php
public static function reconstitute(
    MembershipApplicationId $id,
    TenantId $tenantId,
    MemberId $memberId,
    CommitteeId $committeeId,
    ApplicationReason $reason,
    ?string $exceptionJustification,
    ApplicationStatus $status,
    \DateTimeImmutable $submittedAt,
    ?MemberId $reviewedBy,
    ?\DateTimeImmutable $reviewedAt,
): self {
    $instance = new self($id, $tenantId, $memberId, $committeeId, $reason, $exceptionJustification);
    $instance->status      = $status;
    $instance->submittedAt = $submittedAt;
    $instance->reviewedBy  = $reviewedBy;
    $instance->reviewedAt  = $reviewedAt;
    return $instance;
}
```

**Why:** Domain aggregate has private readonly constructor and properties. Repository needs a way to reconstruct from persistence without triggering business logic or domain events.

**Key Detail:** `reconstitute()` does NOT fire domain events. It's purely for hydration. `submit()`, `approve()`, `reject()` fire events and handle business logic.

#### 4. Eloquent Model: Database Mapping

**File:** `app/Contexts/Membership/Infrastructure/Models/CommitteeMembershipApplicationModel.php`

```php
final class CommitteeMembershipApplicationModel extends Model
{
    use BelongsToTenant;  // Auto-scope queries by organisation_id

    protected $table = 'committee_membership_applications';
    protected $fillable = [
        'id', 'organisation_id', 'member_id', 'committee_id',
        'reason', 'exception_justification', 'status',
        'submitted_at', 'reviewed_by', 'reviewed_at',
    ];
    protected $casts = [
        'id'              => 'string',
        'organisation_id' => 'string',
        'member_id'       => 'string',
        'committee_id'    => 'string',
        'reason'          => 'string',
        'status'          => 'string',
        'submitted_at'    => 'datetime',
        'reviewed_at'     => 'datetime',
    ];
    public $timestamps = true;
    public $incrementing = false;
    protected $keyType = 'string';
}
```

**Pattern:** Pure Eloquent model with no business logic. Used only by repository to read/write to database.

#### 5. Repository: Domain-Eloquent Bridge

**File:** `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeMembershipApplicationRepository.php`

```php
final class EloquentCommitteeMembershipApplicationRepository 
    implements MembershipApplicationRepositoryPort
{
    public function saveForTenant(MembershipApplication $application): void
    {
        // Extract private readonly properties via reflection
        CommitteeMembershipApplicationModel::create([
            'id'                    => $this->getId($application),
            'organisation_id'       => $this->getTenantId($application),
            'member_id'             => $this->getMemberId($application),
            'committee_id'          => $this->getCommitteeId($application),
            'reason'                => $this->getReason($application),
            'exception_justification' => $this->getExceptionJustification($application),
            'status'                => $this->getStatus($application),
            'submitted_at'          => $this->getSubmittedAt($application),
        ]);
    }

    public function getOrFailForTenant(
        MembershipApplicationId $id,
        TenantId $tenantId,
    ): MembershipApplication {
        $model = CommitteeMembershipApplicationModel::where('organisation_id', $tenantId->value())
            ->where('id', $id->value())
            ->first();

        if ($model === null) {
            throw new \RuntimeException("MembershipApplication not found: {$id->value()}");
        }

        return $this->hydrate($model);
    }

    public function existsActiveForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): bool {
        return CommitteeMembershipApplicationModel::where('organisation_id', $tenantId->value())
            ->where('member_id', $memberId->value())
            ->where('committee_id', $committeeId->value())
            ->whereIn('status', [
                ApplicationStatus::SUBMITTED->value,
                ApplicationStatus::UNDER_REVIEW->value,
            ])
            ->exists();
    }

    private function hydrate(CommitteeMembershipApplicationModel $model): MembershipApplication
    {
        return MembershipApplication::reconstitute(
            id:                     MembershipApplicationId::fromString($model->id),
            tenantId:               TenantId::fromOrganisationId($model->organisation_id),
            memberId:               MemberId::fromString($model->member_id),
            committeeId:            CommitteeId::fromString($model->committee_id),
            reason:                 ApplicationReason::from($model->reason),
            exceptionJustification: $model->exception_justification,
            status:                 ApplicationStatus::from($model->status),
            submittedAt:            new \DateTimeImmutable($model->submitted_at->format('c')),
            reviewedBy:             $model->reviewed_by ? MemberId::fromString($model->reviewed_by) : null,
            reviewedAt:             $model->reviewed_at ? new \DateTimeImmutable($model->reviewed_at->format('c')) : null,
        );
    }

    // Reflection-based getters to access private readonly properties
    private function getId(MembershipApplication $app): string
    {
        $refl = new \ReflectionClass($app);
        return $refl->getProperty('id')->getValue($app)->value();
    }
    
    // ... similar for other properties
}
```

**Responsibility:** 
- Bridge between domain aggregate (pure PHP, private properties) and Eloquent model (database mapping)
- Use reflection to extract private readonly properties for persistence
- Hydrate domain objects from Eloquent models using `reconstitute()`
- Enforce tenant scoping on all queries

#### 6. Geo Providers: Path Resolution

**File:** `app/Contexts/Membership/Infrastructure/Query/EloquentCommitteeGeoPathProvider.php`

```php
final class EloquentCommitteeGeoPathProvider 
    implements CommitteeGeoPathProviderPort
{
    public function __construct(
        private readonly GeoSemanticProjectionBuilder $geoBuilder,
    ) {}

    public function resolveForCommittee(int $geoUnitId): GeoPathChain
    {
        $projection = $this->geoBuilder->build($geoUnitId);

        if ($projection === null) {
            throw new \RuntimeException("Geo unit not found: {$geoUnitId}");
        }

        return GeoPathChainFactory::from($projection);
    }
}
```

**Purpose:** Take a geographic unit ID (e.g., "Bavaria") and return its full geographic path (e.g., [World → Europe → Germany → Bavaria]).

**File:** `app/Contexts/Membership/Infrastructure/Query/SessionMemberGeoPathProvider.php`

```php
final class SessionMemberGeoPathProvider 
    implements MemberGeoPathProviderPort
{
    public function __construct(
        private readonly GeoSemanticProjectionBuilder $geoBuilder,
    ) {}

    public function resolveForMember(
        MemberId $memberId,
        TenantId $tenantId,
    ): GeoPathChain {
        $user = User::find($memberId->value());

        // If user has no geographic assignment, return empty path (root level)
        if (!$user?->residence_geo_unit_id) {
            return GeoPathChain::empty();
        }

        $projection = $this->geoBuilder->build((int) $user->residence_geo_unit_id);

        if ($projection === null) {
            return GeoPathChain::empty();
        }

        return GeoPathChainFactory::from($projection);
    }
}
```

**Purpose:** Get a member's geographic context from their User profile. Same resolution logic as committee provider.

**GeoPathChain::empty() Safety:**
```php
// Domain: Sentinel for "no geographic assignment"
public static function empty(): self
{
    return new self(geoUnitId: 1, path: '', segments: []);
}

public function isEmpty(): bool
{
    return $this->path === '';  // Short-circuits before ID comparison
}
```

#### 7. Service Provider: Implementation Binding

**File:** `app/Contexts/Membership/Infrastructure/Providers/MembershipServiceProvider.php`

Key changes:

```php
// F2: Real implementations — replace F1 stubs
$this->app->bind(
    \App\Contexts\Membership\Application\Membership\Ports\MembershipApplicationRepositoryPort::class,
    \App\Contexts\Membership\Infrastructure\Repositories\EloquentCommitteeMembershipApplicationRepository::class
);

$this->app->bind(
    \App\Contexts\Membership\Application\Membership\Query\Ports\CommitteeGeoPathProviderPort::class,
    \App\Contexts\Membership\Infrastructure\Query\EloquentCommitteeGeoPathProvider::class
);
```

**Principle:** This is the **single point where implementations swap**. Controller and query service don't know or care which implementation they get — they depend on interfaces.

---

## How It Works

### End-to-End Flow

```
1. User visits /organisations/{slug}
   ↓
2. OrganisationController::show() instantiated
   ├─ DI: MyCommitteesQueryService injected
   └─ DI: MemberGeoPathProviderPort injected (→ SessionMemberGeoPathProvider)
   ↓
3. Controller calls:
   $memberGeoPath = $geoPathProvider->resolveForMember($memberId, $tenantId)
   ├─ SessionMemberGeoPathProvider::resolveForMember() called
   ├─ User::find($memberId) loads user record
   ├─ GeoSemanticProjectionBuilder::build($user->residence_geo_unit_id) called
   ├─ GeoPathChainFactory::from($projection) returns path chain
   └─ Returns: GeoPathChain { geoUnitId: 42, path: "/1/5/12/42", segments: [1,5,12,42] }
   ↓
4. Controller calls:
   $eligibleCommittees = $membershipService->getForMember($memberId, $tenantId, $memberGeoPath)
   ├─ MyCommitteesQueryService::getForMember() called (Phase C service)
   ├─ Loop through all committees in organisation
   ├─ For each committee:
   │  ├─ CommitteeGeoPathProviderPort::resolveForCommittee() called
   │  │  ├─ EloquentCommitteeGeoPathProvider::resolveForCommittee() called
   │  │  ├─ GeoSemanticProjectionBuilder::build($committee->geo_id) called
   │  │  ├─ GeoPathChainFactory::from($projection) called
   │  │  └─ Returns: GeoPathChain { geoUnitId: 12, path: "/1/5/12", segments: [1,5,12] }
   │  ├─ CommitteeEligibilityPolicy::isEligible($member, $committee, $memberPath, $committeePath)
   │  │  ├─ If geographic mismatch → ineligible
   │  │  └─ If geographic match → check associations
   │  ├─ CommitteeAssociationRepositoryPort::getForMember() called
   │  │  └─ Returns: active memberships only
   │  ├─ MembershipApplicationRepositoryPort::existsActiveForTenant() called
   │  │  ├─ EloquentCommitteeMembershipApplicationRepository::existsActiveForTenant() called
   │  │  ├─ Query: SELECT * FROM committee_membership_applications
   │  │  │         WHERE organisation_id = ? AND member_id = ? AND committee_id = ?
   │  │  │         AND status IN ('SUBMITTED', 'UNDER_REVIEW')
   │  │  └─ Returns: true/false
   │  └─ Build EligibleCommitteeView with flags
   └─ Returns: array of EligibleCommitteeView objects
   ↓
5. Controller transforms to primitives:
   $membershipData = array_map(fn ($view) => [
       'committee_id'            => $view->committeeId,
       'has_active_association'  => $view->hasActiveAssociation,
       'has_pending_application' => $view->hasPendingApplication,
       'can_apply'               => $view->canApply,
       // ... etc
   ], $eligibleCommittees)
   ↓
6. Render Inertia component:
   return inertia('Organisations/Show', ['membership' => $membershipData])
   ↓
7. Browser receives JSON:
   {
     "component": "Organisations/Show",
     "props": {
       "membership": [
         {
           "committee_id": "123",
           "committee_name": "Board of Directors",
           "has_active_association": true,
           "has_pending_application": false,
           "can_apply": false
         },
         ...
       ]
     }
   }
   ↓
8. Vue component renders:
   <MembershipWidget :membership="membership" />
   ├─ Counts active, pending, eligible
   └─ Displays summary in portal card
```

### Key Data Flow Diagram

```
Database                         Domain Layer                    Query Layer
┌─────────────────────┐         ┌──────────────────┐          ┌──────────────────┐
│ users               │         │ MembershipApp    │          │ MyCommittees     │
│ ├─ id               │─────┐   │ ├─ id            │          │ QueryService     │
│ └─ residence_...    │ DI  │   │ ├─ status        │          │ (Phase C)        │
│                     │     │   │ ├─ submitted_at  │          │                  │
│                     │     └──▶│ └─ ...           │◀─────────│ getForMember()   │
├─────────────────────┤         └──────────────────┘          └──────────────────┘
│ geo_admin_units     │                                                 ▲
│ ├─ id               │         ┌──────────────────┐                   │
│ └─ ...              │─────┐   │ GeoPathChain     │                   │
│                     │ DI  │   │ ├─ geoUnitId     │              Depends on:
├─────────────────────┤     │   │ ├─ path          │          ┌─────────────────┐
│ committee_membership │     │   │ └─ segments      │          │ EloquentCMMRepo │
│ _applications       │     └──▶│ (both member &   │          │ EloquentGeoPath │
│ ├─ id               │         │  committee)      │          │ Provider        │
│ ├─ organisation_id  │         └──────────────────┘          └─────────────────┘
│ ├─ member_id        │                                                 ▲
│ ├─ committee_id     │         ┌──────────────────┐                   │
│ ├─ status           │─────────│ Eligibility      │          Via Service Provider:
│ └─ ...              │         │ Policy           │          ┌─────────────────┐
└─────────────────────┘         │ (Phase E)        │          │ MembershipSP    │
                                └──────────────────┘          │ binds ports to  │
                                                               │ implementations │
                                                               └─────────────────┘
```

---

## Key Components

### Domain Layer

**MembershipApplication** — Domain aggregate for tracking applications:
```
- submit() — Create new application (fires CommitteeMembershipApplicationSubmitted event)
- approve(reviewedBy) — Approve application (fires CommitteeMembershipApplicationApproved event)
- reject(reviewedBy) — Reject application (fires CommitteeMembershipApplicationRejected event)
- reconstitute() — Hydrate from persistence (no events fired)
```

**GeoPathChain** — Value object representing geographic hierarchy:
```
GeoPathChain {
    geoUnitId: 42,
    path: "/1/5/12/42",
    segments: [1, 5, 12, 42]
}

Methods:
- isEmpty() → boolean (checks if path === '')
- from(projection) → static factory from GeoSemanticProjection
- empty() → static factory for no-geo sentinel
```

**EligibleCommitteeView** — Read model from Phase C query service:
```
EligibleCommitteeView {
    committeeId: string
    committeeName: string
    governanceLevel: int
    hasActiveAssociation: bool
    hasPendingApplication: bool
    canApply: bool
    applicationStatus: ?string
}
```

### Application Layer

**MyCommitteesQueryService** (Phase C) — Orchestrates eligibility checks:
```php
public function getForMember(
    MemberId $memberId,
    TenantId $tenantId,
    GeoPathChain $memberGeoPath
): array {
    // Returns array of EligibleCommitteeView
}
```

**Ports (Interfaces):**
- `MembershipApplicationRepositoryPort` — Query/persist applications
- `CommitteeGeoPathProviderPort` — Resolve committee geographic paths
- `MemberGeoPathProviderPort` — Resolve member geographic context
- `CommitteeAssociationRepositoryPort` — Query active associations
- `MembershipLineageRepositoryPort` — Query membership history

### Infrastructure Layer

**SessionMemberGeoPathProvider** — Resolves member geography from User model:
- Loads `user.residence_geo_unit_id`
- Returns `GeoPathChain::empty()` if null
- Builds path via `GeoSemanticProjectionBuilder` otherwise

**EloquentCommitteeGeoPathProvider** — Resolves committee geography:
- Takes `geoUnitId` from committee record
- Builds path via `GeoSemanticProjectionBuilder`
- Throws if geo unit not found

**EloquentCommitteeMembershipApplicationRepository** — Persists/queries applications:
- `saveForTenant()` — Create new application record
- `getOrFailForTenant()` — Load by ID + org scope
- `existsActiveForTenant()` — Check for pending applications

---

## Development Patterns

### 1. Constructor Injection Only

**✅ Correct:**
```php
final class EloquentCommitteeGeoPathProvider implements CommitteeGeoPathProviderPort
{
    public function __construct(
        private readonly GeoSemanticProjectionBuilder $geoBuilder,
    ) {}
}
```

**❌ Wrong:**
```php
final class BadProvider implements CommitteeGeoPathProviderPort
{
    public function resolve(int $geoUnitId): GeoPathChain
    {
        $builder = app(GeoSemanticProjectionBuilder::class);  // NO!
        // ...
    }
}
```

**Why:** Testability, explicit dependencies, no hidden service locator calls.

### 2. Tenant Scoping

**✅ Correct:**
```php
CommitteeMembershipApplicationModel::where('organisation_id', $tenantId->value())
    ->where('id', $id->value())
    ->first();
```

**❌ Wrong:**
```php
CommitteeMembershipApplicationModel::where('id', $id->value())
    ->first();  // What if another org has this ID?
```

**Why:** Multi-tenancy is hardened by default. Every query must explicitly scope to `organisation_id`.

### 3. Reflection for Private Properties

**Pattern:** Domain aggregates use private readonly properties. Repository needs to extract them:

```php
private function getId(MembershipApplication $app): string
{
    $refl = new \ReflectionClass($app);
    return $refl->getProperty('id')->getValue($app)->value();
}
```

**Why:** Preserves domain invariants (immutability, encapsulation). Repository doesn't expose getters just for persistence.

### 4. Reconstitute vs. Create

**Reconstitute** (from persistence, no events):
```php
MembershipApplication::reconstitute(
    id: $id,
    status: ApplicationStatus::SUBMITTED,
    // ... all state ...
);
```

**Create** (business logic, fires events):
```php
MembershipApplication::submit(
    id: $id,
    reason: ApplicationReason::EXCEPTION,
    exceptionJustification: "...",
    isEligible: true,
);
```

**Why:** Separates hydration (infrastructure concern) from aggregate creation (business logic). Events only fire on business operations, not on load.

### 5. Port Interfaces, Not Concrete Classes

**✅ Correct:**
```php
// Service depends on interface
public function __construct(
    private readonly MemberGeoPathProviderPort $geoProvider,
) {}
```

**❌ Wrong:**
```php
// Service depends on concrete class
public function __construct(
    private readonly SessionMemberGeoPathProvider $geoProvider,
) {}
```

**Why:** Implementations can swap in the service provider. Code that depends on concrete classes breaks when implementations change.

---

## Testing Strategy

### Unit Tests

Test domain aggregates in isolation:

```php
public function test_membership_application_submit_fires_event(): void
{
    $app = MembershipApplication::submit(
        id: $id,
        reason: ApplicationReason::RESIDENCE,
        isEligible: true,
    );

    $this->assertEquals(ApplicationStatus::SUBMITTED, $app->status());
    
    $events = $app->recordedEvents();
    $this->assertCount(1, $events);
    $this->assertInstanceOf(CommitteeMembershipApplicationSubmitted::class, $events[0]);
}
```

### Feature Tests

Test the full composition:

```php
public function test_organisation_dashboard_membership_widget_renders(): void
{
    $org = Organisation::factory()->create(['type' => 'tenant']);
    $member = User::factory()->create();
    UserOrganisationRole::create([
        'user_id' => $member->id,
        'organisation_id' => $org->id,
        'role' => 'member',
    ]);

    $response = $this->actingAs($member)
        ->get(route('organisations.show', $org->slug));

    $response->assertStatus(200)
        ->assertInertia(fn ($page) => 
            $page->component('Organisations/Show')
                ->has('membership')
        );
}
```

### Test Isolation

Use **stubs** during development, **real implementations** in production:

**F1 (Stubs):**
- Fast tests (no database queries)
- Safe defaults (no active applications)
- Focus on composition logic

**F2 (Real):**
- Database-backed (integration tests)
- Actual eligibility computed
- Volumes of test data possible

**Service Provider binds based on environment:**
```php
if (app()->isProduction()) {
    $this->app->bind(..., EloquentCommitteeMembershipApplicationRepository::class);
} else {
    $this->app->bind(..., StubMembershipApplicationRepository::class);
}
```

---

## Extending the System

### Adding a New Application Status

1. **Enum:** `app/Contexts/Membership/Domain/Membership/ValueObjects/ApplicationStatus.php`
```php
enum ApplicationStatus: string {
    case SUBMITTED = 'submitted';
    case UNDER_REVIEW = 'under_review';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case WITHDRAWN = 'withdrawn';  // ← New
}
```

2. **Database Migration:**
```php
// Update column check constraint if using strict enums
// Or allow 'withdrawn' in validation
```

3. **Domain Logic:** Add method to aggregate if needed:
```php
public function withdraw(MemberId $withdrawnBy): void {
    if (!$this->status->canTransitionTo(ApplicationStatus::WITHDRAWN)) {
        throw new DomainException("Cannot withdraw from {$this->status->value}");
    }
    $this->status = ApplicationStatus::WITHDRAWN;
    // ... fire event ...
}
```

4. **Test:**
```php
public function test_submitted_application_can_be_withdrawn(): void {
    $app = MembershipApplication::submit(...);
    $app->withdraw($memberId);
    $this->assertEquals(ApplicationStatus::WITHDRAWN, $app->status());
}
```

### Adding Member Geographic Context to Existing Users

1. **Create migration:**
```php
Schema::table('users', function (Blueprint $table) {
    // Column already exists from F2, just backfill
});
```

2. **Backfill data:**
```php
User::whereNull('residence_geo_unit_id')
    ->chunk(100, function ($users) {
        foreach ($users as $user) {
            // Determine geo_unit_id from user.state or other field
            $geoUnitId = GeoAdministrativeUnit::where('name', $user->state)
                ->value('id');
            $user->update(['residence_geo_unit_id' => $geoUnitId]);
        }
    });
```

3. **Test:** Verify `SessionMemberGeoPathProvider` resolves correctly:
```php
$this->assertEquals('/1/5/12/42', $provider->resolveForMember($memberId, $tenantId)->path);
```

### Adding New Eligibility Rule

1. **Domain Policy:** `app/Contexts/Membership/Domain/Committee/Policies/CommitteeEligibilityPolicy.php`
```php
public function isEligible(...): bool {
    // Existing checks
    
    // New check: Committee has open enrollment period
    if (!$this->isEnrollmentOpen($committee)) {
        return false;
    }
    
    return true;
}

private function isEnrollmentOpen(Committee $committee): bool {
    // Business logic
}
```

2. **Test:**
```php
public function test_closed_enrollment_committee_ineligible(): void {
    $committee = Committee::factory()
        ->create(['enrollment_open' => false]);
    
    $this->assertFalse(
        $policy->isEligible($member, $committee, $memberPath, $committeePath)
    );
}
```

3. **No controller/view changes** — composition layer already wired.

---

## Troubleshooting

### Issue: Membership widget shows empty array

**Check:**
1. User has `residence_geo_unit_id` set? `User::find(1)->residence_geo_unit_id`
2. Geo unit exists? `GeoAdministrativeUnit::find($id)`
3. Service provider bindings correct? Check `MembershipServiceProvider::register()`
4. Query service returns data? Run unit test on `MyCommitteesQueryService`

### Issue: "Geo unit not found" error

**Cause:** Committee or member geo unit ID doesn't exist in `geo_administrative_units` table.

**Fix:**
```php
// Backfill missing geo units
Committee::whereNull('geo_unit_id')
    ->update(['geo_unit_id' => 1]);  // Default to root
```

### Issue: Pending applications not showing

**Check:**
1. Records in `committee_membership_applications` table?
   ```sql
   SELECT * FROM committee_membership_applications 
   WHERE organisation_id = ? AND status IN ('submitted', 'under_review');
   ```
2. Repository query correct? Run `existsActiveForTenant()` test.
3. Service provider binding correct? Is `EloquentCommitteeMembershipApplicationRepository` bound?

---

## Summary

**Phase F1 & F2 deliver:**
- ✅ Complete membership eligibility widget on organisation dashboard
- ✅ Real database-backed application tracking (F2)
- ✅ Geographic eligibility computation with real data (F2)
- ✅ Clean composition layer decoupled from infrastructure
- ✅ TDD-validated with comprehensive unit + feature tests
- ✅ Multi-tenant isolation hardened at query level
- ✅ Domain aggregates remain pure, testable, event-sourced

**Key files to understand:**
1. `app/Http/Controllers/OrganisationController.php` — Composition orchestration
2. `app/Contexts/Membership/Application/Membership/Query/MyCommitteesQueryService.php` — Phase C eligibility engine
3. `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeMembershipApplicationRepository.php` — F2 persistence
4. `app/Contexts/Membership/Infrastructure/Query/SessionMemberGeoPathProvider.php` — F2 member geo resolution
5. `resources/js/Pages/Organisations/Partials/MembershipWidget.vue` — UI layer

**Next steps:** F3 would add application submission UI, approval workflow, and real-time notifications.

