<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\GovernanceLevelDefinition;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class GeoUnitControllerTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->user = User::factory()->forOrganisation($this->org)->create();
        // UserOrganisationRole auto-created by UserFactory.configure()
    }

    // ──────────────────────────────────────────────────────────────
    // Index
    // ──────────────────────────────────────────────────────────────

    #[\PHPUnit\Framework\Attributes\Test]
    public function index_returns_geo_units_filtered_by_governance_levels(): void
    {
        $this->seedGovernanceLevels();
        $this->seedGeoUnits();

        $response = $this->actingAs($this->user)
            ->getJson("/organisations/{$this->org->slug}/geo/units/api");

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'code', 'name', 'admin_level', 'admin_type',
                    'parent_id', 'path', 'children_count', 'is_active',
                ],
            ],
        ]);

        // Should include geo units at governance-relevant levels
        $units = $response->json('data');
        $codes = array_column($units, 'code');
        $this->assertContains('CONT-ASIA', $codes);
        $this->assertContains('COUNTRY-NP', $codes);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function index_returns_only_enabled_levels_from_tenant_profile(): void
    {
        // Governance levels only cover levels 0-2, level 3 should be excluded
        $this->seedGovernanceLevels();
        $this->seedGeoUnits();

        $response = $this->actingAs($this->user)
            ->getJson("/organisations/{$this->org->slug}/geo/units/api");

        $units = $response->json('data');
        $levels = array_unique(array_column($units, 'admin_level'));
        $this->assertNotEmpty($levels);
        // Level 3 (ward) should NOT appear since governance only covers 0-2
        $this->assertNotContains(3, $levels);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function index_returns_units_ordered_by_materialized_path(): void
    {
        $this->seedGovernanceLevels();
        $this->seedGeoUnits();

        $response = $this->actingAs($this->user)
            ->getJson("/organisations/{$this->org->slug}/geo/units/api");

        $units = $response->json('data');
        // Path ordering: root first, then children in path order
        $paths = array_column($units, 'path');
        for ($i = 1; $i < count($paths); $i++) {
            $this->assertGreaterThanOrEqual($paths[$i - 1], $paths[$i]);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function index_respects_tenant_isolation(): void
    {
        $this->seedGovernanceLevels();
        $this->seedGeoUnits();

        // Create a second org with its own user and NO geo units
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);
        $otherUser = User::factory()->forOrganisation($otherOrg)->create();

        $response = $this->actingAs($otherUser)
            ->getJson("/organisations/{$otherOrg->slug}/geo/units/api");

        $response->assertOk();
        $this->assertCount(0, $response->json('data'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function index_returns_empty_when_no_governance_levels_defined(): void
    {
        // Seed geo units but no governance level definitions
        $this->seedGeoUnits();

        $response = $this->actingAs($this->user)
            ->getJson("/organisations/{$this->org->slug}/geo/units/api");

        $response->assertOk();
        $this->assertCount(0, $response->json('data'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function index_can_filter_by_admin_level(): void
    {
        $this->seedGovernanceLevels();
        $this->seedGeoUnits();

        $response = $this->actingAs($this->user)
            ->getJson("/organisations/{$this->org->slug}/geo/units/api?level=0");

        $response->assertOk();
        $units = $response->json('data');
        $this->assertNotEmpty($units);
        foreach ($units as $unit) {
            $this->assertEquals(0, $unit['admin_level']);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function index_can_search_by_name_or_code(): void
    {
        $this->seedGovernanceLevels();
        $this->seedGeoUnits();

        // Search by code
        $response = $this->actingAs($this->user)
            ->getJson("/organisations/{$this->org->slug}/geo/units/api?search=CONT-ASIA");

        $response->assertOk();
        $units = $response->json('data');
        $this->assertNotEmpty($units);
        $this->assertEquals('CONT-ASIA', $units[0]['code']);

        // Search by name (partial match)
        $response = $this->actingAs($this->user)
            ->getJson("/organisations/{$this->org->slug}/geo/units/api?search=Asia");

        $response->assertOk();
        $units = $response->json('data');
        $this->assertNotEmpty($units);
    }

    // ──────────────────────────────────────────────────────────────
    // Show
    // ──────────────────────────────────────────────────────────────

    #[\PHPUnit\Framework\Attributes\Test]
    public function show_returns_single_geo_unit(): void
    {
        $this->seedGovernanceLevels();
        $this->seedGeoUnits();

        $asiaId = DB::table('geo_administrative_units')
            ->where('code', 'CONT-ASIA')
            ->value('id');

        $response = $this->actingAs($this->user)
            ->getJson("/organisations/{$this->org->slug}/geo/units/api/{$asiaId}");

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id', 'code', 'name', 'admin_level', 'admin_type',
                'parent_id', 'path', 'children_count', 'is_active',
            ],
        ]);
        $this->assertEquals('CONT-ASIA', $response->json('data.code'));
        $this->assertArrayHasKey('breadcrumb', $response->json('data'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function show_returns_404_for_nonexistent_unit(): void
    {
        $this->seedGovernanceLevels();
        $this->seedGeoUnits();

        $response = $this->actingAs($this->user)
            ->getJson("/organisations/{$this->org->slug}/geo/units/api/99999");

        $response->assertNotFound();
    }

    // ──────────────────────────────────────────────────────────────
    // Lookup
    // ──────────────────────────────────────────────────────────────

    #[\PHPUnit\Framework\Attributes\Test]
    public function lookup_returns_search_results_for_cascader(): void
    {
        $this->seedGovernanceLevels();
        $this->seedGeoUnits();

        $response = $this->actingAs($this->user)
            ->getJson("/organisations/{$this->org->slug}/geo/units/api/lookup?q=Nepal");

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'code', 'name', 'admin_level', 'admin_type', 'full_path'],
            ],
        ]);
        $this->assertNotEmpty($response->json('data'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function lookup_returns_empty_when_no_match(): void
    {
        $this->seedGovernanceLevels();
        $this->seedGeoUnits();

        $response = $this->actingAs($this->user)
            ->getJson("/organisations/{$this->org->slug}/geo/units/api/lookup?q=NonExistentPlace");

        $response->assertOk();
        $this->assertCount(0, $response->json('data'));
    }

    // ──────────────────────────────────────────────────────────────
    // Auth
    // ──────────────────────────────────────────────────────────────

    #[\PHPUnit\Framework\Attributes\Test]
    public function unauthenticated_user_gets_redirected(): void
    {
        $response = $this->getJson("/organisations/{$this->org->slug}/geo/units/api");
        $response->assertStatus(302);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function non_member_gets_forbidden(): void
    {
        $nonMember = User::factory()->create();

        $response = $this->actingAs($nonMember)
            ->getJson("/organisations/{$this->org->slug}/geo/units/api");

        $response->assertForbidden();
    }

    // ──────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────

    private function seedGovernanceLevels(): void
    {
        GovernanceLevelDefinition::create([
            'tenant_id'       => $this->org->id,
            'level'           => 0,
            'committee_name'  => 'Global Committee',
            'committee_code'  => 'GC',
            'geo_name'        => 'Continent',
            'geo_code'        => 'CONT',
            'is_active'       => true,
            'sort_order'      => 0,
            'created_by'      => (string) $this->user->id,
        ]);

        GovernanceLevelDefinition::create([
            'tenant_id'       => $this->org->id,
            'level'           => 1,
            'committee_name'  => 'Continent Committee',
            'committee_code'  => 'CC',
            'geo_name'        => 'Country',
            'geo_code'        => 'COUNTRY',
            'geo_parent_code' => 'CONT',
            'is_active'       => true,
            'sort_order'      => 1,
            'created_by'      => (string) $this->user->id,
        ]);

        GovernanceLevelDefinition::create([
            'tenant_id'       => $this->org->id,
            'level'           => 2,
            'committee_name'  => 'Country Committee',
            'committee_code'  => 'CTRC',
            'geo_name'        => 'Province/State',
            'geo_code'        => 'PROV',
            'geo_parent_code' => 'COUNTRY',
            'is_active'       => true,
            'sort_order'      => 2,
            'created_by'      => (string) $this->user->id,
        ]);
    }

    private function seedGeoUnits(): void
    {
        // Level 0: Continents
        $worldId = DB::table('geo_administrative_units')->insertGetId([
            'country_code' => 'XX',
            'admin_level'  => 0,
            'admin_type'   => 'world',
            'parent_id'    => null,
            'path'         => '/',
            'code'         => 'WORLD',
            'name_local'   => json_encode(['en' => 'World']),
            'is_active'    => true,
            'is_official'  => true,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $asiaId = DB::table('geo_administrative_units')->insertGetId([
            'country_code' => 'XX',
            'admin_level'  => 0,
            'admin_type'   => 'continent',
            'parent_id'    => $worldId,
            'path'         => "/{$worldId}/",
            'code'         => 'CONT-ASIA',
            'name_local'   => json_encode(['en' => 'Asia']),
            'is_active'    => true,
            'is_official'  => true,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $europeId = DB::table('geo_administrative_units')->insertGetId([
            'country_code' => 'XX',
            'admin_level'  => 0,
            'admin_type'   => 'continent',
            'parent_id'    => $worldId,
            'path'         => "/{$worldId}/",
            'code'         => 'CONT-EU',
            'name_local'   => json_encode(['en' => 'Europe', 'de' => 'Europa']),
            'is_active'    => true,
            'is_official'  => true,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // Level 1: Countries
        $nepalId = DB::table('geo_administrative_units')->insertGetId([
            'country_code' => 'NP',
            'admin_level'  => 1,
            'admin_type'   => 'country',
            'parent_id'    => $asiaId,
            'path'         => "/{$worldId}/{$asiaId}/",
            'code'         => 'COUNTRY-NP',
            'name_local'   => json_encode(['en' => 'Nepal', 'np' => 'नेपाल']),
            'is_active'    => true,
            'is_official'  => true,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        DB::table('geo_administrative_units')->insertGetId([
            'country_code' => 'DE',
            'admin_level'  => 1,
            'admin_type'   => 'country',
            'parent_id'    => $europeId,
            'path'         => "/{$worldId}/{$europeId}/",
            'code'         => 'COUNTRY-DE',
            'name_local'   => json_encode(['en' => 'Germany', 'de' => 'Deutschland']),
            'is_active'    => true,
            'is_official'  => true,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // Level 2: Province
        DB::table('geo_administrative_units')->insertGetId([
            'country_code' => 'NP',
            'admin_level'  => 2,
            'admin_type'   => 'province',
            'parent_id'    => $nepalId,
            'path'         => "/{$worldId}/{$asiaId}/{$nepalId}/",
            'code'         => 'PROV-1',
            'name_local'   => json_encode(['en' => 'Province 1', 'np' => 'प्रदेश १']),
            'is_active'    => true,
            'is_official'  => true,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // Level 3: Ward (outside governance levels, should NOT appear)
        DB::table('geo_administrative_units')->insertGetId([
            'country_code' => 'NP',
            'admin_level'  => 3,
            'admin_type'   => 'ward',
            'parent_id'    => $nepalId,
            'path'         => "/{$worldId}/{$asiaId}/{$nepalId}/",
            'code'         => 'WARD-1',
            'name_local'   => json_encode(['en' => 'Ward 1']),
            'is_active'    => true,
            'is_official'  => false,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
}
