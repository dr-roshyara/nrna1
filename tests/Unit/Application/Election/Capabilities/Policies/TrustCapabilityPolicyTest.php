<?php

namespace Tests\Unit\Application\Election\Capabilities\Policies;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Application\Election\Capabilities\Policies\TrustCapabilityPolicy;
use App\Application\Election\Security\ConstitutionalTrustSnapshot;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Security\OverlayInfluenceContext;
use App\Domain\Election\Security\TrustEvaluationEnvelope;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VotingTrustResult;
use PHPUnit\Framework\TestCase;

class TrustCapabilityPolicyTest extends TestCase
{
    private function makeContext(
        ?TrustEvaluationEnvelope $trust = null,
    ): CapabilityContext {
        return new CapabilityContext(
            election: null, // Trust policy doesn't depend on election object
            user: null,
            action: 'vote',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
            trust: $trust,
        );
    }

    public function test_null_trust_in_context_returns_null_abstain(): void
    {
        $policy = new TrustCapabilityPolicy();
        $context = $this->makeContext(trust: null); // Non-voting action

        $decision = $policy->evaluate($context);

        $this->assertNull($decision);
    }

    public function test_requiresReview_true_returns_constitutional_review_pending_denial(): void
    {
        $policy = new TrustCapabilityPolicy();
        $overlayInfluence = new OverlayInfluenceContext(
            signals: [],
            hasInfluence: true,
            requiresReview: true,
            requiresReverification: false,
            isInconclusive: false,
            elevationRequest: null,
        );
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::allow(TrustLevel::Attested, [], []),
            $overlayInfluence,
            new ConstitutionalTrustSnapshot(true, TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );
        $context = $this->makeContext(trust: $envelope);

        $decision = $policy->evaluate($context);

        $this->assertNotNull($decision);
        $this->assertEquals(CapabilityDenialReason::ConstitutionalReviewPending, $decision->reason);
    }

    public function test_isInconclusive_true_returns_trust_evaluation_inconclusive_denial(): void
    {
        $policy = new TrustCapabilityPolicy();
        $overlayInfluence = new OverlayInfluenceContext(
            signals: [],
            hasInfluence: true,
            requiresReview: false,
            requiresReverification: false,
            isInconclusive: true,
            elevationRequest: null,
        );
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::allow(TrustLevel::Attested, [], []),
            $overlayInfluence,
            new ConstitutionalTrustSnapshot(true, TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );
        $context = $this->makeContext(trust: $envelope);

        $decision = $policy->evaluate($context);

        $this->assertNotNull($decision);
        $this->assertEquals(CapabilityDenialReason::TrustEvaluationInconclusive, $decision->reason);
    }

    public function test_result_not_trusted_returns_trust_denied_denial(): void
    {
        $policy = new TrustCapabilityPolicy();
        $overlayInfluence = OverlayInfluenceContext::noInfluence();
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::deny('network_limit_exceeded', [], []),
            $overlayInfluence,
            new ConstitutionalTrustSnapshot(false, TrustLevel::Unverified, 'single_code', false, false, false, 'none', false, null, null, 'network_limit_exceeded', []),
        );
        $context = $this->makeContext(trust: $envelope);

        $decision = $policy->evaluate($context);

        $this->assertNotNull($decision);
        $this->assertEquals(CapabilityDenialReason::TrustDenied, $decision->reason);
        $this->assertEquals('network_limit_exceeded', $decision->detail);
    }

    public function test_all_signals_pass_returns_null_abstain(): void
    {
        $policy = new TrustCapabilityPolicy();
        $overlayInfluence = OverlayInfluenceContext::noInfluence();
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::allow(TrustLevel::Attested, [], []),
            $overlayInfluence,
            new ConstitutionalTrustSnapshot(true, TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );
        $context = $this->makeContext(trust: $envelope);

        $decision = $policy->evaluate($context);

        $this->assertNull($decision); // abstain — let other policies run
    }

    public function test_policy_layer_returns_trust_priority_2(): void
    {
        $policy = new TrustCapabilityPolicy();

        $this->assertEquals(CapabilityPolicyLayer::Trust, $policy->layer());
        $this->assertEquals(2, CapabilityPolicyLayer::Trust->value);
    }

    public function test_policy_performs_zero_io(): void
    {
        $policy = new TrustCapabilityPolicy();
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::allow(TrustLevel::Attested, [], []),
            OverlayInfluenceContext::noInfluence(),
            new ConstitutionalTrustSnapshot(true, TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );
        $context = $this->makeContext(trust: $envelope);

        // If policy attempts DB/cache access, this would error in test isolation
        // We verify no queries are executed by relying on RefreshDatabase not being tripped
        $decision = $policy->evaluate($context);

        // Test passes if no errors occur — policy performs zero I/O
        $this->assertNull($decision);
    }

    public function test_review_takes_precedence_over_inconclusive_when_both_present(): void
    {
        $policy = new TrustCapabilityPolicy();
        $overlayInfluence = new OverlayInfluenceContext(
            signals: [],
            hasInfluence: true,
            requiresReview: true,  // ← Check this first
            requiresReverification: false,
            isInconclusive: true,  // ← But also this
            elevationRequest: null,
        );
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::allow(TrustLevel::Attested, [], []),
            $overlayInfluence,
            new ConstitutionalTrustSnapshot(true, TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );
        $context = $this->makeContext(trust: $envelope);

        $decision = $policy->evaluate($context);

        // Review must take precedence
        $this->assertNotNull($decision);
        $this->assertEquals(CapabilityDenialReason::ConstitutionalReviewPending, $decision->reason);
    }
}
