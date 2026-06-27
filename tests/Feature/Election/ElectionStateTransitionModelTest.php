<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionStateTransition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionStateTransitionModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_uses_uuid_primary_key(): void
    {
        $election = Election::factory()->create();
        $user = User::factory()->create();

        $transition = ElectionStateTransition::create([
            'election_id' => $election->id,
            'from_state' => 'administration',
            'to_state' => 'nomination',
            'trigger' => 'manual',
            'actor_id' => $user->id,
            'reason' => 'Transition test',
        ]);

        $this->assertIsString($transition->id);
        $this->assertTrue(strlen($transition->id) === 36); // UUID format
    }

    public function test_model_has_no_timestamps_flag(): void
    {
        $election = Election::factory()->create();

        ElectionStateTransition::create([
            'election_id' => $election->id,
            'from_state' => null,
            'to_state' => 'administration',
            'trigger' => 'create',
        ]);

        $this->assertFalse((new ElectionStateTransition())->timestamps);
    }

    public function test_model_casts_metadata_as_array(): void
    {
        $election = Election::factory()->create();
        $metadata = ['key1' => 'value1', 'key2' => 'value2'];

        $transition = ElectionStateTransition::create([
            'election_id' => $election->id,
            'from_state' => 'administration',
            'to_state' => 'nomination',
            'trigger' => 'manual',
            'metadata' => $metadata,
        ]);

        $this->assertIsArray($transition->metadata);
        $this->assertEquals($metadata, $transition->metadata);
    }

    public function test_model_belongs_to_election(): void
    {
        $election = Election::factory()->create();

        $transition = ElectionStateTransition::create([
            'election_id' => $election->id,
            'from_state' => 'administration',
            'to_state' => 'nomination',
            'trigger' => 'manual',
        ]);

        $this->assertInstanceOf(Election::class, $transition->election);
        $this->assertEquals($election->id, $transition->election->id);
    }

    public function test_model_belongs_to_actor_user(): void
    {
        $election = Election::factory()->create();
        $user = User::factory()->create();

        $transition = ElectionStateTransition::create([
            'election_id' => $election->id,
            'from_state' => 'administration',
            'to_state' => 'nomination',
            'trigger' => 'manual',
            'actor_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $transition->actor);
        $this->assertEquals($user->id, $transition->actor->id);
    }

    public function test_updating_model_throws_runtime_exception(): void
    {
        $election = Election::factory()->create();

        $transition = ElectionStateTransition::create([
            'election_id' => $election->id,
            'from_state' => 'administration',
            'to_state' => 'nomination',
            'trigger' => 'manual',
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('immutable');

        $transition->update(['reason' => 'Should not update']);
    }

    public function test_deleting_model_throws_runtime_exception(): void
    {
        $election = Election::factory()->create();

        $transition = ElectionStateTransition::create([
            'election_id' => $election->id,
            'from_state' => 'administration',
            'to_state' => 'nomination',
            'trigger' => 'manual',
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('cannot be deleted');

        $transition->delete();
    }

    public function test_scope_for_election_filters_by_election_id(): void
    {
        $election1 = Election::factory()->create();
        $election2 = Election::factory()->create();

        ElectionStateTransition::create([
            'election_id' => $election1->id,
            'from_state' => 'administration',
            'to_state' => 'nomination',
            'trigger' => 'manual',
        ]);

        ElectionStateTransition::create([
            'election_id' => $election2->id,
            'from_state' => 'nomination',
            'to_state' => 'voting',
            'trigger' => 'manual',
        ]);

        $transitions = ElectionStateTransition::forElection($election1->id)->get();

        $this->assertCount(1, $transitions);
        $this->assertEquals($election1->id, $transitions->first()->election_id);
    }

    public function test_scope_latest_orders_by_created_at_desc(): void
    {
        $election = Election::factory()->create();

        $transition1 = ElectionStateTransition::create([
            'election_id' => $election->id,
            'from_state' => 'administration',
            'to_state' => 'nomination',
            'trigger' => 'manual',
        ]);

        sleep(1);

        $transition2 = ElectionStateTransition::create([
            'election_id' => $election->id,
            'from_state' => 'nomination',
            'to_state' => 'voting',
            'trigger' => 'manual',
        ]);

        $latest = ElectionStateTransition::latest('created_at')->first();

        $this->assertEquals($transition2->id, $latest->id);
    }

    public function test_creating_hook_sets_created_at(): void
    {
        $election = Election::factory()->create();

        $before = now()->subSecond();
        $transition = ElectionStateTransition::create([
            'election_id' => $election->id,
            'from_state' => 'administration',
            'to_state' => 'nomination',
            'trigger' => 'manual',
        ]);
        $after = now()->addSecond();

        $this->assertNotNull($transition->created_at);
        $this->assertTrue($transition->created_at >= $before && $transition->created_at <= $after);
    }
}
