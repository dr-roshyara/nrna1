<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\Events;

use App\Contexts\Election\Domain\CorrectionType;
use App\Contexts\Election\Domain\DeterminationId;
use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\ElectionId;
use DateTimeImmutable;

/**
 * Historical fact: the Election applied a correction in reaction to a binding
 * determination (Catalog 50-05).
 *
 * Event boundary: carries ONLY Election-owned identity. It never carries the
 * Contestation `ChallengeId`/challenge reference (Election does not know the
 * Challenge) and never any voter↔vote linkage (anonymity, ADR-T11). @immutable
 */
final readonly class ElectionCorrectionApplied implements DomainEvent
{
    public function __construct(
        public ElectionId $electionId,
        public DeterminationId $determinationId,
        public CorrectionType $correctionType,
        public DateTimeImmutable $appliedAt,
    ) {
    }
}
