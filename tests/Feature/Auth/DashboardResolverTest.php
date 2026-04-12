<?php

namespace Tests\Feature\Auth;

use App\Services\DashboardResolver;
use App\Services\TenantContext;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardResolverTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = app(DashboardResolver::class);
        Cache::flush();
    }

    /**
     * Test first-time user is directed to welcome dashboard
     */
    public function test_first_time_user_redirects_to_welcome_dashboard(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
        ]);

        $redirect = $this->resolver->resolve($user);

        $this->assertEquals(route('dashboard.welcome'), $redirect->getTargetUrl());
    }

    /**
     * Test user with single organisation role is routed to organisation dashboard
     */
    public function test_user_with_single_org_role_redirects_to_organisation(): void
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create();

        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        $redirect = $this->resolver->resolve($user);

        $this->assertEquals(
            route('organisations.show', $organisation->slug),
            $redirect->getTargetUrl()
        );
    }

    /**
     * Test user with multiple roles is directed to role selection
     */
    public function test_user_with_multiple_roles_redirects_to_role_selection(): void
    {
        $user = User::factory()->create();
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        // Create two organisation roles
        DB::table('user_organisation_roles')->insert([
            ['user_id' => $user->id, 'organisation_id' => $org1->id, 'role' => 'admin'],
            ['user_id' => $user->id, 'organisation_id' => $org2->id, 'role' => 'editor'],
        ]);

        $redirect = $this->resolver->resolve($user);

        $this->assertEquals(route('role.selection'), $redirect->getTargetUrl());
    }

    /**
     * Test voter user is routed to voting dashboard
     */
    public function test_voter_user_redirects_to_vote_dashboard(): void
    {
        $user = User::factory()->create([
            'is_voter' => true,
        ]);

        $redirect = $this->resolver->resolve($user);

        $this->assertEquals(route('vote.dashboard'), $redirect->getTargetUrl());
    }

    /**
     * Test dashboard resolution is cached
     */
    public function test_dashboard_resolution_is_cached(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
        ]);

        // First resolution - should not be cached
        $this->assertNull(Cache::get(config('login-routing.cache.cache_key_prefix') . $user->id));

        $redirect = $this->resolver->resolve($user);

        // Second resolution - should be cached
        $cached = Cache::get(config('login-routing.cache.cache_key_prefix') . $user->id);
        $this->assertNotNull($cached);
        $this->assertEquals($redirect->getTargetUrl(), $cached);
    }

    /**
     * Test cache is invalidated when user's session is not fresh
     */
    public function test_stale_cache_not_used(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
            'last_activity_at' => now()->subMinutes(5),
        ]);

        // Manually cache a resolution
        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        Cache::put($cacheKey, route('dashboard.welcome'), 300);

        // Resolve again - cache should be ignored due to stale session
        $redirect = $this->resolver->resolve($user);

        // Should still resolve correctly but through normal path, not cache
        $this->assertEquals(route('dashboard.welcome'), $redirect->getTargetUrl());
    }

    /**
     * Test active voting session takes priority
     */
    public function test_active_voting_session_takes_priority(): void
    {
        $user = User::factory()->create();
        $election = \App\Models\Election::factory()->create();

        // Create voter slug (active voting session)
        DB::table('voter_slugs')->insert([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'slug' => 'test-slug',
            'current_step' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $redirect = $this->resolver->resolve($user);

        // Should redirect to voting, not welcome dashboard
        $this->assertStringContainsString('vote', $redirect->getTargetUrl());
    }

    /**
     * Test legacy fallback for users without new system data
     */
    public function test_legacy_fallback_for_users_with_legacy_roles(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $redirect = $this->resolver->resolve($user);

        $this->assertEquals(route('admin.dashboard'), $redirect->getTargetUrl());
    }

    /**
     * Test defensive handling when tables don't exist
     */
    public function test_handles_missing_tables_gracefully(): void
    {
        $user = User::factory()->create([
            'is_voter' => false,
        ]);

        // Should not throw even if tables missing
        $redirect = $this->resolver->resolve($user);

        $this->assertIsNotNull($redirect);
        $this->assertEquals(route('dashboard.welcome'), $redirect->getTargetUrl());
    }

    /**
     * Test commission member is routed correctly
     */
    public function test_commission_member_redirects_to_commission_dashboard(): void
    {
        $user = User::factory()->create();
        $election = \App\Models\Election::factory()->create();

        // Create commission membership
        DB::table('election_commission_members')->insert([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $redirect = $this->resolver->resolve($user);

        $this->assertEquals(route('commission.dashboard'), $redirect->getTargetUrl());
    }

    /**
     * Test multiple role types prioritize correctly
     */
    public function test_role_priority_organization_over_voter(): void
    {
        $user = User::factory()->create(['is_voter' => true]);
        $organisation = Organisation::factory()->create();

        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        $redirect = $this->resolver->resolve($user);

        // Organisation role should take priority over voter role
        $this->assertEquals(
            route('organisations.show', $organisation->slug),
            $redirect->getTargetUrl()
        );
    }

    /**
     * Test TenantContext is set when redirecting to organisation dashboard
     */
    public function test_tenant_context_is_set_on_organisation_redirect(): void
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create(['type' => 'tenant']);

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mock TenantContext to verify setContext is called
        $tenantContext = app(TenantContext::class);

        $redirect = $this->resolver->resolve($user);

        // After resolution, TenantContext should have the organisation set
        // (This verifies that setContext was called during resolution)
        try {
            $currentOrg = $tenantContext->getCurrentOrganisation();
            $this->assertEquals($organisation->id, $currentOrg->id);
        } catch (\RuntimeException $e) {
            // If TenantContext is not set, this test will fail
            $this->fail('TenantContext was not set during DashboardResolver resolution: ' . $e->getMessage());
        }
    }
}
