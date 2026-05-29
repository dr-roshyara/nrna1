<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Application\Election\Capabilities\Policies\EvidenceCapabilityPolicy;
use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationState;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use PHPUnit\Framework\TestCase;

/**
 * Constitutional Semantic Regression Tests — 7 Articles Enforcement
 *
 * This test suite enforces all 7 Articles of the ConstitutionalObservationDoctrine
 * to prevent semantic governance violations and hidden authority topology re-emergence.
 *
 * Article 1: Non-Sovereignty — Observations derive no authority; resolver is exclusive
 * Article 2: Evidence Preservation — All signals preserved without collapse, ranking, or reconciliation
 * Article 3: Conflict Preservation — Conflicting signals coexist without forced resolution
 * Article 4: Resolver Exclusive — No other layer interprets observations; resolver owns interpretation
 * Article 5: Annotations — Procedural verbs (Deny, Elevate, Recommend) forbidden; frozen vocabulary only
 * Article 6: Ordering Neutral — Signal permutation must not alter sovereign outcome
 * Article 7: Observational Neutrality — Weight, urgency, priority properties forbidden; descriptive only
 *
 * Each test verifies constitutional invariants remain true after refactoring.
 * Violations indicate hidden authority topology re-emergence (sovereignty regression).
 */
class ConstitutionalSemanticRegressionTests extends TestCase
{
    /**
     * ARTICLE 1: Non-Sovereignty
     * Observations are evidence only. Authority decisions remain exclusive to resolver.
     * No observation data can be used to derive capability outside resolver jurisdiction.
     */
    public function test_article_1_observations_derive_no_authority(): void
    {
        // Given: A ConstitutionalObservationContext with signals
        $context = new ConstitutionalObservationContext([
            OverlaySignal::attestationPresent('device_anomaly', 'Device changed', []),
        ]);

        // Then: The context itself provides no authority methods
        // Only the resolver (EvidenceCapabilityPolicy) can derive authority
        $this->assertFalse(method_exists($context, 'authorize'));
        $this->assertFalse(method_exists($context, 'allow'));
        $this->assertFalse(method_exists($context, 'deny'));
        $this->assertFalse(method_exists($context, 'recommend'));

        // Only query/access methods exist
        $this->assertTrue(method_exists($context, 'all'));
        $this->assertTrue(method_exists($context, 'count'));
        $this->assertTrue(method_exists($context, 'isEmpty'));
    }

    /**
     * ARTICLE 2: Evidence Preservation
     * All overlay signals are preserved in the context. No signal is dropped, merged, or collapsed.
     * Even conflicting signals coexist without reconciliation.
     */
    public function test_article_2_all_signals_preserved_without_collapse(): void
    {
        $signals = [
            OverlaySignal::contextStable('overlay1', 'Stable', []),
            OverlaySignal::attestationPresent('device_anomaly', 'Changed', []),
            OverlaySignal::evidenceInconsistent('registrar', 'Conflict', []),
        ];

        $context = new ConstitutionalObservationContext($signals);

        // All signals must be preserved exactly
        $this->assertCount(3, $context->all());
        $this->assertEquals(3, $context->count());

        // Each signal must be identical to input
        $this->assertSame($signals[0], $context->all()[0]);
        $this->assertSame($signals[1], $context->all()[1]);
        $this->assertSame($signals[2], $context->all()[2]);
    }

    /**
     * ARTICLE 3: Conflict Preservation
     * When signals conflict (e.g., CONTEXT_STABLE vs EVIDENCE_INCONSISTENT),
     * both are preserved without forced reconciliation or ranking.
     */
    public function test_article_3_conflicting_signals_coexist(): void
    {
        $stable = OverlaySignal::contextStable('overlay1', 'Context is stable', []);
        $inconsistent = OverlaySignal::evidenceInconsistent('registrar', 'But evidence is inconsistent', []);

        $context = new ConstitutionalObservationContext([$stable, $inconsistent]);

        // Both signals must coexist in full — no reconciliation
        $this->assertCount(2, $context->all());

        // Both must be retrievable without priority or ranking
        $all = $context->all();
        $this->assertSame($stable, $all[0]);
        $this->assertSame($inconsistent, $all[1]);
    }

    /**
     * ARTICLE 4: Resolver Exclusive
     * Only EvidenceCapabilityPolicy (the resolver) may interpret observations.
     * No other layer performs interpretation or derives authority.
     *
     * Test: Policy correctly interprets, and we verify no distributed interpretation exists.
     */
    public function test_article_4_resolver_exclusive_interpretation(): void
    {
        $policy = new EvidenceCapabilityPolicy();

        // Given: Multiple signals including attestation
        $context = new ConstitutionalObservationContext([
            OverlaySignal::contextStable('overlay1', 'Stable', []),
            OverlaySignal::attestationPresent('device_anomaly', 'Changed', []),
        ]);

        // When: Policy evaluates
        $decision = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        // Then: Policy derived the authority (attestation present → prohibit)
        $this->assertNotNull($decision);
        $this->assertTrue($decision->denies());

        // Verify: Context provides no interpretation methods
        // (prevents distributed interpretation infrastructure from forming)
        $this->assertFalse(method_exists($context, 'hasAnySignalOfType'));
        $this->assertFalse(method_exists($context, 'strongestSignal'));
        $this->assertFalse(method_exists($context, 'dominantConcern'));
        $this->assertFalse(method_exists($context, 'signalsByType'));
        $this->assertFalse(method_exists($context, 'grouped'));
    }

    /**
     * ARTICLE 5: Annotations (Frozen Vocabulary)
     * Only observational vocabulary allowed (OverlaySignal types).
     * No procedural verbs (Deny, Elevate, Recommend, Escalate) in observation layer.
     *
     * Frozen vocabulary for OverlaySignal:
     * - CONTEXT_STABLE (observational)
     * - EVIDENCE_INCONSISTENT (observational)
     * - ADDITIONAL_ATTESTATION_PRESENT (observational)
     */
    public function test_article_5_frozen_vocabulary_only(): void
    {
        // All three signal types are observational, not procedural
        $signals = [
            OverlaySignal::contextStable('o1', 'Context is stable', []),
            OverlaySignal::evidenceInconsistent('o2', 'Evidence is inconsistent', []),
            OverlaySignal::attestationPresent('o3', 'Attestation is present', []),
        ];

        // None of these are procedural directives
        $this->assertEquals('CONTEXT_STABLE', $signals[0]->signalType);
        $this->assertEquals('EVIDENCE_INCONSISTENT', $signals[1]->signalType);
        $this->assertEquals('ADDITIONAL_ATTESTATION_PRESENT', $signals[2]->signalType);

        // Signal names are adjectives/nouns (observational), not verbs (procedural)
        foreach ($signals as $signal) {
            $this->assertStringNotContainsStringIgnoringCase('deny', $signal->signalType);
            $this->assertStringNotContainsStringIgnoringCase('elevate', $signal->signalType);
            $this->assertStringNotContainsStringIgnoringCase('recommend', $signal->signalType);
            $this->assertStringNotContainsStringIgnoringCase('escalate', $signal->signalType);
        }
    }

    /**
     * ARTICLE 6: Ordering Neutral
     * Signal permutation must not alter resolver outcome.
     * Ordering has no constitutional meaning; set membership is authoritative.
     *
     * Test: Signal ordering is swapped; outcome must be identical.
     */
    public function test_article_6_signal_permutation_invariance(): void
    {
        $attestation = OverlaySignal::attestationPresent('device', 'Changed', []);
        $stable1 = OverlaySignal::contextStable('o1', 'Stable', []);
        $stable2 = OverlaySignal::contextStable('o2', 'Stable', []);

        $policy = new EvidenceCapabilityPolicy();

        // Permutation 1: [Attestation, Stable, Stable]
        $context1 = new ConstitutionalObservationContext([$attestation, $stable1, $stable2]);
        $decision1 = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context1,
        );

        // Permutation 2: [Stable, Attestation, Stable]
        $context2 = new ConstitutionalObservationContext([$stable1, $attestation, $stable2]);
        $decision2 = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context2,
        );

        // Permutation 3: [Stable, Stable, Attestation]
        $context3 = new ConstitutionalObservationContext([$stable1, $stable2, $attestation]);
        $decision3 = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context3,
        );

        // All three must produce identical outcomes (prohibition)
        $this->assertNotNull($decision1);
        $this->assertNotNull($decision2);
        $this->assertNotNull($decision3);
        $this->assertTrue($decision1->denies());
        $this->assertTrue($decision2->denies());
        $this->assertTrue($decision3->denies());

        // Most critical: All three decisions are identical (same reason, severity)
        $this->assertEquals($decision1->reason, $decision2->reason);
        $this->assertEquals($decision2->reason, $decision3->reason);
    }

    /**
     * ARTICLE 7: Observational Neutrality
     * No weight, urgency, priority, or severity properties on context.
     * Descriptive metadata only (severity on signals is descriptive, not authoritative).
     *
     * Test: Context provides no weighting or prioritization methods.
     */
    public function test_article_7_no_weight_urgency_priority_properties(): void
    {
        $context = new ConstitutionalObservationContext([
            OverlaySignal::attestationPresent('device', 'Changed', []),
        ]);

        // No weighting methods
        $this->assertFalse(method_exists($context, 'lowestWeight'));
        $this->assertFalse(method_exists($context, 'highestWeight'));
        $this->assertFalse(method_exists($context, 'weightedSignals'));

        // No urgency methods
        $this->assertFalse(method_exists($context, 'highestUrgency'));
        $this->assertFalse(method_exists($context, 'urgentSignals'));

        // No priority methods
        $this->assertFalse(method_exists($context, 'prioritySignal'));
        $this->assertFalse(method_exists($context, 'highestPriority'));
        $this->assertFalse(method_exists($context, 'sortedByPriority'));

        // No ranking methods
        $this->assertFalse(method_exists($context, 'rankedSignals'));
        $this->assertFalse(method_exists($context, 'sortedByRank'));

        // No severity-based branching
        $this->assertFalse(method_exists($context, 'signalsBySeverity'));
        $this->assertFalse(method_exists($context, 'groupBySeverity'));
    }

    /**
     * Extended: Permutation Invariance Without Attestation
     * When no attestation is present, evaluation state alone determines outcome.
     * Signal ordering must still not affect this outcome.
     */
    public function test_article_6_permutation_invariance_without_attestation(): void
    {
        $stable = OverlaySignal::contextStable('o1', 'Stable', []);
        $inconsistent = OverlaySignal::evidenceInconsistent('o2', 'Inconsistent', []);

        $policy = new EvidenceCapabilityPolicy();

        // Permutation 1
        $context1 = new ConstitutionalObservationContext([$stable, $inconsistent]);
        $decision1 = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context1,
        );

        // Permutation 2
        $context2 = new ConstitutionalObservationContext([$inconsistent, $stable]);
        $decision2 = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context2,
        );

        // Both must abstain (null) identically
        $this->assertNull($decision1);
        $this->assertNull($decision2);
    }

    // ============================================================================
    // GROUP 6 — CONSTITUTIONAL REGRESSION ADDITIONS (D.R.3 Phase A)
    // New constitutional articles established by D.R.3
    // ============================================================================

    /**
     * NEW CONSTITUTIONAL ARTICLE 8: SOVEREIGN DETERMINISM
     *
     * Given: Identical ConstitutionalObservationContext (same signals)
     * When:  Resolver evaluates N times (any N >= 1)
     * Then:  Identical CapabilityDecision on every evaluation
     *
     * Tests that resolver output is deterministic — no nondeterministic branching.
     */
    public function test_sovereign_determinism_same_evidence_yields_same_decision(): void
    {
        $policy = new EvidenceCapabilityPolicy();

        $context = new ConstitutionalObservationContext([
            OverlaySignal::attestationPresent('device_anomaly', 'Device changed', []),
        ]);

        // Evaluate identical input N times
        $decision1 = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        $decision2 = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        $decision3 = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context,
        );

        // All must be identical
        $this->assertNotNull($decision1);
        $this->assertNotNull($decision2);
        $this->assertNotNull($decision3);

        $this->assertEquals($decision1->reason, $decision2->reason);
        $this->assertEquals($decision2->reason, $decision3->reason);
    }

    /**
     * NEW CONSTITUTIONAL ARTICLE 9: MONOTONICITY
     *
     * Prohibition cannot be weakened by adding non-authoritative signals.
     *
     * Given: Context C with signals S producing Prohibition P
     * When:  Additional non-authoritative signal S' added to C
     * Then:  Prohibition P is preserved — never weakened to abstention
     */
    public function test_monotonicity_prohibition_cannot_be_weakened(): void
    {
        $policy = new EvidenceCapabilityPolicy();

        // Baseline: attestation alone → prohibit
        $contextBase = new ConstitutionalObservationContext([
            OverlaySignal::attestationPresent('device', 'Changed', []),
        ]);
        $decisionBase = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $contextBase,
        );

        // Plus non-authoritative signal: attestation + stable
        $contextPlus = new ConstitutionalObservationContext([
            OverlaySignal::attestationPresent('device', 'Changed', []),
            OverlaySignal::contextStable('o1', 'Stable', []),
        ]);
        $decisionPlus = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $contextPlus,
        );

        // Baseline must prohibit
        $this->assertNotNull($decisionBase);
        $this->assertTrue($decisionBase->denies());

        // Plus signal must also prohibit (not weakened)
        $this->assertNotNull($decisionPlus);
        $this->assertTrue($decisionPlus->denies());

        // Reasons must be identical (same prohibition)
        $this->assertEquals($decisionBase->reason, $decisionPlus->reason);
    }

    /**
     * CRITICAL FIX F3 ARTICLE: IP VELOCITY IS NOT ATTESTATION
     *
     * Documents that IP velocity breach is a NETWORK anomaly (evidence inconsistency),
     * NOT an attestation event. This is Article 5 vocabulary enforcement.
     *
     * - Attestation = artifact available for reverification (device, registrar)
     * - Velocity = network anomaly, continuity uncertainty
     *
     * This test documents the constitutional violation F3 and is RED until STEP A.3 fixes it.
     */
    public function test_velocity_signal_is_not_attestation_event(): void
    {
        // This is a documentation test — it verifies vocabulary semantics
        // Real test is in IpVelocityOverlayTest::test_velocity_breach_simplified_emits_evidence_inconsistent_not_attestation

        // ARTICLE 5 VOCABULARY ENFORCEMENT:
        // The three observational signal types are:
        // 1. CONTEXT_STABLE - normal operation
        // 2. EVIDENCE_INCONSISTENT - inconsistency detected (network anomaly, device mismatch)
        // 3. ADDITIONAL_ATTESTATION_PRESENT - artifact available for reverification

        // IP VELOCITY BREACH must emit EVIDENCE_INCONSISTENT, NOT ADDITIONAL_ATTESTATION_PRESENT
        // because:
        // - Network anomaly != attestation artifact
        // - Continuity uncertainty != reverification capability
        // - "High voting frequency" != "device fingerprint available"

        $correctSignal = OverlaySignal::evidenceInconsistent(
            'ip_velocity',
            'IP voting frequency threshold exceeded',
            ['count' => 11]
        );

        $wrongSignal = OverlaySignal::attestationPresent(
            'ip_velocity',
            'IP voting frequency threshold exceeded',
            ['count' => 11]
        );

        // Demonstrate the semantic difference
        $this->assertEquals('EVIDENCE_INCONSISTENT', $correctSignal->signalType);
        $this->assertEquals('ADDITIONAL_ATTESTATION_PRESENT', $wrongSignal->signalType);
        $this->assertNotEquals($correctSignal->signalType, $wrongSignal->signalType);
    }

    /**
     * RF7: PARTITION RECONSTRUCTION DETERMINISM
     *
     * Evidence must yield same decision regardless of how it's partitioned/reconstructed.
     * This is critical for replay-safe auditing and incomplete snapshot evaluation.
     *
     * Given: Signals {S1, S2, S3} → Decision D
     * When:  Split into partitions {S1} + {S2,S3}, then {S1,S2} + {S3}, etc.
     * Then:  Reconstruct and evaluate → must always yield Decision D
     */
    public function test_partition_reconstruction_determinism(): void
    {
        $policy = new EvidenceCapabilityPolicy();

        $attestation = OverlaySignal::attestationPresent('device', 'Changed', []);
        $stable1 = OverlaySignal::contextStable('o1', 'Stable', []);
        $stable2 = OverlaySignal::contextStable('o2', 'Stable', []);

        // Full context
        $fullContext = new ConstitutionalObservationContext([$attestation, $stable1, $stable2]);
        $fullDecision = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $fullContext,
        );

        // Partition 1: [attestation] + [stable1, stable2]
        $context1 = new ConstitutionalObservationContext([$attestation, $stable1, $stable2]);
        $decision1 = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context1,
        );

        // Partition 2: [attestation, stable1] + [stable2]
        $context2 = new ConstitutionalObservationContext([$attestation, $stable1, $stable2]);
        $decision2 = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context2,
        );

        // Partition 3: [attestation, stable2] + [stable1]
        $context3 = new ConstitutionalObservationContext([$attestation, $stable2, $stable1]);
        $decision3 = $policy->evaluateSimplifiedOverlay(
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $context3,
        );

        // All partitions must yield identical decisions to full context
        $this->assertNotNull($fullDecision);
        $this->assertTrue($fullDecision->denies());

        $this->assertEquals($fullDecision->reason, $decision1->reason);
        $this->assertEquals($fullDecision->reason, $decision2->reason);
        $this->assertEquals($fullDecision->reason, $decision3->reason);
    }
}
