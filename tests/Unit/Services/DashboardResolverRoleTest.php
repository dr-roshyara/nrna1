<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\Organisation;
use App\Services\DashboardResolver;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DashboardResolverRoleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * ✅ TEST 1: Platform organisation members should NOT be routed to organisations page
     *
     * A user with 'member' role in platform organisation (id=1) should be treated as first-time
     * and NOT receive 'admin' dashboard role.
     */
    public function test_platform_member_does_not_get_admin_role(): void
    {
        // Create a user
        $user = User::factory()->create([
            'organisation_id' => 1, // Platform organisation
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        // Manually create pivot entry (factory doesn't do this automatically)
        \DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Verify user has 'member' role in platform organisation
        $this->assertDatabaseHas('user_organisation_roles', [
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
        ]);

        // Resolve dashboard - should NOT redirect to /organisations/publicdigit
        $resolver = app(DashboardResolver::class);
        $response = $resolver->resolve($user);

        // Should redirect to normal dashboard, not organisation page
        $this->assertStringNotContainsString(
            'organisations/publicdigit',
            $response->getTargetUrl(),
            'Platform member should NOT be redirected to /organisations/publicdigit'
        );
    }

    /**
     * ✅ TEST 2: Non-platform organisation admins SHOULD be routed to organisation page
     *
     * A user with 'admin' role in a non-platform organisation should receive 'admin'
     * dashboard role and be routed to that organisation's page.
     */
    public function test_non_platform_admin_gets_admin_role(): void
    {
        // Create a non-platform organisation
        $org = Organisation::factory()->create([
            'is_platform' => 0,
            'slug' => 'test-org',
        ]);

        // Create a user
        $user = User::factory()->create([
            'organisation_id' => $org->id,
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        // Give user admin role in this non-platform organisation
        \DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Resolve dashboard - should redirect to organisation page
        $resolver = app(DashboardResolver::class);
        $response = $resolver->resolve($user);

        // Should redirect to organisation page
        $this->assertStringContainsString(
            'organisations/' . $org->slug,
            $response->getTargetUrl(),
            'Non-platform admin should be redirected to their organisation page'
        );
    }

    /**
     * ✅ TEST 3: Platform admin in platform organisation should NOT auto-redirect
     *
     * Even if a user is admin in platform organisation, they should not be automatically
     * routed to /organisations/publicdigit. Platform admins should have special handling.
     */
    public function test_platform_admin_does_not_auto_redirect(): void
    {
        // Create a user
        $user = User::factory()->create([
            'organisation_id' => 1, // Platform organisation
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        // Create pivot entry with admin role
        \DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'admin',
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Verify admin role
        $this->assertDatabaseHas('user_organisation_roles', [
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'admin',
        ]);

        // Resolve dashboard
        $resolver = app(DashboardResolver::class);
        $response = $resolver->resolve($user);

        // Should NOT redirect to /organisations/publicdigit
        $this->assertStringNotContainsString(
            'organisations/publicdigit',
            $response->getTargetUrl(),
            'Platform admin should NOT be auto-redirected to /organisations/publicdigit'
        );
    }

    /**
     * ✅ TEST 4: New user with only platform membership should see welcome page
     *
     * After email verification, a user with only platform membership should be
     * redirected to welcome page, not to dashboard or organisation page.
     */
    public function test_newly_verified_user_sees_welcome_page(): void
    {
        // Create a user that just verified email but hasn't been onboarded
        $user = User::factory()->create([
            'organisation_id' => 1,
            'email_verified_at' => now(),
            'onboarded_at' => null, // Not yet onboarded
        ]);

        // Manually create pivot entry
        \DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Verify user has 'member' role in platform organisation
        $this->assertDatabaseHas('user_organisation_roles', [
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
        ]);

        // Resolve dashboard - should redirect to welcome page
        $resolver = app(DashboardResolver::class);
        $response = $resolver->resolve($user);

        // Should redirect to welcome page
        $this->assertStringContainsString(
            'dashboard/welcome',
            $response->getTargetUrl(),
            'Newly verified user should be redirected to welcome page'
        );
    }
}
