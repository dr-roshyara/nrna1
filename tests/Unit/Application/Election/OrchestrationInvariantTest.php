<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Application\Election\Capabilities\Policy\CapabilityPolicy;
use App\Application\Election\Capabilities\Policy\LifecycleCapabilityBaselinePolicy;
use App\Application\Election\Capabilities\Policy\OverlayCapabilityPolicy;
use App\Application\Election\Services\ElectionCapabilityResolver;
use App\Domain\Election\Enum\CapabilityOutcome;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Models\User;
use Tests\TestCase;

class OrchestrationInvariantTest extends TestCase
{
    private ElectionCapabilityResolver $resolver;
    private Election $election;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->election = new Election();
        $this->user = new User();

        $policies = [
            new OverlayCapabilityPolicy(),
            new LifecycleCapabilityBaselinePolicy(),
        ];

        $this->resolver = new ElectionCapabilityResolver($policies);
    }

    public function test_overlay_short_circuits_lifecycle_for_suspended_election(): void
    {
        $this->election->suspended_at = now();

        $context = new CapabilityContext(
            $this->election,
            $this->user,
            'open_voting',
            ['allowed_states' => ['ready_for_voting', 'voting_active']],
            ElectionLifecycleState::ReadyForVoting,
        );

        $decision = $this->resolver->evaluate($context);

        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::Suspended, $decision->reason);

        $trace = $this->resolver->lastTrace();
        $this->assertNotNull($trace);
        $this->assertTrue($trace->hasTerminalOutcome());

        $shortCircuitEntry = $trace->firstTerminal();
        $this->assertNotNull($shortCircuitEntry);
        $this->assertTrue($shortCircuitEntry->isShortCircuit());
        $this->assertEquals('OverlayCapabilityPolicy', $shortCircuitEntry->policyName);
    }

    public function test_lifecycle_grant_cannot_override_overlay_denial(): void
    {
        $this->election->suspended_at = now();

        $context = new CapabilityContext(
            $this->election,
            $this->user,
            'open_voting',
            ['allowed_states' => ['ready_for_voting', 'voting_active']],
            ElectionLifecycleState::ReadyForVoting,
        );

        $decision = $this->resolver->evaluate($context);

        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::Suspended, $decision->reason);
    }

    public function test_trace_preserves_termination_order(): void
    {
        $this->election->suspended_at = now();

        $context = new CapabilityContext(
            $this->election,
            $this->user,
            'open_voting',
            ['allowed_states' => ['ready_for_voting'], 'allowed_roles' => ['chief']],
            ElectionLifecycleState::ReadyForVoting,
        );

        $decision = $this->resolver->evaluate($context);
        $trace = $this->resolver->lastTrace();

        $this->assertNotNull($trace);
        $this->assertGreaterThanOrEqual(1, $trace->count());

        $firstEntry = $trace->entries[0] ?? null;
        $this->assertNotNull($firstEntry);
        $this->assertTrue($firstEntry->isShortCircuit());
    }

    public function test_downstream_policies_skipped_after_short_circuit(): void
    {
        $this->election->suspended_at = now();

        $context = new CapabilityContext(
            $this->election,
            $this->user,
            'open_voting',
            ['allowed_states' => ['ready_for_voting'], 'allowed_roles' => ['chief']],
            ElectionLifecycleState::ReadyForVoting,
        );

        $decision = $this->resolver->evaluate($context);
        $trace = $this->resolver->lastTrace();

        $this->assertNotNull($trace);
        $policyNames = $trace->policyNames();

        $this->assertContains('OverlayCapabilityPolicy', $policyNames);
        $this->assertNotContains('LifecycleCapabilityBaselinePolicy', $policyNames);
    }

    public function test_overlay_allows_resume_even_when_suspended(): void
    {
        $this->election->suspended_at = now();

        $context = new CapabilityContext(
            $this->election,
            $this->user,
            'resume',
            ['allowed_states' => ['voting_active', 'counting', 'results_published']],
            ElectionLifecycleState::VotingActive,
        );

        $decision = $this->resolver->evaluate($context);

        $this->assertTrue($decision->allows());
    }

    public function test_resolver_returns_decision_with_complete_trace(): void
    {
        $context = new CapabilityContext(
            $this->election,
            $this->user,
            'open_voting',
            ['allowed_states' => ['ready_for_voting', 'voting_active']],
            ElectionLifecycleState::ReadyForVoting,
        );

        $decision = $this->resolver->evaluate($context);
        $trace = $this->resolver->lastTrace();

        $this->assertNotNull($decision);
        $this->assertNotNull($trace);
        $this->assertGreaterThanOrEqual(1, $trace->count());
    }

    public function test_suspended_election_not_affecting_unsuspended(): void
    {
        $unsuspendedElection = new Election();
        $unsuspendedElection->suspended_at = null;

        $context = new CapabilityContext(
            $unsuspendedElection,
            $this->user,
            'open_voting',
            ['allowed_states' => ['ready_for_voting', 'voting_active']],
            ElectionLifecycleState::ReadyForVoting,
        );

        $decision = $this->resolver->evaluate($context);

        $this->assertTrue($decision->allows());

        $trace = $this->resolver->lastTrace();
        $this->assertNotNull($trace);
        $this->assertFalse($trace->hasTerminalOutcome());
    }
}
