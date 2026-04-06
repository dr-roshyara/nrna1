<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoCode;
use App\Models\DemoVote;
use App\Models\DemoResult;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoVoteCompleteFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Complete Demo Voting Flow - MODE 1
     *
     * Test the complete voting workflow in MODE 1 (public demo):
     * 1. Get election and code
     * 2. Submit vote
     * 3. Record results
     * 4. Verify all data persisted
     */
    public function test_complete_demo_voting_flow_mode1()
    {
        // MODE 1: No organisation
        $user = User::factory()->create(['organisation_id' => null]);
        $this->actingAs($user);
        session(['current_organisation_id' => null]);

        // Create public demo election
        $election = Election::withoutGlobalScopes()->create([
            'name' => 'Public Demo Election',
            'slug' => 'public-demo',
            'type' => 'demo',
            'organisation_id' => null
        ]);

        // Create demo posts
        $post = DemoPost::create([
            'post_id' => 'president-' . $election->id,
            'election_id' => $election->id,
            'name' => 'President',
            'position_order' => 1,
            'organisation_id' => null
        ]);

        // Create demo candidates
        $candidate1 = DemoCandidacy::create([
            'candidacy_id' => 'cand-a-flow-' . $election->id,
            'user_id' => 'user-a-flow',
            'election_id' => $election->id,
            'post_id' => $post->post_id,
            'user_name' => 'Candidate A',
            'organisation_id' => null
        ]);

        $candidate2 = DemoCandidacy::create([
            'candidacy_id' => 'cand-b-flow-' . $election->id,
            'user_id' => 'user-b-flow',
            'election_id' => $election->id,
            'post_id' => $post->post_id,
            'user_name' => 'Candidate B',
            'organisation_id' => null
        ]);

        // Create demo code for user
        $code = DemoCode::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'code_to_open_voting_form' => 'DEMO111',
            'code_to_save_vote' => 'CODE111',
            'organisation_id' => null
        ]);

        // Submit vote
        $vote = DemoVote::create([
            'election_id' => $election->id,
            'voting_code' => Hash::make($code->code_to_open_voting_form . $code->code_to_save_vote),
            'organisation_id' => null
        ]);

        // Record results for both candidates
        DemoResult::create([
            'election_id' => $election->id,
            'vote_id' => $vote->id,
            'post_id' => $post->post_id,
            'candidacy_id' => $candidate1->candidacy_id,
            'organisation_id' => null
        ]);

        DemoResult::create([
            'election_id' => $election->id,
            'vote_id' => $vote->id,
            'post_id' => $post->post_id,
            'candidacy_id' => $candidate2->candidacy_id,
            'organisation_id' => null
        ]);

        // Verify vote recorded
        $this->assertDatabaseHas('demo_votes', [
            'id' => $vote->id,
            'organisation_id' => null
        ]);

        $this->assertGreaterThan(0, DemoResult::where('vote_id', $vote->id)->count());
        $this->assertEquals(2, DemoResult::where('vote_id', $vote->id)->count());
    }

    /**
     * Test: Complete Demo Voting Flow - MODE 2
     *
     * Test the complete voting workflow in MODE 2 (organisation-specific demo):
     * 1. Get organisation-specific election and code
     * 2. Submit vote
     * 3. Record results
     * 4. Verify data isolation (other orgs can't see this vote)
     */
    public function test_complete_demo_voting_flow_mode2()
    {
        // MODE 2: Organisation 1
        $user = User::factory()->create(['organisation_id' => 1]);
        $this->actingAs($user);
        session(['current_organisation_id' => 1]);

        // Create org 1 demo election
        $election = Election::withoutGlobalScopes()->create([
            'name' => 'Organisation 1 Demo',
            'slug' => 'org1-demo',
            'type' => 'demo',
            'organisation_id' => 1
        ]);

        // Create demo post
        $post = DemoPost::create([
            'post_id' => 'president-org1-' . $election->id,
            'election_id' => $election->id,
            'name' => 'President',
            'position_order' => 1,
            'organisation_id' => 1
        ]);

        // Create demo candidates
        $candidate1 = DemoCandidacy::create([
            'candidacy_id' => 'cand-org1-a-flow-' . $election->id,
            'user_id' => 'user-org1-a-flow',
            'election_id' => $election->id,
            'post_id' => $post->post_id,
            'user_name' => 'Org 1 Candidate A',
            'organisation_id' => 1
        ]);

        $candidate2 = DemoCandidacy::create([
            'candidacy_id' => 'cand-org1-b-flow-' . $election->id,
            'user_id' => 'user-org1-b-flow',
            'election_id' => $election->id,
            'post_id' => $post->post_id,
            'user_name' => 'Org 1 Candidate B',
            'organisation_id' => 1
        ]);

        // Create org 1 demo code
        $code = DemoCode::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'code_to_open_voting_form' => 'ORG1111',
            'code_to_save_vote' => 'CODE111',
            'organisation_id' => 1
        ]);

        // Submit vote
        $vote = DemoVote::create([
            'election_id' => $election->id,
            'voting_code' => Hash::make($code->code_to_open_voting_form . $code->code_to_save_vote),
            'organisation_id' => 1
        ]);

        // Record results
        DemoResult::create([
            'election_id' => $election->id,
            'vote_id' => $vote->id,
            'post_id' => $post->post_id,
            'candidacy_id' => $candidate1->candidacy_id,
            'organisation_id' => 1
        ]);

        DemoResult::create([
            'election_id' => $election->id,
            'vote_id' => $vote->id,
            'post_id' => $post->post_id,
            'candidacy_id' => $candidate2->candidacy_id,
            'organisation_id' => 1
        ]);

        // Verify vote recorded with org 1
        $this->assertDatabaseHas('demo_votes', [
            'id' => $vote->id,
            'organisation_id' => 1
        ]);

        $this->assertGreaterThan(0, DemoResult::where('vote_id', $vote->id)->count());
        $this->assertEquals(2, DemoResult::where('vote_id', $vote->id)->count());

        // Org 2 user cannot see this data
        $user2 = User::factory()->create(['organisation_id' => 2]);
        $this->actingAs($user2);
        session(['current_organisation_id' => 2]);

        // Should not see org 1's votes
        $this->assertCount(0, DemoVote::where('election_id', $election->id)->get());
    }
}
