<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\CommitteeStructure;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * TemporalIntegrityConstraintsTest
 *
 * Tests that verify governance temporal invariants are enforced.
 * These tests validate that impossible governance histories cannot exist.
 *
 * Status: FAILING - constraints not yet implemented
 * Target: Phase A2 - Governance Epoch Integrity
 */
final class TemporalIntegrityConstraintsTest extends TestCase
{
    use RefreshDatabase;

    private CommitteeStructureRepositoryInterface $repository;
    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(CommitteeStructureRepositoryInterface::class);
        $this->tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');
    }

    // ============================================================
    // G-002: Unique Versioning Within Tenant
    // ============================================================

    public function test_cannot_create_duplicate_version_within_tenant(): void
    {
        // Create v1 and activate
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure v1',
            levels: [CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user-1');
        $this->repository->persist($v1);

        // Evolve to v2
        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)]);
        // Persist v1 as DEPRECATED before activating v2
        $this->repository->persist($v1);
        $v2->activate('user-1');
        $this->repository->persist($v2);

        // Try to create another v2 directly (violates G-002)
        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);

        $badV2 = CommitteeStructure::reconstruct(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Duplicate v2',
            version: 2,
            status: \App\Contexts\Membership\Domain\Committee\StructureStatus::ACTIVE,
            levels: [CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)],
            parentStructureId: null,
            activatedBy: 'user',
            activatedAt: new \DateTimeImmutable(),
            activationReason: null,
            activationMetadata: null,
            effectiveFrom: null,
            effectiveUntil: null
        );
        $this->repository->persist($badV2);
    }

    public function test_version_numbers_are_unique_per_tenant_isolation(): void
    {
        // Tenant A creates v1, v2
        $tenantA = TenantId::fromString('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa');
        $v1_A = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $tenantA,
            name: 'Structure v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1_A->activate('user');
        $this->repository->persist($v1_A);

        $v2_A = $v1_A->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v2_A);

        // Tenant B can also have v1, v2 (different namespace)
        $tenantB = TenantId::fromString('bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb');
        $v1_B = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $tenantB,
            name: 'Structure v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1_B->activate('user');
        $this->repository->persist($v1_B);

        // Verify tenant isolation works
        $this->assertEquals(1, $v1_A->version());
        $this->assertEquals(1, $v1_B->version());
        $this->assertEquals(2, $v2_A->version());
    }

    // ============================================================
    // G-003: Single Draft Successor Per Parent
    // ============================================================

    public function test_cannot_create_multiple_draft_successors(): void
    {
        // Create and activate v1
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure v1',
            levels: [CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user-1');
        $this->repository->persist($v1);

        // Evolve to v2 (DRAFT)
        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v2);

        // Try to create another DRAFT successor of v1
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('parent_structure_id');

        $v2_duplicate = CommitteeStructure::reconstruct(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure v2 Duplicate',
            version: 2,
            status: \App\Contexts\Membership\Domain\Committee\StructureStatus::DRAFT,
            levels: [CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)],
            parentStructureId: $v1->getId(),
            activatedBy: null,
            activatedAt: null,
            activationReason: null,
            activationMetadata: null,
            effectiveFrom: null,
            effectiveUntil: null
        );
        $this->repository->persist($v2_duplicate);
    }

    public function test_prevents_governance_branching(): void
    {
        // Create v1 → v2 → v3
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user');
        $this->repository->persist($v1);

        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v1);  // Persist v1 as DEPRECATED
        $v2->activate('user');
        $this->repository->persist($v2);

        // v2 can be evolved to v3
        $v3 = $v2->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v2);  // Persist v2 as DEPRECATED
        $this->repository->persist($v3);

        // Now try to create v3b as second DRAFT successor of v2
        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);

        $v3b = CommitteeStructure::reconstruct(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v3b',
            version: 3,
            status: \App\Contexts\Membership\Domain\Committee\StructureStatus::DRAFT,
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)],
            parentStructureId: $v2->getId(),
            activatedBy: null,
            activatedAt: null,
            activationReason: null,
            activationMetadata: null,
            effectiveFrom: null,
            effectiveUntil: null
        );
        $this->repository->persist($v3b);
    }

    public function test_draft_successor_can_be_replaced(): void
    {
        // Create v1 → v2 (DRAFT) without persisting v1 as DEPRECATED yet
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user');
        $this->repository->persist($v1);  // v1 ACTIVE, no DEPRECATED state yet

        // Evolve but don't activate v2 - don't persist v1 as DEPRECATED
        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v2);  // v2 DRAFT only

        // Delete the DRAFT v2 (simulating user discarding draft)
        $this->repository->hardDelete($v2);

        // Since v1 was never persisted as DEPRECATED, we can evolve it again
        // by reloading and evolving
        $v1_fresh = $this->repository->findActiveByTenant($this->tenantId);
        $this->assertNotNull($v1_fresh);

        $v2b = $v1_fresh->evolve([CommitteeLevel::create(1, null, 'L (revised)', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v2b);

        // Should succeed because v2 was deleted and v1 was never persisted as DEPRECATED
        $this->assertEquals(2, $v2b->version());
        $this->assertTrue($v2b->isDraft());
    }

    // ============================================================
    // G-006: Deprecated Structures Cannot Evolve
    // ============================================================

    public function test_cannot_evolve_deprecated_structure(): void
    {
        // Create v1 → v2 → v3
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user');
        $this->repository->persist($v1);

        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v1);  // Persist v1 as DEPRECATED
        $v2->activate('user');
        $this->repository->persist($v2);

        // v1 is now DEPRECATED (because v2 was evolved from it)

        // Try to evolve v1 again
        $this->expectException(\App\Contexts\Membership\Domain\Committee\Exceptions\CannotEvolveNonActiveStructure::class);

        $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
    }

    public function test_only_active_structures_can_evolve(): void
    {
        // Create v1 (DRAFT)
        $v1_draft = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1 Draft',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );

        // Try to evolve DRAFT structure (should fail)
        $this->expectException(\App\Contexts\Membership\Domain\Committee\Exceptions\CannotEvolveNonActiveStructure::class);

        $v1_draft->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
    }

    // ============================================================
    // G-002: Lineage Query APIs
    // ============================================================

    public function test_can_find_draft_successor_of_structure(): void
    {
        // Create v1 → v2 (DRAFT)
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user');
        $this->repository->persist($v1);

        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v2);

        // Repository should be able to find the DRAFT successor
        $found = $this->repository->findDraftSuccessorOf($v1->getId());

        $this->assertNotNull($found);
        $this->assertEquals($v2->getId()->value(), $found->getId()->value());
        $this->assertTrue($found->isDraft());
    }

    public function test_can_find_structure_by_version(): void
    {
        // Create v1 → v2 → v3
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user');
        $this->repository->persist($v1);

        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v1);  // Persist v1 as DEPRECATED
        $v2->activate('user');
        $this->repository->persist($v2);

        $v3 = $v2->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v2);  // Persist v2 as DEPRECATED
        $this->repository->persist($v3);

        // Should be able to find by version
        $found = $this->repository->findVersion($this->tenantId, 2);

        $this->assertNotNull($found);
        $this->assertEquals(2, $found->version());
        $this->assertTrue($found->isDeprecated());  // v2 is now DEPRECATED after v3 evolved from it
    }

    public function test_can_retrieve_full_lineage_chain(): void
    {
        // Create v1 → v2 → v3 → v4
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user');
        $this->repository->persist($v1);

        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v1);  // Persist v1 as DEPRECATED
        $v2->activate('user');
        $this->repository->persist($v2);

        $v3 = $v2->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v2);  // Persist v2 as DEPRECATED
        $v3->activate('user');
        $this->repository->persist($v3);

        $v4 = $v3->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v3);  // Persist v3 as DEPRECATED
        $this->repository->persist($v4);

        // Should be able to retrieve complete chain: [v1, v2, v3, v4]
        $chain = $this->repository->findLineageChain($v4->getId());

        $this->assertCount(4, $chain);
        $this->assertEquals(1, $chain[0]->version());
        $this->assertEquals(2, $chain[1]->version());
        $this->assertEquals(3, $chain[2]->version());
        $this->assertEquals(4, $chain[3]->version());
    }

    public function test_lineage_chain_cannot_have_gaps(): void
    {
        // Create v1 → v2 → v3
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user');
        $this->repository->persist($v1);

        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v1);  // Persist v1 as DEPRECATED
        $v2->activate('user');
        $this->repository->persist($v2);

        $v3 = $v2->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);
        $this->repository->persist($v2);  // Persist v2 as DEPRECATED
        $this->repository->persist($v3);

        // Lineage should be: v1 (v2) → v2 (v3) → v3
        // No v1 → v3 (skipping v2)
        $chain = $this->repository->findLineageChain($v3->getId());

        $this->assertCount(3, $chain);
        $this->assertEquals([1, 2, 3], array_map(fn($s) => $s->version(), $chain));
    }

    // ============================================================
    // G-001: Single Active Structure (Regression)
    // ============================================================

    public function test_only_one_active_structure_per_tenant(): void
    {
        // Create v1 and activate
        $v1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'v1',
            levels: [CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $v1->activate('user');
        $this->repository->persist($v1);

        // Verify v1 is ACTIVE
        $this->assertTrue($v1->isActive());

        // Evolve to v2: v1 transitions to DEPRECATED in memory
        $v2 = $v1->evolve([CommitteeLevel::create(1, null, 'L', GeoPolicy::NONE, null, [], 0, null, null)]);

        // v2 should start as DRAFT
        $this->assertTrue($v2->isDraft());

        // v1 should now be DEPRECATED (changed by evolve())
        $this->assertTrue($v1->isDeprecated());

        // Persist v1 (now DEPRECATED) and v2 (DRAFT)
        $this->repository->persist($v1);
        $this->repository->persist($v2);

        // Activate v2
        $v2->activate('user');
        $this->repository->persist($v2);

        // Query for active structure: should be v2, not v1
        $active = $this->repository->findActiveByTenant($this->tenantId);

        // v2 should be the active one
        $this->assertNotNull($active);
        $this->assertEquals($v2->getId()->value(), $active->getId()->value());
        $this->assertTrue($active->isActive());
    }
}
