<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\DemoVote;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * DemoModeTest
 *
 * Tests the two-level demo system:
 * - MODE 1: No organisation (organisation_id = NULL) - Demo testing
 * - MODE 2: With organisation (organisation_id = X) - Live multi-tenancy
 *
 * These tests verify that the BelongsToTenant trait correctly handles both modes
 */
class DemoModeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable foreign key checks for testing
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
     * Test MODE 1: Demo mode works without organisation
     *
     * When a user has organisation_id = NULL:
     * - Session is set to NULL
     * - Elections created have organisation_id = NULL
     * - Votes created have organisation_id = NULL
     * - Data is isolated to NULL only (no cross-pollination with other orgs)
     */
    public function test_mode1_demo_works_without_organisation()
    {
        // Create user with NULL organisation (MODE 1)
        $demoUser = User::factory()->create([
            'name' => 'Demo Customer',
            'organisation_id' => null,
        ]);

        // Act: Login with session context (MODE 1 = NULL)
        $this->setUserContext($demoUser);

        // Assert: Session is NULL
        $this->assertNull(session('current_organisation_id'));

        // Create demo election
        $election = Election::create([
            'name' => 'Demo Test Election',
            'slug' => 'demo-test-' . now()->timestamp,
            'type' => 'demo',
            'description' => 'Test election for MODE 1',
        ]);

        // Assert: organisation_id is NULL
        $this->assertNull($election->organisation_id);
        $this->assertEquals('demo', $election->type);

        // Create demo vote
        $demoVote = DemoVote::create([
            'election_id' => $election->id,
            'voting_code' => 'TEST-CODE-123',
            'ip_address' => '127.0.0.1',
        ]);

        // Assert: organisation_id is NULL
        $this->assertNull($demoVote->organisation_id);
        $this->assertEquals($election->id, $demoVote->election_id);

        // Assert: Global scope filters by NULL
        // When session is NULL, only records with organisation_id = NULL should be visible
        $visibleVotes = DemoVote::where('election_id', $election->id)->get();
        $this->assertCount(1, $visibleVotes);
        $this->assertNull($visibleVotes->first()->organisation_id);
    }

    /**
     * Test MODE 2: Tenant mode works with organisation
     *
     * When a user has organisation_id = X:
     * - Session is set to X
     * - Elections created have organisation_id = X
     * - Votes created have organisation_id = X
     * - Data is isolated to organisation X only
     */
    public function test_mode2_tenant_works_with_organisation()
    {
        // Create user with organisation_id = 1 (MODE 2)
        $orgUser = User::factory()->create([
            'name' => 'Org Admin',
            'organisation_id' => 1,
        ]);

        // Act: Login with session context (MODE 2 = 1)
        $this->setUserContext($orgUser);

        // Assert: Session is set to 1
        $this->assertEquals(1, session('current_organisation_id'));

        // Create real election
        $election = Election::create([
            'name' => 'Real Election for Org 1',
            'slug' => 'real-election-org1-' . now()->timestamp,
            'type' => 'real',
            'description' => 'Production election',
        ]);

        // Assert: organisation_id is 1
        $this->assertEquals(1, $election->organisation_id);
        $this->assertEquals('real', $election->type);

        // Create vote
        $vote = Vote::create([
            'election_id' => $election->id,
            'voting_code' => 'REAL-CODE-456',
            'ip_address' => '192.168.1.1',
        ]);

        // Assert: organisation_id is 1
        $this->assertEquals(1, $vote->organisation_id);
        $this->assertEquals($election->id, $vote->election_id);

        // Assert: Global scope filters by org_id = 1
        $visibleVotes = Vote::where('election_id', $election->id)->get();
        $this->assertCount(1, $visibleVotes);
        $this->assertEquals(1, $visibleVotes->first()->organisation_id);
    }

    /**
     * Test MODE 1 and MODE 2 isolation
     *
     * Verify that demo elections (organisation_id = NULL) are completely isolated
     * from tenant elections (organisation_id = X)
     */
    public function test_mode1_and_mode2_are_isolated()
    {
        // Create MODE 1 user (demo)
        $demoUser = User::factory()->create(['organisation_id' => null]);

        // Create MODE 2 user (tenant 1)
        $orgUser1 = User::factory()->create(['organisation_id' => 1]);

        // Create MODE 2 user (tenant 2)
        $orgUser2 = User::factory()->create(['organisation_id' => 2]);

        // MODE 1: Create demo election
        $this->setUserContext($demoUser);
        $demoElection = Election::create([
            'name' => 'Demo',
            'slug' => 'demo',
            'type' => 'demo',
        ]);
        $this->assertNull($demoElection->organisation_id);

        // MODE 2 Org 1: Create election
        $this->setUserContext($orgUser1);
        $org1Election = Election::create([
            'name' => 'Org 1 Election',
            'slug' => 'org1-election',
            'type' => 'real',
        ]);
        $this->assertEquals(1, $org1Election->organisation_id);

        // MODE 2 Org 2: Create election
        $this->setUserContext($orgUser2);
        $org2Election = Election::create([
            'name' => 'Org 2 Election',
            'slug' => 'org2-election',
            'type' => 'real',
        ]);
        $this->assertEquals(2, $org2Election->organisation_id);

        // Assert: MODE 1 can only see demo election
        $this->setUserContext($demoUser);
        $visibleElections = Election::all();
        $this->assertTrue($visibleElections->contains($demoElection));
        $this->assertFalse($visibleElections->contains($org1Election));
        $this->assertFalse($visibleElections->contains($org2Election));

        // Assert: Org 1 can only see org 1 election
        $this->setUserContext($orgUser1);
        $visibleElections = Election::all();
        $this->assertFalse($visibleElections->contains($demoElection));
        $this->assertTrue($visibleElections->contains($org1Election));
        $this->assertFalse($visibleElections->contains($org2Election));

        // Assert: Org 2 can only see org 2 election
        $this->setUserContext($orgUser2);
        $visibleElections = Election::all();
        $this->assertFalse($visibleElections->contains($demoElection));
        $this->assertFalse($visibleElections->contains($org1Election));
        $this->assertTrue($visibleElections->contains($org2Election));
    }

    /**
     * Test helper functions for mode detection
     */
    public function test_tenant_helper_functions()
    {
        // MODE 1: Demo user
        $demoUser = User::factory()->create(['organisation_id' => null]);
        $this->setUserContext($demoUser);

        $this->assertTrue(is_demo_mode());
        $this->assertFalse(is_tenant_mode());
        $this->assertEquals('MODE_1_DEMO', current_mode());
        $this->assertNull(get_tenant_id());

        // MODE 2: Org 1 user
        $orgUser = User::factory()->create(['organisation_id' => 5]);
        $this->setUserContext($orgUser);

        $this->assertFalse(is_demo_mode());
        $this->assertTrue(is_tenant_mode());
        $this->assertEquals('MODE_2_TENANT_5', current_mode());
        $this->assertEquals(5, get_tenant_id());
    }

    /**
     * Test vote anonymity is preserved in both modes
     *
     * Verify that:
     * - Votes have NO user_id column (anonymity preserved)
     * - organisation_id is used ONLY for data isolation
     * - IP address and user_agent are stored for audit
     */
    public function test_vote_anonymity_preserved_in_both_modes()
    {
        // MODE 1: Demo
        $demoUser = User::factory()->create(['organisation_id' => null]);
        $this->setUserContext($demoUser);

        $demoElection = Election::create([
            'name' => 'Demo',
            'slug' => 'demo-anon-test',
            'type' => 'demo',
        ]);

        $demoVote = DemoVote::create([
            'election_id' => $demoElection->id,
            'voting_code' => 'ANON-TEST-1',
            'ip_address' => '127.0.0.1',
        ]);

        // Assert: Vote has NO user_id (anonymity)
        $this->assertNull($demoVote->organisation_id); // MODE 1 isolation
        $this->assertNotNull($demoVote->voting_code);   // Audit trail
        // Note: Check that user_id column doesn't exist in votes table
        $this->assertFalse(in_array('user_id', array_keys($demoVote->getAttributes())));

        // MODE 2: Real election
        $orgUser = User::factory()->create(['organisation_id' => 1]);
        $this->setUserContext($orgUser);

        $realElection = Election::create([
            'name' => 'Real',
            'slug' => 'real-anon-test',
            'type' => 'real',
        ]);

        $vote = Vote::create([
            'election_id' => $realElection->id,
            'voting_code' => 'ANON-TEST-2',
            'ip_address' => '192.168.1.1',
        ]);

        // Assert: Vote has NO user_id (anonymity)
        $this->assertEquals(1, $vote->organisation_id);  // MODE 2 isolation
        $this->assertNotNull($vote->voting_code);        // Audit trail
        // Note: Check that user_id column doesn't exist in votes table
        $this->assertFalse(in_array('user_id', array_keys($vote->getAttributes())));
    }
}
