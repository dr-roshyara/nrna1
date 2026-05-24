<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Application\Election\Capabilities\Policy\LifecycleCapabilityBaselinePolicy;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use Tests\TestCase;

class LifecycleCapabilityBaselinePolicyTest extends TestCase
{
    private LifecycleCapabilityBaselinePolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new LifecycleCapabilityBaselinePolicy();
    }

    public function test_policy_declares_lifecycle_layer(): void
    {
        $this->assertEquals(CapabilityPolicyLayer::Lifecycle, $this->policy->layer());
    }

    public function test_grants_when_action_allowed_in_state(): void
    {
        $election = new Election();
        $context = new CapabilityContext(
            $election,
            null,
            'open_voting',
            ['allowed_states' => ['ready_for_voting', 'voting_active']],
            ElectionLifecycleState::ReadyForVoting,
        );

        $decision = $this->policy->evaluate($context);

        $this->assertInstanceOf(CapabilityDecision::class, $decision);
        $this->assertTrue($decision->allows());
        $this->assertFalse($decision->denies());
    }

    public function test_denies_when_action_not_allowed_in_state(): void
    {
        $election = new Election();
        $context = new CapabilityContext(
            $election,
            null,
            'open_voting',
            ['allowed_states' => ['ready_for_voting', 'voting_active']],
            ElectionLifecycleState::Draft,
        );

        $decision = $this->policy->evaluate($context);

        $this->assertInstanceOf(CapabilityDecision::class, $decision);
        $this->assertTrue($decision->denies());
        $this->assertFalse($decision->allows());
    }

    public function test_denies_when_no_allowed_states_defined(): void
    {
        $election = new Election();
        $context = new CapabilityContext(
            $election,
            null,
            'custom_action',
            [],
            ElectionLifecycleState::Draft,
        );

        $decision = $this->policy->evaluate($context);

        $this->assertInstanceOf(CapabilityDecision::class, $decision);
        $this->assertTrue($decision->denies());
    }

    public function test_pure_lifecycle_only_ignores_role(): void
    {
        $user = $this->createMock(\App\Models\User::class);
        $election = new Election();
        $context = new CapabilityContext(
            $election,
            $user,
            'open_voting',
            ['allowed_states' => ['ready_for_voting'], 'allowed_roles' => ['chief']],
            ElectionLifecycleState::ReadyForVoting,
        );

        $decision = $this->policy->evaluate($context);

        $this->assertTrue($decision->allows());
    }

    public function test_pure_lifecycle_only_ignores_preconditions(): void
    {
        $election = new Election();
        $context = new CapabilityContext(
            $election,
            null,
            'open_voting',
            [
                'allowed_states' => ['ready_for_voting'],
                'preconditions' => ['voting_window_defined', 'timezone_set'],
            ],
            ElectionLifecycleState::ReadyForVoting,
        );

        $decision = $this->policy->evaluate($context);

        $this->assertTrue($decision->allows());
    }

    public function test_grants_for_all_allowed_states(): void
    {
        $allowedStates = [
            ElectionLifecycleState::Draft,
            ElectionLifecycleState::SetupAdministration,
            ElectionLifecycleState::ReadyForVoting,
        ];

        foreach ($allowedStates as $state) {
            $election = new Election();
            $context = new CapabilityContext(
                $election,
                null,
                'test_action',
                ['allowed_states' => array_map(fn($s) => $s->value, $allowedStates)],
                $state,
            );

            $decision = $this->policy->evaluate($context);
            $this->assertTrue($decision->allows(), "Should allow in state {$state->value}");
        }
    }

    public function test_baseline_policy_never_abstains(): void
    {
        $states = ElectionLifecycleState::cases();

        foreach ($states as $state) {
            $context = new CapabilityContext(
                new Election(),
                null,
                'any_action',
                [],
                $state,
            );

            $decision = $this->policy->evaluate($context);

            $this->assertTrue(
                $decision->allows() || $decision->denies(),
                "Baseline policy must grant or deny, never abstain for state {$state->value}"
            );
        }
    }
}
