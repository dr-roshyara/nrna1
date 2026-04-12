<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\DashboardResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardResolverTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = app(DashboardResolver::class);
    }

    /**
     * Helper: Create a test organization
     */
    private function createOrganization(): int
    {
        return \DB::table('organizations')->insertGetId([
            'name' => 'Test Organization ' . uniqid(),
            'slug' => 'test-org-' . uniqid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Helper: Create a test election
     */
    private function createElection(): int
    {
        return \DB::table('elections')->insertGetId([
            'title' => 'Test Election ' . uniqid(),
            'slug' => 'test-election-' . uniqid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * TEST 1: First-time user (no roles, no orgs) → welcome dashboard
     */
    public function test_first_time_user_redirects_to_welcome_dashboard()
    {
        $user = User::factory()->create([
            'is_voter' => false,
            'is_committee_member' => false,
        ]);

        $response = $this->resolver->resolve($user);

        $this->assertEquals(route('dashboard.welcome'), $response->getTargetUrl());
    }

    /**
     * TEST 2: User with organization role → admin dashboard
     */
    public function test_user_with_organization_redirects_to_admin()
    {
        $user = User::factory()->create(['is_voter' => false]);

        $orgId = $this->createOrganization();

        \DB::table('user_organization_roles')->insert([
            'user_id' => $user->id,
            'organization_id' => $orgId,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->resolver->resolve($user);

        $this->assertEquals(route('admin.dashboard'), $response->getTargetUrl());
    }

    /**
     * TEST 3: User with commission membership → commission dashboard
     */
    public function test_user_with_commission_redirects_to_commission()
    {
        $user = User::factory()->create(['is_voter' => false]);

        $electionId = $this->createElection();

        \DB::table('election_commission_members')->insert([
            'user_id' => $user->id,
            'election_id' => $electionId,
            'member_name' => $user->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->resolver->resolve($user);

        $this->assertEquals(route('commission.dashboard'), $response->getTargetUrl());
    }

    /**
     * TEST 4: User with voter status → voter dashboard
     */
    public function test_user_with_voter_status_redirects_to_voter()
    {
        $user = User::factory()->create(['is_voter' => true]);

        $response = $this->resolver->resolve($user);

        $this->assertEquals(route('vote.dashboard'), $response->getTargetUrl());
    }

    /**
     * TEST 5: User with multiple roles (org + voter) → role selection
     */
    public function test_user_with_multiple_roles_redirects_to_selection()
    {
        $user = User::factory()->create(['is_voter' => true]);

        $orgId = $this->createOrganization();

        \DB::table('user_organization_roles')->insert([
            'user_id' => $user->id,
            'organization_id' => $orgId,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->resolver->resolve($user);

        $this->assertEquals(route('role.selection'), $response->getTargetUrl());
    }

    /**
     * TEST 6: Legacy user with Spatie admin role → admin dashboard
     */
    public function test_legacy_user_with_admin_role_redirects_to_admin()
    {
        $user = User::factory()->create(['is_voter' => false]);
        $user->assignRole('admin');

        $response = $this->resolver->resolve($user);

        $this->assertEquals(route('admin.dashboard'), $response->getTargetUrl());
    }

    /**
     * TEST 7: Legacy user with election_officer role → admin dashboard
     */
    public function test_legacy_user_with_election_officer_role_redirects_to_admin()
    {
        $user = User::factory()->create(['is_voter' => false]);
        $user->assignRole('election_officer');

        $response = $this->resolver->resolve($user);

        $this->assertEquals(route('admin.dashboard'), $response->getTargetUrl());
    }

    /**
     * TEST 8: Legacy user marked as committee_member → commission dashboard
     */
    public function test_legacy_committee_member_redirects_to_commission()
    {
        $user = User::factory()->create([
            'is_voter' => false,
            'is_committee_member' => true,
        ]);

        $response = $this->resolver->resolve($user);

        $this->assertEquals(route('commission.dashboard'), $response->getTargetUrl());
    }

    /**
     * TEST 9: User with no roles and nothing else → first-time user (not default)
     */
    public function test_user_with_no_roles_redirects_to_welcome()
    {
        $user = User::factory()->create([
            'is_voter' => false,
            'is_committee_member' => false,
        ]);

        $response = $this->resolver->resolve($user);

        // First-time user should go to welcome
        $this->assertEquals(route('dashboard.welcome'), $response->getTargetUrl());
    }

    /**
     * TEST 10: CRITICAL BUG FIX - Returning user (old account, >7 days) with no roles
     * This validates the 7-day cutoff removal (core fix)
     */
    public function test_old_account_with_no_roles_still_is_first_time()
    {
        // User created long ago (>7 days), but never completed onboarding
        $user = User::factory()->create([
            'created_at' => now()->subDays(30),
            'is_voter' => false,
            'is_committee_member' => false,
        ]);

        $response = $this->resolver->resolve($user);

        // Should still be treated as first-time user (NOT blocked by 7-day limit)
        $this->assertEquals(
            route('dashboard.welcome'),
            $response->getTargetUrl(),
            'Old account (30 days) should still be first-time if no roles assigned'
        );
    }

    /**
     * TEST 11: Organization + Commission + Voter = 3 roles
     */
    public function test_user_with_three_roles_shows_selection()
    {
        $user = User::factory()->create(['is_voter' => true]);

        $orgId = $this->createOrganization();
        $electionId = $this->createElection();

        \DB::table('user_organization_roles')->insert([
            'user_id' => $user->id,
            'organization_id' => $orgId,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \DB::table('election_commission_members')->insert([
            'user_id' => $user->id,
            'election_id' => $electionId,
            'member_name' => $user->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->resolver->resolve($user);

        $this->assertEquals(route('role.selection'), $response->getTargetUrl());
    }

    /**
     * TEST 12: Legacy voter (old system only) → existing voter dashboard
     */
    public function test_legacy_voter_with_no_new_roles_redirects_to_voter_dashboard()
    {
        $user = User::factory()->create([
            'is_voter' => true,
            'is_committee_member' => false,
        ]);

        // No new system roles (no org, no commission)
        $response = $this->resolver->resolve($user);

        $this->assertEquals(route('vote.dashboard'), $response->getTargetUrl());
    }

    /**
     * TEST 13: Response type verification
     */
    public function test_resolve_returns_redirect_response()
    {
        $user = User::factory()->create(['is_voter' => false]);

        $response = $this->resolver->resolve($user);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
    }
}
