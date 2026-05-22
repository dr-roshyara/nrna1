<?php

namespace Tests\Feature\Election;

use Tests\TestCase;
use Tests\Support\ElectionScenarioFactory;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\ElectionStateTransition;
use App\Models\ElectionOfficer;
use App\Application\Election\Facades\ElectionLifecycle;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VotingButtonsStateMachineTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $org;
    protected Election $election;
    protected User $officer;

    protected function setUp(): void
    {
        parent::setUp();

        // Freeze time for deterministic timeline assertions
        Carbon::setTestNow(Carbon::parse('2026-05-20 12:00:00'));

        $this->org = Organisation::factory()->create();

        // Create election using constitutional scenario factory
        // configurationComplete() sets all facts and verifies derived state
        $this->election = ElectionScenarioFactory::configurationComplete($this->org);

        $this->officer = User::factory()->create();

        // Create Spatie role for authorization checks (required by ConstitutionalTransitionGuard)
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'chief']);
        $this->officer->assignRole($role);

        // Clear cached permissions - RefreshDatabase can cause stale permission cache
        $this->officer = $this->officer->fresh();

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

    protected function tearDown(): void
    {
        Carbon::setTestNow(); // Release frozen time
        parent::tearDown();
    }

    /**
     * MODEL TEST: transitionTo() creates ElectionStateTransition and updates election state
     * Tests the core Election::transitionTo() method (bridge to state machine)
     */
    public function test_election_transition_to_voting_creates_transition_record(): void
    {
        // Arrange: Verify scenario factory created valid starting state
        $initialState = ElectionLifecycle::of($this->election)->state()->value;
        $this->assertContains(
            $initialState,
            ['setup', 'ready_for_voting'],
            'Scenario factory derives to setup or ready_for_voting'
        );
        $this->assertEquals(0, ElectionStateTransition::count());

        // Act: Need authenticated context for ConstitutionalTransitionGuard
        $this->actingAs($this->officer);
        $transition = $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual('open_voting', $this->officer->id, 'Opened voting')
        );

        // Assert
        $this->assertInstanceOf(ElectionStateTransition::class, $transition);
        $this->assertEquals(1, ElectionStateTransition::count());
        $this->assertEquals($initialState, $transition->from_state);
        $this->assertEquals('voting_active', $transition->to_state);
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

        // Act - need authenticated context for ConstitutionalTransitionGuard
        $this->actingAs($this->officer);
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
     * TEST 1: openVoting() transitions from setup → voting_active state
     * The open_voting action sets voting window facts as a side effect
     */
    public function test_open_voting_transitions_from_nomination_to_voting(): void
    {
        // Arrange: Verify starting state (setup or ready_for_voting)
        $initialState = ElectionLifecycle::of($this->election)->state()->value;
        $this->assertContains(
            $initialState,
            ['setup', 'ready_for_voting'],
            'Election must start in setup or ready_for_voting state'
        );

        // Act: Officer clicks "Open Voting" button (sets voting window as side effect)
        $response = $this->actingAs($this->officer)->post(
            route('elections.open-voting', ['election' => $this->election->slug])
        );

        // Assert: Response should be successful redirect
        $response->assertStatus(302);
        if ($response->getSession()->has('error')) {
            $this->fail("POST failed with error: " . $response->getSession()->get('error'));
        }
        $response->assertSessionHas('success');

        // Assert: Should transition to voting_active state
        $this->election->refresh();

        // Debug: Check what values are in the database
        $derivedState = ElectionLifecycle::of($this->election)->state()->value;

        if ($derivedState !== 'voting_active') {
            $startsAt = $this->election->voting_starts_at ? $this->election->voting_starts_at->toIso8601String() : 'null';
            $endsAt = $this->election->voting_ends_at ? $this->election->voting_ends_at->toIso8601String() : 'null';
            $state = $this->election->state;
            $this->fail(
                "Expected state 'voting_active' but got '{$derivedState}'. " .
                "Election data: state column='{$state}', voting_starts_at={$startsAt}, " .
                "voting_ends_at={$endsAt}, now()=" . now()->toIso8601String() .
                ". Candidates count=" . $this->election->candidates_count . ", Posts count=" . $this->election->posts_count
            );
        }

        $this->assertEquals(
            'voting_active',
            $derivedState
        );
    }

    /**
     * TEST 2: openVoting() validates election is in setup state
     */
    public function test_open_voting_rejects_if_not_in_nomination_state(): void
    {
        // Arrange: Update facts to move election to voting_active state
        $this->election->update([
            'nomination_completed' => true,
            'nomination_completed_at' => now(),
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
            'administration_completed' => true,
        ]);

        // Sync state column with engine-derived state
        $derivedState = ElectionLifecycle::of($this->election->fresh())->state()->value;
        $this->election->update(['state' => $derivedState]);

        // Verify derived state is voting_active (not setup)
        $this->assertEquals(
            'voting_active',
            ElectionLifecycle::of($this->election->fresh())->state()->value
        );

        // Act: Try to open voting from wrong state
        $response = $this->actingAs($this->officer)->post(
            route('elections.open-voting', ['election' => $this->election->slug])
        );

        // Assert: Should reject with error
        $this->election->refresh();
        $this->assertEquals(
            'voting_active',
            ElectionLifecycle::of($this->election)->state()->value
        );

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertStringContainsString(
            "'open_voting' not allowed",
            session('error')
        );
    }

    /**
     * TEST 3: openVoting() creates ElectionStateTransition record
     */
    public function test_open_voting_creates_state_transition_record(): void
    {
        // Arrange
        $this->assertEquals(0, ElectionStateTransition::count());
        $initialState = ElectionLifecycle::of($this->election)->state()->value;

        // Act: open_voting sets voting window as side effect
        $this->actingAs($this->officer)->post(
            route('elections.open-voting', ['election' => $this->election->slug])
        );

        // Assert
        $this->assertEquals(1, ElectionStateTransition::count());

        $transition = ElectionStateTransition::first();
        $this->assertEquals($initialState, $transition->from_state);
        $this->assertEquals('voting_active', $transition->to_state);
        $this->assertEquals('manual', $transition->trigger);
        $this->assertEquals($this->officer->id, $transition->actor_id);
        $this->assertEquals('Manually opened voting by election officer', $transition->reason);
    }

    /**
     * TEST 4: openVoting() locks voting when transitioning to voting_active state
     */
    public function test_open_voting_locks_voting_immediately(): void
    {
        // Arrange: Verify scenario setup
        $this->assertFalse($this->election->voting_locked, 'Election should not be locked initially');

        // Act
        $this->actingAs($this->officer)->post(
            route('elections.open-voting', ['election' => $this->election->slug])
        );

        // Assert: Voting should be locked as side effect of transition
        $this->election->refresh();
        $this->assertTrue($this->election->voting_locked);
        $this->assertNotNull($this->election->voting_locked_at);
        $this->assertEquals($this->officer->id, $this->election->voting_locked_by);
    }

    /**
     * TEST 5: closeVoting() transitions from voting_active → counting state
     */
    public function test_close_voting_transitions_from_voting_to_results_pending(): void
    {
        // Arrange: Update facts to create voting_active state
        $this->election->update([
            'administration_completed' => true,
            'nomination_completed' => true,
            'nomination_completed_at' => now(),
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);

        // Sync state column with engine-derived state
        $derivedState = ElectionLifecycle::of($this->election->fresh())->state()->value;
        $this->election->update(['state' => $derivedState]);

        // Verify derived state is voting_active
        $this->assertEquals(
            'voting_active',
            ElectionLifecycle::of($this->election->fresh())->state()->value
        );

        // Act
        $response = $this->actingAs($this->officer)->post(
            route('elections.close-voting', ['election' => $this->election->slug])
        );

        // Assert
        $this->election->refresh();
        $this->assertEquals(
            'counting',
            ElectionLifecycle::of($this->election)->state()->value
        );

        $response->assertStatus(302);
        $response->assertSessionHas('success');
    }

    /**
     * TEST 6: closeVoting() validates election is in voting_active state
     */
    public function test_close_voting_rejects_if_not_in_voting_state(): void
    {
        // Arrange: Election is in setup/ready_for_voting state (wrong state for close_voting)
        $initialState = ElectionLifecycle::of($this->election)->state()->value;
        $this->assertContains($initialState, ['setup', 'ready_for_voting']);

        // Act
        $response = $this->actingAs($this->officer)->post(
            route('elections.close-voting', ['election' => $this->election->slug])
        );

        // Assert: State should be unchanged
        $this->election->refresh();
        $this->assertEquals(
            $initialState,
            ElectionLifecycle::of($this->election)->state()->value
        );

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertStringContainsString(
            "'close_voting' not allowed",
            session('error')
        );
    }

    /**
     * TEST 7: closeVoting() creates ElectionStateTransition record
     */
    public function test_close_voting_creates_state_transition_record(): void
    {
        // Arrange: Update facts to create voting_active state
        $this->election->update([
            'administration_completed' => true,
            'nomination_completed' => true,
            'nomination_completed_at' => now(),
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);

        // Sync state column with engine-derived state
        $derivedState = ElectionLifecycle::of($this->election->fresh())->state()->value;
        $this->election->update(['state' => $derivedState]);

        $this->assertEquals(0, ElectionStateTransition::count());

        // Verify derived state is voting_active
        $this->assertEquals(
            'voting_active',
            ElectionLifecycle::of($this->election->fresh())->state()->value
        );

        // Act
        $response = $this->actingAs($this->officer)->post(
            route('elections.close-voting', ['election' => $this->election->slug])
        );

        // Assert response was successful
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Assert transition record was created
        $this->assertEquals(1, ElectionStateTransition::count());

        $transition = ElectionStateTransition::first();
        $this->assertEquals('voting_active', $transition->from_state);
        $this->assertEquals('counting', $transition->to_state);
        $this->assertEquals('manual', $transition->trigger);
        $this->assertEquals($this->officer->id, $transition->actor_id);
    }

    /**
     * TEST 8: closeVoting() guard prevents closing voting in wrong state
     * NOTE: When voting_ends_at is in the past, engine correctly derives to counting state
     * not voting_active. This test verifies that trying to close from wrong state fails.
     */
    public function test_close_voting_prevents_double_close_when_already_locked_and_ended(): void
    {
        // Arrange: Set facts where voting window has already closed
        $this->election->update([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subDays(1),  // Started yesterday
            'voting_ends_at' => now()->subHour(),     // Already ended
            'voting_locked' => true,
            'voting_locked_at' => now()->subHour(),
            'votes_count' => 0,                       // No votes recorded
        ]);

        // Verify derived state is counting (voting window closed)
        $derivedState = ElectionLifecycle::of($this->election->fresh())->state()->value;
        $this->assertEquals(
            'counting',
            $derivedState,
            'Voting window ended → engine derives to counting state'
        );

        // Sync state column with engine-derived state
        $this->election->update(['state' => $derivedState]);

        // Act: Try to close voting from counting state
        $response = $this->actingAs($this->officer)->post(
            route('elections.close-voting', ['election' => $this->election->slug])
        );

        // Assert: Should reject with error (can't close voting that's already in counting)
        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertStringContainsString(
            "'close_voting' not allowed",
            session('error')
        );
    }
}
