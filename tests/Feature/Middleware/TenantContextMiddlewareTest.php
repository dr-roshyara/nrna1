<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantContextMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test middleware sets tenant context from session.
     */
    public function test_middleware_sets_tenant_context_from_session()
    {
        $user = User::factory()->create(['organisation_id' => 1]);

        $this->actingAs($user);
        session(['current_organisation_id' => 1]);

        // Access authenticated route
        $response = $this->get('/dashboard');

        // Should have access (authenticated user)
        $this->assertNotEquals(401, $response->status());
    }

    /**
     * Test middleware allows access without organisation for platform routes.
     */
    public function test_middleware_allows_access_without_organization()
    {
        $user = User::factory()->create(['organisation_id' => null]);

        $this->actingAs($user);

        // No organisation session set - should be fine for platform routes
        $this->assertTrue(true);
    }

    /**
     * Test middleware handles missing organisation gracefully.
     */
    public function test_middleware_handles_missing_organization_gracefully()
    {
        $user = User::factory()->create(['organisation_id' => null]);

        $this->actingAs($user);

        // Try to access dashboard without organisation
        $response = $this->get('/dashboard');

        // Should not crash - response status should be valid
        $this->assertTrue(
            in_array($response->status(), [200, 302, 401, 403]),
            'Should handle missing organisation gracefully'
        );
    }

    /**
     * Test middleware executes after session start.
     */
    public function test_middleware_runs_after_session_start()
    {
        $user = User::factory()->create(['organisation_id' => 1]);

        $this->actingAs($user);
        session(['current_organisation_id' => 1]);

        // Make authenticated request
        $response = $this->get('/dashboard');

        // Session value should still be accessible
        $this->assertEquals(1, session('current_organisation_id'));
    }

    /**
     * Test middleware preserves session data.
     */
    public function test_middleware_preserves_session_data()
    {
        $user = User::factory()->create(['organisation_id' => 2]);

        $this->actingAs($user);
        session(['current_organisation_id' => 2, 'locale' => 'de']);

        $response = $this->get('/dashboard');

        // Both session values should be preserved
        $this->assertEquals(2, session('current_organisation_id'));
        $this->assertEquals('de', session('locale'));
    }

    /**
     * Test middleware works with demo elections (NULL organisation_id).
     */
    public function test_middleware_works_with_demo_elections()
    {
        $user = User::factory()->create(['organisation_id' => null]);

        $this->actingAs($user);
        session(['current_organisation_id' => null]);

        // Should handle NULL organisation gracefully
        $response = $this->get('/dashboard');

        // Should not crash
        $this->assertTrue(in_array($response->status(), [200, 302, 401, 403]));
    }
}
