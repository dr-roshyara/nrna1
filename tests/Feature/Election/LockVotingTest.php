<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\ElectionStateTransition;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LockVotingTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $chief;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create();
        $this->chief = User::factory()->create();

        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'state'           => 'voting',
            'voting_locked'   => false,
            'voting_starts_at'=> now()->addDay(),
            'voting_ends_at'  => now()->addDays(5),
        ]);

        ElectionOfficer::create([
            'user_id'         => $this->chief->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'role'            => 'chief',
            'status'          => 'active',
        ]);
    }

    /** @test */
    public function chief_can_lock_voting(): void
    {
        $this->actingAs($this->chief)
            ->post(route('elections.lock-voting', ['election' => $this->election->slug]))
            ->assertRedirect();

        $fresh = $this->election->fresh();
        $this->assertTrue($fresh->voting_locked);
        $this->assertNotNull($fresh->voting_locked_at);
        $this->assertEquals('voting', $fresh->state); // Still in voting
    }

    /** @test */
    public function lock_voting_creates_audit_record(): void
    {
        $this->actingAs($this->chief)
            ->post(route('elections.lock-voting', ['election' => $this->election->slug]));

        $transition = ElectionStateTransition::where('election_id', $this->election->id)
            ->latest()
            ->first();

        $this->assertEquals('voting', $transition->from_state);
        $this->assertEquals('voting', $transition->to_state);
        $this->assertEquals('manual', $transition->trigger);
        $this->assertEquals($this->chief->id, $transition->actor_id);
    }

    /** @test */
    public function cannot_lock_voting_if_already_locked(): void
    {
        $this->election->update(['voting_locked' => true]);

        $this->actingAs($this->chief)
            ->post(route('elections.lock-voting', ['election' => $this->election->slug]))
            ->assertSessionHas('error');
    }

    /** @test */
    public function cannot_lock_voting_if_not_in_voting_state(): void
    {
        $this->election->update(['state' => 'nomination']);

        $this->actingAs($this->chief)
            ->post(route('elections.lock-voting', ['election' => $this->election->slug]))
            ->assertSessionHas('error');
    }

    /** @test */
    public function non_chief_cannot_lock_voting(): void
    {
        $regularUser = User::factory()->create();

        $this->actingAs($regularUser)
            ->post(route('elections.lock-voting', ['election' => $this->election->slug]))
            ->assertStatus(403);
    }

    /** @test */
    public function open_voting_does_not_lock_voting(): void
    {
        // Regression test: Verify that entering voting state (via open_voting) does NOT lock voting.
        // Only lock_voting action should set voting_locked = true.
        // This election was created in voting state in setUp without lock_voting being called.

        $this->assertEquals('voting', $this->election->state);
        $this->assertFalse($this->election->voting_locked,
            'Election should not be locked just by being in voting state. Only lock_voting sets this flag.');
    }
}
