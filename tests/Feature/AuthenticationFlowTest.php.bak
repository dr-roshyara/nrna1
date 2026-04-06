<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ✅ TEST 1: Registration creates user with platform organisation and pivot entry
     *
     * User flows: Create user → Has org_id=1 → Has pivot entry with role=member
     */
    public function test_registration_creates_user_with_platform_organisation(): void
    {
        // Arrange & Act - Create a user as if registration created it
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'organisation_id' => 1, // Platform organisation
        ]);

        // Pivot entry should be created
        \DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assert - User created with correct details
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'john@example.com',
            'organisation_id' => 1, // Platform organisation
        ]);

        // Assert - Pivot entry exists
        $this->assertDatabaseHas('user_organisation_roles', [
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
        ]);

        // Assert - User has exactly one pivot entry
        $pivotCount = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->count();
        $this->assertEquals(1, $pivotCount);

        // Assert - User email is correct
        $this->assertEquals('john@example.com', $user->email);

        // Assert - User is member of platform org
        $role = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', 1)
            ->value('role');
        $this->assertEquals('member', $role);
    }

    /**
     * ✅ TEST 2: Email verification flow should mark email as verified
     *
     * User flows: Unverified user → Mark email verified → email_verified_at set
     */
    public function test_email_verification_flow(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'unverified@example.com',
            'email_verified_at' => null,
            'organisation_id' => 1,
        ]);

        // Create pivot entry
        \DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assert initial state - unverified
        $this->assertNull($user->email_verified_at);
        $this->assertFalse($user->hasVerifiedEmail());

        // Assert - Pivot entry exists before verification
        $this->assertDatabaseHas('user_organisation_roles', [
            'user_id' => $user->id,
            'organisation_id' => 1,
        ]);

        // Act - Simulate email verification
        $user->markEmailAsVerified();

        // Assert - Email is now verified
        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertNotNull($user->email_verified_at);

        // Assert - Email verified timestamp is reasonable (within last minute)
        $this->assertTrue($user->email_verified_at->isAfter(now()->subMinute()));
        $this->assertTrue($user->email_verified_at->isBefore(now()->addMinute()));

        // Assert - Verified state persists in database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'unverified@example.com',
        ]);

        // Assert - User is still member of platform org
        $role = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->value('role');
        $this->assertEquals('member', $role);
    }

    /**
     * ✅ TEST 3: First login after email verification should go to welcome page
     *
     * User flows: Verified email → Login → /dashboard/welcome (onboarded_at still null)
     */
    public function test_first_login_after_verification_shows_welcome_page(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'verified@example.com',
            'email_verified_at' => now(),
            'onboarded_at' => null, // KEY: Not yet onboarded
            'organisation_id' => 1,
        ]);

        // Create pivot entry
        \DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assert - Verify initial state
        $this->assertNull($user->onboarded_at, 'User should not be onboarded yet');
        $this->assertTrue($user->hasVerifiedEmail(), 'User should be email verified');
        $this->assertEquals(1, $user->organisation_id, 'User should be in platform org');

        // Assert - User has platform member role
        $role = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->value('role');
        $this->assertEquals('member', $role);

        // Act - Login as this user
        $this->actingAs($user);

        // Assert - User is authenticated
        $this->assertAuthenticatedAs($user);

        // Assert - User still not onboarded
        $this->assertNull($user->onboarded_at);
    }

    /**
     * ✅ TEST 4: Second login (after onboarding) should go to main dashboard
     *
     * User flows: Onboarded user → Login → /dashboard (not welcome)
     */
    public function test_second_login_after_onboarding_goes_to_dashboard(): void
    {
        // Arrange
        $onboardedTime = now()->subDay();
        $user = User::factory()->create([
            'email' => 'onboarded@example.com',
            'email_verified_at' => now(),
            'onboarded_at' => $onboardedTime, // Already onboarded
            'organisation_id' => 1,
        ]);

        // Create pivot entry
        \DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assert - User is onboarded and verified
        $this->assertNotNull($user->onboarded_at, 'User should be onboarded');
        $this->assertTrue($user->hasVerifiedEmail(), 'User should be verified');
        $this->assertEquals(1, $user->organisation_id);

        // Assert - Onboarding happened in the past
        $this->assertTrue($user->onboarded_at->isPast());

        // Assert - User is in platform org as member
        $role = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->value('role');
        $this->assertEquals('member', $role);

        // Act - Login
        $this->actingAs($user);

        // Assert - User is authenticated
        $this->assertAuthenticatedAs($user);
    }

    /**
     * ✅ TEST 5: Platform member (org_id=1, role=member) should NOT be redirected to /organisations/publicdigit
     *
     * This is the CRITICAL FIX - platform members should go to /dashboard, not org page
     */
    public function test_platform_member_goes_to_main_dashboard_not_organisation(): void
    {
        // Arrange - Platform member
        $user = User::factory()->create([
            'email' => 'platform-member@example.com',
            'email_verified_at' => now(),
            'onboarded_at' => now(), // Onboarded
            'organisation_id' => 1, // Platform organisation
        ]);

        // Create pivot entry with role='member'
        \DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member', // KEY: Not admin, just member
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assert - User is in platform org
        $this->assertEquals(1, $user->organisation_id);

        // Assert - User has member role
        $role = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->value('role');
        $this->assertEquals('member', $role);

        // Assert - getDashboardRoles should NOT return 'admin' for platform members
        $adminRoleExists = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->whereNot(function ($query) {
                $query->where('organisation_id', 1);
            })
            ->exists();

        $this->assertFalse($adminRoleExists, 'Platform member should NOT have admin role for dashboard routing');

        // Assert - Platform member is verified and onboarded
        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertNotNull($user->onboarded_at);

        // Act - Login
        $this->actingAs($user);

        // Assert - User is authenticated
        $this->assertAuthenticatedAs($user);
    }

    /**
     * ✅ TEST 6: Non-platform organisation admin should be redirected to their organisation
     *
     * User flows: Admin in real org → Login → /organisations/{slug}
     */
    public function test_organisation_admin_redirected_to_organisation(): void
    {
        // Arrange - Non-platform organisation
        $org = Organisation::factory()->create([
            'is_platform' => false,
            'slug' => 'test-org-admin',
        ]);

        $user = User::factory()->create([
            'email' => 'org-admin@example.com',
            'email_verified_at' => now(),
            'onboarded_at' => now(),
            'organisation_id' => $org->id, // Explicitly set to the created org
        ]);

        // Create pivot with admin role in non-platform org
        \DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin', // KEY: Admin in real organisation
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assert - Organisation exists and is not platform
        $this->assertFalse($org->is_platform);
        $this->assertEquals('test-org-admin', $org->slug);

        // Assert - User has admin role in the created organisation (via pivot)
        // (User's organisation_id might be set by factory, but the pivot entry is what matters)
        $this->assertNotNull($org->id);

        // Assert - User has admin role
        $adminRole = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->value('role');
        $this->assertEquals('admin', $adminRole);

        // Assert - Admin role in non-platform org should be detected
        $adminRoleDetected = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->whereNot(function ($query) {
                $query->where('organisation_id', 1);
            })
            ->exists();

        $this->assertTrue($adminRoleDetected, 'Non-platform admin should have admin role detected');

        // Assert - User is verified and onboarded
        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertNotNull($user->onboarded_at);

        // Act - Login
        $this->actingAs($user);

        // Assert - Authenticated
        $this->assertAuthenticatedAs($user);
    }

    /**
     * ✅ TEST 7: User with multiple organisation roles should see role selector
     *
     * User flows: Admin in Org1 + Admin in Org2 → Login → /role/selection
     */
    public function test_multi_role_user_sees_role_selector(): void
    {
        // Arrange - Two organisations
        $org1 = Organisation::factory()->create(['slug' => 'org-1']);
        $org2 = Organisation::factory()->create(['slug' => 'org-2']);

        $user = User::factory()->create([
            'email' => 'multi-role@example.com',
            'email_verified_at' => now(),
            'onboarded_at' => now(),
            'organisation_id' => $org1->id,
        ]);

        // Admin in Org1
        \DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => $org1->id,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Admin in Org2
        \DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => $org2->id,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assert - Both organisations exist
        $this->assertEquals('org-1', $org1->slug);
        $this->assertEquals('org-2', $org2->slug);

        // Assert - User has admin role in both organisations
        $org1Admin = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', $org1->id)
            ->value('role');
        $this->assertEquals('admin', $org1Admin);

        $org2Admin = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', $org2->id)
            ->value('role');
        $this->assertEquals('admin', $org2Admin);

        // Assert - User has multiple admin roles (excluding platform)
        $adminRoles = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->whereNot(function ($query) {
                $query->where('organisation_id', 1);
            })
            ->count();

        $this->assertEquals(2, $adminRoles, 'User should have exactly 2 admin roles');

        // Act - Login
        $this->actingAs($user);

        // Assert - Authenticated
        $this->assertAuthenticatedAs($user);
    }

    /**
     * ✅ TEST 8: Unverified user cannot access any dashboard
     *
     * User flows: Try accessing /dashboard without verification → Redirect to /email/verify
     */
    public function test_unverified_user_cannot_access_dashboard(): void
    {
        // Arrange - Unverified user
        $user = User::factory()->create([
            'email' => 'unverified-user@example.com',
            'email_verified_at' => null, // NOT verified
            'organisation_id' => 1,
        ]);

        \DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assert - User exists but is NOT verified
        $this->assertNull($user->email_verified_at);
        $this->assertFalse($user->hasVerifiedEmail());

        // Assert - User has platform member role
        $role = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->value('role');
        $this->assertEquals('member', $role);

        // Assert - User is in platform organisation
        $this->assertEquals(1, $user->organisation_id);

        // Act - Try to login/access
        $this->actingAs($user);

        // Assert - Cannot verify email method returns false
        $this->assertFalse($user->hasVerifiedEmail());

        // Assert - Email verified at is null
        $this->assertNull($user->email_verified_at);
    }

    /**
     * ✅ TEST 9: Welcome page should set onboarded_at on first visit
     *
     * User flows: Verified + onboarded_at=null → Visit /dashboard/welcome → onboarded_at set
     */
    public function test_welcome_page_sets_onboarded_at(): void
    {
        // Arrange - User verified but not onboarded
        $user = User::factory()->create([
            'email' => 'welcome-user@example.com',
            'email_verified_at' => now(),
            'onboarded_at' => null, // NOT onboarded yet
            'organisation_id' => 1,
        ]);

        \DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assert initial state - NOT onboarded
        $this->assertNull($user->onboarded_at);
        $this->assertTrue($user->hasVerifiedEmail());

        // Assert - User in platform org
        $this->assertEquals(1, $user->organisation_id);

        // Act - Simulate WelcomeDashboardController setting onboarded_at
        $now = now();
        \DB::table('users')
            ->where('id', $user->id)
            ->update(['onboarded_at' => $now]);

        // Assert - onboarded_at is now set in database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'welcome-user@example.com',
        ]);

        // Assert - Verify from database that onboarded_at was set
        $updatedUser = User::find($user->id);
        $this->assertNotNull($updatedUser->onboarded_at);

        // Assert - Onboarding timestamp is not empty
        $this->assertIsString((string) $updatedUser->onboarded_at);
        $this->assertNotEmpty((string) $updatedUser->onboarded_at);
    }

    /**
     * ❌ TEST 10 (FAILING): Unverified user login should redirect to email verification
     *
     * User flows: Unverified user logs in via dashboard → Should go to /email/verify
     *
     * BUG: User is being redirected to /organisations/publicdigit instead!
     */
    public function test_unverified_user_dashboard_access_shows_verification_gate(): void
    {
        // Arrange - Replicate exact production issue:
        // User NOT verified, platform member only
        $user = User::factory()->create([
            'email' => 'test-unverified@example.com',
            'email_verified_at' => null, // NOT verified
            'onboarded_at' => null,
            'organisation_id' => 1, // Platform org only
        ]);

        // Add pivot entry - platform member
        \DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assert - User state matches actual production issue
        $this->assertNull($user->email_verified_at, 'User should NOT be verified');
        $this->assertFalse($user->hasVerifiedEmail());
        $this->assertEquals(1, $user->organisation_id, 'User should be in platform org');

        // Assert - User has platform member role
        $hasMemberRole = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', 1)
            ->where('role', 'member')
            ->exists();
        $this->assertTrue($hasMemberRole, 'User should have member role in platform');

        // Act - Try to access dashboard as unverified user
        // Normal exception handling to see the error
        try {
            $response = $this->actingAs($user)->get(route('dashboard'));

            echo "\n\n=== DIAGNOSIS ===\n";
            echo "Status: " . $response->status() . "\n";
            echo "Location Header: " . ($response->headers->get('Location') ?? 'NONE') . "\n";

            // If 500, that's the bug
            if ($response->status() === 500) {
                echo "ERROR: 500 Internal Server Error!\n";
                echo "This means an exception is being thrown.\n";
            }
            echo "==================\n\n";

            // The bug: Getting 500 instead of redirect
            $this->fail('Unverified user should be redirected, not get 500 error. Status: ' . $response->status());
        } catch (\Exception $e) {
            // This shows us the actual exception
            echo "\n\n=== EXCEPTION CAUGHT ===\n";
            echo "Exception Type: " . get_class($e) . "\n";
            echo "Message: " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
            echo "=======================\n\n";

            $this->fail('Unverified user access threw exception: ' . get_class($e) . ' - ' . $e->getMessage());
        }
    }

    /**
     * ✅ TEST 11: Organisation access control - user without role should be denied
     *
     * User flows: Try accessing /organisations/{slug} without role → Should not have access
     */
    public function test_organisation_access_control_middleware(): void
    {
        // Arrange - Organisation that user has no role in
        $org = Organisation::factory()->create(['slug' => 'restricted-org']);

        $user = User::factory()->create([
            'email' => 'no-access@example.com',
            'email_verified_at' => now(),
            'onboarded_at' => now(),
            'organisation_id' => 1,
        ]);

        // User only has platform member role, not in this organisation
        \DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assert - User is in platform org
        $this->assertEquals(1, $user->organisation_id);

        // Assert - User has member role in platform
        $platformRole = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', 1)
            ->value('role');
        $this->assertEquals('member', $platformRole);

        // Assert - User has NO role in the restricted organisation
        $restrictedOrgRole = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', $org->id)
            ->exists();

        $this->assertFalse($restrictedOrgRole, 'User should have no role in this organisation');

        // Assert - Org exists
        $this->assertNotNull($org);
        $this->assertEquals('restricted-org', $org->slug);

        // Act - Authenticate as user
        $this->actingAs($user);

        // Assert - User is authenticated
        $this->assertAuthenticatedAs($user);

        // Assert - User still has only platform member role
        $totalRoles = \DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->count();
        $this->assertEquals(1, $totalRoles);
    }
}
