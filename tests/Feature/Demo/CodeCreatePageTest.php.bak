<?php

namespace Tests\Feature\Demo;

use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * CodeCreatePageTest
 *
 * Tests the demo code creation page at /v/{slug}/demo-code/create
 * Reproduces the 404 error the user is experiencing
 */
class CodeCreatePageTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $election;
    private $voterSlug;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user (with platform organisation_id = 0 and verified email)
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'organisation_id' => 0,
            'email_verified_at' => now(),
        ]);

        // Set session organisation to platform (0) for demo elections
        session(['current_organisation_id' => 0]);

        // Create demo election (platform-wide with organisation_id = 0)
        $this->election = Election::create([
            'name' => 'Test Demo Election',
            'slug' => 'test-demo-' . time(),
            'type' => 'demo',
            'organisation_id' => 0, // Platform-wide election
            'is_active' => true,
        ]);

        // Create voter slug (platform-wide user with organisation_id = 0)
        $this->voterSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'organisation_id' => 0, // Platform-wide user
            'election_id' => $this->election->id,
            'slug' => 'tb6kcb_lbzaJRxJEEyzwe_bJXyefdUeuKmY',
            'expires_at' => now()->addHour(),
            'current_step' => 1,
            'is_active' => true,
        ]);
    }

    /**
     * TEST 1: User can access demo code create page
     *
     * GET /v/{slug}/demo-code/create should return 200
     */
    public function test_user_can_access_demo_code_create_page()
    {
        $this->actingAs($this->user);

        // DEBUG: Verify voter slug exists and has correct data
        $verifySlug = VoterSlug::where('slug', $this->voterSlug->slug)->first();
        $this->assertNotNull($verifySlug, 'Voter slug should exist in database');
        $this->assertEquals($this->election->id, $verifySlug->election_id, 'Voter slug should have correct election_id');

        $response = $this->get("/v/{$this->voterSlug->slug}/demo-code/create");

        // If it's a 302 redirect, show where it redirects to
        if ($response->getStatusCode() === 302) {
            $redirectUrl = $response->headers->get('Location');
            $this->fail("Got 302 redirect to: {$redirectUrl}. Expected 200 for demo code create page.");
        }

        $this->assertEquals(200, $response->getStatusCode(),
            'Demo code create page should return 200, but got ' . $response->getStatusCode());

        $response->assertInertia(fn ($page) => $page
            ->component('Code/DemoCode/Create')
        );
    }

    /**
     * TEST 2: Missing voter slug returns 404
     *
     * If the voter slug doesn't exist, it should return 404
     */
    public function test_invalid_voter_slug_returns_404()
    {
        $this->actingAs($this->user);

        $response = $this->get("/v/invalid_slug_that_does_not_exist/demo-code/create");

        $this->assertEquals(404, $response->getStatusCode(),
            'Invalid voter slug should return 404');
    }

    /**
     * TEST 3: Code is generated and sent on page access
     *
     * When accessing the create page, a demo code should be generated and sent
     */
    public function test_code_is_generated_on_page_access()
    {
        $this->actingAs($this->user);

        // Access the create page
        $response = $this->get("/v/{$this->voterSlug->slug}/demo-code/create");

        $this->assertEquals(200, $response->getStatusCode());

        // Verify response contains code info
        $response->assertInertia(fn ($page) => $page
            ->has('code')  // Code should be passed to component
        );
    }
}
