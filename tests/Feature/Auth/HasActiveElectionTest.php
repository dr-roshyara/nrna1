<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Organisation;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Tests for hasActiveElection() logic
 *
 * Verifies that users can check if they have active elections to vote in
 */
class HasActiveElectionTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $tenantOrg;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenantOrg = Organisation::factory()->create(['type' => 'tenant']);
    }

    /**
     * Test hasActiveElection returns true when active election exists
     */
    public function test_has_active_election_returns_true_when_active_election_exists(): void
    {
        $user = User::factory()->create();

        // Add user to tenant org
        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->tenantOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create active election directly
        $electionId = (string) \Illuminate\Support\Str::uuid();
        DB::table('elections')->insert([
            'id' => $electionId,
            'organisation_id' => $this->tenantOrg->id,
            'name' => 'Test Election',
            'slug' => 'test-election',
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue($user->hasActiveElection());
        $this->assertNotNull($user->getActiveElection());
        $this->assertEquals($electionId, $user->getActiveElection()->id);
        $this->assertEquals(1, $user->countActiveElections());
    }

    /**
     * Test hasActiveElection returns false when no active election
     */
    public function test_has_active_election_returns_false_when_no_active_election(): void
    {
        $user = User::factory()->create();

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->tenantOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create inactive election (future date)
        DB::table('elections')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'organisation_id' => $this->tenantOrg->id,
            'name' => 'Future Election',
            'slug' => 'future-election',
            'status' => 'active',
            'start_date' => now()->addMonth(),
            'end_date' => now()->addMonths(2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse($user->hasActiveElection());
        $this->assertNull($user->getActiveElection());
        $this->assertEquals(0, $user->countActiveElections());
    }

    /**
     * Test hasActiveElection returns false when already voted
     */
    public function test_has_active_election_returns_false_when_already_voted(): void
    {
        $user = User::factory()->create();

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->tenantOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $electionId = (string) \Illuminate\Support\Str::uuid();
        DB::table('elections')->insert([
            'id' => $electionId,
            'organisation_id' => $this->tenantOrg->id,
            'name' => 'Test Election',
            'slug' => 'test-election-voted',
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // User already voted - create voter_slug with status = 'voted'
        DB::table('voter_slugs')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'election_id' => $electionId,
            'organisation_id' => $this->tenantOrg->id,
            'slug' => 'test-slug',
            'status' => 'voted',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse($user->hasActiveElection());
        $this->assertNull($user->getActiveElection());
    }

    /**
     * Test hasActiveElection returns false without tenant org
     */
    public function test_has_active_election_returns_false_without_tenant_org(): void
    {
        $user = User::factory()->create();
        $platformOrg = Organisation::firstOrCreate(
            ['slug' => 'publicdigit'],
            [
                'name' => 'Public Digit',
                'type' => 'platform',
                'is_default' => true,
            ]
        );

        // User only in platform org
        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $platformOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse($user->hasActiveElection());
        $this->assertNull($user->getActiveElection());
    }

    /**
     * Test countActiveElections returns correct number
     */
    public function test_count_active_elections_returns_correct_number(): void
    {
        $user = User::factory()->create();

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->tenantOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create 2 active elections
        DB::table('elections')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'organisation_id' => $this->tenantOrg->id,
            'name' => 'Election 1',
            'slug' => 'election-1',
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('elections')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'organisation_id' => $this->tenantOrg->id,
            'name' => 'Election 2',
            'slug' => 'election-2',
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertEquals(2, $user->countActiveElections());
    }

    /**
     * Test getActiveElection ignores elections outside date range
     */
    public function test_get_active_election_ignores_elections_outside_date_range(): void
    {
        $user = User::factory()->create();

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->tenantOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create election in the future
        DB::table('elections')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'organisation_id' => $this->tenantOrg->id,
            'name' => 'Future Election',
            'slug' => 'future-election',
            'status' => 'active',
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse($user->hasActiveElection());
        $this->assertNull($user->getActiveElection());
    }

    /**
     * Test getActiveElection returns first election ordered by start_date
     */
    public function test_get_active_election_returns_first_ordered_by_start_date(): void
    {
        $user = User::factory()->create();

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->tenantOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create first election
        $firstElectionId = (string) \Illuminate\Support\Str::uuid();
        DB::table('elections')->insert([
            'id' => $firstElectionId,
            'organisation_id' => $this->tenantOrg->id,
            'name' => 'Election 1',
            'slug' => 'election-1',
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create second election
        DB::table('elections')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'organisation_id' => $this->tenantOrg->id,
            'name' => 'Election 2',
            'slug' => 'election-2',
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays(3),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Should return first election
        $activeElection = $user->getActiveElection();
        $this->assertEquals($firstElectionId, $activeElection->id);
    }

    /**
     * Test hasActiveElection with multiple organisations
     */
    public function test_has_active_election_checks_all_user_organisations(): void
    {
        $user = User::factory()->create();
        $org1 = Organisation::factory()->create(['type' => 'tenant']);
        $org2 = Organisation::factory()->create(['type' => 'tenant']);

        // Add user to both orgs
        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $org1->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $org2->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create election only in org2
        $electionId = (string) \Illuminate\Support\Str::uuid();
        DB::table('elections')->insert([
            'id' => $electionId,
            'organisation_id' => $org2->id,
            'name' => 'Test Election',
            'slug' => 'test-election',
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // User should find the election
        $this->assertTrue($user->hasActiveElection());
        $this->assertEquals($electionId, $user->getActiveElection()->id);
        $this->assertEquals(1, $user->countActiveElections());
    }
}
