<?php

namespace App\Domain\Election\Security\Event;

use App\Domain\Election\Security\LegitimacyOutcome;

/**
 * LegitimacyEvaluated
 *
 * SOVEREIGN EVENT — emitted when a voter's legitimacy is evaluated.
 * Records what outcome was derived and from what evidence.
 */
readonly class LegitimacyEvaluated
{
    public function __construct(
        public string              $electionId,
        public string              $voterIdentifier,
        public LegitimacyOutcome   $outcome,
        public string              $evidenceEnvelopeHash,
        public string              $policySequenceHash,
        public string              $denialReason,
        public \DateTimeImmutable  $occurredAt,
    ) {}
}
