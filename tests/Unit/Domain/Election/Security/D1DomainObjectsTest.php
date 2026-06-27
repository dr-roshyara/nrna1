<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\BallotAuthorizationProtocol;
use App\Domain\Election\Security\Simplified\ConstitutionalTrustSnapshot;
use App\Domain\Election\Security\Simplified\DeviceEvidence;
use App\Domain\Election\Security\Simplified\ElectionSecurityEvent;
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

class D1DomainObjectsTest extends TestCase
{
    /**
     * Test TrustEvaluationState enum has required cases
     */
    public function test_trust_evaluation_state_has_all_cases(): void
    {
        $this->assertEquals('sufficient_evidence', TrustEvaluationState::SUFFICIENT_EVIDENCE->value);
        $this->assertEquals('insufficient_evidence', TrustEvaluationState::INSUFFICIENT_EVIDENCE->value);
        $this->assertEquals('review_required', TrustEvaluationState::REVIEW_REQUIRED->value);
        $this->assertEquals('inconclusive', TrustEvaluationState::INCONCLUSIVE->value);
    }

    /**
     * Test TrustLevel enum has required cases
     */
    public function test_trust_level_has_all_cases(): void
    {
        $this->assertEquals('unverified', TrustLevel::Unverified->value);
        $this->assertEquals('attested', TrustLevel::Attested->value);
        $this->assertEquals('continuity_verified', TrustLevel::ContinuityVerified->value);
        $this->assertEquals('registrar_attested', TrustLevel::RegistrarAttested->value);
    }

    /**
     * Test BallotAuthorizationProtocol enum
     */
    public function test_ballot_authorization_protocol_has_cases(): void
    {
        $this->assertEquals('single_code', BallotAuthorizationProtocol::UnifiedTokenProtocol->value);
        $this->assertEquals('dual_code', BallotAuthorizationProtocol::SplitAuthorizationProtocol->value);
    }

    /**
     * Test NetworkEvidence value object is readonly
     */
    public function test_network_evidence_is_readonly(): void
    {
        $evidence = new NetworkEvidence(
            currentIpHash: hash('sha256', '192.168.1.1'),
            maxVotesPerIp: 6,
            votesFromThisIp: 2,
            restrictionEnabled: true,
            bindingStrategy: 'ip_strict',
        );

        $this->assertEquals(6, $evidence->maxVotesPerIp);
        $this->assertEquals(2, $evidence->votesFromThisIp);

        // Test immutability by attempting property assignment (should fail with readonly)
        try {
            $evidence->maxVotesPerIp = 10;
            $this->fail('NetworkEvidence should be readonly');
        } catch (\Error $e) {
            $this->assertStringContainsString('readonly', $e->getMessage());
        }
    }

    /**
     * Test DeviceEvidence value object
     */
    public function test_device_evidence_captures_fingerprint_hash(): void
    {
        $evidence = new DeviceEvidence(
            fingerprintHash: hash('sha256', 'fp_123'),
            matchType: 'exact_match',
            captureMethod: 'browser_api',
            volatility: 'stable',
        );

        $this->assertNotNull($evidence->fingerprintHash);
        $this->assertEquals('exact_match', $evidence->matchType);
    }

    /**
     * Test VerificationEvidence value object
     */
    public function test_verification_evidence_required_field(): void
    {
        $evidence = new VerificationEvidence(
            required: true,
            attested: false,
            registrarId: null,
            attestationTimestamp: null,
        );

        $this->assertTrue($evidence->required);
        $this->assertFalse($evidence->attested);
    }

    /**
     * Test SessionContinuity value object
     */
    public function test_session_continuity_preserves_state(): void
    {
        $evidence = new SessionContinuity(
            sessionId: 'sess_123',
            ipHashAtStart: hash('sha256', '192.168.1.1'),
            ipHashCurrent: hash('sha256', '192.168.1.1'),
            deviceChanged: false,
            continuityState: 'continuous',
        );

        $this->assertEquals('sess_123', $evidence->sessionId);
        $this->assertFalse($evidence->deviceChanged);
    }

    /**
     * Test TrustEvidenceAggregate consolidates all evidence
     */
    public function test_trust_evidence_aggregate_bundles_evidence(): void
    {
        $aggregate = new TrustEvidenceAggregate(
            network: new NetworkEvidence(
                currentIpHash: hash('sha256', '192.168.1.1'),
                maxVotesPerIp: 6,
                votesFromThisIp: 1,
                restrictionEnabled: true,
                bindingStrategy: 'ip_strict',
            ),
            device: new DeviceEvidence(
                fingerprintHash: hash('sha256', 'fp_123'),
                matchType: 'exact_match',
                captureMethod: 'browser_api',
                volatility: 'stable',
            ),
            attestation: new VerificationEvidence(
                required: false,
                attested: false,
                registrarId: null,
                attestationTimestamp: null,
            ),
            continuity: new SessionContinuity(
                sessionId: 'sess_123',
                ipHashAtStart: hash('sha256', '192.168.1.1'),
                ipHashCurrent: hash('sha256', '192.168.1.1'),
                deviceChanged: false,
                continuityState: 'continuous',
            ),
        );

        $this->assertNotNull($aggregate->network);
        $this->assertNotNull($aggregate->device);
        $this->assertNotNull($aggregate->attestation);
        $this->assertNotNull($aggregate->continuity);
    }

    /**
     * Test VotingTrustResult captures evaluation state (not authority)
     */
    public function test_voting_trust_result_captures_evaluation_state(): void
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

    /**
     * Test VotingTrustResult with insufficient evidence
     */
    public function test_voting_trust_result_insufficient_evidence(): void
    {
        $result = new VotingTrustResult(
            evaluationState: TrustEvaluationState::INSUFFICIENT_EVIDENCE,
            trustLevel: TrustLevel::Unverified,
            reason: EvaluationReasonCode::NETWORK_EVIDENCE_INSUFFICIENT,
            auditContext: ['network_votes' => 6],
            policyOutcomeSequence: ['network_binding' => 'denied'],
        );

        $this->assertEquals(TrustEvaluationState::INSUFFICIENT_EVIDENCE, $result->evaluationState);
        $this->assertEquals(EvaluationReasonCode::NETWORK_EVIDENCE_INSUFFICIENT, $result->reason);
    }

    /**
     * Test OverlaySignal simplification (3 signal types only)
     */
    public function test_overlay_signal_has_three_signal_types(): void
    {
        $signal = OverlaySignal::contextStable(
            'emergency_condition',
            'No emergency condition detected',
            []
        );

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
        $this->assertEquals('emergency_condition', $signal->overlayIdentifier);
    }

    /**
     * Test ConstitutionalTrustSnapshot is readonly projection (no methods)
     */
    public function test_constitutional_trust_snapshot_is_readonly_projection(): void
    {
        $snapshot = new ConstitutionalTrustSnapshot(
            evaluationState: TrustEvaluationState::SUFFICIENT_EVIDENCE,
            trustLevel: TrustLevel::Attested,
            attestationValid: true,
            continuityPreserved: true,
            activeOverlay: null,
            denialReason: '',
            trustProvenance: ['verification_attestation' => 'passed', 'network_binding' => 'passed'],
        );

        $this->assertEquals(TrustEvaluationState::SUFFICIENT_EVIDENCE, $snapshot->evaluationState);
        $this->assertTrue($snapshot->continuityPreserved);

        // Verify no behavioral methods exist (only data projection)
        $reflection = new \ReflectionClass($snapshot);
        $publicMethods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => $m->getName() !== '__construct'
        );
        $this->assertCount(0, $publicMethods, 'ConstitutionalTrustSnapshot should have no behavioral methods');
    }

    /**
     * Test ConstitutionalTrustSnapshot with denial reason
     */
    public function test_constitutional_trust_snapshot_denial_reason(): void
    {
        $snapshot = new ConstitutionalTrustSnapshot(
            evaluationState: TrustEvaluationState::INSUFFICIENT_EVIDENCE,
            trustLevel: TrustLevel::Unverified,
            attestationValid: false,
            continuityPreserved: false,
            activeOverlay: null,
            denialReason: 'Network limit exceeded for this IP',
            trustProvenance: ['network_binding' => 'denied'],
        );

        $this->assertEquals('Network limit exceeded for this IP', $snapshot->denialReason);
    }

    /**
     * Test ElectionSecurityEvent append-only audit entry
     */
    public function test_election_security_event_immutable(): void
    {
        $now = new \DateTimeImmutable();
        $event = new ElectionSecurityEvent(
            eventType: 'trust_denied',
            electionId: 1,
            voterSlugId: 'voter_abc',
            auditContext: ['reason' => 'network_limit'],
            trustLevelBefore: TrustLevel::Unverified,
            trustLevelAfter: TrustLevel::Unverified,
            policySequence: ['network_binding' => 'denied'],
            overlaySignalType: null,
            constitutionalOutcome: 'deny',
            recordedAt: $now,
        );

        $this->assertEquals('trust_denied', $event->eventType);
        $this->assertEquals(1, $event->electionId);
        $this->assertEquals('deny', $event->constitutionalOutcome);

        // Verify immutability
        try {
            $event->eventType = 'trust_allowed';
            $this->fail('ElectionSecurityEvent should be readonly');
        } catch (\Error $e) {
            $this->assertStringContainsString('readonly', $e->getMessage());
        }
    }

    /**
     * Test ElectionSecurityEvent causality chain
     */
    public function test_election_security_event_captures_causality(): void
    {
        $event = new ElectionSecurityEvent(
            eventType: 'policy_evaluated',
            electionId: 1,
            voterSlugId: 'voter_abc',
            auditContext: [],
            trustLevelBefore: TrustLevel::Attested,
            trustLevelAfter: TrustLevel::ContinuityVerified,
            policySequence: [
                'verification_attestation' => 'passed',
                'network_binding' => 'passed',
                'device_binding' => 'passed',
            ],
            overlaySignalType: null,
            constitutionalOutcome: 'allow',
            recordedAt: new \DateTimeImmutable(),
        );

        $this->assertCount(3, $event->policySequence);
        $this->assertEquals('allow', $event->constitutionalOutcome);
    }
}
