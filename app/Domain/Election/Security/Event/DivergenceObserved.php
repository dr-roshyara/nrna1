<?php

namespace App\Domain\Election\Security\Event;

/**
 * DivergenceObserved
 *
 * OBSERVATIONAL EVENT — emitted when sovereignty divergence is detected
 * between constitutional and procedural authority paths.
 *
 * Non-authoritative: divergence observations inform migration decisions
 * but never feed back into the sovereignty loop.
 */
readonly class DivergenceObserved
{
    public function __construct(
        public string              $electionId,
        public string              $voterIdentifier,
        public string              $divergenceCategory,
        public string              $constitutionalOutcome,
        public string              $legacyOutcome,
        public string              $evidenceEnvelopeHash,
        public \DateTimeImmutable  $occurredAt,
    ) {}
}
