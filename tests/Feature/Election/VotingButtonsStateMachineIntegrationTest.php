<?php

namespace Tests\Feature\Election;

use App\Models\Candidacy;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionStateTransition;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        ]);

        $election->approve($this->officer->id, 'Approved for testing');

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

        // Phase 1: Complete administration
        $election->completeAdministration('Setup complete', $this->officer->id);

        // Phase 2: Add approved candidate (needed for voting validation)
        Candidacy::factory()->create([
            'post_id' => $post->id,
            'status' => 'approved',
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

        // Verify election is in nomination state before opening voting
        $this->assertEquals('nomination', $election->fresh()->current_state);

        $response = $this->actingAs($this->officer)
            ->withSession(['current_organisation_id' => $this->testOrg->id])
            ->post(route('elections.open-voting', $election->slug));

        $response->assertSessionHas('success');

        $election->refresh();
        $this->assertTrue($election->voting_locked);
        $this->assertEquals('voting', $election->current_state);

        // Verify audit trail
        $this->assertDatabaseHas('election_state_transitions', [
            'election_id' => $election->id,
            'to_state' => 'voting',
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
        $this->assertEquals('voting', $election->current_state);

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
        $this->assertEquals('voting', $election->current_state);

        // Now close voting
        $response = $this->actingAs($this->officer)
            ->post(route('elections.close-voting', $election->slug));

        $response->assertSessionHas('success');

        $election->refresh();
        $this->assertEquals('results_pending', $election->current_state);

        // Verify audit trail
        $this->assertDatabaseHas('election_state_transitions', [
            'election_id' => $election->id,
            'to_state' => 'results_pending',
            'trigger' => 'manual',
        ]);
    }

    /** @test */
    public function close_voting_rejects_if_not_in_voting_state(): void
    {
        $election = $this->createApprovedElection();
        $this->advanceToVotingState($election);

        // Election is in nomination state (not voting)
        $this->assertEquals('nomination', $election->current_state);

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
        $this->assertEquals('results_pending', $election->current_state);

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
            ->where('to_state', 'voting')
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
            ->where('to_state', 'results_pending')
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
