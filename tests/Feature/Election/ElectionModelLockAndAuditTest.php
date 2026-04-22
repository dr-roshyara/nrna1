<?php

namespace Tests\Feature\Election;

use App\Domain\Election\StateMachine\ElectionStateMachine;
use App\Models\Election;
use App\Models\ElectionAuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionModelLockAndAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_state_machine_returns_election_state_machine_instance(): void
    {
        $election = Election::factory()->create([
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $stateMachine = $election->getStateMachine();

        $this->assertInstanceOf(ElectionStateMachine::class, $stateMachine);
    }

    public function test_get_state_machine_is_memoized(): void
    {
        $election = Election::factory()->create([
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $stateMachine1 = $election->getStateMachine();
        $stateMachine2 = $election->getStateMachine();

        $this->assertSame($stateMachine1, $stateMachine2);
    }

    public function test_log_state_change_still_appends_to_json_column(): void
    {
        $election = Election::factory()->create([
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
            'state_audit_log' => null,
        ]);

        $election->logStateChange('test_action', ['key' => 'value']);

        $this->assertNotNull($election->state_audit_log);
        $this->assertCount(1, $election->state_audit_log);
        $this->assertEquals('test_action', $election->state_audit_log[0]['action']);
    }

    public function test_log_state_change_also_creates_election_audit_log_record(): void
    {
        $election = Election::factory()->create([
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $election->logStateChange('transition', ['state' => 'voting']);

        $this->assertDatabaseHas('election_audit_logs', [
            'election_id' => $election->id,
            'action' => 'transition',
        ]);
    }

    public function test_lock_voting_sets_voting_locked_true(): void
    {
        $election = Election::factory()->create([
            'voting_locked' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $user = User::factory()->create();
        $election->lockVoting($user->id);

        $this->assertTrue($election->fresh()->voting_locked);
    }

    public function test_lock_voting_sets_timestamp_and_actor(): void
    {
        $election = Election::factory()->create([
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $user = User::factory()->create();
        $election->lockVoting($user->id);

        $fresh = $election->fresh();
        $this->assertNotNull($fresh->voting_locked_at);
        $this->assertEquals($user->id, $fresh->voting_locked_by);
    }

    public function test_lock_results_sets_results_locked(): void
    {
        $election = Election::factory()->create([
            'results_locked' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $election->lockResults();

        $this->assertTrue($election->fresh()->results_locked);
    }

    public function test_complete_nomination_locks_voting_on_start(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'voting_locked' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        // Create a post for the election
        $post = \App\Models\Post::factory()->create([
            'election_id' => $election->id,
        ]);

        // Create at least one approved candidacy for the post
        \App\Models\Candidacy::factory()->create([
            'post_id' => $post->id,
            'status' => 'approved',
        ]);

        $user = User::factory()->create();
        $election->completeNomination('Test reason', $user->id);

        $fresh = $election->fresh();
        $this->assertTrue($fresh->nomination_completed);
        $this->assertTrue($fresh->voting_locked);
        $this->assertEquals($user->id, $fresh->voting_locked_by);
    }

    public function test_complete_administration_still_works(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        // Create at least one post
        \App\Models\Post::factory()->create([
            'election_id' => $election->id,
        ]);

        // Create at least one active voter membership
        $voter = User::factory()->create();
        \App\Models\ElectionMembership::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'user_id' => $voter->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $user = User::factory()->create();
        $election->completeAdministration('Test reason', $user->id);

        $this->assertTrue($election->fresh()->administration_completed);
    }
}
