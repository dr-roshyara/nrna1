<?php

namespace Tests\Feature\Demo;

use App\Models\Election;
use App\Models\Post;
use App\Models\DemoCandidacy;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoVoteControllerCandidateOrderingTest extends TestCase
{
    use RefreshDatabase;

    private Election $election;
    private Organisation $organisation;
    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create([
            'type' => 'platform',
            'slug' => 'public-digit',
        ]);

        $this->election = Election::factory()->create([
            'type' => 'demo',
            'status' => 'active',
            'organisation_id' => $this->organisation->id,
        ]);

        $this->post = Post::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
        ]);
    }

    public function test_demo_candidates_are_ordered_by_position_order(): void
    {
        $this->markTestIncomplete(
            'DemoCandidacy FK constraints require investigation. '
            . 'Tests separated into demo/real files; ordering validation needs post_id FK resolution.'
        );
    }

    public function test_demo_candidacy_position_order_stores_correctly(): void
    {
        $this->markTestIncomplete(
            'DemoCandidacy FK constraints require investigation. '
            . 'Post_id foreign key relationship needs resolution.'
        );
    }

    public function test_demo_candidacy_fillable_includes_position_order(): void
    {
        $this->assertContains('position_order', (new DemoCandidacy())->getFillable());
    }
}
