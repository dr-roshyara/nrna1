<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\Events\CommitteeEstablished;
use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;
use App\Contexts\Membership\Domain\Committee\ValueObjects\Jurisdiction;
use App\Shared\Domain\Concerns\RecordsEvents;
use DateTimeImmutable;

/**
 * ConstitutionalCommittee — the constitutional essence of a committee.
 *
 * Represents who the committee IS, not how it operates.
 * This is the governance identity: name, assignment, and establishment fact.
 *
 * Operational details (structure, membership, geography) belong in the
 * legacy Committee aggregate and are linked later through a bridge.
 *
 * Domain event:
 * - CommitteeEstablished — a governance authority became constitutionally active
 */
final class ConstitutionalCommittee
{
    use RecordsEvents;

    private function __construct(
        private readonly CommitteeId $id,
        private readonly string $name,
        private readonly GovernanceAssignment $assignment,
        private readonly DateTimeImmutable $establishedAt,
    ) {}

    /**
     * Establish a new governance authority.
     *
     * This is a named domain factory, not a public constructor.
     * Committee establishment is a domain event, not primitive construction.
     * Validates the assignment via policy before recording the event.
     */
    public static function establish(
        CommitteeId $id,
        string $name,
        GovernanceAssignment $assignment,
        DateTimeImmutable $at,
        GovernancePolicy $policy,
    ): self {
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

    /**
     * Reconstitute from persistence (hydration, not a business event).
     * Does NOT validate assignment — trust persistence layer.
     */
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

    public function getId(): CommitteeId
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
