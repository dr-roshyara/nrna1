<?php

namespace Tests\Feature\Election;

use Tests\TestCase;
use App\Models\Election;
use App\Models\User;
use App\Models\ElectionStateTransition;
use App\Models\ElectionOfficer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VotingButtonsStateMachineTest extends TestCase
{
    use RefreshDatabase;

    protected Election $election;
    protected User $officer;

    protected function setUp(): void
    {
        parent::setUp();

        // Create election in nomination phase (ready for voting)
        // administration_completed = true (from inNominationState factory)
        // nomination_completed = true (so canEnterVotingPhase() passes)
        $this->election = Election::factory()->inNominationState()->create([
            'nomination_completed' => true,
            'nomination_completed_at' => now(),
            'voting_starts_at' => null,
            'voting_ends_at' => null,
            'candidates_count' => 1,
            'pending_candidacies_count' => 0,
        ]);

        $this->officer = User::factory()->create();
        // Create ElectionOfficer relationship for authorization
        ElectionOfficer::create([
            'organisation_id' => $this->election->organisation_id,
            'election_id' => $this->election->id,
            'user_id' => $this->officer->id,
            'role' => 'chief',
            'status' => 'active',
        ]);

        // Grant authorization for 'manageSettings' ability
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if ($ability === 'manageSettings') {
                return true;
            }
        });
    }

    /**
     * MODEL TEST: transitionTo() creates ElectionStateTransition and updates election state
     * Tests the core Election::transitionTo() method (bridge to state machine)
     */
    public function test_election_transition_to_voting_creates_transition_record(): void
    {
        // Arrange
        $this->assertEquals('nomination', $this->election->current_state);
        $this->assertEquals(0, ElectionStateTransition::count());

        // Act
        $transition = $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual('open_voting', $this->officer->id, 'Opened voting')
        );

        // Assert
        $this->assertInstanceOf(ElectionStateTransition::class, $transition);
        $this->assertEquals(1, ElectionStateTransition::count());
        $this->assertEquals('nomination', $transition->from_state);
        $this->assertEquals('voting', $transition->to_state);
        $this->assertEquals('manual', $transition->trigger);
        $this->assertEquals($this->officer->id, $transition->actor_id);
        $this->assertEquals('Opened voting', $transition->reason);
    }

    /**
     * MODEL TEST: transitionTo() updates election flags when transitioning to voting
     */
    public function test_election_transition_to_voting_locks_voting_and_completes_nomination(): void
    {
        // Arrange - election is in nomination state with nomination_completed already true
        $this->assertTrue($this->election->nomination_completed);
        $this->assertFalse($this->election->voting_locked);

        // Act
        $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual('open_voting', $this->officer->id, 'Opened voting')
        );
        $this->election->refresh();

        // Assert - verify voting is locked as a side effect of transition
        $this->assertTrue($this->election->voting_locked);
        $this->assertNotNull($this->election->voting_locked_at);
        $this->assertEquals($this->officer->id, $this->election->voting_locked_by);
    }

    /**
     * TEST 1: openVoting() transitions from nomination → voting state
     */
    public function test_open_voting_transitions_from_nomination_to_voting(): void
    {
        // Arrange: Election is in nomination phase
        $this->assertEquals('nomination', $this->election->current_state);

        // Act: Officer clicks "Open Voting" button
        $response = $this->actingAs($this->officer)->post(
            route('elections.open-voting', ['election' => $this->election->slug])
        );

        // Assert: Should transition to voting state
        $this->election->refresh();
        $this->assertEquals('voting', $this->election->current_state);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
    }

    /**
     * TEST 2: openVoting() validates election is in nomination state
     */
    public function test_open_voting_rejects_if_not_in_nomination_state(): void
    {
        // Arrange: Election is in voting state (wrong state)
        // Must explicitly set state because current_state uses the state column, not timestamps
        $this->election->update([
            'state' => 'voting',
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);
        $this->assertEquals('voting', $this->election->current_state);

        // Act: Try to open voting
        $response = $this->actingAs($this->officer)->post(
            route('elections.open-voting', ['election' => $this->election->slug])
        );

        // Assert: Should reject with error
        $this->election->refresh();
        $this->assertEquals('voting', $this->election->current_state);  // Unchanged

        $response->assertStatus(302);
        $response->assertSessionHas('error', "Action 'open_voting' is not allowed from state 'voting'. Allowed: close_voting");
    }

    /**
     * TEST 3: openVoting() creates ElectionStateTransition record
     * RED: Should fail because no transition record is created
     */
    public function test_open_voting_creates_state_transition_record(): void
    {
        // Arrange
        $this->assertEquals(0, ElectionStateTransition::count());

        // Act
        $this->actingAs($this->officer)->post(
            route('elections.open-voting', ['election' => $this->election->slug])
        );

        // Assert
        $this->assertEquals(1, ElectionStateTransition::count());

        $transition = ElectionStateTransition::first();
        $this->assertEquals('nomination', $transition->from_state);
        $this->assertEquals('voting', $transition->to_state);
        $this->assertEquals('manual', $transition->trigger);
        $this->assertEquals($this->officer->id, $transition->actor_id);
        $this->assertEquals('Manually opened voting by election officer', $transition->reason);
    }

    /**
     * TEST 4: openVoting() locks voting when transitioning to voting state
     * RED: Should fail because voting_locked column isn't set
     */
    public function test_open_voting_locks_voting_immediately(): void
    {
        // Arrange - Election starts in nomination phase

        // Act
        $this->actingAs($this->officer)->post(
            route('elections.open-voting', ['election' => $this->election->slug])
        );

        // Assert
        $this->election->refresh();
        $this->assertTrue($this->election->voting_locked);
        $this->assertNotNull($this->election->voting_locked_at);
        $this->assertEquals($this->officer->id, $this->election->voting_locked_by);
    }

    /**
     * TEST 5: closeVoting() transitions from voting → results_pending state
     */
    public function test_close_voting_transitions_from_voting_to_results_pending(): void
    {
        // Arrange: Election is in voting state
        // Must explicitly set state because current_state uses the state column, not timestamps
        $this->election->update([
            'state' => 'voting',
            'nomination_completed' => true,
            'nomination_completed_at' => now(),
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);
        $this->assertEquals('voting', $this->election->current_state);

        // Act
        $response = $this->actingAs($this->officer)->post(
            route('elections.close-voting', ['election' => $this->election->slug])
        );

        // Assert
        $this->election->refresh();
        $this->assertEquals('results_pending', $this->election->current_state);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
    }

    /**
     * TEST 6: closeVoting() validates election is in voting state
     */
    public function test_close_voting_rejects_if_not_in_voting_state(): void
    {
        // Arrange: Election is in nomination state (wrong state) - this is already the setUp state
        $this->assertEquals('nomination', $this->election->current_state);

        // Act
        $response = $this->actingAs($this->officer)->post(
            route('elections.close-voting', ['election' => $this->election->slug])
        );

        // Assert
        $this->election->refresh();
        $this->assertEquals('nomination', $this->election->current_state);  // Unchanged

        $response->assertStatus(302);
        $response->assertSessionHas('error', "Action 'close_voting' is not allowed from state 'nomination'. Allowed: open_voting");
    }

    /**
     * TEST 7: closeVoting() creates ElectionStateTransition record
     */
    public function test_close_voting_creates_state_transition_record(): void
    {
        // Arrange: Election is in voting state
        $this->election->update([
            'state' => 'voting',
            'nomination_completed' => true,
            'nomination_completed_at' => now(),
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);
        $this->assertEquals(0, ElectionStateTransition::count());

        // Act
        $this->actingAs($this->officer)->post(
            route('elections.close-voting', ['election' => $this->election->slug])
        );

        // Assert
        $this->assertEquals(1, ElectionStateTransition::count());

        $transition = ElectionStateTransition::first();
        $this->assertEquals('voting', $transition->from_state);
        $this->assertEquals('results_pending', $transition->to_state);
        $this->assertEquals('manual', $transition->trigger);
        $this->assertEquals($this->officer->id, $transition->actor_id);
    }

    /**
     * TEST 8: closeVoting() double-lock guard prevents closing already-closed voting
     */
    public function test_close_voting_prevents_double_close_when_already_locked_and_ended(): void
    {
        // Arrange: Election voting already ended and locked
        $this->election->update([
            'state' => 'voting',                    // Must be in voting state for guard to fire
            'voting_ends_at' => now()->subHour(),   // Already ended
            'voting_locked' => true,
            'voting_locked_at' => now()->subHour(),
            'votes_count' => 0,                     // Guard checks this
        ]);

        // Act: Try to close voting
        $response = $this->actingAs($this->officer)->post(
            route('elections.close-voting', ['election' => $this->election->slug])
        );

        // Assert: Should reject with error
        $response->assertStatus(302);
        $response->assertSessionHas('error', 'Cannot close voting: Voting ended with no votes recorded.');
    }
}
