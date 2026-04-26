<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionStateTransition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * TDD: Test Election::transitionTo() model method with Transition VO
 *
 * Tests the action-based API using Transition::manual()
 */
class ElectionTransitionToMethodTest extends TestCase
{
    use RefreshDatabase;

    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        $this->election = Election::factory()->demo()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'posts_count' => 1,
            'voters_count' => 1,
            'election_committee_members_count' => 1,
            'candidates_count' => 5,
            'pending_candidacies_count' => 0,
        ]);
    }

    // ============================================================
    // BASIC TRANSITION FUNCTIONALITY
    // ============================================================

    /** @test */
    public function transitions_to_valid_target_state()
    {
        $transition = $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual('open_voting', 'test-actor-id', 'Test transition')
        );

        // Should create an ElectionStateTransition record
        $this->assertInstanceOf(ElectionStateTransition::class, $transition);
        $this->assertEquals('nomination', $transition->from_state);
        $this->assertEquals('voting', $transition->to_state);
        $this->assertEquals('manual', $transition->trigger);
        $this->assertEquals('test-actor-id', $transition->actor_id);
    }

    /** @test */
    public function transition_creates_immutable_audit_record()
    {
        $transition = $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual('open_voting', 'actor-123', 'Testing')
        );

        $found = ElectionStateTransition::where('election_id', $this->election->id)
            ->where('from_state', 'nomination')
            ->where('to_state', 'voting')
            ->first();

        $this->assertNotNull($found);
        $this->assertEquals('Testing', $found->reason);
    }

    // ============================================================
    // FLAG UPDATES AFTER TRANSITION
    // ============================================================

    /** @test */
    public function transition_to_voting_sets_nomination_completed()
    {
        $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual('open_voting', 'system')
        );

        $this->election->refresh();
        $this->assertTrue($this->election->nomination_completed);
    }

    /** @test */
    public function transition_to_voting_locks_voting_immediately()
    {
        $actorId = 'admin-user-123';
        $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual('open_voting', $actorId)
        );

        $this->election->refresh();
        $this->assertTrue($this->election->voting_locked);
        $this->assertNotNull($this->election->voting_locked_at);
        $this->assertEquals($actorId, $this->election->voting_locked_by);
    }

    // ============================================================
    // IDEMPOTENCY & CONCURRENCY (Cache Lock)
    // ============================================================

    /** @test */
    public function concurrent_transitions_are_blocked_with_cache_lock()
    {
        // Simulate first transition acquiring lock
        $lockKey = "election_transition:{$this->election->id}";
        $lock = Cache::lock($lockKey, 30);
        $lock->get();

        // Second transition should fail
        $this->expectException(\RuntimeException::class);

        $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual('open_voting', 'system')
        );

        $lock->release();
    }

    /** @test */
    public function cache_lock_is_released_after_successful_transition()
    {
        $lockKey = "election_transition:{$this->election->id}";

        // First transition
        $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual('open_voting', 'system')
        );

        // Lock should be released, so we should be able to acquire it
        $lock = Cache::lock($lockKey, 30);
        $this->assertTrue($lock->get());
        $lock->release();
    }

    // ============================================================
    // ERROR HANDLING & ROLLBACK
    // ============================================================

    /** @test */
    public function invalid_transition_throws_exception()
    {
        $this->expectException(\Exception::class);

        // Can't open voting again if already in voting state
        $this->election->update([
            'state' => 'voting',
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);

        $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual('open_voting', 'system')
        );
    }

    /** @test */
    public function rollback_on_validation_failure_restores_original_flags()
    {
        $originalAdminCompleted = $this->election->administration_completed;
        $originalNominationCompleted = $this->election->nomination_completed;

        try {
            // Try invalid transition (missing candidates)
            $this->election->update(['candidates_count' => 0]);
            $this->election->transitionTo(
                \App\Domain\Election\StateMachine\Transition::manual('open_voting', 'system')
            );
        } catch (\Exception $e) {
            // Expected
        }

        $this->election->refresh();
        // Flags should be restored to original
        $this->assertEquals($originalAdminCompleted, $this->election->administration_completed);
        $this->assertEquals($originalNominationCompleted, $this->election->nomination_completed);
    }

    // ============================================================
    // EVENT DISPATCHING
    // ============================================================

    /** @test */
    public function transition_dispatches_election_state_changed_event()
    {
        $eventDispatched = false;
        $eventData = null;

        // Mock event listener
        \Event::listen(\App\Events\ElectionStateChangedEvent::class, function ($event) use (&$eventDispatched, &$eventData) {
            $eventDispatched = true;
            $eventData = $event;
        });

        $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual('open_voting', 'actor-123', 'Testing')
        );

        $this->assertTrue($eventDispatched);
        $this->assertEquals('nomination', $eventData->fromState);
        $this->assertEquals('voting', $eventData->toState);
        $this->assertEquals('manual', $eventData->trigger);
        $this->assertEquals('actor-123', $eventData->actorId);
    }

    // ============================================================
    // TRANSACTION ISOLATION
    // ============================================================

    /** @test */
    public function transition_uses_database_transaction()
    {
        $transition = $this->election->transitionTo(
            \App\Domain\Election\StateMachine\Transition::manual('open_voting', 'system')
        );

        // Verify both audit record and flag updates exist
        $this->assertDatabaseHas('election_state_transitions', [
            'election_id' => $this->election->id,
            'to_state' => 'voting',
        ]);

        $this->assertDatabaseHas('elections', [
            'id' => $this->election->id,
            'nomination_completed' => true,
            'voting_locked' => true,
        ]);
    }
}
