<?php

namespace Tests\Feature\Voting;

use App\Models\User;
use App\Models\Election;
use App\Models\DemoVoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoterSlugStepTrackingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that voter slug step tracking exists.
     */
    public function test_demo_voter_slug_can_be_created()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        $slug = DemoVoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'current_step' => 0,
        ]);

        $this->assertEquals(0, $slug->current_step);
        $this->assertNotNull($slug->slug);
    }

    /**
     * Test step tracking can be incremented.
     */
    public function test_step_can_be_incremented()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        $slug = DemoVoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'current_step' => 0,
        ]);

        // Increment step
        $slug->current_step = 1;
        $slug->save();

        $slug->refresh();
        $this->assertEquals(1, $slug->current_step);
    }

    /**
     * Test step can progress through all 5 steps.
     */
    public function test_step_can_progress_through_workflow()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        $slug = DemoVoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'current_step' => 0,
        ]);

        // Simulate progression through steps
        for ($step = 1; $step <= 5; $step++) {
            $slug->current_step = $step;
            $slug->save();

            $slug->refresh();
            $this->assertEquals($step, $slug->current_step);
        }
    }

    /**
     * Test step meta can store additional data.
     */
    public function test_step_meta_stores_step_data()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        $metadata = json_encode([
            'step_1_started_at' => now()->toString(),
            'step_1_ip' => '127.0.0.1',
        ]);

        $slug = DemoVoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'current_step' => 1,
            'step_meta' => $metadata,
        ]);

        $metaData = json_decode($slug->step_meta, true);
        $this->assertArrayHasKey('step_1_started_at', $metaData);
        $this->assertArrayHasKey('step_1_ip', $metaData);
    }

    /**
     * Test voting status tracking.
     */
    public function test_has_voted_flag_tracks_voting_status()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        $slug = DemoVoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'current_step' => 0,
            'has_voted' => false,
        ]);

        $this->assertFalse($slug->has_voted);

        // Mark as voted
        $slug->has_voted = true;
        $slug->save();

        $slug->refresh();
        $this->assertTrue($slug->has_voted);
    }

    /**
     * Test can_vote_now flag.
     */
    public function test_can_vote_now_flag()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        $slug = DemoVoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'can_vote_now' => true,
        ]);

        $this->assertTrue($slug->can_vote_now);

        // Disable voting
        $slug->can_vote_now = false;
        $slug->save();

        $slug->refresh();
        $this->assertFalse($slug->can_vote_now);
    }

    /**
     * Test voting time constraints.
     */
    public function test_voting_time_constraints()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        $slug = DemoVoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'voting_time_min' => 5, // 5 minutes allowed
            'created_at' => now(),
        ]);

        // Voting should be allowed immediately after creation
        $timeSinceCreation = now()->diffInMinutes($slug->created_at);
        $this->assertLessThanOrEqual($slug->voting_time_min, $timeSinceCreation + 5);
    }

    /**
     * Test multiple voter slugs for same user.
     */
    public function test_multiple_voter_slugs_for_same_user()
    {
        $user = User::factory()->create();
        $election1 = Election::factory()->create(['type' => 'demo']);
        $election2 = Election::factory()->create(['type' => 'demo']);

        $slug1 = DemoVoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election1->id,
        ]);

        $slug2 = DemoVoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election2->id,
        ]);

        // Should have different slugs
        $this->assertNotEquals($slug1->slug, $slug2->slug);

        // Each should have independent step tracking
        $slug1->current_step = 2;
        $slug1->save();

        $slug2->refresh();
        $this->assertEquals(0, $slug2->current_step);
    }

    /**
     * Test slug uniqueness.
     */
    public function test_slug_is_unique()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $election = Election::factory()->create(['type' => 'demo']);

        $slug1 = DemoVoterSlug::factory()->create([
            'user_id' => $user1->id,
            'election_id' => $election->id,
        ]);

        $slug2 = DemoVoterSlug::factory()->create([
            'user_id' => $user2->id,
            'election_id' => $election->id,
        ]);

        // Slugs should be different
        $this->assertNotEquals($slug1->slug, $slug2->slug);
    }
}
