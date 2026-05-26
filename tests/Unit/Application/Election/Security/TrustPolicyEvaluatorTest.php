<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\OverlayCoordinator;
use App\Application\Election\Security\PolicySequence;
use App\Application\Election\Security\SecurityEventRecorder;
use App\Application\Election\Security\TrustPolicyEvaluator;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustEvidencePrivacyPolicy;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingTrustResult;
use App\Models\Election;
use Tests\TestCase;

class TrustPolicyEvaluatorTest extends TestCase
{
    private function makeEvaluator(): TrustPolicyEvaluator
    {
        // Create a minimal evaluator with all dependencies
        $overlay = new OverlayCoordinator();
        $sequence = new PolicySequence(
            new \App\Application\Election\Security\Policies\VerificationAttestationPolicy(),
            new \App\Application\Election\Security\Policies\NetworkBindingPolicy(),
            new \App\Application\Election\Security\Policies\DeviceBindingPolicy(),
        );
        $recorder = new SecurityEventRecorder();
        $privacy = new TrustEvidencePrivacyPolicy();

        return new TrustPolicyEvaluator($overlay, $sequence, $recorder, $privacy);
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

        $this->assertInstanceOf(VotingTrustResult::class, $result);
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

        // Invariant 3: returns VotingTrustResult, never can_vote, capability arrays
        $this->assertFalse(is_array($result));
        $this->assertFalse(method_exists($result, 'canVote'));
        $this->assertFalse(method_exists($result, 'isAuthorized'));
    }

    public function test_overlay_denial_short_circuits_policies(): void
    {
        $evaluator = $this->makeEvaluator();
        $election = new Election();
        $election->trust_overlay_active = true;
        $election->trust_overlay_reason = 'Emergency suspension';

        $result = $evaluator->evaluate(
            election: $election,
            user: null,
            rawIp: '192.168.1.1',
            rawFingerprint: null,
            sessionId: 'sess-123',
        );

        // If overlay denies, policies not run (reason would be different)
        $this->assertFalse($result->trusted);
        $this->assertEquals('overlay_active', $result->reason);
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
        $this->assertTrue($result->trusted);
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

        // Must be exactly VotingTrustResult, not an array, not a DTO, not a capability object
        $this->assertEquals(VotingTrustResult::class, get_class($result));
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
        $this->assertEquals(TrustLevel::Unverified, $result->trustLevel);
    }
}
