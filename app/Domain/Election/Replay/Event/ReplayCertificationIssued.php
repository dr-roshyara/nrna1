<?php

namespace App\Domain\Election\Replay\Event;

/**
 * ReplayCertificationIssued
 *
 * REPLAY EVENT — emitted when a replay session produces a certification
 * where the re-evaluated outcome matches the original outcome.
 *
 * This confirms the replay determinism invariant for this evidence set.
 */
readonly class ReplayCertificationIssued
{
    public function __construct(
        public string              $sessionId,
        public string              $envelopeHash,
        public string              $outcome,
        public string              $certificationHash,
        public \DateTimeImmutable  $occurredAt,
    ) {}
}
