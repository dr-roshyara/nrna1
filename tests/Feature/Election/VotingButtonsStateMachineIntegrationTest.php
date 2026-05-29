<?php

namespace Tests\Feature\Election;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Models\Candidacy;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionStateTransition;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * TDD: Test voting button integration with state machine
 *
 * Phase 4: Controller methods using real business workflow (not direct state assignment)
 */
class VotingButtonsStateMachineIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private User $officer;
    private Organisation $testOrg;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a consistent test organisation
        $this->testOrg = Organisation::factory()->create([
            'type' => 'platform'
        ]);

        // Set session organisation context
        session(['current_organisation_id' => $this->testOrg->id]);

        // Create officer for this organisation
        $this->officer = User::factory()->forOrganisation($this->testOrg)->create();

        // Grant officer permission to manage elections
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if ($ability === 'manageSettings') {
                return true;
            }
        });
    }

    // ============================================================
    // HELPER METHODS - Create elections using business workflow
    // ============================================================

    /**
     * Create a fully approved election ready for administration
     */
    private function createApprovedElection(): Election
    {
        $election = Election::factory()->create([
            'organisation_id' => $this->testOrg->id,
            'type' => 'demo',
            'state' => 'draft',
            'expected_voter_count' => 50,  // Exceeds self-service limit (40) → requires manual approval
            'timezone' => 'UTC',  // Required precondition for submission
        ]);

        // Assign chief role for this specific election (required for transitions)
        \App\Models\ElectionOfficer::create([
            'election_id' => $election->id,
            'organisation_id' => $this->testOrg->id,
            'user_id' => $this->officer->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $this->officer->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);

        // Authenticate as officer so that transition guard can verify role
        Auth::setUser($this->officer);

        // Grant platform_admin role for approve action (required by constitution)
        $platformAdminRole = \Spatie\Permission\Models\Role::firstOrCreate(
            ['name' => 'platform_admin'],
            ['guard_name' => 'web']
        );
        $this->officer->assignRole($platformAdminRole);

        // Follow the new approval workflow: draft → submitted_for_approval → approved
        $election->submitForApproval($this->officer->id);  // Transitions to submitted_for_approval
        $election->approve($this->officer->id, 'Approved for testing');  // Now transitions to approved

        return $election;
    }

    /**
     * Take an election through the workflow to nomination state (ready for voting)
     *
     * This sets up all prerequisites for voting but keeps election in 'nomination' state
     * so that voting button tests can exercise the voting transitions.
     */
    private function advanceToVotingState(Election $election): void
    {
        // Setup: Create posts, voters, and committee members
        $post = Post::factory()->create(['election_id' => $election->id]);

        ElectionMembership::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'user_id' => $this->officer->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        ElectionMembership::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'user_id' => User::factory()->forOrganisation($this->testOrg)->create()->id,
            'role' => 'committee',
            'status' => 'active',
        ]);

        // Refresh election object so precondition checks see the memberships
        $election->refresh();

        // Transition from approved → setup_administration (requires chief/deputy role)
        Auth::setUser($this->officer);
        $election->transitionTo(\App\Domain\Election\StateMachine\Transition::manual('begin_setup', $this->officer->id));

        // Phase 1: Complete administration (setup_administration → setup_nomination)
        $election->completeAdministration('Setup complete', $this->officer->id);

        // Phase 2: Add approved candidate (needed for voting validation)
        Candidacy::factory()->create([
            'post_id' => $post->id,
            'status' => 'approved',
        ]);

        // Set voting window (required precondition for open_voting action)
        $election->update([
            'voting_starts_at' => now()->addHours(1),
            'voting_ends_at' => now()->addHours(3),
        ]);

        // Update candidates_count so canEnterVotingPhase() passes
        $election->update([
            'candidates_count' => 1,
            'pending_candidacies_count' => 0,
        ]);

        // NOTE: We do NOT call completeNomination() here because it moves the election
        // directly to 'voting' state. Instead, we set flags to match 'nomination' state.
        // nomination_completed MUST be true so canEnterVotingPhase() passes validation
        $election->update([
            'state' => 'nomination',
            'nomination_completed' => true,
        ]);
    }

    // ============================================================
    // OPEN VOTING BUTTON TESTS
    // ============================================================

    /** @test */
    public function open_voting_transitions_from_nomination_to_voting(): void
    {
        $election = $this->createApprovedElection();
        $this->advanceToVotingState($election);

        // Verify election is in ready_for_voting state before opening voting
        $this->assertEquals('ready_for_voting', ElectionLifecycle::of($election->fresh())->state()->value);

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->testOrg->id])
            ->post(route('elections.open-voting', $election->slug));

        $response->assertSessionHas('success');

        $election->refresh();
        $this->assertTrue($election->voting_locked);
        $this->assertEquals('voting_active', ElectionLifecycle::of($election)->state()->value);

        // Verify audit trail
        $this->assertDatabaseHas('election_state_transitions', [
            'election_id' => $election->id,
            'to_state' => 'voting_active',
            'trigger' => 'manual',
        ]);
    }

    /** @test */
    public function open_voting_rejects_if_not_in_nomination_state(): void
    {
        $election = $this->createApprovedElection();
        $this->advanceToVotingState($election);

        // Open voting first (moves to voting state)
        $this->actingAs($this->officer)
            ->post(route('elections.open-voting', $election->slug));

        $election->refresh();
        $this->assertEquals('voting_active', ElectionLifecycle::of($election)->state()->value);

        // Try to open voting again (should fail)
        $response = $this->actingAs($this->officer)
            ->post(route('elections.open-voting', $election->slug));

        $response->assertSessionHas('error');
    }

    /** @test */
    public function open_voting_rejects_if_missing_candidates(): void
    {
        $election = $this->createApprovedElection();

        // Setup: Add posts, voters, committee
        Post::factory()->create(['election_id' => $election->id]);

        ElectionMembership::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'user_id' => $this->officer->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        ElectionMembership::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'user_id' => User::factory()->forOrganisation($this->testOrg)->create()->id,
            'role' => 'committee',
            'status' => 'active',
        ]);

        // Refresh election object so precondition checks see the memberships
        $election->refresh();

        // Transition from approved → setup_administration
        Auth::setUser($this->officer);
        $election->transitionTo(\App\Domain\Election\StateMachine\Transition::manual('begin_setup', $this->officer->id));

        // Complete administration but NOT nomination (no candidates)
        $election->completeAdministration('Setup complete', $this->officer->id);

        // No candidates added - should fail when trying to open voting
        $response = $this->actingAs($this->officer)
            ->post(route('elections.open-voting', $election->slug));

        $response->assertSessionHas('error');
        $this->assertStringContainsString('candidates', session('error'));
    }

    // ============================================================
    // CLOSE VOTING BUTTON TESTS
    // ============================================================

    /** @test */
    public function close_voting_transitions_from_voting_to_results_pending(): void
    {
        $election = $this->createApprovedElection();
        $this->advanceToVotingState($election);

        // Open voting first
        $response = $this->actingAs($this->officer)
            ->post(route('elections.open-voting', $election->slug));
        $response->assertSessionHas('success');

        $election->refresh();
        $this->assertEquals('voting_active', ElectionLifecycle::of($election)->state()->value);

        // Now close voting
        $response = $this->actingAs($this->officer)
            ->post(route('elections.close-voting', $election->slug));

        $response->assertSessionHas('success');

        $election->refresh();
        $this->assertEquals('counting', ElectionLifecycle::of($election)->state()->value);

        // Verify audit trail
        $this->assertDatabaseHas('election_state_transitions', [
            'election_id' => $election->id,
            'to_state' => 'counting',
            'trigger' => 'manual',
        ]);
    }

    /** @test */
    public function close_voting_rejects_if_not_in_voting_state(): void
    {
        $election = $this->createApprovedElection();
        $this->advanceToVotingState($election);

        // Election is in ready_for_voting state (not yet voting)
        $this->assertEquals('ready_for_voting', ElectionLifecycle::of($election)->state()->value);

        $response = $this->actingAs($this->officer)
            ->post(route('elections.close-voting', $election->slug));

        $response->assertSessionHas('error');
    }

    /** @test */
    public function close_voting_prevents_double_close(): void
    {
        $election = $this->createApprovedElection();
        $this->advanceToVotingState($election);

        // Open voting
        $this->actingAs($this->officer)
            ->post(route('elections.open-voting', $election->slug));

        // Close voting first time
        $response1 = $this->actingAs($this->officer)
            ->post(route('elections.close-voting', $election->slug));
        $response1->assertSessionHas('success');

        $election->refresh();
        $this->assertEquals('counting', ElectionLifecycle::of($election)->state()->value);

        // Close voting second time (should fail - already closed)
        $response2 = $this->actingAs($this->officer)
            ->post(route('elections.close-voting', $election->slug));

        $response2->assertSessionHas('error');
    }

    // ============================================================
    // AUDIT TRAIL TESTS
    // ============================================================

    /** @test */
    public function open_voting_records_actor_id_in_audit(): void
    {
        $election = $this->createApprovedElection();
        $this->advanceToVotingState($election);

        $this->actingAs($this->officer)
            ->post(route('elections.open-voting', $election->slug));

        $transition = ElectionStateTransition::where('election_id', $election->id)
            ->where('to_state', 'voting_active')
            ->first();

        $this->assertNotNull($transition);
        $this->assertEquals($this->officer->id, $transition->actor_id);
    }

    /** @test */
    public function close_voting_records_actor_id_in_audit(): void
    {
        $election = $this->createApprovedElection();
        $this->advanceToVotingState($election);

        // Open and close voting
        $this->actingAs($this->officer)
            ->post(route('elections.open-voting', $election->slug));

        $this->actingAs($this->officer)
            ->post(route('elections.close-voting', $election->slug));

        $transition = ElectionStateTransition::where('election_id', $election->id)
            ->where('to_state', 'counting')
            ->first();

        $this->assertNotNull($transition);
        $this->assertEquals($this->officer->id, $transition->actor_id);
    }

    // ============================================================
    // IDEMPOTENCY & CONCURRENCY TESTS
    // ============================================================

    /** @test */
    public function open_voting_is_idempotent_with_concurrent_requests(): void
    {
        $election = $this->createApprovedElection();
        $this->advanceToVotingState($election);

        // First request
        $response1 = $this->actingAs($this->officer)
            ->post(route('elections.open-voting', $election->slug));
        $response1->assertSessionHas('success');

        // Second request - should fail (already in voting state)
        $response2 = $this->actingAs($this->officer)
            ->post(route('elections.open-voting', $election->slug));

        $this->assertTrue(
            $response2->getSession()->has('error') ||
            $response2->getSession()->has('warning'),
            'Expected error or warning on second open voting request'
        );
    }
}
