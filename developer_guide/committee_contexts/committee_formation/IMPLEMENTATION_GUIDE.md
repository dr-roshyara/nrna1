# Committee Formation — Implementation Guide

**Focus:** How to build features using the Geo×Governance matrix system  
**Audience:** Backend developers implementing new committee features

---

## Quick Start

### 1. Understand the Three Phases

The implementation follows strict TDD:

| Phase | Layer | Pattern | Tests | Status |
|-------|-------|---------|-------|--------|
| **1** | Domain | Policy kernel | 3 | ✅ PASS |
| **2** | Application | Use case | 3 | ✅ PASS |
| **3** | Cleanup | Legacy removal | — | ✅ PASS |

Always implement in this order. Each phase builds on the previous one.

---

## Phase 1: The Policy Kernel

### Goal
Implement `GovernancePolicy` and supporting VOs. This is the **core rule engine**.

### Step 1.1: Write Tests First (RED)

Create `tests/Unit/Contexts/Membership/Domain/Committee/Policies/YourPolicyTest.php`:

```php
<?php

namespace Tests\Unit\Contexts\Membership\Domain\Committee\Policies;

use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;
use App\Contexts\Membership\Domain\Committee\Policies\GovernanceMatrix;
use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;
use DomainException;
use PHPUnit\Framework\TestCase;

final class YourPolicyTest extends TestCase
{
    public function test_allows_valid_assignment(): void
    {
        $matrix = GovernanceMatrix::fromRows([
            ['level' => 2, 'is_active' => true],
        ]);
        $policy = new GovernancePolicy($matrix);

        $policy->assertAllowed(
            new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
        );

        $this->expectNotToPerformAssertions();
    }

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

    public function test_rejects_absent_assignment(): void
    {
        $matrix = GovernanceMatrix::fromRows([
            ['level' => 2, 'is_active' => true],
        ]);
        $policy = new GovernancePolicy($matrix);

        $this->expectException(DomainException::class);

        // Different geo level
        $policy->assertAllowed(
            new GovernanceAssignment(2, 1, GeoUnitId::fromInt(1))
        );
    }
}
```

**Run:** `php artisan test tests/Unit/.../YourPolicyTest.php`  
**Expected:** FAIL (classes don't exist yet)

### Step 1.2: Implement Minimal Domain Objects (GREEN)

Create value objects in order:

**GovernanceAssignment.php:**
```php
<?php

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;

final readonly class GovernanceAssignment
{
    public function __construct(
        public int $governanceLevel,
        public int $geoLevel,
        public GeoUnitId $geoUnitId,
    ) {}

    public function equals(self $other): bool
    {
        return $this->governanceLevel === $other->governanceLevel
            && $this->geoLevel === $other->geoLevel
            && $this->geoUnitId->equals($other->geoUnitId);
    }
}
```

**MatrixCell.php:**
```php
<?php

namespace App\Contexts\Membership\Domain\Committee\Policies;

final readonly class MatrixCell
{
    public function __construct(
        public int $governanceLevel,
        public int $geoLevel,
        public bool $allowed,
    ) {}
}
```

**GovernanceMatrix.php:**
```php
<?php

namespace App\Contexts\Membership\Domain\Committee\Policies;

final readonly class GovernanceMatrix
{
    /** @param MatrixCell[] $cells */
    public function __construct(private array $cells) {}

    public function isAllowed(int $gov, int $geo): bool
    {
        foreach ($this->cells as $cell) {
            if ($cell->governanceLevel === $gov && $cell->geoLevel === $geo) {
                return $cell->allowed;
            }
        }
        return false;
    }

    public function allowedGovernanceLevelsForGeo(int $geo): array
    {
        $result = [];
        foreach ($this->cells as $cell) {
            if ($cell->geoLevel === $geo && $cell->allowed) {
                $result[] = $cell->governanceLevel;
            }
        }
        return array_values(array_unique($result));
    }

    public static function fromRows(array $rows): self
    {
        $cells = [];
        foreach ($rows as $row) {
            $gov = (int) $row['level'];
            $cells[] = new MatrixCell(
                governanceLevel: $gov,
                geoLevel: $gov,  // diagonal seed — awaits geo_level column
                allowed: (bool) ($row['is_active'] ?? true),
            );
        }
        return new self($cells);
    }
}
```

**GovernancePolicy.php:**
```php
<?php

namespace App\Contexts\Membership\Domain\Committee\Policies;

use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;
use DomainException;

final readonly class GovernancePolicy
{
    public function __construct(private GovernanceMatrix $matrix) {}

    public function assertAllowed(GovernanceAssignment $a): void
    {
        if (!$this->matrix->isAllowed($a->governanceLevel, $a->geoLevel)) {
            throw new DomainException(sprintf(
                'Invalid governance assignment: %d × %d not allowed',
                $a->governanceLevel,
                $a->geoLevel
            ));
        }
    }
}
```

**Run:** `php artisan test tests/Unit/.../YourPolicyTest.php`  
**Expected:** PASS (all 3 tests)

### Step 1.3: Refactor (Optional)

No refactoring needed if code is clean. Move forward to Phase 2.

---

## Phase 2: The Use Case Layer

### Goal
Implement `CreateCommitteeHandler` and wire everything together. This is the **orchestration layer**.

### Step 2.1: Write Tests First (RED)

Create `tests/Unit/Contexts/Membership/Application/.../CreateCommitteeUseCaseTest.php`:

```php
<?php

namespace Tests\Unit\Contexts\Membership\Application\Committee\UseCases\CreateCommittee;

use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeCommand;
use App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeHandler;
use App\Contexts\Membership\Domain\Committee\Policies\GovernanceMatrix;
use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;
use DomainException;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\FakeEventBus;
use Tests\Doubles\InMemoryCommitteeRepository;

final class CreateCommitteeUseCaseTest extends TestCase
{
    public function test_creates_committee_with_valid_assignment(): void
    {
        $policy = new GovernancePolicy(
            GovernanceMatrix::fromRows([
                ['level' => 2, 'is_active' => true],
            ])
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

    public function test_rejects_invalid_assignment(): void
    {
        $policy = new GovernancePolicy(
            GovernanceMatrix::fromRows([
                ['level' => 2, 'is_active' => false],
            ])
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

    public function test_committee_emits_established_event(): void
    {
        $policy = new GovernancePolicy(
            GovernanceMatrix::fromRows([
                ['level' => 2, 'is_active' => true],
            ])
        );

        $eventBus = new FakeEventBus();
        $handler = new CreateCommitteeHandler(
            new InMemoryCommitteeRepository(),
            $eventBus,
            $policy
        );

        $handler->handle(new CreateCommitteeCommand(
            'Test Committee',
            new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
        ));

        $this->assertTrue($eventBus->hasPublished('CommitteeEstablished'));
    }
}
```

**Run:** `php artisan test tests/Unit/.../CreateCommitteeUseCaseTest.php`  
**Expected:** FAIL (classes don't exist yet)

### Step 2.2: Update Command (GREEN)

**CreateCommitteeCommand.php:**
```php
<?php

namespace App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee;

use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;

final readonly class CreateCommitteeCommand
{
    public function __construct(
        public string $name,
        public GovernanceAssignment $assignment,
    ) {}
}
```

### Step 2.3: Update Handler (GREEN)

**CreateCommitteeHandler.php:**
```php
<?php

namespace App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee;

use App\Contexts\Membership\Application\Committee\Ports\CommitteeRepositoryPort;
use App\Contexts\Membership\Application\Committee\Ports\EventBusPort;
use App\Contexts\Membership\Domain\Committee\ConstitutionalCommittee;
use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;

final readonly class CreateCommitteeHandler
{
    public function __construct(
        private CommitteeRepositoryPort $repository,
        private EventBusPort $eventBus,
        private GovernancePolicy $policy,
    ) {}

    public function handle(CreateCommitteeCommand $command): string
    {
        $committee = ConstitutionalCommittee::establish(
            id: CommitteeId::generate(),
            name: $command->name,
            assignment: $command->assignment,
            at: new DateTimeImmutable(),
            policy: $this->policy,
        );

        $this->repository->save($committee);

        foreach ($committee->releaseEvents() as $event) {
            $this->eventBus->publish($event);
        }

        return $committee->id()->value();
    }
}
```

### Step 2.4: Update Aggregate (GREEN)

**ConstitutionalCommittee.php:**
```php
<?php

namespace App\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\Events\CommitteeEstablished;
use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;
use App\Shared\Domain\Concerns\RecordsEvents;
use DateTimeImmutable;

final class ConstitutionalCommittee
{
    use RecordsEvents;

    private function __construct(
        private readonly CommitteeId $id,
        private readonly string $name,
        private readonly GovernanceAssignment $assignment,
        private readonly DateTimeImmutable $establishedAt,
    ) {}

    public static function establish(
        CommitteeId $id,
        string $name,
        GovernanceAssignment $assignment,
        DateTimeImmutable $at,
        GovernancePolicy $policy,
    ): self {
        // CRITICAL: Validate BEFORE creating aggregate
        $policy->assertAllowed($assignment);

        $committee = new self($id, $name, $assignment, $at);

        $committee->recordEvent(new CommitteeEstablished(
            committeeId: $id->value(),
            governanceLevel: $assignment->governanceLevel,
            geoLevel: $assignment->geoLevel,
            geoUnitId: $assignment->geoUnitId->toString(),
            establishedAt: $at,
        ));

        return $committee;
    }

    public static function reconstitute(
        CommitteeId $id,
        string $name,
        GovernanceAssignment $assignment,
        DateTimeImmutable $at,
    ): self {
        return new self($id, $name, $assignment, $at);
    }

    public function id(): CommitteeId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAssignment(): GovernanceAssignment
    {
        return $this->assignment;
    }

    public function getEstablishedAt(): DateTimeImmutable
    {
        return $this->establishedAt;
    }
}
```

### Step 2.5: Update Event (GREEN)

**CommitteeEstablished.php:**
```php
<?php

namespace App\Contexts\Membership\Domain\Committee\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;
use DateTimeImmutable;

final class CommitteeEstablished extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $committeeId,
        public readonly int $governanceLevel,
        public readonly int $geoLevel,
        public readonly string $geoUnitId,
        public readonly DateTimeImmutable $establishedAt,
    ) {
        parent::__construct();
    }
}
```

### Step 2.6: Enhance Test Double (GREEN)

**tests/Doubles/FakeEventBus.php:**
```php
<?php

namespace Tests\Doubles;

use App\Contexts\Membership\Application\Committee\Ports\EventBusPort;

final class FakeEventBus implements EventBusPort
{
    /** @var array<object> */
    public array $publishedEvents = [];

    public function publish(object $event): void
    {
        $this->publishedEvents[] = $event;
    }

    public function hasPublished(string $eventClassName): bool
    {
        foreach ($this->publishedEvents as $event) {
            if (class_basename($event) === $eventClassName) {
                return true;
            }
        }
        return false;
    }
}
```

**Run:** `php artisan test tests/Unit/.../CreateCommitteeUseCaseTest.php`  
**Expected:** PASS (all 3 tests)

---

## Phase 3: Cleanup

### Goal
Remove legacy code. Replace `Jurisdiction` with `GovernanceAssignment`.

### Step 3.1: Update Bridge Method

**LegacyJurisdiction.php:**
```php
public function toMatrixJurisdiction(
    int $governanceLevel,
    int $geoLevel,
    GeoUnitId $geoUnitId
): GovernanceAssignment {
    return new GovernanceAssignment(
        governanceLevel: $governanceLevel,
        geoLevel: $geoLevel,
        geoUnitId: $geoUnitId,
    );
}
```

### Step 3.2: Delete Superseded Classes

```bash
rm app/Contexts/Membership/Domain/Committee/ValueObjects/JurisdictionInstance.php
```

### Step 3.3: Delete Incompatible Tests

```bash
rm tests/Unit/.../CreateCommitteeHandlerTest.php  # old API
```

### Step 3.4: Run Full Suite

```bash
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/Policies/
php artisan test tests/Unit/Contexts/Membership/Application/Committee/UseCases/CreateCommittee/
```

**Expected:** All tests pass.

---

## Common Implementation Patterns

### Pattern A: Injecting Policy in Service Provider

```php
// app/Providers/GovernanceServiceProvider.php

namespace App\Providers;

use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;
use App\Contexts\Membership\Domain\Committee\Policies\GovernanceMatrix;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class GovernanceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GovernancePolicy::class, function () {
            $rows = DB::table('governance_level_definitions')
                ->select('level', 'is_active')
                ->get()
                ->toArray();

            $matrix = GovernanceMatrix::fromRows($rows);
            return new GovernancePolicy($matrix);
        });
    }
}
```

Then inject in handlers:
```php
public function __construct(GovernancePolicy $policy)
{
    $this->policy = $policy;
}
```

### Pattern B: Validation in FormRequest

```php
// app/Http/Requests/CreateCommitteeRequest.php

namespace App\Http\Requests;

use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use DomainException;
use Illuminate\Foundation\Http\FormRequest;

class CreateCommitteeRequest extends FormRequest
{
    private GovernancePolicy $policy;

    public function __construct(GovernancePolicy $policy)
    {
        parent::__construct();
        $this->policy = $policy;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'governance_level' => 'required|integer|min:0|max:10',
            'geo_level' => 'required|integer|min:0|max:10',
            'geo_unit_id' => 'required|exists:geo_units,id',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            try {
                $this->policy->assertAllowed(
                    new GovernanceAssignment(
                        $this->governance_level,
                        $this->geo_level,
                        GeoUnitId::fromInt($this->geo_unit_id),
                    )
                );
            } catch (DomainException $e) {
                $validator->errors()->add(
                    'assignment',
                    $e->getMessage()
                );
            }
        });
    }
}
```

### Pattern C: Error Handling in Controller

```php
// app/Http/Controllers/CommitteeController.php

public function store(CreateCommitteeRequest $request): RedirectResponse
{
    try {
        $committeeId = $this->handler->handle(
            new CreateCommitteeCommand(
                $request->name,
                new GovernanceAssignment(
                    $request->governance_level,
                    $request->geo_level,
                    GeoUnitId::fromInt($request->geo_unit_id),
                ),
            )
        );

        return redirect()
            ->route('committee.show', $committeeId)
            ->with('success', 'Committee created successfully');
    } catch (DomainException $e) {
        return back()
            ->withInput()
            ->withErrors(['assignment' => $e->getMessage()]);
    }
}
```

---

## Checklist for Adding New Policy Rules

- [ ] Write test for the new rule (RED)
- [ ] Add MatrixCell to GovernanceMatrix.fromRows() (GREEN)
- [ ] Verify policy rejects invalid assignments
- [ ] Run test suite: `php artisan test`
- [ ] Update database if needed
- [ ] Update documentation

---

## Debugging Tips

### Inspect the Matrix

```php
$matrix = GovernanceMatrix::fromRows(...);

// What's in the matrix?
foreach ($matrix as $cell) {
    dump($cell->governanceLevel, $cell->geoLevel, $cell->allowed);
}

// What governance levels are allowed at geo 2?
$allowed = $matrix->allowedGovernanceLevelsForGeo(2);
dd($allowed);
```

### Test Policy in Tinker

```bash
php artisan tinker

>>> $matrix = \App\Contexts\Membership\Domain\Committee\Policies\GovernanceMatrix::fromRows([['level' => 2, 'is_active' => true]])
>>> $policy = new \App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy($matrix)
>>> $policy->assertAllowed(new \App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment(2, 2, \App\Contexts\Geography\Domain\ValueObjects\GeoUnitId::fromInt(1)))
>>> // No error = allowed
```

---

## Running the Full Test Suite

```bash
# Phase 1 only
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/Policies/GovernancePolicyTest.php

# Phase 2 only
php artisan test tests/Unit/Contexts/Membership/Application/Committee/UseCases/CreateCommittee/CreateCommitteeUseCaseTest.php

# Both phases
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/Policies/ \
                   tests/Unit/Contexts/Membership/Application/Committee/UseCases/CreateCommittee/

# With Architecture tests
php artisan test tests/Architecture/CommitteeDomainPurityTest.php
```

---

## Performance Considerations

### Matrix Bootstrapping

Currently done per-request. **Optimize with caching:**

```php
$matrix = Cache::remember('governance_matrix', minutes: 60, function () {
    return GovernanceMatrix::fromRows(
        DB::table('governance_level_definitions')
            ->select('level', 'is_active')
            ->get()
            ->toArray()
    );
});
```

### Bulk Validation

For validating many assignments:

```php
// Instead of:
foreach ($assignments as $a) {
    $policy->assertAllowed($a);  // N queries potentially
}

// Better:
$allowedLevels = $matrix->allowedGovernanceLevelsForGeo(2);
foreach ($assignments as $a) {
    if (!in_array($a->governanceLevel, $allowedLevels, true)) {
        throw new DomainException(...);
    }
}
```

---

**Next:** See `GEO_GOVERNANCE_MATRIX.md` for detailed architecture.
