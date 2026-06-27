<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\Simplified\EvaluationReasonCode;
use App\Domain\Election\Security\Simplified\EvidenceClassification;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationResult;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationState;
use App\Domain\Election\Security\Simplified\EvidenceSeverity;
use App\Domain\Election\Security\Simplified\EvidenceSnapshot;
use App\Domain\Election\Security\Simplified\EvaluationEnvelope;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use PHPUnit\Framework\TestCase;

/**
 * D.5 Resolver Integration Test
 *
 * Verifies that:
 * 1. EvaluationEnvelope bundles result, overlay signal, and snapshot
 * 2. Envelope is read-only data projection (no behavioral methods)
 * 3. Envelope carries evaluation state, NOT authority decisions
 * 4. Overlay signal is influence-only, never authority
 * 5. Snapshot is read-only projection, never authority source
 * 6. Full pipeline: evidence → evaluation → envelope → resolver-ready
 */
class D5ResolverIntegrationTest extends TestCase
{
    public function test_envelope_bundles_evaluation_result_overlay_and_snapshot(): void
    {
        $result = new EvidenceEvaluationResult(
            evaluationState: EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            classification: EvidenceClassification::Attested,
            reason: EvaluationReasonCode::ALL_POLICIES_PASSED,
            auditContext: ['ip_hash' => 'abc123'],
            policyOutcomeSequence: ['verification_attestation' => 'passed', 'network_binding' => 'passed'],
        );

        $overlay = OverlaySignal::contextStable('emergency_condition', 'No emergency', []);

        $snapshot = new EvidenceSnapshot(
            evaluationState: EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            classification: EvidenceClassification::Attested,
            attestationValid: true,
            continuityPreserved: true,
            activeOverlay: null,
            denialReason: '',
            evidenceProvenance: ['verification_attestation' => 'passed', 'network_binding' => 'passed'],
        );

        $envelope = new EvaluationEnvelope($result, $overlay, $snapshot);

        $this->assertSame($result, $envelope->result);
        $this->assertSame($overlay, $envelope->overlaySignal);
        $this->assertSame($snapshot, $envelope->snapshot);
    }

    public function test_envelope_is_readonly(): void
    {
        $result = new EvidenceEvaluationResult(
            evaluationState: EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            classification: EvidenceClassification::Attested,
            reason: EvaluationReasonCode::ALL_POLICIES_PASSED,
            auditContext: [],
            policyOutcomeSequence: [],
        );

        $envelope = new EvaluationEnvelope(
            $result,
            OverlaySignal::contextStable('test', 'Context stable', []),
            new EvidenceSnapshot(
                evaluationState: EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                classification: EvidenceClassification::Attested,
                attestationValid: true,
                continuityPreserved: true,
                activeOverlay: null,
                denialReason: '',
                evidenceProvenance: [],
            ),
        );

        try {
            $envelope->result = $result;
            $this->fail('EvaluationEnvelope should be readonly');
        } catch (\Error $e) {
            $this->assertStringContainsString('readonly', $e->getMessage());
        }
    }

    public function test_envelope_has_no_behavioral_methods(): void
    {
        $reflection = new \ReflectionClass(EvaluationEnvelope::class);

        $publicMethods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => $m->getName() !== '__construct'
        );
        $this->assertCount(0, $publicMethods, 'EvaluationEnvelope should have no behavioral methods');
    }

    public function test_envelope_carries_evaluation_state_not_authority(): void
    {
        // Verify envelope carries evaluation facts, not authority decisions
        $sufficient = new EvaluationEnvelope(
            new EvidenceEvaluationResult(EvidenceEvaluationState::SUFFICIENT_EVIDENCE, EvidenceClassification::Attested, EvaluationReasonCode::ALL_POLICIES_PASSED, [], []),
            OverlaySignal::contextStable('test', 'Test context stable', []),
            new EvidenceSnapshot(EvidenceEvaluationState::SUFFICIENT_EVIDENCE, EvidenceClassification::Attested, true, true, null, '', []),
        );

        $insufficient = new EvaluationEnvelope(
            new EvidenceEvaluationResult(EvidenceEvaluationState::INSUFFICIENT_EVIDENCE, EvidenceClassification::Initial, EvaluationReasonCode::NETWORK_EVIDENCE_INSUFFICIENT, ['votes' => 6], ['network_binding' => 'denied']),
            OverlaySignal::contextStable('test', 'Context stable', []),
            new EvidenceSnapshot(EvidenceEvaluationState::INSUFFICIENT_EVIDENCE, EvidenceClassification::Initial, false, false, null, 'Limit exceeded', ['network_binding' => 'denied']),
        );

        // Envelope carries evaluation facts, not allow/deny flags
        $this->assertEquals(EvidenceEvaluationState::SUFFICIENT_EVIDENCE, $sufficient->result->evaluationState);
        $this->assertEquals(EvidenceEvaluationState::INSUFFICIENT_EVIDENCE, $insufficient->result->evaluationState);

        // No boolean authority flag anywhere on envelope
        $this->assertObjectNotHasProperty('allowed', $sufficient);
        $this->assertObjectNotHasProperty('denied', $sufficient);
        $this->assertObjectNotHasProperty('granted', $sufficient);
    }

    public function test_overlay_signal_in_envelope_is_influence_only(): void
    {
        $elevate = OverlaySignal::evidenceInconsistent('registrar_attestation', 'Registrar attestation evidence', ['reg_id' => 'r1']);

        $envelope = new EvaluationEnvelope(
            new EvidenceEvaluationResult(EvidenceEvaluationState::SUFFICIENT_EVIDENCE, EvidenceClassification::Attested, EvaluationReasonCode::ALL_POLICIES_PASSED, [], []),
            $elevate,
            new EvidenceSnapshot(EvidenceEvaluationState::SUFFICIENT_EVIDENCE, EvidenceClassification::Attested, true, true, 'registrar_attestation', '', []),
        );

        // Overlay signal suggests elevation but does NOT grant authority
        $this->assertEquals('EVIDENCE_INCONSISTENT', $envelope->overlaySignal->signalType);
        $this->assertEquals('registrar_attestation', $envelope->overlaySignal->overlayIdentifier);

        // No boolean allow/deny on overlay signal
        $this->assertObjectNotHasProperty('allowed', $envelope->overlaySignal);
        $this->assertObjectNotHasProperty('denied', $envelope->overlaySignal);
    }

    public function test_full_pipeline_evaluation(): void
    {
        // Simulate full trust evaluation pipeline:
        // 1. Evidence collected → EvidenceEvaluationResult
        // 2. Overlays evaluated → OverlaySignal
        // 3. Snapshot assembled → EvidenceSnapshot
        // 4. Bundled → EvaluationEnvelope
        // 5. Resolver reads envelope (not shown here)

        $result = new EvidenceEvaluationResult(
            evaluationState: EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            classification: EvidenceClassification::RegistrarConfirmed,
            reason: EvaluationReasonCode::ALL_POLICIES_PASSED,
            auditContext: ['registrar_id' => 'reg_001'],
            policyOutcomeSequence: [
                'verification_attestation' => 'passed',
                'network_binding' => 'passed',
                'device_binding' => 'passed',
            ],
        );

        $overlay = OverlaySignal::evidenceInconsistent('registrar_attestation', 'Registrar attestation evidence inconsistent', ['registrar_id' => 'reg_001']);

        $snapshot = new EvidenceSnapshot(
            evaluationState: EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            classification: EvidenceClassification::RegistrarConfirmed,
            attestationValid: true,
            continuityPreserved: true,
            activeOverlay: 'registrar_attestation',
            denialReason: '',
            evidenceProvenance: [
                'verification_attestation' => 'passed',
                'network_binding' => 'passed',
                'device_binding' => 'passed',
                'registrar_attestation' => 'elevated',
            ],
        );

        $envelope = new EvaluationEnvelope($result, $overlay, $snapshot);

        // Verify causality chain preserved
        $this->assertCount(3, $result->policyOutcomeSequence);
        $this->assertEquals('EVIDENCE_INCONSISTENT', $overlay->signalType);
        $this->assertEquals('registrar_attestation', $snapshot->activeOverlay);
        $this->assertCount(4, $snapshot->evidenceProvenance);

        // Resolver would read these values and determine capability
        // But envelope itself has NO authority methods
        $this->assertObjectNotHasProperty('allowed', $envelope);
        $this->assertObjectNotHasProperty('denied', $envelope);
    }

    public function test_denial_pipeline(): void
    {
        // Denial scenario: network limit exceeded, overlay signals reverification
        $result = new EvidenceEvaluationResult(
            evaluationState: EvidenceEvaluationState::INSUFFICIENT_EVIDENCE,
            classification: EvidenceClassification::Initial,
            reason: EvaluationReasonCode::NETWORK_EVIDENCE_INSUFFICIENT,
            auditContext: ['votes_from_ip' => 7, 'max_allowed' => 6],
            policyOutcomeSequence: [
                'verification_attestation' => 'passed',
                'network_binding' => 'denied',
            ],
        );

        $overlay = OverlaySignal::attestationPresent('ip_velocity', 'IP velocity threshold exceeded', ['votes_from_ip' => 7]);

        $snapshot = new EvidenceSnapshot(
            evaluationState: EvidenceEvaluationState::INSUFFICIENT_EVIDENCE,
            classification: EvidenceClassification::Initial,
            attestationValid: true,
            continuityPreserved: false,
            activeOverlay: 'ip_velocity',
            denialReason: 'IP vote limit exceeded',
            evidenceProvenance: ['verification_attestation' => 'passed', 'network_binding' => 'denied'],
        );

        $envelope = new EvaluationEnvelope($result, $overlay, $snapshot);

        // Envelope carries facts about denial, not the denial decision itself
        $this->assertEquals(EvaluationReasonCode::NETWORK_EVIDENCE_INSUFFICIENT, $result->reason);
        $this->assertEquals('ADDITIONAL_ATTESTATION_PRESENT', $overlay->signalType);
        $this->assertEquals('IP vote limit exceeded', $snapshot->denialReason);
        $this->assertArrayHasKey('network_binding', $result->policyOutcomeSequence);
        $this->assertEquals('denied', $result->policyOutcomeSequence['network_binding']);
    }

    public function test_insufficient_data_pipeline(): void
    {
        // Inconclusive: not enough data to evaluate (not denial, not allow)
        $result = new EvidenceEvaluationResult(
            evaluationState: EvidenceEvaluationState::INCONCLUSIVE,
            classification: EvidenceClassification::Initial,
            reason: EvaluationReasonCode::DEVICE_EVIDENCE_INSUFFICIENT,
            auditContext: ['fingerprint_available' => false],
            policyOutcomeSequence: [
                'verification_attestation' => 'passed',
                'device_binding' => 'inconclusive',
            ],
        );

        $envelope = new EvaluationEnvelope(
            $result,
            OverlaySignal::contextStable('emergency_condition', 'No emergency', []),
            new EvidenceSnapshot(
                evaluationState: EvidenceEvaluationState::INCONCLUSIVE,
                classification: EvidenceClassification::Initial,
                attestationValid: false,
                continuityPreserved: true,
                activeOverlay: null,
                denialReason: 'No fingerprint data available',
                evidenceProvenance: ['verification_attestation' => 'passed', 'device_binding' => 'inconclusive'],
            ),
        );

        // Inconclusive is NOT a denial — it's an evidence gap
        $this->assertEquals(EvidenceEvaluationState::INCONCLUSIVE, $result->evaluationState);
        $this->assertEquals(EvaluationReasonCode::DEVICE_EVIDENCE_INSUFFICIENT, $result->reason);
    }

    public function test_review_required_pipeline(): void
    {
        // Review required: device anomaly detected, manual review needed
        $result = new EvidenceEvaluationResult(
            evaluationState: EvidenceEvaluationState::REVIEW_REQUIRED,
            classification: EvidenceClassification::Initial,
            reason: EvaluationReasonCode::SESSION_CONTINUITY_FAILED,
            auditContext: ['fingerprint_match' => false],
            policyOutcomeSequence: [
                'verification_attestation' => 'passed',
                'device_binding' => 'review_required',
            ],
        );

        $overlay = OverlaySignal::attestationPresent('device_anomaly', 'Device changed during session', []);

        $envelope = new EvaluationEnvelope(
            $result,
            $overlay,
            new EvidenceSnapshot(
                evaluationState: EvidenceEvaluationState::REVIEW_REQUIRED,
                classification: EvidenceClassification::Initial,
                attestationValid: true,
                continuityPreserved: false,
                activeOverlay: 'device_anomaly',
                denialReason: 'Device changed — manual review required',
                evidenceProvenance: ['verification_attestation' => 'passed', 'device_binding' => 'review_required'],
            ),
        );

        $this->assertEquals(EvidenceEvaluationState::REVIEW_REQUIRED, $result->evaluationState);
        $this->assertEquals('ADDITIONAL_ATTESTATION_PRESENT', $envelope->overlaySignal->signalType);
    }
}
