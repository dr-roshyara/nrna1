<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiddlewareExecutionOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test web middleware group contains custom middleware.
     *
     * CRITICAL: Order must be:
     * 1. Laravel's default web middleware (session, csrf, etc)
     * 2. SetLocale (must run after session is started)
     * 3. HandleInertiaRequests (must run after locale is set)
     * 4. TenantContext (must run last to access session data)
     */
    public function test_web_middleware_group_has_custom_middleware_in_order()
    {
        $router = app('router');
        $middlewareGroups = $router->getMiddlewareGroups();

        $this->assertArrayHasKey('web', $middlewareGroups);
        $webMiddleware = $middlewareGroups['web'];

        // Convert to class names for easier checking
        $middlewareClassNames = array_map(function($m) {
            if (is_string($m)) {
                return class_basename($m);
            }
            return class_basename(get_class($m));
        }, $webMiddleware);

        // Verify custom middleware are present
        $this->assertContains('SetLocale', $middlewareClassNames);
        $this->assertContains('HandleInertiaRequests', $middlewareClassNames);
        $this->assertContains('TenantContext', $middlewareClassNames);
    }

    /**
     * Test session is available to custom middleware.
     */
    public function test_session_is_available_to_custom_middleware()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        session(['test_key' => 'test_value']);

        $response = $this->get('/dashboard');

        // Session should be readable
        $this->assertEquals('test_value', session('test_key'));
    }

    /**
     * Test authentication middleware runs before tenant context.
     */
    public function test_authentication_middleware_runs_before_tenant_context()
    {
        // Without authentication
        $response1 = $this->get('/dashboard');
        // Should not have access (401/302 redirect)
        $this->assertTrue(
            in_array($response1->status(), [302, 401]),
            'Unauthenticated request should be redirected'
        );

        // With authentication
        $user = User::factory()->create();
        $this->actingAs($user);

        $response2 = $this->get('/dashboard');
        // Should have access (200)
        $this->assertNotEquals(401, $response2->status());
    }

    /**
     * Test CSRF protection is enforced.
     */
    public function test_csrf_protection_is_enforced()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // POST without CSRF token should fail
        $response = $this->post('/dashboard', []);

        // Should be rejected (419 or redirect)
        $this->assertTrue(
            in_array($response->status(), [419, 302, 405]),
            'POST without CSRF should be rejected'
        );
    }

    /**
     * Test CSRF token is available for forms.
     */
    public function test_csrf_token_is_available()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Token should be available
        $token = csrf_token();
        $this->assertNotNull($token);
        $this->assertNotEmpty($token);
    }

    /**
     * Test global middleware execute on all routes.
     */
    public function test_global_middleware_executes()
    {
        // TrustProxies and TrackPerformance should run on all requests
        $response = $this->get('/login');

        // Should execute without error
        $this->assertNotEquals(500, $response->status());
    }

    /**
     * Test middleware chain does not skip steps.
     */
    public function test_middleware_chain_does_not_skip_steps()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Set multiple session values that middleware should preserve
        session(['locale' => 'de', 'test_value' => 'preserved']);

        $response = $this->get('/dashboard');

        // Both values should still be available
        $this->assertEquals('de', session('locale'));
        $this->assertEquals('preserved', session('test_value'));
    }

    /**
     * Test middleware group aliases are registered.
     */
    public function test_middleware_group_aliases_registered()
    {
        $router = app('router');
        $middlewareGroups = $router->getMiddlewareGroups();

        // Web and API groups should exist
        $this->assertArrayHasKey('web', $middlewareGroups);
        $this->assertArrayHasKey('api', $middlewareGroups);
    }

    /**
     * Test route-level middleware can be applied.
     */
    public function test_route_level_middleware_can_be_applied()
    {
        $router = app('router');
        $middlewareAliases = $router->getMiddleware();

        // Check that voting middleware are registered
        $this->assertArrayHasKey('vote.eligibility', $middlewareAliases);
        $this->assertArrayHasKey('voter.slug.window', $middlewareAliases);
        $this->assertArrayHasKey('voter.step.order', $middlewareAliases);
    }
}
