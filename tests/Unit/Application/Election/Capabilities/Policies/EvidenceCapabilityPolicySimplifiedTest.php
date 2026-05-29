<?php

namespace Tests\Unit\Application\Election\Capabilities\Policies;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\Policies\EvidenceCapabilityPolicy;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationState;
use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use PHPUnit\Framework\TestCase;

/**
 * EvidenceCapabilityPolicy Simplified Integration Test (Step 12)
 *
 * Verifies that EvidenceCapabilityPolicy correctly interprets
 * Simplified OverlaySignal descriptive types alongside the old overlay architecture.
 *
 * The policy must handle both overlay architectures during strangler fig migration.
 */
class EvidenceCapabilityPolicySimplifiedTest extends TestCase
{
    private EvidenceCapabilityPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new EvidenceCapabilityPolicy();
    }

    private function makeContext(
        EvidenceEvaluationState $state = EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
        ?OverlaySignal $simplifiedOverlay = null,
    ): CapabilityContext {
        $metadata = [];

        if ($simplifiedOverlay !== null) {
            $metadata['simplified_overlay_signal'] = $simplifiedOverlay;
        }

        return new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: $metadata,
            state: ElectionLifecycleState::VotingActive,
            trust: null, // Old pipeline trust not set — testing Simplified path
        );
    }

    /**
     * CONTEXT_STABLE with SUFFICIENT_EVIDENCE → no overlay concern → abstain.
     * Trust evaluation and overlay both indicate normal operation.
     */
    public function test_context_stable_with_sufficient_evidence_abstains(): void
    {
        $context = new ConstitutionalObservationContext([
            OverlaySignal::contextStable('test_overlay', 'Normal operation', []),
        ]);

        $decision = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        $this->assertNull($decision);
    }

    /**
     * ADDITIONAL_ATTESTATION_PRESENT → reverification required → prohibited.
     * Even with sufficient evidence, an attestation overlay blocks.
     */
    public function test_attestation_present_requires_reverification(): void
    {
        $context = new ConstitutionalObservationContext([
            OverlaySignal::attestationPresent('device_anomaly', 'Device changed', ['fp_match' => false]),
        ]);

        $decision = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        $this->assertNotNull($decision);
        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::ConstitutionalReviewPending, $decision->reason);
    }

    /**
     * EVIDENCE_INCONSISTENT with SUFFICIENT_EVIDENCE → abstain (elevation is non-authoritative).
     * Overlay signals evidence inconsistency but the evaluation is sufficient — let other policies run.
     */
    public function test_evidence_inconsistent_with_sufficient_evidence_abstains(): void
    {
        $context = new ConstitutionalObservationContext([
            OverlaySignal::evidenceInconsistent('registrar_attestation', 'Registrar evidence conflict detected', ['reg' => 'r1']),
        ]);

        $decision = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        // Evidence inconsistency is influence only — resolver decides whether to honor it
        $this->assertNull($decision);
    }

    /**
     * INSUFFICIENT_EVIDENCE → prohibited regardless of overlay signal.
     */
    public function test_insufficient_evidence_prohibits(): void
    {
        $context = new ConstitutionalObservationContext([
            OverlaySignal::contextStable('test_overlay', 'Normal operation', []),
        ]);

        $decision = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::INSUFFICIENT_EVIDENCE,
            $context,
        );

        $this->assertNotNull($decision);
        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::TrustDenied, $decision->reason);
    }

    /**
     * REVIEW_REQUIRED → prohibited regardless of overlay signal.
     */
    public function test_review_required_prohibits(): void
    {
        $context = new ConstitutionalObservationContext([
            OverlaySignal::contextStable('test_overlay', 'Normal operation', []),
        ]);

        $decision = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::REVIEW_REQUIRED,
            $context,
        );

        $this->assertNotNull($decision);
        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::ConstitutionalReviewPending, $decision->reason);
    }

    /**
     * INCONCLUSIVE → prohibited regardless of overlay signal.
     */
    public function test_inconclusive_prohibits(): void
    {
        $context = new ConstitutionalObservationContext([
            OverlaySignal::contextStable('test_overlay', 'Normal operation', []),
        ]);

        $decision = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::INCONCLUSIVE,
            $context,
        );

        $this->assertNotNull($decision);
        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::TrustEvaluationInconclusive, $decision->reason);
    }

    /**
     * ADDITIONAL_ATTESTATION_PRESENT overrides INCONCLUSIVE — reverification is possible.
     */
    public function test_attestation_present_overrides_inconclusive(): void
    {
        // When overlay signals attestation present, the system can attempt reverification
        // rather than denying outright
        $context = new ConstitutionalObservationContext([
            OverlaySignal::attestationPresent('device_anomaly', 'Device fingerprint available for reverification', []),
        ]);

        $decision = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::INCONCLUSIVE,
            $context,
        );

        // ADDITIONAL_ATTESTATION_PRESENT takes precedence: requires constitutional review
        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::ConstitutionalReviewPending, $decision->reason);
    }

    /**
     * Multiple signals with one attestation in context → prohibit.
     * Resolver must check ALL signals for presence, not just first signal.
     * Attestation presence in multi-signal context blocks even with other CONTEXT_STABLE signals.
     */
    public function test_multiple_signals_with_attestation_prohibits(): void
    {
        $context = new ConstitutionalObservationContext([
            OverlaySignal::contextStable('overlay1', 'Normal operation', []),
            OverlaySignal::contextStable('overlay2', 'Normal operation', []),
            OverlaySignal::attestationPresent('device_anomaly', 'Device changed', []),
        ]);

        $decision = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        $this->assertNotNull($decision);
        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::ConstitutionalReviewPending, $decision->reason);
    }

    /**
     * Multiple signals without attestation → follow evaluation state.
     * Only CONTEXT_STABLE and EVIDENCE_INCONSISTENT signals present.
     */
    public function test_multiple_signals_without_attestation_follows_evaluation_state(): void
    {
        $context = new ConstitutionalObservationContext([
            OverlaySignal::contextStable('overlay1', 'Normal operation', []),
            OverlaySignal::evidenceInconsistent('registrar', 'Evidence conflict', []),
            OverlaySignal::contextStable('overlay2', 'Normal operation', []),
        ]);

        $decision = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        // No attestation present → follow evaluation state (SUFFICIENT_EVIDENCE → abstain)
        $this->assertNull($decision);
    }

    /**
     * Signal permutation invariance test — attestation at start vs end.
     * CONSTITUTIONAL INVARIANT: Signal ordering must NOT affect resolver outcome.
     *
     * Permutation 1: [ATTESTATION, CONTEXT_STABLE, CONTEXT_STABLE]
     * Permutation 2: [CONTEXT_STABLE, CONTEXT_STABLE, ATTESTATION]
     *
     * Both must produce identical CapabilityDecision (prohibition).
     */
    public function test_signal_permutation_invariance_attestation_ordering(): void
    {
        $attestation = OverlaySignal::attestationPresent('device_anomaly', 'Device changed', []);
        $stable1 = OverlaySignal::contextStable('overlay1', 'Normal', []);
        $stable2 = OverlaySignal::contextStable('overlay2', 'Normal', []);

        // Permutation 1: attestation first
        $context1 = new ConstitutionalObservationContext([$attestation, $stable1, $stable2]);
        $decision1 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context1,
        );

        // Permutation 2: attestation last
        $context2 = new ConstitutionalObservationContext([$stable1, $stable2, $attestation]);
        $decision2 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context2,
        );

        // Permutation 3: attestation middle
        $context3 = new ConstitutionalObservationContext([$stable1, $attestation, $stable2]);
        $decision3 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context3,
        );

        // All three permutations must produce identical decisions
        $this->assertNotNull($decision1);
        $this->assertTrue($decision1->denies());
        $this->assertEquals(CapabilityDenialReason::ConstitutionalReviewPending, $decision1->reason);

        $this->assertNotNull($decision2);
        $this->assertTrue($decision2->denies());
        $this->assertEquals(CapabilityDenialReason::ConstitutionalReviewPending, $decision2->reason);

        $this->assertNotNull($decision3);
        $this->assertTrue($decision3->denies());
        $this->assertEquals(CapabilityDenialReason::ConstitutionalReviewPending, $decision3->reason);

        // Most critical: decisions must be identical (same reason, same severity)
        $this->assertEquals($decision1->reason, $decision2->reason);
        $this->assertEquals($decision2->reason, $decision3->reason);
    }

    /**
     * Signal permutation invariance test — without attestation.
     * Same signals in different order must produce identical abstention.
     */
    public function test_signal_permutation_invariance_without_attestation(): void
    {
        $stable = OverlaySignal::contextStable('overlay', 'Normal', []);
        $inconsistent = OverlaySignal::evidenceInconsistent('registrar', 'Conflict', []);

        // Permutation 1
        $context1 = new ConstitutionalObservationContext([$stable, $inconsistent]);
        $decision1 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context1,
        );

        // Permutation 2
        $context2 = new ConstitutionalObservationContext([$inconsistent, $stable]);
        $decision2 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context2,
        );

        // Both must abstain identically
        $this->assertNull($decision1);
        $this->assertNull($decision2);
    }

    /**
     * No simplified overlay signal → falls back to standard evaluation path.
     */
    public function test_no_simplified_overlay_falls_back(): void
    {
        $context = $this->makeContext(EvidenceEvaluationState::SUFFICIENT_EVIDENCE);

        // When $context->trust is null AND no simplified overlay, the policy has
        // no trust data to evaluate — should abstain
        $decision = $this->policy->evaluate($context);

        // This follows the existing path: null trust → abstain
        $this->assertNull($decision);
    }

    // ============================================================================
    // GROUP 1 — RESOLVER DETERMINISM TESTS (D.R.3 Phase A)
    // ============================================================================

    /**
     * SOVEREIGN DETERMINISM: Identical input yields identical output across N evaluations.
     * Same ConstitutionalObservationContext → same CapabilityDecision every time.
     */
    public function test_resolver_output_is_deterministic_for_prohibition(): void
    {
        $context = new ConstitutionalObservationContext([
            OverlaySignal::attestationPresent('device_anomaly', 'Device changed', []),
        ]);

        // Evaluate N times with identical input
        $decision1 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        $decision2 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        $decision3 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        // All three must be identical
        $this->assertNotNull($decision1);
        $this->assertNotNull($decision2);
        $this->assertNotNull($decision3);

        $this->assertTrue($decision1->denies());
        $this->assertTrue($decision2->denies());
        $this->assertTrue($decision3->denies());

        // Most critical: all three decisions are identical (same reason, severity)
        $this->assertEquals($decision1->reason, $decision2->reason);
        $this->assertEquals($decision2->reason, $decision3->reason);
    }

    /**
     * SOVEREIGN DETERMINISM: Abstention (null) must also be deterministic.
     * Same signals with no attestation → null every time.
     */
    public function test_resolver_output_is_deterministic_for_abstention(): void
    {
        $context = new ConstitutionalObservationContext([
            OverlaySignal::contextStable('overlay1', 'Normal operation', []),
        ]);

        // Evaluate N times with identical input
        $decision1 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        $decision2 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        $decision3 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        // All three must be null (abstain)
        $this->assertNull($decision1);
        $this->assertNull($decision2);
        $this->assertNull($decision3);
    }

    /**
     * SOVEREIGN DETERMINISM: Idempotent across context reconstructions.
     * If we rebuild context from same signals → same decision.
     */
    public function test_resolver_is_idempotent_across_context_reconstructions(): void
    {
        $signals = [
            OverlaySignal::attestationPresent('device_anomaly', 'Device changed', []),
            OverlaySignal::contextStable('overlay1', 'Normal', []),
        ];

        // First evaluation: original context
        $context1 = new ConstitutionalObservationContext($signals);
        $decision1 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context1,
        );

        // Second evaluation: reconstructed context from same signals
        $context2 = new ConstitutionalObservationContext($signals);
        $decision2 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context2,
        );

        // Third evaluation: newly constructed from signal array
        $context3 = new ConstitutionalObservationContext([
            OverlaySignal::attestationPresent('device_anomaly', 'Device changed', []),
            OverlaySignal::contextStable('overlay1', 'Normal', []),
        ]);
        $decision3 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context3,
        );

        // All three must be identical
        $this->assertNotNull($decision1);
        $this->assertNotNull($decision2);
        $this->assertNotNull($decision3);

        $this->assertEquals($decision1->reason, $decision2->reason);
        $this->assertEquals($decision2->reason, $decision3->reason);
    }

    // ============================================================================
    // GROUP 2 — MONOTONICITY TESTS (D.R.3 Phase A)
    // ============================================================================

    /**
     * MONOTONICITY: Adding non-authoritative signals cannot weaken a prohibition.
     * [attestation] → prohibit; [attestation + stable] → same prohibition.
     */
    public function test_monotonicity_adding_stable_signals_cannot_weaken_prohibition(): void
    {
        $attestation = OverlaySignal::attestationPresent('device_anomaly', 'Device changed', []);
        $stable = OverlaySignal::contextStable('overlay1', 'Normal', []);

        // Baseline: attestation alone → prohibit
        $context1 = new ConstitutionalObservationContext([$attestation]);
        $decision1 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context1,
        );

        // Plus stable signal: attestation + stable → must not weaken
        $context2 = new ConstitutionalObservationContext([$attestation, $stable]);
        $decision2 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context2,
        );

        // Both must prohibit with same reason
        $this->assertNotNull($decision1);
        $this->assertTrue($decision1->denies());

        $this->assertNotNull($decision2);
        $this->assertTrue($decision2->denies());

        $this->assertEquals($decision1->reason, $decision2->reason);
    }

    /**
     * MONOTONICITY: Adding evidence inconsistency signals cannot weaken attestation prohibition.
     * [attestation] → prohibit; [attestation + inconsistent] → same prohibition.
     */
    public function test_monotonicity_adding_inconsistent_signals_cannot_weaken_prohibition(): void
    {
        $attestation = OverlaySignal::attestationPresent('device_anomaly', 'Device changed', []);
        $inconsistent = OverlaySignal::evidenceInconsistent('registrar', 'Conflict', []);

        // Baseline: attestation alone → prohibit
        $context1 = new ConstitutionalObservationContext([$attestation]);
        $decision1 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context1,
        );

        // Plus inconsistent signal: attestation + inconsistent → must not weaken
        $context2 = new ConstitutionalObservationContext([$attestation, $inconsistent]);
        $decision2 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context2,
        );

        // Both must prohibit with same reason
        $this->assertNotNull($decision1);
        $this->assertTrue($decision1->denies());

        $this->assertNotNull($decision2);
        $this->assertTrue($decision2->denies());

        $this->assertEquals($decision1->reason, $decision2->reason);
    }

    /**
     * MONOTONICITY: Empty context is weakest possible state.
     * Empty signal set follows only evaluation state (cannot strengthen to prohibition).
     */
    public function test_monotonicity_empty_context_is_weakest_possible_state(): void
    {
        $emptyContext = new ConstitutionalObservationContext([]);

        // Empty context with SUFFICIENT_EVIDENCE → abstain
        $decision1 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $emptyContext,
        );
        $this->assertNull($decision1);

        // Empty context with INSUFFICIENT_EVIDENCE → prohibit (evaluation state decides)
        $decision2 = $this->policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::INSUFFICIENT_EVIDENCE,
            $emptyContext,
        );
        $this->assertNotNull($decision2);
        $this->assertTrue($decision2->denies());

        // Empty context + attestation would strengthen to prohibition
        // but this test verifies baseline: empty alone follows evaluation state only
    }
}
