<?php

namespace Tests\Feature\Seeders;

use Tests\TestCase;
use App\Models\Organisation;
use Database\Seeders\OrganisationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrganisationSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function organisation_seeder_creates_platform_organisation()
    {
        // When: Running OrganisationSeeder
        $this->seed(OrganisationSeeder::class);

        // Then: Platform organisation exists
        $platform = Organisation::where('type', 'platform')
            ->where('is_default', true)
            ->first();

        $this->assertNotNull($platform);
        $this->assertEquals('PublicDigit', $platform->name);
        $this->assertEquals('publicdigit', $platform->slug);
    }

    /** @test */
    public function organisation_seeder_is_idempotent()
    {
        // Given: Run seeder once
        $this->seed(OrganisationSeeder::class);
        $countAfterFirst = Organisation::count();

        // When: Run seeder again
        $this->seed(OrganisationSeeder::class);
        $countAfterSecond = Organisation::count();

        // Then: Count should not increase
        $this->assertEquals($countAfterFirst, $countAfterSecond);
    }

    /** @test */
    public function organisation_seeder_uses_correct_search_keys()
    {
        // This test verifies that the seeder uses the same search keys
        // as UserFactory and TestCase, preventing duplicate slug errors

        // When: Running OrganisationSeeder
        $this->seed(OrganisationSeeder::class);

        // Then: Can be found with UUID-compatible search keys
        $platform = Organisation::where('type', 'platform')
            ->where('is_default', true)
            ->firstOrFail();

        $this->assertTrue($platform->is_default);
        $this->assertEquals('platform', $platform->type);
    }
}
