<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\Commands\AssignMemberWithGeoCommand;
use App\Contexts\Membership\Application\Committee\Handlers\AssignMemberToCommitteeHandler;
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\GeoScope;
use App\Contexts\Membership\Domain\Committee\Policies\CommitteeEligibilityPolicy;
use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdiction;
use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdictionProvider;
use App\Contexts\Membership\Domain\Committee\Services\GeoSemanticProjectionBuilder;
use App\Contexts\Membership\Domain\Committee\Strategies\GeographicCommitteeStructure;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteePolicy;
use App\Contexts\Membership\Domain\Member\Member;
use App\Contexts\Membership\Domain\Member\ValueObjects\MemberResidenceGeoIdentity;
use App\Contexts\Membership\Domain\Member\ValueObjects\PersonalInfo;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\TenantId as MembershipTenantId;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\ValueObjects\NominationType;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Contexts\Shared\Domain\ValueObjects\TenantId as SharedTenantId;
use App\Shared\Domain\Events\EventBus;
use DomainException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AssignMemberToCommitteeHandlerTest extends TestCase
{
    private MemberRepositoryInterface $members;
    private CommitteeRepositoryInterface $committees;
    private GeographicJurisdictionProvider $provider;
    private GeoSemanticProjectionBuilder $projectionBuilder;
    private CommitteeEligibilityPolicy $policy;
    private EventBus $eventBus;
    private AssignMemberToCommitteeHandler $handler;
    private SharedTenantId $tenantId;
    private CommitteeId $committeeId;

    protected function setUp(): void
    {
        $this->tenantId = SharedTenantId::fromOrganisationId('org-1');
        $this->committeeId = CommitteeId::generate();

        $this->members = $this->createMock(MemberRepositoryInterface::class);
        $this->committees = $this->createMock(CommitteeRepositoryInterface::class);
        $this->provider = $this->createMock(GeographicJurisdictionProvider::class);
        $this->projectionBuilder = new GeoSemanticProjectionBuilder($this->provider);
        $this->policy = new CommitteeEligibilityPolicy();
        $this->eventBus = $this->createMock(EventBus::class);

        $this->handler = new AssignMemberToCommitteeHandler(
            $this->projectionBuilder,
            $this->policy,
            $this->members,
            $this->committees,
            $this->eventBus,
        );
    }

    #[Test]
    public function throws_when_member_not_found(): void
    {
        $this->members->method('find')->willReturn(null);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Member not found');

        $this->handler->handle($this->makeCommand());
    }

    #[Test]
    public function throws_when_committee_not_found(): void
    {
        $member = $this->createMember(100);
        $this->members->method('find')->willReturn($member);
        $this->committees->method('findForTenant')->willReturn(null);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Committee not found');

        $this->handler->handle($this->makeCommand());
    }

    #[Test]
    public function throws_when_member_has_no_residence_for_geographic_committee(): void
    {
        $member = $this->createMember(null);
        $committee = $this->createGeographicCommittee(23, new GeoReference('np.3'));

        $this->members->method('find')->willReturn($member);
        $this->committees->method('findForTenant')->willReturn($committee);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Member without residence geography');

        $this->handler->handle($this->makeCommand());
    }

    #[Test]
    public function assigns_member_to_central_committee_without_geo_check(): void
    {
        $member = $this->createMember(100);
        $committee = $this->createCentralCommittee();

        $this->members->method('find')->willReturn($member);
        $this->committees->method('findForTenant')->willReturn($committee);
        $this->committees->expects($this->once())->method('saveForTenant');
        $this->eventBus->expects($this->once())->method('dispatchAll');

        $this->handler->handle($this->makeCommand());

        $assignments = $committee->getAssignments();
        $this->assertCount(1, $assignments);
    }

    #[Test]
    public function throws_when_member_geo_not_in_committee_area(): void
    {
        $member = $this->createMember(456);
        $committee = $this->createGeographicCommittee(23, new GeoReference('np.3'));

        $this->members->method('find')->willReturn($member);
        $this->committees->method('findForTenant')->willReturn($committee);

        // Member in province 4 (path /2/99/456), committee in province 3 (path /1/23)
        $this->provider->method('resolve')
            ->willReturnMap([
                [456, new GeographicJurisdiction(456, 3, '4', 'NP', '/2/99/456')],
                [23, new GeographicJurisdiction(23, 2, '3', 'NP', '/1/23')],
            ]);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('not within');

        $this->handler->handle($this->makeCommand());
    }

    #[Test]
    public function assigns_member_when_residence_is_within_committee_area(): void
    {
        $member = $this->createMember(456);
        $committee = $this->createGeographicCommittee(23, new GeoReference('np.3'));

        $this->members->method('find')->willReturn($member);
        $this->committees->method('findForTenant')->willReturn($committee);

        // Member in province 3, district 23, ward 456 — within committee area (path /1/23/456)
        $this->provider->method('resolve')
            ->willReturnMap([
                [456, new GeographicJurisdiction(456, 3, '3', 'NP', '/1/23/456')],
                [23, new GeographicJurisdiction(23, 2, '3', 'NP', '/1/23')],
            ]);

        $this->committees->expects($this->once())->method('saveForTenant');
        $this->eventBus->expects($this->once())->method('dispatchAll');

        $this->handler->handle($this->makeCommand());

        $assignments = $committee->getAssignments();
        $this->assertCount(1, $assignments);
    }

    private function makeCommand(): AssignMemberWithGeoCommand
    {
        return new AssignMemberWithGeoCommand(
            committeeId: $this->committeeId,
            tenantId: $this->tenantId,
            memberId: MemberId::generate(),
            rolePath: RolePath::fromString('1'),
            nominationType: NominationType::elected(),
            electionDate: new \DateTimeImmutable('2026-01-15'),
        );
    }

    private function createMember(?int $residenceGeoUnitId): Member
    {
        $member = Member::register(
            MembershipTenantId::fromOrganisationId($this->tenantId->value()),
            PersonalInfo::create('Test Member', 'test@example.com'),
            MembershipTypeId::generate(),
        );

        if ($residenceGeoUnitId !== null) {
            $member->setResidenceGeoIdentity(
                new MemberResidenceGeoIdentity($residenceGeoUnitId),
            );
        }

        return $member;
    }

    private function createCentralCommittee(): Committee
    {
        return Committee::createCentral(
            id: $this->committeeId,
            tenantId: $this->tenantId,
            name: 'Central Committee',
            code: 'CC-001',
        );
    }

    private function createGeographicCommittee(int $geoUnitId, GeoReference $operationalGeo): Committee
    {
        $level = CommitteeLevel::create(
            index: 3,
            code: 'province',
            name: 'Province',
            geoPolicy: GeoPolicy::REQUIRED,
            geoScope: new GeoScope('NP'),
            roleLimits: ['president' => 1],
            minMembershipYears: 0,
            ageRange: null,
            genderRequirement: null,
        );

        $policy = new CommitteePolicy(
            type: CommitteeType::province(),
            structure: new GeographicCommitteeStructure(),
            level: $level,
        );

        return Committee::create(
            id: $this->committeeId,
            tenantId: $this->tenantId,
            policy: $policy,
            structureId: CommitteeStructureId::generate(),
            levelIndex: $level->index,
            levelName: $level->name,
            geoPolicy: $level->geoPolicy,
            geoScope: $level->geoScope,
            name: CommitteeName::fromString('Province Committee'),
            code: 'PC-001',
            operationalGeo: $operationalGeo,
            geoUnitId: $geoUnitId,
        );
    }
}
