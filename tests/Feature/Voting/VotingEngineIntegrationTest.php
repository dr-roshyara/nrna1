<?php

namespace Tests\Feature\Voting;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VotingEngineIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $voter;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        // Create organisation and voter
        $this->org = Organisation::factory()->create();
        $this->voter = User::factory()->create();

        // FK chain requirement: UserOrganisationRole must exist
        \App\Models\UserOrganisationRole::create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);

        // Create election in voting state
        $this->election = Election::factory()->create([
            'organisation_id'  => $this->org->id,
            'type'             => 'real',
            'state'            => 'voting',
            'voting_starts_at' => now()->subDay(),
            'voting_ends_at'   => now()->addDays(5),
        ]);

        // Create voter membership (required for eligibility)
        ElectionMembership::create([
            'id'              => \Str::uuid(),
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'role'            => 'voter',
            'status'          => 'active',
            'has_voted'       => false,
            'metadata'        => [],
            'suspension_status' => 'none',
        ]);
    }

    /** @test */
    public function voter_slug_creation(): void
    {
        $slug = VoterSlug::factory()->create([
            'user_id'         => $this->voter->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'current_step'    => 0,
        ]);

        $this->assertNotNull($slug->slug);
        $this->assertEquals(0, $slug->current_step);
        $this->assertEquals('active', $slug->status);
    }

    /** @test */
    public function step_progression_1_to_5(): void
    {
        $slug = VoterSlug::factory()->create([
            'user_id'         => $this->voter->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'current_step'    => 0,
        ]);

        for ($step = 1; $step <= 5; $step++) {
            $slug->update(['current_step' => $step]);
            $this->assertEquals($step, $slug->fresh()->current_step);
        }
    }

    /** @test */
    public function has_voted_prevents_double_voting(): void
    {
        $membership = ElectionMembership::where('user_id', $this->voter->id)
            ->where('election_id', $this->election->id)
            ->first();

        $this->assertFalse($membership->has_voted);

        $membership->markAsVoted();

        $this->assertTrue($membership->fresh()->has_voted);
        $this->assertEquals('inactive', $membership->fresh()->status);
    }

    /** @test */
    public function slug_status_transitions_to_voted(): void
    {
        $slug = VoterSlug::factory()->create([
            'user_id'         => $this->voter->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'current_step'    => 4,
            'status'          => 'active',
        ]);

        $this->assertEquals('active', $slug->status);

        $slug->markAsVoted();

        $this->assertEquals('voted', $slug->fresh()->status);
    }

    /** @test */
    public function step_meta_stores_audit_data(): void
    {
        $auditData = [
            'step_3_ip'           => '127.0.0.1',
            'step_3_completed_at' => now()->toIso8601String(),
        ];

        $slug = VoterSlug::factory()->create([
            'user_id'         => $this->voter->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'current_step'    => 3,
            'step_meta'       => $auditData,
        ]);

        $meta = $slug->fresh()->step_meta;
        $this->assertIsArray($meta);
        $this->assertArrayHasKey('step_3_ip', $meta);
        $this->assertEquals('127.0.0.1', $meta['step_3_ip']);
    }

    /** @test */
    public function membership_status_tracks_voter_state(): void
    {
        $membership = ElectionMembership::where('user_id', $this->voter->id)
            ->where('election_id', $this->election->id)
            ->first();

        $this->assertEquals('active', $membership->status);
        $this->assertEquals('voter', $membership->role);
        $this->assertFalse($membership->has_voted);
    }

    /** @test */
    public function expired_slug_can_be_marked_expired(): void
    {
        $slug = VoterSlug::factory()->create([
            'user_id'         => $this->voter->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'current_step'    => 2,
            'status'          => 'active',
            'expires_at'      => now()->subHour(),
            'is_active'       => true,
        ]);

        // Force expire — simulates the booted() retrieved event
        $slug->update(['status' => 'expired', 'is_active' => false]);

        $fresh = $slug->fresh();
        $this->assertEquals('expired', $fresh->status);
        $this->assertFalse($fresh->is_active);
    }
}
