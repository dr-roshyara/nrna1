<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\GeoScope;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\StructureStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CommitteeStructurePersistenceTest extends TestCase
{
    use RefreshDatabase;

    private CommitteeStructureRepositoryInterface $repository;
    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(CommitteeStructureRepositoryInterface::class);
        $this->tenantId = TenantId::fromString('123e4567-e89b-12d3-a456-426614174000');
    }

    public function test_saves_and_retrieves_aggregate_intact(): void
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Test Structure',
            levels: [
                CommitteeLevel::create(1, null, 'Central', GeoPolicy::NONE, null, [], 0, null, null),
                CommitteeLevel::create(2, null, 'District', GeoPolicy::REQUIRED, new GeoScope('district'), [], 0, null, null),
            ]
        );

        $this->repository->save($structure);

        $retrieved = $this->repository->findById($structure->getId());

        $this->assertNotNull($retrieved);
        $this->assertEquals($structure->name(), $retrieved->name());
        $this->assertEquals(2, count($retrieved->levels()));
        $this->assertEquals('Central', $retrieved->getLevel(1)->name);
        $this->assertEquals('District', $retrieved->getLevel(2)->name);
    }

    public function test_version_persisted_correctly(): void
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );

        $structure->activate();
        $newVersion = $structure->evolve($structure->levels());

        $this->repository->save($structure);
        $this->repository->save($newVersion);

        $retrieved = $this->repository->findById($newVersion->getId());
        $this->assertEquals(2, $retrieved->version());
    }

    public function test_geo_scope_persisted_and_reconstructed(): void
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Geo Test',
            levels: [
                CommitteeLevel::create(
                    1,
                    'Province',
                    GeoPolicy::REQUIRED,
                    new GeoScope('province'),
                    [],
                    0,
                    null,
                    null
                ),
            ]
        );

        $this->repository->save($structure);
        $retrieved = $this->repository->findById($structure->getId());

        $level = $retrieved->getLevel(1);
        $this->assertNotNull($level->geoScope);
        $this->assertEquals('province', $level->geoScope->code);
    }

    public function test_find_active_by_tenant(): void
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Active',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );

        // Draft — not returned
        $this->repository->save($structure);
        $this->assertNull($this->repository->findActiveByTenant($this->tenantId));

        // Activate
        $structure->activate();
        $this->repository->save($structure);

        $active = $this->repository->findActiveByTenant($this->tenantId);
        $this->assertNotNull($active);
        $this->assertEquals($structure->name(), $active->name());
    }

    public function test_only_one_active_per_tenant_enforced(): void
    {
        $struct1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'First',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );

        $struct2 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Second',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );

        // Activate both
        $struct1->activate();
        $this->repository->save($struct1);

        $struct2->activate();

        // Trying to save second ACTIVE should fail (database constraint)
        $this->expectException(\Exception::class);
        $this->repository->save($struct2);
    }

    public function test_tenant_isolation_enforced(): void
    {
        $tenant1 = TenantId::fromString('123e4567-e89b-12d3-a456-426614174001');
        $tenant2 = TenantId::fromString('123e4567-e89b-12d3-a456-426614174002');

        $struct1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $tenant1,
            name: 'Org1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );

        $struct2 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $tenant2,
            name: 'Org2',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );

        $struct1->activate();
        $struct2->activate();

        $this->repository->save($struct1);
        $this->repository->save($struct2);

        // Each tenant should have exactly one active
        $active1 = $this->repository->findActiveByTenant($tenant1);
        $active2 = $this->repository->findActiveByTenant($tenant2);

        $this->assertNotNull($active1);
        $this->assertNotNull($active2);
        $this->assertEquals('Org1', $active1->name());
        $this->assertEquals('Org2', $active2->name());
    }

    public function test_evolve_creates_new_version_not_overwrite(): void
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Original',
            levels: [CommitteeLevel::create(1, null, 'L1', GeoPolicy::NONE, null, [], 0, null, null)]
        );

        $structure->activate();
        $this->repository->save($structure);

        $evolved = $structure->evolve($structure->levels());
        $this->repository->save($evolved);

        // Original should be DEPRECATED
        $original = $this->repository->findById($structure->getId());
        $this->assertTrue($original->isDeprecated());

        // Evolved should be DRAFT with new ID
        $newVersion = $this->repository->findById($evolved->getId());
        $this->assertTrue($newVersion->isDraft());
        $this->assertEquals(2, $newVersion->version());
    }

    public function test_returns_full_aggregate_not_partial(): void
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Full',
            levels: [
                CommitteeLevel::create(1, null, 'L1', GeoPolicy::NONE, null, ['role' => 1], 0, null, null),
                CommitteeLevel::create(2, null, 'L2', GeoPolicy::OPTIONAL, new GeoScope('prov'), ['role' => 2], 5, [18, 65], 'M'),
            ]
        );

        $this->repository->save($structure);
        $retrieved = $this->repository->findById($structure->getId());

        // All properties intact
        $level2 = $retrieved->getLevel(2);
        $this->assertEquals(GeoPolicy::OPTIONAL, $level2->geoPolicy);
        $this->assertEquals('prov', $level2->geoScope->code);
        $this->assertEquals(['role' => 2], $level2->roleLimits);
        $this->assertEquals(5, $level2->minMembershipYears);
        $this->assertEquals([18, 65], $level2->ageRange);
        $this->assertEquals('M', $level2->genderRequirement);
    }
}
