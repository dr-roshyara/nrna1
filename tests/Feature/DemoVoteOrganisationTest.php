<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\DemoVote;
use App\Models\DemoResult;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * DemoVoteOrganisationTest
 *
 * Tests that demo votes and results respect organisation_id scoping:
 * - MODE 1: demo_votes.organisation_id = NULL (demo mode, no org)
 * - MODE 2: demo_votes.organisation_id = X (org-scoped demo data)
 *
 * This ensures users from different organisations see only their own demo data.
 */
class DemoVoteOrganisationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
    }

    protected function tearDown(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        parent::tearDown();
    }

    /**
     * Helper to set session context for a user
     */
    protected function setUserContext($user)
    {
        $this->actingAs($user);
        session(['current_organisation_id' => $user->organisation_id]);
        return $this;
    }

    /**
     * Test MODE 1: Demo vote gets NULL organisation_id when user has no org
     *
     * User with organisation_id = NULL should create demo votes with organisation_id = NULL
     */
    public function test_mode1_demo_vote_gets_null_organisation_id()
    {
        // MODE 1: User with no organisation
        $user = User::factory()->create(['organisation_id' => null]);
        $this->setUserContext($user);

        // Create demo election for mode 1
        $election = Election::create([
            'name' => 'Demo Election',
            'slug' => 'demo-election-' . now()->timestamp,
            'type' => 'demo',
            'organisation_id' => null,
        ]);

        // Create demo vote
        $vote = DemoVote::create([
            'election_id' => $election->id,
            'voting_code' => 'TEST-CODE-123',
            'ip_address' => '127.0.0.1',
        ]);

        // Assert: organisation_id is NULL (MODE 1)
        $this->assertNull($vote->organisation_id);
        $this->assertEquals($election->id, $vote->election_id);
    }

    /**
     * Test MODE 2: Demo vote gets organisation_id when user has org
     *
     * User with organisation_id = 1 should create demo votes with organisation_id = 1
     * This is the key requirement: org-scoped demo testing
     */
    public function test_mode2_demo_vote_gets_organisation_id_when_user_has_org()
    {
        // MODE 2: User with organisation_id = 1
        $user = User::factory()->create(['organisation_id' => 1]);
        $this->setUserContext($user);

        // Create demo election for org 1
        $election = Election::create([
            'name' => 'Org 1 Demo Election',
            'slug' => 'org1-demo-election-' . now()->timestamp,
            'type' => 'demo',
            'organisation_id' => 1,
        ]);

        // Create demo vote
        $vote = DemoVote::create([
            'election_id' => $election->id,
            'voting_code' => 'ORG1-CODE-123',
            'ip_address' => '192.168.1.1',
        ]);

        // Assert: organisation_id = 1 (NOT NULL) ✅ KEY TEST
        $this->assertEquals(1, $vote->organisation_id);
        $this->assertEquals($election->id, $vote->election_id);
    }

    /**
     * Test MODE 2: Demo results also get organisation_id
     *
     * When a user votes in demo, the results should also be scoped to their org
     */
    public function test_mode2_demo_result_gets_organisation_id()
    {
        // MODE 2: User with organisation_id = 1
        $user = User::factory()->create(['organisation_id' => 1]);
        $this->setUserContext($user);

        // Create demo election
        $election = Election::create([
            'name' => 'Org 1 Demo',
            'slug' => 'org1-demo-' . now()->timestamp,
            'type' => 'demo',
            'organisation_id' => 1,
        ]);

        // Create demo vote
        $vote = DemoVote::create([
            'election_id' => $election->id,
            'voting_code' => 'DEMO-CODE-ORG1',
        ]);

        // Create demo result for this vote (with required post_id and candidacy_id)
        $result = DemoResult::create([
            'election_id' => $election->id,
            'vote_id' => $vote->id,
            'post_id' => 'post-1',
            'candidacy_id' => 'candidacy-1',
        ]);

        // Assert: demo_result.organisation_id = 1 (NOT NULL) ✅
        $this->assertEquals(1, $result->organisation_id);
        $this->assertEquals($vote->id, $result->vote_id);
    }

    /**
     * Test Isolation: Org 1 demo votes are isolated from Org 2
     *
     * Verify that users from different organisations cannot see each other's demo data
     */
    public function test_org1_demo_votes_are_isolated_from_org2()
    {
        // Create org 1 user and demo vote
        $user1 = User::factory()->create(['organisation_id' => 1]);
        $this->setUserContext($user1);

        $election1 = Election::create([
            'name' => 'Org 1 Demo',
            'slug' => 'org1-demo',
            'type' => 'demo',
            'organisation_id' => 1,
        ]);

        $vote1 = DemoVote::create([
            'election_id' => $election1->id,
            'voting_code' => 'ORG1-VOTE',
        ]);

        // Create org 2 user and demo vote
        $user2 = User::factory()->create(['organisation_id' => 2]);
        $this->setUserContext($user2);

        $election2 = Election::create([
            'name' => 'Org 2 Demo',
            'slug' => 'org2-demo',
            'type' => 'demo',
            'organisation_id' => 2,
        ]);

        $vote2 = DemoVote::create([
            'election_id' => $election2->id,
            'voting_code' => 'ORG2-VOTE',
        ]);

        // Assert: Org 1 can only see their demo vote
        $this->setUserContext($user1);
        $visibleVotes = DemoVote::all();
        $this->assertCount(1, $visibleVotes);
        $this->assertEquals('ORG1-VOTE', $visibleVotes->first()->voting_code);
        $this->assertEquals(1, $visibleVotes->first()->organisation_id);

        // Assert: Org 2 can only see their demo vote
        $this->setUserContext($user2);
        $visibleVotes = DemoVote::all();
        $this->assertCount(1, $visibleVotes);
        $this->assertEquals('ORG2-VOTE', $visibleVotes->first()->voting_code);
        $this->assertEquals(2, $visibleVotes->first()->organisation_id);
    }

    /**
     * Test: MODE 1 and MODE 2 are isolated
     *
     * Demo votes with organisation_id = NULL should not be visible to org users
     * and vice versa
     */
    public function test_mode1_and_mode2_demo_votes_are_isolated()
    {
        // MODE 1: Create demo vote with no organisation
        $user_null = User::factory()->create(['organisation_id' => null]);
        $this->setUserContext($user_null);

        $election_null = Election::create([
            'name' => 'Mode 1 Demo',
            'slug' => 'mode1-demo',
            'type' => 'demo',
            'organisation_id' => null,
        ]);

        $vote_null = DemoVote::create([
            'election_id' => $election_null->id,
            'voting_code' => 'MODE1-VOTE',
        ]);

        // MODE 2: Create demo vote for organisation 1
        $user1 = User::factory()->create(['organisation_id' => 1]);
        $this->setUserContext($user1);

        $election1 = Election::create([
            'name' => 'Mode 2 Demo',
            'slug' => 'mode2-demo',
            'type' => 'demo',
            'organisation_id' => 1,
        ]);

        $vote1 = DemoVote::create([
            'election_id' => $election1->id,
            'voting_code' => 'MODE2-VOTE',
        ]);

        // Assert: MODE 1 can only see their vote (NULL org)
        $this->setUserContext($user_null);
        $visibleVotes = DemoVote::all();
        $this->assertCount(1, $visibleVotes);
        $this->assertNull($visibleVotes->first()->organisation_id);
        $this->assertEquals('MODE1-VOTE', $visibleVotes->first()->voting_code);

        // Assert: MODE 2 can only see their vote (org = 1)
        $this->setUserContext($user1);
        $visibleVotes = DemoVote::all();
        $this->assertCount(1, $visibleVotes);
        $this->assertEquals(1, $visibleVotes->first()->organisation_id);
        $this->assertEquals('MODE2-VOTE', $visibleVotes->first()->voting_code);
    }

    /**
     * Test: Demo vote creation with explicit organisation_id
     *
     * When creating a demo vote with an explicit organisation_id,
     * it should respect that value (not override with session)
     */
    public function test_demo_vote_respects_explicit_organisation_id()
    {
        // User with org 1, but we're explicitly setting org 2
        $user = User::factory()->create(['organisation_id' => 1]);
        $this->setUserContext($user);

        $election = Election::create([
            'name' => 'Test Election',
            'slug' => 'test-election',
            'type' => 'demo',
            'organisation_id' => 1,
        ]);

        // Create vote with explicit organisation_id = 2
        $vote = DemoVote::create([
            'election_id' => $election->id,
            'voting_code' => 'EXPLICIT-ORG',
            'organisation_id' => 2,  // Explicitly set to 2
        ]);

        // Assert: organisation_id respects explicit value
        $this->assertEquals(2, $vote->organisation_id);
    }

    /**
     * Test: Complete demo voting workflow with organisation context
     *
     * Full scenario: User from org 1 votes in demo, then real election
     */
    public function test_complete_demo_to_real_workflow_with_organisation()
    {
        // User from org 1
        $user = User::factory()->create(['organisation_id' => 1, 'name' => 'John Org1']);
        $this->setUserContext($user);

        // STEP 1: Create and vote in demo election (org-scoped)
        $demoElection = Election::create([
            'name' => 'Demo for Org 1',
            'slug' => 'demo-org1-' . now()->timestamp,
            'type' => 'demo',
            'organisation_id' => 1,
        ]);

        $demoVote = DemoVote::create([
            'election_id' => $demoElection->id,
            'voting_code' => 'DEMO-ORG1-001',
        ]);

        // Assert: Demo vote is scoped to org 1
        $this->assertEquals(1, $demoVote->organisation_id);

        // Create a result for the demo vote
        $demoResult = DemoResult::create([
            'election_id' => $demoElection->id,
            'vote_id' => $demoVote->id,
            'post_id' => 'post-1',
            'candidacy_id' => 'candidacy-1',
        ]);

        // Assert: Demo result is also scoped to org 1
        $this->assertEquals(1, $demoResult->organisation_id);

        // STEP 2: Verify both demo vote and result are visible
        $allVotes = DemoVote::all();
        $allResults = DemoResult::all();
        $this->assertCount(1, $allVotes);
        $this->assertCount(1, $allResults);
        $this->assertTrue($allVotes->every(fn($v) => $v->organisation_id === 1));
        $this->assertTrue($allResults->every(fn($r) => $r->organisation_id === 1));
    }
}
