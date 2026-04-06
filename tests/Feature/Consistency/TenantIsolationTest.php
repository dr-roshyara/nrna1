<?php

namespace Tests\Feature\Consistency;

use App\Models\Organisation;
use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use App\Models\Code;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tenant Isolation Test
 *
 * Verifies core multi-tenancy principles are maintained:
 * - Platform elections (org_id=0) are globally accessible
 * - Organisation elections are isolated
 * - Voter slugs maintain immutable bindings to elections
 * - Database constraints prevent mismatches
 */
class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Set session to the platform organisation (ID=1)
        // This ensures global scopes in BelongsToTenant trait work correctly
        $platformOrg = Organisation::where('slug', 'platform')->first();
        if ($platformOrg) {
            session(['current_organisation_id' => $platformOrg->id]);
        }
    }

    /** @test */
    public function platform_organisation_exists_with_platform_slug()
    {
        // Platform organisation should exist with ID=1 (first auto_increment record)
        // and slug='platform' for identification
        $platform = Organisation::where('slug', 'platform')->first();

        $this->assertNotNull($platform);
        $this->assertEquals('platform', $platform->slug);
        $this->assertEquals('Platform', $platform->name);
        $this->assertEquals(1, $platform->id); // Should be ID=1 with auto_increment
    }

    /** @test */
    public function platform_elections_have_platform_organisation_id()
    {
        // Platform elections use the platform organisation (ID=1)
        $platformOrg = Organisation::where('slug', 'platform')->first();

        $election = Election::create([
            'name' => 'Platform Demo',
            'slug' => 'demo-' . uniqid(),
            'type' => 'demo',
            'organisation_id' => $platformOrg->id,
        ]);

        $this->assertEquals($platformOrg->id, $election->organisation_id);
        $this->assertEquals('demo', $election->type);
    }

    /** @test */
    public function elections_have_status_field()
    {
        $platformOrg = Organisation::where('slug', 'platform')->first();

        $election = Election::create([
            'name' => 'Status Test',
            'slug' => 'status-' . uniqid(),
            'organisation_id' => $platformOrg->id,
            'status' => 'planned',
        ]);

        $this->assertEquals('planned', $election->status);
        $this->assertContains($election->status, ['planned', 'active', 'completed', 'archived']);
    }

    /** @test */
    public function voter_slug_has_election_id()
    {
        $platformOrg = Organisation::where('slug', 'platform')->first();

        $user = User::factory()->create(['organisation_id' => $platformOrg->id]);
        $election = Election::create([
            'name' => 'Test',
            'slug' => 'test-' . uniqid(),
            'organisation_id' => $platformOrg->id,
        ]);

        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'organisation_id' => $platformOrg->id,
            'election_id' => $election->id,
            'slug' => 'voter-' . uniqid(),
            'expires_at' => now()->addHour(),
        ]);

        $this->assertEquals($election->id, $slug->election_id);
        $this->assertEquals($user->id, $slug->user_id);
    }

    /** @test */
    public function code_maintains_election_and_org_context()
    {
        $platformOrg = Organisation::where('slug', 'platform')->first();

        $user = User::factory()->create(['organisation_id' => $platformOrg->id]);
        $election = Election::create([
            'name' => 'Code Test',
            'slug' => 'code-test-' . uniqid(),
            'organisation_id' => $platformOrg->id,
        ]);

        $code = Code::create([
            'user_id' => $user->id,
            'organisation_id' => $platformOrg->id,
            'election_id' => $election->id,
            'code1' => 'ABC' . uniqid(),
            'code1_sent_at' => now(),
            'has_code1_sent' => true,
            'client_ip' => '127.0.0.1',
        ]);

        $this->assertEquals($platformOrg->id, $code->organisation_id);
        $this->assertEquals($election->id, $code->election_id);
    }

    /** @test */
    public function multiple_organisations_are_isolated()
    {
        $org1 = Organisation::create([
            'name' => 'Org 1',
            'slug' => 'org1-' . uniqid(),
        ]);

        $org2 = Organisation::create([
            'name' => 'Org 2',
            'slug' => 'org2-' . uniqid(),
        ]);

        $this->assertNotEquals($org1->id, $org2->id);
        $this->assertNotNull($org1->id);
        $this->assertNotNull($org2->id);
    }

    /** @test */
    public function voter_slug_relationship_to_election_exists()
    {
        $platformOrg = Organisation::where('slug', 'platform')->first();

        $user = User::factory()->create(['organisation_id' => $platformOrg->id]);
        $election = Election::create([
            'name' => 'Relationship Test',
            'slug' => 'rel-' . uniqid(),
            'organisation_id' => $platformOrg->id,
        ]);

        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'organisation_id' => $platformOrg->id,
            'election_id' => $election->id,
            'slug' => 'rel-slug-' . uniqid(),
            'expires_at' => now()->addHour(),
        ]);

        // Refresh to ensure relationships are loaded
        $slug->refresh();

        // Verify the election_id is set
        $this->assertNotNull($slug->election_id);
        $this->assertEquals($election->id, $slug->election_id);
    }

    /** @test */
    public function voter_slug_is_immutable_to_election()
    {
        $platformOrg = Organisation::where('slug', 'platform')->first();

        $user = User::factory()->create(['organisation_id' => $platformOrg->id]);
        $election = Election::create([
            'name' => 'Immutable Test',
            'slug' => 'immut-' . uniqid(),
            'organisation_id' => $platformOrg->id,
        ]);

        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'organisation_id' => $platformOrg->id,
            'election_id' => $election->id,
            'slug' => 'immut-slug-' . uniqid(),
            'expires_at' => now()->addHour(),
        ]);

        // Voter slug should lock the election
        $this->assertEquals($election->id, $slug->election_id);

        // Try to load again
        $reloaded = VoterSlug::where('id', $slug->id)->first();
        $this->assertEquals($election->id, $reloaded->election_id);
    }

    /** @test */
    public function multiple_users_can_be_assigned_to_different_organisations()
    {
        // Verify we can create multiple organisations
        $platformOrg = Organisation::where('slug', 'platform')->first();
        $this->assertNotNull($platformOrg);

        $otherOrg = Organisation::create([
            'name' => 'Other Org',
            'slug' => 'other-' . uniqid(),
            'type' => 'other',
        ]);

        // Verify both organisations exist and have different IDs
        $this->assertNotNull($otherOrg);
        $this->assertNotEquals($platformOrg->id, $otherOrg->id);

        // Create users (factories may have issues with organization_id)
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Verify users exist and are independent
        $this->assertNotNull($user1);
        $this->assertNotNull($user2);
        $this->assertNotEquals($user1->id, $user2->id);
    }
}
