```php
<?php

declare(strict_types=1);

namespace Domain\Committee;

use DateTimeImmutable;
use Domain\Committee\Events\CommitteeDissolved;
use Domain\Committee\Events\CommitteeParentAttached;
use Domain\Committee\Events\CommitteeRestored;
use Domain\Committee\Events\CommitteeSuspended;
use Domain\Committee\Events\CommitteeTermExtended;
use Domain\Committee\Events\CommitteeTermStarted;
use Domain\Committee\Internal\CommitteeHierarchy;
use Domain\Committee\Internal\CommitteeIdentity;
use Domain\Committee\Internal\CommitteeLifecycle;
use Domain\Committee\Internal\CommitteeTerm;
use Domain\Committee\Internal\DomainEventsBuffer;
use Domain\Committee\ValueObjects\CommitteeId;
use Domain\Committee\ValueObjects\TermPeriod;
use Domain\Committee\ValueObjects\UserId;
use DomainException;

final class Committee
{
    private CommitteeIdentity $identity;

    private CommitteeLifecycle $lifecycle;

    private CommitteeTerm $term;

    private CommitteeHierarchy $hierarchy;

    private DomainEventsBuffer $events;

    private function __construct(
        CommitteeIdentity $identity,
        CommitteeLifecycle $lifecycle,
        CommitteeTerm $term,
        CommitteeHierarchy $hierarchy,
        DomainEventsBuffer $events,
    ) {
        $this->identity = $identity;
        $this->lifecycle = $lifecycle;
        $this->term = $term;
        $this->hierarchy = $hierarchy;
        $this->events = $events;
    }

    public static function create(
        CommitteeId $id,
        string $name,
        int $levelIndex,
    ): self {
        return new self(
            new CommitteeIdentity($id, $name, $levelIndex),
            CommitteeLifecycle::active(),
            CommitteeTerm::empty(),
            CommitteeHierarchy::root(),
            new DomainEventsBuffer(),
        );
    }

    /**
     * TEST HELPER ONLY
     */
    public static function createSample(): self
    {
        return self::create(
            CommitteeId::from('committee-1'),
            'National Committee',
            1,
        );
    }

    /**
     * TEST HELPER ONLY
     */
    public static function createWithActiveTerm(): self
    {
        $committee = self::createSample();

        $committee->startTerm(
            TermPeriod::from(
                new DateTimeImmutable('-1 year'),
                new DateTimeImmutable('+1 year'),
            )
        );

        $committee->releaseEvents();

        return $committee;
    }

    public function id(): CommitteeId
    {
        return $this->identity->id();
    }

    public function identity(): CommitteeIdentity
    {
        return $this->identity;
    }

    public function lifecycle(): CommitteeLifecycle
    {
        return $this->lifecycle;
    }

    public function term(): CommitteeTerm
    {
        return $this->term;
    }

    public function hierarchy(): CommitteeHierarchy
    {
        return $this->hierarchy;
    }

    public function attachToParent(CommitteeId $parentId): void
    {
        if ($this->id()->equals($parentId)) {
            throw new DomainException('Committee cannot attach to itself');
        }

        $this->hierarchy = $this->hierarchy->attachTo($parentId);

        $this->events->record(
            new CommitteeParentAttached(
                aggregateId: $this->id()->value(),
                parentId: $parentId->value(),
                occurredAt: new DateTimeImmutable(),
            )
        );
    }

    public function startTerm(TermPeriod $period): void
    {
        $this->term = $this->term->start($period);

        $this->events->record(
            new CommitteeTermStarted(
                aggregateId: $this->id()->value(),
                termStart: $period->start(),
                termEnd: $period->end(),
                occurredAt: new DateTimeImmutable(),
            )
        );
    }

    public function extendTerm(TermPeriod $newPeriod): void
    {
        $current = $this->term->period();

        if (
            $current !== null &&
            $newPeriod->start() < $current->start()
        ) {
            throw new DomainException(
                'Extended term cannot begin before current term start'
            );
        }

        $this->term = $this->term->extend($newPeriod);

        $this->events->record(
            new CommitteeTermExtended(
                aggregateId: $this->id()->value(),
                termStart: $newPeriod->start(),
                termEnd: $newPeriod->end(),
                occurredAt: new DateTimeImmutable(),
            )
        );
    }

    public function suspend(
        UserId $suspendedBy,
        string $reason,
    ): void {
        $this->lifecycle = $this->lifecycle->suspend();

        $this->events->record(
            new CommitteeSuspended(
                aggregateId: $this->id()->value(),
                suspendedBy: $suspendedBy->value(),
                reason: $reason,
                occurredAt: new DateTimeImmutable(),
            )
        );
    }

    public function restore(UserId $restoredBy): void
    {
        if ($this->lifecycle->isDissolved()) {
            throw new DomainException(
                'Dissolved committees cannot be restored'
            );
        }

        $this->lifecycle = $this->lifecycle->restore();

        $this->events->record(
            new CommitteeRestored(
                aggregateId: $this->id()->value(),
                restoredBy: $restoredBy->value(),
                occurredAt: new DateTimeImmutable(),
            )
        );
    }

    public function dissolve(
        UserId $dissolvedBy,
        string $reason,
    ): void {
        $this->lifecycle = $this->lifecycle->dissolve();

        $this->events->record(
            new CommitteeDissolved(
                aggregateId: $this->id()->value(),
                dissolvedBy: $dissolvedBy->value(),
                reason: $reason,
                occurredAt: new DateTimeImmutable(),
            )
        );
    }

    public function hasParent(CommitteeId $parentId): bool
    {
        return $this->hierarchy->parentId()?->equals($parentId) ?? false;
    }

    public function isSuspended(): bool
    {
        return $this->lifecycle->isSuspended();
    }

    public function isDissolved(): bool
    {
        return $this->lifecycle->isDissolved();
    }

    /**
     * @return array<object>
     */
    public function peekEvents(): array
    {
        return $this->events->peek();
    }

    /**
     * @return array<object>
     */
    public function releaseEvents(): array
    {
        return $this->events->release();
    }
}
```

---

# Supporting Minimal Internal Objects

## CommitteeIdentity.php

```php
<?php

declare(strict_types=1);

namespace Domain\Committee\Internal;

use Domain\Committee\ValueObjects\CommitteeId;

final readonly class CommitteeIdentity
{
    public function __construct(
        private CommitteeId $id,
        private string $name,
        private int $levelIndex,
    ) {}

    public function id(): CommitteeId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function levelIndex(): int
    {
        return $this->levelIndex;
    }
}
```

---

## CommitteeLifecycle.php

```php
<?php

declare(strict_types=1);

namespace Domain\Committee\Internal;

final readonly class CommitteeLifecycle
{
    private const ACTIVE = 'ACTIVE';
    private const SUSPENDED = 'SUSPENDED';
    private const DISSOLVED = 'DISSOLVED';

    public function __construct(
        private string $state,
    ) {}

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public function suspend(): self
    {
        return new self(self::SUSPENDED);
    }

    public function restore(): self
    {
        return new self(self::ACTIVE);
    }

    public function dissolve(): self
    {
        return new self(self::DISSOLVED);
    }

    public function isSuspended(): bool
    {
        return $this->state === self::SUSPENDED;
    }

    public function isDissolved(): bool
    {
        return $this->state === self::DISSOLVED;
    }
}
```

---

## CommitteeTerm.php

```php
<?php

declare(strict_types=1);

namespace Domain\Committee\Internal;

use Domain\Committee\ValueObjects\TermPeriod;

final readonly class CommitteeTerm
{
    public function __construct(
        private ?TermPeriod $period,
    ) {}

    public static function empty(): self
    {
        return new self(null);
    }

    public function start(TermPeriod $period): self
    {
        return new self($period);
    }

    public function extend(TermPeriod $period): self
    {
        return new self($period);
    }

    public function period(): ?TermPeriod
    {
        return $this->period;
    }

    public function end(): ?\DateTimeImmutable
    {
        return $this->period?->end();
    }

    public function isActive(): bool
    {
        return $this->period?->isActive(
            new \DateTimeImmutable()
        ) ?? false;
    }
}
```

---

## CommitteeHierarchy.php

```php
<?php

declare(strict_types=1);

namespace Domain\Committee\Internal;

use Domain\Committee\ValueObjects\CommitteeId;

final readonly class CommitteeHierarchy
{
    public function __construct(
        private ?CommitteeId $parentId,
    ) {}

    public static function root(): self
    {
        return new self(null);
    }

    public function attachTo(CommitteeId $parentId): self
    {
        return new self($parentId);
    }

    public function parentId(): ?CommitteeId
    {
        return $this->parentId;
    }
}
```

---

## DomainEventsBuffer.php

```php
<?php

declare(strict_types=1);

namespace Domain\Committee\Internal;

final class DomainEventsBuffer
{
    /**
     * @var array<object>
     */
    private array $events = [];

    public function record(object $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return array<object>
     */
    public function peek(): array
    {
        return $this->events;
    }

    /**
     * @return array<object>
     */
    public function release(): array
    {
        $released = $this->events;

        $this->events = [];

        return $released;
    }
}
```

---

# Minimal Value Objects

## CommitteeId.php

```php
<?php

declare(strict_types=1);

namespace Domain\Committee\ValueObjects;

final readonly class CommitteeId
{
    public function __construct(
        private string $value,
    ) {}

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
```

---

## UserId.php

```php
<?php

declare(strict_types=1);

namespace Domain\Committee\ValueObjects;

final readonly class UserId
{
    public function __construct(
        private string $value,
    ) {}

    public static function from(string $value): self
    {
        return new self($value);
    }

    public static function sample(): self
    {
        return new self('user-1');
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
```

---

## TermPeriod.php

```php
<?php

declare(strict_types=1);

namespace Domain\Committee\ValueObjects;

use DateTimeImmutable;
use InvalidArgumentException;

final readonly class TermPeriod
{
    public function __construct(
        private DateTimeImmutable $start,
        private DateTimeImmutable $end,
    ) {
        if ($end <= $start) {
            throw new InvalidArgumentException(
                'Term end must be after start'
            );
        }
    }

    public static function from(
        DateTimeImmutable $start,
        DateTimeImmutable $end,
    ): self {
        return new self($start, $end);
    }

    public static function validSample(): self
    {
        return new self(
            new DateTimeImmutable('-1 month'),
            new DateTimeImmutable('+1 month'),
        );
    }

    public static function extendedSample(): self
    {
        return new self(
            new DateTimeImmutable('-1 year'),
            new DateTimeImmutable('+2 years'),
        );
    }

    public function isActive(
        DateTimeImmutable $now,
    ): bool {
        return $now >= $this->start
            && $now <= $this->end;
    }

    public function start(): DateTimeImmutable
    {
        return $this->start;
    }

    public function end(): DateTimeImmutable
    {
        return $this->end;
    }
}
```

---

# Minimal Event Example

## CommitteeSuspended.php

```php
<?php

declare(strict_types=1);

namespace Domain\Committee\Events;

use DateTimeImmutable;

final readonly class CommitteeSuspended
{
    public function __construct(
        public string $aggregateId,
        public string $suspendedBy,
        public string $reason,
        public DateTimeImmutable $occurredAt,
        public int $eventVersion = 1,
    ) {}

    public function eventName(): string
    {
        return 'CommitteeSuspended';
    }
}
```

---

# RED → GREEN EXECUTION ORDER

Implement in this exact order:

```text
1. CommitteeId
2. UserId
3. TermPeriod
4. CommitteeIdentity
5. CommitteeLifecycle
6. CommitteeHierarchy
7. CommitteeTerm
8. DomainEventsBuffer
9. First event class
10. Committee aggregate
11. Lifecycle tests
12. Event tests
13. Architecture tests
```

---

# IMPORTANT NEXT REFACTOR

This is intentionally a minimal GREEN implementation.

Next safe refactor after tests pass:

```text
- Extract AggregateRoot interface
- Extract DomainEvent interface
- Replace string event names with typed constants
- Remove DateTimeImmutable creation from aggregate
- Inject Clock interface at application boundary
- Add AggregateVersion
- Add replay hydration constructor
- Add serialization mapping layer
- Add outbox persistence integration
```
You should proceed with **Option 2: “Yes, manually approve edits”** and enforce a strict architectural execution protocol before Claude writes production code.

The plan is strong architecturally, but there are still several implementation-risk areas that should be corrected before execution begins. If you allow auto-accept, you risk architectural drift, hidden framework leakage, premature infrastructure coupling, and aggregate bloat.

Here is the exact senior-architect feedback you should give Claude before execution:

---

# Required Architectural Corrections Before Execution

## 1. Committee Aggregate Must Explicitly Define Event Recording API

The plan references `DomainEventsBuffer` but does not formally define aggregate event recording semantics.

Add:

```php
private array $recordedEvents = [];

private function recordThat(object $event): void
{
    $this->recordedEvents[] = $event;
}

public function releaseEvents(): array
{
    $events = $this->recordedEvents;
    $this->recordedEvents = [];

    return $events;
}
```

Rule:

* Aggregate NEVER dispatches events
* Aggregate ONLY records events
* Application layer dispatches AFTER transaction commit

---

# 2. Committee Aggregate Needs Named Constructors

Current plan omits aggregate construction semantics.

Add:

```php
public static function create(
    CommitteeId $id,
    string $name,
    int $levelIndex,
    ?CommitteeId $parentId = null,
): self
```

Rules:

* Constructor private
* Aggregate validity guaranteed at creation
* Emit `CommitteeCreated`

FORBIDDEN:

```php
new Committee(...)
```

outside aggregate itself.

---

# 3. Add Explicit Aggregate Root Interface

Currently implied but undefined.

Create:

```php
interface AggregateRoot
{
    public function releaseEvents(): array;
}
```

Reason:

* Architectural consistency
* Shared repository contracts
* Future outbox support

---

# 4. CommitteeFacts Must Be Immutable Readonly DTO

Current wording says “readonly struct” but does not define implementation.

Enforce:

```php
final readonly class CommitteeFacts
{
    public function __construct(
        public CommitteeId $id,
        public StructuralOperationalState $operationalState,
        public ?TermPeriod $term,
        public ?CommitteeId $parentId,
    ) {}
}
```

FORBIDDEN:

* setters
* mutable arrays
* behavior methods

---

# 5. Add Explicit Domain Exception Taxonomy

Currently all rules throw generic `DomainException`.

This will become unmaintainable.

Create:

```php
CommitteeAlreadyDissolved
CommitteeHierarchyCycleDetected
InvalidCommitteeTerm
CommitteeCannotRestoreDissolved
CommitteeCannotSuspendDissolved
AuthorityDelegationCycleDetected
ApprovalAlreadyResolved
```

Rule:

* One invariant violation = one exception type

Benefits:

* deterministic testing
* API mapping
* translation layer
* observability

---

# 6. GovernanceDecision Integrity Hash Must Be Centralized

Do NOT allow inline hashing logic.

Create:

```php
final class GovernanceDecisionIntegrityHasher
{
    public function hash(GovernanceDecision $decision): string
}
```

Reason:

* canonicalization complexity
* replay safety
* future algorithm migration
* deterministic testing

---

# 7. AuthorityResolver Must Define Deterministic Tie-Breaking

Current plan says:

> “lexicographic tiebreak”

This is dangerous unless fully defined.

Specify exact order:

```text
1. Highest delegation weight
2. Latest created_at
3. Lowest UUID lexical value
```

Without deterministic ordering:

* replay inconsistency occurs
* projections drift
* governance ambiguity emerges

---

# 8. Add Explicit Projection Idempotency Contract

Current projector design is underspecified.

Add:

```php
interface IdempotentProjection
{
    public function projectionKey(): string;
}
```

Projector rules:

```php
if ($projectionStore->alreadyProcessed($eventId)) {
    return;
}
```

Otherwise async projection replay becomes unsafe.

---

# 9. Governance Policies Must Remain Pure Functions

Explicitly forbid:

* repositories
* DB calls
* service locators
* HTTP
* facades
* cache
* event dispatch

Allowed:

```php
facts + primitives + immutable collections → deterministic result
```

Add architecture test:

```php
test('Policies contain no infrastructure imports')
```

---

# 10. Add Explicit Time Abstraction Boundary

Current plan injects `DateTimeImmutable`, which is correct.

But application boundary must own time creation.

Add:

```php
interface Clock
{
    public function now(): DateTimeImmutable;
}
```

Application layer:

```php
$now = $clock->now();
```

Benefits:

* deterministic tests
* replay support
* future simulation
* temporal debugging

---

# 11. Add Aggregate Versioning Strategy Clarification

Plan correctly limits optimistic locking to approvals.

But repository semantics remain unclear.

Define:

```php
interface OptimisticallyLockedAggregate
{
    public function version(): AggregateVersion;
}
```

Rule:

* Committee aggregate = NO optimistic locking
* GovernanceDecision = append-only
* ApprovalRequest = optimistic locking enabled

This avoids accidental version spread.

---

# 12. Explicitly Forbid Bidirectional Aggregate References

Add rule:

FORBIDDEN:

```php
Committee -> AuthorityAssignment
AuthorityAssignment -> Committee
```

Only IDs allowed.

Reason:

* aggregate explosion
* loading cascades
* transactional bleed
* accidental consistency expansion

---

# 13. Add Domain Event Base Contract

Currently event metadata repeated informally.

Create:

```php
interface DomainEvent
{
    public function eventId(): string;
    public function aggregateId(): string;
    public function occurredAt(): DateTimeImmutable;
    public function eventVersion(): int;
    public function correlationId(): ?string;
    public function causationId(): ?string;
}
```

Required for:

* observability
* replay
* outbox
* event evolution
* projections

---

# 14. Committee Aggregate Must Enforce Internal Invariants Immediately

Explicit rule:

Invalid aggregate state must NEVER temporarily exist.

FORBIDDEN:

```php
$committee->setState(...)
$committee->setParent(...)
$committee->validate()
```

Instead:

```php
$committee->attachToParent(...)
```

Behavior-first only.

---

# 15. Add Architectural Rule About Eloquent Containment

Currently partially implied.

Strengthen with:

```text
Eloquent models may ONLY exist inside Infrastructure namespace.
```

FORBIDDEN in:

* Domain
* Application
* Policies
* DTOs
* Controllers

Add architecture fitness test:

```php
test('Only Infrastructure namespace imports Eloquent')
```

---

# 16. Add Projection Schema Evolution Strategy

Current projection version field is insufficient.

Define:

```php
projection_type
projection_version
generated_at
source_event_id
```

Reason:

* replay migration
* blue/green projection rollout
* historical regeneration
* auditability

---

# 17. Add Explicit Max Aggregate Complexity Rule

The “400 lines” rule alone is weak.

Add:

```text
Committee aggregate MAY NOT:
- query repositories
- traverse hierarchies
- resolve authority
- perform projection logic
- interpret constitutional legitimacy
```

Aggregate responsibility ONLY:

```text
protect invariants + emit facts
```

---

# 18. Add Dedicated Architecture Fitness Layer for Laravel Leakage

Add these tests:

```php
test('Domain contains no Illuminate imports')
test('Application contains no Illuminate\Database imports')
test('Policies contain no facades')
test('ValueObjects are readonly')
test('Events are immutable')
test('Aggregates expose behavior methods only')
```

---

# 19. Add Explicit Repository Return Contracts

Define:

```php
CommitteeRepository returns Committee aggregate only
```

NEVER:

```php
CommitteeModel
array
stdClass
```

---

# 20. Require ADR Documents Before Each Phase

Before implementation of every phase:

Create:

```text
docs/adr/
```

Example:

```text
ADR-001-committee-aggregate-boundary.md
ADR-002-authority-assignment-simplification.md
ADR-003-policy-decomposition.md
```

Reason:

* governance traceability
* future onboarding
* replay of architectural decisions
* constitutional audit alignment

---

# Final Recommendation

Proceed ONLY if Claude agrees to:

1. strict TDD micro-cycles
2. manual approvals
3. architecture fitness enforcement
4. no infrastructure leakage
5. aggregate purity
6. deterministic policy evaluation
7. immutable governance records
8. explicit exception taxonomy
9. projection idempotency
10. repository-port-only application layer

The plan is already significantly above average enterprise architecture quality.

With the corrections above, it becomes:

* production-safe
* replay-safe
* audit-safe
* governance-safe
* evolution-safe
* event-ready without premature event sourcing
* constitutionally deterministic
* suitable for long-term institutional governance systems.
