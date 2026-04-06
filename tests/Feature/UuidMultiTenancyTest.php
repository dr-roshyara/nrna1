<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test Suite: UUID Multi-Tenancy Implementation
 *
 * These tests define the expected behavior of the complete UUID multi-tenancy system.
 * They are written FIRST and drive the implementation through RED -> GREEN -> REFACTOR cycles.
 */
class UuidMultiTenancyTest extends TestCase
{
    use RefreshDatabase;

    // ==================== ORGANISATION MODEL TESTS ====================

    /**
     * @test
     * Organisation model uses UUID as primary key
     */
    public function organisation_uses_uuid_primary_key()
    {
        $org = Organisation::factory()->create([
            'name' => 'Test Org',
            'slug' => 'test-org',
        ]);

        // UUID should be a string, not an integer
        $this->assertIsString($org->id);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $org->id);
    }

    /**
     * @test
     * Organisation has type field (platform or tenant)
     */
    public function organisation_has_type_field()
    {
        $platformOrg = Organisation::factory()->create([
            'type' => 'platform',
            'is_default' => true,
        ]);

        $tenantOrg = Organisation::factory()->create([
            'type' => 'tenant',
            'is_default' => false,
        ]);

        $this->assertEquals('platform', $platformOrg->type);
        $this->assertEquals('tenant', $tenantOrg->type);
    }

    /**
     * @test
     * Organisation has isPlatform() and isTenant() methods
     */
    public function organisation_has_type_checking_methods()
    {
        $platformOrg = Organisation::factory()->create(['type' => 'platform']);
        $tenantOrg = Organisation::factory()->create(['type' => 'tenant']);

        $this->assertTrue($platformOrg->isPlatform());
        $this->assertFalse($platformOrg->isTenant());

        $this->assertFalse($tenantOrg->isPlatform());
        $this->assertTrue($tenantOrg->isTenant());
    }

    /**
     * @test
     * Organisation::getDefaultPlatform() returns platform org with is_default=true
     */
    public function organisation_get_default_platform()
    {
        $platformOrg = Organisation::factory()->create([
            'type' => 'platform',
            'is_default' => true,
            'slug' => 'platform',
        ]);

        $foundOrg = Organisation::getDefaultPlatform();

        $this->assertNotNull($foundOrg);
        $this->assertEquals($platformOrg->id, $foundOrg->id);
        $this->assertTrue($foundOrg->isPlatform());
        $this->assertTrue($foundOrg->is_default);
    }

    // ==================== USER MODEL TESTS ====================

    /**
     * @test
     * User model uses UUID as primary key
     */
    public function user_uses_uuid_primary_key()
    {
        $user = User::factory()->create();

        $this->assertIsString($user->id);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $user->id);
    }

    /**
     * @test
     * User can belong to multiple organisations through pivot table
     */
    public function user_belongs_to_multiple_organisations()
    {
        $user = User::factory()->create();
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org1->id,
            'role' => 'admin',
        ]);

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org2->id,
            'role' => 'member',
        ]);

        $organisations = $user->organisations()->pluck('id');

        $this->assertCount(2, $organisations);
        $this->assertContains($org1->id, $organisations);
        $this->assertContains($org2->id, $organisations);
    }

    /**
     * @test
     * User::belongsToOrganisation() checks membership
     */
    public function user_belongs_to_organisation_method()
    {
        $user = User::factory()->create();
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org1->id,
            'role' => 'member',
        ]);

        $this->assertTrue($user->belongsToOrganisation($org1->id));
        $this->assertFalse($user->belongsToOrganisation($org2->id));
    }

    /**
     * @test
     * User::getRoleInOrganisation() returns user's role
     */
    public function user_get_role_in_organisation()
    {
        $user = User::factory()->create();
        $org = Organisation::factory()->create();

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        $role = $user->getRoleInOrganisation($org->id);

        $this->assertEquals('admin', $role);
    }

    // ==================== USER ORGANISATION ROLE MODEL TESTS ====================

    /**
     * @test
     * UserOrganisationRole model uses UUID primary key
     */
    public function user_organisation_role_uses_uuid_primary_key()
    {
        $user = User::factory()->create();
        $org = Organisation::factory()->create();

        $role = UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
        ]);

        $this->assertIsString($role->id);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $role->id);
    }

    /**
     * @test
     * UserOrganisationRole prevents duplicate user-org pairs
     */
    public function user_organisation_role_unique_constraint()
    {
        $user = User::factory()->create();
        $org = Organisation::factory()->create();

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
        ]);

        // Should not allow duplicate
        $this->expectException(\Illuminate\Database\QueryException::class);

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin', // Different role, same user-org pair
        ]);
    }

    // ==================== ELECTION MODEL TESTS ====================

    /**
     * @test
     * Election model uses UUID primary key
     */
    public function election_uses_uuid_primary_key()
    {
        $org = Organisation::factory()->create();
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
        ]);

        $this->assertIsString($election->id);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $election->id);
    }

    /**
     * @test
     * Election has scopeForOrganisation() to filter by organisation
     */
    public function election_scope_for_organisation()
    {
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        Election::factory()->create(['organisation_id' => $org1->id]);
        Election::factory()->create(['organisation_id' => $org1->id]);
        Election::factory()->create(['organisation_id' => $org2->id]);

        $org1Elections = Election::forOrganisation($org1->id)->get();
        $org2Elections = Election::forOrganisation($org2->id)->get();

        $this->assertCount(2, $org1Elections);
        $this->assertCount(1, $org2Elections);
    }

    // ==================== TENANT CONTEXT SERVICE TESTS ====================

    /**
     * @test
     * TenantContext service can set and retrieve current organisation
     */
    public function tenant_context_service_manages_context()
    {
        $user = User::factory()->create();
        $org = Organisation::factory()->create();

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
        ]);

        $tenantContext = app(\App\Services\TenantContext::class);
        $tenantContext->setContext($user, $org);

        $this->assertEquals($org->id, $tenantContext->getCurrentOrganisationId());
        $this->assertEquals($org->id, $tenantContext->getCurrentOrganisation()->id);
    }

    /**
     * @test
     * TenantContext throws exception if user doesn't belong to organisation
     */
    public function tenant_context_validates_membership()
    {
        $user = User::factory()->create();
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org1->id,
            'role' => 'member',
        ]);

        $tenantContext = app(\App\Services\TenantContext::class);

        // Should succeed
        $tenantContext->setContext($user, $org1);
        $this->assertEquals($org1->id, $tenantContext->getCurrentOrganisationId());

        // Should fail - user doesn't belong to org2
        $this->expectException(\RuntimeException::class);
        $tenantContext->setContext($user, $org2);
    }

    /**
     * @test
     * TenantContext persists to session
     */
    public function tenant_context_persists_to_session()
    {
        $user = User::factory()->create();
        $org = Organisation::factory()->create();

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
        ]);

        $tenantContext = app(\App\Services\TenantContext::class);
        $tenantContext->setContext($user, $org);

        $this->assertEquals($org->id, session('current_organisation_id'));
    }

    /**
     * @test
     * TenantContext resolves from session on retrieval
     */
    public function tenant_context_resolves_from_session()
    {
        $org = Organisation::factory()->create();
        session(['current_organisation_id' => $org->id]);

        $tenantContext = app(\App\Services\TenantContext::class);
        $currentOrg = $tenantContext->getCurrentOrganisation();

        $this->assertEquals($org->id, $currentOrg->id);
    }

    /**
     * @test
     * TenantContext isPlatformContext() and isTenantContext() methods
     */
    public function tenant_context_organisation_type_checks()
    {
        $platformOrg = Organisation::factory()->create(['type' => 'platform']);
        $tenantOrg = Organisation::factory()->create(['type' => 'tenant']);

        $user = User::factory()->create();
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $platformOrg->id,
            'role' => 'member',
        ]);
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $tenantOrg->id,
            'role' => 'member',
        ]);

        $tenantContext = app(\App\Services\TenantContext::class);

        $tenantContext->setContext($user, $platformOrg);
        $this->assertTrue($tenantContext->isPlatformContext());
        $this->assertFalse($tenantContext->isTenantContext());

        $tenantContext->setContext($user, $tenantOrg);
        $this->assertFalse($tenantContext->isPlatformContext());
        $this->assertTrue($tenantContext->isTenantContext());
    }
}
