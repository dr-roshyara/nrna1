<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\CommitteeStructure;

use Tests\TestCase;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\GeoScope;
use App\Contexts\Membership\Domain\Committee\StructureStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class TemporalIdentityTest extends TestCase
{
    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');
    }

    // ============================================================
    // Version Semantics Tests
    // ============================================================

    public function test_original_structure_has_version_one(): void
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Original Structure',
            levels: [
                CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );

        $this->assertEquals(1, $structure->version());
    }

    public function test_original_structure_has_no_parent(): void
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Original Structure',
            levels: [
                CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );

        $this->assertNull($structure->parentStructureId());
    }

    public function test_evolved_structure_increments_version(): void
    {
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure v1',
            levels: [
                CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );
        $v1->activate('user-id');

        $v2 = $v1->evolve([
            CommitteeLevel::create(1, null, 'Level 1 Updated', GeoPolicy::NONE, null, [], 0, null, null)
        ]);

        $this->assertEquals(2, $v2->version());
    }

    public function test_evolved_structure_records_parent(): void
    {
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure v1',
            levels: [
                CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );
        $v1->activate('user-id');

        $v2 = $v1->evolve([
            CommitteeLevel::create(1, null, 'Level 1 Updated', GeoPolicy::NONE, null, [], 0, null, null)
        ]);

        $this->assertEquals($v1->getId()->value(), $v2->parentStructureId()->value());
    }

    public function test_version_increments_monotonically_across_chain(): void
    {
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user-id');

        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $v2->activate('user-id');

        $v3 = $v2->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $v3->activate('user-id');

        $v4 = $v3->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);

        $this->assertEquals(1, $v1->version());
        $this->assertEquals(2, $v2->version());
        $this->assertEquals(3, $v3->version());
        $this->assertEquals(4, $v4->version());
    }

    // ============================================================
    // Lineage Tests
    // ============================================================

    public function test_lineage_chain_is_reconstructable(): void
    {
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user-id');

        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $v2->activate('user-id');

        $v3 = $v2->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);

        // Lineage chain: v3 → v2 → v1
        $this->assertEquals($v2->getId()->value(), $v3->parentStructureId()->value());
        $this->assertEquals($v1->getId()->value(), $v2->parentStructureId()->value());
        $this->assertNull($v1->parentStructureId());
    }

    public function test_evolution_preserves_tenant_lineage(): void
    {
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user-id');

        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);

        // Both versions belong to same tenant
        $this->assertEquals($v1->getTenantId()->value(), $v2->getTenantId()->value());
    }

    // ============================================================
    // Temporal Validity Tests (Future-Proofing)
    // ============================================================

    public function test_structure_can_have_effective_from_date(): void
    {
        $now = new \DateTimeImmutable();
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );

        // Reconstruct with effective dates
        $reconstructed = CommitteeStructure::reconstruct(
            id: $v1->getId(),
            tenantId: $v1->getTenantId(),
            name: $v1->name(),
            version: 1,
            status: StructureStatus::DRAFT,
            levels: $v1->levels(),
            parentStructureId: null,
            activatedBy: null,
            activatedAt: null,
            activationReason: null,
            activationMetadata: null,
            effectiveFrom: $now,
            effectiveUntil: $now->modify('+1 year')
        );

        $this->assertEquals($now->getTimestamp(), $reconstructed->effectiveFrom()->getTimestamp());
    }

    public function test_structure_can_have_effective_until_date(): void
    {
        $now = new \DateTimeImmutable();
        $futureDate = $now->modify('+6 months');

        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );

        $reconstructed = CommitteeStructure::reconstruct(
            id: $v1->getId(),
            tenantId: $v1->getTenantId(),
            name: $v1->name(),
            version: 1,
            status: StructureStatus::DRAFT,
            levels: $v1->levels(),
            parentStructureId: null,
            activatedBy: null,
            activatedAt: null,
            activationReason: null,
            activationMetadata: null,
            effectiveFrom: null,
            effectiveUntil: $futureDate
        );

        $this->assertEquals($futureDate->getTimestamp(), $reconstructed->effectiveUntil()->getTimestamp());
    }

    // ============================================================
    // Evolution Safety Tests
    // ============================================================

    public function test_evolved_structure_starts_as_draft(): void
    {
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user-id');

        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);

        $this->assertTrue($v2->isDraft());
        $this->assertFalse($v2->isActive());
    }

    public function test_parent_becomes_deprecated_on_evolution(): void
    {
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user-id');

        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);

        // After evolve(), v1 is marked deprecated
        $this->assertTrue($v1->isDeprecated());
    }

    // ============================================================
    // Reconstruction Tests (Temporal Consistency)
    // ============================================================

    public function test_structure_reconstructs_with_full_temporal_context(): void
    {
        $id = CommitteeStructureId::generate();
        $parentId = CommitteeStructureId::generate();
        $activatedAt = new \DateTimeImmutable('2026-05-01 10:00:00');
        $effectiveFrom = new \DateTimeImmutable('2026-06-01 00:00:00');

        $reconstructed = CommitteeStructure::reconstruct(
            id: $id,
            tenantId: $this->tenantId,
            name: 'Reconstructed',
            version: 2,
            status: StructureStatus::ACTIVE,
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)],
            parentStructureId: $parentId,
            activatedBy: 'admin-user',
            activatedAt: $activatedAt,
            activationReason: 'Election cycle',
            activationMetadata: ['cycle' => '2026'],
            effectiveFrom: $effectiveFrom,
            effectiveUntil: null
        );

        $this->assertEquals(2, $reconstructed->version());
        $this->assertEquals($parentId->value(), $reconstructed->parentStructureId()->value());
        $this->assertEquals($activatedAt->getTimestamp(), $reconstructed->activatedAt()->getTimestamp());
        $this->assertEquals($effectiveFrom->getTimestamp(), $reconstructed->effectiveFrom()->getTimestamp());
    }

    public function test_reconstruction_preserves_lineage_identity(): void
    {
        $parentId = CommitteeStructureId::generate();
        $currentId = CommitteeStructureId::generate();

        $reconstructed = CommitteeStructure::reconstruct(
            id: $currentId,
            tenantId: $this->tenantId,
            name: 'v2',
            version: 2,
            status: StructureStatus::ACTIVE,
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)],
            parentStructureId: $parentId,
            activatedBy: 'user',
            activatedAt: new \DateTimeImmutable(),
            activationReason: null,
            activationMetadata: null,
            effectiveFrom: null,
            effectiveUntil: null
        );

        // Can query the lineage chain
        $this->assertEquals($parentId->value(), $reconstructed->parentStructureId()->value());
        $this->assertEquals(2, $reconstructed->version());
    }
}
