<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Post;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionProgressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function progress_marks_current_state(): void
    {
        $election = Election::factory()->create(['state' => 'administration']);
        $step = collect($election->getProgress())->firstWhere('state', 'administration');
        $this->assertEquals('current', $step['status']);
    }

    /** @test */
    public function progress_marks_states_before_current_as_completed(): void
    {
        $election = Election::factory()->create(['state' => 'administration']);
        $progress = collect($election->getProgress());
        $this->assertEquals('completed', $progress->firstWhere('state', 'draft')['status']);
        $this->assertEquals('completed', $progress->firstWhere('state', 'pending_approval')['status']);
    }

    /** @test */
    public function progress_marks_next_state_blocked_when_no_posts(): void
    {
        $election = Election::factory()->create(['state' => 'administration']);
        $nomination = collect($election->getProgress())->firstWhere('state', 'nomination');
        $this->assertEquals('blocked', $nomination['status']);
        $this->assertStringContainsString('No election posts', $nomination['blockedReason']);
    }

    /** @test */
    public function progress_marks_next_state_future_when_prerequisites_met(): void
    {
        $org = Organisation::factory()->create();
        $election = Election::factory()->create([
            'state'           => 'administration',
            'organisation_id' => $org->id,
        ]);
        Post::factory()->create(['election_id' => $election->id]);
        $voter     = User::factory()->create(['organisation_id' => $org->id]);
        $committee = User::factory()->create(['organisation_id' => $org->id]);
        ElectionMembership::create([
            'id' => \Str::uuid(), 'organisation_id' => $org->id,
            'election_id' => $election->id, 'user_id' => $voter->id,
            'role' => 'voter', 'status' => 'active',
            'metadata' => [], 'has_voted' => false, 'suspension_status' => 'none',
        ]);
        ElectionMembership::create([
            'id' => \Str::uuid(), 'organisation_id' => $org->id,
            'election_id' => $election->id, 'user_id' => $committee->id,
            'role' => 'committee', 'status' => 'active',
            'metadata' => [], 'has_voted' => false, 'suspension_status' => 'none',
        ]);

        $nomination = collect($election->fresh()->getProgress())->firstWhere('state', 'nomination');
        $this->assertEquals('future', $nomination['status']);
        $this->assertArrayNotHasKey('blockedReason', $nomination);
    }

    /** @test */
    public function progress_marks_states_beyond_next_as_future_not_blocked(): void
    {
        $election = Election::factory()->create(['state' => 'administration']);
        $progress = collect($election->getProgress());
        $this->assertEquals('future', $progress->firstWhere('state', 'voting')['status']);
        $this->assertEquals('future', $progress->firstWhere('state', 'results_pending')['status']);
        $this->assertEquals('future', $progress->firstWhere('state', 'results')['status']);
    }

    /** @test */
    public function progress_marks_voting_blocked_when_nomination_not_completed(): void
    {
        $election = Election::factory()->create([
            'state' => 'nomination',
            'nomination_completed' => false,
        ]);
        $voting = collect($election->getProgress())->firstWhere('state', 'voting');
        $this->assertEquals('blocked', $voting['status']);
        $this->assertStringContainsString('Nomination phase', $voting['blockedReason']);
    }
}
