<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Geography\Infrastructure;

use App\Contexts\Geography\Domain\Entities\GeoAdministrativeUnit;
use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use App\Contexts\Geography\Domain\ValueObjects\GeographyLevel;
use App\Contexts\Geography\Domain\ValueObjects\GeoPath;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use App\Contexts\Geography\Domain\ValueObjects\LocalizedName;
use App\Contexts\Geography\Domain\ValueObjects\GeographicCode;
use App\Contexts\Geography\Infrastructure\Exceptions\ConcurrencyException;
use App\Contexts\Geography\Infrastructure\Repositories\EloquentGeoUnitRepository;
use App\Contexts\Geography\Domain\Models\GeoAdministrativeUnit as GeoAdministrativeUnitModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class EloquentGeoUnitRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentGeoUnitRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('countries')->insert([
            'code' => 'NP',
            'code_alpha3' => 'NPL',
            'code_numeric' => '524',
            'name_en' => 'Nepal',
            'name_local' => json_encode(['np' => 'नेपाल']),
            'admin_levels' => json_encode([
                1 => ['name' => 'Province', 'count' => 7],
                2 => ['name' => 'District', 'count' => 77],
                3 => ['name' => 'Local Level', 'count' => 753],
                4 => ['name' => 'Ward', 'count' => 6743],
            ]),
            'is_active' => true,
            'is_supported' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->repository = new EloquentGeoUnitRepository();
    }

    private function makeRootUnit(int $id, string $code = 'NP-P1'): GeoAdministrativeUnit
    {
        return GeoAdministrativeUnit::createRoot(
            GeoUnitId::fromInt($id),
            CountryCode::fromString('NP'),
            LocalizedName::fromArray(['en' => 'Province 1', 'np' => 'प्रदेश १']),
            GeographicCode::fromString($code)
        );
    }

    // ── save(): INSERT ──

    public function test_save_creates_new_unit(): void
    {
        $unit = $this->makeRootUnit(100, 'NP-P1');
        $this->repository->save($unit);

        $this->assertDatabaseHas('geo_administrative_units', [
            'id' => 100,
            'country_code' => 'NP',
            'admin_level' => 1,
            'code' => 'NP-P1',
            'version' => 1,
        ]);
    }

    public function test_save_creates_unit_and_can_fetch_back(): void
    {
        $unit = $this->makeRootUnit(200, 'NP-P1');
        $this->repository->save($unit);

        $fetched = $this->repository->findById(GeoUnitId::fromInt(200));

        $this->assertNotNull($fetched);
        $this->assertSame(200, $fetched->getId()->toInt());
        $this->assertSame('NP', $fetched->getCountryCode()->toString());
        $this->assertSame(1, $fetched->getLevel()->toInt());
        $this->assertSame(1, $fetched->getVersion());
    }

    // ── save(): UPDATE with optimistic locking ──

    public function test_save_updates_existing_unit_and_increments_version(): void
    {
        $unit = $this->makeRootUnit(300, 'NP-P1');
        $this->repository->save($unit);

        // Fetch back, modify, save
        $fetched = $this->repository->findById(GeoUnitId::fromInt(300));
        $this->assertNotNull($fetched);
        $this->assertSame(1, $fetched->getVersion());

        $fetched->updateName(LocalizedName::english('Province 1 Updated'));
        $this->repository->save($fetched);

        // Verify version incremented
        $reFetched = $this->repository->findById(GeoUnitId::fromInt(300));
        $this->assertNotNull($reFetched);
        $this->assertSame(2, $reFetched->getVersion());
    }

    public function test_save_throws_concurrency_exception_on_version_mismatch(): void
    {
        $unit = $this->makeRootUnit(400, 'NP-P1');
        $this->repository->save($unit);

        // Simulate concurrent update: increment version behind repository's back
        DB::table('geo_administrative_units')
            ->where('id', 400)
            ->update(['version' => 5]);

        // The entity still has version=1, try to save → should fail
        $this->expectException(ConcurrencyException::class);
        $this->expectExceptionMessage('version mismatch');
        $this->repository->save($unit);
    }

    // ── save(): INSERT child unit ──

    public function test_save_creates_child_unit_with_correct_hierarchy(): void
    {
        // Create and save parent
        $parent = $this->makeRootUnit(500, 'NP-P1');
        $this->repository->save($parent);

        // Create child
        $child = GeoAdministrativeUnit::createChild(
            GeoUnitId::fromInt(501),
            CountryCode::fromString('NP'),
            GeographyLevel::fromInt(2),
            $parent,
            LocalizedName::english('District A'),
            GeographicCode::fromString('NP-DIST-A')
        );

        $this->repository->save($child);

        $this->assertDatabaseHas('geo_administrative_units', [
            'id' => 501,
            'parent_id' => 500,
            'admin_level' => 2,
            'code' => 'NP-DIST-A',
            'version' => 1,
        ]);
    }

    // ── delete() ──

    public function test_delete_removes_unit(): void
    {
        $unit = $this->makeRootUnit(600, 'NP-P1');
        $this->repository->save($unit);

        $this->repository->delete(GeoUnitId::fromInt(600));

        $fetched = $this->repository->findById(GeoUnitId::fromInt(600));
        $this->assertNull($fetched);
    }

    // ── findByCode() ──

    public function test_find_by_code_returns_unit(): void
    {
        $unit = $this->makeRootUnit(700, 'NP-P1');
        $this->repository->save($unit);

        $fetched = $this->repository->findByCode(
            CountryCode::fromString('NP'),
            'NP-P1'
        );

        $this->assertNotNull($fetched);
        $this->assertSame(700, $fetched->getId()->toInt());
        $this->assertSame('NP-P1', $fetched->getOfficialCode()->toString());
    }

    public function test_find_by_code_returns_null_when_not_found(): void
    {
        $fetched = $this->repository->findByCode(
            CountryCode::fromString('NP'),
            'NONEXISTENT'
        );

        $this->assertNull($fetched);
    }
}
