<?php

namespace App\Domain\Election\Security\Event;

/**
 * ObservationRecorded
 *
 * OBSERVATIONAL EVENT — emitted when an overlay observation is recorded.
 * Pure telemetry: carries observation finding without any authority semantics.
 *
 * CONSTRAINT: Observation events must never derive or imply legitimacy.
 */
readonly class ObservationRecorded
{
    public function __construct(
        public string              $overlayIdentifier,
        public string              $finding,
        public array               $evidenceContext,
        public string              $electionId,
        public \DateTimeImmutable  $occurredAt,
    ) {}
}
