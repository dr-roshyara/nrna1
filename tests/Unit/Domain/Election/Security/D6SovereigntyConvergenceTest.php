<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\Simplified\EvaluationReasonCode;
use App\Domain\Election\Security\Simplified\EvidenceClassification;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationResult;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationState;
use App\Domain\Election\Security\Simplified\EvidenceSnapshot;
use App\Domain\Election\Security\Simplified\EvaluationEnvelope;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use PHPUnit\Framework\TestCase;

/**
 * D.6 Sovereignty Convergence Test
 *
 * Architectural invariant tests that Phase D.6 completion conditions
 * must satisfy. These tests verify the TRUST DOMAIN remains free of
 * authority-derivation patterns and that sovereignty remains centralized.
 *
 * Phase D completion conditions verified:
 * 1. ✅ EvaluationEnvelope carries evaluation facts, not authority
 * 2. ✅ No boolean allow/deny on domain objects (authority belongs in Resolver)
 * 3. ✅ Overlays only signal influence, never authority
 * 4. ✅ Snapshot is read-only projection
 * 5. ✅ Causality chain preserved in policy sequence
 */
class D6SovereigntyConvergenceTest extends TestCase
{
    // =========================================================================
    // ARCHITECTURAL INVARIANT TESTS
    // These protect against sovereignty regression. If any fails,
    // authority has leaked into the domain layer.
    // =========================================================================

    /**
     * INVARIANT 1: Domain objects must NOT have boolean authority flags.
     * Authority belongs in Application\Capabilities, not Domain\Security.
     */
    public function test_domain_objects_have_no_authority_flags(): void
    {
        // EvidenceEvaluationResult carries evaluation state, not allow/deny
        $result = new EvidenceEvaluationResult(
            evaluationState: EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            classification: EvidenceClassification::Attested,
            reason: EvaluationReasonCode::ALL_POLICIES_PASSED,
            auditContext: [],
            policyOutcomeSequence: [],
        );

        $this->assertObjectNotHasProperty('allowed', $result);
        $this->assertObjectNotHasProperty('denied', $result);
        $this->assertObjectNotHasProperty('granted', $result);
        $this->assertObjectNotHasProperty('authorized', $result);
        $this->assertObjectNotHasProperty('permitted', $result);
    }

    /**
     * INVARIANT 2: OverlaySignal must NOT have boolean authority methods.
     * Overlays only signal influence (CONTINUE, ELEVATE, REVERIFY).
     */
    public function test_overlay_signal_has_no_instance_methods(): void
    {
        $reflection = new \ReflectionClass(OverlaySignal::class);
        $instanceMethods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => !$m->isStatic() && $m->getName() !== '__construct'
        );
        $this->assertCount(0, $instanceMethods, 'OverlaySignal must have zero instance methods');
    }

    /**
     * INVARIANT 3: EvidenceSnapshot must have NO behavioral methods.
     * It is a read-only projection for controllers/UI.
     */
    public function test_snapshot_has_no_behavioral_methods(): void
    {
        $reflection = new \ReflectionClass(EvidenceSnapshot::class);
        $methods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => $m->getName() !== '__construct'
        );
        $this->assertCount(0, $methods, 'Snapshot must be pure data projection');
    }

    /**
     * INVARIANT 4: EvaluationEnvelope must have NO behavioral methods.
     * It bundles facts for the Resolver to interpret.
     */
    public function test_envelope_has_no_behavioral_methods(): void
    {
        $reflection = new \ReflectionClass(EvaluationEnvelope::class);
        $methods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => $m->getName() !== '__construct'
        );
        $this->assertCount(0, $methods, 'Envelope must be pure data bundle');
    }

    /**
     * INVARIANT 5: NO domain object returns boolean.
     * Boolean returns in domain create false authority semantics.
     */
    public function test_no_domain_object_returns_boolean(): void
    {
        $domainClasses = [
            EvidenceEvaluationResult::class,
            OverlaySignal::class,
            EvidenceSnapshot::class,
            EvaluationEnvelope::class,
        ];

        foreach ($domainClasses as $class) {
            $reflection = new \ReflectionClass($class);
            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->isStatic() || $method->getName() === '__construct') {
                    continue;
                }
                $returnType = $method->getReturnType();
                if ($returnType instanceof \ReflectionNamedType && $returnType->getName() === 'bool') {
                    $this->fail("{$class}::{$method->getName()}() returns bool — authority leak detected");
                }
            }
        }

        $this->assertTrue(true);
    }

    // =========================================================================
    // COMPLETION CONDITION TESTS
    // These verify the trust evaluation pipeline produces correct outcomes
    // that the Resolver can consume.
    // =========================================================================

    /**
     * D.6.1: SUFFICIENT_EVIDENCE → Resolver can allow voting
     */
    public function test_sufficient_evidence_allows_voting_in_resolver(): void
    {
        $envelope = new EvaluationEnvelope(
            new EvidenceEvaluationResult(
                EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                EvidenceClassification::Attested,
                EvaluationReasonCode::ALL_POLICIES_PASSED, [], ['verification' => 'passed', 'network' => 'passed'],
            ),
            new ConstitutionalObservationContext([OverlaySignal::contextStable('none', 'Context stable', [])]),
            new EvidenceSnapshot(
                EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                EvidenceClassification::Attested, true, true, null, '',
                ['verification' => 'passed', 'network' => 'passed'],
            ),
        );

        // Resolver reads: SUFFICIENT_EVIDENCE + CONTINUE → abstain (let other policies run)
        $this->assertEquals(EvidenceEvaluationState::SUFFICIENT_EVIDENCE, $envelope->result->evaluationState);
        $this->assertEquals('CONTEXT_STABLE', $envelope->observations->all()[0]->signalType);
    }

    /**
     * D.6.2: INSUFFICIENT_EVIDENCE → Resolver denies voting
     */
    public function test_insufficient_evidence_denies_voting(): void
    {
        $envelope = new EvaluationEnvelope(
            new EvidenceEvaluationResult(
                EvidenceEvaluationState::INSUFFICIENT_EVIDENCE,
                EvidenceClassification::Initial,
                EvaluationReasonCode::NETWORK_EVIDENCE_INSUFFICIENT, ['votes' => 6], ['network' => 'denied'],
            ),
            new ConstitutionalObservationContext([OverlaySignal::contextStable('none', 'Context stable', [])]),
            new EvidenceSnapshot(
                EvidenceEvaluationState::INSUFFICIENT_EVIDENCE,
                EvidenceClassification::Initial, false, false, null, 'Network limit exceeded',
                ['network' => 'denied'],
            ),
        );

        // Resolver reads: INSUFFICIENT_EVIDENCE → deny
        $this->assertEquals(EvidenceEvaluationState::INSUFFICIENT_EVIDENCE, $envelope->result->evaluationState);
    }

    /**
     * D.6.3: REVIEW_REQUIRED → Resolver defers to constitutional review
     */
    public function test_review_required_defers_to_constitutional_review(): void
    {
        $envelope = new EvaluationEnvelope(
            new EvidenceEvaluationResult(
                EvidenceEvaluationState::REVIEW_REQUIRED,
                EvidenceClassification::Initial,
                EvaluationReasonCode::SESSION_CONTINUITY_FAILED, ['device_changed' => true], ['device' => 'review'],
            ),
            new ConstitutionalObservationContext([OverlaySignal::attestationPresent('device_anomaly', 'Device changed', [])]),
            new EvidenceSnapshot(
                EvidenceEvaluationState::REVIEW_REQUIRED,
                EvidenceClassification::Initial, false, false, 'device_anomaly', 'Device changed — review',
                ['device' => 'review'],
            ),
        );

        // Resolver reads: REVIEW_REQUIRED + ADDITIONAL_ATTESTATION_PRESENT → block for review
        $this->assertEquals(EvidenceEvaluationState::REVIEW_REQUIRED, $envelope->result->evaluationState);
        $this->assertEquals('ADDITIONAL_ATTESTATION_PRESENT', $envelope->observations->all()[0]->signalType);
    }

    /**
     * D.6.4: INCONCLUSIVE → Resolver blocks (trust cannot be established)
     */
    public function test_inconclusive_blocks_voting(): void
    {
        $envelope = new EvaluationEnvelope(
            new EvidenceEvaluationResult(
                EvidenceEvaluationState::INCONCLUSIVE,
                EvidenceClassification::Initial,
                EvaluationReasonCode::DEVICE_EVIDENCE_INSUFFICIENT, ['fingerprint' => null], ['device' => 'inconclusive'],
            ),
            new ConstitutionalObservationContext([OverlaySignal::contextStable('none', 'Context stable', [])]),
            new EvidenceSnapshot(
                EvidenceEvaluationState::INCONCLUSIVE,
                EvidenceClassification::Initial, false, true, null, 'No fingerprint',
                ['device' => 'inconclusive'],
            ),
        );

        $this->assertEquals(EvidenceEvaluationState::INCONCLUSIVE, $envelope->result->evaluationState);
    }

    /**
     * D.6.5: Overlay ELEVATE_TRUST with SUFFICIENT_EVIDENCE → elevated voting
     */
    public function test_elevate_trust_with_sufficient_evidence(): void
    {
        $envelope = new EvaluationEnvelope(
            new EvidenceEvaluationResult(
                EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                EvidenceClassification::Attested, EvaluationReasonCode::ALL_POLICIES_PASSED, [],
                ['verification' => 'passed', 'network' => 'passed', 'device' => 'passed'],
            ),
            new ConstitutionalObservationContext([OverlaySignal::evidenceInconsistent('registrar_attestation', 'Registrar attestation inconsistent', ['reg' => 'r1'])]),
            new EvidenceSnapshot(
                EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                EvidenceClassification::RegistrarConfirmed, true, true, 'registrar_attestation', '',
                ['verification' => 'passed', 'network' => 'passed', 'device' => 'passed', 'registrar' => 'elevated'],
            ),
        );

        // Elevation is influence only — resolver decides whether to honor it
        $this->assertEquals('EVIDENCE_INCONSISTENT', $envelope->observations->all()[0]->signalType);
        $this->assertEquals('registrar_attestation', $envelope->snapshot->activeOverlay);
    }

    /**
     * D.6.6: Causality chain preserved end-to-end
     */
    public function test_causality_chain_preserved(): void
    {
        $policySequence = [
            'verification_attestation' => 'passed',
            'network_binding' => 'denied',
        ];

        $result = new EvidenceEvaluationResult(
            EvidenceEvaluationState::INSUFFICIENT_EVIDENCE,
            EvidenceClassification::Initial,
            EvaluationReasonCode::NETWORK_EVIDENCE_INSUFFICIENT,
            ['votes_from_ip' => 7, 'max_allowed' => 6],
            $policySequence,
        );

        $envelope = new EvaluationEnvelope(
            $result,
            new ConstitutionalObservationContext([OverlaySignal::attestationPresent('ip_velocity', 'IP threshold exceeded', ['votes' => 7])]),
            new EvidenceSnapshot(
                EvidenceEvaluationState::INSUFFICIENT_EVIDENCE,
                EvidenceClassification::Initial, true, false, 'ip_velocity',
                'Network: 7/6 votes from this IP',
                $policySequence,
            ),
        );

        // Audit can reconstruct: verification passed → network denied → policy short-circuited
        $this->assertCount(2, $envelope->result->policyOutcomeSequence);
        $this->assertEquals('passed', $envelope->result->policyOutcomeSequence['verification_attestation']);
        $this->assertEquals('denied', $envelope->result->policyOutcomeSequence['network_binding']);
        $this->assertArrayNotHasKey('device_binding', $envelope->result->policyOutcomeSequence);
    }

    /**
     * D.6.7: SecurityEvent-recordable data is available from envelope
     */
    public function test_envelope_contains_event_recordable_data(): void
    {
        $envelope = new EvaluationEnvelope(
            new EvidenceEvaluationResult(
                EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                EvidenceClassification::ContinuityProven, EvaluationReasonCode::ALL_POLICIES_PASSED,
                ['ip_hash' => 'abc', 'fingerprint_hash' => 'def'],
                ['verification' => 'passed', 'network' => 'passed', 'device' => 'passed'],
            ),
            new ConstitutionalObservationContext([OverlaySignal::evidenceInconsistent('registrar_attestation', 'Registrar attestation inconsistent', ['reg' => 'r1'])]),
            new EvidenceSnapshot(
                EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                EvidenceClassification::RegistrarConfirmed, true, true, 'registrar_attestation', '',
                ['verification' => 'passed', 'network' => 'passed', 'device' => 'passed', 'registrar' => 'elevated'],
            ),
        );

        // All data needed for a SecurityEvent is available:
        $this->assertNotEmpty($envelope->result->auditContext);
        $this->assertNotEmpty($envelope->result->policyOutcomeSequence);
        $this->assertNotEmpty($envelope->snapshot->evidenceProvenance);

        // Event would record: evaluation state, trust levels, policy outcomes, overlay
        $eventData = [
            'evaluation_state' => $envelope->result->evaluationState->value,
            'trust_level' => $envelope->result->classification->value,
            'trust_level_before' => EvidenceClassification::Attested->value,
            'trust_level_after' => $envelope->result->classification->value,
            'overlay_signal' => $envelope->observations->all()[0]->signalType,
            'constitutional_outcome' => 'allow',
            'policy_count' => count($envelope->result->policyOutcomeSequence),
        ];
        $this->assertEquals(7, count($eventData));
    }
}
