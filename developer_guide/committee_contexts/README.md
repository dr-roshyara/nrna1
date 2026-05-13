# Committee Contexts — Developer Guide

## Overview

The committee system spans two bounded contexts following hexagonal (ports & adapters) architecture with CQRS:

```
Membership Context (Write Side)          Governance Context (Read Side)
┌─────────────────────────────┐          ┌──────────────────────────────┐
│                             │          │                              │
│  Domain Events ─────────────┼──────────┼──▶ Projector → Interpreters  │
│                             │          │         ↓                    │
│  Committee Aggregate        │          │   Projection Tables          │
│  Committee Structure        │          │         ↓                    │
│  Committee Assignments      │          │   Query Path → API           │
│                             │          │                              │
└─────────────────────────────┘          └──────────────────────────────┘
```

**Golden Rule:** Domain events flow left-to-right. Governance reads projected state, never domain aggregates.

---

## ⚠️ Current Architecture Decision

**See:** [ADR: Dual Committee Creation Paths](./ADR_COMMITTEE_CREATION_DUAL_PATH.md)

The codebase contains two committee creation implementations during a transition period:
- **Legacy path** (currently active): Structure-based validation, operational Committee aggregate
- **New canonical path** (being introduced): Matrix-based GovernancePolicy, event-sourced ConstitutionalCommittee

Both paths coexist during Phase 1 migration. The legacy path is marked `@deprecated` and will be removed in v3.0 after all consumers are migrated to the new path. See the ADR for the full deprecation strategy, feature parity assessment, and migration timeline.

---

## 1. Bounded Contexts

### 1.1 Membership Context (Write)

`app/Contexts/Membership/Domain/Committee/` — Pure PHP domain model with:

| Concept | Location | Purpose |
|---------|----------|---------|
| `Committee` aggregate | `Domain/Committee/Committee.php` | Core committee entity |
| `CommitteeAggregate` | `Domain/Committee/CommitteeAggregate.php` | Lifecycle & event recording |
| `CommitteeStructure` | `Domain/Committee/CommitteeStructure.php` | Structure template & levels |
| `CommitteeAssignment` | `Domain/Committee/CommitteeAssignment.php` | Member-to-committee assignment |
| `CapabilityPolicyEngine` | `Domain/Committee/Capability/` | Governance access control rules |
| `ConstitutionalArbitrationKernel` | `Domain/Committee/Constitutional/` | Constitutional compliance checks |
| `GeoAuthorityGraph` | `Domain/Committee/Geo/Graph/` | Geo-delegation authority graph |

### 1.2 Governance Context (Read)

`app/Contexts/Governance/` — Read-optimized projection system. No writes. Contains:

| Layer | Directory | Responsibility |
|-------|-----------|---------------|
| **Domain** | `Domain/` | Interpretation policies, value objects, events |
| **Application** | `Application/` | Use cases, DTOs, port interfaces |
| **Infrastructure** | `Infrastructure/` | Projectors, Eloquent models, repositories, migrations, clock |
| **API** | `API/V1/` | REST controllers, response DTOs, form requests, routes |

### 1.3 Layer Rules

```
Domain       → Pure PHP, zero Laravel dependencies
Application  → Limited Laravel (constructor injection only, no facades/eloquent)
Infrastructure → Laravel allowed freely
API          → Laravel allowed (controllers, form requests, responses)
```

---

## 2. CQRS Read-Side Architecture

### 2.1 Event Flow

```
Membership Domain Event
     │
     ▼
CommitteeGovernanceProjector.onEvent()      [Infrastructure/Projections/]
     │
     ├─ alreadyProcessed? ──yes──▶ (skip)
     │
     ▼ no
CommitteeGovernanceInterpreter.interpret()   [Domain/Committee/]
     │
     ├─ OperationalStatePolicy              [Domain/Committee/Policies/OperationalStatePolicy.php]
     ├─ TemporalGovernancePolicy            [Domain/Committee/Policies/TemporalGovernancePolicy.php]
     ├─ ConstitutionalLegitimacyPolicy      [Domain/Committee/Policies/ConstitutionalLegitimacyPolicy.php]
     │
     ▼
committee_governance_projections table      [Infrastructure/Database/Migrations/]
     │
     ▼
Query path → CommitteeHierarchyRepository   [Infrastructure/Repositories/]
     │
     ▼
CommitteeHierarchyBuilder → Tree output     [Application/Services/]
     │
     ▼
API Response DTOs → JSON                    [API/V1/Responses/]
```

### 2.2 Projection Table Schema

The `committee_governance_projections` table stores pre-computed governance state:

| Column | Source | Description |
|--------|--------|-------------|
| `committee_id` | Domain | ULID, primary key |
| `tenant_id` | Domain | Organisation scope |
| `operational_state` | OperationalStatePolicy | ACTIVE / INACTIVE / SUSPENDED / DISBANDED |
| `temporal_state` | TemporalGovernancePolicy | CURRENT / EXPIRED / FUTURE / NOT_YET_ACTIVE |
| `legitimacy` | ConstitutionalLegitimacyPolicy | CONSTITUTIONAL / PROVISIONAL / CONTESTED |
| `can_act` | TemporalGovernancePolicy | Boolean |
| `is_fully_operational` | Combined | Boolean |
| `projection_generation` | System | UUIDv7, changes per rebuild |
| `projection_schema_version` | System | Integer for migration tracking |

### 2.3 Interpretation Pipeline

Three independent policies, each evaluated in `CommitteeGovernanceInterpreter`:

```php
// Domain/Committee/CommitteeGovernanceInterpreter.php — simplified
$operational = $this->operationalPolicy->evaluate($facts);
$temporal    = $this->temporalPolicy->evaluate($facts, $now);
$legitimacy  = $this->legitimacyPolicy->evaluate($facts, $operational);

return new CommitteeGovernanceProjection(
    operationalState: $operational->state,
    temporalState: $temporal->state,
    legitimacy: $legitimacy->state,
    canAct: $temporal->canAct,
    isFullyOperational: $operational->isFullyOperational && $temporal->isCurrent,
);
```

---

## 3. API Layer (Phase 7)

### 3.1 Endpoints

All under `api/v1/governance` with rate limiting (100 req/min per tenant):

| Method | Path | Controller | Description |
|--------|------|------------|-------------|
| GET | `/hierarchy` | `GovernanceHierarchyController` | Full committee tree with filters |
| GET | `/committees/{id}` | `GovernanceCommitteeController::show` | Single committee state |
| GET | `/committees/{id}/children` | `GovernanceCommitteeController::children` | Direct children |
| GET | `/committees/{id}/governance` | `GovernanceCommitteeController::governance` | Governance status only |
| GET | `/health/projections` | `GovernanceHealthController::projections` | Projection freshness |

### 3.2 Hierarchy Query Parameters

| Param | Type | Default | Description |
|-------|------|---------|-------------|
| `depth` | int (1-5) | full tree | Max levels to expand |
| `root` | string | null | ULID of root committee |
| `search` | string | null | Partial name match |
| `state` | string | null | Filter by operational state |

### 3.3 Response DTOs

All response DTOs are `final readonly` classes implementing `JsonSerializable`. Located in `API/V1/Responses/`:

| DTO | Used By | Key Fields |
|-----|---------|------------|
| `CommitteeHierarchyResponse` | `/hierarchy` | id, name, level, children[], governance |
| `CommitteeSummaryResponse` | `/committees/{id}/children` | id, name, level, operationalState, canAct |
| `CommitteeGovernanceResponse` | `/committees/{id}/governance`, `/committees/{id}` | operationalState, temporalState, legitimacy, canAct |
| `CommitteeChildrenResponse` | `/committees/{id}/children` | committee (summary), children[], total |
| `GovernanceHealthResponse` | `/health/projections` | status, projectionGeneration, totalCommittees |
| `ErrorResponse` | All 4xx/5xx | code, message, status, traceId |

### 3.4 Error Contract

All errors follow a consistent envelope:

```json
{
    "error": {
        "code": "COMMITTEE_NOT_FOUND",
        "message": "Committee not found with ID: abc-123",
        "status": 404,
        "traceId": "req-abc-123"
    }
}
```

| HTTP | Code | When |
|------|------|------|
| 400 | `VALIDATION_ERROR` | Invalid query params |
| 404 | `COMMITTEE_NOT_FOUND` | ID not found in projection |
| 409 | `PROJECTION_STALE` | Rebuild in progress |
| 429 | `RATE_LIMITED` | Too many requests |
| 500 | `INTERNAL_ERROR` | Unexpected failure |

### 3.5 Controller Architecture

Controllers depend ONLY on `CommitteeHierarchyQueryInterface`, following the Dependency Inversion Principle:

```php
final class GovernanceHierarchyController
{
    public function __construct(
        private readonly CommitteeHierarchyQueryInterface $hierarchyQuery,
    ) {}
}
```

This interface is implemented by `GetCommitteeHierarchy` (the use case) and bound in `GovernanceServiceProvider`.

---

## 4. Tree Building

`CommitteeHierarchyBuilder` converts flat projection records into a nested tree:

```php
// Input:  [RootRecord, ChildRecord, GrandchildRecord]   (flat array)
// Output: [RootNode → [ChildNode → [GrandchildNode]]]   (nested tree)
```

### Algorithm

1. **Group** records by `parentId` into a children map
2. **Filter** by `search` (name match) and `state` (operational state)
3. **Identify roots** — records with `parentId === null`, or a specific root ULID via `?root=`
4. **DFS** from roots to max `depth`, building `CommitteeHierarchyResponse` nodes

### Known Invariant

Root IDs must be extracted from records using `$r->id->value()`, not numeric array keys. The recursive call in `buildNodes()` (line 101) does this correctly; the flat-to-root conversion at line 71 uses `array_map(fn($r) => $r->id->value(), ...)` for the same reason.

---

## 5. Projection Rebuild System

### CLI Command

```bash
php artisan governance:rebuild-projections           # Full rebuild (all tenants)
php artisan governance:rebuild-projections --tenant=org-123  # Single tenant
php artisan governance:rebuild-projections --dry-run         # Preview only
php artisan governance:rebuild-projections --force           # Skip lock
```

### Locking

Only one rebuild per tenant at a time. Uses `Cache::lock()` with 600s TTL:

```php
// Lock key: projection_rebuild_lock:{tenantId}
// Auto-releases on process crash after TTL expiry
```

### Determinism

Rebuilds use an injected `GovernanceClock` (SystemClock in production, FixedClock in tests) so the same events + same clock always produce the same projection state.

### Failure Isolation

Each committee rebuild is wrapped in try/catch — one failure never aborts the full rebuild.

### Rebuild Tracking

`projection_rebuild_runs` table records every rebuild: generation UUIDv7, tenant scope, timestamps, status, counts (rebuilt/failed/error_log).

---

## 6. Architecture Fitness Tests

Located in `tests/Architecture/` — reflection-based tests that enforce bounded context purity:

| Test File | Rule | What It Checks |
|-----------|------|----------------|
| `GovernanceDomainPurityTest.php` | H6-04 | Query path classes don't import domain policies |
| `GovernanceApiPurityTest.php` | FE-01–FE-08 | Controllers only use query interface, responses are immutable, no Eloquent in DTOs |
| `CommitteeDomainPurityTest.php` | D1–D5 | Domain layer has zero Laravel imports |

Key API rules enforced:

| Code | Rule | Enforcement |
|------|------|-------------|
| FE-01 | Response DTOs don't expose Eloquent models | Reflection on `use` statements in Response/ directory |
| FE-02 | Controllers don't import domain policies | Reflection on `use` statements in Controllers/ |
| FE-03 | API responses are immutable | All `final readonly` classes |
| FE-04 | Controllers depend on use case interfaces only | Constructor parameter type checks |
| FE-08 | Query handlers interpretation-free | No Interpreter/Policy imports in query path |

---

## 7. Testing Strategy

### Test Layers

```
tests/
├── Unit/                           # Pure logic, no Laravel bootstrap
│   ├── Contexts/Governance/        # Domain, Application, API tests
│   │   ├── Domain/                 # Policies, ValueObjects, Events (fast)
│   │   ├── Application/            # Use cases, DTOs (fast)
│   │   └── API/V1/                 # Controllers, Responses (Laravel helpers OK)
│   └── Domain/Committee/           # Constitutional, Geo, Capability tests
├── Integration/                    # Database-backed tests
│   └── Contexts/Governance/        # Projector, Repository tests
├── Feature/                        # Full HTTP stack tests
│   ├── Committee/                  # Committee creation with geo selections
│   └── CommitteeStructure/         # Activation, persistence
└── Architecture/                   # Reflection-based fitness tests
    ├── GovernanceDomainPurityTest.php
    ├── GovernanceApiPurityTest.php
    └── CommitteeDomainPurityTest.php
```

### Running Tests

```bash
# All governance tests
php artisan test tests/Unit/Contexts/Governance/ tests/Architecture/Governance*

# API layer only
php artisan test tests/Unit/Contexts/Governance/API/ tests/Architecture/GovernanceApiPurityTest.php

# Domain policies
php artisan test tests/Unit/Contexts/Governance/Domain/Committee/Policies/

# Full committee suite
php artisan test tests/Unit/Contexts/Governance/ tests/Architecture/Governance*
```

### Testing Patterns

**Controllers** use `CommitteeHierarchyQueryInterface` mocked, with real `Request::create()` for FormRequest inputs:

```php
// ✅ Use real request instances, not mocks of FormRequest
$request = HierarchyQueryRequest::create('/api/v1/governance/hierarchy', 'GET', ['depth' => 0]);
$response = $this->controller->hierarchy($request, $this->tenantId);
$this->assertSame(200, $response->status());
```

**Controllers never use `response()` helper** — always `new JsonResponse(...)` to avoid Laravel container dependency in unit tests.

**Response DTOs** are tested as plain objects — no Laravel bootstrap needed:

```php
$response = new CommitteeGovernanceResponse(
    operationalState: 'ACTIVE',
    temporalState: 'CURRENT',
    legitimacy: 'CONSTITUTIONAL',
    canAct: true,
    isFullyOperational: true,
    termStart: null,
    termEnd: null,
    evaluatedAt: null,
    projectionGeneration: 'gen-001',
);
$data = $response->jsonSerialize();
$this->assertSame('ACTIVE', $data['operationalState']);
```

---

## 8. Key Architectural Rules (Summary)

| Rule | Description | Enforced By |
|------|-------------|-------------|
| H6-02 | Projections are eventually consistent | Code comment annotations |
| H6-03 | Projections are rebuildable (not source of truth) | Architecture, not code |
| H6-04 | Query path: no interpretation at request time | Architecture fitness tests |
| H6-05 | Rebuild locking per tenant | Cache::lock() |
| H6-06 | Rebuilds are deterministic | Injected GovernanceClock |
| H6-07 | Time-bounded locks (TTL 600s) | Cache::lock TTL |
| H6-08 | Failure isolation per committee | Try/catch per committee |
| H6-09 | Generation monotonicity (UUIDv7) | Generated per rebuild |
| H6-10 | Mixed generations visible during rebuild | Known limitation |
| FE-01 | No Eloquent in response DTOs | Architecture fitness tests |
| FE-02 | No domain policies in controllers | Architecture fitness tests |
| FE-03 | API responses are immutable | `final readonly` classes |
| FE-04 | Controllers use interface injection | Architecture fitness tests |
| FE-08 | Query handlers are interpretation-free | Architecture fitness tests |

---

## 9. File Map

```
app/Contexts/Governance/
├── API/V1/
│   ├── Controllers/
│   │   ├── GovernanceCommitteeController.php     # show, children, governance
│   │   ├── GovernanceHealthController.php        # projections
│   │   └── GovernanceHierarchyController.php     # hierarchy (tree)
│   ├── Requests/
│   │   └── HierarchyQueryRequest.php             # depth/search/state/root params
│   ├── Responses/
│   │   ├── CommitteeChildrenResponse.php
│   │   ├── CommitteeGovernanceResponse.php
│   │   ├── CommitteeHierarchyResponse.php         # Recursive tree node
│   │   ├── CommitteeSummaryResponse.php
│   │   ├── ErrorResponse.php                      # Standard error contract
│   │   └── GovernanceHealthResponse.php
│   └── Routes/
│       └── governance_v1.php                      # Route definitions
├── Application/
│   ├── Approval/                                  # Approval workflow engine
│   ├── Authority/                                 # Authority graph validation
│   ├── DTOs/
│   │   ├── CommitteeHierarchyRecord.php           # Flat projection row DTO
│   │   ├── CommitteeTreeNode.php
│   │   └── RebuildResult.php
│   ├── Ports/
│   │   ├── CacheInterface.php
│   │   ├── CommitteeGovernanceProjectorInterface.php
│   │   ├── CommitteeHierarchyQueryInterface.php   # Controller dependency
│   │   ├── CommitteeProjectionRebuildRepository.php
│   │   ├── GovernanceClock.php
│   │   ├── LockInterface.php
│   │   └── RebuildRunRepository.php
│   ├── Services/
│   │   ├── CommitteeHierarchyBuilder.php          # Flat → tree algorithm
│   │   └── GovernanceProjectionRebuilder.php      # Full/tenant rebuild
│   └── UseCases/
│       └── GetCommitteeHierarchy.php              # Implements QueryInterface
├── Domain/
│   ├── Approval/                                  # Approval events, policies, VOs
│   ├── Authority/                                 # Delegation, resolution, conflicts
│   ├── Committee/
│   │   ├── CommitteeGovernanceInterpreter.php     # Orchestrates policy evaluation
│   │   ├── Policies/
│   │   │   ├── ConstitutionalLegitimacyPolicy.php
│   │   │   ├── OperationalStatePolicy.php
│   │   │   └── TemporalGovernancePolicy.php
│   │   └── ViewModels/
│   │       └── CommitteeGovernanceProjection.php  # Pre-computed result
│   ├── Events/
│   ├── ValueObjects/                              # AuthorityChain, Legitimacy, etc.
│   └── GovernanceDecision.php
└── Infrastructure/
    ├── Clock/
    │   └── SystemClock.php
    ├── Database/Migrations/Landlord/
    │   ├── 2026_05_10_000004_create_projection_rebuild_runs_table.php
    │   └── 2026_05_10_000005_add_projection_freshness_metadata.php
    ├── Projections/
    │   ├── CommitteeGovernanceProjectionModel.php
    │   ├── CommitteeGovernanceProjector.php       # Event → projection
    │   └── ProcessedEventModel.php
    ├── Providers/
    │   └── GovernanceServiceProvider.php           # DI bindings
    └── Repositories/
        ├── CommitteeHierarchyRepository.php        # Query path
        ├── EloquentCommitteeProjectionRebuildRepository.php
        └── EloquentRebuildRunRepository.php
```
