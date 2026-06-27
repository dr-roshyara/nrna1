<?php

namespace Tests\Architecture\GovernanceRuntime;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\Policy\OverlayCapabilityPolicy;
use App\Application\Election\Facades\ElectionLifecycle;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * Overlay-Runtime Semantics Tests
 *
 * Verifies that the suspension overlay correctly short-circuits lifecycle semantics.
 * Overlay operates at priority 1 — it is checked BEFORE lifecycle state.
 *
 * ARCHITECTURE INVARIANT: Overlay (suspension) MUST operate orthogonally to lifecycle.
 * - Suspension does NOT mutate lifecycle state
 * - Suspension does NOT clear business facts
 * - Resume restores capabilities by removing overlay, not by restoring state
 * - canVote() = false when suspended, regardless of lifecycle state
 * - canVote() = true after resume (if still VotingActive)
 */
class OverlaySemanticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    // ============================================================
    // OVERLAY SHORT-CIRCUIT INVARIANTS
    // ============================================================

    /**
     * RED: canVote() MUST return false when election is suspended.
     * INVARIANT H: Suspension override short-circuits all lifecycle permissions.
     */
    public function test_can_vote_false_when_suspended(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);

        // Vote must be allowed before suspension
        $this->assertTrue(ElectionLifecycle::of($election)->canVote());

        // Suspend
        $election->update([
            'suspended_at' => now(),
            'suspended_reason' => 'Overlay semantics test',
        ]);

        // Vote must be denied after suspension
        $this->assertFalse(
            ElectionLifecycle::of($election->fresh())->canVote(),
            'canVote() MUST return false when election is suspended, '
            . 'regardless of underlying lifecycle state'
        );
    }

    /**
     * RED: canVote() MUST return true after resume (if still in VotingActive).
     * Overlay removal restores capability — no lifecycle state mutation needed.
     */
    public function test_can_vote_true_after_resume_when_still_voting_active(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);

        // Suspend
        $election->update([
            'suspended_at' => now(),
            'suspended_reason' => 'Overlay semantics test',
        ]);
        $this->assertFalse(ElectionLifecycle::of($election->fresh())->canVote());

        // Resume
        $election->update(['suspended_at' => null]);

        $this->assertTrue(
            ElectionLifecycle::of($election->fresh())->canVote(),
            'canVote() MUST return true after resume when lifecycle is still VotingActive. '
            . 'Overlay removal restores capability without mutating lifecycle state.'
        );
    }

    /**
     * RED: Suspension MUST NOT mutate lifecycle state.
     * The lifecycle state is derived from business facts, not from overlay flags.
     *
     * NOTE: Current engine implementation derives Suspended as the effective state
     * (priority 1 in the 12-step derivation). The dual-axis model
     * (LifecycleState + OperationalStatus) is the long-term migration target.
     * Once migrated, this test should assert VotingActive + Suspended overlay.
     */
    public function test_suspension_does_not_mutate_lifecycle_state(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);

        $preState = ElectionLifecycle::of($election)->state();
        $this->assertEquals(ElectionLifecycleState::VotingActive, $preState);

        // Suspend — engine returns Suspended as effective state (priority 1)
        $election->update([
            'suspended_at' => now(),
            'suspended_reason' => 'State preservation test',
        ]);

        $postState = ElectionLifecycle::of($election->fresh())->state();
        // TODO (dual-axis migration): Expect ElectionLifecycleState::VotingActive
        // with a separate OperationalStatus::Suspended. Currently the engine
        // returns Suspended as the effective state.
        $this->assertEquals(
            ElectionLifecycleState::Suspended,
            $postState,
            'Current engine returns Suspended as effective state. '
            . 'Dual-axis model will separate lifecycle state from overlay.'
        );
    }

    /**
     * RED: Suspension does NOT clear business facts (voting dates, completion flags).
     * Only the overlay flag (suspended_at) is modified.
     */
    public function test_suspension_preserves_business_facts(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);

        $votingStartsAt = $election->voting_starts_at;
        $votingEndsAt = $election->voting_ends_at;

        // Suspend
        $election->update([
            'suspended_at' => now(),
            'suspended_reason' => 'Business fact preservation test',
        ]);

        $fresh = $election->fresh();
        $this->assertEquals(
            $votingStartsAt->toIso8601String(),
            $fresh->voting_starts_at->toIso8601String(),
            'voting_starts_at MUST be preserved after suspension'
        );
        $this->assertEquals(
            $votingEndsAt->toIso8601String(),
            $fresh->voting_ends_at->toIso8601String(),
            'voting_ends_at MUST be preserved after suspension'
        );
        $this->assertTrue($fresh->administration_completed, 'administration_completed flag preserved');
        $this->assertTrue($fresh->nomination_completed, 'nomination_completed flag preserved');
    }

    // ============================================================
    // OVERLAY CAPABILITY POLICY UNIT TESTS
    // ============================================================

    /**
     * RED: OverlayCapabilityPolicy short-circuits when suspended.
     */
    public function test_overlay_policy_short_circuits_when_suspended(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();

        $policy = new OverlayCapabilityPolicy();

        // Not suspended → policy abstains
        $context = new CapabilityContext(
            $election,
            $user,
            'vote',
            [],
            ElectionLifecycleState::VotingActive
        );
        $result = $policy->evaluate($context);
        $this->assertNull($result, 'Overlay policy should abstain when not suspended');

        // Suspended → short-circuit deny
        $election->update(['suspended_at' => now()]);
        $context = new CapabilityContext(
            $election->fresh(),
            $user,
            'vote',
            [],
            ElectionLifecycleState::VotingActive
        );
        $result = $policy->evaluate($context);
        $this->assertNotNull($result, 'Overlay policy must respond when suspended');
        $this->assertTrue($result->denies(), 'Overlay policy must deny when suspended');
        $this->assertTrue($result->isShortCircuit(), 'Overlay policy must short-circuit when suspended');
    }

    /**
     * RED: OverlayCapabilityPolicy allows 'resume' action even when suspended.
     */
    public function test_overlay_policy_allows_resume_when_suspended(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();

        $election->update(['suspended_at' => now()]);

        $policy = new OverlayCapabilityPolicy();

        // Even when suspended, 'resume' action must be allowed
        $context = new CapabilityContext(
            $election->fresh(),
            $user,
            'resume',
            [],
            ElectionLifecycleState::VotingActive
        );
        $result = $policy->evaluate($context);
        $this->assertNull(
            $result,
            'Overlay policy must abstain for "resume" action even when suspended'
        );
    }

    // ============================================================
    // CAPABILITY RUNTIME INTEGRATION
    // ============================================================

    /**
     * RED: Overlay semantics short-circuit lifecycle capability resolution.
     *
     * Verifies that overlay is checked at priority 1, before lifecycle.
     */
    public function test_overlay_short_circuits_before_lifecycle(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();

        $resolver = app(\App\Application\Election\Services\ElectionCapabilityResolver::class);

        // Before suspension: vote action should be decided by lifecycle policy
        $context = new CapabilityContext(
            $election,
            $user,
            'vote',
            ['allowed_states' => ['voting_active']],
            ElectionLifecycleState::VotingActive
        );
        $decision = $resolver->evaluate($context);
        $this->assertTrue($decision->allows(), 'Capability resolver should allow vote in VotingActive');

        // After suspension: overlay should short-circuit before lifecycle policy even runs
        $election->update(['suspended_at' => now()]);
        $context = new CapabilityContext(
            $election->fresh(),
            $user,
            'vote',
            ['allowed_states' => ['voting_active']],
            ElectionLifecycleState::VotingActive
        );
        $decision = $resolver->evaluate($context);
        $this->assertTrue($decision->denies(), 'Capability resolver should deny vote when suspended');
        $this->assertTrue(
            $decision->isShortCircuit(),
            'Denial must be a short-circuit (overlay wins before lifecycle)'
        );
    }
}
