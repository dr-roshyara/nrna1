<?php

namespace App\Domain\Election\Security\Event;

/**
 * LegitimacyGranted
 *
 * SOVEREIGN EVENT — emitted when a voter is granted legitimacy
 * (Allowed outcome) and proceeds past the constitutional gate.
 *
 * Complements ConstitutionalDenialIssued — together they provide
 * complete sovereign enforcement observability.
 */
readonly class LegitimacyGranted
{
    public function __construct(
        public string              $electionId,
        public string              $voterIdentifier,
        public string              $evidenceEnvelopeHash,
        public \DateTimeImmutable  $occurredAt,
    ) {}
}
