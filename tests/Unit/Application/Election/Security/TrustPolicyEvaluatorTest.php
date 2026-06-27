<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\OverlayAggregator;
use App\Application\Election\Security\PolicySequence;
use App\Application\Election\Security\SecurityEventRecorder;
use App\Application\Election\Security\TrustPolicyEvaluator;
use App\Application\Election\Security\TrustSnapshotAssembler;
use App\Domain\Election\Security\TrustEvaluationEnvelope;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\TrustEvidencePrivacyPolicy;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingTrustResult;
use App\Models\Election;
use Tests\TestCase;

class TrustPolicyEvaluatorTest extends TestCase
{
    private function makeClock(): \App\Domain\Shared\Clock\ClockInterface
    {
        return new class implements \App\Domain\Shared\Clock\ClockInterface {
            public function now(): \DateTimeImmutable
            {
                return new \DateTimeImmutable();
            }
        };
    }

    private function makeEvaluator(): TrustPolicyEvaluator
    {
        // Create a minimal evaluator with all dependencies
        // OverlayAggregator requires array of overlays (can be empty for unit testing)
        $overlay = new OverlayAggregator([]);
        $sequence = new PolicySequence(
            new \App\Application\Election\Security\Policies\VerificationAttestationPolicy(),
            new \App\Application\Election\Security\Policies\NetworkBindingPolicy(),
            new \App\Application\Election\Security\Policies\DeviceBindingPolicy(),
        );
        $recorder = new SecurityEventRecorder($this->makeClock());
        $privacy = new TrustEvidencePrivacyPolicy();
        $assembler = new TrustSnapshotAssembler();

        return new TrustPolicyEvaluator($overlay, $sequence, $recorder, $privacy, $assembler);
    }

    public function test_evaluator_returns_voting_trust_result(): void
    {
        $evaluator = $this->makeEvaluator();

        $result = $evaluator->evaluate(
            election: null,
            user: null,
            rawIp: '192.168.1.1',
            rawFingerprint: null,
            sessionId: 'sess-123',
        );

        $this->assertInstanceOf(TrustEvaluationEnvelope::class, $result);
        $this->assertInstanceOf(VotingTrustResult::class, $result->result);
    }

    public function test_evaluator_never_returns_capability_array(): void
    {
        $evaluator = $this->makeEvaluator();

        $result = $evaluator->evaluate(
            election: null,
            user: null,
            rawIp: '192.168.1.1',
            rawFingerprint: null,
            sessionId: 'sess-123',
        );

        // Invariant 3: returns TrustEvaluationEnvelope, never can_vote, capability arrays
        $this->assertFalse(is_array($result));
        $this->assertFalse(method_exists($result, 'canVote'));
        $this->assertFalse(method_exists($result, 'isAuthorized'));
    }

    public function test_full_allow_path(): void
    {
        $evaluator = $this->makeEvaluator();
        $election = new Election();
        $election->trust_overlay_active = false;
        $election->network_binding_strategy = 'none';
        $election->device_binding_strategy = 'none';

        $result = $evaluator->evaluate(
            election: $election,
            user: null,
            rawIp: '192.168.1.1',
            rawFingerprint: null,
            sessionId: 'sess-123',
        );

        // With no restrictions and no verification required, should allow
        $this->assertEquals(TrustEvaluationState::SUFFICIENT_EVIDENCE, $result->result->evaluationState);
    }

    public function test_returns_only_voting_trust_result(): void
    {
        $evaluator = $this->makeEvaluator();

        $result = $evaluator->evaluate(
            election: null,
            user: null,
            rawIp: '192.168.1.1',
            rawFingerprint: null,
            sessionId: 'sess-123',
        );

        // Must be exactly TrustEvaluationEnvelope, not an array, not a DTO, not a capability object
        $this->assertEquals(TrustEvaluationEnvelope::class, get_class($result));
        $this->assertInstanceOf(VotingTrustResult::class, $result->result);
    }

    public function test_unverified_context_when_no_attestation_required(): void
    {
        $evaluator = $this->makeEvaluator();
        $election = new Election();
        $election->trust_overlay_active = false;
        $election->network_binding_strategy = 'none';

        $result = $evaluator->evaluate(
            election: $election,
            user: null,
            rawIp: '192.168.1.1',
            rawFingerprint: null,
            sessionId: 'sess-123',
        );

        // No verification required → Unverified trust level
        $this->assertEquals(TrustLevel::Unverified, $result->result->trustLevel);
    }
}
