<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Organisation;
use App\Services\DashboardResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Tests for hasOwnOrganisation logic
 *
 * Verifies that users can be distinguished between:
 * 1. Users with their OWN organisation (tenant type)
 * 2. Users with ONLY platform organisation
 */
class HasOwnOrganisationTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $platformOrg;
    protected DashboardResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->platformOrg = Organisation::firstOrCreate(
            ['slug' => 'publicdigit'],
            [
                'name' => 'Public Digit',
                'type' => 'platform',
                'is_default' => true,
            ]
        );

        $this->resolver = app(DashboardResolver::class);
    }

    /**
     * Test hasOwnOrganisation returns true when user belongs to tenant org
     */
    public function test_has_own_organisation_returns_true_when_belongs_to_tenant_org(): void
    {
        $user = User::factory()->create();
        $tenantOrg = Organisation::factory()->create(['type' => 'tenant']);

        // Add user to tenant org
        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $tenantOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue(
            $user->hasOwnOrganisation(),
            'User should have own organisation when belonging to tenant'
        );
    }

    /**
     * Test hasOwnOrganisation returns false when user only has platform org
     */
    public function test_has_own_organisation_returns_false_when_only_platform(): void
    {
        $user = User::factory()->create();

        // Add user only to platform org
        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->platformOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse(
            $user->hasOwnOrganisation(),
            'User should NOT have own organisation when only in platform'
        );
    }

    /**
     * Test getOwnOrganisation returns tenant org
     */
    public function test_get_own_organisation_returns_tenant_org(): void
    {
        $user = User::factory()->create();
        $tenantOrg = Organisation::factory()->create(['type' => 'tenant']);

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $tenantOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $ownOrg = $user->getOwnOrganisation();

        $this->assertNotNull($ownOrg);
        $this->assertEquals($tenantOrg->id, $ownOrg->id);
        $this->assertEquals('tenant', $ownOrg->type);
    }

    /**
     * Test getOwnOrganisation returns null when no tenant org
     */
    public function test_get_own_organisation_returns_null_when_no_tenant_org(): void
    {
        $user = User::factory()->create();

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->platformOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertNull(
            $user->getOwnOrganisation(),
            'Should return null when user has no tenant org'
        );
    }

    /**
     * Test isOwnerOf returns true when user has owner role
     */
    public function test_is_owner_of_returns_true_when_role_is_owner(): void
    {
        $user = User::factory()->create();
        $org = Organisation::factory()->create(['type' => 'tenant']);

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'owner',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue(
            $user->isOwnerOf($org->id),
            'User should be owner when role is owner'
        );
    }

    /**
     * Test isOwnerOf returns false when user has member role
     */
    public function test_is_owner_of_returns_false_when_role_is_member(): void
    {
        $user = User::factory()->create();
        $org = Organisation::factory()->create(['type' => 'tenant']);

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse(
            $user->isOwnerOf($org->id),
            'User should NOT be owner when role is member'
        );
    }

    /**
     * Test user with own organisation is correctly identified
     */
    public function test_user_with_own_organisation_is_correctly_identified(): void
    {
        $user = User::factory()->create();
        $ownOrg = Organisation::factory()->create(['type' => 'tenant']);

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $ownOrg->id,
            'role' => 'owner',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue(
            $user->hasOwnOrganisation(),
            'User should be identified as having own organisation'
        );

        $this->assertEquals(
            $ownOrg->id,
            $user->getOwnOrganisation()->id,
            'getOwnOrganisation should return the tenant org'
        );
    }

    /**
     * Test user with only platform org is correctly identified
     */
    public function test_user_with_only_platform_org_is_correctly_identified(): void
    {
        $user = User::factory()->create();

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->platformOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse(
            $user->hasOwnOrganisation(),
            'User should NOT have own organisation when only in platform'
        );

        $this->assertNull(
            $user->getOwnOrganisation(),
            'getOwnOrganisation should return null for platform-only user'
        );
    }

    /**
     * Test user with both platform and tenant org prioritizes tenant
     */
    public function test_user_with_both_orgs_prioritizes_tenant(): void
    {
        $user = User::factory()->create();
        $tenantOrg = Organisation::factory()->create(['type' => 'tenant']);

        // Add to both platform and tenant
        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->platformOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $tenantOrg->id,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue(
            $user->hasOwnOrganisation(),
            'User should be identified as having own organisation'
        );

        $this->assertEquals(
            $tenantOrg->id,
            $user->getOwnOrganisation()->id,
            'getOwnOrganisation should return tenant org'
        );
    }
}
