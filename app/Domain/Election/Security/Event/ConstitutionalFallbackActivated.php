<?php

namespace App\Domain\Election\Security\Event;

/**
 * ConstitutionalFallbackActivated
 *
 * MIGRATION EVENT — emitted when a rollback condition is triggered
 * and the system reverts to dual sovereignty mode.
 *
 * Triggers (any one activates):
 * - Replay divergence detected
 * - Topology-dependent legitimacy observed
 * - Nondeterministic replay encountered
 * - Procedural fallback path activated
 */
readonly class ConstitutionalFallbackActivated
{
    public function __construct(
        public string              $reason,
        public string              $previousPhase,
        public string              $fallbackPhase,
        public array               $context,
        public \DateTimeImmutable  $occurredAt,
    ) {}
}
