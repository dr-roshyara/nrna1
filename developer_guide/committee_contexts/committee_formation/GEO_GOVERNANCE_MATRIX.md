# Geo×Governance Matrix System — Developer Guide

**Status:** Phase 1-3 Complete (TDD-first implementation)  
**Test Coverage:** 6/6 core tests passing  
**Last Updated:** 2026-05-13

---

## Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Core Concepts](#core-concepts)
3. [Implementation Layers](#implementation-layers)
4. [Using the System](#using-the-system)
5. [Extending the System](#extending-the-system)
6. [Testing Strategy](#testing-strategy)
7. [Common Patterns](#common-patterns)
8. [Troubleshooting](#troubleshooting)

---

## Architecture Overview

The Geo×Governance matrix replaces the hardcoded `Jurisdiction` enum with a **configurable 2D policy system** that separates governance levels (hierarchy of authority) from geo levels (geographic units) into independent axes.

### The Problem It Solves

**Before:** Jurisdiction was a hardcoded enum with scope names (ward, district, province, national).
- Inflexible: adding new jurisdictions required code changes
- Assumes 1:1 mapping: governance level = geography level
- Cannot express complex policies (e.g., a governance level 2 can exist at geo level 1 OR 3)

**After:** Configurable matrix where any [governanceLevel, geoLevel] pair can be marked as allowed.
- Flexible: change policies via database without code changes
- Independent axes: governance and geography are separate concerns
- Expressive: any combination possible

### 2D Matrix Model

```
                    Geo Level
                    0 1 2 3
Governance Level:
    0  (Central)   ✓ ✓ ✓ ✓
    1  (Regional)    ✓ ✓  
    2  (District)      ✓
    3  (Ward)           
```

Each cell `[gov, geo]` → boolean allowed.

---

## Core Concepts

### 1. GovernanceAssignment (Value Object)

Immutable snapshot of a committee's governance positioning.

```php
use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;

// Create an assignment
$assignment = new GovernanceAssignment(
    governanceLevel: 2,
    geoLevel: 2,
    geoUnitId: GeoUnitId::fromInt(42),
);

// Check equality
$same = $assignment->equals(
    new GovernanceAssignment(2, 2, GeoUnitId::fromInt(42))
); // true
```

**Properties:**
- `governanceLevel: int` — where in the governance hierarchy (0-10)
- `geoLevel: int` — geographic unit level (0-10)
- `geoUnitId: GeoUnitId` — which geographic unit (cross-context VO)

**Design:** No validation. Pure data snapshot. Validation happens in `GovernancePolicy`.

---

### 2. MatrixCell (Configuration Unit)

Atomic rule: "governance level X at geo level Y is [allowed/forbidden]"

```php
use App\Contexts\Membership\Domain\Committee\Policies\MatrixCell;

$cell = new MatrixCell(
    governanceLevel: 2,
    geoLevel: 2,
    allowed: true,
);

// Use in GovernanceMatrix
```

**Design:** Read-only properties, no behavior. Pure configuration.

---

### 3. GovernanceMatrix (Policy Projection)

Projection model: loads cells from database, evaluates rules.

```php
use App\Contexts\Membership\Domain\Committee\Policies\GovernanceMatrix;

// Bootstrap from database rows
$matrix = GovernanceMatrix::fromRows([
    ['level' => 0, 'is_active' => true],   // diagonal seed: 0×0
    ['level' => 1, 'is_active' => true],   // diagonal seed: 1×1
    ['level' => 2, 'is_active' => false],  // diagonal seed: 2×2 (inactive)
]);

// Query the matrix
$matrix->isAllowed(1, 1);        // → true
$matrix->isAllowed(2, 2);        // → false
$matrix->isAllowed(3, 3);        // → false (not in matrix)

// Find allowed governance levels for a geo level
$levels = $matrix->allowedGovernanceLevelsForGeo(1);  // → [0, 1]
```

**Key Design:**
- `fromRows()` uses **diagonal seed**: `geoLevel = governanceLevel` by default
- Awaits `geo_level` column migration for full 2D support
- No business logic; only evaluation and projection

---

### 4. GovernancePolicy (Enforcement Gate)

**THE ONLY validation point.** Throws `DomainException` if assignment invalid.

```php
use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;

$policy = new GovernancePolicy($matrix);

// Enforce the rule
$policy->assertAllowed(
    new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
);
// throws DomainException if matrix.isAllowed(2, 2) === false
```

**Single Responsibility:** Apply policy rules. Nothing else.

---

## Implementation Layers

### Domain Layer (Pure PHP)

```
app/Contexts/Membership/Domain/Committee/

├── Policies/
│   ├── GovernancePolicy.php       ← THE enforcement gate
│   ├── GovernanceMatrix.php        ← projection model
│   └── MatrixCell.php              ← configuration unit
│
├── ValueObjects/
│   ├── GovernanceAssignment.php    ← immutable snapshot
│   └── ... (CommitteeId, etc.)
│
├── Events/
│   └── CommitteeEstablished.php    ← records gov/geo/unit/time
│
└── ConstitutionalCommittee.php     ← aggregate, calls policy
```

**Key Rule:** No Laravel dependencies. Pure PHP only.

---

### Application Layer (Limited Laravel)

```
app/Contexts/Membership/Application/Committee/UseCases/CreateCommittee/

├── CreateCommitteeCommand.php      ← (name, assignment)
├── CreateCommitteeHandler.php      ← injects policy, NO validation logic
└── Ports/
    ├── CommitteeRepositoryPort.php
    └── EventBusPort.php
```

**Key Rule:** No Facades. Constructor injection only.

---

### Infrastructure Layer (Laravel Allowed)

```
app/Contexts/Membership/Infrastructure/

├── Repositories/
│   └── EloquentCommitteeRepository.php
│
└── Persistence/
    └── GovernanceMatrix bootstrap from DB
```

**Key Rule:** Eloquent allowed. Facades allowed. Database coupling OK.

---

## Using the System

### Basic Flow: Create a Committee

```php
use App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeCommand;
use App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeHandler;
use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;

// 1. Build the matrix from governance_level_definitions table
$matrix = GovernanceMatrix::fromRows(
    DB::table('governance_level_definitions')
        ->select('level', 'is_active')
        ->get()
        ->toArray()
);

// 2. Create the policy
$policy = new GovernancePolicy($matrix);

// 3. Inject into handler
$handler = new CreateCommitteeHandler(
    repository: $committeeRepository,
    eventBus: $eventBus,
    policy: $policy,
);

// 4. Execute with command
$committeeId = $handler->handle(
    new CreateCommitteeCommand(
        name: 'District Committee',
        assignment: new GovernanceAssignment(
            governanceLevel: 2,
            geoLevel: 2,
            geoUnitId: GeoUnitId::fromInt(15),
        ),
    )
);
// Returns committee ID string, or throws DomainException if policy rejects
```

### What Happens Inside

```
Command received
    ↓
ConstitutionalCommittee::establish()
    ↓
    → policy.assertAllowed(assignment)  ← ONLY validation point
    ↓
    → throws DomainException if invalid
    ↓
    → creates aggregate + records event
    ↓
EventBus publishes CommitteeEstablished
    ↓
Repository saves committee
    ↓
Return committee ID
```

---

### Querying the Matrix

```php
// Check if a specific assignment is allowed
$isAllowed = $policy->assertAllowed(new GovernanceAssignment(...));
// throws if not allowed

// Get all allowed governance levels for a geo level
$allowedLevels = $matrix->allowedGovernanceLevelsForGeo(2);
// → [0, 1, 2] if those levels are active at geo 2

// Build allowed posts for a voter
$voter = User::find($userId);  // has $voter->region
$geo = GeoUnit::where('name', $voter->region)->first();

$posts = Post::where('election_id', $election->id)
    ->where('is_national_wide', 0)
    ->where('state_name', $voter->region)
    ->get();
// Now filter by policy if needed
```

---

## Extending the System

### Adding a New Governance Level

**In Database:**
```sql
INSERT INTO governance_level_definitions (level, name, is_active)
VALUES (4, 'Sub-ward', true);
```

**No Code Change Needed.** The matrix is configuration-driven.

---

### Changing Policy Rules

**Before:** Required code changes.  
**After:** Just update the database.

```sql
-- Disable level 2 at geo 2
UPDATE governance_level_definitions
SET is_active = false
WHERE level = 2;

-- Reload the matrix
$matrix = GovernanceMatrix::fromRows(...);
```

---

### Custom Policy Logic

If you need domain-specific rules beyond the matrix:

```php
final class ElectionGovernancePolicy
{
    public function __construct(
        private GovernancePolicy $matrixPolicy,
        private ElectionSpecificRules $rules,
    ) {}

    public function assertAllowed(GovernanceAssignment $a): void
    {
        // First: check the matrix
        $this->matrixPolicy->assertAllowed($a);

        // Then: check election-specific rules
        $this->rules->validate($a);
    }
}
```

**Key:** Compose policies; don't extend them.

---

## Testing Strategy

### Unit Tests: Policy Layer

```php
use Tests\Unit\Contexts\Membership\Domain\Committee\Policies\GovernancePolicyTest;

// Test: matrix accepts valid assignment
public function test_allows_valid_assignment(): void
{
    $matrix = GovernanceMatrix::fromRows([
        ['level' => 2, 'is_active' => true],
    ]);
    $policy = new GovernancePolicy($matrix);

    $policy->assertAllowed(
        new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
    );

    $this->expectNotToPerformAssertions();  // ← no exception = pass
}

// Test: matrix rejects invalid (inactive) assignment
public function test_rejects_invalid_assignment(): void
{
    $matrix = GovernanceMatrix::fromRows([
        ['level' => 2, 'is_active' => false],
    ]);
    $policy = new GovernancePolicy($matrix);

    $this->expectException(DomainException::class);

    $policy->assertAllowed(
        new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
    );
}

// Test: matrix rejects absent assignment
public function test_rejects_absent_assignment(): void
{
    $matrix = GovernanceMatrix::fromRows([
        ['level' => 2, 'is_active' => true],
    ]);
    $policy = new GovernancePolicy($matrix);

    $this->expectException(DomainException::class);

    // Different geo level (not in matrix)
    $policy->assertAllowed(
        new GovernanceAssignment(2, 1, GeoUnitId::fromInt(1))
    );
}
```

### Unit Tests: Use Case Layer

```php
use Tests\Unit\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeUseCaseTest;

// Test: handler creates and returns ID
public function test_creates_committee_with_valid_assignment(): void
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

    $this->assertNotNull($id);
    $this->assertIsString($id);
}

// Test: handler rejects invalid assignment
public function test_rejects_invalid_assignment(): void
{
    $policy = new GovernancePolicy(
        GovernanceMatrix::fromRows([['level' => 2, 'is_active' => false]])
    );

    $handler = new CreateCommitteeHandler(
        new InMemoryCommitteeRepository(),
        new FakeEventBus(),
        $policy
    );

    $this->expectException(DomainException::class);

    $handler->handle(new CreateCommitteeCommand(
        'Test Committee',
        new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
    ));
}

// Test: handler publishes event
public function test_committee_emits_established_event(): void
{
    $eventBus = new FakeEventBus();
    // ... setup ...

    $handler->handle(new CreateCommitteeCommand(...));

    $this->assertTrue($eventBus->hasPublished('CommitteeEstablished'));
}
```

### Running Tests

```bash
# Phase 1: Policy layer
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/Policies/GovernancePolicyTest.php

# Phase 2: Use case layer
php artisan test tests/Unit/Contexts/Membership/Application/Committee/UseCases/CreateCommittee/CreateCommitteeUseCaseTest.php

# Both together
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/Policies/ \
                   tests/Unit/Contexts/Membership/Application/Committee/UseCases/
```

---

## Common Patterns

### Pattern 1: Bootstrap from Config

```php
// In a service provider or controller
$rows = DB::table('governance_level_definitions')
    ->select('level', 'is_active')
    ->get()
    ->toArray();

$matrix = GovernanceMatrix::fromRows($rows);
$policy = new GovernancePolicy($matrix);

// Cache it for subsequent requests
Cache::put('governance_policy', $policy, minutes: 60);
```

### Pattern 2: Conditional Committee Creation

```php
try {
    $committeeId = $handler->handle(
        new CreateCommitteeCommand($name, $assignment)
    );
    
    return redirect()->route('committee.show', $committeeId)
        ->with('success', 'Committee created');
} catch (DomainException $e) {
    return back()
        ->withInput()
        ->withErrors(['assignment' => $e->getMessage()]);
}
```

### Pattern 3: Multi-Level Validation

```php
// Application layer (FormRequest)
$request->validate([
    'governance_level' => 'required|integer|min:0|max:10',
    'geo_level' => 'required|integer|min:0|max:10',
    'geo_unit_id' => 'required|exists:geo_units,id',
]);

// Domain layer (policy)
$policy->assertAllowed(
    new GovernanceAssignment(
        $request->governance_level,
        $request->geo_level,
        GeoUnitId::fromInt($request->geo_unit_id),
    )
);
```

### Pattern 4: Bulk Policy Evaluation

```php
$proposedAssignments = [
    new GovernanceAssignment(1, 1, $geo1),
    new GovernanceAssignment(2, 2, $geo2),
    new GovernanceAssignment(3, 1, $geo3),
];

$valid = [];
$invalid = [];

foreach ($proposedAssignments as $assignment) {
    try {
        $policy->assertAllowed($assignment);
        $valid[] = $assignment;
    } catch (DomainException $e) {
        $invalid[] = ['assignment' => $assignment, 'reason' => $e->getMessage()];
    }
}

return ['valid' => $valid, 'invalid' => $invalid];
```

---

## Troubleshooting

### Error: "Invalid governance assignment: 2 × 2 not allowed"

**Cause:** Matrix doesn't have an active cell for [2, 2].

**Fix:**
```php
// Check what's in the matrix
$rows = DB::table('governance_level_definitions')->get();
dd($rows);

// Activate level 2
DB::table('governance_level_definitions')
    ->where('level', 2)
    ->update(['is_active' => true]);

// Reload
$matrix = GovernanceMatrix::fromRows($rows->toArray());
```

### Error: "Call to undefined method GeoUnitId::value()"

**Cause:** Using wrong method name.

**Fix:** Use `->toString()` or `->toInt()`, not `->value()`.

```php
// Wrong:
$geoUnitId->value()

// Right:
$geoUnitId->toString()  // returns "42"
$geoUnitId->toInt()     // returns 42
```

### Error: "Too few arguments to CreateCommitteeHandler::__construct()"

**Cause:** Not passing GovernancePolicy.

**Fix:**
```php
// Wrong:
new CreateCommitteeHandler($repo, $eventBus);

// Right:
new CreateCommitteeHandler($repo, $eventBus, $policy);
```

### Tests Fail: "Unknown matrix cell"

**Cause:** Test creates assignment with levels not in test matrix.

**Fix:**
```php
// Make sure test matrix includes both levels
$matrix = GovernanceMatrix::fromRows([
    ['level' => 1, 'is_active' => true],
    ['level' => 2, 'is_active' => true],  // ← add if testing (2, 2)
]);
```

---

## Design Rationale

### Why Separate Governance from Geography?

**Before:** `Jurisdiction` conflated two concerns.  
**After:** Two independent axes allow:
- A governance level to exist at multiple geography levels
- A geography level to have multiple governance structures
- Configuration without code changes

### Why No Validation in GovernanceAssignment?

**Value Objects** are immutable data snapshots. Validation is a **policy concern**, not a data concern. This separates "what is" from "what's allowed".

### Why Does ConstitutionalCommittee Call Policy?

**Domain Event Sourcing:** The policy check happens at the aggregate boundary, so we can be sure the event represents a valid state transition. This is the only place that matters for consistency.

### Why fromRows() Uses Diagonal Seed?

**Bootstrap simplicity:** The governance_level_definitions table historically stored only a single "level" per row. Until a geo_level column is added, we assume governance_level == geo_level (diagonal). This is safe for most hierarchies.

---

## Next Steps

1. **Add geo_level column** to governance_level_definitions for full 2D support
2. **Implement reverse queries** — e.g., "which governance levels can a voter at geo 2 form?"
3. **Add policy caching** — GovernancePolicy is read-heavy; cache the matrix
4. **Extend to election-specific policies** — wrap GovernancePolicy for context-specific rules
5. **Implement policy versioning** — track historical policy changes for audit trails

---

## Files Reference

| File | Purpose |
|------|---------|
| `app/Contexts/Membership/Domain/Committee/Policies/GovernancePolicy.php` | Enforcement gate |
| `app/Contexts/Membership/Domain/Committee/Policies/GovernanceMatrix.php` | Projection model |
| `app/Contexts/Membership/Domain/Committee/Policies/MatrixCell.php` | Config unit |
| `app/Contexts/Membership/Domain/Committee/ValueObjects/GovernanceAssignment.php` | Data snapshot |
| `app/Contexts/Membership/Domain/Committee/ConstitutionalCommittee.php` | Aggregate |
| `app/Contexts/Membership/Application/Committee/UseCases/CreateCommittee/CreateCommitteeHandler.php` | Use case |
| `tests/Unit/.../GovernancePolicyTest.php` | Phase 1 tests |
| `tests/Unit/.../CreateCommitteeUseCaseTest.php` | Phase 2 tests |

---

**Questions?** Check the test files for working examples.
