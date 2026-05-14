<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureRegistry;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId as CanonicalCommitteeId;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Shared\Identity\CommitteeIdBridge;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId as LegacyCommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeStatus;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * B.1.5 Propagation Validation — Type Consistency Across Boundaries
 *
 * Validates that CommitteeIdBridge maintains type safety when:
 * 1. Legacy code passes legacy CommitteeId to repository
 * 2. Repository normalizes and returns types correctly
 * 3. Application layer receives values without type errors
 *
 * Success: Bridge holds. No type leakage at boundaries.
 * Failure: Would indicate bridge doesn't prevent cross-boundary type drift.
 *
 * This is NOT Phase C logic. These tests verify bridge correctness only.
 */
final class CommitteeIdPropagationTest extends TestCase
{
    private CommitteeRepositoryInterface $repository;
    private string $tenantIdValue;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(CommitteeRepositoryInterface::class);
        $this->tenantIdValue = (string) Str::uuid();
    }

    private function tenantId()
    {
        return \App\Contexts\Shared\Domain\ValueObjects\TenantId::fromString($this->tenantIdValue);
    }

    /**
     * TEST 1: Repository Round-Trip Integrity
     *
     * Scenario: Legacy code creates a Committee with legacy CommitteeId,
     * stores it, then retrieves it using that CommitteeId.
     *
     * Validates: Bridge correctly handles legacy IDs at persistence boundary.
     * Risk if fails: Repository doesn't handle legacy IDs, query fails.
     */
    public function test_repository_accepts_legacy_committee_id_and_retrieves()
    {
        $legacyId = new LegacyCommitteeId('01ARZ3NDEKTSV4RRFFQ69G5FAV'); // Valid ULID

        // Create and store committee with legacy ID
        $committee = Committee::reconstruct(
            id: $legacyId,
            tenantId: $this->tenantId(),
            type: CommitteeType::central(),
            name: CommitteeName::fromString('Central Committee'),
            code: 'CC-001',
            operationalGeo: null,
            status: CommitteeStatus::active(),
            structure: CommitteeStructureRegistry::forType(CommitteeType::central()),
        );

        $this->repository->saveForTenant($committee);

        // Retrieve using the legacy CommitteeId
        $retrieved = $this->repository->findForTenant(
            id: $legacyId,
            tenantId: $this->tenantId(),
        );

        // Should successfully retrieve
        $this->assertNotNull(
            $retrieved,
            'Repository should find committee using legacy CommitteeId'
        );

        // Retrieved committee should have the same ID value
        $this->assertEquals(
            $legacyId->value(),
            $retrieved->getId()->value(),
            'Repository must preserve ID value through storage/retrieval'
        );
    }

    /**
     * TEST 2: Multiple Committees with Legacy IDs
     *
     * Scenario: Store multiple committees with different legacy CommitteeIds,
     * retrieve each one independently. Tests that bridge isolation works.
     *
     * Validates: Bridge handles multiple legacy IDs independently.
     * Risk if fails: ID leakage between different committees.
     */
    public function test_multiple_committees_with_different_legacy_ids()
    {
        $legacyId1 = new LegacyCommitteeId('52GDCGSA16KER00GM403QA4T0C');
        $legacyId2 = new LegacyCommitteeId('VBSBW4ZC5X1DX9Q3CTXDFX0C6Y');

        // Store two committees
        $committee1 = Committee::reconstruct(
            id: $legacyId1,
            tenantId: $this->tenantId(),
            type: CommitteeType::central(),
            name: CommitteeName::fromString('Committee One'),
            code: 'C1',
            operationalGeo: null,
            status: CommitteeStatus::active(),
            structure: CommitteeStructureRegistry::forType(CommitteeType::central()),
        );
        $this->repository->saveForTenant($committee1);

        $committee2 = Committee::reconstruct(
            id: $legacyId2,
            tenantId: $this->tenantId(),
            type: CommitteeType::central(),
            name: CommitteeName::fromString('Committee Two'),
            code: 'C2',
            operationalGeo: null,
            status: CommitteeStatus::active(),
            structure: CommitteeStructureRegistry::forType(CommitteeType::central()),
        );
        $this->repository->saveForTenant($committee2);

        // Retrieve each using its legacy ID
        $retrieved1 = $this->repository->findForTenant(
            id: $legacyId1,
            tenantId: $this->tenantId(),
        );
        $retrieved2 = $this->repository->findForTenant(
            id: $legacyId2,
            tenantId: $this->tenantId(),
        );

        // Both should retrieve correctly
        $this->assertNotNull($retrieved1);
        $this->assertNotNull($retrieved2);

        // IDs must match their respective values (no cross-contamination)
        $this->assertEquals($legacyId1->value(), $retrieved1->getId()->value());
        $this->assertEquals($legacyId2->value(), $retrieved2->getId()->value());
    }

    /**
     * TEST 3: Boundary Type Consistency (Critical)
     *
     * Scenario: Retrieve Committee from repository multiple times,
     * verify retrieved committee maintains ID consistently across retrievals.
     *
     * Validates: Repository output is type-consistent.
     * Risk if fails: Type inconsistency at boundaries breaks downstream services.
     */
    public function test_repository_returns_consistent_id_across_multiple_retrievals()
    {
        $legacyId = new LegacyCommitteeId('Y3FP2521G5N1KXDEQ67MMVKZNS');

        // Create and store committee once
        $committee = Committee::reconstruct(
            id: $legacyId,
            tenantId: $this->tenantId(),
            type: CommitteeType::central(),
            name: CommitteeName::fromString('Central Committee'),
            code: 'CC-CONSISTENCY',
            operationalGeo: null,
            status: CommitteeStatus::active(),
            structure: CommitteeStructureRegistry::forType(CommitteeType::central()),
        );
        $this->repository->saveForTenant($committee);

        // Retrieve multiple times with same ID
        $retrieved1 = $this->repository->findForTenant(
            id: $legacyId,
            tenantId: $this->tenantId(),
        );
        $retrieved2 = $this->repository->findForTenant(
            id: $legacyId,
            tenantId: $this->tenantId(),
        );

        // Both retrievals must return consistent IDs
        $this->assertNotNull($retrieved1);
        $this->assertNotNull($retrieved2);

        $this->assertEquals(
            $legacyId->value(),
            $retrieved1->getId()->value(),
            'First retrieval must preserve ID value'
        );
        $this->assertEquals(
            $legacyId->value(),
            $retrieved2->getId()->value(),
            'Second retrieval must preserve ID value consistently'
        );

        // Both retrieved committees must have identical IDs
        $this->assertEquals(
            $retrieved1->getId()->value(),
            $retrieved2->getId()->value(),
            'Multiple retrievals must return consistent ID values'
        );
    }

    /**
     * TEST 4: Bridge Bidirectional Consistency
     *
     * Scenario: Convert legacy → canonical → legacy and verify values match.
     * Ensures bridge doesn't corrupt identity during conversion.
     *
     * Validates: Bridge conversions are lossless and reversible.
     * Risk if fails: Identity corruption during type conversion (silent data loss).
     */
    public function test_bridge_round_trip_maintains_identity()
    {
        $originalValue = '04ARZ3NDEKTSV4RRFFQ69G5FAY';
        $originalLegacy = new LegacyCommitteeId($originalValue);

        // Legacy → Canonical
        $canonical = CommitteeIdBridge::toCanonical($originalLegacy);
        $this->assertInstanceOf(CanonicalCommitteeId::class, $canonical);

        // Canonical → Legacy
        $backToLegacy = CommitteeIdBridge::toLegacy($canonical);
        $this->assertInstanceOf(LegacyCommitteeId::class, $backToLegacy);

        // Values must match (no corruption)
        $this->assertEquals(
            $originalValue,
            $canonical->value(),
            'Bridge should preserve identity when converting legacy to canonical'
        );
        $this->assertEquals(
            $originalValue,
            $backToLegacy->value(),
            'Bridge should preserve identity when converting back to legacy'
        );
    }

    /**
     * TEST 5: Repository Exists with Legacy ID
     *
     * Scenario: Legacy code calls repository->exists() with legacy CommitteeId.
     * Validate that exists() also handles legacy IDs correctly.
     *
     * Validates: Bridge works across all repository methods (find, exists, delete).
     * Risk if fails: Some methods bypass bridge, inconsistent behavior across API.
     */
    public function test_repository_exists_method_accepts_legacy_committee_id()
    {
        $legacyId = new LegacyCommitteeId('05ARZ3NDEKTSV4RRFFQ69G5FAZ');

        // Setup: Store committee
        $committee = Committee::reconstruct(
            id: $legacyId,
            tenantId: $this->tenantId(),
            type: CommitteeType::central(),
            name: CommitteeName::fromString('Exists Test Committee'),
            code: 'ETC-001',
            operationalGeo: null,
            status: CommitteeStatus::active(),
            structure: CommitteeStructureRegistry::forType(CommitteeType::central()),
        );
        $this->repository->saveForTenant($committee);

        // Check exists with legacy ID
        $exists = $this->repository->existsForTenant(
            id: $legacyId,
            tenantId: $this->tenantId(),
        );

        $this->assertTrue(
            $exists,
            'Repository->exists() should find committee using legacy CommitteeId'
        );
    }
}
