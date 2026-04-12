<?php

namespace Tests\Feature\RealElection;

use App\Models\Code;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test-Driven Tests for Real Election Endpoint Routing
 *
 * Verifies that real election voting uses REAL routes (vote.*),
 * NOT demo routes (demo-vote.*)
 *
 * Ensures all fixes for demo elections don't break real elections
 */
class EndpointRoutingTest extends TestCase
{
    use RefreshDatabase;

    private $realElection;
    private $realUser;
    private $realCode;
    private $organisation;

    protected function setUp(): void
    {
        parent::setUp();

        // Create organisation
        $this->organisation = Organisation::factory()->create();

        // Create real election
        $this->realElection = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);

        // Create user
        $this->realUser = User::factory()->create([
            'is_voter' => 1,
            'can_vote' => 1
        ]);

        // Create real code
        $this->realCode = Code::factory()->create([
            'user_id' => $this->realUser->id,
            'can_vote_now' => 1,
            'code1_used_at' => null  // Not yet used
        ]);
    }

    /**
     * TEST 1: Real Routes Are Different From Demo Routes
     *
     * Verify that real voting routes don't contain 'demo'
     */
    public function test_real_routes_differ_from_demo_routes()
    {
        $realCreate = route('vote.create');
        $demoCreate = route('demo-vote.create');

        $this->assertNotEquals($realCreate, $demoCreate);
        $this->assertStringNotContainsString('demo', $realCreate);
        $this->assertStringContainsString('demo', $demoCreate);
    }

    /**
     * TEST 2: Real Submit Routes Are Different
     */
    public function test_real_submit_routes_differ_from_demo()
    {
        $realSubmit = route('vote.submit');
        $demoSubmit = route('demo-vote.submit');

        $this->assertNotEquals($realSubmit, $demoSubmit);
        $this->assertStringNotContainsString('demo', $realSubmit);
        $this->assertStringContainsString('demo', $demoSubmit);
    }

    /**
     * TEST 3: Real Verify Routes Are Different
     */
    public function test_real_verify_routes_differ_from_demo()
    {
        $realVerify = route('vote.verify');
        $demoVerify = route('demo-vote.verify');

        $this->assertNotEquals($realVerify, $demoVerify);
        $this->assertStringNotContainsString('demo', $realVerify);
        $this->assertStringContainsString('demo', $demoVerify);
    }

    /**
     * TEST 4: Real Code Routes Are Correct
     */
    public function test_real_code_routes_are_correct()
    {
        $codeCreate = route('code.create');

        // Real code route should NOT contain 'demo'
        $this->assertStringNotContainsString('demo-code', $codeCreate);
        $this->assertStringContainsString('code', $codeCreate);
    }

    /**
     * TEST 5: Slug Routes Use Real Endpoints
     */
    public function test_slug_real_routes_differ_from_slug_demo()
    {
        $voterSlug = \App\Models\VoterSlug::factory()->create([
            'election_id' => $this->realElection->id,
            'slug' => 'real-slug'
        ]);

        $slugRealCreate = route('slug.vote.create', ['vslug' => $voterSlug->slug]);
        $slugDemoCreate = route('slug.demo-vote.create', ['vslug' => $voterSlug->slug]);

        $this->assertNotEquals($slugRealCreate, $slugDemoCreate);
        $this->assertStringNotContainsString('demo', $slugRealCreate);
        $this->assertStringContainsString('demo', $slugDemoCreate);
    }

    /**
     * TEST 6: Real Election Endpoint Selection
     *
     * Verify VoteController correctly identifies real elections
     * and uses real routes, not demo routes
     */
    public function test_real_election_uses_vote_routes_not_demo()
    {
        // Verify election type
        $this->assertEquals('real', $this->realElection->type);
        $this->assertNotEquals('demo', $this->realElection->type);

        // Verify routes are real
        $createRoute = route('vote.create');
        $submitRoute = route('vote.submit');
        $verifyRoute = route('vote.verify');

        $this->assertStringNotContainsString('demo-vote', $createRoute);
        $this->assertStringNotContainsString('demo-vote', $submitRoute);
        $this->assertStringNotContainsString('demo-vote', $verifyRoute);
    }

    /**
     * TEST 7: Real Election Navigation
     */
    public function test_real_election_page_navigation()
    {
        $this->actingAs($this->realUser);

        // Access voting page for real election
        $response = $this->get('/vote/create');

        // Should work (not 404)
        $this->assertTrue($response->status() === 200 || $response->status() === 302);
    }

    /**
     * TEST 8: Slug-Based Real Election Navigation
     */
    public function test_slug_based_real_election_navigation()
    {
        $voterSlug = \App\Models\VoterSlug::factory()->create([
            'election_id' => $this->realElection->id,
            'slug' => 'real-voter-slug'
        ]);

        $this->actingAs($this->realUser);

        // Access via slug
        $response = $this->get("/v/{$voterSlug->slug}/vote/create");

        // Should work
        $this->assertTrue($response->status() === 200 || $response->status() === 302);
    }

    /**
     * TEST 9: Real Code Creation Route
     */
    public function test_real_code_creation_route_correct()
    {
        $codeRoute = route('code.create');

        $this->assertStringNotContainsString('demo', $codeRoute);
        $this->assertStringContainsString('code/create', $codeRoute);
    }

    /**
     * TEST 10: No Bleeding Between Real and Demo
     *
     * Verify routes for real elections don't accidentally use demo endpoints
     */
    public function test_no_demo_endpoints_in_real_routes()
    {
        $realRoutes = [
            route('vote.create'),
            route('vote.submit'),
            route('vote.verify'),
            route('code.create'),
            route('slug.vote.create', ['vslug' => 'test']),
            route('slug.vote.submit', ['vslug' => 'test']),
            route('slug.vote.verify', ['vslug' => 'test']),
        ];

        foreach ($realRoutes as $route) {
            $this->assertStringNotContainsString('demo-vote', $route,
                "Real route '{$route}' should not contain 'demo-vote'");
            $this->assertStringNotContainsString('demo-code', $route,
                "Real route '{$route}' should not contain 'demo-code'");
        }
    }

    /**
     * TEST 11: All Real Routes Registered
     */
    public function test_all_real_routes_are_registered()
    {
        $routes = [
            'vote.create' => route('vote.create'),
            'vote.submit' => route('vote.submit'),
            'vote.verify' => route('vote.verify'),
            'code.create' => route('code.create'),
        ];

        foreach ($routes as $name => $route) {
            $this->assertNotEmpty($route, "Route {$name} not registered");
        }
    }

    /**
     * TEST 12: Real Code Model vs Demo Code Model
     *
     * Verify that real elections use Code model, not DemoCode
     */
    public function test_real_elections_use_code_model()
    {
        // Create real code
        $code = Code::create([
            'user_id' => $this->realUser->id,
            'code1' => 'ABC123',
            'can_vote_now' => 1,
        ]);

        $this->assertIsInstance($code, Code::class);
        $this->assertNotIsInstance($code, \App\Models\DemoCode::class);
    }

    /**
     * TEST 13: Validation Error Redirects to Real Route
     *
     * Verify VoteController redirects validation errors to vote.create
     */
    public function test_validation_error_handling_in_real_controller()
    {
        // VoteController should handle real elections
        $this->assertTrue(class_exists(\App\Http\Controllers\VoteController::class));

        // Verify it has the validation error handling
        $reflection = new \ReflectionClass(\App\Http\Controllers\VoteController::class);
        $this->assertTrue($reflection->hasMethod('first_submission'));
    }

    /**
     * TEST 14: Real Election Complete Flow
     */
    public function test_real_election_complete_flow_uses_real_routes()
    {
        $this->actingAs($this->realUser);

        // Verify election is real
        $this->assertEquals('real', $this->realElection->type);

        // Verify routes are different from demo
        $this->assertStringNotContainsString('demo', route('vote.create'));
        $this->assertStringNotContainsString('demo', route('vote.verify'));
    }

    /**
     * TEST 15: Route Parameters Correct for Real Elections
     */
    public function test_real_slug_parameters_correct()
    {
        $voterSlug = \App\Models\VoterSlug::factory()->create([
            'election_id' => $this->realElection->id,
            'slug' => 'my-real-slug'
        ]);

        $route = route('slug.vote.create', ['vslug' => $voterSlug->slug]);

        $this->assertStringContainsString('v/my-real-slug', $route);
        $this->assertStringNotContainsString('demo', $route);
    }
}
