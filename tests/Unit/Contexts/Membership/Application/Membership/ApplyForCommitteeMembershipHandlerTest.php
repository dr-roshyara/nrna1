<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Membership;

use App\Contexts\Membership\Application\Membership\ApplyForCommitteeMembership\ApplyForCommitteeMembershipCommand;
use App\Contexts\Membership\Application\Membership\ApplyForCommitteeMembership\ApplyForCommitteeMembershipHandler;
use App\Contexts\Membership\Domain\Committee\Policies\EligibilityPolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipApplicationId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\FakeCommitteeEligibilityPolicy;
use Tests\Doubles\FakeEventBus;
use Tests\Doubles\InMemoryMembershipApplicationRepository;
use Tests\Doubles\InMemoryMembershipLineageRepository;

final class ApplyForCommitteeMembershipHandlerTest extends TestCase
{
    private ApplyForCommitteeMembershipHandler $handler;

    private InMemoryMembershipApplicationRepository $repository;

    private InMemoryMembershipLineageRepository $lineageRepository;

    private FakeEventBus $eventBus;

    private FakeCommitteeEligibilityPolicy $eligibilityPolicy;

    private TenantId $tenantId;

    protected function setUp(): void
    {
        $this->repository = new InMemoryMembershipApplicationRepository();
        $this->lineageRepository = new InMemoryMembershipLineageRepository();
        $this->eventBus = new FakeEventBus();
        $this->eligibilityPolicy = new FakeCommitteeEligibilityPolicy();
        $this->tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');

        $this->handler = new ApplyForCommitteeMembershipHandler(
            $this->repository,
            $this->lineageRepository,
            $this->eventBus,
            $this->eligibilityPolicy,
        );
    }

    public function test_residence_application_is_stored_and_event_is_emitted(): void
    {
        $memberId = MemberId::generate();
        $committeeId = 'committee-1';

        $this->eligibilityPolicy->setEligible(true);

        $command = new ApplyForCommitteeMembershipCommand(
            tenantId: $this->tenantId,
            memberId: $memberId->value(),
            committeeId: $committeeId,
            reason: ApplicationReason::RESIDENCE,
            exceptionJustification: null,
            memberGeoPath: new GeoPathChain(3, '/1/2/3', [1, 2, 3]),
            committeeGeoPath: new GeoPathChain(3, '/1/2/3', [1, 2, 3]),
        );

        $id = $this->handler->handle($command);

        self::assertInstanceOf(MembershipApplicationId::class, $id);

        $application = $this->repository->getOrFailForTenant($id, $this->tenantId);

        self::assertNotNull($application);
        self::assertTrue(
            $this->eventBus->hasPublished(
                'CommitteeMembershipApplicationSubmitted'
            )
        );
    }

    public function test_exception_requires_justification(): void
    {
        $this->expectException(\DomainException::class);

        $memberId = MemberId::generate();

        $command = new ApplyForCommitteeMembershipCommand(
            tenantId: $this->tenantId,
            memberId: $memberId->value(),
            committeeId: 'committee-1',
            reason: ApplicationReason::EXCEPTION,
            exceptionJustification: null,
            memberGeoPath: new GeoPathChain(1, '/1', [1]),
            committeeGeoPath: new GeoPathChain(2, '/1/2', [1, 2]),
        );

        $this->handler->handle($command);
    }

    public function test_manual_application_skips_eligibility_check(): void
    {
        $memberId = MemberId::generate();

        $command = new ApplyForCommitteeMembershipCommand(
            tenantId: $this->tenantId,
            memberId: $memberId->value(),
            committeeId: 'committee-1',
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            memberGeoPath: new GeoPathChain(1, '/1', [1]),
            committeeGeoPath: new GeoPathChain(9, '/9/9', [9, 9]),
        );

        $id = $this->handler->handle($command);

        self::assertInstanceOf(MembershipApplicationId::class, $id);
        self::assertFalse($this->eligibilityPolicy->wasEligibilityCalled());
    }

    public function test_ineligible_residence_application_is_rejected(): void
    {
        $this->expectException(\DomainException::class);

        $memberId = MemberId::generate();

        $this->eligibilityPolicy->setEligible(false);

        $command = new ApplyForCommitteeMembershipCommand(
            tenantId: $this->tenantId,
            memberId: $memberId->value(),
            committeeId: 'committee-1',
            reason: ApplicationReason::RESIDENCE,
            exceptionJustification: null,
            memberGeoPath: new GeoPathChain(1, '/1', [1]),
            committeeGeoPath: new GeoPathChain(2, '/2', [2]),
        );

        $this->handler->handle($command);
    }

    public function test_duplicate_application_is_rejected(): void
    {
        $this->expectException(\DomainException::class);

        $memberId = MemberId::generate();

        $this->eligibilityPolicy->setEligible(true);

        $command = new ApplyForCommitteeMembershipCommand(
            tenantId: $this->tenantId,
            memberId: $memberId->value(),
            committeeId: 'committee-1',
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            memberGeoPath: new GeoPathChain(1, '/1', [1]),
            committeeGeoPath: new GeoPathChain(1, '/1', [1]),
        );

        $this->handler->handle($command);

        // second attempt must fail
        $this->handler->handle($command);
    }
}
