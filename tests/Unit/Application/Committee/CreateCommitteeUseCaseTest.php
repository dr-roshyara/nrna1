<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Committee;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Contexts\Membership\Application\Committee\CreateCommittee;
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\GeoScope;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\CommitteeCreationPolicy;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class CreateCommitteeUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private CommitteeStructureRepositoryInterface $structureRepo;
    private CommitteeRepositoryInterface $committeeRepo;
    private CommitteeCreationPolicy $policy;

    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->structureRepo = $this->app->make(CommitteeStructureRepositoryInterface::class);
        $this->committeeRepo = $this->app->make(CommitteeRepositoryInterface::class);
        // Create policy with mocked GeoContextPort
        $mockGeoPort = \Mockery::mock(\App\Contexts\Membership\Domain\Committee\Ports\GeoContextPort::class);
        $this->policy = new CommitteeCreationPolicy($mockGeoPort);

        $this->tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');
    }

    // --------------------------------------------------
    // ✅ HAPPY PATH
    // --------------------------------------------------

    public function test_it_creates_committee_from_active_structure(): void
    {
        $structure = $this->createActiveStructure();

        $useCase = $this->makeUseCase();

        $result = $useCase->execute([
            'tenantId' => $this->tenantId->value(),
            'levelIndex' => 1,
            'geoReference' => null,
            'name' => 'Test Committee'
        ]);

        $this->assertInstanceOf(Committee::class, $result);
        $this->assertEquals(1, $result->levelIndex());
        $this->assertEquals($structure->getId()->value(), $result->structureId()->value());
    }

    // --------------------------------------------------
    // ❌ NO ACTIVE STRUCTURE
    // --------------------------------------------------

    public function test_it_fails_when_no_active_structure_exists(): void
    {
        $useCase = $this->makeUseCase();

        $this->expectException(\DomainException::class);

        $useCase->execute([
            'tenantId' => $this->tenantId->value(),
            'levelIndex' => 1,
            'geoReference' => null,
            'name' => 'Test Committee'
        ]);
    }

    // --------------------------------------------------
    // ❌ INVALID LEVEL
    // --------------------------------------------------

    public function test_it_fails_for_invalid_level_index(): void
    {
        $this->createActiveStructure();

        $useCase = $this->makeUseCase();

        $this->expectException(\DomainException::class);

        $useCase->execute([
            'tenantId' => $this->tenantId->value(),
            'levelIndex' => 99,
            'geoReference' => null,
            'name' => 'Invalid Level Committee'
        ]);
    }

    // --------------------------------------------------
    // ❌ GEO POLICY VIOLATION
    // --------------------------------------------------

    public function test_it_fails_when_required_geo_missing(): void
    {
        $this->createActiveStructureWithRequiredGeo();

        $useCase = $this->makeUseCase();

        $this->expectException(\DomainException::class);

        $useCase->execute([
            'tenantId' => $this->tenantId->value(),
            'levelIndex' => 1,
            'geoReference' => null,
            'name' => 'Invalid Geo Committee'
        ]);
    }

    // --------------------------------------------------
    // ✅ SNAPSHOT TEST (CRITICAL)
    // --------------------------------------------------

    public function test_it_snapshots_structure_data_at_creation_time(): void
    {
        $structure = $this->createActiveStructure();

        $useCase = $this->makeUseCase();

        $committee = $useCase->execute([
            'tenantId' => $this->tenantId->value(),
            'levelIndex' => 1,
            'geoReference' => null,
            'name' => 'Snapshot Committee'
        ]);

        // mutate structure AFTER creation
        $structure->deprecate();
        $this->structureRepo->persist($structure);

        // committee must remain unchanged
        $this->assertEquals(1, $committee->levelIndex());
        $this->assertEquals('Level 1', $committee->levelName());
    }

    // --------------------------------------------------
    // 🔥 INDEPENDENCE TEST (MOST IMPORTANT)
    // --------------------------------------------------

    public function test_committee_does_not_depend_on_structure_after_creation(): void
    {
        $structure = $this->createActiveStructure();

        $useCase = $this->makeUseCase();

        $committee = $useCase->execute([
            'tenantId' => $this->tenantId->value(),
            'levelIndex' => 1,
            'geoReference' => null,
            'name' => 'Independence Committee'
        ]);

        // Simulate structure evolution (new version)
        $newLevels = [
            CommitteeLevel::create(1, null, 'Changed Level Name', GeoPolicy::NONE, null, [], 0, null, null)
        ];
        $evolved = $structure->evolve($newLevels);
        $evolved->activate('11111111-1111-1111-1111-111111111111');

        // Persist both the deprecation of old structure and creation of new one
        $this->structureRepo->persist($structure); // Now deprecated
        $this->structureRepo->persist($evolved);   // New ACTIVE version

        // Committee must NOT change - it still has the original level name
        $this->assertEquals('Level 1', $committee->levelName());
    }

    // --------------------------------------------------
    // 🧰 HELPERS
    // --------------------------------------------------

    private function makeUseCase(): CreateCommittee
    {
        return new CreateCommittee(
            $this->structureRepo,
            $this->committeeRepo,
            $this->policy
        );
    }

    private function createActiveStructure(): CommitteeStructure
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Test Structure',
            levels: [
                CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );

        $structure->activate('11111111-1111-1111-1111-111111111111');
        $this->structureRepo->persist($structure);

        return $structure;
    }

    private function createActiveStructureWithRequiredGeo(): CommitteeStructure
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Geo Required Structure',
            levels: [
                CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::REQUIRED, new GeoScope('district'), [], 0, null, null)
            ]
        );

        $structure->activate('11111111-1111-1111-1111-111111111111');
        $this->structureRepo->persist($structure);

        return $structure;
    }
}
