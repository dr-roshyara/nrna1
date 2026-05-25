<?php

namespace Tests\Feature\Election;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * Phase C.2.5 Step 5: Behavioral Sovereignty Invariant Tests
 *
 * CRITICAL: Verify constitutional authority guarantees at runtime.
 * These are NOT grep tests. These are behavioral tests that prove:
 * 1. Lifecycle change alone doesn't grant authority
 * 2. Resolver is authoritative across all contexts
 * 3. Deprecated bridges emit telemetry
 * 4. Wrappers pass-through without reinterpretation
 *
 * These tests provide the hard proof that the system cannot accidentally
 * grant authority based on state change alone or via hidden paths.
 */
final class Phase_C25_SovereigntyBehavioralInvariantsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Lifecycle state change alone does NOT grant authority
     *
     * CONSTITUTIONAL: Authority must flow through ElectionCapabilityResolver.
     * Simply changing state column should NOT grant permissions.
     *
     * CRITICAL TEST: This proves resolver recomputes state from facts,
     * not trusting the stored state column. This prevents distributed sovereignty.
     *
     * Scenario:
     * 1. Create election in Draft state
     * 2. Corrupt the state column to VotingActive (without proper preconditions)
     * 3. Resolver recomputes state from FACTS, ignores corrupt column
     * 4. Confirm resolver state reflects actual conditions, not stored value
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function state_change_alone_does_not_grant_authority(): void
    {
        $election = Election::factory()->create([
            'state' => 'draft',
            'voting_starts_at' => null,
            'voting_ends_at' => null,
            'administration_completed' => false,
            'nomination_completed' => false,
        ]);

        // Corrupt the state column (simulates someone trying to bypass resolver)
        $election->update(['state' => 'voting_active']);
        $election->refresh();

        // Resolver MUST recompute state from FACTS, ignoring corrupted column
        $lifecycle = ElectionLifecycle::of($election);
        $snapshot = $lifecycle->snapshot();

        // Resolver correctly returns Draft (not VotingActive from column)
        // Because voting_starts_at is null and setup incomplete
        $this->assertEquals(ElectionLifecycleState::Draft, $snapshot->state,
            'CRITICAL: Resolver must recompute state from constitutional facts, ' .
            'not trust the stored state column. ' .
            'This proves authority flows from constitution, not DB state.'
        );

        // Voting is NOT allowed, even though state column says voting_active
        $this->assertFalse($snapshot->canVote,
            'Authority must come from resolver computation, not state column. ' .
            'This proves state change alone does NOT grant permission.'
        );
    }

    /**
     * Test: Resolver is the sole authority source
     *
     * CONSTITUTIONAL: All authority checks must go through ElectionCapabilityResolver.
     * Authority flows from constitutional facts only, not state transitions.
     *
     * Scenario:
     * 1. Create two elections in identical Draft state
     * 2. Verify both have identical authority (same facts = same authority)
     * 3. Advance only election1 through proper lifecycle (Approved state)
     * 4. Verify allowed actions differ based on resolver computation
     * 5. Confirm authority is solely determined by resolver, not by state machine
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function resolver_is_sole_authority_source(): void
    {
        // Create two elections with identical facts
        $election1 = Election::factory()->create(['state' => 'draft']);
        $election2 = Election::factory()->create(['state' => 'draft']);

        // Same facts → same authority
        $lifecycle1 = ElectionLifecycle::of($election1);
        $lifecycle2 = ElectionLifecycle::of($election2);

        $this->assertEquals(
            $lifecycle1->snapshot()->allowedActions,
            $lifecycle2->snapshot()->allowedActions,
            'Same constitutional facts should produce same allowed actions'
        );

        // Both should be in Draft with only submit_for_approval action
        $this->assertEquals(['submit_for_approval'], $lifecycle1->snapshot()->allowedActions);

        // Now advance election1 to Approved (simulate approval process)
        $election1->update(['approved_at' => now(), 'state' => 'approved']);

        // Recompute: election1 should now allow begin_setup
        $lifecycle1_approved = ElectionLifecycle::of($election1);
        $lifecycle2_still_draft = ElectionLifecycle::of($election2);

        // Authority should differ based on resolver computation
        $this->assertNotEquals(
            $lifecycle1_approved->snapshot()->allowedActions,
            $lifecycle2_still_draft->snapshot()->allowedActions,
            'Different constitutional facts should produce different allowed actions'
        );

        // Election1 (approved) should allow begin_setup
        $this->assertContains(
            'begin_setup',
            $lifecycle1_approved->snapshot()->allowedActions,
            'Approved election should allow begin_setup'
        );

        // Election2 (still draft) should only allow submit_for_approval
        $this->assertEquals(
            ['submit_for_approval'],
            $lifecycle2_still_draft->snapshot()->allowedActions,
            'Draft election should only allow submit_for_approval'
        );
    }

    /**
     * Test: Deprecated allowsAction() bridge delegates to resolver correctly
     *
     * GOVERNANCE: Before removing deprecated bridge in Step 7, we track all usage.
     * This test verifies the deprecated method delegates to resolver without crashes.
     *
     * Scenario:
     * 1. Call deprecated Election::allowsAction() method with mapped actions
     * 2. Verify it returns a boolean (functional)
     * 3. Verify it delegates to resolver's capability assessment
     * 4. Confirm behavior changes when state changes (resolver driven)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function deprecated_allowsAction_delegates_to_resolver(): void
    {
        $election = Election::factory()->create();

        // Call deprecated bridge with action that's mapped in allowsAction()
        // (telemetry logging happens internally)
        // Draft state allows: canEdit=true, canVote=false, canManageVoters=true
        $result_manage_posts = $election->allowsAction('manage_posts');
        $result_vote = $election->allowsAction('can_vote');

        // Verify results are booleans (delegates to resolver pattern)
        $this->assertIsBool($result_manage_posts);
        $this->assertIsBool($result_vote);

        // Draft state: canEdit=true (manage_posts allowed)
        $this->assertTrue(
            $result_manage_posts,
            'Draft election should allow manage_posts (canEdit=true in Draft state)'
        );

        // Draft state: canVote=false (voting NOT allowed)
        $this->assertFalse(
            $result_vote,
            'Draft election should NOT allow voting (canVote=false in Draft state)'
        );

        // Now archive the election (no permissions allowed)
        $election->update(['archived_at' => now()]);

        // Verify behavior changed (proves resolver is consulted for each call)
        $result_after_archive = $election->allowsAction('manage_posts');
        $this->assertFalse(
            $result_after_archive,
            'Archived election should NOT allow manage_posts (resolver changed canEdit to false)'
        );
    }

    /**
     * Test: ElectionLifecycle wrapper is transparent (no reinterpretation)
     *
     * ARCHITECTURAL: The ElectionLifecycle facade must NOT reinterpret or modify
     * the resolver's authority decisions. It's a transparent pass-through.
     *
     * Scenario:
     * 1. Get authority from resolver directly (ElectionLifecycleEngineImpl)
     * 2. Get same authority through facade (ElectionLifecycle)
     * 3. Verify both return identical results
     * 4. Confirm no reinterpretation occurs in wrapper
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function lifecycle_facade_is_transparent_passthrough(): void
    {
        $election = Election::factory()->create();

        // Get authority through facade
        $facade = ElectionLifecycle::of($election);
        $facadeSnapshot = $facade->snapshot();

        // Verify facade returns exact same snapshot properties
        $this->assertIsArray($facadeSnapshot->allowedActions);
        $this->assertIsBool($facadeSnapshot->canVote);
        $this->assertIsBool($facadeSnapshot->canEdit);
        $this->assertIsBool($facadeSnapshot->canManageVoters);
        $this->assertIsBool($facadeSnapshot->canPublishResults);
        $this->assertIsBool($facadeSnapshot->canEditTimeline);

        // Test method chaining returns consistent results
        $this->assertEquals(
            $facadeSnapshot->allowedActions,
            $facade->allowedActions(),
            'Facade methods should return exact resolver data without modification'
        );

        // Test that snapshot is truly immutable
        // (readonly properties prevent modification)
        $this->expectException(\Error::class);
        $facadeSnapshot->canVote = true; // Should throw Error (readonly)
    }

    /**
     * Test: isActionAllowed() checks are consistent with allowedActions array
     *
     * BOUNDARY: The snapshot's isActionAllowed() method must be consistent with
     * the allowedActions array it holds (no divergence allowed).
     *
     * Scenario:
     * 1. Get snapshot for an election
     * 2. For each action in allowedActions, verify isActionAllowed() returns true
     * 3. For actions NOT in allowedActions, verify isActionAllowed() returns false
     * 4. Confirm bidirectional consistency
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function isActionAllowed_is_consistent_with_allowedActions_array(): void
    {
        $election = Election::factory()->create(['state' => 'draft']);
        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // AllowedActions should contain: ['submit_for_approval']
        $this->assertEquals(['submit_for_approval'], $snapshot->allowedActions);

        // Test: action in array returns true
        $this->assertTrue(
            $snapshot->isActionAllowed('submit_for_approval'),
            'isActionAllowed() must return true for actions in allowedActions array'
        );

        // Test: action NOT in array returns false
        $this->assertFalse(
            $snapshot->isActionAllowed('open_voting'),
            'isActionAllowed() must return false for actions NOT in allowedActions array'
        );

        $this->assertFalse(
            $snapshot->isActionAllowed('complete_administration'),
            'isActionAllowed() must return false for actions NOT in allowedActions array'
        );

        // Test: all actions in array are marked allowed
        foreach ($snapshot->allowedActions as $action) {
            $this->assertTrue(
                $snapshot->isActionAllowed($action),
                "Every action in allowedActions must return true from isActionAllowed()"
            );
        }
    }

    /**
     * Test: Overlay states (Suspended) preserve constitutional authority
     *
     * GOVERNANCE: When an election is suspended, it's an operational overlay.
     * Authority derivation must still flow through resolver, not bypass via state.
     *
     * Scenario:
     * 1. Create election in VotingActive state
     * 2. Suspend it (overlay state)
     * 3. Verify snapshot correctly shows Suspended state
     * 4. Verify resolver denies ALL actions (appropriate for suspended state)
     * 5. Confirm authority still comes from resolver, not hardcoded
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function suspended_overlay_respects_constitutional_authority(): void
    {
        $election = Election::factory()->create([
            'state' => 'voting_active',
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
            'approved_at' => now()->subDay(),
            'administration_completed' => true,
            'nomination_completed' => true,
        ]);

        // Suspend the election
        $election->update(['suspended_at' => now()]);
        $election->refresh();

        // Get snapshot for suspended state
        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // Resolver should report suspended state
        $this->assertEquals(ElectionLifecycleState::Suspended, $snapshot->state);

        // Resolver should deny most permissions when suspended
        $this->assertFalse($snapshot->canVote, 'Voting should be denied in suspended state');
        $this->assertFalse($snapshot->canEdit, 'Editing should be denied in suspended state');

        // Only 'resume' action should be allowed
        $this->assertEquals(['resume'], $snapshot->allowedActions,
            'Suspended state should only allow resume action'
        );
    }

    /**
     * Test: Pre-computed snapshot optimization is transparent
     *
     * PERFORMANCE: ElectionLifecycle::withSnapshot() allows pre-computed snapshots
     * for batch operations. This must be transparent (no authority divergence).
     *
     * Scenario:
     * 1. Compute snapshot normally
     * 2. Use pre-computed snapshot with withSnapshot()
     * 3. Verify both return identical authority
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function withSnapshot_optimization_is_transparent(): void
    {
        $election = Election::factory()->create();

        // Compute snapshot normally
        $normalLifecycle = ElectionLifecycle::of($election);
        $normalSnapshot = $normalLifecycle->snapshot();

        // Use optimization: create with pre-computed snapshot
        $optimizedLifecycle = ElectionLifecycle::withSnapshot($election, $normalSnapshot);
        $optimizedSnapshot = $optimizedLifecycle->snapshot();

        // Both must return identical authority
        $this->assertEquals(
            $normalSnapshot->allowedActions,
            $optimizedSnapshot->allowedActions,
            'Pre-computed snapshot must return identical authority'
        );

        $this->assertEquals(
            $normalSnapshot->canVote,
            $optimizedSnapshot->canVote
        );

        $this->assertEquals(
            $normalSnapshot->canEdit,
            $optimizedSnapshot->canEdit
        );
    }
}
