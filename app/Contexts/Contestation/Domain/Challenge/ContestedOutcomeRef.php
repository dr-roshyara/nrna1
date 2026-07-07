<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge;

/**
 * ContestedOutcomeRef — the Value Object identifying the ContestedOutcome a
 * Challenge contests (ADR-UL-01). Replaces the former opaque `TargetRef:string`
 * (Primitive Obsession). A ContestedOutcome is an authoritative outcome of the
 * constitutional process — today an Election Result or a prior Determination
 * (BDR v1.1 certified term). Election-scoped by construction: a Challenge is
 * scoped to exactly one Election via this reference's `electionId`.
 *
 * Reference ONLY — carries no vote content; `type`/`targetId` reference a
 * Result or Determination, never a vote or voter (anonymity — ADR-T11).
 */
final readonly class ContestedOutcomeRef
{
    private function __construct(
        public ElectionId $electionId,
        public TargetType $type,
        public TargetId $targetId,
    ) {
    }

    public static function of(ElectionId $electionId, TargetType $type, TargetId $targetId): self
    {
        return new self($electionId, $type, $targetId);
    }

    // NOTE: reconstruction from wire strings (ADR-T16) is an INFRASTRUCTURE
    // concern — a mapper/hydrator in each consuming context's Infrastructure
    // layer builds the VO from strings. The VO itself owns identity, equality,
    // and invariants only — not transport. (Added in the v2 hydrators, step 2.)

    public function equals(self $other): bool
    {
        return $this->electionId->toString() === $other->electionId->toString()
            && $this->type === $other->type
            && $this->targetId->toString() === $other->targetId->toString();
    }
}
