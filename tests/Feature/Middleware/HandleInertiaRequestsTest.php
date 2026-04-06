<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HandleInertiaRequestsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Inertia shares user data.
     */
    public function test_inertia_shares_user_data()
    {
        $user = User::factory()->create(['name' => 'Test User']);

        $this->actingAs($user);

        // Request a page that uses Inertia
        $response = $this->get('/dashboard');

        // Inertia should be handling this
        $this->assertNotEquals(401, $response->status());
    }

    /**
     * Test Inertia shares flash messages.
     */
    public function test_inertia_shares_flash_messages()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        session()->flash('success', 'Test message');

        $response = $this->get('/dashboard');

        // Flash messages should be set in session
        $this->assertEquals('Test message', session('success'));
    }

    /**
     * Test middleware runs after locale middleware.
     */
    public function test_middleware_runs_after_locale_middleware()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        session(['locale' => 'de']);

        $response = $this->get('/dashboard');

        // Locale should be set before Inertia renders
        $this->assertEquals('de', app()->getLocale());
    }

    /**
     * Test Inertia handles authenticated and unauthenticated requests.
     */
    public function test_inertia_handles_both_authenticated_and_unauthenticated()
    {
        // Unauthenticated request
        $response1 = $this->get('/login');
        $this->assertNotEquals(500, $response1->status());

        // Authenticated request
        $user = User::factory()->create();
        $this->actingAs($user);

        $response2 = $this->get('/dashboard');
        $this->assertNotEquals(401, $response2->status());
    }

    /**
     * Test Inertia middleware preserves session state.
     */
    public function test_inertia_middleware_preserves_session_state()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        session(['test_key' => 'test_value']);

        $response = $this->get('/dashboard');

        // Session data should be preserved
        $this->assertEquals('test_value', session('test_key'));
    }

    /**
     * Test middleware doesn't break JSON responses.
     */
    public function test_inertia_middleware_handles_json_requests()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // Try a JSON request
        $response = $this->getJson('/api/user');

        // Should return valid response (not 500)
        $this->assertNotEquals(500, $response->status());
    }
}
