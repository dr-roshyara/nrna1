<?php

namespace App\Domain\Election\Security;

/**
 * SovereigntyConvergenceFitnessFunction
 *
 * Canonical identifiers for the 10 sovereignty convergence fitness functions.
 * These prevent silent regression of constitutional topology invariants.
 *
 * CONSTITUTIONAL LAW:
 * Each fitness function protects a specific sovereignty invariant.
 * Violation means constitutional regression — NOT a test failure.
 *
 * - F1: No single signal may carry authority semantics (plurality enforcement)
 * - F2: Every evaluation envelope must hold a collection, not a singular authority
 * - F3: Identical snapshots must produce identical replay hashes (determinism)
 * - F4: Only ConstitutionalLegitimacyDecision may derive sovereign enforcement
 * - F5: Adding observations must not collapse plurality or weaken insufficiency
 * - F6: Same evidence → same legitimacy outcome, independent of evaluation topology
 * - F7: Controllers must not derive or create LegitimacyOutcome
 * - F8: UI/projection layer must not reference LegitimacyOutcome
 * - F9: Replay determinism across runtimes (same envelope → same certification)
 * - F10: Only ConstitutionalLegitimacyDecision may map TrustEvaluationState → LegitimacyOutcome
 */
enum SovereigntyConvergenceFitnessFunction: string
{
    case F1_NoSingularAuthorityFields    = 'no_singular_authority_fields';
    case F2_EnvelopePlurality            = 'envelope_plurality';
    case F3_ReplayHashStability          = 'replay_hash_stability';
    case F4_ResolverExclusivity          = 'resolver_exclusivity';
    case F5_ObservationPreservation      = 'observation_preservation';
    case F6_DeterministicConvergence     = 'deterministic_convergence';
    case F7_NoControllerLegitimacy       = 'no_controller_legitimacy';
    case F8_NoProjectionLegitimacy       = 'no_projection_legitimacy';
    case F9_ReplayDeterminism            = 'replay_determinism';
    case F10_LegitimacyDerivationOnly    = 'legitimacy_derivation_only';

    public function label(): string
    {
        return match ($this) {
            self::F1_NoSingularAuthorityFields => 'No Singular Sovereignty Fields',
            self::F2_EnvelopePlurality         => 'Envelope Plurality Invariant',
            self::F3_ReplayHashStability       => 'Replay Hash Stability',
            self::F4_ResolverExclusivity       => 'Resolver Exclusivity',
            self::F5_ObservationPreservation   => 'Observation Preservation',
            self::F6_DeterministicConvergence  => 'Deterministic Convergence',
            self::F7_NoControllerLegitimacy    => 'No Controller Legitimacy',
            self::F8_NoProjectionLegitimacy    => 'No Projection Legitimacy',
            self::F9_ReplayDeterminism         => 'Replay Determinism',
            self::F10_LegitimacyDerivationOnly => 'Legitimacy Derivation Restriction',
        };
    }

    public function purpose(): string
    {
        return match ($this) {
            self::F1_NoSingularAuthorityFields => 'Prevent singular OverlaySignal fields from carrying implicit authority semantics. Only ConstitutionalObservationContext is canonical.',
            self::F2_EnvelopePlurality         => 'Every EvaluationEnvelope must hold a ConstitutionalObservationContext collection. Singular OverlaySignal parameters are forbidden — they imply prioritization and procedural influence.',
            self::F3_ReplayHashStability       => 'Identical constitutional snapshots must produce identical integrity hashes. Hash stability is foundational for replay determinism and cross-runtime sovereignty verification.',
            self::F4_ResolverExclusivity       => 'Only ConstitutionalLegitimacyDecision may derive sovereign enforcement. Controllers, middleware, services, and queue workers must not create LegitimacyOutcome or TrustEvaluationState values.',
            self::F5_ObservationPreservation   => 'Adding observations to ConstitutionalObservationContext must not erase lineage, collapse plurality, or weaken an INSUFFICIENT_EVIDENCE finding. Sovereign monotonicity must be preserved.',
            self::F6_DeterministicConvergence  => 'Same constitutional evidence must produce identical LegitimacyOutcome independent of middleware order, async timing, queue retries, or evaluation topology permutation.',
            self::F7_NoControllerLegitimacy    => 'Controllers must not derive or create LegitimacyOutcome values. They may only receive outcomes from ConstitutionalLegitimacyDecision for telemetry.',
            self::F8_NoProjectionLegitimacy    => 'UI/projection layer must not reference LegitimacyOutcome. Observations are a flat set — presentation ordering must not imply constitutional precedence.',
            self::F9_ReplayDeterminism         => 'Same evidence envelope must produce identical certification outcome across runtimes, serialization cycles, and timezones.',
            self::F10_LegitimacyDerivationOnly => 'Only ConstitutionalLegitimacyDecision may map TrustEvaluationState to LegitimacyOutcome. No fromTrustState method or equivalent mapping may exist outside the resolver.',
        };
    }

    /**
     * Whether violation of this fitness function blocks sovereignty transfer.
     */
    public function blocksTransfer(): bool
    {
        return match ($this) {
            self::F4_ResolverExclusivity => true,
            self::F5_ObservationPreservation => true,
            self::F6_DeterministicConvergence => true,
            self::F10_LegitimacyDerivationOnly => true,
            default => false,
        };
    }

    /**
     * Whether violation requires immediate investigation.
     */
    public function requiresInvestigation(): bool
    {
        return match ($this) {
            self::F4_ResolverExclusivity => true,
            self::F3_ReplayHashStability => true,
            self::F5_ObservationPreservation => true,
            self::F6_DeterministicConvergence => true,
            self::F10_LegitimacyDerivationOnly => true,
            default => false,
        };
    }
}
