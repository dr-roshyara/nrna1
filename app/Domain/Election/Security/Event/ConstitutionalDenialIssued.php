<?php

namespace App\Domain\Election\Security\Event;

use App\Domain\Election\Security\LegitimacyOutcome;

/**
 * ConstitutionalDenialIssued
 *
 * SOVEREIGN EVENT — emitted when the constitutional enforcement gate
 * blocks a vote submission due to non-Allowed legitimacy outcome.
 *
 * This is distinct from LegitimacyEvaluated — denial is the enforcement
 * action taken based on the evaluation. One evaluation may be evaluated
 * without denial (if legacy gate passes it), and vice versa.
 */
readonly class ConstitutionalDenialIssued
{
    public function __construct(
        public string              $electionId,
        public string              $voterIdentifier,
        public LegitimacyOutcome   $outcome,
        public string              $reason,
        public string              $evidenceEnvelopeHash,
        public \DateTimeImmutable  $occurredAt,
    ) {}
}
