<?php

namespace App\Domain\Election\Replay\Event;

/**
 * ReplaySessionOpened
 *
 * REPLAY EVENT — emitted when a replay session is initialized
 * with a sealed evidence envelope.
 */
readonly class ReplaySessionOpened
{
    public function __construct(
        public string              $sessionId,
        public string              $envelopeHash,
        public string              $electionIdentifier,
        public string              $compatibilityVersion,
        public \DateTimeImmutable  $occurredAt,
    ) {}
}
