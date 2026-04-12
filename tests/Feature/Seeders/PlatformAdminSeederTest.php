<?php

namespace Tests\Feature\Seeders;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use Database\Seeders\OrganisationSeeder;
use Database\Seeders\PlatformAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class PlatformAdminSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to seed OrganisationSeeder (required dependency)
     */
    protected function seedDependencies(): void
    {
        $this->seed(OrganisationSeeder::class);
    }

    /** @test */
    public function platform_admin_seeder_creates_admin_user()
    {
        // Given: Platform organisation exists
        $this->seedDependencies();
        $platform = Organisation::getDefaultPlatform();

        // When: Running PlatformAdminSeeder
        $this->seed(PlatformAdminSeeder::class);

        // Then: Admin user exists with correct attributes
        $admin = User::where('email', 'admin@publicdigit.org')->first();
        $this->assertNotNull($admin);
        $this->assertEquals('Platform Admin', $admin->name);
        $this->assertEquals($platform->id, $admin->organisation_id);
        $this->assertNotNull($admin->email_verified_at);
        $this->assertTrue(Hash::check('password', $admin->password));
        $this->assertEquals('admin@publicdigit.org', $admin->email);
    }

    /** @test */
    public function platform_admin_seeder_creates_pivot_record()
    {
        // Given: Platform organisation exists
        $this->seedDependencies();
        $platform = Organisation::getDefaultPlatform();

        // When: Running PlatformAdminSeeder
        $this->seed(PlatformAdminSeeder::class);

        // Then: Pivot record exists with admin role
        $admin = User::where('email', 'admin@publicdigit.org')->first();
        $pivot = UserOrganisationRole::where('user_id', $admin->id)
            ->where('organisation_id', $platform->id)
            ->first();

        $this->assertNotNull($pivot);
        $this->assertEquals('admin', $pivot->role);
    }

    /** @test */
    public function platform_admin_seeder_is_idempotent()
    {
        // Given: Platform organisation exists
        $this->seedDependencies();

        // When: Run seeder once
        $this->seed(PlatformAdminSeeder::class);
        $countAfterFirst = User::count();

        // And: Run seeder again
        $this->seed(PlatformAdminSeeder::class);
        $countAfterSecond = User::count();

        // Then: Count should not increase
        $this->assertEquals($countAfterFirst, $countAfterSecond);
    }

    /** @test */
    public function platform_admin_seeder_fails_without_platform_org()
    {
        // When: Running seeder without platform org
        // Then: Should throw exception

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->seed(PlatformAdminSeeder::class);
    }
}
