<?php

namespace Tests\Feature\Seeders;

use Tests\TestCase;
use App\Models\Election;
use App\Models\Organisation;
use Database\Seeders\OrganisationSeeder;
use Database\Seeders\ElectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ElectionSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function election_seeder_creates_demo_election_with_platform_org()
    {
        // Given: Platform organisation exists
        $this->seed(OrganisationSeeder::class);
        $platform = Organisation::getDefaultPlatform();

        // When: Running ElectionSeeder
        $this->seed(ElectionSeeder::class);

        // Then: Demo election exists with correct platform org
        $demoElection = Election::where('slug', 'demo-election')
            ->withoutGlobalScopes()
            ->first();

        $this->assertNotNull($demoElection);
        $this->assertEquals($platform->id, $demoElection->organisation_id);
        $this->assertEquals('demo', $demoElection->type);
        $this->assertTrue($demoElection->is_active);
    }

    /** @test */
    public function election_seeder_creates_real_election_with_platform_org()
    {
        // Given: Platform organisation exists
        $this->seed(OrganisationSeeder::class);
        $platform = Organisation::getDefaultPlatform();

        // When: Running ElectionSeeder
        $this->seed(ElectionSeeder::class);

        // Then: Real election exists with correct platform org
        $realElection = Election::where('slug', '2024-general-election')
            ->withoutGlobalScopes()
            ->first();

        $this->assertNotNull($realElection);
        $this->assertEquals($platform->id, $realElection->organisation_id);
        $this->assertEquals('real', $realElection->type);
        $this->assertTrue($realElection->is_active);
    }

    /** @test */
    public function election_seeder_is_idempotent()
    {
        // Given: Platform organisation exists
        $this->seed(OrganisationSeeder::class);

        // When: Run seeder once
        $this->seed(ElectionSeeder::class);
        $countAfterFirst = Election::withoutGlobalScopes()->count();

        // And: Run seeder again
        $this->seed(ElectionSeeder::class);
        $countAfterSecond = Election::withoutGlobalScopes()->count();

        // Then: Count should not increase
        $this->assertEquals($countAfterFirst, $countAfterSecond);
    }

    /** @test */
    public function election_seeder_uses_platform_org_uuid()
    {
        // Given: Platform organisation with UUID exists
        $this->seed(OrganisationSeeder::class);
        $platform = Organisation::getDefaultPlatform();

        // When: Running ElectionSeeder
        $this->seed(ElectionSeeder::class);

        // Then: Elections use platform org UUID (not hardcoded 1)
        $demoElection = Election::where('slug', 'demo-election')
            ->withoutGlobalScopes()
            ->first();

        // Verify it's a UUID, not integer 1
        $this->assertEquals($platform->id, $demoElection->organisation_id);
        $this->assertNotEquals(1, $demoElection->organisation_id);
    }
}
