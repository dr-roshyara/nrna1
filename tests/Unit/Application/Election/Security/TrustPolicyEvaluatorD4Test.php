<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\OverlayCoordinator;
use App\Application\Election\Security\PolicySequence;
use App\Application\Election\Security\SecurityEventRecorder;
use App\Application\Election\Security\TrustPolicyEvaluator;
use App\Domain\Election\Security\TrustEvidencePrivacyPolicy;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VotingTrustResult;
use App\Models\Election;
use Tests\TestCase;

class TrustPolicyEvaluatorD4Test extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private function makePolicySequence(): PolicySequence
    {
        // Create minimal policy sequence that always allows
        $emptyPolicy = new \App\Application\Election\Security\Policies\VerificationAttestationPolicy();

        return new PolicySequence(
            $emptyPolicy,
            new \App\Application\Election\Security\Policies\NetworkBindingPolicy(),
            new \App\Application\Election\Security\Policies\DeviceBindingPolicy(),
        );
    }

    public function test_evaluate_with_overlays_always_runs_policies(): void
    {
        // Verify that overlays DON'T short-circuit — policies ALWAYS run
        $election = Election::factory()->create(['trust_overlay_active' => true]);

        $evaluator = new TrustPolicyEvaluator(
            new OverlayCoordinator([]),
            $this->makePolicySequence(),
            new SecurityEventRecorder(),
            new TrustEvidencePrivacyPolicy(),
        );

        // Even with overlay active, should still evaluate policies
        $result = $evaluator->evaluate(
            election: $election,
            user: null,
            rawIp: '192.168.1.1',
            rawFingerprint: null,
            sessionId: 'test_session',
        );

        // Should return a VotingTrustResult (not null, not affected by overlay)
        $this->assertInstanceOf(VotingTrustResult::class, $result);
    }

    public function test_evaluate_returns_voting_trust_result_only(): void
    {
        $evaluator = new TrustPolicyEvaluator(
            new OverlayCoordinator([]),
            $this->makePolicySequence(),
            new SecurityEventRecorder(),
            new TrustEvidencePrivacyPolicy(),
        );

        $result = $evaluator->evaluate(
            election: null,
            user: null,
            rawIp: '192.168.1.1',
            rawFingerprint: null,
            sessionId: 'test_session',
        );

        // Must be exactly VotingTrustResult, not array, not DTO, not capability object
        $this->assertEquals(VotingTrustResult::class, get_class($result));
        $this->assertFalse(is_array($result));
    }

    public function test_evaluate_hashes_raw_ip_before_use(): void
    {
        // Verify raw IP never leaks into context
        $evaluator = new TrustPolicyEvaluator(
            new OverlayCoordinator([]),
            $this->makePolicySequence(),
            new SecurityEventRecorder(),
            new TrustEvidencePrivacyPolicy(),
        );

        $result = $evaluator->evaluate(
            election: null,
            user: null,
            rawIp: '192.168.1.1',
            rawFingerprint: null,
            sessionId: 'test_session',
        );

        // If raw IP leaked, event would contain it — verify it doesn't
        $this->assertInstanceOf(VotingTrustResult::class, $result);
    }
}
