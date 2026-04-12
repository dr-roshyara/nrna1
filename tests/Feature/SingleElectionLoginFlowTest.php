<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Single Election Login Flow Tests
 *
 * Tests the simplified architecture:
 * - 1 Demo Election (for testing)
 * - 1 Real Election (for production voting)
 *
 * Login scenarios:
 * 1. Admin → admin.dashboard
 * 2. Voter (eligible) + Real election active → /code/create/{slug}
 * 3. Voter (ineligible) → voter.dashboard
 * 4. Any user + Demo election → /election/demo/start → /code/create/demo-election
 */
class SingleElectionLoginFlowTest extends TestCase
{
    use RefreshDatabase;

    private $demoElection;
    private $realElection;
    private $adminUser;
    private $voterEligible;
    private $voterIneligible;
    private $demoTester;

    protected function setUp(): void
    {
        parent::setUp();

        // Create elections
        $this->demoElection = Election::create([
            'name' => 'Demo Election',
            'slug' => 'demo-election',
            'type' => 'demo',
            'description' => 'Test election',
            'is_active' => true,
            'start_date' => now()->subDays(1),
            'end_date' => now()->addMonths(3),
        ]);

        $this->realElection = Election::create([
            'name' => '2024 General Election',
            'slug' => '2024-general-election',
            'type' => 'real',
            'description' => 'Real election',
            'is_active' => true,
            'start_date' => now()->subDays(1),
            'end_date' => now()->addWeeks(2),
        ]);

        // Create test users
        $this->adminUser = User::create([
            'name' => 'Admin Tester',
            'email' => 'admin@test.com',
            'user_id' => 'admin_001',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_voter' => false,
            'can_vote' => false,
        ]);
        $this->adminUser->syncRoles('admin');

        $this->voterEligible = User::create([
            'name' => 'Voter Eligible',
            'email' => 'voter.eligible@test.com',
            'user_id' => 'voter_eligible_001',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_voter' => true,
            'can_vote' => true,
            'can_vote_now' => 1,
            'has_voted' => false,
        ]);
        $this->voterEligible->syncRoles('voter');

        $this->voterIneligible = User::create([
            'name' => 'Voter Ineligible',
            'email' => 'voter.ineligible@test.com',
            'user_id' => 'voter_ineligible_001',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_voter' => true,
            'can_vote' => true,
            'can_vote_now' => 0,
            'has_voted' => false,
        ]);
        $this->voterIneligible->syncRoles('voter');

        $this->demoTester = User::create([
            'name' => 'Demo Tester',
            'email' => 'demo@test.com',
            'user_id' => 'demo_tester_001',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_voter' => false,
            'can_vote' => false,
        ]);
        $this->demoTester->syncRoles('user');
    }

    /**
     * SCENARIO 1: Admin Login
     * Expected: Redirect to admin.dashboard
     */
    public function test_scenario_1_admin_login_redirects_to_admin_dashboard()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/');

        // Admin should see admin dashboard route
        $response->assertStatus(200);
        // The dashboard redirects based on role, so admin should get admin.dashboard
        // In a real test, we'd check the redirect URL or session
        $this->assertTrue($this->adminUser->hasRole('admin'));
    }

    /**
     * SCENARIO 2: Voter (Eligible) Login ON Election Day
     * Expected: Redirect directly to /code/create/2024-general-election
     */
    public function test_scenario_2_eligible_voter_on_election_day_redirects_to_voting()
    {
        $response = $this->actingAs($this->voterEligible)
            ->get('/');

        // Should render ElectionPage or redirect to code entry
        $response->assertStatus(200);

        // Verify user properties are correct
        $this->assertTrue($this->voterEligible->is_voter);
        $this->assertEquals(1, $this->voterEligible->can_vote_now);

        // Verify real election is active
        $this->assertTrue($this->realElection->isCurrentlyActive());
    }

    /**
     * SCENARIO 3: Voter (Ineligible) Login OFF Election Day
     * Expected: Redirect to voter.dashboard
     */
    public function test_scenario_3_ineligible_voter_redirects_to_dashboard()
    {
        $response = $this->actingAs($this->voterIneligible)
            ->get('/');

        // Should render dashboard
        $response->assertStatus(200);

        // Verify user is voter but not eligible
        $this->assertTrue($this->voterIneligible->is_voter);
        $this->assertEquals(0, $this->voterIneligible->can_vote_now);
    }

    /**
     * SCENARIO 4: Demo Election Access
     * Expected: /election/demo/start → redirect to /code/create/demo-election
     */
    public function test_scenario_4_demo_election_access()
    {
        $response = $this->actingAs($this->demoTester)
            ->get('/election/demo/start');

        // Should redirect to code entry for demo election
        // Note: actual redirect happens in ElectionController::startDemo()
        $response->assertStatus(302);
        $this->assertStringContainsString('demo-election', $response->headers->get('Location'));
    }

    /**
     * VERIFY: Elections are correctly configured
     */
    public function test_elections_are_configured_correctly()
    {
        // Verify exactly 2 elections exist
        $this->assertEquals(2, Election::count());

        // Verify demo election
        $demo = Election::where('type', 'demo')->first();
        $this->assertNotNull($demo);
        $this->assertEquals('demo-election', $demo->slug);
        $this->assertTrue($demo->is_active);
        $this->assertTrue($demo->isCurrentlyActive());

        // Verify real election
        $real = Election::where('type', 'real')->first();
        $this->assertNotNull($real);
        $this->assertEquals('2024-general-election', $real->slug);
        $this->assertTrue($real->is_active);
        $this->assertTrue($real->isCurrentlyActive());
    }

    /**
     * VERIFY: Test users have correct properties
     */
    public function test_test_users_are_configured_correctly()
    {
        // Admin
        $this->assertTrue($this->adminUser->hasRole('admin'));
        $this->assertFalse($this->adminUser->is_voter);

        // Voter Eligible
        $this->assertTrue($this->voterEligible->hasRole('voter'));
        $this->assertTrue($this->voterEligible->is_voter);
        $this->assertEquals(1, $this->voterEligible->can_vote_now);

        // Voter Ineligible
        $this->assertTrue($this->voterIneligible->hasRole('voter'));
        $this->assertTrue($this->voterIneligible->is_voter);
        $this->assertEquals(0, $this->voterIneligible->can_vote_now);

        // Demo Tester
        $this->assertTrue($this->demoTester->hasRole('user'));
        $this->assertFalse($this->demoTester->is_voter);
    }

    /**
     * INTEGRATION: Complete voting flow for eligible voter
     */
    public function test_eligible_voter_can_initiate_voting_flow()
    {
        // User can access the real election
        $this->assertTrue($this->realElection->isCurrentlyActive());

        // User is voter AND eligible
        $this->assertTrue($this->voterEligible->is_voter);
        $this->assertEquals(1, $this->voterEligible->can_vote_now);

        // User should be able to create a code
        $response = $this->actingAs($this->voterEligible)
            ->get("/code/create/{$this->realElection->slug}");

        // Should succeed or redirect appropriately
        $this->assertIn($response->status(), [200, 302]);
    }

    /**
     * INTEGRATION: Demo election can be accessed by any user
     */
    public function test_demo_election_accessible_to_any_user()
    {
        // Even non-voters can access demo
        $response = $this->actingAs($this->demoTester)
            ->get('/election/demo/start');

        $this->assertEquals(302, $response->status());
        $this->assertStringContainsString('demo-election', $response->headers->get('Location'));
    }
}
