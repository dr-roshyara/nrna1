# Membership Context Developer Guide

Welcome to the **Membership Context** documentation. This guide explains the architecture, domain model, and implementation patterns used for managing committees, members, and their relationships in the NRNA platform.

---

## 📚 Table of Contents

1. **[Architecture Overview](./01_ARCHITECTURE.md)** — High-level structure and layers
2. **[Domain Model](./02_DOMAIN_MODEL.md)** — Core concepts: Committee, Member, Assignment
3. **[Implementation Guide](./03_IMPLEMENTATION_GUIDE.md)** — How to build new features
4. **[Tenant Isolation](./04_TENANT_ISOLATION.md)** — Multi-tenancy patterns and safeguards
5. **[API Reference](./05_API_REFERENCE.md)** — Use cases, repositories, services
6. **[Testing Guide](./06_TESTING.md)** — Unit, integration, and feature tests
7. **[Common Workflows](./07_WORKFLOWS.md)** — Step-by-step feature examples
8. **[FAQ & Troubleshooting](./08_FAQ.md)** — Common issues and solutions

---

## 🎯 Quick Start

### What is the Membership Context?

The Membership Context is a **bounded context** (from Domain-Driven Design) that manages:

- **Committees**: Central, Province, District, Ward, Wings (Youth, Women, Student)
- **Members**: Party members with registration, approval, and lifecycle tracking
- **Assignments**: Member participation in committees with specific roles
- **Geography**: Regional assignment and validation (provinces, districts, wards)

### Key Technologies

- **Laravel 11** — Framework and ORM
- **Domain-Driven Design** — Architecture pattern
- **Tenant Isolation** — Multi-organization support
- **PostgreSQL** — Primary database
- **Vue.js 3 + Inertia** — Frontend components

---

## 🏛️ Core Concepts

### 1. **Aggregate Root: Committee**

The `Committee` class is an aggregate root managing all committee-related logic.

```php
// Domain layer - pure business logic, no framework
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;

$committee = Committee::form(
    CommitteeType::central(),
    CommitteeName::of('Central Executive Committee'),
    CommitteeCode::of('CENTRAL-001'),
    tenantId: $tenantId
);

// Assign member to committee with validation
$committee->assignMember(
    memberId: $memberId,
    rolePath: RolePath::chairperson(),
    nominationType: NominationType::elected(),
    memberGeography: null // null for central, required for geographic committees
);

// Domain events are recorded (not dispatched)
$events = $committee->pullEvents(); // [CommitteeFormed, CommitteeMemberAssigned]
```

### 2. **Value Objects: Immutable Domain Concepts**

Instead of strings and integers, we use strongly-typed Value Objects:

```php
// Not: $type = 'central'
// But:
$type = CommitteeType::central(); // Type-safe, with validation

// Not: $role = 'chairperson'
// But:
$role = RolePath::chairperson(); // Dot-notation: "1.0.0"

// Not: $geo = 'np.3.15'
// But:
$geo = GeoReference::fromString('np.3.15'); // Validated, with helper methods
```

### 3. **Strategy Pattern: Committee Types**

Each committee type has different rules (role limits, membership requirements, geography).

```
CommitteeType (enum: central, province, district, ward, youth, women, student)
         ↓
    CommitteeStructure (interface)
         ↓
    ├─ CentralCommitteeStructure (1 chairperson max, national only)
    ├─ GeographicCommitteeStructure (Province/District/Ward, geography required)
    ├─ YouthWingStructure (18-35 age check, optional geography)
    ├─ WomenWingStructure (women only, optional geography)
    └─ StudentWingStructure (student status, optional geography)
```

When assigning a member, the committee's strategy validates role limits and membership constraints.

### 4. **Tenant Isolation: Multi-Organization Safety**

Every committee belongs to exactly one `TenantId` (organization).

```php
// Tenant isolation is enforced at THREE levels:

// Level 1: Database column (hard isolation)
$committee->tenant_id // organisatons.id (UUID)

// Level 2: Eloquent GlobalScope (query scoping)
CommitteeModel::all(); // automatically filters by session('current_organisation_id')

// Level 3: Repository layer (explicit tenant parameter)
$repo->findForTenant($committeeId, $tenantId); // passes TenantId to query
```

**No committee can leak to another organization.**

---

## 📂 File Structure at a Glance

```
app/Contexts/Membership/
├── Domain/                          # Pure business logic, no Laravel
│   ├── Committee/                   # Aggregate: Committee, AssignmentId, Status
│   ├── Strategies/                  # Strategy pattern per committee type
│   ├── ValueObjects/                # CommitteeId, CommitteeType, RolePath, etc.
│   ├── Services/                    # Domain service interfaces (pure)
│   ├── Events/                      # Domain events
│   ├── Exceptions/                  # Business rule exceptions
│   └── Repositories/                # Repository interfaces (contracts)
│
├── Application/                     # Use cases, orchestration, limited Laravel
│   ├── Committee/
│   │   ├── GetCommitteeDashboard.php    # Query use case
│   │   ├── CreateCommittee.php          # Command use case
│   │   └── AssignMemberToCommittee.php  # Command use case
│   ├── DTOs/                        # Data transfer objects (no arrays)
│   └── Services/                    # Application services (coordinate domain + infrastructure)
│
└── Infrastructure/                  # Technical implementation, full Laravel allowed
    ├── Models/                      # Eloquent models (CommitteeModel)
    ├── Repositories/                # Repository implementations (Eloquent)
    ├── Http/                        # Controllers, form requests, resources
    ├── Services/                    # Technical services (adapters, parsers)
    └── Database/
        ├── Migrations/              # Schema changes
        └── Seeders/                 # Initial data

app/Shared/
├── Domain/Events/                   # AbstractDomainEvent base class
└── Infrastructure/Events/           # LaravelEventBus implementation

app/Contracts/
└── TenantContextInterface.php       # Tenant resolution contract

app/Services/
└── TenantContext.php               # TenantId resolver (HTTP session → auth user → explicit)
```

---

## 🚀 Quick Examples

### Create a Committee

```php
// In a controller or command handler
use App\Contexts\Membership\Application\Committee\CreateCommittee;
use App\Contexts\Membership\Application\Committee\DTOs\CreateCommitteeCommand;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;

$useCase = app(CreateCommittee::class);

$useCase->execute(new CreateCommitteeCommand(
    tenantId: TenantId::fromString('org-123'),
    type: CommitteeType::district(),
    name: 'Kathmandu District Committee',
    code: 'KATH-001',
    geoReference: GeoReference::fromString('np.3.15')
));

// Events are automatically dispatched to listeners
```

### Assign Member to Committee

```php
use App\Contexts\Membership\Application\Committee\AssignMemberToCommittee;
use App\Contexts\Membership\Application\Committee\DTOs\AssignMemberDto;

$useCase = app(AssignMemberToCommittee::class);

$useCase->execute(new AssignMemberDto(
    committeeId: CommitteeId::fromString('committee-001'),
    tenantId: TenantId::fromString('org-123'),
    memberId: MemberId::fromString('member-001'),
    rolePath: RolePath::chairperson(),
    nominationType: NominationType::elected()
));
```

### Query Dashboard Data

```php
use App\Contexts\Membership\Application\Committee\GetCommitteeDashboard;

$useCase = app(GetCommitteeDashboard::class);

$view = $useCase->execute(
    CommitteeId::fromString('committee-001'),
    TenantId::fromString('org-123')
);

// Returns CommitteeDashboardView with committee + sub-committees
return Inertia::render('Committee/Dashboard', $view->toArray());
```

---

## 🎓 Learning Path

**New to the codebase?** Follow this order:

1. Read **[02_DOMAIN_MODEL.md](./02_DOMAIN_MODEL.md)** — Understand Committee, Member, Assignment
2. Read **[04_TENANT_ISOLATION.md](./04_TENANT_ISOLATION.md)** — Understand multi-tenancy safety
3. Read **[03_IMPLEMENTATION_GUIDE.md](./03_IMPLEMENTATION_GUIDE.md)** — How to build features
4. Read **[05_API_REFERENCE.md](./05_API_REFERENCE.md)** — Look up classes and methods
5. Read **[06_TESTING.md](./06_TESTING.md)** — Write tests for new features
6. Study **[07_WORKFLOWS.md](./07_WORKFLOWS.md)** — Real examples end-to-end

**Experienced Laravel developer?** Jump to:
- **[03_IMPLEMENTATION_GUIDE.md](./03_IMPLEMENTATION_GUIDE.md)** — Layer responsibilities
- **[05_API_REFERENCE.md](./05_API_REFERENCE.md)** — Available interfaces and classes

---

## 📋 Documentation Files

| File | Purpose | Audience |
|------|---------|----------|
| `01_ARCHITECTURE.md` | Layer separation, folder structure, dependency flow | Architects |
| `02_DOMAIN_MODEL.md` | Committee aggregate, Value Objects, Strategies | Domain experts, developers |
| `03_IMPLEMENTATION_GUIDE.md` | How to add features, layer rules, patterns | Developers |
| `04_TENANT_ISOLATION.md` | Multi-tenancy guarantees, safety, testing | Security reviewers, developers |
| `05_API_REFERENCE.md` | Classes, methods, signatures, contracts | Developers looking up code |
| `06_TESTING.md` | Unit, integration, feature test patterns | QA, developers |
| `07_WORKFLOWS.md` | Complete examples: create committee, assign member | Beginners |
| `08_FAQ.md` | Common questions, troubleshooting, edge cases | Everyone |

---

## 🔧 Getting Help

### See a compile error?
→ Check **[08_FAQ.md](./08_FAQ.md)** or search for the class name in **[05_API_REFERENCE.md](./05_API_REFERENCE.md)**

### Don't understand the layer separation?
→ Read **[01_ARCHITECTURE.md](./01_ARCHITECTURE.md)** and **[03_IMPLEMENTATION_GUIDE.md](./03_IMPLEMENTATION_GUIDE.md)**

### Need to add a new feature?
→ Follow **[07_WORKFLOWS.md](./07_WORKFLOWS.md)** and **[03_IMPLEMENTATION_GUIDE.md](./03_IMPLEMENTATION_GUIDE.md)**

### Worried about tenant isolation?
→ Read **[04_TENANT_ISOLATION.md](./04_TENANT_ISOLATION.md)** and **[06_TESTING.md](./06_TESTING.md)**

---

## 🎯 Core Principles

1. **Domain Drives Design** — Business rules in pure PHP classes, not scattered in controllers or queries
2. **Clear Layer Boundaries** — Domain layer has zero framework dependencies; Infrastructure can use Laravel freely
3. **Tenant Safety by Default** — Every repository query automatically scoped to current tenant; impossible to leak data
4. **Strong Typing** — Value Objects instead of strings/integers; IDE autocomplete works
5. **Events as State Changelog** — Domain events record what happened; listeners react independently
6. **Strategy Pattern** — Committee types don't live in conditionals; each has its own class

---

## 📞 Questions?

- **Architecture questions** → Read `01_ARCHITECTURE.md`
- **Domain model questions** → Read `02_DOMAIN_MODEL.md`
- **"How do I...?"** → Check `07_WORKFLOWS.md` and `08_FAQ.md`
- **API questions** → Search `05_API_REFERENCE.md`
- **Test questions** → Read `06_TESTING.md`

---

**Last Updated:** May 3, 2026  
**Author:** Claude  
**Status:** Phase 2 Complete (Application Layer, DDD Architecture, Multi-Tenancy)
