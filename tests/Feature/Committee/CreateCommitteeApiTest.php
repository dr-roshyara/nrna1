<?php

declare(strict_types=1);

namespace Tests\Feature\Committee;

use App\Contexts\Membership\Domain\Committee\Policies\GovernanceMatrix;
use App\Contexts\Membership\Domain\Committee\Policies\MatrixCell;
use App\Models\GovernanceLevelDefinition;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class CreateCommitteeApiTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $user;
    private int $geoUnitId = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->user = User::factory()->forOrganisation($this->org)->create();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_creates_committee_successfully(): void
    {
        $this->seedGovernanceLevels();
        $this->seedGeoUnits();

        $payload = [
            'name' => 'District Committee',
            'governanceLevel' => 2,
            'geoUnitId' => $this->geoUnitId,
        ];

        $response = $this->actingAs($this->user)
            ->postJson(
                "/organisations/{$this->org->slug}/committees/api/create-canonical",
                $payload
            );

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'committeeId'
        ]);
        $this->assertIsString($response->json('committeeId'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_rejects_invalid_governance_assignment(): void
    {
        $this->seedGovernanceLevels();
        $this->seedGeoUnits();

        $payload = [
            'name' => 'Invalid Committee',
            'governanceLevel' => 5,
            'geoUnitId' => $this->geoUnitId,
        ];

        $response = $this->actingAs($this->user)
            ->postJson(
                "/organisations/{$this->org->slug}/committees/api/create-canonical",
                $payload
            );

        $response->assertStatus(422);
        $response->assertJsonPath('message', 'Invalid governance assignment: 5 × 5 not allowed');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_rejects_missing_required_fields(): void
    {
        $payload = [
            'name' => 'Incomplete Committee',
        ];

        $response = $this->actingAs($this->user)
            ->postJson(
                "/organisations/{$this->org->slug}/committees/api/create-canonical",
                $payload
            );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['governanceLevel', 'geoUnitId']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_requires_authentication(): void
    {
        $payload = [
            'name' => 'Test Committee',
            'governanceLevel' => 2,
            'geoUnitId' => 1,
        ];

        $response = $this->postJson(
            "/organisations/{$this->org->slug}/committees/api/create-canonical",
            $payload
        );

        $response->assertUnauthorized();
    }

    private function seedGovernanceLevels(): void
    {
        GovernanceLevelDefinition::create([
            'tenant_id'       => $this->org->id,
            'level'           => 0,
            'committee_name'  => 'Global Committee',
            'committee_code'  => 'GC',
            'geo_name'        => 'World',
            'geo_code'        => 'WORLD',
            'is_active'       => true,
            'sort_order'      => 0,
            'created_by'      => (string) $this->user->id,
        ]);

        GovernanceLevelDefinition::create([
            'tenant_id'       => $this->org->id,
            'level'           => 1,
            'committee_name'  => 'Regional Committee',
            'committee_code'  => 'RC',
            'geo_name'        => 'Region',
            'geo_code'        => 'REG',
            'is_active'       => true,
            'sort_order'      => 1,
            'created_by'      => (string) $this->user->id,
        ]);

        GovernanceLevelDefinition::create([
            'tenant_id'       => $this->org->id,
            'level'           => 2,
            'committee_name'  => 'District Committee',
            'committee_code'  => 'DC',
            'geo_name'        => 'District',
            'geo_code'        => 'DIST',
            'is_active'       => true,
            'sort_order'      => 2,
            'created_by'      => (string) $this->user->id,
        ]);
    }

    private function seedGeoUnits(): void
    {
        // Insert global country first
        DB::table('countries')->insert([
            'code'         => 'XX',
            'code_alpha3'  => 'XXX',
            'code_numeric' => '000',
            'name_en'      => 'Global',
            'name_local'   => json_encode(['en' => 'Global']),
            'admin_levels' => json_encode([]),
            'is_active'    => true,
            'is_supported' => false,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        DB::table('geo_administrative_units')->insert([
            'country_code' => 'XX',
            'admin_level'  => 0,
            'admin_type'   => 'world',
            'parent_id'    => null,
            'path'         => '/',
            'code'         => 'WORLD',
            'name_local'   => json_encode(['en' => 'World']),
            'is_active'    => true,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $districtId = DB::table('geo_administrative_units')->insertGetId([
            'country_code' => 'XX',
            'admin_level'  => 2,
            'admin_type'   => 'district',
            'parent_id'    => null,
            'path'         => '/',
            'code'         => 'DIST-01',
            'name_local'   => json_encode(['en' => 'Test District']),
            'is_active'    => true,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // Store for later reference if needed
        $this->geoUnitId = $districtId;
    }
}
