<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use App\Services\DashboardResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class RegistrationAndLoginTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $platformOrg;

    protected function setUp(): void
    {
        parent::setUp();

        // Get or create the platform organisation (created by migration)
        $this->platformOrg = Organisation::firstOrCreate(
            ['slug' => 'publicdigit'],
            [
                'name' => 'Public Digit',
                'type' => 'platform',
                'is_default' => true,
            ]
        );
    }

    /** @test */
    public function user_factory_assigns_platform_organisation_by_default()
    {
        $user = User::factory()->create();

        $this->assertEquals(
            $this->platformOrg->id,
            $user->organisation_id,
            'User factory should assign platform organisation'
        );
    }

    /** @test */
    public function user_can_access_publicdigit_organisation()
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

        $response = $this->actingAs($user)
            ->get('/organisations/publicdigit');

        $response->assertOk();
    }

    /** @test */
    public function user_not_member_of_org_gets_403()
    {
        $user = User::factory()->create();
        $otherOrg = Organisation::factory()->create(['slug' => 'testorg']);

        $response = $this->actingAs($user)
            ->get('/organisations/' . $otherOrg->slug);

        $response->assertStatus(403);
    }

    /** @test */
    public function registration_creates_pivot_record()
    {
        $response = $this->post('/register', [
            'firstName' => 'Test',
            'lastName' => 'User',
            'email' => 'testuser@example.com',
            'region' => 'Bayern',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
            'terms' => true,
        ]);

        $user = User::where('email', 'testuser@example.com')->first();
        $this->assertNotNull($user);

        $this->assertTrue(
            $user->belongsToOrganisation($this->platformOrg->id),
            'User should belong to platform organisation after registration'
        );
    }

    /** @test */
    public function registered_user_can_access_publicdigit_after_email_verification()
    {
        $this->post('/register', [
            'firstName' => 'Test',
            'lastName' => 'Access',
            'email' => 'testaccess@example.com',
            'region' => 'Bayern',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
            'terms' => true,
        ]);

        $user = User::where('email', 'testaccess@example.com')->first();

        // Simulate email verification
        $user->update(['email_verified_at' => now()]);

        $response = $this->actingAs($user)
            ->get('/organisations/publicdigit');

        $response->assertOk();
    }

    /** @test */
    public function new_user_dashboard_resolves_to_welcome()
    {
        // Create new user (verified email, not onboarded)
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => null,
        ]);

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->platformOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $resolver = app(DashboardResolver::class);
        $response = $resolver->resolve($user);

        $this->assertStringContainsString(
            'welcome',
            $response->getTargetUrl(),
            'New unboarded user should go to welcome page'
        );
    }

    /** @test */
    public function user_with_stale_org_id_uses_platform_via_pivot()
    {
        // Create a dummy tenant org
        $tenantOrg = Organisation::factory()->create();

        // Create user with stale org_id (points to tenant, but no pivot)
        $user = User::factory()->create([
            'organisation_id' => $tenantOrg->id, // Stale!
        ]);

        // Only has pivot for platform
        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->platformOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // belongsToOrganisation should reflect pivot truth
        $this->assertTrue($user->belongsToOrganisation($this->platformOrg->id));
        $this->assertFalse($user->belongsToOrganisation($tenantOrg->id));
    }

    /** @test */
    public function platform_user_not_onboarded_redirects_to_welcome()
    {
        $user = User::factory()->create([
            'organisation_id' => $this->platformOrg->id,
            'email_verified_at' => now(),
            'onboarded_at' => null,
        ]);

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->platformOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $resolver = app(DashboardResolver::class);
        $response = $resolver->resolve($user);

        $this->assertStringContainsString(
            'welcome',
            $response->getTargetUrl(),
            'Platform user not onboarded should go to welcome'
        );
    }

    /** @test */
    public function onboarded_platform_user_redirects_to_dashboard()
    {
        $user = User::factory()->create([
            'organisation_id' => $this->platformOrg->id,
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        DB::table('user_organisation_roles')->insertOrIgnore([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->platformOrg->id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $resolver = app(DashboardResolver::class);
        $response = $resolver->resolve($user);

        $this->assertStringContainsString(
            'dashboard',
            $response->getTargetUrl(),
            'Onboarded platform user should go to dashboard'
        );
        $this->assertStringNotContainsString('welcome', $response->getTargetUrl());
    }

    /** @test */
    public function user_with_multiple_orgs_has_correct_memberships()
    {
        $user = User::factory()->create();
        $tenantOrg = Organisation::factory()->create();

        // User belongs to both platform and tenant
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

        $this->assertTrue($user->belongsToOrganisation($this->platformOrg->id));
        $this->assertTrue($user->belongsToOrganisation($tenantOrg->id));
    }
}
