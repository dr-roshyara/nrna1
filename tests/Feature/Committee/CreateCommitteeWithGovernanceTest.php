<?php

declare(strict_types=1);

namespace Tests\Feature\Committee;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateCommitteeWithGovernanceTest extends TestCase
{
    use RefreshDatabase;

    private string $orgId;
    private string $orgSlug;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orgId = \Illuminate\Support\Str::uuid()->toString();
        $this->orgSlug = 'test-org-slug';

        \Illuminate\Support\Facades\DB::table('organisations')->insert([
            'id' => $this->orgId,
            'slug' => $this->orgSlug,
            'name' => 'Test Organisation',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->seedGovernanceLevels();
        $this->seedGeoUnits();
    }

    private function seedGovernanceLevels(): void
    {
        \Illuminate\Support\Facades\DB::table('governance_level_definitions')->insert([
            [
                'tenant_id' => $this->orgId,
                'level' => 0,
                'committee_name' => 'ICC Global',
                'committee_code' => 'ICC',
                'geo_code' => 'ICC',
                'geo_name' => 'World',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $this->orgId,
                'level' => 1,
                'committee_name' => 'Regional',
                'committee_code' => 'REG',
                'geo_code' => 'REG',
                'geo_name' => 'Region',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $this->orgId,
                'level' => 2,
                'committee_name' => 'NCC',
                'committee_code' => 'NCC',
                'geo_code' => 'NCC',
                'geo_name' => 'Country',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    private function seedGeoUnits(): void
    {
        // Create global unit (Level 0)
        $globalId = \Illuminate\Support\Facades\DB::table('geo_administrative_units')->insertGetId([
            'organisation_id' => $this->orgId,
            'country_code' => null,
            'code' => 'GLOBAL-' . uniqid(),
            'admin_level' => 0,
            'admin_type' => 'world',
            'name_local' => json_encode(['en' => 'Global']),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create regional unit (Level 1)
        \Illuminate\Support\Facades\DB::table('geo_administrative_units')->insertGetId([
            'organisation_id' => $this->orgId,
            'country_code' => null,
            'code' => 'REGION-' . uniqid(),
            'admin_level' => 1,
            'admin_type' => 'region',
            'name_local' => json_encode(['en' => 'Region One']),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create country unit (Level 2)
        \Illuminate\Support\Facades\DB::table('geo_administrative_units')->insertGetId([
            'organisation_id' => $this->orgId,
            'country_code' => null,
            'code' => 'COUNTRY-' . uniqid(),
            'admin_level' => 2,
            'admin_type' => 'country',
            'name_local' => json_encode(['en' => 'Country One']),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_create_returns_governance_levels_and_geo_units(): void
    {
        $user = $this->createUserWithOrganisation($this->orgId);
        $response = $this->actingAs($user)
            ->get("/organisations/{$this->orgSlug}/committees/create");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Committee/Create')
                ->has('governanceLevels', 3)
                ->has('geoUnits')
        );
    }

    public function test_store_requires_governance_level_and_geo_unit_id(): void
    {
        $user = $this->createUserWithOrganisation($this->orgId);
        $response = $this->actingAs($user)
            ->post("/organisations/{$this->orgSlug}/committees", [
                'name' => 'Test Committee',
                'code' => 'TEST-001',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['governanceLevel', 'geoUnitId']);
    }

    public function test_store_accepts_governance_level_and_geo_unit_id(): void
    {
        $user = $this->createUserWithOrganisation($this->orgId);
        $response = $this->actingAs($user)
            ->post("/organisations/{$this->orgSlug}/committees", [
                'name' => 'ICC Committee',
                'code' => 'ICC-001',
                'governanceLevel' => 0,
                'geoUnitId' => 1,
            ]);

        $response->assertStatus(302);
        \Illuminate\Support\Facades\DB::table('committees')
            ->where('code', 'ICC-001')
            ->where('organisation_id', $this->orgId)
            ->firstOrFail();
    }

    public function test_store_rejects_invalid_governance_assignment(): void
    {
        $user = $this->createUserWithOrganisation($this->orgId);

        $response = $this->actingAs($user)
            ->post("/organisations/{$this->orgSlug}/committees", [
                'name' => 'Invalid Committee',
                'code' => 'INVALID-001',
                'governanceLevel' => 999,
                'geoUnitId' => 1,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    public function test_store_creates_committee_with_governance_assignment(): void
    {
        $user = $this->createUserWithOrganisation($this->orgId);
        $response = $this->actingAs($user)
            ->post("/organisations/{$this->orgSlug}/committees", [
                'name' => 'Regional Committee',
                'code' => 'REG-001',
                'governanceLevel' => 1,
                'geoUnitId' => 2,
            ]);

        $response->assertStatus(302);
        $committee = \Illuminate\Support\Facades\DB::table('committees')
            ->where('code', 'REG-001')
            ->where('organisation_id', $this->orgId)
            ->firstOrFail();

        $this->assertEquals(1, $committee->level);
        $this->assertEquals(2, $committee->operational_geo);
    }

    private function createUserWithOrganisation(string $orgId)
    {
        $user = \App\Models\User::factory()->create();
        \Illuminate\Support\Facades\DB::table('user_organisation_roles')->insert([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'user_id' => $user->id,
            'organisation_id' => $orgId,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return $user;
    }
}
