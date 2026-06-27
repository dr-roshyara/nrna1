# Committee Formation — Complete Developer Guide

**Everything you need to know about the Geo×Governance matrix system.**

---

## What's in This Folder?

| Document | Purpose | Audience |
|----------|---------|----------|
| **GEO_GOVERNANCE_MATRIX.md** | Complete architecture & concepts | Everyone |
| **IMPLEMENTATION_GUIDE.md** | Step-by-step build instructions | Backend developers |
| **API_REFERENCE.md** | Method signatures & quick lookup | Developers coding |
| **README.md** | This file — orientation | Everyone |

---

## Five-Minute Overview

### The Problem

The voting platform needs to define which **governance levels** (hierarchy) can exist at which **geographic levels** (territory).

**Old way:** Hardcoded `Jurisdiction` enum (ward, district, province, national).  
**Problem:** Inflexible, 1:1 mapping, requires code changes for new rules.

### The Solution

A **2D policy matrix** where:
- One axis: Governance Level (0-10) — where in the hierarchy
- Other axis: Geo Level (0-10) — geographic unit level
- Each cell: Allowed or forbidden

Example:
```
                Geo Level
                0 1 2 3
Governance:
    0 (Central) ✓ ✓ ✓ ✓
    1 (Regional)  ✓ ✓
    2 (District)    ✓
```

### Benefits

✅ Fully configurable (change via database)  
✅ Independent axes (governance ≠ geography)  
✅ Expressive (any combination possible)  
✅ Type-safe (value objects, no strings)  
✅ Well-tested (TDD-first, 6/6 tests pass)

---

## Architecture at a Glance

```
┌────────────────────────────────────────────┐
│         Application Layer                   │
│  CreateCommitteeHandler ← Orchestration    │
│  CreateCommitteeCommand                    │
└─────────────────┬──────────────────────────┘
                  │ injects
                  ▼
┌────────────────────────────────────────────┐
│         Domain Layer (Pure PHP)             │
│                                            │
│  ConstitutionalCommittee ← Aggregate      │
│  ├─ establish(policy) ← Validation        │
│  ├─ recordEvent(CommitteeEstablished)    │
│                                            │
│  GovernancePolicy ← ENFORCEMENT GATE      │
│  ├─ assertAllowed(assignment)             │
│  └─ throws if invalid                     │
│                                            │
│  GovernanceMatrix ← Projection            │
│  ├─ isAllowed(gov, geo)                   │
│  ├─ allowedGovernanceLevelsForGeo()       │
│  └─ fromRows() ← bootstrap                │
│                                            │
│  MatrixCell ← Config unit                 │
│  GovernanceAssignment ← Data snapshot     │
└────────────────────────────────────────────┘
```

---

## Quick Start

### 1. Understand the Data Model

```php
// A committee's governance position
$assignment = new GovernanceAssignment(
    governanceLevel: 2,  // District level
    geoLevel: 2,         // In a district
    geoUnitId: GeoUnitId::fromInt(15),  // This specific district
);
```

### 2. Check if It's Allowed

```php
// Load the matrix (once per request, cache in production)
$matrix = GovernanceMatrix::fromRows(
    DB::table('governance_level_definitions')
        ->select('level', 'is_active')
        ->get()
        ->toArray()
);

// Create the policy enforcer
$policy = new GovernancePolicy($matrix);

// Check the assignment
$policy->assertAllowed($assignment);
// throws DomainException if not allowed
```

### 3. Create a Committee

```php
$handler = new CreateCommitteeHandler(
    $repository,
    $eventBus,
    $policy
);

$committeeId = $handler->handle(
    new CreateCommitteeCommand(
        'District Committee',
        $assignment
    )
);

// $committeeId is a UUID string
// Event is published
// Committee is saved
```

---

## Implementation Timeline

**Phase 1: Policy Kernel** (TDD)  
- Write 3 tests (RED)
- Implement GovernancePolicy, Matrix, Cell, Assignment (GREEN)
- ✅ 3/3 tests pass

**Phase 2: Use Case** (TDD)  
- Write 3 tests (RED)
- Update CreateCommitteeCommand, Handler, Aggregate, Event (GREEN)
- ✅ 3/3 tests pass

**Phase 3: Cleanup**  
- Delete JurisdictionInstance (superseded)
- Update LegacyJurisdiction bridge
- Delete incompatible test
- ✅ All tests pass

**Result:** 6/6 core tests passing, zero regressions.

---

## Key Design Decisions

### 1. Policy Check Happens in Aggregate::establish()

```php
// ✅ Right: Policy enforced at boundary
ConstitutionalCommittee::establish(..., $policy) {
    $policy->assertAllowed($assignment);  // FIRST
    $committee = new self(...);           // THEN
    $committee->recordEvent(...);
}
```

This ensures every event represents a valid state.

### 2. No Validation in Value Objects

```php
// GovernanceAssignment has NO validation
public function __construct(int $gov, int $geo, GeoUnitId $id) {
    $this->governanceLevel = $gov;
    $this->geoLevel = $geo;
    $this->geoUnitId = $id;
    // no checks here
}
```

VOs are pure data. **Policy is the validator.**

### 3. Handler Has NO Validation Logic

```php
// ✅ Right: Handler is pure orchestration
public function handle(CreateCommitteeCommand $cmd): string {
    $committee = ConstitutionalCommittee::establish(
        ...,
        $this->policy  // ← pass policy
    );
    // policy handles validation
    $this->repository->save($committee);
    $this->eventBus->publish($committee->releaseEvents());
    return $committee->id()->value();
}
```

The **domain** validates, the **handler** orchestrates.

### 4. Matrix Bootstrap Uses Diagonal Seed

```php
// [level] → [level × level] in matrix
GovernanceMatrix::fromRows([
    ['level' => 0, 'is_active' => true],   // becomes (0, 0)
    ['level' => 1, 'is_active' => true],   // becomes (1, 1)
    ['level' => 2, 'is_active' => false],  // becomes (2, 2)
]);
```

**Why:** Awaits geo_level column migration.  
**Current:** Assumes governance_level == geo_level.  
**Future:** Full 2D support when database migrates.

---

## Common Usage Patterns

### Pattern 1: In a Controller

```php
public function store(CreateCommitteeRequest $request): Response
{
    try {
        $id = $this->handler->handle(
            new CreateCommitteeCommand(
                $request->name,
                new GovernanceAssignment(
                    $request->governance_level,
                    $request->geo_level,
                    GeoUnitId::fromInt($request->geo_unit_id),
                ),
            )
        );

        return response()->json(['id' => $id]);
    } catch (DomainException $e) {
        return response()->json(['error' => $e->getMessage()], 422);
    }
}
```

### Pattern 2: In a Service Provider

```php
class GovernanceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GovernancePolicy::class, function () {
            $matrix = GovernanceMatrix::fromRows(
                DB::table('governance_level_definitions')
                    ->select('level', 'is_active')
                    ->get()
                    ->toArray()
            );
            return new GovernancePolicy($matrix);
        });
    }
}
```

Then inject: `public function __construct(GovernancePolicy $policy)`

### Pattern 3: In Tests

```php
public function test_creates_valid_committee(): void
{
    $policy = new GovernancePolicy(
        GovernanceMatrix::fromRows([['level' => 2, 'is_active' => true]])
    );

    $handler = new CreateCommitteeHandler(
        new InMemoryCommitteeRepository(),
        new FakeEventBus(),
        $policy
    );

    $id = $handler->handle(new CreateCommitteeCommand(
        'Test Committee',
        new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
    ));

    $this->assertIsString($id);
}
```

---

## Testing

**All tests are in:** `tests/Unit/Contexts/Membership/`

```bash
# Phase 1: Policy layer
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/Policies/GovernancePolicyTest.php

# Phase 2: Use case layer
php artisan test tests/Unit/Contexts/Membership/Application/Committee/UseCases/CreateCommittee/CreateCommitteeUseCaseTest.php

# Both together
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/Policies/ \
                   tests/Unit/Contexts/Membership/Application/Committee/UseCases/CreateCommittee/
```

**Result:** 6/6 passing ✅

---

## Troubleshooting

### "Invalid governance assignment: 2 × 2 not allowed"

Your matrix doesn't have an active cell for [2, 2].

**Solution:** Check the database
```sql
SELECT level, is_active FROM governance_level_definitions WHERE level = 2;
```

If inactive, activate it:
```sql
UPDATE governance_level_definitions SET is_active = true WHERE level = 2;
```

### "Too few arguments to CreateCommitteeHandler::__construct()"

You're not passing GovernancePolicy.

**Fix:**
```php
// Wrong:
new CreateCommitteeHandler($repo, $bus);

// Right:
new CreateCommitteeHandler($repo, $bus, $policy);
```

### Tests Fail: "Unknown matrix cell [2, 1]"

Your test creates an assignment with levels not in the test matrix.

**Fix:**
```php
// Add both levels to your test matrix
$matrix = GovernanceMatrix::fromRows([
    ['level' => 2, 'is_active' => true],
]);
// Now (2, 2) is in the matrix
```

---

## Performance Tips

### Cache the Matrix

```php
$policy = Cache::remember('governance_policy', minutes: 60, function () {
    return new GovernancePolicy(
        GovernanceMatrix::fromRows(
            DB::table('governance_level_definitions')
                ->select('level', 'is_active')
                ->get()
                ->toArray()
        )
    );
});
```

### Bulk Validation

```php
// Instead of checking each assignment individually:
$allowedLevels = $matrix->allowedGovernanceLevelsForGeo(2);

foreach ($assignments as $a) {
    if (!in_array($a->governanceLevel, $allowedLevels, true)) {
        throw new DomainException(...);
    }
}
```

---

## Next Steps

1. **Read GEO_GOVERNANCE_MATRIX.md** — Full architecture
2. **Read IMPLEMENTATION_GUIDE.md** — Step-by-step guide
3. **Read API_REFERENCE.md** — Method signatures
4. **Implement a new policy rule** — Follow the guide
5. **Write tests** — Always RED→GREEN→REFACTOR

---

## Key Files

```
app/Contexts/Membership/
├── Domain/
│   ├── Committee/
│   │   ├── Policies/
│   │   │   ├── GovernancePolicy.php (enforcer)
│   │   │   ├── GovernanceMatrix.php (projection)
│   │   │   └── MatrixCell.php (config)
│   │   ├── ValueObjects/
│   │   │   ├── GovernanceAssignment.php (snapshot)
│   │   │   └── CommitteeId.php (aggregate ID)
│   │   ├── Events/
│   │   │   └── CommitteeEstablished.php (event)
│   │   └── ConstitutionalCommittee.php (aggregate)
│   └── ...
│
└── Application/
    ├── Committee/
    │   └── UseCases/
    │       └── CreateCommittee/
    │           ├── CreateCommitteeCommand.php
    │           ├── CreateCommitteeHandler.php
    │           └── Ports/
    └── ...

tests/Unit/Contexts/Membership/
├── Domain/Committee/Policies/
│   └── GovernancePolicyTest.php (3 tests)
│
└── Application/Committee/UseCases/CreateCommittee/
    └── CreateCommitteeUseCaseTest.php (3 tests)
```

---

## Document Quick Links

- **Architecture Details** → `GEO_GOVERNANCE_MATRIX.md`
- **Building Guide** → `IMPLEMENTATION_GUIDE.md`
- **API Lookup** → `API_REFERENCE.md`
- **This Guide** → `README.md` (you are here)

---

## Got Questions?

1. Check the test files for working examples
2. Read the architecture document
3. Look at the API reference
4. Run tests in tinker: `php artisan tinker`

---

**Last Updated:** 2026-05-13  
**Status:** Phase 1-3 Complete, All Tests Passing ✅
