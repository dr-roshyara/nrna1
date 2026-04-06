<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoVoteMode2Test extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: MODE 2 - Organisation-Specific Demo Only Shows Org Specific Data
     *
     * Users from different organisations should only see their own org's demo data.
     * Data from other organisations should not be visible.
     */
    public function test_mode2_org_demo_only_shows_org_specific_data()
    {
        // Create Organisation 1 user
        $user1 = User::factory()->create(['organisation_id' => 1]);
        $this->actingAs($user1);
        session(['current_organisation_id' => 1]);

        // Create Organisation 2 user
        $user2 = User::factory()->create(['organisation_id' => 2]);

        // Create Org 1 demo election
        $election1 = Election::withoutGlobalScopes()->create([
            'name' => 'Org 1 Demo',
            'slug' => 'org1-demo',
            'type' => 'demo',
            'organisation_id' => 1
        ]);

        // Create Org 1 demo posts and candidates
        $post1 = DemoPost::create([
            'post_id' => 'president-org1-' . $election1->id,
            'election_id' => $election1->id,
            'name' => 'President',
            'organisation_id' => 1
        ]);

        DemoCandidacy::create([
            'candidacy_id' => 'cand-org1-a-' . $election1->id,
            'user_id' => 'user-org1-a',
            'election_id' => $election1->id,
            'post_id' => $post1->post_id,
            'user_name' => 'Org 1 Candidate A',
            'organisation_id' => 1
        ]);

        // Create Org 2 demo election (separate)
        $election2 = Election::withoutGlobalScopes()->create([
            'name' => 'Org 2 Demo',
            'slug' => 'org2-demo',
            'type' => 'demo',
            'organisation_id' => 2
        ]);

        $post2 = DemoPost::create([
            'post_id' => 'president-org2-' . $election2->id,
            'election_id' => $election2->id,
            'name' => 'President',
            'organisation_id' => 2
        ]);

        DemoCandidacy::create([
            'candidacy_id' => 'cand-org2-b-' . $election2->id,
            'user_id' => 'user-org2-b',
            'election_id' => $election2->id,
            'post_id' => $post2->post_id,
            'user_name' => 'Org 2 Candidate B',
            'organisation_id' => 2
        ]);

        // As Org 1 user, should ONLY see Org 1 data
        $this->actingAs($user1);
        session(['current_organisation_id' => 1]);

        $this->assertEquals(1, DemoPost::count());
        $this->assertEquals('President', DemoPost::first()->name);
        $this->assertEquals(1, DemoPost::first()->organisation_id);

        $this->assertEquals(1, DemoCandidacy::count());
        $this->assertEquals('Org 1 Candidate A', DemoCandidacy::first()->user_name);
    }
}
