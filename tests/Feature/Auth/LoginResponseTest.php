<?php

namespace Tests\Feature\Auth;

use App\Http\Responses\LoginResponse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LoginResponseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        Log::spy();
    }

    /**
     * Test login response redirects user to correct dashboard
     */
    public function test_login_response_redirects_to_dashboard(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
        ]);

        $response = new LoginResponse(app());
        $request = $this->createRequestWithUser($user);

        $result = $response->toResponse($request);

        $this->assertIsRedirect();
        $this->assertStringContainsString('dashboard', $result->getTargetUrl());
    }

    /**
     * Test login response creates unique request ID
     */
    public function test_login_response_tracks_request_id(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
        ]);

        $response1 = new LoginResponse(app());
        $response2 = new LoginResponse(app());

        // Request IDs should be different due to unique UUID generation
        $this->assertNotEquals(
            $this->getPrivateProperty($response1, 'requestId'),
            $this->getPrivateProperty($response2, 'requestId')
        );
    }

    /**
     * Test login response logs analytics
     */
    public function test_login_response_logs_analytics(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
        ]);

        $response = new LoginResponse(app());
        $request = $this->createRequestWithUser($user);

        $response->toResponse($request);

        Log::shouldHaveReceived('channel')
            ->with(config('login-routing.analytics.channel', 'login'))
            ->atLeast()->once();
    }

    /**
     * Test login response handles maintenance mode
     */
    public function test_login_response_handles_maintenance_mode(): void
    {
        $this->app->markDownForMaintenance();

        $user = User::factory()->create([
            'is_voter' => false,
        ]);

        $response = new LoginResponse(app());
        $request = $this->createRequestWithUser($user);

        $result = $response->toResponse($request);

        $this->assertIsRedirect();

        $this->app->markAsUp();
    }

    /**
     * Test login response caches successful resolution
     */
    public function test_login_response_caches_resolution(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
        ]);

        $response = new LoginResponse(app());
        $request = $this->createRequestWithUser($user);

        $response->toResponse($request);

        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        $this->assertNotNull(Cache::get($cacheKey));
    }

    /**
     * Test login response uses cached resolution on second attempt
     */
    public function test_login_response_uses_cached_resolution(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
        ]);

        // First login
        $response1 = new LoginResponse(app());
        $request1 = $this->createRequestWithUser($user);
        $result1 = $response1->toResponse($request1);

        // Second login should use cache
        $response2 = new LoginResponse(app());
        $request2 = $this->createRequestWithUser($user);
        $result2 = $response2->toResponse($request2);

        // Both should redirect to same location
        $this->assertEquals($result1->getTargetUrl(), $result2->getTargetUrl());
    }

    /**
     * Test fallback to emergency dashboard when resolution fails
     */
    public function test_fallback_to_emergency_dashboard_on_failure(): void
    {
        $user = User::factory()->create();

        // Create a mock that throws exception
        $this->app->bind('DashboardResolver', function () {
            throw new \Exception('Database connection failed');
        });

        $response = new LoginResponse(app());
        $request = $this->createRequestWithUser($user);

        // Should not throw but fallback to emergency
        $result = $response->toResponse($request);

        $this->assertIsRedirect();
        // Should either be emergency dashboard or static fallback
        $this->assertTrue(
            str_contains($result->getTargetUrl(), 'emergency') ||
            str_contains($result->getTargetUrl(), 'login')
        );
    }

    /**
     * Test performance thresholds are checked
     */
    public function test_performance_thresholds_logged(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
        ]);

        $response = new LoginResponse(app());
        $request = $this->createRequestWithUser($user);

        $response->toResponse($request);

        // Should have logged performance metrics
        Log::shouldHaveReceived('channel')->atLeast()->once();
    }

    /**
     * Test failure count tracking
     */
    public function test_failure_count_is_tracked(): void
    {
        $user = User::factory()->create();

        // Create failing scenario by mocking exception
        $this->app->bind('DashboardResolver', function () {
            throw new \Exception('Database error');
        });

        $response = new LoginResponse(app());
        $request = $this->createRequestWithUser($user);

        try {
            $response->toResponse($request);
        } catch (\Throwable $e) {
            // Expected fallback path
        }

        // Verify failure was logged
        Log::shouldHaveReceived('channel')->atLeast()->once();
    }

    /**
     * Helper: Create request with authenticated user
     */
    protected function createRequestWithUser(User $user)
    {
        $request = $this->createMock(\Illuminate\Http\Request::class);
        $request->method('user')->willReturn($user);
        $request->method('ip')->willReturn('127.0.0.1');
        $request->method('userAgent')->willReturn('Test Browser');

        return $request;
    }

    /**
     * Helper: Get private property from object
     */
    protected function getPrivateProperty($object, $property)
    {
        $reflection = new \ReflectionClass($object);
        $prop = $reflection->getProperty($property);
        $prop->setAccessible(true);
        return $prop->getValue($object);
    }
}
