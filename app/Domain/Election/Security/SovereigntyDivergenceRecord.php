<?php

namespace App\Domain\Election\Security;

/**
 * SovereigntyDivergenceRecord
 *
 * Records divergence between legacy enforcement and constitutional enforcement
 * during dual-sovereignty shadow mode.
 *
 * PURPOSE:
 * During D.1 shadow deployment, the legacy gate (canVote(), middleware) still
 * enforces, while the constitutional gate (LegitimacyOutcome) runs in parallel.
 * This record captures every evaluation where the two disagree.
 *
 * CLASSIFICATION:
 * Each divergence is classified by type (DivergenceType) and severity
 * (DivergenceSeverity), enabling operational triage and migration gating.
 *
 * INVARIANT:
 * Zero divergence is NOT optional — it is the gate condition for D.1 completion.
 * CRITICAL and EXISTENTIAL divergences BLOCK sovereignty transfer.
 */
readonly class SovereigntyDivergenceRecord
{
    public function __construct(
        public DivergenceType      $divergenceType,       // Classified divergence type
        public LegitimacyOutcome   $legacyOutcome,        // What the legacy gate decided
        public LegitimacyOutcome   $constitutionalOutcome, // What the constitutional gate would decide
        public bool                $matched,              // true = no divergence
        public DivergenceSeverity  $severity,             // Operational severity
        public string              $snapshotHash,         // replay archaeology
        public array               $context,              // election_id, user_id, route, middleware
        public \DateTimeImmutable  $observedAt,
        public ?string             $divergenceReason = null, // why they differed
    ) {}
}
