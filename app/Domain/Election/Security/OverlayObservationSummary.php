<?php

namespace App\Domain\Election\Security;

/**
 * OverlayObservationSummary (D.R.3 - Pure observation semantics)
 *
 * Aggregates signals from all overlays into purely observational properties.
 * Resolver reads these observations and interprets them into capability decisions.
 * Overlays NEVER derive authority — only describe what they found.
 *
 * This class is the constitutional observation aggregate boundary.
 * It must remain purely descriptive with ZERO procedural vocabulary.
 *
 * Properties:
 * - signals: raw OverlaySignal[] observations (CONTEXT_STABLE, EVIDENCE_INCONSISTENT, ATTESTATION_AVAILABLE)
 * - highestConcern: aggregate constitutional concern level (NONE → CRITICAL)
 * - lowestWeight: aggregate evidence quality assessment (DEFINITIVE → WEAK)
 * - hasObservations: true if any signals present (descriptive, NOT "influence")
 *
 * NOT included (removed for sovereignty):
 * - strongestProceduralPath (procedural instructions forbidden)
 * - elevationRequest (elevation is resolver jurisdiction)
 * - hasInfluence (incorrect semantic - overlays don't "influence" authority)
 */
readonly class OverlayObservationSummary
{
    public function __construct(
        public array                      $signals,               // OverlaySignal[] — raw observations
        public ConstitutionalConcernLevel $highestConcern,        // Descriptive: highest concern observed
        public EvidenceWeightCategory     $lowestWeight,          // Descriptive: weakest evidence quality
        public bool                       $hasObservations = false, // Descriptive: observations exist
    ) {}

    /**
     * Factory: no observations detected
     */
    public static function noObservations(): self
    {
        return new self(
            signals: [],
            highestConcern: ConstitutionalConcernLevel::NONE,
            lowestWeight: EvidenceWeightCategory::STRONG,
            hasObservations: false,
        );
    }
}
