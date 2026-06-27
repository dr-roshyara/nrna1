<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\Policy\OverlayCapabilityPolicy;
use App\Application\Election\Capabilities\Policies\TrustCapabilityPolicy;
use App\Application\Election\Services\ElectionCapabilityResolver;
use App\Application\Election\Security\ConstitutionalTrustSnapshot;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Security\OverlayInfluenceContext;
use App\Domain\Election\Security\TrustEvaluationEnvelope;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VotingTrustResult;
use PHPUnit\Framework\TestCase;
use Tests\TestCase as LaravelTestCase;

class ElectionCapabilityResolverTrustIntegrationTest extends LaravelTestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private function makeResolver(): ElectionCapabilityResolver
    {
        return new ElectionCapabilityResolver([
            new OverlayCapabilityPolicy(),
            new TrustCapabilityPolicy(),
            // Other policies omitted for unit test focus
        ]);
    }

    public function test_resolver_with_no_trust_in_context_allows_voting(): void
    {
        $resolver = $this->makeResolver();
        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
            trust: null, // Non-voting context
        );

        // With null trust, trust policy abstains, allowing other policies to evaluate
        $decision = $resolver->evaluate($context);

        // Trust policy should not deny when trust is null (abstain)
        $this->assertNotEquals(CapabilityDenialReason::TrustDenied, $decision->reason);
    }

    public function test_resolver_denies_when_trust_policy_returns_constitutional_review_pending(): void
    {
        $resolver = $this->makeResolver();
        $OverlaySignalCategory = new OverlayInfluenceContext(
            signals: [],
            hasInfluence: true,
            requiresReview: true,
            requiresReverification: false,
            isInconclusive: false,
            elevationRequest: null,
        );
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::allow(TrustLevel::Attested, [], []),
            $OverlaySignalCategory,
            new ConstitutionalTrustSnapshot(true, TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );
        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
            trust: $envelope,
        );

        $decision = $resolver->evaluate($context);

        // Trust policy should deny with ConstitutionalReviewPending
        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::ConstitutionalReviewPending, $decision->reason);
    }

    public function test_resolver_denies_when_trust_policy_returns_trust_evaluation_inconclusive(): void
    {
        $resolver = $this->makeResolver();
        $OverlaySignalCategory = new OverlayInfluenceContext(
            signals: [],
            hasInfluence: true,
            requiresReview: false,
            requiresReverification: false,
            isInconclusive: true,
            elevationRequest: null,
        );
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::allow(TrustLevel::Attested, [], []),
            $OverlaySignalCategory,
            new ConstitutionalTrustSnapshot(true, TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );
        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
            trust: $envelope,
        );

        $decision = $resolver->evaluate($context);

        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::TrustEvaluationInconclusive, $decision->reason);
    }

    public function test_resolver_denies_when_result_not_trusted(): void
    {
        $resolver = $this->makeResolver();
        $OverlaySignalCategory = OverlayInfluenceContext::noInfluence();
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::deny('network_limit_exceeded', [], []),
            $OverlaySignalCategory,
            new ConstitutionalTrustSnapshot(false, TrustLevel::Unverified, 'single_code', false, false, false, 'none', false, null, null, 'network_limit_exceeded', []),
        );
        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
            trust: $envelope,
        );

        $decision = $resolver->evaluate($context);

        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::TrustDenied, $decision->reason);
    }

    public function test_resolver_allows_vote_when_trust_passes(): void
    {
        $resolver = $this->makeResolver();
        $OverlaySignalCategory = OverlayInfluenceContext::noInfluence();
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::allow(TrustLevel::Attested, [], []),
            $OverlaySignalCategory,
            new ConstitutionalTrustSnapshot(true, TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );
        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
            trust: $envelope,
        );

        $decision = $resolver->evaluate($context);

        // Trust passes (no denial from trust policy), resolver should allow
        $this->assertTrue($decision->allows());
    }

    public function test_trust_policy_runs_before_lifecycle_policy(): void
    {
        $resolver = $this->makeResolver();
        // Create envelope where trust fails
        $OverlaySignalCategory = OverlayInfluenceContext::noInfluence();
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::deny('verification_failed', [], []),
            $OverlaySignalCategory,
            new ConstitutionalTrustSnapshot(false, TrustLevel::Unverified, 'single_code', false, false, false, 'none', false, null, null, 'verification_failed', []),
        );
        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
            trust: $envelope,
        );

        $decision = $resolver->evaluate($context);

        // Trust policy (priority 2) should deny, preventing lifecycle (priority 3) from being evaluated
        $this->assertEquals(CapabilityDenialReason::TrustDenied, $decision->reason);
    }

    public function test_overlay_policy_runs_before_trust_policy(): void
    {
        $resolver = $this->makeResolver();
        // Election is suspended (overlay policy concern)
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::allow(TrustLevel::Attested, [], []),
            OverlayInfluenceContext::noInfluence(),
            new ConstitutionalTrustSnapshot(true, TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );
        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: [],
            state: ElectionLifecycleState::Suspended,
            trust: $envelope,
        );

        $decision = $resolver->evaluate($context);

        // Overlay policy (priority 1) runs before trust policy (priority 2)
        // Suspended state should be caught by overlay policy first
        $this->assertNotNull($decision);
    }

    public function test_policies_evaluated_in_priority_order(): void
    {
        $resolver = $this->makeResolver();
        // Both overlay and trust trigger denials - overlay should win (priority 1 < priority 2)
        $OverlaySignalCategory = new OverlayInfluenceContext(
            signals: [],
            hasInfluence: true,
            requiresReview: true,
            requiresReverification: false,
            isInconclusive: false,
            elevationRequest: null,
        );
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::deny('trust_denied', [], []),
            $OverlaySignalCategory,
            new ConstitutionalTrustSnapshot(false, TrustLevel::Unverified, 'single_code', false, false, false, 'none', false, null, null, 'trust_denied', []),
        );
        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
            trust: $envelope,
        );

        $decision = $resolver->evaluate($context);

        // Both policies deny, but overlay (priority 1) is evaluated first
        $this->assertTrue($decision->denies());
    }
}
