# Architecture Overview

## 🏛️ Layered Architecture

The Membership Context follows **Clean Architecture** with strict layer separation:

```
┌─────────────────────────────────────────────────────────────────┐
│                     HTTP LAYER (Controllers)                      │
│  Entry point: receives request, calls use case, renders response │
│  Framework: Full Laravel allowed (Route binding, Middleware, etc) │
└─────────────────────────┬───────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│               APPLICATION LAYER (Use Cases)                      │
│  Orchestration: coordinates domain + infrastructure              │
│  Framework: Limited (DTOs required, no Facades, constructor DI)  │
│  Owns: Transaction boundaries, event dispatch, error handling    │
└─────────────────────────┬───────────────────────────────────────┘
                          │
                 ┌────────┴────────┐
                 ▼                 ▼
    ┌──────────────────┐  ┌──────────────────┐
    │  DOMAIN LAYER    │  │ SERVICE LAYER    │
    │  (Business Rules)│  │ (Anti-Corruption)│
    │                  │  │                  │
    │ - Committee      │  │ - Geography      │
    │ - Member         │  │   Resolver       │
    │ - Assignment     │  │ - Identity       │
    │ - Strategies     │  │   Verification   │
    │ - Events         │  │ - User           │
    │ - Exceptions     │  │   Provisioning   │
    └─────────────────┘  └──────────────────┘
    (Zero Framework)     (Pure Interfaces)
                          │
                          ▼
┌─────────────────────────────────────────────────────────────────┐
│            INFRASTRUCTURE LAYER (Implementation)                  │
│  Technical: Eloquent models, repositories, service adapters     │
│  Framework: Full Laravel allowed (Facades, Models, Services)    │
└─────────────────────────────────────────────────────────────────┘
```

---

## 📁 Directory Structure

```
app/Contexts/Membership/
│
├── Domain/                          # Layer 1: Business Logic (Pure PHP)
│   ├── Committee/
│   │   ├── Committee.php            # Aggregate root: core business logic
│   │   ├── CommitteeAssignment.php  # Owned entity: member in committee
│   │   └── Strategies/              # Strategy pattern per type
│   │       ├── CommitteeStructure.php        # Interface
│   │       ├── CentralCommitteeStructure.php
│   │       ├── GeographicCommitteeStructure.php
│   │       ├── YouthWingStructure.php
│   │       ├── WomenWingStructure.php
│   │       ├── StudentWingStructure.php
│   │       └── GenericCommitteeStructure.php
│   │
│   ├── ValueObjects/                # Immutable domain concepts
│   │   ├── CommitteeId.php
│   │   ├── CommitteeType.php
│   │   ├── CommitteeName.php
│   │   ├── CommitteeStatus.php
│   │   ├── RolePath.php             # Dot-notation role hierarchy
│   │   ├── NominationType.php
│   │   ├── GeoReference.php         # Geographic location
│   │   ├── TenantId.php             # Organization ID
│   │   ├── MemberId.php
│   │   ├── Email.php
│   │   └── ... (20+ value objects)
│   │
│   ├── Repositories/                # Persistence contracts (interfaces only)
│   │   ├── CommitteeRepositoryInterface.php
│   │   ├── MemberRepositoryInterface.php
│   │   └── TenantUserRepositoryInterface.php
│   │
│   ├── Services/                    # Domain service contracts (pure interfaces)
│   │   ├── GeographyResolverInterface.php
│   │   ├── GeographyLookupInterface.php
│   │   ├── IdentityVerificationInterface.php
│   │   └── TenantUserProvisioningInterface.php
│   │
│   ├── Events/                      # Domain events (state changelog)
│   │   ├── CommitteeFormed.php
│   │   ├── CommitteeMemberAssigned.php
│   │   ├── CommitteeMemberRemoved.php
│   │   ├── CommitteeMemberRoleUpdated.php
│   │   └── ... (member events, etc)
│   │
│   ├── Exceptions/                  # Business rule exceptions
│   │   ├── CommitteeNotFoundException.php
│   │   ├── InvalidGeographyException.php
│   │   └── InvalidMemberGeographyException.php
│   │
│   └── Traits/
│       └── RecordsEvents.php        # Trait for recording events
│
├── Application/                     # Layer 2: Use Cases & Orchestration
│   ├── Committee/
│   │   ├── GetCommitteeDashboard.php      # Query use case
│   │   ├── CreateCommittee.php             # Command use case
│   │   ├── AssignMemberToCommittee.php     # Command use case
│   │   ├── Views/
│   │   │   └── CommitteeDashboardView.php # View model
│   │   └── DTOs/
│   │       ├── CreateCommitteeCommand.php
│   │       └── AssignMemberDto.php
│   │
│   ├── Services/                    # Application orchestration services
│   │   ├── DesktopMemberRegistrationService.php
│   │   ├── MobileMemberRegistrationService.php
│   │   └── ... (registration, approval, rejection, import)
│   │
│   ├── DTOs/                        # Data transfer objects (no arrays!)
│   │   ├── CreateCommitteeCommand.php
│   │   ├── AssignMemberDto.php
│   │   └── ... (20+ DTOs)
│   │
│   ├── Handlers/                    # Command handlers
│   │   └── RegisterMemberHandler.php
│   │
│   ├── Jobs/                        # Async jobs
│   │   └── InstallMembershipModule.php
│   │
│   └── Requests/                    # Input validation
│       └── CreateMemberRequest.php
│
└── Infrastructure/                  # Layer 3: Technical Implementation
    ├── Http/
    │   ├── Controllers/
    │   │   ├── Desktop/
    │   │   │   ├── MemberController.php
    │   │   │   ├── MemberApprovalController.php
    │   │   │   └── MemberImportController.php
    │   │   └── Mobile/
    │   │       └── MemberController.php
    │   │
    │   ├── Requests/                # Form request validation
    │   │   ├── Desktop/
    │   │   └── Mobile/
    │   │
    │   └── Resources/               # JSON serialization
    │       ├── DesktopMemberResource.php
    │       └── MobileMemberResource.php
    │
    ├── Models/                      # Eloquent models
    │   └── CommitteeModel.php       # With BelongsToTenant scope
    │
    ├── Repositories/                # Repository implementations
    │   ├── EloquentCommitteeRepository.php
    │   ├── EloquentMemberRepository.php
    │   └── EloquentTenantUserRepository.php
    │
    ├── Services/                    # Technical adapters & implementations
    │   ├── GeographyValidationAdapter.php  # Anti-corruption layer
    │   ├── TenantAuthProvisioningAdapter.php
    │   ├── TenantUserIdentityVerification.php
    │   └── CsvParser.php
    │
    ├── Casts/                       # Eloquent attribute casts
    │   ├── MemberIdCast.php         # Casts member_id to MemberId VO
    │   ├── MemberStatusCast.php
    │   └── PersonalInfoCast.php
    │
    ├── Database/
    │   ├── Migrations/
    │   │   ├── 2026_01_02_140853_create_members_table.php
    │   │   ├── 2026_01_16_000002_create_committees_table.php
    │   │   ├── 2026_01_16_000003_create_committee_assignments_table.php
    │   │   └── ... (6 total migrations)
    │   │
    │   └── Seeders/
    │       └── MembershipDatabaseSeeder.php
    │
    └── Providers/
        └── MembershipServiceProvider.php   # Bindings, migrations, commands
```

---

## 🔄 Request Flow: Creating a Committee

```
1. HTTP LAYER
   ┌──────────────────────────────────────────┐
   │ POST /committees                          │
   │ CommitteeController::store()              │
   │ - Receives HTTP request                  │
   │ - Gets TenantId from TenantContext       │
   │ - Creates CreateCommitteeCommand DTO     │
   └──────────┬───────────────────────────────┘
              │
2. APPLICATION LAYER
   │  ┌──────────────────────────────────────────┐
   │  │ app(CreateCommittee::class)->execute()   │
   │  │ - Validates DTO                          │
   │  │ - Starts DB::transaction()               │
   │  │ - Calls domain layer                     │
   │  │ - Dispatches domain events               │
   │  │ - Returns CommitteeId                    │
   │  └──────────┬───────────────────────────────┘
   │             │
   │  3. DOMAIN LAYER
   │     ┌──────────────────────────────────────────┐
   │     │ Committee::form(                          │
   │     │   type: CommitteeType::central(),         │
   │     │   name: CommitteeName::of('...'),         │
   │     │   tenantId: $tenantId                     │
   │     │ )                                         │
   │     │ - Pure business logic                    │
   │     │ - Validates geography rules              │
   │     │ - Records CommitteeFormed event          │
   │     │ - Returns aggregate                      │
   │     └──────────┬───────────────────────────────┘
   │                │
   │  4. INFRASTRUCTURE LAYER (called from Application)
   │     ┌──────────────────────────────────────────┐
   │     │ $this->committees->saveForTenant(        │
   │     │   $committee, $tenantId                  │
   │     │ )                                        │
   │     │ - Converts Committee to Eloquent model   │
   │     │ - Saves to database with tenant_id      │
   │     │ - Returns                                │
   │     └──────────┬───────────────────────────────┘
   │                │
   └────────────────┼────────────────────────────────
                    │
   5. EVENT DISPATCH (Application layer, inside transaction)
   ┌───────────────────────────────────────────────┐
   │ $this->eventBus->dispatchAll(                 │
   │   $committee->pullEvents()                    │
   │ )                                             │
   │ → CommitteeFormed event listener reacts      │
   └───────────────────────────────────────────────┘
```

---

## 🛡️ Layer Separation Rules

### Domain Layer (Pure PHP, Zero Framework)

**Allowed:**
- Classes, enums, traits, interfaces
- Value Objects, Entities, Aggregates
- Business logic, validation, rules
- Exceptions, Events
- Constructor dependency injection (on interfaces only)

**NOT Allowed:**
- Laravel Facades (Cache, Log, etc.)
- Eloquent Models
- Laravel helpers (env(), route(), etc.)
- Database queries
- HTTP requests
- Framework annotations

```php
// ✅ CORRECT: Domain layer
namespace App\Contexts\Membership\Domain\Committee;

final class Committee {
    public function assignMember(
        MemberId $memberId,
        RolePath $rolePath,
        NominationType $nominationType,
        ?GeoReference $memberGeography = null
    ): CommitteeAssignment {
        // Pure PHP: validation, rules, event recording
        if ($this->type->isCentral() && $memberGeography !== null) {
            throw new InvalidMemberGeographyException('Central committees cannot be geographic');
        }
        
        // Strategy validates role limits
        $this->structure->validateRoleAssignment($rolePath);
        
        // Create assignment and record event
        $assignment = CommitteeAssignment::create(...);
        $this->recordEvent(new CommitteeMemberAssigned(...));
        
        return $assignment;
    }
}

// ❌ WRONG: Framework code in domain
public function assignMember(...) {
    Cache::get(...);         // NO! Framework
    env('MAX_ROLES');        // NO! Framework
    Log::info(...);          // NO! Framework
    DB::table('members')...  // NO! Database
}
```

### Application Layer (Limited Framework)

**Allowed:**
- Use cases (Command/Query handlers)
- DTOs for data transfer
- Application services (orchestration)
- Constructor dependency injection
- Call domain layer
- Call infrastructure repositories
- Own transaction boundaries
- Dispatch domain events

**NOT Allowed:**
- Eloquent Models directly
- Laravel Facades (except in specific cases)
- Route model binding
- Direct database queries
- Rendering responses (that's HTTP layer)

```php
// ✅ CORRECT: Application layer
namespace App\Contexts\Membership\Application\Committee;

final class CreateCommittee {
    public function __construct(
        private readonly CommitteeRepositoryInterface $committees,
        private readonly EventBus $eventBus
    ) {}
    
    public function execute(CreateCommitteeCommand $command): CommitteeId {
        // Own the transaction boundary
        return DB::transaction(function () use ($command) {
            // Call domain layer
            $committee = Committee::form(
                type: $command->type,
                name: CommitteeName::of($command->name),
                code: CommitteeCode::of($command->code),
                tenantId: $command->tenantId,
                geoReference: $command->geoReference
            );
            
            // Call infrastructure layer (repository)
            $this->committees->saveForTenant($committee);
            
            // Dispatch events (after transaction success)
            $this->eventBus->dispatchAll($committee->pullEvents());
            
            return $committee->getId();
        });
    }
}

// ❌ WRONG: Rendering in application layer
public function execute(...) {
    return view('committee.show', $data); // NO! That's HTTP layer
}

// ❌ WRONG: Direct Eloquent in application layer
$model = CommitteeModel::create(...); // NO! Use repository
```

### Infrastructure Layer (Full Laravel Allowed)

**Allowed:**
- Eloquent Models with scopes
- Repository implementations (using Eloquent)
- Laravel Facades
- HTTP Controllers, Form Requests, Resources
- Database queries (via Eloquent)
- Service adapters
- Job classes
- Event listeners

**Still NOT Allowed:**
- Business logic (that belongs in Domain)
- Direct transaction management in repositories (that's Application)

```php
// ✅ CORRECT: Infrastructure layer
namespace App\Contexts\Membership\Infrastructure\Repositories;

final class EloquentCommitteeRepository implements CommitteeRepositoryInterface {
    public function saveForTenant(Committee $committee): void {
        // Use Eloquent freely
        CommitteeModel::updateOrCreate(
            ['id' => $committee->getId()->value()],
            [
                'name' => $committee->getName()->value(),
                'type' => $committee->type()->value(),
                'tenant_id' => $committee->getTenantId()->value(),
                // ... other fields
            ]
        );
        // DON'T dispatch events here — that's application layer
        // DON'T own transaction — that's application layer
    }
    
    public function findForTenant(CommitteeId $id, TenantId $tenantId): ?Committee {
        // GlobalScope already filters by tenant
        $model = CommitteeModel::find($id->value());
        return $model ? $this->reconstitute($model) : null;
    }
}
```

---

## 🔌 Dependency Injection

### The Rule: Depend on Interfaces, Not Implementations

```php
// Application layer depends on domain interface
final class CreateCommittee {
    public function __construct(
        private readonly CommitteeRepositoryInterface $committees, // Interface!
        private readonly EventBus $eventBus                        // Interface!
    ) {}
}

// Controller depends on application interface / use case
final class CommitteeDashboardController {
    public function __construct(
        private readonly GetCommitteeDashboard $useCase,          // Use case class
        private readonly TenantContextInterface $tenantContext    // Interface!
    ) {}
}
```

### Service Provider: Wiring It All Together

```php
// app/Contexts/Membership/Infrastructure/Providers/MembershipServiceProvider.php

public function register(): void {
    // Bind domain interfaces to implementations
    $this->app->bind(
        CommitteeRepositoryInterface::class,
        EloquentCommitteeRepository::class
    );
    
    // Bind use cases (with their dependencies)
    $this->app->bind(CreateCommittee::class, function ($app) {
        return new CreateCommittee(
            $app->make(CommitteeRepositoryInterface::class),
            $app->make(EventBus::class)
        );
    });
}

public function boot(): void {
    // Load migrations
    $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/Tenant');
}
```

---

## 🧪 Cross-Layer Interaction Pattern

### Incoming Request (HTTP → Domain)

```
HTTP Controller
    ↓
    Uses TenantContext to get TenantId
    ↓
    Creates DTO (FormRequest → AssignMemberDto)
    ↓
    Calls Application Use Case
    ↓
    Use Case:
        - Calls Repository (Infrastructure)
        - Repository returns Aggregate (Domain)
        - Use Case calls methods on Aggregate (Domain logic)
        - Aggregate validates and changes state (pure business rules)
        - Use Case saves changes via Repository
        - Use Case dispatches events (Infrastructure listeners)
    ↓
    Controller returns Inertia response
```

### Outgoing Event (Domain → External System)

```
Domain Aggregate records event
    ↓
    Application pulls events from aggregate
    ↓
    Application dispatches via EventBus
    ↓
    Infrastructure listener receives event
    ↓
    Listener performs side effect:
        - Send email
        - Call external API
        - Update cache
        - Log audit trail
```

---

## 📊 Data Flow Diagram

```
┌──────────────────────────────────────────────────────────────┐
│                       HTTP REQUEST                            │
│              POST /committees (JSON payload)                  │
└────────────────────┬─────────────────────────────────────────┘
                     │
                     ▼
        ┌────────────────────────┐
        │ CommitteeController    │
        │ .store()               │
        │                        │
        │ 1. Get TenantId        │
        │ 2. Create DTO          │
        │ 3. Call use case       │
        └────────┬───────────────┘
                 │
                 ▼
   ┌─────────────────────────────────┐
   │ CreateCommittee (Use Case)      │
   │                                 │
   │ 1. Start DB::transaction()      │
   │ 2. Call domain layer            │
   │ 3. Call repository (infra)      │
   │ 4. Dispatch events              │
   │ 5. Commit transaction           │
   └────────┬────────────────────────┘
            │
    ┌───────┴───────────────────────────┐
    ▼                                   ▼
┌───────────────────┐    ┌──────────────────────────┐
│ Committee (Domain)│    │ EloquentCommitteeRepo    │
│                   │    │ (Infrastructure)         │
│ - Validate rules  │    │                          │
│ - Check strategy  │    │ - Convert to Eloquent    │
│ - Record events   │    │ - Save to database       │
│                   │    │ - Query with GlobalScope │
└─────────┬─────────┘    └──────────────────────────┘
          │
          ▼
    ┌─────────────────────────────────────────┐
    │ Domain Events Recorded                  │
    │ - CommitteeFormed                       │
    │ - (other events)                        │
    └────────────┬────────────────────────────┘
                 │
                 ▼
    ┌────────────────────────────────────────┐
    │ Events Dispatched (EventBus)           │
    │                                        │
    │ Listeners receive events:              │
    │ - Send notifications                   │
    │ - Update search index                  │
    │ - Log audit trail                      │
    │ - Trigger webhooks                     │
    └────────────────────────────────────────┘
```

---

## 🎯 Key Takeaways

| Layer | Purpose | Framework | Transaction | Events |
|-------|---------|-----------|-------------|--------|
| **HTTP** | Entry point, validation | Full | No | No |
| **Application** | Orchestration, rules | Limited | YES | YES |
| **Domain** | Business logic | None | No | Record (not dispatch) |
| **Infrastructure** | Technical details | Full | No | Listen & react |

**The Application layer is the engine:**
- It owns transactions
- It coordinates domain + infrastructure
- It dispatches events after success

---

**Next:** Read [02_DOMAIN_MODEL.md](./02_DOMAIN_MODEL.md) to understand Committee, Member, and Assignment
