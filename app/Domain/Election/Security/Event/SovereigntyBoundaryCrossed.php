<?php

namespace App\Domain\Election\Security\Event;

use App\Domain\Election\Security\LegitimacyOutcome;

/**
 * SovereigntyBoundaryCrossed
 *
 * MIGRATION EVENT — emitted when dual sovereignty paths produce different
 * outcomes for the same voter, crossing a sovereignty boundary.
 *
 * This is the critical telemetry event for D-phase migration.
 * Zero crossings is the gate condition for D.0.3c (middleware retirement).
 */
readonly class SovereigntyBoundaryCrossed
{
    public function __construct(
        public string              $electionId,
        public string              $voterIdentifier,
        public LegitimacyOutcome   $constitutionalOutcome,
        public LegitimacyOutcome   $legacyOutcome,
        public string              $divergenceType,
        public string              $evidenceEnvelopeHash,
        public \DateTimeImmutable  $occurredAt,
    ) {}
}
