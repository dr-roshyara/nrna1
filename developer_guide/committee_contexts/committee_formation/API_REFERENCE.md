# Geo×Governance Matrix — API Reference

**Quick lookup for all public methods and classes.**

---

## Core Classes

### GovernanceAssignment

**Location:** `App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment`

```php
class GovernanceAssignment {
    public int $governanceLevel;
    public int $geoLevel;
    public GeoUnitId $geoUnitId;

    public function __construct(
        int $governanceLevel,
        int $geoLevel,
        GeoUnitId $geoUnitId
    );

    public function equals(GovernanceAssignment $other): bool;
}
```

**Example:**
```php
$assignment = new GovernanceAssignment(
    governanceLevel: 2,
    geoLevel: 2,
    geoUnitId: GeoUnitId::fromInt(15)
);

if ($assignment->equals($other)) {
    // same governance + geo + unit
}
```

---

### MatrixCell

**Location:** `App\Contexts\Membership\Domain\Committee\Policies\MatrixCell`

```php
final readonly class MatrixCell {
    public int $governanceLevel;
    public int $geoLevel;
    public bool $allowed;

    public function __construct(
        int $governanceLevel,
        int $geoLevel,
        bool $allowed
    );
}
```

**Note:** Read-only value object. No methods beyond constructor.

---

### GovernanceMatrix

**Location:** `App\Contexts\Membership\Domain\Committee\Policies\GovernanceMatrix`

```php
final readonly class GovernanceMatrix {
    public function __construct(array $cells);  // MatrixCell[]

    public function isAllowed(int $gov, int $geo): bool;

    public function allowedGovernanceLevelsForGeo(int $geo): array;

    public static function fromRows(array $rows): self;
}
```

**Methods:**

#### isAllowed(int $gov, int $geo): bool

Check if a governance × geo combination is allowed.

```php
$allowed = $matrix->isAllowed(2, 2);  // true or false
```

**Returns:** `true` if cell exists and `allowed = true`, else `false`

---

#### allowedGovernanceLevelsForGeo(int $geo): array

Get all governance levels allowed at a specific geo level.

```php
$levels = $matrix->allowedGovernanceLevelsForGeo(2);  // [0, 1, 2]
```

**Returns:** Array of integers, deduplicated and sorted

---

#### fromRows(array $rows): self

Bootstrap matrix from database rows.

```php
$rows = DB::table('governance_level_definitions')
    ->select('level', 'is_active')
    ->get()
    ->toArray();

$matrix = GovernanceMatrix::fromRows($rows);
```

**Input Format:**
```php
[
    ['level' => 0, 'is_active' => true],
    ['level' => 1, 'is_active' => true],
    ['level' => 2, 'is_active' => false],
]
```

**Behavior:**
- Uses diagonal seed: `geoLevel = level` (awaits geo_level column)
- Inactive levels become `allowed = false`
- Non-existent cells default to `false`

---

### GovernancePolicy

**Location:** `App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy`

```php
final readonly class GovernancePolicy {
    public function __construct(GovernanceMatrix $matrix);

    public function assertAllowed(GovernanceAssignment $a): void;
}
```

**Methods:**

#### assertAllowed(GovernanceAssignment $a): void

Enforce the policy. Throws if invalid.

```php
$policy->assertAllowed($assignment);
// throws DomainException if not allowed
```

**Throws:** `DomainException` with message:  
`"Invalid governance assignment: {gov} × {geo} not allowed"`

---

## Use Case Classes

### CreateCommitteeCommand

**Location:** `App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeCommand`

```php
final readonly class CreateCommitteeCommand {
    public string $name;
    public GovernanceAssignment $assignment;

    public function __construct(
        string $name,
        GovernanceAssignment $assignment
    );
}
```

---

### CreateCommitteeHandler

**Location:** `App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeHandler`

```php
final readonly class CreateCommitteeHandler {
    public function __construct(
        CommitteeRepositoryPort $repository,
        EventBusPort $eventBus,
        GovernancePolicy $policy
    );

    public function handle(CreateCommitteeCommand $command): string;
}
```

**Methods:**

#### handle(CreateCommitteeCommand $command): string

Execute the use case.

```php
$committeeId = $handler->handle(
    new CreateCommitteeCommand(
        'Ethics Committee',
        new GovernanceAssignment(2, 2, GeoUnitId::fromInt(15))
    )
);
// returns UUID string, or throws DomainException
```

**Returns:** Committee ID as string (UUID)

**Throws:** `DomainException` if policy rejects assignment

**Side Effects:**
- Saves committee to repository
- Publishes `CommitteeEstablished` event

---

## Aggregate

### ConstitutionalCommittee

**Location:** `App\Contexts\Membership\Domain\Committee\ConstitutionalCommittee`

```php
final class ConstitutionalCommittee {
    public static function establish(
        CommitteeId $id,
        string $name,
        GovernanceAssignment $assignment,
        DateTimeImmutable $at,
        GovernancePolicy $policy
    ): self;

    public static function reconstitute(
        CommitteeId $id,
        string $name,
        GovernanceAssignment $assignment,
        DateTimeImmutable $at
    ): self;

    public function id(): CommitteeId;
    public function getName(): string;
    public function getAssignment(): GovernanceAssignment;
    public function getEstablishedAt(): DateTimeImmutable;
}
```

**Methods:**

#### establish(...): self (Static Factory)

Create a new committee with policy validation.

```php
$committee = ConstitutionalCommittee::establish(
    id: CommitteeId::generate(),
    name: 'District Committee',
    assignment: new GovernanceAssignment(2, 2, GeoUnitId::fromInt(15)),
    at: new DateTimeImmutable(),
    policy: $policy
);
// throws DomainException if policy rejects
// records CommitteeEstablished event on success
```

**Key:** Policy check happens FIRST, before aggregate construction.

---

#### reconstitute(...): self (Static Factory)

Reconstruct from persistence (hydration). No policy check.

```php
$committee = ConstitutionalCommittee::reconstitute(
    id: CommitteeId::fromString('...'),
    name: 'District Committee',
    assignment: new GovernanceAssignment(2, 2, GeoUnitId::fromInt(15)),
    at: $savedDateTime
);
// Does NOT check policy — trust persistence layer
// Does NOT record event — it's already persisted
```

---

#### id(): CommitteeId

Get the committee's ID.

```php
$id = $committee->id();
echo $id->value();  // UUID string
```

---

#### getName(): string

Get the committee name.

```php
echo $committee->getName();  // "District Committee"
```

---

#### getAssignment(): GovernanceAssignment

Get the governance assignment.

```php
$assignment = $committee->getAssignment();
echo $assignment->governanceLevel;  // 2
echo $assignment->geoLevel;          // 2
echo $assignment->geoUnitId->toInt(); // 15
```

---

#### getEstablishedAt(): DateTimeImmutable

Get when the committee was established.

```php
$when = $committee->getEstablishedAt();
echo $when->format('Y-m-d H:i:s');
```

---

## Events

### CommitteeEstablished

**Location:** `App\Contexts\Membership\Domain\Committee\Events\CommitteeEstablished`

```php
final class CommitteeEstablished extends AbstractDomainEvent {
    public string $committeeId;
    public int $governanceLevel;
    public int $geoLevel;
    public string $geoUnitId;
    public DateTimeImmutable $establishedAt;

    public function __construct(
        string $committeeId,
        int $governanceLevel,
        int $geoLevel,
        string $geoUnitId,
        DateTimeImmutable $establishedAt
    );
}
```

---

## Test Doubles

### FakeEventBus

**Location:** `Tests\Doubles\FakeEventBus`

```php
final class FakeEventBus implements EventBusPort {
    public array $publishedEvents = [];

    public function publish(object $event): void;

    public function hasPublished(string $eventClassName): bool;
}
```

**Usage:**
```php
$bus = new FakeEventBus();
$handler->handle($command);

if ($bus->hasPublished('CommitteeEstablished')) {
    // event was published
}

// Access all events
foreach ($bus->publishedEvents as $event) {
    // $event is CommitteeEstablished
}
```

---

### InMemoryCommitteeRepository

**Location:** `Tests\Doubles\InMemoryCommitteeRepository`

```php
final class InMemoryCommitteeRepository implements CommitteeRepositoryPort {
    public function save(ConstitutionalCommittee $committee): void;
    public function get(CommitteeId $id): ?ConstitutionalCommittee;
}
```

**Usage:**
```php
$repo = new InMemoryCommitteeRepository();
$handler = new CreateCommitteeHandler($repo, $bus, $policy);

$handler->handle($command);

$retrieved = $repo->get(CommitteeId::fromString($id));
$this->assertNotNull($retrieved);
```

---

## Cross-Context Value Objects

### GeoUnitId

**Location:** `App\Contexts\Geography\Domain\ValueObjects\GeoUnitId`

```php
readonly class GeoUnitId {
    public static function fromInt(int $value): self;
    public static function fromString(string $value): self;

    public function toInt(): int;
    public function toString(): string;
    public function equals(GeoUnitId $other): bool;
}
```

**Usage:**
```php
$geo = GeoUnitId::fromInt(15);
echo $geo->toString();  // "15"
echo $geo->toInt();     // 15

if ($geo->equals(GeoUnitId::fromInt(15))) {
    // same unit
}
```

---

## Ports (Interfaces)

### CommitteeRepositoryPort

```php
interface CommitteeRepositoryPort {
    public function save(ConstitutionalCommittee $committee): void;
    public function get(CommitteeId $id): ?ConstitutionalCommittee;
}
```

---

### EventBusPort

```php
interface EventBusPort {
    public function publish(object $event): void;
}
```

---

## Exception

### DomainException

```php
class DomainException extends Exception {}
```

**Thrown by:**
- `GovernancePolicy::assertAllowed()` — invalid assignment
- `CommitteeId::__construct()` — invalid ID
- `GeoUnitId::__construct()` — invalid unit ID

**Catch and handle:**
```php
try {
    $policy->assertAllowed($assignment);
} catch (DomainException $e) {
    return back()->withErrors(['assignment' => $e->getMessage()]);
}
```

---

## Quick Examples

### Example 1: Simple Validation

```php
$matrix = GovernanceMatrix::fromRows($rows);
$policy = new GovernancePolicy($matrix);

try {
    $policy->assertAllowed(
        new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
    );
    echo "Allowed!";
} catch (DomainException $e) {
    echo "Forbidden: " . $e->getMessage();
}
```

### Example 2: Create Committee in Handler

```php
$handler = new CreateCommitteeHandler(
    $repository,
    $eventBus,
    $policy
);

$id = $handler->handle(new CreateCommitteeCommand(
    name: 'District Committee',
    assignment: new GovernanceAssignment(2, 2, GeoUnitId::fromInt(15))
));

echo "Created: $id";
```

### Example 3: Test with Fakes

```php
public function test_creates_committee(): void
{
    $policy = new GovernancePolicy(
        GovernanceMatrix::fromRows([['level' => 2, 'is_active' => true]])
    );

    $handler = new CreateCommitteeHandler(
        new InMemoryCommitteeRepository(),
        $eventBus = new FakeEventBus(),
        $policy
    );

    $id = $handler->handle(new CreateCommitteeCommand(
        'Test',
        new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
    ));

    $this->assertIsString($id);
    $this->assertTrue($eventBus->hasPublished('CommitteeEstablished'));
}
```

---

## Type Hints Cheat Sheet

```php
// Import these at the top
use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;
use App\Contexts\Membership\Domain\Committee\Policies\GovernanceMatrix;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ConstitutionalCommittee;
use App\Contexts\Membership\Domain\Committee\Events\CommitteeEstablished;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use DomainException;
```

---

**See also:** `GEO_GOVERNANCE_MATRIX.md` for architecture details.
