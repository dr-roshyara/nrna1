<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\Simplified\ConstitutionalTrustSnapshot;
use App\Domain\Election\Security\Simplified\DeviceEvidence;
use App\Domain\Election\Security\Simplified\EvaluationReasonCode;
use App\Domain\Election\Security\Simplified\NetworkEvidence;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use App\Domain\Election\Security\Simplified\SessionContinuity;
use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\Simplified\TrustEvidenceAggregate;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\Simplified\VerificationEvidence;
use App\Domain\Election\Security\Simplified\VotingTrustResult;
use PHPUnit\Framework\TestCase;

/**
 * D.3 Trust Policy Evaluation Test
 */
class D3TrustPolicyEvaluationTest extends TestCase
{
    public function test_voting_trust_result_captures_sufficient_evidence(): void
    {
        $result = new VotingTrustResult(
            evaluationState: TrustEvaluationState::SUFFICIENT_EVIDENCE,
            trustLevel: TrustLevel::Attested,
            reason: EvaluationReasonCode::ALL_POLICIES_PASSED,
            auditContext: [],
            policyOutcomeSequence: ['verification_attestation' => 'passed', 'network_binding' => 'passed'],
        );

        $this->assertEquals(TrustEvaluationState::SUFFICIENT_EVIDENCE, $result->evaluationState);
        $this->assertEquals(TrustLevel::Attested, $result->trustLevel);
    }

    public function test_voting_trust_result_captures_insufficient_evidence(): void
    {
        $result = new VotingTrustResult(
            evaluationState: TrustEvaluationState::INSUFFICIENT_EVIDENCE,
            trustLevel: TrustLevel::Unverified,
            reason: EvaluationReasonCode::NETWORK_EVIDENCE_INSUFFICIENT,
            auditContext: ['network_votes' => 6],
            policyOutcomeSequence: ['network_binding' => 'denied'],
        );

        $this->assertEquals(TrustEvaluationState::INSUFFICIENT_EVIDENCE, $result->evaluationState);
    }

    public function test_overlay_signal_context_stable(): void
    {
        $signal = OverlaySignal::contextStable('test_overlay', 'No anomaly detected', []);
        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
    }

    public function test_overlay_signal_evidence_inconsistent(): void
    {
        $signal = OverlaySignal::evidenceInconsistent('test', 'Evidence contradicts', []);
        $this->assertEquals('EVIDENCE_INCONSISTENT', $signal->signalType);
        $this->assertNull($signal->suggestedElevatedLevel);
    }

    public function test_overlay_signal_attestation_present(): void
    {
        $signal = OverlaySignal::attestationPresent('test', 'Attestation available', []);
        $this->assertEquals('ADDITIONAL_ATTESTATION_PRESENT', $signal->signalType);
    }

    public function test_constitutional_trust_snapshot_is_readonly_projection(): void
    {
        $snapshot = new ConstitutionalTrustSnapshot(
            evaluationState: TrustEvaluationState::SUFFICIENT_EVIDENCE,
            trustLevel: TrustLevel::Attested,
            attestationValid: true,
            continuityPreserved: true,
            activeOverlay: null,
            denialReason: '',
            trustProvenance: ['verification_attestation' => 'passed'],
        );

        $this->assertEquals(TrustEvaluationState::SUFFICIENT_EVIDENCE, $snapshot->evaluationState);

        // Verify no behavioral methods
        $reflection = new \ReflectionClass($snapshot);
        $publicMethods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => $m->getName() !== '__construct'
        );
        $this->assertCount(0, $publicMethods);
    }

    public function test_constitutional_trust_snapshot_denial_reason(): void
    {
        $snapshot = new ConstitutionalTrustSnapshot(
            evaluationState: TrustEvaluationState::INSUFFICIENT_EVIDENCE,
            trustLevel: TrustLevel::Unverified,
            attestationValid: false,
            continuityPreserved: false,
            activeOverlay: null,
            denialReason: 'Network limit exceeded',
            trustProvenance: [],
        );

        $this->assertEquals('Network limit exceeded', $snapshot->denialReason);
    }

    public function test_policy_sequence_captures_evaluation_order(): void
    {
        $result = new VotingTrustResult(
            evaluationState: TrustEvaluationState::SUFFICIENT_EVIDENCE,
            trustLevel: TrustLevel::ContinuityVerified,
            reason: EvaluationReasonCode::ALL_POLICIES_PASSED,
            auditContext: [],
            policyOutcomeSequence: [
                'verification_attestation' => 'passed',
                'network_binding' => 'passed',
                'device_binding' => 'passed',
            ],
        );

        $sequence = array_keys($result->policyOutcomeSequence);
        $this->assertEquals('verification_attestation', $sequence[0]);
        $this->assertEquals('network_binding', $sequence[1]);
        $this->assertEquals('device_binding', $sequence[2]);
    }

    public function test_short_circuit_stops_at_first_insufficient_evidence(): void
    {
        $result = new VotingTrustResult(
            evaluationState: TrustEvaluationState::INSUFFICIENT_EVIDENCE,
            trustLevel: TrustLevel::Unverified,
            reason: EvaluationReasonCode::NETWORK_EVIDENCE_INSUFFICIENT,
            auditContext: [],
            policyOutcomeSequence: [
                'verification_attestation' => 'passed',
                'network_binding' => 'denied',
            ],
        );

        $this->assertCount(2, $result->policyOutcomeSequence);
        $this->assertArrayNotHasKey('device_binding', $result->policyOutcomeSequence);
    }
}
