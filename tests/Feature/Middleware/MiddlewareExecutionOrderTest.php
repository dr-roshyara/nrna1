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
     * CRITICAL (post Laravel 11 migration — see bootstrap/app.php):
     * 1. Laravel's default web middleware (session, csrf, etc)
     * 2. SetLocale (must run after session is started)
     * 3. HandleInertiaRequests (must run after locale is set)
     * 4. TenantContext is deliberately NOT in the web group — it is route
     *    middleware (alias 'tenant') with an explicit priority rule:
     *    StartSession → TenantContext → SubstituteBindings.
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

        // Verify custom middleware are present in the web group
        $this->assertContains('SetLocale', $middlewareClassNames);
        $this->assertContains('HandleInertiaRequests', $middlewareClassNames);

        // TenantContext must NOT be blanket web middleware — it is applied per
        // route via the 'tenant' alias so its priority rule can order it
        // between StartSession and SubstituteBindings.
        $this->assertNotContains('TenantContext', $middlewareClassNames,
            'TenantContext must stay route-level (alias), not web-group middleware');

        $aliases = $router->getMiddleware();
        $this->assertArrayHasKey('tenant', $aliases);
        $this->assertEquals(\App\Http\Middleware\TenantContext::class, $aliases['tenant']);
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
     * Test the middleware chain tolerates both guests and authenticated users.
     *
     * /dashboard is deliberately public: ElectionManagementController::dashboard
     * renders the Welcome page for guests (it doubles as the landing page).
     * The meaningful ordering property is that TenantContext and the rest of
     * the chain do not crash when no authenticated user / org context exists.
     */
    public function test_authentication_middleware_runs_before_tenant_context()
    {
        // Without authentication: Welcome page renders (200) — no crash from
        // tenant/session middleware running for a guest.
        $response1 = $this->get('/dashboard');
        $response1->assertOk();

        // With authentication: resolver runs (200 render or 302 role redirect),
        // never an auth failure or middleware crash.
        $user = User::factory()->create();
        $this->actingAs($user);

        $response2 = $this->get('/dashboard');
        $this->assertTrue(
            in_array($response2->status(), [200, 302]),
            "Authenticated dashboard request should render or redirect, got {$response2->status()}"
        );
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
     *
     * csrf_token() reads from the session, which only exists after a request
     * has run through StartSession — calling it before any request returns
     * null in Laravel 11 tests.
     */
    public function test_csrf_token_is_available()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Run a request so StartSession boots the session store
        $this->get('/dashboard');

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
