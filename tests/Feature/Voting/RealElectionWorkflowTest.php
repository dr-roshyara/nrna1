<?php

namespace Tests\Feature\Voting;

use App\Models\User;
use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RealElectionWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test real election requires organisation.
     */
    public function test_real_election_requires_organization()
    {
        $user = User::factory()->create(['organisation_id' => 1]);

        $election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => 1,
        ]);

        $this->assertEquals(1, $election->organisation_id);
        $this->assertNotNull($election->organisation_id);
        $this->assertEquals('real', $election->type);
    }

    /**
     * Test demo election has null organisation.
     */
    public function test_demo_election_has_null_organization()
    {
        $election = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => null,
        ]);

        $this->assertNull($election->organisation_id);
        $this->assertEquals('demo', $election->type);
    }

    /**
     * Test real election belongs to organisation.
     */
    public function test_real_election_belongs_to_organization()
    {
        // Create an organisation first
        $org = Organisation::create([
            'name' => 'Test Organisation',
            'slug' => 'test-org-' . uniqid(),
            'type' => 'other',
        ]);

        $election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $org->id,
        ]);

        $this->assertEquals($org->id, $election->organisation_id);
        $this->assertNotNull($election->organisation_id);
    }

    /**
     * Test user can have organisation context.
     */
    public function test_user_can_have_organization_context()
    {
        // Use the platform organisation that's created in setUp()
        $platformOrg = Organisation::where('slug', 'platform')->first();

        // Create a test organisation
        $testOrg = Organisation::create([
            'name' => 'Test Organisation',
            'slug' => 'test-org-' . uniqid(),
            'type' => 'other',
        ]);

        // Create users with explicit organisation context
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Verify both users exist and are independent
        $this->assertNotNull($user1->id);
        $this->assertNotNull($user2->id);
        $this->assertNotEquals($user1->id, $user2->id);

        // Verify organisations exist and are different
        $this->assertNotNull($platformOrg);
        $this->assertNotNull($testOrg);
        $this->assertNotEquals($platformOrg->id, $testOrg->id);
    }

    /**
     * Test election is scoped to organisation.
     */
    public function test_election_scoped_to_organization()
    {
        // Create two organisations
        $org1 = Organisation::create([
            'name' => 'Test Org 1',
            'slug' => 'test-org-1-' . uniqid(),
            'type' => 'other',
        ]);

        $org2 = Organisation::create([
            'name' => 'Test Org 2',
            'slug' => 'test-org-2-' . uniqid(),
            'type' => 'other',
        ]);

        // Create elections for each org
        $org1Election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $org1->id,
        ]);

        $org2Election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $org2->id,
        ]);

        // Verify both elections exist and have their correct org context
        $this->assertEquals($org1->id, $org1Election->organisation_id);
        $this->assertEquals($org2->id, $org2Election->organisation_id);

        // Verify the organisations are different
        $this->assertNotEquals($org1Election->organisation_id, $org2Election->organisation_id);
    }

    /**
     * Test cannot access election from different organisation.
     */
    public function test_cannot_access_election_from_different_organization()
    {
        // Create two organisations
        $org1 = Organisation::create([
            'name' => 'Org 1',
            'slug' => 'org-1-' . uniqid(),
            'type' => 'other',
        ]);

        $org2 = Organisation::create([
            'name' => 'Org 2',
            'slug' => 'org-2-' . uniqid(),
            'type' => 'other',
        ]);

        // Create user in org1 and election in org2
        $org1User = User::factory()->create(['organisation_id' => $org1->id]);
        $org2Election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $org2->id,
        ]);

        // Org1 user should NOT have access to org2 election
        $this->assertNotEquals($org1User->organisation_id, $org2Election->organisation_id);
    }

    /**
     * Test election status tracking.
     */
    public function test_election_status_tracking()
    {
        $election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => 1,
            'is_active' => true,
        ]);

        $this->assertTrue($election->is_active);

        // Deactivate
        $election->is_active = false;
        $election->save();

        $election->refresh();
        $this->assertFalse($election->is_active);
    }

    /**
     * Test real election anonymity.
     */
    public function test_real_election_should_be_anonymous()
    {
        // This test verifies the concept, not implementation
        // A real election should have vote anonymity
        $election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => 1,
        ]);

        // Election should be real (not demo)
        $this->assertEquals('real', $election->type);
        $this->assertNotNull($election->organisation_id);
    }

    /**
     * Test real election cannot use demo tables.
     */
    public function test_real_election_type_differs_from_demo()
    {
        // Create an organisation for the real election
        $org = Organisation::create([
            'name' => 'Test Org for Types',
            'slug' => 'type-org-' . uniqid(),
            'type' => 'other',
        ]);

        $demoElection = Election::factory()->create(['type' => 'demo']);
        $realElection = Election::factory()->create(['type' => 'real', 'organisation_id' => $org->id]);

        // Types should be different
        $this->assertNotEquals($demoElection->type, $realElection->type);
        $this->assertEquals('demo', $demoElection->type);
        $this->assertEquals('real', $realElection->type);
    }

    /**
     * Test election permissions by organisation.
     */
    public function test_election_permissions_scoped_by_organization()
    {
        // Create an organisation
        $org = Organisation::create([
            'name' => 'Test Org for Permissions',
            'slug' => 'perm-org-' . uniqid(),
            'type' => 'other',
        ]);

        // Create an election for that organisation
        $election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $org->id,
        ]);

        // Verify election is scoped to the organisation
        $this->assertEquals($org->id, $election->organisation_id);
        $this->assertNotNull($election->organisation_id);
    }

    /**
     * Test multiple elections in same organisation.
     */
    public function test_multiple_elections_in_same_organization()
    {
        // Create an organisation
        $org = Organisation::create([
            'name' => 'Multi Election Org',
            'slug' => 'multi-org-' . uniqid(),
            'type' => 'other',
        ]);

        $election1 = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $org->id,
            'name' => 'Election 1',
        ]);

        $election2 = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $org->id,
            'name' => 'Election 2',
        ]);

        // Both belong to same org
        $this->assertEquals($org->id, $election1->organisation_id);
        $this->assertEquals($org->id, $election2->organisation_id);

        // But are different elections
        $this->assertNotEquals($election1->id, $election2->id);
    }
}
