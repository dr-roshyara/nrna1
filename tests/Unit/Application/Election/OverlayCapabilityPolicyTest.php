<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Application\Election\Capabilities\Policy\OverlayCapabilityPolicy;
use App\Domain\Election\Enum\CapabilityOutcome;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use Tests\TestCase;

class OverlayCapabilityPolicyTest extends TestCase
{
    private OverlayCapabilityPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new OverlayCapabilityPolicy();
    }

    public function test_policy_declares_overlay_layer(): void
    {
        $this->assertEquals(CapabilityPolicyLayer::Overlay, $this->policy->layer());
    }

    public function test_denies_with_suspended_outcome_when_election_suspended(): void
    {
        $election = new Election();
        $election->suspended_at = now();

        $context = new CapabilityContext(
            $election,
            null,
            'open_voting',
            ['allowed_states' => ['ready_for_voting']],
            ElectionLifecycleState::ReadyForVoting,
        );

        $decision = $this->policy->evaluate($context);

        $this->assertNotNull($decision);
        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::Suspended, $decision->reason);
    }

    public function test_abstrains_when_election_not_suspended(): void
    {
        $election = new Election();
        $election->suspended_at = null;

        $context = new CapabilityContext(
            $election,
            null,
            'open_voting',
            ['allowed_states' => ['ready_for_voting']],
            ElectionLifecycleState::ReadyForVoting,
        );

        $decision = $this->policy->evaluate($context);

        $this->assertNull($decision);
    }

    public function test_allows_resume_even_when_suspended(): void
    {
        $election = new Election();
        $election->suspended_at = now();

        $context = new CapabilityContext(
            $election,
            null,
            'resume',
            ['allowed_states' => ['voting_active']],
            ElectionLifecycleState::VotingActive,
        );

        $decision = $this->policy->evaluate($context);

        $this->assertNull($decision);
    }

    public function test_denies_all_actions_except_resume_when_suspended(): void
    {
        $election = new Election();
        $election->suspended_at = now();

        $actionsToTest = ['open_voting', 'count_votes', 'publish_results', 'complete_counting'];

        foreach ($actionsToTest as $action) {
            $context = new CapabilityContext(
                $election,
                null,
                $action,
                ['allowed_states' => ['voting_active', 'counting']],
                ElectionLifecycleState::VotingActive,
            );

            $decision = $this->policy->evaluate($context);

            $this->assertNotNull($decision, "Expected denial for action: {$action}");
            $this->assertTrue($decision->denies(), "Expected denial for action: {$action}");
            $this->assertEquals(CapabilityDenialReason::Suspended, $decision->reason);
        }
    }

    public function test_abstains_for_all_actions_when_not_suspended(): void
    {
        $election = new Election();
        $election->suspended_at = null;

        $actionsToTest = ['open_voting', 'resume', 'count_votes', 'publish_results'];

        foreach ($actionsToTest as $action) {
            $context = new CapabilityContext(
                $election,
                null,
                $action,
                ['allowed_states' => ['voting_active', 'counting']],
                ElectionLifecycleState::VotingActive,
            );

            $decision = $this->policy->evaluate($context);

            $this->assertNull($decision, "Expected abstain for action: {$action}");
        }
    }

    public function test_overlay_policy_never_grants(): void
    {
        // Test with suspended election (should deny, not grant)
        $suspendedElection = new Election();
        $suspendedElection->suspended_at = now();

        $suspendedContext = new CapabilityContext(
            $suspendedElection,
            null,
            'open_voting',
            ['allowed_states' => ['draft']],
            ElectionLifecycleState::Draft,
        );

        $suspendedDecision = $this->policy->evaluate($suspendedContext);
        $this->assertNotNull($suspendedDecision);
        $this->assertTrue($suspendedDecision->denies());
        $this->assertFalse($suspendedDecision->allows());

        // Test with non-suspended election (should abstain, not grant)
        $normalElection = new Election();
        $normalElection->suspended_at = null;

        $normalContext = new CapabilityContext(
            $normalElection,
            null,
            'any_action',
            ['allowed_states' => ['draft']],
            ElectionLifecycleState::Draft,
        );

        $normalDecision = $this->policy->evaluate($normalContext);
        // Abstain (null) is not a grant, so this assertion confirms non-granting behavior
        $this->assertNull($normalDecision);
    }
}
