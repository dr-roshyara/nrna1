<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\Events\CommitteeCreated;
use App\Contexts\Membership\Domain\Committee\Events\CommitteeLifecycleChanged;
use App\Contexts\Membership\Domain\Committee\Events\CommitteeParentAttached;
use App\Contexts\Membership\Domain\Committee\Events\CommitteeTermUpdated;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeFacts;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\StructuralOperationalState;
use App\Contexts\Membership\Domain\Committee\ValueObjects\TermPeriod;
use DateTimeImmutable;
use DomainException;

final class CommitteeAggregate
{
    private CommitteeId $id;
    private string $name;
    private int $levelIndex;
    private StructuralOperationalState $state;
    private ?TermPeriod $term;
    private ?CommitteeId $parentId;
    private DomainEventsBuffer $events;

    private function __construct(
        CommitteeId $id,
        string $name,
        int $levelIndex,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->levelIndex = $levelIndex;
        $this->state = StructuralOperationalState::ACTIVE;
        $this->term = null;
        $this->parentId = null;
        $this->events = new DomainEventsBuffer();
    }

    public static function create(
        CommitteeId $id,
        string $name,
        int $levelIndex,
    ): self {
        $committee = new self($id, $name, $levelIndex);

        $committee->events->record(
            new CommitteeCreated(
                aggregateId: $id->value(),
                name: $name,
                levelIndex: $levelIndex,
                occurredAt: new DateTimeImmutable(),
            )
        );

        return $committee;
    }

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

    public function state(): StructuralOperationalState
    {
        return $this->state;
    }

    public function term(): ?TermPeriod
    {
        return $this->term;
    }

    public function parentId(): ?CommitteeId
    {
        return $this->parentId;
    }

    /**
     * Attach to parent committee (structural hierarchy).
     *
     * Enforces INV-05: Committee cannot attach to itself.
     * INV-06 (cycle detection) enforced by application layer validator.
     *
     * @throws DomainException If attaching to self
     */
    public function attachToParent(CommitteeId $parentId): void
    {
        if ($this->id->equals($parentId)) {
            throw new DomainException('Committee cannot attach to itself (INV-05)');
        }

        $this->parentId = $parentId;

        $this->events->record(
            new CommitteeParentAttached(
                aggregateId: $this->id->value(),
                parentId: $parentId->value(),
                occurredAt: new DateTimeImmutable(),
            )
        );
    }

    /**
     * Start a term for this committee.
     *
     * Enforces INV-03: Term end must be after start.
     */
    public function startTerm(TermPeriod $period): void
    {
        $this->term = $period;

        $this->events->record(
            new CommitteeTermUpdated(
                aggregateId: $this->id->value(),
                termStart: $period->start(),
                termEnd: $period->end(),
                occurredAt: new DateTimeImmutable(),
            )
        );
    }

    /**
     * Extend the current term.
     *
     * Enforces INV-04: Extended term start must not precede current term start.
     *
     * @throws DomainException If new term violates constraints
     */
    public function extendTerm(TermPeriod $newPeriod): void
    {
        if (
            $this->term !== null &&
            $newPeriod->start() < $this->term->start()
        ) {
            throw new DomainException(
                'Extended term cannot begin before current term start (INV-04)'
            );
        }

        $this->term = $newPeriod;

        $this->events->record(
            new CommitteeTermUpdated(
                aggregateId: $this->id->value(),
                termStart: $newPeriod->start(),
                termEnd: $newPeriod->end(),
                occurredAt: new DateTimeImmutable(),
            )
        );
    }

    /**
     * Suspend this committee.
     *
     * Enforces INV-02: Cannot suspend if already DISSOLVED.
     *
     * @throws DomainException If already dissolved
     */
    public function suspend(MemberId $suspendedBy, string $reason): void
    {
        if ($this->state === StructuralOperationalState::DISSOLVED) {
            throw new DomainException(
                'Dissolved committee cannot be suspended (INV-02)'
            );
        }

        $this->state = StructuralOperationalState::SUSPENDED;

        $this->events->record(
            new CommitteeLifecycleChanged(
                aggregateId: $this->id->value(),
                newState: StructuralOperationalState::SUSPENDED->value,
                changedBy: $suspendedBy->value(),
                reason: $reason,
                occurredAt: new DateTimeImmutable(),
            )
        );
    }

    /**
     * Restore a suspended committee.
     *
     * Enforces INV-01: Cannot restore if DISSOLVED (terminal state).
     *
     * @throws DomainException If already dissolved
     */
    public function restore(MemberId $restoredBy): void
    {
        if ($this->state === StructuralOperationalState::DISSOLVED) {
            throw new DomainException(
                'Dissolved committees cannot be restored (INV-01 — terminal state)'
            );
        }

        $this->state = StructuralOperationalState::ACTIVE;

        $this->events->record(
            new CommitteeLifecycleChanged(
                aggregateId: $this->id->value(),
                newState: StructuralOperationalState::ACTIVE->value,
                changedBy: $restoredBy->value(),
                reason: 'Restored from suspension',
                occurredAt: new DateTimeImmutable(),
            )
        );
    }

    /**
     * Dissolve this committee (terminal state).
     *
     * Dissolution is final. Reinstatement requires new committee + new GovernanceDecision.
     */
    public function dissolve(MemberId $dissolvedBy, string $reason): void
    {
        $this->state = StructuralOperationalState::DISSOLVED;

        $this->events->record(
            new CommitteeLifecycleChanged(
                aggregateId: $this->id->value(),
                newState: StructuralOperationalState::DISSOLVED->value,
                changedBy: $dissolvedBy->value(),
                reason: $reason,
                occurredAt: new DateTimeImmutable(),
            )
        );
    }

    /**
     * Extract facts for policy evaluation.
     *
     * Aggregate exposes FACTS — policies interpret those facts.
     */
    public function toFacts(): CommitteeFacts
    {
        return new CommitteeFacts(
            id: $this->id,
            operationalState: $this->state->value,
            term: $this->term,
            parentId: $this->parentId,
        );
    }

    /**
     * @return array<object>
     */
    public function peekEvents(): array
    {
        return $this->events->peek();
    }

    /**
     * Release and clear recorded events.
     *
     * @return array<object>
     */
    public function releaseEvents(): array
    {
        return $this->events->release();
    }
}
