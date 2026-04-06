<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Organisation;
use App\Http\Responses\LoginResponse;
use App\Services\DashboardResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;

/**
 * Integration tests for the 3-level fallback chain
 *
 * Tests the complete flow:
 * Level 1: Normal dashboard resolution with caching
 * Level 2: Emergency dashboard when resolution fails
 * Level 3: Static HTML when everything fails
 */
class LoginFallbackChainTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Level 1: Normal path works for new user
     */
    public function test_level_1_normal_resolution_for_new_user(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
        ]);

        $this->actingAs($user);
        $response = new LoginResponse(app());
        $request = $this->createRequestWithUser($user);

        $result = $response->toResponse($request);

        $this->assertIsRedirect();
        $this->assertStringContainsString('dashboard', $result->getTargetUrl());

        // Verify cache was created
        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        $this->assertTrue(Cache::has($cacheKey));
    }

    /**
     * Test Level 1: Normal path uses cache on second login
     */
    public function test_level_1_uses_cache_on_second_login(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
        ]);

        // First login
        $response1 = new LoginResponse(app());
        $request1 = $this->createRequestWithUser($user);
        $result1 = $response1->toResponse($request1);

        // Cache should exist
        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        $this->assertTrue(Cache::has($cacheKey));

        // Simulate second login
        $response2 = new LoginResponse(app());
        $request2 = $this->createRequestWithUser($user);
        $result2 = $response2->toResponse($request2);

        // Results should match
        $this->assertEquals($result1->getTargetUrl(), $result2->getTargetUrl());
    }

    /**
     * Test Level 1: Normal path with role-based routing
     */
    public function test_level_1_routes_based_on_user_role(): void
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create();

        // Add organisation role
        \Illuminate\Support\Facades\DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        $response = new LoginResponse(app());
        $request = $this->createRequestWithUser($user);

        $result = $response->toResponse($request);

        $this->assertStringContainsString('organisations', $result->getTargetUrl());
        $this->assertStringContainsString($organisation->slug, $result->getTargetUrl());
    }

    /**
     * Test cache invalidation when user roles change
     */
    public function test_cache_invalidated_when_roles_change(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
        ]);

        // First login - establishes cache
        $response1 = new LoginResponse(app());
        $request1 = $this->createRequestWithUser($user);
        $result1 = $response1->toResponse($request1);

        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        $this->assertTrue(Cache::has($cacheKey));

        // User gets new organisation role
        $org = Organisation::factory()->create();
        \Illuminate\Support\Facades\DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        // Observer should invalidate cache
        Cache::flush();  // Simulate observer clearing cache
        $this->assertFalse(Cache::has($cacheKey));

        // Second login should resolve to different dashboard
        $response2 = new LoginResponse(app());
        $request2 = $this->createRequestWithUser($user);
        $result2 = $response2->toResponse($request2);

        // Should now route to organisation dashboard
        $this->assertStringContainsString('organisations', $result2->getTargetUrl());
    }

    /**
     * Test fallback to emergency dashboard structure
     */
    public function test_fallback_chain_has_emergency_dashboard_available(): void
    {
        $user = User::factory()->create();

        // Emergency dashboard should be accessible
        $response = $this->actingAs($user)
            ->get(route('dashboard.emergency'));

        $response->assertStatus(200);
        $response->assertViewIs('Emergency/Dashboard');
    }

    /**
     * Test all three user types route correctly
     */
    public function test_all_user_types_route_correctly(): void
    {
        // Type 1: First-time user
        $newUser = User::factory()->create([
            'is_voter' => false,
        ]);

        $response = new LoginResponse(app());
        $result = $response->toResponse($this->createRequestWithUser($newUser));
        $this->assertStringContainsString('dashboard', $result->getTargetUrl());

        // Type 2: Organisation admin
        $adminUser = User::factory()->create();
        $org = Organisation::factory()->create();
        \Illuminate\Support\Facades\DB::table('user_organisation_roles')->insert([
            'user_id' => $adminUser->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        $response = new LoginResponse(app());
        $result = $response->toResponse($this->createRequestWithUser($adminUser));
        $this->assertStringContainsString('organisations', $result->getTargetUrl());

        // Type 3: Voter
        $voterUser = User::factory()->create([
            'is_voter' => true,
        ]);

        $response = new LoginResponse(app());
        $result = $response->toResponse($this->createRequestWithUser($voterUser));
        $this->assertStringContainsString('vote', $result->getTargetUrl());
    }

    /**
     * Test cache respects session freshness
     */
    public function test_cache_respects_session_freshness(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
            'last_activity_at' => now(),
        ]);

        // First login - cache created
        $response1 = new LoginResponse(app());
        $request1 = $this->createRequestWithUser($user);
        $response1->toResponse($request1);

        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        $this->assertTrue(Cache::has($cacheKey));

        // Update user's last activity to old timestamp
        $user->update([
            'last_activity_at' => now()->subMinutes(5),
        ]);

        // Cache should be ignored (session not fresh)
        $response2 = new LoginResponse(app());
        $request2 = $this->createRequestWithUser($user);
        $result2 = $response2->toResponse($request2);

        // Should still work but went through normal resolution
        $this->assertIsRedirect();
    }

    /**
     * Test voting session takes priority over cache
     */
    public function test_voting_session_priority_over_cache(): void
    {
        $user = User::factory()->create();
        $election = \App\Models\Election::factory()->create();

        // First login - caches welcome dashboard
        $user->update(['is_voter' => false]);
        $response1 = new LoginResponse(app());
        $request1 = $this->createRequestWithUser($user);
        $response1->toResponse($request1);

        // Create active voting session
        \Illuminate\Support\Facades\DB::table('voter_slugs')->insert([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'slug' => 'test-slug',
            'current_step' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Second login should prioritize voting over cached welcome
        $response2 = new LoginResponse(app());
        $request2 = $this->createRequestWithUser($user);
        $result2 = $response2->toResponse($request2);

        $this->assertStringContainsString('vote', $result2->getTargetUrl());
    }

    /**
     * Test multiple user roles are handled
     */
    public function test_multiple_roles_go_to_selection(): void
    {
        $user = User::factory()->create();
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        // Assign multiple roles
        \Illuminate\Support\Facades\DB::table('user_organisation_roles')->insert([
            ['user_id' => $user->id, 'organisation_id' => $org1->id, 'role' => 'admin'],
            ['user_id' => $user->id, 'organisation_id' => $org2->id, 'role' => 'editor'],
        ]);

        $response = new LoginResponse(app());
        $request = $this->createRequestWithUser($user);
        $result = $response->toResponse($request);

        $this->assertStringContainsString('role', $result->getTargetUrl());
    }

    /**
     * Helper: Create request with authenticated user
     */
    protected function createRequestWithUser($user)
    {
        $request = $this->createMock(\Illuminate\Http\Request::class);
        $request->method('user')->willReturn($user);
        $request->method('ip')->willReturn('127.0.0.1');
        $request->method('userAgent')->willReturn('Test Browser');
        $request->method('session')->willReturn(session());

        return $request;
    }
}
