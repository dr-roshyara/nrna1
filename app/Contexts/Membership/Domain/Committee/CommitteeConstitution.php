<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\Events\CommitteeCreated;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;

/**
 * CommitteeConstitution — Mandate definition within the Committee aggregate.
 *
 * Holds the constitutional identity and mandate configuration of a committee:
 * identity (CommitteeId), designation (name), and hierarchical level.
 *
 * Lifecycle state management moved to GovernanceState (see Committee::governanceState()).
 * This is a mandate definition entity, not the operational committee (see Committee).
 *
 * Domain event emitted:
 * - CommitteeCreated — constituted as a governing body
 */
final class CommitteeConstitution
{
    private CommitteeId $id;
    private string $name;
    private int $levelIndex;
    private DomainEventsBuffer $events;

    private function __construct(
        CommitteeId $id,
        string $name,
        int $levelIndex,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->levelIndex = $levelIndex;
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
