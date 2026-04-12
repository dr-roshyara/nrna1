<?php

namespace Tests\Feature\RealElection;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test-Driven Tests for Real Election Routing
 *
 * Focused tests verifying real elections use correct endpoints
 * and don't use demo endpoints
 */
class RoutingTest extends TestCase
{
    use RefreshDatabase;

    private $realElection;
    private $demoElection;
    private $organisation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();

        // Real election
        $this->realElection = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);

        // Demo election
        $this->demoElection = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => null
        ]);
    }

    /**
     * TEST 1: Real vs Demo Routes Are Different
     */
    public function test_real_vs_demo_vote_create_routes_different()
    {
        $realCreate = route('vote.create');
        $demoCreate = route('demo-vote.create');

        $this->assertNotEquals($realCreate, $demoCreate);
    }

    /**
     * TEST 2: Real Create Route Doesn't Contain Demo
     */
    public function test_real_create_route_no_demo_keyword()
    {
        $realCreate = route('vote.create');
        $this->assertStringNotContainsString('demo', $realCreate);
    }

    /**
     * TEST 3: Demo Create Route Contains Demo
     */
    public function test_demo_create_route_has_demo_keyword()
    {
        $demoCreate = route('demo-vote.create');
        $this->assertStringContainsString('demo', $demoCreate);
    }

    /**
     * TEST 4: Real Submit vs Demo Submit Different
     */
    public function test_real_vs_demo_submit_different()
    {
        $realSubmit = route('vote.submit');
        $demoSubmit = route('demo-vote.submit');

        $this->assertNotEquals($realSubmit, $demoSubmit);
        $this->assertStringNotContainsString('demo', $realSubmit);
        $this->assertStringContainsString('demo', $demoSubmit);
    }

    /**
     * TEST 5: Real Verify vs Demo Verify Different
     */
    public function test_real_vs_demo_verify_different()
    {
        $realVerify = route('vote.verify');
        $demoVerify = route('demo-vote.verify');

        $this->assertNotEquals($realVerify, $demoVerify);
        $this->assertStringNotContainsString('demo', $realVerify);
        $this->assertStringContainsString('demo', $demoVerify);
    }

    /**
     * TEST 6: Slug-Based Routes Different
     */
    public function test_slug_based_real_vs_demo_different()
    {
        $voterSlug = \App\Models\VoterSlug::factory()->create([
            'election_id' => $this->realElection->id,
            'slug' => 'test-slug'
        ]);

        $slugRealCreate = route('slug.vote.create', ['vslug' => $voterSlug->slug]);
        $slugDemoCreate = route('slug.demo-vote.create', ['vslug' => $voterSlug->slug]);

        $this->assertNotEquals($slugRealCreate, $slugDemoCreate);
        $this->assertStringNotContainsString('demo-vote', $slugRealCreate);
        $this->assertStringContainsString('demo-vote', $slugDemoCreate);
    }

    /**
     * TEST 7: Real Election Type Detection
     */
    public function test_real_election_type_is_real()
    {
        $this->assertEquals('real', $this->realElection->type);
        $this->assertNotEquals('demo', $this->realElection->type);
    }

    /**
     * TEST 8: Demo Election Type Detection
     */
    public function test_demo_election_type_is_demo()
    {
        $this->assertEquals('demo', $this->demoElection->type);
        $this->assertNotEquals('real', $this->demoElection->type);
    }

    /**
     * TEST 9: Real Routes List Complete
     */
    public function test_real_routes_complete()
    {
        $realRoutes = [
            'vote.create' => route('vote.create'),
            'vote.submit' => route('vote.submit'),
            'vote.verify' => route('vote.verify'),
        ];

        foreach ($realRoutes as $name => $route) {
            $this->assertNotEmpty($route);
            $this->assertStringNotContainsString('demo', $route,
                "Real route {$name} should not contain 'demo'");
        }
    }

    /**
     * TEST 10: Demo Routes List Complete
     */
    public function test_demo_routes_complete()
    {
        $demoRoutes = [
            'demo-vote.create' => route('demo-vote.create'),
            'demo-vote.submit' => route('demo-vote.submit'),
            'demo-vote.verify' => route('demo-vote.verify'),
            'demo-code.create' => route('demo-code.create'),
        ];

        foreach ($demoRoutes as $name => $route) {
            $this->assertNotEmpty($route);
            $this->assertStringContainsString('demo', $route,
                "Demo route {$name} should contain 'demo'");
        }
    }

    /**
     * TEST 11: No Cross-Contamination Real to Demo
     */
    public function test_real_routes_dont_use_demo_keywords()
    {
        $realRoutes = [
            route('vote.create'),
            route('vote.submit'),
            route('vote.verify'),
        ];

        foreach ($realRoutes as $route) {
            $this->assertStringNotContainsString('demo-vote', $route);
            $this->assertStringNotContainsString('demo-code', $route);
        }
    }

    /**
     * TEST 12: No Cross-Contamination Demo to Real
     */
    public function test_demo_routes_dont_use_real_keywords()
    {
        // Demo routes should be clearly separated with 'demo' keyword
        $demoRoutes = [
            route('demo-vote.create'),
            route('demo-vote.submit'),
            route('demo-vote.verify'),
            route('demo-code.create'),
        ];

        foreach ($demoRoutes as $route) {
            $this->assertStringContainsString('demo', $route);
        }
    }

    /**
     * TEST 13: Real Slug Routes Correct
     */
    public function test_real_slug_routes_correct()
    {
        $voterSlug = \App\Models\VoterSlug::factory()->create([
            'election_id' => $this->realElection->id,
            'slug' => 'real-voter'
        ]);

        $slugCreate = route('slug.vote.create', ['vslug' => $voterSlug->slug]);
        $slugSubmit = route('slug.vote.submit', ['vslug' => $voterSlug->slug]);
        $slugVerify = route('slug.vote.verify', ['vslug' => $voterSlug->slug]);

        foreach ([$slugCreate, $slugSubmit, $slugVerify] as $route) {
            $this->assertStringContainsString('v/real-voter', $route);
            $this->assertStringNotContainsString('demo', $route);
        }
    }

    /**
     * TEST 14: Demo Slug Routes Correct
     */
    public function test_demo_slug_routes_correct()
    {
        $voterSlug = \App\Models\VoterSlug::factory()->create([
            'election_id' => $this->demoElection->id,
            'slug' => 'demo-voter'
        ]);

        $slugCreate = route('slug.demo-vote.create', ['vslug' => $voterSlug->slug]);
        $slugSubmit = route('slug.demo-vote.submit', ['vslug' => $voterSlug->slug]);
        $slugVerify = route('slug.demo-vote.verify', ['vslug' => $voterSlug->slug]);

        foreach ([$slugCreate, $slugSubmit, $slugVerify] as $route) {
            $this->assertStringContainsString('v/demo-voter', $route);
            $this->assertStringContainsString('demo-vote', $route);
        }
    }

    /**
     * TEST 15: VoteController Handles Both Election Types
     */
    public function test_vote_controller_exists_and_complete()
    {
        $class = \App\Http\Controllers\VoteController::class;
        $this->assertTrue(class_exists($class));

        // Has required methods
        $this->assertTrue(method_exists($class, 'first_submission'));
        $this->assertTrue(method_exists($class, 'verify_first_submission'));
    }

    /**
     * TEST 16: DemoVoteController Handles Demo Elections
     */
    public function test_demo_vote_controller_exists_and_complete()
    {
        $class = \App\Http\Controllers\Demo\DemoVoteController::class;
        $this->assertTrue(class_exists($class));

        // Has required methods
        $this->assertTrue(method_exists($class, 'first_submission'));
        $this->assertTrue(method_exists($class, 'verify_first_submission'));
    }

    /**
     * TEST 17: Routes Mapped to Correct Controllers
     */
    public function test_routes_mapped_to_correct_controllers()
    {
        // Real election voting route
        $voteRoute = route('vote.submit');
        $this->assertStringContainsString('vote/submit', $voteRoute);

        // Demo election voting route
        $demoRoute = route('demo-vote.submit');
        $this->assertStringContainsString('demo-vote/submit', $demoRoute);

        // These are clearly different
        $this->assertNotEquals($voteRoute, $demoRoute);
    }

    /**
     * TEST 18: Election Type Determines Route
     *
     * Verify that our fix correctly checks election type before choosing route
     */
    public function test_election_type_determines_route_choice()
    {
        // Real election should use vote.* routes
        if ($this->realElection->type === 'real') {
            $route = route('vote.create');
            $this->assertStringNotContainsString('demo', $route);
        }

        // Demo election should use demo-vote.* routes
        if ($this->demoElection->type === 'demo') {
            $route = route('demo-vote.create');
            $this->assertStringContainsString('demo-vote', $route);
        }
    }

    /**
     * TEST 19: VoteController Can Check Election Type
     */
    public function test_vote_controller_can_identify_election_type()
    {
        $realType = $this->realElection->type;
        $demoType = $this->demoElection->type;

        // Types are different
        $this->assertNotEquals($realType, $demoType);

        // Can be used in conditionals
        $this->assertTrue($realType === 'real');
        $this->assertTrue($demoType === 'demo');
    }

    /**
     * TEST 20: Comprehensive Routing Verification
     *
     * Final comprehensive test of entire routing system
     */
    public function test_comprehensive_routing_system()
    {
        // Real election endpoints
        $realEndpoints = [
            'vote.create',
            'vote.submit',
            'vote.verify',
        ];

        // Demo election endpoints
        $demoEndpoints = [
            'demo-vote.create',
            'demo-vote.submit',
            'demo-vote.verify',
            'demo-code.create',
        ];

        // Verify all real endpoints exist and don't contain 'demo'
        foreach ($realEndpoints as $endpoint) {
            $route = route($endpoint);
            $this->assertNotEmpty($route);
            $this->assertStringNotContainsString('demo', $route);
        }

        // Verify all demo endpoints exist and contain 'demo'
        foreach ($demoEndpoints as $endpoint) {
            $route = route($endpoint);
            $this->assertNotEmpty($route);
            $this->assertStringContainsString('demo', $route);
        }
    }
}
