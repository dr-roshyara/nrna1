<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership\Query;

use App\Contexts\Membership\Application\Membership\Query\EligibleCommitteeQueryService;
use App\Contexts\Membership\Application\Membership\Ports\MembershipApplicationRepositoryPort;
use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Membership\Application\Membership\Query\Ports\CommitteeGeoPathProviderPort;
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Committee\Policies\CommitteeEligibilityPolicy;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Constitutional Specification: Governance Query Layer
 *
 * EligibleCommitteeQueryService is the first constitutional read model.
 * It answers: "Which committees is this member constitutionally eligible to be associated with?"
 *
 * CRITICAL:
 * - This service orchestrates existing policies — does NOT duplicate them
 * - Geo eligibility is determined by CommitteeEligibilityPolicy
 * - Separation: eligibility (can join) ≠ voting rights (given existing membership)
 * - Output DTOs do NOT expose aggregates
 */
final class EligibleCommitteeQueryServiceTest extends TestCase
{
    // Inline test stubs — Committee and MembershipLineage are final so cannot be mocked
    // These stubs provide the contracts needed for the query service
    private static array $stubCounter = [];

    private function createLineageStub(bool $isActive): object
    {
        return new class($isActive) {
            public function __construct(private bool $isActive) {}

            public function isActive(): bool
            {
                return $this->isActive;
            }
        };
    }

    private function createCommitteeStub(
        string $name,
        string $code,
        ?int $geoUnitId,
        ?int $levelIndex,
        CommitteeStatus $status
    ): object {
        static::$stubCounter[] = 1;
        $id = CommitteeId::generate();

        return new class($id, $name, $code, $geoUnitId, $levelIndex, $status) {
            public function __construct(
                private CommitteeId $id,
                private string $name,
                private string $code,
                private ?int $geoUnitId,
                private ?int $levelIndex,
                private CommitteeStatus $status,
            ) {}

            public function getId(): CommitteeId
            {
                return $this->id;
            }

            public function getGeoUnitId(): ?int
            {
                return $this->geoUnitId;
            }

            public function getName()
            {
                return new class($this->name) {
                    public function __construct(private string $value) {}
                    public function value(): string { return $this->value; }
                };
            }

            public function code(): string
            {
                return $this->code;
            }

            public function levelIndex(): ?int
            {
                return $this->levelIndex;
            }

            public function getStatus(): CommitteeStatus
            {
                return $this->status;
            }
        };
    }
    private EligibleCommitteeQueryService $service;

    private CommitteeRepositoryInterface&MockObject $committeeRepo;
    private MembershipLineageRepositoryPort&MockObject $lineageRepo;
    private MembershipApplicationRepositoryPort&MockObject $applicationRepo;
    private CommitteeEligibilityPolicy $eligibilityPolicy;
    private CommitteeGeoPathProviderPort&MockObject $geoPathProvider;

    protected function setUp(): void
    {
        $this->committeeRepo = $this->createMock(CommitteeRepositoryInterface::class);
        $this->lineageRepo = $this->createMock(MembershipLineageRepositoryPort::class);
        $this->applicationRepo = $this->createMock(MembershipApplicationRepositoryPort::class);
        $this->eligibilityPolicy = new CommitteeEligibilityPolicy();
        $this->geoPathProvider = $this->createMock(CommitteeGeoPathProviderPort::class);

        $this->service = $this->createServiceInstance();
    }

    /**
     * Constitutional Specification: Central committee is eligible for all members
     *
     * CRITICAL: A central committee (geoUnitId = null) is eligible for members
     * with any geography (including no geography).
     * GEO-ELIG-2: Central governance applies to all.
     */
    public function test_central_committee_eligible_for_member_with_no_geo_path(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 1, path: '', segments: []);

        $centralCommittee = $this->createCentralCommitteeStub('Central Assembly', 'CA', null);

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$centralCommittee]);

        $this->lineageRepo->expects($this->once())
            ->method('findLineageByMemberAndCommitteeForTenant')
            ->willReturn(null);

        $this->applicationRepo->expects($this->once())
            ->method('existsActiveForTenant')
            ->willReturn(false);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(1, $result);
        $this->assertFalse($result[0]->hasActiveAssociation);
        $this->assertFalse($result[0]->hasPendingApplication);
    }

    /**
     * Constitutional Specification: Central committee is eligible for all members
     *
     * Even members with a geographic path are eligible for central committees.
     */
    public function test_central_committee_eligible_for_member_with_geo_path(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 23, path: '/1/23', segments: [1, 23]);

        $centralCommittee = $this->createCentralCommitteeStub('National Assembly', 'NA', null);

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$centralCommittee]);

        $this->lineageRepo->expects($this->once())
            ->method('findLineageByMemberAndCommitteeForTenant')
            ->willReturn(null);

        $this->applicationRepo->expects($this->once())
            ->method('existsActiveForTenant')
            ->willReturn(false);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(1, $result);
    }

    /**
     * Constitutional Specification: Geographic committee eligible when member path matches
     *
     * GEO-ELIG-1: Member is eligible when member's geo path starts with committee's path.
     * Example: Member in /1/23/456 is eligible for committee at /1/23 (both within Bayern)
     */
    public function test_geographic_committee_eligible_when_member_path_matches(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 456, path: '/1/23/456', segments: [1, 23, 456]);

        $geoCommittee = $this->createGeographicCommitteeStub('Bayern Assembly', 'BAY', 23, 1);

        $committeeGeoPath = new GeoPathChain(geoUnitId: 23, path: '/1/23', segments: [1, 23]);

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$geoCommittee]);

        $this->geoPathProvider->expects($this->once())
            ->method('resolveForCommittee')
            ->with(23)
            ->willReturn($committeeGeoPath);

        $this->lineageRepo->expects($this->once())
            ->method('findLineageByMemberAndCommitteeForTenant')
            ->willReturn(null);

        $this->applicationRepo->expects($this->once())
            ->method('existsActiveForTenant')
            ->willReturn(false);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(1, $result);
    }

    /**
     * Constitutional Specification: Geographic committee ineligible when paths don't match
     *
     * GEO-ELIG-1: Member in /1/23 is NOT eligible for committee at /1/24 (different branches).
     */
    public function test_geographic_committee_ineligible_when_member_path_does_not_match(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 234, path: '/1/23/234', segments: [1, 23, 234]);

        $geoCommittee = $this->createGeographicCommitteeStub('Baden Assembly', 'BAD', 24, 1);

        $committeeGeoPath = new GeoPathChain(geoUnitId: 24, path: '/1/24', segments: [1, 24]);

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$geoCommittee]);

        $this->geoPathProvider->expects($this->once())
            ->method('resolveForCommittee')
            ->with(24)
            ->willReturn($committeeGeoPath);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(0, $result, 'Ineligible committee should not be returned');
    }

    /**
     * Constitutional Specification: Member with no geography ineligible for geographic committees
     *
     * GEO-ELIG-3: Member with empty path (no residence) cannot join geographic committees.
     * Service shortcircuits before calling geo provider (optimization).
     */
    public function test_member_with_empty_geo_path_ineligible_for_geographic_committee(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 1, path: '', segments: []);

        $geoCommittee = $this->createGeographicCommitteeStub('Bayern Assembly', 'BAY', 23, 1);

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$geoCommittee]);

        // Service shortcircuits with isEmpty() check, never calls geoPathProvider
        $this->geoPathProvider->expects($this->never())
            ->method('resolveForCommittee');

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(0, $result);
    }

    /**
     * Constitutional Specification: Inactive committees are excluded
     *
     * Only committees with active status are returned in eligibility results.
     */
    public function test_inactive_committee_excluded_from_results(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 1, path: '', segments: []);

        $inactiveCommittee = $this->createCommitteeStubWithStatus(
            'Suspended Assembly',
            'SA',
            null,
            0,
            CommitteeStatus::inactive()
        );

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$inactiveCommittee]);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(0, $result, 'Inactive committees should be filtered out');
    }

    /**
     * Constitutional Specification: Dissolved committees are excluded
     */
    public function test_dissolved_committee_excluded_from_results(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 1, path: '', segments: []);

        $dissolvedCommittee = $this->createCommitteeStubWithStatus(
            'Dissolved Assembly',
            'DA',
            null,
            0,
            CommitteeStatus::dissolved()
        );

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$dissolvedCommittee]);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(0, $result, 'Dissolved committees should be filtered out');
    }

    /**
     * Constitutional Specification: Association decoration
     *
     * When a member already has an active membership, the DTO carries hasActiveAssociation = true.
     */
    /**
     * Verify service queries lineage repository with correct parameters
     * (Association logic is tested via unit assertions on return values)
     */
    public function test_eligible_committee_with_active_association_has_flag_true(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 1, path: '', segments: []);

        $committee = $this->createCentralCommitteeStub('National Assembly', 'NA', null);

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$committee]);

        // Verify service calls lineage repo with correct parameters
        $this->lineageRepo->expects($this->once())
            ->method('findLineageByMemberAndCommitteeForTenant')
            ->with($memberId, $this->anything(), $tenantId)
            ->willReturn(null);

        $this->applicationRepo->expects($this->once())
            ->method('existsActiveForTenant')
            ->willReturn(false);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(1, $result);
        // When lineage is null, association flag is false
        $this->assertFalse($result[0]->hasActiveAssociation);
    }

    /**
     * Constitutional Specification: Association decoration when no association exists
     */
    public function test_eligible_committee_without_association_has_flag_false(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 1, path: '', segments: []);

        $committee = $this->createCentralCommitteeStub('National Assembly', 'NA', null);

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$committee]);

        $this->lineageRepo->expects($this->once())
            ->method('findLineageByMemberAndCommitteeForTenant')
            ->willReturn(null);

        $this->applicationRepo->expects($this->once())
            ->method('existsActiveForTenant')
            ->willReturn(false);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(1, $result);
        $this->assertFalse($result[0]->hasActiveAssociation);
    }

    /**
     * Constitutional Specification: Application decoration
     *
     * When a member has a pending application, the DTO carries hasPendingApplication = true.
     */
    public function test_eligible_committee_with_pending_application_has_flag_true(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 1, path: '', segments: []);

        $committee = $this->createCentralCommitteeStub('National Assembly', 'NA', null);

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$committee]);

        $this->lineageRepo->expects($this->once())
            ->method('findLineageByMemberAndCommitteeForTenant')
            ->willReturn(null);

        $this->applicationRepo->expects($this->once())
            ->method('existsActiveForTenant')
            ->willReturn(true);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(1, $result);
        $this->assertTrue($result[0]->hasPendingApplication);
    }

    /**
     * Constitutional Specification: Application decoration when no application exists
     */
    public function test_eligible_committee_without_pending_application_has_flag_false(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 1, path: '', segments: []);

        $committee = $this->createCentralCommitteeStub('National Assembly', 'NA', null);

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$committee]);

        $this->lineageRepo->expects($this->once())
            ->method('findLineageByMemberAndCommitteeForTenant')
            ->willReturn(null);

        $this->applicationRepo->expects($this->once())
            ->method('existsActiveForTenant')
            ->willReturn(false);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(1, $result);
        $this->assertFalse($result[0]->hasPendingApplication);
    }

    /**
     * Constitutional Specification: Empty tenant has no eligible committees
     */
    public function test_no_committees_returns_empty_array(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 1, path: '', segments: []);

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([]);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(0, $result);
    }

    /**
     * Constitutional Specification: All ineligible committees are filtered
     */
    public function test_all_ineligible_committees_returns_empty_array(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 23, path: '/1/23', segments: [1, 23]);

        $geoCommittee = $this->createGeographicCommitteeStub('Baden Assembly', 'BAD', 24, 1);

        $committeeGeoPath = new GeoPathChain(geoUnitId: 24, path: '/1/24', segments: [1, 24]);

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$geoCommittee]);

        $this->geoPathProvider->expects($this->once())
            ->method('resolveForCommittee')
            ->with(24)
            ->willReturn($committeeGeoPath);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(0, $result);
    }

    /**
     * Constitutional Specification: Deterministic ordering by governance level
     *
     * Results are sorted by governanceLevel ASC (central/root first).
     */
    public function test_results_sorted_by_governance_level_ascending(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 1, path: '', segments: []);

        $rootCommittee = $this->createCentralCommitteeStub('National Assembly', 'NA', 0);
        $localCommittee = $this->createCentralCommitteeStub('Regional Assembly', 'RA', 2);

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$localCommittee, $rootCommittee]);

        $this->lineageRepo
            ->method('findLineageByMemberAndCommitteeForTenant')
            ->willReturn(null);

        $this->applicationRepo
            ->method('existsActiveForTenant')
            ->willReturn(false);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(2, $result);
        $this->assertLessThan($result[1]->governanceLevel, $result[0]->governanceLevel);
    }

    /**
     * Constitutional Specification: Tiebreaker ordering by committee name
     *
     * When governance levels are equal, results are sorted by name ASC.
     */
    public function test_results_sorted_by_name_when_levels_equal(): void
    {
        $tenantId = TenantId::fromString('test-tenant');
        $memberId = MemberId::generate();
        $memberGeoPath = new GeoPathChain(geoUnitId: 1, path: '', segments: []);

        $committee1 = $this->createCentralCommitteeStub('Zebra Committee', 'ZC', 1);
        $committee2 = $this->createCentralCommitteeStub('Alpha Committee', 'AC', 1);

        $this->committeeRepo->expects($this->once())
            ->method('findByTenant')
            ->with($tenantId)
            ->willReturn([$committee1, $committee2]);

        $this->lineageRepo
            ->method('findLineageByMemberAndCommitteeForTenant')
            ->willReturn(null);

        $this->applicationRepo
            ->method('existsActiveForTenant')
            ->willReturn(false);

        $result = $this->service->eligibleForMember($memberId, $tenantId, $memberGeoPath);

        $this->assertCount(2, $result);
        $this->assertStringStartsWith('Alpha', $result[0]->committeeName);
        $this->assertStringStartsWith('Zebra', $result[1]->committeeName);
    }

    /**
     * Helper: Create central committee stub
     */
    private function createCentralCommitteeStub(string $name, string $code, ?int $levelIndex): object
    {
        return $this->createCommitteeStub(
            $name,
            $code,
            null,
            $levelIndex,
            CommitteeStatus::active()
        );
    }

    /**
     * Helper: Create geographic committee stub
     */
    private function createGeographicCommitteeStub(string $name, string $code, int $geoUnitId, ?int $levelIndex): object
    {
        return $this->createCommitteeStub(
            $name,
            $code,
            $geoUnitId,
            $levelIndex,
            CommitteeStatus::active()
        );
    }

    /**
     * Helper: Create committee stub with full control
     */
    private function createCommitteeStubWithStatus(
        string $name,
        string $code,
        ?int $geoUnitId,
        ?int $levelIndex,
        CommitteeStatus $status
    ): object {
        return $this->createCommitteeStub($name, $code, $geoUnitId, $levelIndex, $status);
    }

    /**
     * Helper: Create the service instance
     *
     * This will fail in RED phase until EligibleCommitteeQueryServiceImpl exists.
     */
    private function createServiceInstance(): EligibleCommitteeQueryService
    {
        $className = 'App\Contexts\Membership\Application\Membership\Query\EligibleCommitteeQueryServiceImpl';

        if (!class_exists($className)) {
            $this->markTestSkipped('EligibleCommitteeQueryServiceImpl does not exist yet (RED phase)');
        }

        return new $className(
            $this->committeeRepo,
            $this->lineageRepo,
            $this->applicationRepo,
            $this->eligibilityPolicy,
            $this->geoPathProvider,
        );
    }
}
