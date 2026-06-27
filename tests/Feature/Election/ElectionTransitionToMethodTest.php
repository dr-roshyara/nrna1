<?php

namespace Tests\Feature\Election;

use App\Domain\Election\Events\VotingOpened;
use App\Domain\Election\StateMachine\Transition;
use App\Events\ElectionStateChangedEvent;
use App\Exceptions\InvalidTransitionException;
use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\User;
use App\Models\ElectionStateTransition;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * TDD: Test Election::transitionTo() with ConstitutionalTransitionGuard authorization
 *
 * Verifies:
 * 1. Role-based authorization (chief, deputy, system)
 * 2. State machine transitions and invalid state detection
 * 3. Precondition validation (voting_window, timezone, has_posts, etc.)
 * 4. Audit trail creation
 * 5. Event dispatching with correct properties
 * 6. Cache lock concurrency control
 * 7. Transaction rollback on failure
 */
class ElectionTransitionToMethodTest extends TestCase
{
    use RefreshDatabase;

    private Election $election;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        \App\Services\TenantContext::clear();

        // Create election in 'setup_nomination' state (ready for open_voting transition)
        $this->election = Election::factory()->demo()->create([
            'state' => 'setup_nomination',
            'timezone' => 'UTC',
            'voting_starts_at' => now()->addDay(),
            'voting_ends_at' => now()->addDay()->addHours(2),
            'administration_completed' => true,
            'nomination_completed' => true,
            'posts_count' => 1,
            'voters_count' => 1,
            'pending_candidacies_count' => 0,
        ]);

        // Set TenantContext to election's org so BelongsToTenant scope works correctly
        \App\Services\TenantContext::set($this->election->organisation_id);

        // Create at least one post for the election (required for voting)
        $post = \App\Models\Post::create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->election->organisation_id,
            'name' => 'President',
            'required_number' => 1,
            'position_order' => 1,
        ]);

        // Create approved candidates using real Candidacy model
        // (candidates_count is a computed accessor that counts Candidacy::where('status', 'approved'))
        $candidate = \App\Models\User::factory()->create([
            'organisation_id' => $this->election->organisation_id,
        ]);
        for ($i = 0; $i < 5; $i++) {
            \App\Models\Candidacy::create([
                'election_id' => $this->election->id,
                'organisation_id' => $this->election->organisation_id,
                'post_id' => $post->id,
                'user_id' => $candidate->id,
                'status' => 'approved',
                'position_order' => $i + 1,
            ]);
        }

        // Create and authenticate user with chief role
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        // Create ElectionOfficer with 'chief' role for authorization
        ElectionOfficer::create([
            'organisation_id' => $this->election->organisation_id,
            'election_id' => $this->election->id,
            'user_id' => $this->user->id,
            'role' => 'chief',
            'status' => 'active',
        ]);
    }

    // ============================================================
    // BASIC TRANSITION FUNCTIONALITY
    // ============================================================

    #[Test]
    public function transitions_to_valid_target_state()
    {
        $transition = $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id, 'Test transition')
        );

        // Should create an ElectionStateTransition record
        $this->assertInstanceOf(ElectionStateTransition::class, $transition);
        $this->assertEquals('setup_nomination', $transition->from_state);
        $this->assertEquals('voting_active', $transition->to_state);
        $this->assertEquals('manual', $transition->trigger);
        $this->assertEquals((string)$this->user->id, $transition->actor_id);
    }

    #[Test]
    public function transition_creates_immutable_audit_record()
    {
        $transition = $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id, 'Testing')
        );

        $found = ElectionStateTransition::where('election_id', $this->election->id)
            ->where('from_state', 'setup_nomination')
            ->where('to_state', 'voting_active')
            ->first();

        $this->assertNotNull($found);
        $this->assertEquals('Testing', $found->reason);
    }

    // ============================================================
    // AUTHORIZATION SCENARIOS
    // ============================================================

    #[Test]
    public function deputy_cannot_perform_chief_only_actions()
    {
        // Change officer role to deputy
        ElectionOfficer::where('election_id', $this->election->id)
            ->update(['role' => 'deputy']);

        $this->expectException(InvalidTransitionException::class);
        $this->expectExceptionMessage("Required role(s): chief");

        $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id)
        );
    }

    #[Test]
    public function unauthenticated_user_cannot_perform_user_role_actions()
    {
        // Logout the user
        \Illuminate\Support\Facades\Auth::logout();

        $this->expectException(InvalidTransitionException::class);
        $this->expectExceptionMessage("Required role(s): chief");

        $this->election->transitionTo(
            Transition::manual('open_voting', 'some-actor-id')
        );
    }

    // ============================================================
    // PRECONDITION VALIDATION
    // ============================================================

    #[Test]
    public function missing_voting_window_rejects_open_voting()
    {
        $this->election->update([
            'voting_starts_at' => null,
            'voting_ends_at' => null,
        ]);

        $this->expectException(\DomainException::class);

        $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id)
        );
    }

    #[Test]
    public function missing_timezone_rejects_open_voting()
    {
        $this->election->update(['timezone' => null]);

        $this->expectException(\DomainException::class);

        $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id)
        );
    }

    // ============================================================
    // FLAG UPDATES AFTER TRANSITION
    // ============================================================

    #[Test]
    public function transition_to_voting_sets_nomination_completed()
    {
        $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id)
        );

        $this->election->refresh();
        $this->assertTrue($this->election->nomination_completed);
    }

    #[Test]
    public function transition_to_voting_locks_voting_immediately()
    {
        $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id)
        );

        $this->election->refresh();
        $this->assertTrue($this->election->voting_locked);
        $this->assertNotNull($this->election->voting_locked_at);
        $this->assertEquals((string)$this->user->id, $this->election->voting_locked_by);
    }

    // ============================================================
    // STATE MACHINE VALIDATION
    // ============================================================

    #[Test]
    public function cannot_open_voting_twice()
    {
        // First transition succeeds
        $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id)
        );

        // Reload to get new state
        $this->election->refresh();

        // Second attempt fails (state is now 'voting_active', not 'setup_nomination')
        $this->expectException(InvalidTransitionException::class);
        $this->expectExceptionMessage("not allowed in state 'voting_active'");

        $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id)
        );
    }

    // ============================================================
    // IDEMPOTENCY & CONCURRENCY (Cache Lock)
    // ============================================================

    #[Test]
    public function concurrent_transitions_are_blocked_with_cache_lock()
    {
        // Simulate first transition acquiring lock
        $lockKey = "election_transition:{$this->election->id}";
        $lock = Cache::lock($lockKey, 30);
        $lock->get();

        // Second transition should fail
        $this->expectException(LockTimeoutException::class);

        $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id)
        );

        $lock->release();
    }

    #[Test]
    public function cache_lock_is_released_after_successful_transition()
    {
        $lockKey = "election_transition:{$this->election->id}";

        // First transition
        $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id)
        );

        // Lock should be released, so we should be able to acquire it
        $lock = Cache::lock($lockKey, 30);
        $this->assertTrue($lock->get());
        $lock->release();
    }

    // ============================================================
    // ERROR HANDLING & ROLLBACK
    // ============================================================

    #[Test]
    public function invalid_transition_throws_exception()
    {
        $this->expectException(\Exception::class);

        // Can't open voting again if already in voting state
        $this->election->update([
            'state' => 'voting_active',
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);

        $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id)
        );
    }

    #[Test]
    public function rollback_on_validation_failure_restores_original_flags()
    {
        $originalAdminCompleted = $this->election->administration_completed;
        $originalNominationCompleted = $this->election->nomination_completed;

        try {
            // Try invalid transition (missing voting window)
            $this->election->update([
                'voting_starts_at' => null,
                'voting_ends_at' => null,
            ]);
            $this->election->transitionTo(
                Transition::manual('open_voting', $this->user->id)
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

    #[Test]
    public function transition_dispatches_election_state_changed_event()
    {
        $eventDispatched = false;
        $eventData = null;

        // VotingOpened is the event dispatched for 'open_voting' action
        \Event::listen(VotingOpened::class, function ($event) use (&$eventDispatched, &$eventData) {
            $eventDispatched = true;
            $eventData = $event;
        });

        $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id, 'Testing')
        );

        $this->assertTrue($eventDispatched);
        $this->assertEquals($this->election->id, $eventData->election->id);
        $this->assertEquals((string)$this->user->id, $eventData->openedBy);
    }

    // ============================================================
    // TRANSACTION ISOLATION
    // ============================================================

    #[Test]
    public function transition_uses_database_transaction()
    {
        $transition = $this->election->transitionTo(
            Transition::manual('open_voting', $this->user->id)
        );

        // Verify both audit record and flag updates exist
        $this->assertDatabaseHas('election_state_transitions', [
            'election_id' => $this->election->id,
            'to_state' => 'voting_active',
        ]);

        $this->assertDatabaseHas('elections', [
            'id' => $this->election->id,
            'nomination_completed' => true,
            'voting_locked' => true,
        ]);
    }
}
