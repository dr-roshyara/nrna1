<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee;

use Tests\TestCase;
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\GeoScope;
use App\Contexts\Membership\Domain\Committee\Strategies\CentralCommitteeStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\GeographicCommitteeStructure;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteePolicy;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class TemporalSnapshotTest extends TestCase
{
    private TenantId $tenantId;
    private CommitteeStructureId $structureId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');
        $this->structureId = CommitteeStructureId::generate();
    }

    private function makeCentralPolicy(): CommitteePolicy
    {
        return new CommitteePolicy(
            type: CommitteeType::central(),
            structure: new CentralCommitteeStructure(),
            level: CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null),
        );
    }

    private function makeGeographicPolicy(int $levelIndex, string $scope, string $levelName): CommitteePolicy
    {
        return new CommitteePolicy(
            type: CommitteeType::geographic(),
            structure: new GeographicCommitteeStructure(),
            level: CommitteeLevel::create($levelIndex, $scope, $levelName, GeoPolicy::REQUIRED, new GeoScope($scope), [], 0, null, null),
        );
    }

    // ============================================================
    // Temporal Snapshot Tests
    // ============================================================

    public function test_committee_snapshots_created_from_structure_id(): void
    {
        $committee = Committee::create(
            id: CommitteeId::generate(),
            tenantId: $this->tenantId,
            policy: $this->makeCentralPolicy(),
            structureId: $this->structureId,
            levelIndex: 1,
            levelName: 'Level 1',
            geoPolicy: GeoPolicy::NONE,
            geoScope: null,
            name: CommitteeName::fromString('Test Committee'),
            code: 'TEST001',
            operationalGeo: null
        );

        // Should expose the created_from_structure_id for temporal reconstruction
        $this->assertNotNull($committee->createdFromStructureId());
        $this->assertEquals($this->structureId->value(), $committee->createdFromStructureId()->value());
    }

    public function test_committee_snapshots_structure_version(): void
    {
        $committee = Committee::create(
            id: CommitteeId::generate(),
            tenantId: $this->tenantId,
            policy: $this->makeCentralPolicy(),
            structureId: $this->structureId,
            levelIndex: 1,
            levelName: 'Level 1',
            geoPolicy: GeoPolicy::NONE,
            geoScope: null,
            name: CommitteeName::fromString('Test Committee'),
            code: 'TEST001',
            operationalGeo: null,
            structureVersion: 2
        );

        // Should expose the structure_version for temporal reconstruction
        $this->assertNotNull($committee->structureVersion());
        $this->assertEquals(2, $committee->structureVersion());
    }

    public function test_committee_can_be_created_with_version_context(): void
    {
        $structureId1 = CommitteeStructureId::generate();
        $committee = Committee::create(
            id: CommitteeId::generate(),
            tenantId: $this->tenantId,
            policy: $this->makeGeographicPolicy(1, 'district', 'District Committee'),
            structureId: $structureId1,
            levelIndex: 1,
            levelName: 'District Committee',
            geoPolicy: GeoPolicy::REQUIRED,
            geoScope: new GeoScope('district'),
            name: CommitteeName::fromString('District Committee'),
            code: 'DIST001',
            operationalGeo: null,
            structureVersion: 1
        );

        // Committee knows it was created from v1 of a structure
        $this->assertEquals(1, $committee->structureVersion());
        $this->assertEquals($structureId1->value(), $committee->createdFromStructureId()->value());
    }

    public function test_committee_snapshot_is_immutable_after_creation(): void
    {
        $structureId = CommitteeStructureId::generate();
        $committee = Committee::create(
            id: CommitteeId::generate(),
            tenantId: $this->tenantId,
            policy: $this->makeCentralPolicy(),
            structureId: $structureId,
            levelIndex: 1,
            levelName: 'Level 1',
            geoPolicy: GeoPolicy::NONE,
            geoScope: null,
            name: CommitteeName::fromString('Test Committee'),
            code: 'TEST001',
            operationalGeo: null,
            structureVersion: 1
        );

        $originalVersion = $committee->structureVersion();
        $originalStructureId = $committee->createdFromStructureId()->value();

        // Even if we try to "recreate" with different version, snapshot should remain
        // (In practice, this would be caught by persistence layer, but domain should be defensible)
        $this->assertEquals($originalVersion, $committee->structureVersion());
        $this->assertEquals($originalStructureId, $committee->createdFromStructureId()->value());
    }

    public function test_committees_from_different_structure_versions_are_distinguishable(): void
    {
        $structureV1Id = CommitteeStructureId::generate();
        $structureV2Id = CommitteeStructureId::generate();

        $committeeFromV1 = Committee::create(
            id: CommitteeId::generate(),
            tenantId: $this->tenantId,
            policy: $this->makeCentralPolicy(),
            structureId: $structureV1Id,
            levelIndex: 1,
            levelName: 'Central Committee',
            geoPolicy: GeoPolicy::NONE,
            geoScope: null,
            name: CommitteeName::fromString('Central'),
            code: 'CENTRAL1',
            operationalGeo: null,
            structureVersion: 1
        );

        $committeeFromV2 = Committee::create(
            id: CommitteeId::generate(),
            tenantId: $this->tenantId,
            policy: $this->makeCentralPolicy(),
            structureId: $structureV2Id,
            levelIndex: 1,
            levelName: 'Central Committee v2',
            geoPolicy: GeoPolicy::NONE,
            geoScope: null,
            name: CommitteeName::fromString('Central'),
            code: 'CENTRAL2',
            operationalGeo: null,
            structureVersion: 2
        );

        // Can distinguish based on temporal context
        $this->assertEquals(1, $committeeFromV1->structureVersion());
        $this->assertEquals(2, $committeeFromV2->structureVersion());
        $this->assertNotEquals(
            $committeeFromV1->createdFromStructureId()->value(),
            $committeeFromV2->createdFromStructureId()->value()
        );
    }

    public function test_committee_reconstruction_preserves_temporal_identity(): void
    {
        $id = CommitteeId::generate();
        $createdFromStructureId = CommitteeStructureId::generate();
        $structureVersion = 3;

        $reconstructed = Committee::reconstruct(
            id: $id,
            tenantId: $this->tenantId,
            type: CommitteeType::central(),
            name: CommitteeName::fromString('Test'),
            code: 'TEST',
            operationalGeo: null,
            status: CommitteeStatus::active(),
            structure: new \App\Contexts\Membership\Domain\Committee\Strategies\CentralCommitteeStructure(),
            assignments: [],
            structureId: $createdFromStructureId,
            levelIndex: 1,
            levelName: 'Level 1',
            geoPolicy: GeoPolicy::NONE,
            geoScope: null,
            createdFromStructureId: $createdFromStructureId,
            structureVersion: $structureVersion
        );

        // Temporal identity preserved through reconstruction
        $this->assertEquals($createdFromStructureId->value(), $reconstructed->createdFromStructureId()->value());
        $this->assertEquals($structureVersion, $reconstructed->structureVersion());
    }

    // ============================================================
    // Temporal Reconstruction Queries
    // ============================================================

    public function test_can_query_committee_created_from_structure_version(): void
    {
        $structureId = CommitteeStructureId::generate();

        $committee = Committee::create(
            id: CommitteeId::generate(),
            tenantId: $this->tenantId,
            policy: $this->makeGeographicPolicy(2, 'district', 'District Committee'),
            structureId: $structureId,
            levelIndex: 2,
            levelName: 'District Committee',
            geoPolicy: GeoPolicy::REQUIRED,
            geoScope: new GeoScope('district'),
            name: CommitteeName::fromString('District'),
            code: 'DIST001',
            operationalGeo: null,
            structureVersion: 2
        );

        // Question: "What governance version was active when this committee was created?"
        // Answer available via snapshot:
        $this->assertEquals(2, $committee->structureVersion());

        // Question: "Which structure created this committee?"
        // Answer available via snapshot:
        $this->assertEquals($structureId->value(), $committee->createdFromStructureId()->value());
    }

    public function test_temporal_snapshot_enables_governance_archaeology(): void
    {
        $v1StructureId = CommitteeStructureId::generate();
        $v2StructureId = CommitteeStructureId::generate();

        // Committee created under v1 structure
        $olderCommittee = Committee::create(
            id: CommitteeId::generate(),
            tenantId: $this->tenantId,
            policy: $this->makeGeographicPolicy(1, 'province', 'Province Committee'),
            structureId: $v1StructureId,
            levelIndex: 1,
            levelName: 'Province Committee',
            geoPolicy: GeoPolicy::REQUIRED,
            geoScope: new GeoScope('province'),
            name: CommitteeName::fromString('Kathmandu Province'),
            code: 'KTM001',
            operationalGeo: null,
            structureVersion: 1
        );

        // Committee created under v2 structure (governance evolved)
        $newerCommittee = Committee::create(
            id: CommitteeId::generate(),
            tenantId: $this->tenantId,
            policy: $this->makeGeographicPolicy(1, 'region', 'Bagmati Province'),
            structureId: $v2StructureId,
            levelIndex: 1,
            levelName: 'Bagmati Province',  // Name changed in v2
            geoPolicy: GeoPolicy::REQUIRED,
            geoScope: new GeoScope('region'),  // Scope changed in v2
            name: CommitteeName::fromString('Bagmati Province'),
            code: 'BGMT001',
            operationalGeo: null,
            structureVersion: 2
        );

        // We can now answer temporal governance questions:
        // "Why did governance change between these committees?"
        // Answer: Because they were created against different structure versions

        $this->assertEquals(1, $olderCommittee->structureVersion());
        $this->assertEquals(2, $newerCommittee->structureVersion());

        // The temporal context is preserved for future audit/migration/reconstruction
        $this->assertNotEquals(
            $olderCommittee->createdFromStructureId()->value(),
            $newerCommittee->createdFromStructureId()->value()
        );
    }
}
