<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain;

use App\Contexts\Election\Domain\Events\ElectionCorrectionApplied;
use App\Contexts\Election\Domain\Policy\ElectionCorrectionPolicy;
use DateTimeImmutable;

/**
 * The Election aggregate — the part of an election that reacts to a binding
 * determination and DECIDES how the election is corrected. The application handler
 * merely resolves the aggregate and relays the decision; the aggregate owns the rule.
 *
 * Invariants:
 *  - FORWARD-ONLY (ADR-T8/T11): there is no operation to reverse a correction or
 *    rescind a determination — anonymity forbids un-casting votes.
 *  - IDEMPOTENT: the same determination can never produce two corrections, whether it
 *    is redelivered (message duplicate) or re-presented against reconstituted state
 *    (semantic duplicate).
 *
 * Tenant-free (ADR-T16): the aggregate holds no OrganisationId; organisational scope
 * is resolved at the repository/infrastructure boundary, not inside the domain.
 */
final class Election
{
    /** @var list<ElectionCorrectionApplied> */
    private array $recordedEvents = [];

    /** @var array<string, true> determinationIds already applied (idempotency key set) */
    private array $appliedDeterminations = [];

    /**
     * @param list<DeterminationId> $appliedDeterminations
     */
    private function __construct(private readonly ElectionId $id, array $appliedDeterminations = [])
    {
        foreach ($appliedDeterminations as $determinationId) {
            $this->appliedDeterminations[$determinationId->toString()] = true;
        }
    }

    public static function identifiedBy(ElectionId $id): self
    {
        return new self($id);
    }

    /**
     * Rehydrate an election that has already applied the given determinations, so that
     * a re-presented determination is recognised as a semantic duplicate.
     *
     * @param list<DeterminationId> $appliedDeterminations
     */
    public static function reconstitute(ElectionId $id, array $appliedDeterminations): self
    {
        return new self($id, $appliedDeterminations);
    }

    public function applyDetermination(
        DeterminationId $determinationId,
        RulingOutcome $outcome,
        DateTimeImmutable $at,
    ): void {
        if (isset($this->appliedDeterminations[$determinationId->toString()])) {
            return; // idempotent — this determination has already been applied
        }

        $correction = (new ElectionCorrectionPolicy())->decide($outcome);
        if ($correction === null) {
            return; // Dismissed ⇒ no correction, Election stays silent (D-02)
        }

        $this->appliedDeterminations[$determinationId->toString()] = true;
        $this->recordedEvents[] = new ElectionCorrectionApplied($this->id, $determinationId, $correction, $at);
    }

    /**
     * @return list<ElectionCorrectionApplied>
     */
    public function pullEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];

        return $events;
    }
}
