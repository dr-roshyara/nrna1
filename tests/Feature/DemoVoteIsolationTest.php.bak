<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoVoteIsolationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: MODE 1 and MODE 2 Data Are Completely Isolated
     *
     * Verify that public demo (NULL org) and organisation-specific demos (org_id = X)
     * are completely isolated from each other and users only see their own data.
     */
    public function test_mode1_and_mode2_data_are_completely_isolated()
    {
        // MODE 1: Public demo data (NULL org)
        session(['current_organisation_id' => null]);
        $publicElection = Election::withoutGlobalScopes()->create([
            'name' => 'Public Demo',
            'slug' => 'public-demo',
            'type' => 'demo',
            'organisation_id' => null
        ]);

        $publicPost = DemoPost::create([
            'post_id' => 'public-post-' . $publicElection->id,
            'election_id' => $publicElection->id,
            'name' => 'Public Post',
            'organisation_id' => null
        ]);

        $publicCandidate = DemoCandidacy::create([
            'candidacy_id' => 'cand-public-' . $publicElection->id,
            'user_id' => 'user-public',
            'election_id' => $publicElection->id,
            'post_id' => $publicPost->post_id,
            'user_name' => 'Public Candidate',
            'organisation_id' => null
        ]);

        // MODE 2: Org 1 demo data (org_id = 1)
        session(['current_organisation_id' => 1]);
        $org1Election = Election::withoutGlobalScopes()->create([
            'name' => 'Org 1 Demo',
            'slug' => 'org1-demo',
            'type' => 'demo',
            'organisation_id' => 1
        ]);

        $org1Post = DemoPost::create([
            'post_id' => 'org1-post-' . $org1Election->id,
            'election_id' => $org1Election->id,
            'name' => 'Org 1 Post',
            'organisation_id' => 1
        ]);

        $org1Candidate = DemoCandidacy::create([
            'candidacy_id' => 'cand-org1-' . $org1Election->id,
            'user_id' => 'user-org1',
            'election_id' => $org1Election->id,
            'post_id' => $org1Post->post_id,
            'user_name' => 'Org 1 Candidate',
            'organisation_id' => 1
        ]);

        // MODE 2: Org 2 demo data (org_id = 2)
        session(['current_organisation_id' => 2]);
        $org2Election = Election::withoutGlobalScopes()->create([
            'name' => 'Org 2 Demo',
            'slug' => 'org2-demo',
            'type' => 'demo',
            'organisation_id' => 2
        ]);

        $org2Post = DemoPost::create([
            'post_id' => 'org2-post-' . $org2Election->id,
            'election_id' => $org2Election->id,
            'name' => 'Org 2 Post',
            'organisation_id' => 2
        ]);

        $org2Candidate = DemoCandidacy::create([
            'candidacy_id' => 'cand-org2-' . $org2Election->id,
            'user_id' => 'user-org2',
            'election_id' => $org2Election->id,
            'post_id' => $org2Post->post_id,
            'user_name' => 'Org 2 Candidate',
            'organisation_id' => 2
        ]);

        // VERIFY ISOLATION

        // MODE 1 user sees only NULL org data
        session(['current_organisation_id' => null]);
        $this->assertCount(1, DemoPost::all());
        $this->assertEquals('Public Post', DemoPost::first()->name);

        // MODE 2 Org 1 user sees only org 1 data
        session(['current_organisation_id' => 1]);
        $this->assertCount(1, DemoPost::all());
        $this->assertEquals('Org 1 Post', DemoPost::first()->name);

        // MODE 2 Org 2 user sees only org 2 data
        session(['current_organisation_id' => 2]);
        $this->assertCount(1, DemoPost::all());
        $this->assertEquals('Org 2 Post', DemoPost::first()->name);
    }
}
