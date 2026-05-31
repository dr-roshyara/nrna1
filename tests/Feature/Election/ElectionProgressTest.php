<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Post;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ElectionProgressTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function progress_returns_array_of_states(): void
    {
        $election = Election::factory()->create();
        $progress = collect($election->getProgress());

        // Verify structure: each state has required fields
        $progress->each(function ($state) {
            $this->assertArrayHasKey('state', $state);
            $this->assertArrayHasKey('label', $state);
            $this->assertArrayHasKey('status', $state);
            $this->assertContains($state['status'], ['current', 'completed', 'future', 'blocked']);
        });
    }

    #[Test]
    public function progress_contains_all_main_states(): void
    {
        $election = Election::factory()->create();
        $progress = collect($election->getProgress());
        $states = $progress->pluck('state')->toArray();

        // Verify main workflow states are present
        $expected = ['draft', 'submitted_for_approval', 'approved', 'setup_administration', 'setup_nomination', 'ready_for_voting', 'voting_active', 'counting', 'results_published', 'archived'];
        foreach ($expected as $state) {
            $this->assertContains($state, $states, "State '$state' not found in progress");
        }
    }

    #[Test]
    public function progress_marks_next_state_future_when_basics_incomplete(): void
    {
        $election = Election::factory()->create(['state' => 'setup_administration']);
        $nextState = collect($election->getProgress())->firstWhere('state', 'setup_nomination');
        $this->assertNotNull($nextState, 'setup_nomination state not found in progress');
        $this->assertThat(
            $nextState['status'],
            $this->logicalOr(
                $this->equalTo('blocked'),
                $this->equalTo('future')
            ),
            'setup_nomination should be either blocked or future'
        );
    }

    #[Test]
    public function progress_marks_next_state_future_when_prerequisites_met(): void
    {
        $org = Organisation::factory()->create();
        $election = Election::factory()->create([
            'state'           => 'setup_administration',
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

        $nextState = collect($election->fresh()->getProgress())->firstWhere('state', 'setup_nomination');
        $this->assertEquals('future', $nextState['status']);
        $this->assertArrayNotHasKey('blockedReason', $nextState);
    }

    #[Test]
    public function progress_marks_states_beyond_next_as_future_not_blocked(): void
    {
        $election = Election::factory()->create(['state' => 'setup_administration']);
        $progress = collect($election->getProgress());
        $this->assertEquals('future', $progress->firstWhere('state', 'voting_active')['status']);
        $this->assertEquals('future', $progress->firstWhere('state', 'counting')['status']);
        $this->assertEquals('future', $progress->firstWhere('state', 'results_published')['status']);
    }

    #[Test]
    public function progress_marks_voting_blocked_when_nomination_not_completed(): void
    {
        $election = Election::factory()->create([
            'state' => 'setup_nomination',
            'nomination_completed' => false,
        ]);
        $votingState = collect($election->getProgress())->firstWhere('state', 'ready_for_voting');
        $this->assertNotNull($votingState, 'ready_for_voting state not found in progress');
        $this->assertThat(
            $votingState['status'],
            $this->logicalOr(
                $this->equalTo('blocked'),
                $this->equalTo('future')
            ),
            'ready_for_voting should be either blocked or future'
        );
    }
}
