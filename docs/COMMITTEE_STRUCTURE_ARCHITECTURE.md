# CommitteeStructure Aggregate Refactor — Architecture Decisions

**Last Updated:** 2026-05-06  
**Phase:** 2 (Domain Complete, Pre-Phase-3 Documentation)  
**Status:** Ready for Phase 3 Infrastructure — WITH CRITICAL DB CONSTRAINTS

---

## 1. Cross-Aggregate Consistency Rule (CRITICAL)

### "Only ONE ACTIVE Structure per Tenant"

This is **NOT a domain invariant** — it spans multiple aggregates.

**Enforcement hierarchy (apply ALL levels):**

#### 1. Database Constraint (MANDATORY)
```sql
CREATE UNIQUE INDEX uniq_active_structure_per_org
ON committee_structures (organisation_id)
WHERE status = 'active';
```

This is the **hard guarantee**. Without this, application bugs corrupt data.

#### 2. Application Orchestration (Safety layer)
```php
CommitteeStructureActivationService::activate(TenantId $tenant, CommitteeStructure $new)
{
    // Load current ACTIVE
    $current = $repo->findActiveByTenant($tenant);
    if ($current) {
        $current->deprecate();
        $repo->save($current);  // Commit before new
    }
    
    // Activate new one
    $new->activate();
    $repo->save($new);
}
```

Runs in database transaction. Ensures atomic state transition.

#### 3. Domain Service (Orchestration owner)
`CommitteeStructureActivationService` in domain layer handles this responsibility.

**Why this layering?**
- DB constraint = immunity to bugs
- App service = transaction safety
- Domain service = business logic clarity

---

## 2. Multi-Tenancy Strategy (Enforced at 4 layers)

### Decision: Single Database + Tenant ID

**Chosen Model:**
```
Single shared PostgreSQL database
├── organisation_id column on all tenant-aware tables
├── Global query scopes in models
└── Middleware enforces current_organisation_id context
```

**Rationale:**

| Model | Complexity | Cost | Isolation | Query Complexity | Chosen? |
|-------|-----------|------|-----------|------------------|---------|
| **Per-Tenant Schemas** | High | High | Excellent | Low | ❌ |
| **Per-Tenant Databases** | High | Very High | Perfect | Low | ❌ |
| **Single DB + Tenant ID** | Low | Low | Good (enforced) | Medium | ✅ |

**Why Single DB + Tenant ID:**
1. **Simplicity** — No database switching logic, no schema migrations per org
2. **Cost** — One database connection pool, one backup, one monitoring system
3. **Data Sharing** — Easier analytics across tenants if needed (reports, audit trails)
4. **Multi-Tenancy Safety** — Enforced at:
   - **Database layer** — Foreign keys require `organisation_id` match
   - **Model layer** — `BelongsToTenant` global scope filters all queries
   - **Middleware layer** — `TenantContext` middleware enforces `session('current_organisation_id')`
   - **Repository layer** — All queries must pass `TenantId` parameter

**Implementation:**
```php
// Model
class CommitteeStructure extends TenantAggregateRoot {
    protected static function booted(): void {
        static::addGlobalScope(new BelongsToTenant());
    }
}

// Query (always includes tenant)
$structure = CommitteeStructureRepository::findActiveByTenant($tenantId);

// Cannot escape tenant scoping
$structure = CommitteeStructure::find($id);  // ❌ Still scoped to current tenant
$structure = CommitteeStructure::withoutGlobalScopes()->find($id);  // ❌ Requires explicit intent (caught in code review)
```

**Threat Model Mitigation:**
- ✅ SQL injection doesn't cross tenants (parameterized queries + foreign keys)
- ✅ N+1 queries still filtered by tenant (scope is automatic)
- ✅ Bulk operations scoped to tenant (`CommitteeStructure::where('status', 'draft')` only returns current tenant's drafts)
- ✅ Tenant switching requires session mutation (defended by middleware)

---

## 2. Indexing Strategy for Performance

### Indexes to Create

All indexes are on the `committee_structures` and `committee_structure_levels` tables:

#### Primary Lookup (Most Frequent)
```sql
-- Find active structure for a tenant (CommitteeStructureRepository::findActiveByTenant)
CREATE INDEX idx_cs_org_status 
  ON committee_structures(organisation_id, status) 
  WHERE status = 'active';  -- Filtered index: only index active structures
```

**Why filtered:** Only one ACTIVE structure per org, so filtering at index time saves space and speeds writes to the table.

#### Secondary Lookups
```sql
-- List all structures for an org (for admin/dashboard)
CREATE INDEX idx_cs_organisation 
  ON committee_structures(organisation_id, created_at DESC);

-- Find deprecated structures (for archive/migration)
CREATE INDEX idx_cs_org_deprecated 
  ON committee_structures(organisation_id, status) 
  WHERE status = 'deprecated';
```

#### Level Lookups
```sql
-- Find level by structure + index (CommitteeStructure::getLevel)
CREATE UNIQUE INDEX idx_csl_structure_index 
  ON committee_structure_levels(committee_structure_id, level_index);

-- Find all levels for a structure (eager loading)
CREATE INDEX idx_csl_structure 
  ON committee_structure_levels(committee_structure_id);
```

#### Tenant Isolation Safety
```sql
-- Ensure no cross-tenant queries possible (defensive)
CREATE INDEX idx_csl_org_structure 
  ON committee_structure_levels(organisation_id, committee_structure_id);
```

### Index Summary

| Index | Table | Columns | Type | Why |
|-------|-------|---------|------|-----|
| `idx_cs_org_status` | `committee_structures` | `(org_id, status)` | **Filtered** | Find active structure per org |
| `idx_cs_organisation` | `committee_structures` | `(org_id, created_at)` | Regular | List structures for org |
| `idx_cs_org_deprecated` | `committee_structures` | `(org_id, status)` | **Filtered** | Archive queries |
| `idx_csl_structure_index` | `committee_structure_levels` | `(structure_id, level_index)` | **Unique** | Domain constraint |
| `idx_csl_structure` | `committee_structure_levels` | `(structure_id)` | Regular | Eager load levels |
| `idx_csl_org_structure` | `committee_structure_levels` | `(org_id, structure_id)` | Regular | Defensive tenant safety |

### Index Size Projection

Assuming 1000 organizations × 2-3 structures per org:
```
committee_structures:       ~2,000 rows (small, composite key fits in memory)
committee_structure_levels: ~20,000 rows (each structure has 1-10 levels)

Total index size: ~500 KB (negligible)
Query time: <1 ms for indexed lookups
```

---

## 3. Anti-Corruption Layer: GeoContextPort

### Problem
Committee Membership context needs to validate geographic references, but Geography is a separate context. Direct dependency would create:
- Circular dependencies
- Tight coupling
- Testing difficulties

### Solution: Hexagonal Architecture Port

```
Membership Domain
   ↓
   CommitteeCreationPolicy (domain service)
       ↓ uses
       GeoContextPort (interface/port)
           ↓ implemented by
Infrastructure Layer
   ↓
   MembershipGeoReferenceAdapter (ACL)
       ↓ delegates to
       GeographyService (Geography context)
```

### Port Definition

```php
// Domain layer (purely abstract)
interface GeoContextPort {
    public function validateGeoReferenceForScope(
        string $geoScope, 
        GeoReference $geoReference
    ): void;
    
    public function isScopeValid(string $geoScope): bool;
    public function getValidScopes(): array;
}
```

### Implementation (Phase 3 Infrastructure)

```php
// Infrastructure/Services/MembershipGeoReferenceAdapter.php
final class MembershipGeoReferenceAdapter implements GeoContextPort {
    public function __construct(private readonly GeographyService $geoService) {}
    
    public function validateGeoReferenceForScope(
        string $geoScope, 
        GeoReference $geoReference
    ): void {
        // Translate "province", "district" → GeographicLevelType enum
        $levelType = $this->mapScopeToLevelType($geoScope);
        
        // Delegate to Geography context
        $this->geoService->validateReference($geoReference, $levelType);
        
        // Translate exceptions from Geography domain to Membership domain
        // (handles exception mapping here)
    }
}

// Service container registration
$container->singleton(
    GeoContextPort::class,
    fn() => new MembershipGeoReferenceAdapter($this->get(GeographyService::class))
);
```

### Benefits

✅ **Decouples Contexts** — Membership doesn't know Geography implementation  
✅ **Testable** — Mock the port in unit tests  
✅ **Flexible** — Can swap adapter implementation (e.g., cache layer, different Geography provider)  
✅ **Explicit Contract** — Port clearly documents what Membership needs from Geography  

---

## 4. State Lifecycle Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                        CommitteeStructure Lifecycle              │
└─────────────────────────────────────────────────────────────────┘

  CREATED
     ↓
  ┌─────────────────────────────────────────────────────────┐
  │                        DRAFT                             │
  │   - Editable (not yet used)                              │
  │   - No committees can be created against it              │
  │   - Event: CommitteeStructureDefined emitted             │
  │   - Valid state: levels validated, invariants checked    │
  └─────────────────────────────────────────────────────────┘
     ↓ activate()
     │ Conditions:
     │ - Must be DRAFT
     │ - Must have at least one level
     │ - No structual issues
     │
  ┌─────────────────────────────────────────────────────────┐
  │                       ACTIVE                             │
  │   - Immutable (no edits allowed)                          │
  │   - Committees CAN be created against it                 │
  │   - Event: CommitteeStructureActivated emitted           │
  │   - Only ONE ACTIVE per tenant at a time                 │
  └─────────────────────────────────────────────────────────┘
     ↓ evolve(newLevels)
     │ Conditions:
     │ - Current must be ACTIVE
     │ - newLevels must pass validation
     │
     ├─→ (current structure) → DEPRECATED
     │                            │
     │                            └─ Old version, committees keep references
     │
     └─→ (new structure) → DRAFT ← Ready for next activation
          version++
          status=draft
```

### Key Invariants

1. **Only ONE ACTIVE per tenant** (enforced by application layer, Phase 4)
2. **Activation is irreversible** (no ACTIVE → DRAFT transition)
3. **Deprecation is automatic** (triggered by evolve(), not manual)
4. **Version increments on evolve** (tracks structure changes)
5. **Committees reference specific version** (via `committee_structure_id` + `level_index`)

---

## 5. Domain Model Summary

### Aggregates

**CommitteeStructure** (Aggregate Root)
- Tenant-scoped (multi-tenancy via TenantId)
- Identified by: CommitteeStructureId (ULID)
- Lifecycle: DRAFT → ACTIVE → DEPRECATED
- Events: CommitteeStructureDefined, CommitteeStructureActivated
- Contains: CommitteeLevel[] (value objects)

### Value Objects

**CommitteeLevel**
- Identified by: level_index (1-10, unique within structure)
- Fields: name, geoPolicy, geoScope, roleLimits, minMembershipYears, ageRange, genderRequirement
- Immutable, with validation at construction

**GeoPolicy** (Enum: NONE | REQUIRED | OPTIONAL)
- Determines whether level requires geographic reference
- Methods: requiresGeo(), validate()

**GeoScope** (Value Object)
- Single field: code (string, e.g., "province", "district")
- Represents administrative unit type
- Mapped to Geography context's GeoLevelType in infrastructure

### Domain Services

**CommitteeCreationPolicy**
- Validates cross-aggregate invariants
- Checks: structure is active, level exists, geoPolicy satisfied
- Uses: GeoContextPort (anti-corruption layer)
- Dependency: GeoContextPort (port, not concrete implementation)

---

## 6. Critical Rules for Phase 3+ Implementation

### Migration Phase (Phase 7)

1. ✅ **Test data safety** — Data migration runs in per-org transaction
2. ✅ **Backwards compat** — Old `type` column kept until `level_index` verified
3. ✅ **Dry-run first** — Command has `--dry-run` flag to preview changes
4. ✅ **Idempotent** — Command can be re-run safely

### Repository Phase (Phase 3)

1. ✅ **TenantId required** — All public methods require `TenantId` parameter
2. ✅ **Query scoping** — Global scope enforced, `withoutGlobalScopes()` explicitly flagged
3. ✅ **Event projection** — Events saved to event_log (CQRS for auditing)

### Application Layer (Phase 4)

1. ✅ **"Only one ACTIVE" rule** — Application layer enforces before `activate()`
2. ✅ **Use CommitteeCreationPolicy** — Every committee creation uses it
3. ✅ **Port injection** — Adapter passed to policy at construction

### Regression Prevention

All existing tests must remain GREEN:
- `CommitteeAssignmentTest`
- `CreateCommitteeTest` (will be refactored, not deleted)
- `CommitteeDashboardTest`
- `CommitteeManagementTest`

---

## 7. Architectural Improvements (Pre-Phase-3 Refinement)

### ✅ Split Brain Validation Fixed

**Before:**
- Structural validation split between `CommitteeStructure` (data) and `CommitteeCreationPolicy` (rules)

**After:**
- Core validation moved INTO aggregate: `CommitteeStructure::assertCanCreateCommittee(int $levelIndex)`
- Aggregate is now **behavioral**, not just a data holder
- `CommitteeCreationPolicy` reduced to **thin orchestrator** that:
  - Calls aggregate validation
  - Delegates geo validation to `GeoContextPort`
  - Keeps domain/infrastructure boundary clean

### ✅ Cross-Aggregate Invariant Enforced

**Invariant:** Only ONE ACTIVE structure per tenant

**Implementation:**
- `CommitteeStructureActivationService` (domain service, not aggregate)
- Loads current ACTIVE, deprecates it, activates new one
- Uses repository interface (dependency injection)
- Keeps aggregate boundaries clean

### ✅ Type Safety in Anti-Corruption Layer

**Before:**
```php
GeoContextPort::validateGeoReferenceForScope(string $geoScope, GeoReference $ref)
```

**After:**
```php
GeoContextPort::validateGeoReference(GeoScope $scope, GeoReference $ref)
```

- Uses strongly-typed `GeoScope` value object
- Prevents invalid scope strings
- Enforces domain language consistency

### ✅ Behavioral Aggregate (No Geo Leakage)

Added single orchestration method to `CommitteeStructure`:
```php
public function assertCanCreateCommittee(int $levelIndex): void
```

This enforces:
- Structure is ACTIVE
- Level exists and is valid
- Geo policy is satisfied

**Geo query responsibility stays with caller:**
```php
$level = $structure->getLevel($levelIndex);
if ($level->geoPolicy->requiresGeo()) { ... }
```

This keeps boundaries clean—structure doesn't leak geo concerns.

---

## 8. Architectural Corrections Applied (Pre-Phase-3)

### ✅ Correction 1: DB-level Consistency Enforcement
**Added:** Explicit section on "Only ONE ACTIVE per tenant"
- DB constraint: `UNIQUE (organisation_id) WHERE status = 'active'`
- Application orchestration: `CommitteeStructureActivationService`
- Domain service coordination: not cross-aggregate invariant logic
- **Why:** Without DB constraint, application bugs corrupt data

### ✅ Correction 2: No Geo Leakage from Aggregate
**Removed:** Helper methods that exposed geo concerns
- ❌ Deleted: `levelRequiresGeo()`, `getGeoScope()`
- ✅ Kept: `getLevel()` — caller accesses level properties directly

**Why:** Aggregate should not shape behavior for external contexts

### ✅ Correction 3: Policy Remains a Real Orchestrator
**Verified:** `CommitteeCreationPolicy` performs meaningful work
- Calls structure validation
- Checks geo requirements
- Delegates to geography ACL
- Not just pass-through

---

## 9. When to Move to Phase 3

✅ **All Prerequisites Met:**
- [x] 33 domain tests passing (11 + 10 + 5 + 7)
- [x] State machine fully tested (5 core + 2 strengthened)
- [x] Invariants fully tested (7 original + 3 core assertion)
- [x] Behavioral aggregate verified (no geo leakage)
- [x] Anti-corruption layer type-safe (GeoScope VO)
- [x] Multi-tenancy strategy documented (4 enforcement layers)
- [x] Indexing strategy defined
- [x] Cross-aggregate consistency rule explicit (DB + app)
- [x] Architectural commitment: meta-configurable system

**CRITICAL FIXES APPLIED:**
1. ✅ Split brain validation → consolidated in aggregate
2. ✅ Geo leakage → removed helper methods
3. ✅ Cross-aggregate rule → DB constraint + app service
4. ✅ Type safety → GeoScope VO throughout
5. ✅ Boundary clarity → no context leakage

**Next: Phase 3 Infrastructure (Persistence, Migrations, Eloquent Models)**

**Phase 3 Design Principles:**
- Composite FK: `(committee_structure_id, organisation_id)`
- CHECK: `organisation_id IS NOT NULL`
- Unique: `(organisation_id) WHERE status = 'active'`
- Repository: strict tenant isolation by design
- Migrations: safety-first with rollback capabilities

