<?php

namespace App\Domain\Election\Security;

/**
 * DivergenceType
 *
 * Classification of sovereignty divergence between legacy and constitutional enforcement.
 *
 * CONSTITUTIONAL LAW:
 * Each divergence type has distinct operational meaning and severity.
 * Classification determines alerting, telemetry, and migration blocking criteria.
 *
 * - PROCEDURAL_OVERREACH:   Legacy denied, constitutional would allow (false illegitimacy)
 * - SOVEREIGNTY_LEAK:       Legacy allowed, constitutional would deny (false legitimacy)
 * - LINEAGE_MISMATCH:       Both deny/allowed, but for different constitutional reasons
 * - TOPOLOGY_MISMATCH:      Both allow/deny, but based on different evidence topology
 * - CONSTITUTIONAL_UNCERTAINTY: Legacy definitive, constitutional deferred (review class mismatch)
 * - EXPOSURE:               Legacy allowed, constitutional flagged for investigation (replay risk)
 */
enum DivergenceType: string
{
    case ProceduralOverreach     = 'procedural_overreach';
    case SovereigntyLeak         = 'sovereignty_leak';
    case LineageMismatch         = 'lineage_mismatch';
    case TopologyMismatch        = 'topology_mismatch';
    case ConstitutionalUncertainty = 'constitutional_uncertainty';
    case Exposure                = 'exposure';

    /**
     * Classify divergence between legacy and constitutional outcomes.
     *
     * | Legacy | Constitutional | Classification |
     * |--------|---------------|----------------|
     * | Denied | Allowed       | ProceduralOverreach |
     * | Allowed | Denied       | SovereigntyLeak |
     * | Denied | Denied        | LineageMismatch (different basis) |
     * | Allowed | Allowed       | TopologyMismatch (different evidence) |
     * | Denied/Allowed | Deferred | ConstitutionalUncertainty |
     * | Allowed | Investigate   | Exposure |
     */
    public static function classify(
        LegitimacyOutcome $legacy,
        LegitimacyOutcome $constitutional,
        ?string $legacyBasis = null,
        ?string $constitutionalBasis = null,
    ): self {
        // When outcomes match, the classification depends on which outcome:
        // both-denied → LineageMismatch (different constitutional basis)
        // both-allowed → TopologyMismatch (different evidence topology)
        if ($legacy === $constitutional) {
            return match ($legacy) {
                LegitimacyOutcome::Denied => self::LineageMismatch,
                default => self::TopologyMismatch,
            };
        }

        return match (true) {
            $legacy === LegitimacyOutcome::Denied && $constitutional === LegitimacyOutcome::Allowed
                => self::ProceduralOverreach,

            $legacy === LegitimacyOutcome::Allowed && $constitutional === LegitimacyOutcome::Denied
                => self::SovereigntyLeak,

            $legacy === LegitimacyOutcome::Denied && $constitutional === LegitimacyOutcome::Denied
                => self::LineageMismatch,

            $legacy === LegitimacyOutcome::Allowed && $constitutional === LegitimacyOutcome::Allowed
                => self::TopologyMismatch,

            $constitutional === LegitimacyOutcome::Deferred
                => self::ConstitutionalUncertainty,

            $constitutional === LegitimacyOutcome::Investigate
                => self::Exposure,

            default => self::LineageMismatch,
        };
    }
}
