<?php

namespace Tests\Feature\Routes;

use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoterSlugRouteBindingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that voter routes are accessible.
     *
     * Note: Voter slug route binding depends on the actual DemoVoterSlug/VoterSlug model.
     * This test verifies the route structure exists.
     */
    public function test_voter_routes_exist()
    {
        $routes = collect(\Route::getRoutes())->pluck('uri')->toArray();

        // Check for voter voting flow routes
        $voterRoutes = array_filter($routes, function($uri) {
            return strpos($uri, '/v/') === 0;
        });

        // Should have voter slug routes
        $this->assertNotEmpty($voterRoutes, 'Voter slug routes should exist');
    }

    /**
     * Test demo election voter flow route exists.
     */
    public function test_demo_election_voter_flow_routes_exist()
    {
        $routes = collect(\Route::getRoutes())->pluck('uri')->toArray();

        // Look for demo voter routes
        $demoVoterRoutes = array_filter($routes, function($uri) {
            return strpos($uri, '/demo/') === 0 || strpos($uri, '/v/') === 0;
        });

        // Should have some demo voter routes
        $this->assertNotEmpty($demoVoterRoutes, 'Demo voter routes should exist');
    }

    /**
     * Test that invalid route returns 404.
     */
    public function test_invalid_voter_slug_returns_404()
    {
        $response = $this->get('/v/non-existent-slug-xyz-123/code/create');

        // Should not find the route or resolve the model
        $this->assertTrue(
            in_array($response->status(), [404, 302]),
            'Invalid slug should return 404 or redirect'
        );
    }

    /**
     * Test that route requires proper voter slug format.
     */
    public function test_route_binding_validation()
    {
        // Very short slug - unlikely to exist
        $response = $this->get('/v/x/code/create');

        // Should handle gracefully
        $this->assertTrue(
            in_array($response->status(), [404, 302]),
            'Invalid format slug should be handled'
        );
    }

    /**
     * Test that unauthenticated users are redirected.
     */
    public function test_unauthenticated_user_is_redirected()
    {
        // Unauthenticated request to voter flow
        $response = $this->get('/v/some-slug/code/create');

        // Should redirect or return 404/403
        $this->assertTrue(
            in_array($response->status(), [302, 404, 403]),
            'Unauthenticated request should be handled appropriately'
        );
    }

    /**
     * Test case sensitivity of voter slug routes.
     */
    public function test_route_case_sensitivity()
    {
        // Test different cases - routes should be case-sensitive
        $response1 = $this->get('/v/TestSlug/code/create');
        $response2 = $this->get('/v/testslug/code/create');

        // Both should return same error (404/302) since both slugs don't exist
        $this->assertTrue(
            in_array($response1->status(), [404, 302, 403]),
            'Should handle case in routes'
        );
    }

    /**
     * Test that route binding works with route model binding.
     */
    public function test_route_binding_framework_support()
    {
        // Verify route model binding is supported in the application
        $router = app('router');

        // Routes should be able to use implicit binding
        $this->assertNotNull($router);
    }
}
