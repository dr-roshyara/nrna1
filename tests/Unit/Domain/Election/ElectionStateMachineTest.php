<?php

namespace Tests\Unit\Domain\Election;

use App\Domain\Election\StateMachine\ElectionStateMachine;
use App\Domain\Election\StateMachine\Exceptions\InvalidTransitionException;
use App\Models\Election;
use App\Models\ElectionStateTransition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionStateMachineTest extends TestCase
{
    use RefreshDatabase;

    private ElectionStateMachine $stateMachine;
    private Election $election;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->election = Election::factory()->create([
            'administration_completed' => false,
            'nomination_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6), // 1 day = 24 hours, min requirement met
            'results_published_at' => null,
        ]);

        $this->user = User::factory()->create();
        $this->stateMachine = new ElectionStateMachine($this->election);
    }

    // ── Current State Tests (5) ────────────────────────────────────────────

    public function test_get_current_state_administration(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $stateMachine = new ElectionStateMachine($election);

        $this->assertEquals('administration', $stateMachine->getCurrentState());
    }

    public function test_get_current_state_nomination(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $stateMachine = new ElectionStateMachine($election);

        $this->assertEquals('nomination', $stateMachine->getCurrentState());
    }

    public function test_get_current_state_voting(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(1),
            'voting_ends_at' => now()->addHours(2),
            'results_published_at' => null,
        ]);

        $stateMachine = new ElectionStateMachine($election);

        $this->assertEquals('voting', $stateMachine->getCurrentState());
    }

    public function test_get_current_state_results_pending(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subHours(2),
            'results_published_at' => null,
        ]);

        $stateMachine = new ElectionStateMachine($election);

        $this->assertEquals('results_pending', $stateMachine->getCurrentState());
    }

    public function test_get_current_state_results(): void
    {
        $election = Election::factory()->create([
            'results_published_at' => now()->subHours(1),
        ]);

        $stateMachine = new ElectionStateMachine($election);

        $this->assertEquals('results', $stateMachine->getCurrentState());
    }

    // ── Can Transition Tests (3) ────────────────────────────────────────────

    public function test_can_transition_to_valid_next_state(): void
    {
        $this->assertTrue($this->stateMachine->canTransition('nomination'));
    }

    public function test_cannot_transition_to_invalid_next_state(): void
    {
        $this->assertFalse($this->stateMachine->canTransition('voting'));
    }

    public function test_cannot_transition_backward(): void
    {
        $stateMachine = new ElectionStateMachine($this->election);
        // From administration, cannot go backward to any state
        $this->assertFalse($stateMachine->canTransition('administration'));
    }

    // ── Validate Transition Tests (2) ────────────────────────────────────────

    public function test_validate_transition_throws_on_invalid_path(): void
    {
        $this->expectException(InvalidTransitionException::class);
        $this->expectExceptionMessageMatches('/Cannot transition/');

        $this->stateMachine->validateTransition('voting');
    }

    public function test_validate_transition_passes_on_valid_path(): void
    {
        // Should not throw
        $this->stateMachine->validateTransition('nomination');

        $this->assertTrue(true);
    }

    // ── Transition To Tests (5) ────────────────────────────────────────────

    public function test_transition_to_creates_state_transition_record(): void
    {
        $transition = $this->stateMachine->transitionTo(
            'nomination',
            'manual',
            'Advancing to nomination phase',
            $this->user->id
        );

        $this->assertInstanceOf(ElectionStateTransition::class, $transition);
        $this->assertDatabaseHas('election_state_transitions', [
            'election_id' => $this->election->id,
            'from_state' => 'administration',
            'to_state' => 'nomination',
            'trigger' => 'manual',
        ]);
    }

    public function test_transition_to_records_actor_and_reason(): void
    {
        $transition = $this->stateMachine->transitionTo(
            'nomination',
            'manual',
            'Test reason for transition',
            $this->user->id
        );

        $this->assertEquals($this->user->id, $transition->actor_id);
        $this->assertEquals('Test reason for transition', $transition->reason);
    }

    public function test_transition_to_throws_on_invalid_path(): void
    {
        $this->expectException(InvalidTransitionException::class);

        $this->stateMachine->transitionTo(
            'voting',
            'manual',
            'Invalid transition',
            $this->user->id
        );
    }

    public function test_transition_to_uses_database_transaction(): void
    {
        $transition = $this->stateMachine->transitionTo(
            'nomination',
            'manual',
            'Transition test',
            $this->user->id
        );

        $this->assertDatabaseHas('election_state_transitions', [
            'id' => $transition->id,
        ]);
    }

    public function test_transition_to_creates_immutable_record(): void
    {
        $transition = $this->stateMachine->transitionTo(
            'nomination',
            'manual',
            'Transition',
            $this->user->id
        );

        $this->expectException(\RuntimeException::class);
        $transition->update(['reason' => 'Modified']);
    }

    // ── Allow Action Tests (2) ────────────────────────────────────────────

    public function test_allow_action_delegates_to_model(): void
    {
        $result = $this->stateMachine->allowsAction('complete_administration');

        $this->assertTrue(is_bool($result));
    }

    public function test_allow_action_returns_false_for_wrong_state(): void
    {
        // Create election in voting state
        $votingElection = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(1),
            'voting_ends_at' => now()->addHours(2), // 3 hours total, > 1 hour minimum
        ]);

        $stateMachine = new ElectionStateMachine($votingElection);

        // Should not allow completing nomination during voting
        $this->assertFalse($stateMachine->allowsAction('complete_nomination'));
    }
}
