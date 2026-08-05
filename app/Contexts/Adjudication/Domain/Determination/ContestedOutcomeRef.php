<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

/**
 * ContestedOutcomeRef — Adjudication's LOCAL value object identifying the
 * ContestedOutcome a Determination rules on (ADR-UL-01). Reconstructed locally
 * from wire strings per ADR-T16 (Adjudication does NOT import Contestation's VO;
 * duplicate concept, own reconstruction). Reference only — no vote content;
 * `type`/`targetId` reference an Election Result or a prior Determination,
 * never a vote or voter (anonymity, ADR-T11).
 *
 * @immutable
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

    public function equals(self $other): bool
    {
        return $this->electionId->toString() === $other->electionId->toString()
            && $this->type === $other->type
            && $this->targetId->toString() === $other->targetId->toString();
    }
}
