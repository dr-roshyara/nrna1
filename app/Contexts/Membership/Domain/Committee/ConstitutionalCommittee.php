<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\Events\CommitteeEstablished;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\Jurisdiction;
use App\Shared\Domain\Concerns\RecordsEvents;
use DateTimeImmutable;

/**
 * ConstitutionalCommittee — the constitutional essence of a committee.
 *
 * Represents who the committee IS, not how it operates.
 * This is the governance identity: name, jurisdiction, and establishment fact.
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
        private readonly Jurisdiction $jurisdiction,
        private readonly DateTimeImmutable $establishedAt,
    ) {}

    /**
     * Establish a new governance authority.
     *
     * This is a named domain factory, not a public constructor.
     * Committee establishment is a domain event, not primitive construction.
     */
    public static function establish(
        CommitteeId $id,
        string $name,
        Jurisdiction $jurisdiction,
        DateTimeImmutable $establishedAt,
    ): self {
        $committee = new self($id, $name, $jurisdiction, $establishedAt);

        $committee->recordEvent(new CommitteeEstablished(
            committeeId: $id->value(),
            name: $name,
            jurisdiction: $jurisdiction->toString(),
        ));

        return $committee;
    }

    /**
     * Reconstitute from persistence (hydration, not a business event).
     */
    public static function reconstitute(
        CommitteeId $id,
        string $name,
        Jurisdiction $jurisdiction,
        DateTimeImmutable $establishedAt,
    ): self {
        return new self($id, $name, $jurisdiction, $establishedAt);
    }

    public function getId(): CommitteeId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getJurisdiction(): Jurisdiction
    {
        return $this->jurisdiction;
    }

    public function getEstablishedAt(): DateTimeImmutable
    {
        return $this->establishedAt;
    }
}
