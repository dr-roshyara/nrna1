<?php

declare(strict_types=1);

namespace Tests\Feature\Committee;

use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class CommitteeCreationWithGeoSelectionsTest extends TestCase
{
    private User $user;
    private Organisation $organisation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        $this->user = User::factory()->create();

        DB::table('user_organisation_roles')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'user_id' => $this->user->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->seedGovernanceLevels();
        $this->seedGeoUnits();
    }

    private function seedGovernanceLevels(): void
    {
        for ($level = 0; $level <= 5; $level++) {
            DB::table('governance_level_definitions')->updateOrInsert(
                ['tenant_id' => $this->organisation->id, 'level' => $level],
                [
                    'tenant_id' => $this->organisation->id,
                    'level' => $level,
                    'committee_name' => "Level $level Committee",
                    'committee_code' => "LEVEL_$level",
                    'geo_name' => "Level $level Geo",
                    'geo_code' => "GEO_$level",
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    private function seedGeoUnits(): void
    {
        DB::table('countries')->updateOrInsert(
            ['code' => 'NP'],
            [
                'code_alpha3' => 'NPL',
                'code_numeric' => '524',
                'name_en' => 'Nepal',
                'name_local' => json_encode(['en' => 'Nepal']),
                'admin_levels' => json_encode([0, 1]),
                'is_active' => true,
                'is_supported' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('geo_administrative_units')->updateOrInsert(
            ['code' => 'NP', 'admin_level' => 0],
            [
                'country_code' => 'NP',
                'admin_level' => 0,
                'admin_type' => 'country',
                'parent_id' => null,
                'name_local' => json_encode(['en' => 'Nepal']),
                'code' => 'NP',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $countryId = DB::table('geo_administrative_units')
            ->where('code', 'NP')
            ->where('admin_level', 0)
            ->value('id');

        DB::table('geo_administrative_units')->updateOrInsert(
            ['code' => 'PROV1'],
            [
                'country_code' => 'NP',
                'admin_level' => 1,
                'admin_type' => 'province',
                'parent_id' => $countryId,
                'name_local' => json_encode(['en' => 'Province 1']),
                'code' => 'PROV1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function assertCommitteeCreated(string $name): CommitteeModel
    {
        $committeeRaw = CommitteeModel::withoutGlobalScopes()
            ->where('organisation_id', $this->organisation->id)
            ->where('name', $name)
            ->first();

        $this->assertNotNull($committeeRaw, "Committee '{$name}' not found in database");

        $committeeScoped = CommitteeModel::where('name', $name)->first();
        $this->assertNotNull($committeeScoped, "Committee exists but filtered by tenant scope");

        return $committeeRaw;
    }

    public function test_create_committee_with_complete_geo_selections(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->withHeader('X-Tenant-Id', $this->organisation->id)
            ->actingAs($this->user)
            ->post(route('committees.store', $this->organisation), [
                'name' => 'Asia Region Committee',
                'code' => 'ASIA_REG',
                'type' => 'central',
                'governanceLevel' => 1,
                'geoUnitId' => 1,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $committee = $this->assertCommitteeCreated('Asia Region Committee');
        $this->assertEquals('ASIA_REG', $committee->code);
        $this->assertEquals('central', $committee->type);
    }

    public function test_create_committee_with_region_and_country_only(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->withHeader('X-Tenant-Id', $this->organisation->id)
            ->actingAs($this->user)
            ->post(route('committees.store', $this->organisation), [
                'name' => 'Regional Committee Nepal',
                'code' => 'REG_NP',
                'type' => 'central',
                'governanceLevel' => 1,
                'geoUnitId' => 1,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $committee = $this->assertCommitteeCreated('Regional Committee Nepal');
        $this->assertEquals('REG_NP', $committee->code);
    }

public function test_create_committee_with_invalid_type(): void
    {
        $response = $this->withHeader('X-Tenant-Id', $this->organisation->id)
            ->actingAs($this->user)
            ->post(route('committees.store', $this->organisation), [
                'name' => 'Invalid Type Committee',
                'code' => 'INVALID_TYPE',
                'type' => 'nonexistent_type',
                'governanceLevel' => 1,
                'geoUnitId' => 1,
            ]);

        $response->assertSessionHasErrors(['type']);
        $this->assertDatabaseMissing('committees', [
            'organisation_id' => $this->organisation->id,
            'code' => 'INVALID_TYPE',
        ]);
    }

    public function test_create_committee_with_missing_name(): void
    {
        $response = $this->withHeader('X-Tenant-Id', $this->organisation->id)
            ->actingAs($this->user)
            ->post(route('committees.store', $this->organisation), [
                'name' => '',
                'code' => 'MISSING_NAME',
                'type' => 'central',
                'governanceLevel' => 1,
                'geoUnitId' => 1,
            ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('committees', [
            'organisation_id' => $this->organisation->id,
            'code' => 'MISSING_NAME',
        ]);
    }

    public function test_create_committee_with_missing_type(): void
    {
        $response = $this->withHeader('X-Tenant-Id', $this->organisation->id)
            ->actingAs($this->user)
            ->post(route('committees.store', $this->organisation), [
                'name' => 'Missing Type Committee',
                'code' => 'MISSING_TYPE',
                'type' => null,
                'governanceLevel' => 1,
                'geoUnitId' => 1,
            ]);

        $response->assertSessionHasErrors(['type']);
        $this->assertDatabaseMissing('committees', [
            'organisation_id' => $this->organisation->id,
            'code' => 'MISSING_TYPE',
        ]);
    }

    public function test_create_committee_with_duplicate_code(): void
    {
        CommitteeModel::factory()->create([
            'organisation_id' => $this->organisation->id,
            'code' => 'DUPLICATE',
        ]);

        $response = $this->withHeader('X-Tenant-Id', $this->organisation->id)
            ->actingAs($this->user)
            ->post(route('committees.store', $this->organisation), [
                'name' => 'Duplicate Code Committee',
                'code' => 'DUPLICATE',
                'type' => 'central',
                'governanceLevel' => 1,
                'geoUnitId' => 1,
            ]);

        $response->assertSessionHasErrors(['code']);
        $this->assertEquals(1, CommitteeModel::where('code', 'DUPLICATE')->count());
    }

    public function test_create_committee_with_very_long_name(): void
    {
        $veryLongName = str_repeat('A', 300);

        $response = $this->withHeader('X-Tenant-Id', $this->organisation->id)
            ->actingAs($this->user)
            ->post(route('committees.store', $this->organisation), [
                'name' => $veryLongName,
                'code' => 'LONG_NAME',
                'type' => 'central',
                'governanceLevel' => 1,
                'geoUnitId' => 1,
            ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('committees', [
            'organisation_id' => $this->organisation->id,
            'code' => 'LONG_NAME',
        ]);
    }

    public function test_create_committee_with_slug_generation(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->withHeader('X-Tenant-Id', $this->organisation->id)
            ->actingAs($this->user)
            ->post(route('committees.store', $this->organisation), [
                'name' => 'Committee With Spaces',
                'code' => 'SLUG_TEST',
                'type' => 'central',
                'governanceLevel' => 1,
                'geoUnitId' => 1,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $committee = $this->assertCommitteeCreated('Committee With Spaces');
        $this->assertEquals('committee-with-spaces', $committee->slug);
        $this->assertEquals('SLUG_TEST', $committee->code);
    }
}
