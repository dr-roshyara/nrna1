<?php

namespace App\Domain\Election\Replay\Event;

/**
 * ReplayDivergenceDetected
 *
 * REPLAY EVENT — emitted when a replay session produces a certification
 * where the re-evaluated outcome DIFFERS from the original outcome.
 *
 * This is a constitutional integrity signal: the evaluation pipeline
 * has changed in a way that affects outcomes for this evidence set.
 */
readonly class ReplayDivergenceDetected
{
    public function __construct(
        public string              $sessionId,
        public string              $envelopeHash,
        public string              $expectedOutcome,
        public string              $actualOutcome,
        public \DateTimeImmutable  $occurredAt,
    ) {}
}
