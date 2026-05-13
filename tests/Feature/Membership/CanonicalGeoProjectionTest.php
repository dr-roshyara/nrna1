<?php

declare(strict_types=1);

namespace Tests\Feature\Membership;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Committee\Strategies\CentralCommitteeStructure;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeGeoIdentity;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeStatus;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Membership\Infrastructure\Services\CanonicalGeoSerializer;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CanonicalGeoProjectionTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private CanonicalGeoSerializer $serializer;
    private CommitteeRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create([
            'governance_status' => 'active',
        ]);

        $this->serializer = new CanonicalGeoSerializer();
        $this->repository = app(CommitteeRepositoryInterface::class);
    }

    private function persistCommittee(
        ?int $geoUnitId = null,
        ?string $regionCode = null,
        ?string $countryCode = null,
    ): Committee {
        $committeeId = CommitteeId::generate();
        $tenantId = TenantId::fromString($this->organisation->id);

        $committee = Committee::reconstruct(
            id: $committeeId,
            tenantId: $tenantId,
            type: CommitteeType::central(),
            name: CommitteeName::fromString('Canonical Test'),
            code: 'CANON-' . rand(1000, 9999),
            operationalGeo: null,
            status: CommitteeStatus::active(),
            structure: new CentralCommitteeStructure(),
            assignments: [],
            structureId: null,
            levelIndex: null,
            levelName: null,
            geoPolicy: null,
            geoScope: null,
            structureVersion: null,
            regionCode: $regionCode,
            countryCode: $countryCode,
            geoUnitId: $geoUnitId,
            geoIdentity: $geoUnitId !== null ? new CommitteeGeoIdentity($geoUnitId) : null,
        );

        $this->repository->persist($committee);

        return $committee;
    }

    // --- Repository persists canonical_geo_id ---

    public function test_persist_stores_canonical_geo_id(): void
    {
        $committee = $this->persistCommittee(
            geoUnitId: 7,
            regionCode: 'asia',
            countryCode: 'NP',
        );

        $model = CommitteeModel::withoutGlobalScopes()
            ->where('id', $committee->getId()->value())
            ->first();

        $this->assertNotNull($model);
        $this->assertNotNull($model->canonical_geo_id);
        $this->assertSame('region:asia.country:NP.geo:7', $model->canonical_geo_id);
    }

    public function test_persist_sets_null_when_no_geo(): void
    {
        $committee = $this->persistCommittee(
            geoUnitId: null,
            regionCode: null,
            countryCode: null,
        );

        $model = CommitteeModel::withoutGlobalScopes()
            ->where('id', $committee->getId()->value())
            ->first();

        $this->assertNotNull($model);
        $this->assertNull($model->canonical_geo_id);
    }

    // --- Read path (findById) returns correct aggregate ---

    public function test_find_by_id_returns_committee_with_geo_identity(): void
    {
        $committee = $this->persistCommittee(
            geoUnitId: 7,
            regionCode: 'asia',
            countryCode: 'NP',
        );

        $reconstructed = $this->repository->findById($committee->getId());

        $this->assertNotNull($reconstructed);
        $this->assertEquals(7, $reconstructed->getGeoUnitId());
        $this->assertEquals('asia', $reconstructed->getRegionCode());
        $this->assertEquals('NP', $reconstructed->getCountryCode());
    }

    // --- Legacy records (null canonical_geo_id) ---

    public function test_legacy_records_without_canonical_geo_id_still_load(): void
    {
        $id = CommitteeId::generate()->value();
        $tenantId = $this->organisation->id;

        \DB::table('committees')->insert([
            'id' => $id,
            'organisation_id' => $tenantId,
            'name' => 'Legacy',
            'code' => 'LEGACY-' . rand(1000, 9999),
            'slug' => 'legacy-' . rand(1000, 9999),
            'type' => 'central',
            'level' => 1,
            'status' => 'active',
            'formation_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            // canonical_geo_id intentionally NULL (legacy)
        ]);

        $reconstructed = $this->repository->findById(CommitteeId::fromString($id));

        $this->assertNotNull($reconstructed);
        $this->assertNull($reconstructed->getGeoUnitId());

        $model = CommitteeModel::withoutGlobalScopes()->find($id);
        $this->assertNotNull($model);
        $this->assertNull($model->canonical_geo_id);
    }

    // --- Canonical format ---

    public function test_canonical_format_follows_expected_pattern(): void
    {
        $committee = $this->persistCommittee(
            geoUnitId: 7,
            regionCode: 'asia',
            countryCode: 'NP',
        );

        $model = CommitteeModel::withoutGlobalScopes()
            ->where('id', $committee->getId()->value())
            ->first();

        $this->assertNotNull($model);

        // Format: region:xxx.country:YY.geo:NNN (segments separated by dots)
        $this->assertMatchesRegularExpression(
            '/^([a-z]+:[a-zA-Z0-9]+\.)*[a-z]+:\d+$/',
            $model->canonical_geo_id
        );
    }
}
