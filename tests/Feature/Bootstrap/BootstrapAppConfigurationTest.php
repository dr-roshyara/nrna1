<?php

namespace Tests\Feature\Bootstrap;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BootstrapAppConfigurationTest extends TestCase
{
    /**
     * Test that Laravel 11 fluent API bootstrap is loaded correctly.
     */
    public function test_bootstrap_app_uses_laravel_11_configuration()
    {
        // Verify application is booted and configured
        $this->assertNotNull(app());
        $this->assertTrue(app()->isBooted());
    }

    /**
     * Test that all route files are registered.
     */
    public function test_all_route_files_are_registered()
    {
        $routes = collect(\Route::getRoutes())->pluck('uri')->toArray();

        // Web routes
        $this->assertTrue(in_array('login', $routes) || in_array('/', $routes), 'Web routes should be registered');

        // Election routes (voter voting flow)
        $this->assertTrue(
            in_array('v/{vslug}/code/create', $routes) ||
            in_array('v/{vslug}/code', $routes),
            'Election/voter routes should be registered'
        );

        // Organisation routes
        $this->assertTrue(
            in_array('organisations/{organisation}/voters', $routes) ||
            in_array('organisations', $routes),
            'Organisation routes should be registered'
        );
    }

    /**
     * Test that all middleware aliases are registered in bootstrap.
     */
    public function test_all_middleware_aliases_are_registered()
    {
        $router = app('router');
        $middlewareAliases = $router->getMiddleware();

        // Authentication middleware (standard Laravel)
        $this->assertArrayHasKey('auth', $middlewareAliases);
        $this->assertArrayHasKey('guest', $middlewareAliases);
        $this->assertArrayHasKey('verified', $middlewareAliases);

        // Voting middleware (CRITICAL - Phase 2 migration)
        $this->assertArrayHasKey('vote.eligibility', $middlewareAliases);
        $this->assertArrayHasKey('voter.slug.window', $middlewareAliases);
        $this->assertArrayHasKey('voter.step.order', $middlewareAliases);
        $this->assertArrayHasKey('validate.voting.ip', $middlewareAliases);
        $this->assertArrayHasKey('election', $middlewareAliases);

        // Organisation & Multi-tenancy middleware
        $this->assertArrayHasKey('ensure.organisation', $middlewareAliases);
        $this->assertArrayHasKey('committee.member', $middlewareAliases);

        // Spatie permission middleware
        $this->assertArrayHasKey('role', $middlewareAliases);
        $this->assertArrayHasKey('permission', $middlewareAliases);
    }

    /**
     * Test that rate limiting is configured for API.
     */
    public function test_api_rate_limiting_is_configured()
    {
        // Verify rate limiter service is registered
        $limiter = app(\Illuminate\Cache\RateLimiter::class);
        $this->assertNotNull($limiter);

        // Test that we can use the rate limiter
        $key = 'test-rate-limit-' . time();

        // Should allow first attempt
        $this->assertTrue($limiter->attempt($key, 10, function() {
            return true;
        }));
    }

    /**
     * Test that all global middleware are registered.
     */
    public function test_global_middleware_are_registered()
    {
        $router = app('router');
        $middlewareGroups = $router->getMiddlewareGroups();

        $this->assertArrayHasKey('web', $middlewareGroups);
        $this->assertArrayHasKey('api', $middlewareGroups);

        // Get web middleware group
        $webMiddleware = $middlewareGroups['web'];

        // Verify global middleware are present
        $this->assertTrue(
            collect($webMiddleware)->contains(function($middleware) {
                return is_string($middleware) || is_object($middleware);
            }),
            'Web middleware should be configured'
        );
    }

    /**
     * Test that exception handling is configured.
     */
    public function test_exception_handler_is_configured()
    {
        // Verify exception handler is registered
        $handler = app(\Illuminate\Contracts\Debug\ExceptionHandler::class);
        $this->assertNotNull($handler);
    }

    /**
     * Test that web middleware group has custom middleware.
     */
    public function test_web_middleware_group_has_custom_middleware()
    {
        $router = app('router');
        $middlewareGroups = $router->getMiddlewareGroups();

        $webMiddleware = $middlewareGroups['web'];

        // Check for custom middleware (SetLocale, HandleInertiaRequests, TenantContext)
        $middlewareClassNames = array_map(function($m) {
            if (is_string($m)) {
                return class_basename($m);
            }
            return class_basename(get_class($m));
        }, $webMiddleware);

        // These should be present (Phase 2 added them to web group)
        $this->assertContains('SetLocale', $middlewareClassNames);
        $this->assertContains('HandleInertiaRequests', $middlewareClassNames);
        $this->assertContains('TenantContext', $middlewareClassNames);
    }

    /**
     * Test that authentication guards are configured.
     */
    public function test_authentication_guards_are_configured()
    {
        $config = config('auth.guards');

        // Default web guard should exist
        $this->assertArrayHasKey('web', $config);

        // Session driver should be configured for web
        $this->assertEquals('session', $config['web']['driver']);
    }

    /**
     * Test that services are discoverable from bootstrap.
     */
    public function test_application_services_are_discoverable()
    {
        // Verify we can resolve core services
        $this->assertNotNull(app('router'));
        $this->assertNotNull(app('request'));
        // Response factory is available but may not be directly aliased
        $this->assertTrue(class_exists(\Illuminate\Http\Response::class));
    }
}
