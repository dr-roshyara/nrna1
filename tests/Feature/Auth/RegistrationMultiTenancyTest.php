<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test Suite: Registration with Multi-Tenancy
 *
 * Tests the demo→paid user journey:
 * 1. User registers
 * 2. Automatically belongs to platform org (demo)
 * 3. Can later create their own organisation
 */
class RegistrationMultiTenancyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * New user registration creates platform org membership
     */
    public function registration_creates_platform_membership()
    {
        // Create platform org first
        $platform = Organisation::factory()->platform()->create();

        // Register new user
        $response = $this->post('/register', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'region' => 'Test Region',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => 1,  // Checkbox value
        ]);

        // Debug: Check response
        if ($response->status() !== 302) {
            $this->fail('Registration failed with status ' . $response->status() . '. Response: ' . $response->getContent());
        }

        // User should exist
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);

        $user = User::where('email', 'john@example.com')->first();

        // User's current org should be platform
        $this->assertEquals($platform->id, $user->organisation_id);

        // User should have pivot record with platform (role=member)
        $this->assertDatabaseHas('user_organisation_roles', [
            'user_id' => $user->id,
            'organisation_id' => $platform->id,
            'role' => 'member',
        ]);

        // Should be redirected to verification notice (email must be verified first)
        $response->assertRedirect('/email/verify');
    }

    /**
     * @test
     * User can create their own tenant organisation
     */
    public function user_can_create_tenant_organisation()
    {
        // Register user (has platform membership)
        $user = User::factory()->create();
        $platform = Organisation::factory()->platform()->create();

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $platform->id,
            'role' => 'member',
        ]);

        // Authenticate
        $this->actingAs($user);

        // Create organisation
        $response = $this->post('/organisations', [
            'name' => 'Acme Inc',
        ]);

        // New organisation should be created
        $org = Organisation::where('name', 'Acme Inc')->first();
        $this->assertNotNull($org);
        $this->assertEquals('tenant', $org->type);
        $this->assertFalse($org->is_default);

        // User should now be owner of it
        $this->assertDatabaseHas('user_organisation_roles', [
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'owner',
        ]);

        // User's current org should switch to new org
        $user->refresh();
        $this->assertEquals($org->id, $user->organisation_id);

        // User should STILL belong to platform (support access)
        $this->assertTrue($user->belongsToOrganisation($platform->id));

        // Should redirect
        $response->assertRedirect("/organisations/{$org->id}/dashboard");
    }

    /**
     * @test
     * User model has method to check for tenant organisation
     */
    public function user_can_check_if_has_tenant_organisation()
    {
        $platform = Organisation::factory()->platform()->create();
        $user = User::factory()->create();

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $platform->id,
            'role' => 'member',
        ]);

        // No tenant org yet
        $this->assertFalse($user->hasTenantOrganisation());

        // Create tenant org
        $tenant = Organisation::factory()->tenant()->create();
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $tenant->id,
            'role' => 'owner',
        ]);

        // Now has tenant org
        $this->assertTrue($user->hasTenantOrganisation());
    }

    /**
     * @test
     * User can get their owned organisation
     */
    public function user_can_get_owned_organisation()
    {
        $platform = Organisation::factory()->platform()->create();
        $user = User::factory()->create();

        // Add to platform
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $platform->id,
            'role' => 'member',
        ]);

        // No owned org yet
        $this->assertNull($user->getOwnedOrganisation());

        // Create tenant org where they're owner
        $tenant = Organisation::factory()->tenant()->create();
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $tenant->id,
            'role' => 'owner',
        ]);

        // Should return owned org
        $owned = $user->getOwnedOrganisation();
        $this->assertNotNull($owned);
        $this->assertEquals($tenant->id, $owned->id);
    }

    /**
     * @test
     * User can switch organisations
     */
    public function user_can_switch_organisations()
    {
        $platform = Organisation::factory()->platform()->create();
        $tenant = Organisation::factory()->tenant()->create();
        $user = User::factory()->create(['organisation_id' => $platform->id]);

        // Add user to both orgs
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $platform->id,
            'role' => 'member',
        ]);
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $tenant->id,
            'role' => 'owner',
        ]);

        // Currently in platform
        $this->assertEquals($platform->id, $user->organisation_id);

        // Switch to tenant
        $user->switchToOrganisation($tenant);

        // Now in tenant
        $this->assertEquals($tenant->id, $user->organisation_id);

        // Switch back to platform
        $user->switchToOrganisation($platform);
        $this->assertEquals($platform->id, $user->organisation_id);
    }

    /**
     * @test
     * User cannot switch to organisation they don't belong to
     */
    public function user_cannot_switch_to_unowned_organisation()
    {
        $org1 = Organisation::factory()->platform()->create();
        $org2 = Organisation::factory()->tenant()->create();
        $user = User::factory()->create(['organisation_id' => $org1->id]);

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org1->id,
            'role' => 'member',
        ]);

        // Try to switch to org they don't belong to
        $this->expectException(\Exception::class);
        $user->switchToOrganisation($org2);
    }

    /**
     * @test
     * Dashboard shows demo elections when user has no tenant
     */
    public function dashboard_shows_demo_when_no_tenant()
    {
        $platform = Organisation::factory()->platform()->create();
        $user = User::factory()->create(['organisation_id' => $platform->id]);

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $platform->id,
            'role' => 'member',
        ]);

        $this->actingAs($user);

        $response = $this->get('/dashboard');

        // Should render demo dashboard
        $response->assertStatus(200);
        $response->assertViewIs('demo.dashboard');
    }

    /**
     * @test
     * Dashboard shows real elections when user has tenant
     */
    public function dashboard_shows_real_when_has_tenant()
    {
        $tenant = Organisation::factory()->tenant()->create();
        $user = User::factory()->create(['organisation_id' => $tenant->id]);

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $tenant->id,
            'role' => 'owner',
        ]);

        $this->actingAs($user);

        $response = $this->get('/dashboard');

        // Should render real dashboard
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }
}
