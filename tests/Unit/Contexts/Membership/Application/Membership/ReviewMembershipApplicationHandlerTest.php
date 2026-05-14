<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Membership;

use App\Contexts\Membership\Application\Membership\ApplyForCommitteeMembership\ApplyForCommitteeMembershipCommand;
use App\Contexts\Membership\Application\Membership\ApplyForCommitteeMembership\ApplyForCommitteeMembershipHandler as ApplyHandler;
use App\Contexts\Membership\Application\Membership\ReviewMembershipApplication\ReviewMembershipApplicationCommand;
use App\Contexts\Membership\Application\Membership\ReviewMembershipApplication\ReviewMembershipApplicationHandler;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationStatus;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\FakeCommitteeEligibilityPolicy;
use Tests\Doubles\FakeEventBus;
use Tests\Doubles\InMemoryCommitteeAssociationRepository;
use Tests\Doubles\InMemoryMembershipApplicationRepository;
use Tests\Doubles\InMemoryMembershipLineageRepository;

final class ReviewMembershipApplicationHandlerTest extends TestCase
{
    private ReviewMembershipApplicationHandler $reviewHandler;

    private ApplyHandler $applyHandler;

    private InMemoryMembershipApplicationRepository $repository;

    private InMemoryCommitteeAssociationRepository $associationRepository;

    private InMemoryMembershipLineageRepository $lineageRepository;

    private FakeEventBus $eventBus;

    private FakeCommitteeEligibilityPolicy $eligibilityPolicy;

    private TenantId $tenantId;

    protected function setUp(): void
    {
        $this->repository = new InMemoryMembershipApplicationRepository();
        $this->associationRepository = new InMemoryCommitteeAssociationRepository();
        $this->lineageRepository = new InMemoryMembershipLineageRepository();
        $this->eventBus = new FakeEventBus();
        $this->eligibilityPolicy = new FakeCommitteeEligibilityPolicy();
        $this->tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');

        $this->applyHandler = new ApplyHandler(
            $this->repository,
            $this->lineageRepository,
            $this->eventBus,
            $this->eligibilityPolicy,
        );

        $this->reviewHandler = new ReviewMembershipApplicationHandler(
            $this->repository,
            $this->associationRepository,
            $this->lineageRepository,
            $this->eventBus,
        );
    }

    public function test_approve_creates_committee_association(): void
    {
        $memberId = MemberId::generate();
        $committeeId = 'committee-1';

        $this->eligibilityPolicy->setEligible(true);

        // Apply for membership first
        $applyCommand = new ApplyForCommitteeMembershipCommand(
            tenantId: $this->tenantId,
            memberId: $memberId->value(),
            committeeId: $committeeId,
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            memberGeoPath: new GeoPathChain(1, '/1', [1]),
            committeeGeoPath: new GeoPathChain(1, '/1', [1]),
        );

        $applicationId = $this->applyHandler->handle($applyCommand);

        // Clear events from apply
        $this->eventBus->reset();

        // Now review and approve
        $reviewCommand = new ReviewMembershipApplicationCommand(
            tenantId: $this->tenantId,
            applicationId: $applicationId->value(),
            action: 'APPROVE',
            reviewedBy: MemberId::generate()->value(),
        );

        $association = $this->reviewHandler->handle($reviewCommand);

        // Assertions
        self::assertInstanceOf(CommitteeAssociation::class, $association);
        self::assertTrue($association->memberId->equals($memberId));
        self::assertEquals('committee-1', $association->committeeId->value());
        self::assertTrue($association->status->equals(MembershipStatus::ACTIVE));

        // Application should be in APPROVED state
        $application = $this->repository->getOrFailForTenant($applicationId, $this->tenantId);
        self::assertTrue($application->status()->equals(ApplicationStatus::APPROVED));

        // Association should be saved to association repository (for Elections context)
        $committeeId = CommitteeId::fromString('committee-1');
        $activeAssociations = $this->associationRepository->findActiveByCommitteeForTenant($committeeId, $this->tenantId);
        self::assertCount(1, $activeAssociations);
        self::assertTrue($activeAssociations[0]->memberId->equals($memberId));

        // Event should be emitted
        self::assertTrue(
            $this->eventBus->hasPublished('CommitteeMembershipApplicationApproved')
        );

        // Lineage should be created and saved
        self::assertCount(1, $this->lineageRepository->findAllByMemberForTenant($memberId, $this->tenantId));
        $lineage = $this->lineageRepository->findAllByMemberForTenant($memberId, $this->tenantId)[0];
        self::assertTrue($lineage->memberId->equals($memberId));
        self::assertTrue($lineage->committeeId->equals($committeeId));
        self::assertEquals(MembershipStatus::ACTIVE, $lineage->currentStatus());
    }

    public function test_reject_marks_application_rejected(): void
    {
        $memberId = MemberId::generate();
        $committeeId = 'committee-1';

        $this->eligibilityPolicy->setEligible(true);

        // Apply for membership first
        $applyCommand = new ApplyForCommitteeMembershipCommand(
            tenantId: $this->tenantId,
            memberId: $memberId->value(),
            committeeId: $committeeId,
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            memberGeoPath: new GeoPathChain(1, '/1', [1]),
            committeeGeoPath: new GeoPathChain(1, '/1', [1]),
        );

        $applicationId = $this->applyHandler->handle($applyCommand);

        // Clear events from apply
        $this->eventBus->reset();

        // Now review and reject
        $reviewCommand = new ReviewMembershipApplicationCommand(
            tenantId: $this->tenantId,
            applicationId: $applicationId->value(),
            action: 'REJECT',
            reviewedBy: MemberId::generate()->value(),
        );

        $result = $this->reviewHandler->handle($reviewCommand);

        // Rejection returns null
        self::assertNull($result);

        // Application should be in REJECTED state
        $application = $this->repository->getOrFailForTenant($applicationId, $this->tenantId);
        self::assertTrue($application->status()->equals(ApplicationStatus::REJECTED));

        // Event should be emitted
        self::assertTrue(
            $this->eventBus->hasPublished('CommitteeMembershipApplicationRejected')
        );
    }

    public function test_cannot_approve_already_approved(): void
    {
        $this->expectException(\DomainException::class);

        $memberId = MemberId::generate();
        $committeeId = 'committee-1';

        $this->eligibilityPolicy->setEligible(true);

        // Apply for membership
        $applyCommand = new ApplyForCommitteeMembershipCommand(
            tenantId: $this->tenantId,
            memberId: $memberId->value(),
            committeeId: $committeeId,
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            memberGeoPath: new GeoPathChain(1, '/1', [1]),
            committeeGeoPath: new GeoPathChain(1, '/1', [1]),
        );

        $applicationId = $this->applyHandler->handle($applyCommand);

        $reviewer = MemberId::generate();

        // Approve once
        $reviewCommand = new ReviewMembershipApplicationCommand(
            tenantId: $this->tenantId,
            applicationId: $applicationId->value(),
            action: 'APPROVE',
            reviewedBy: $reviewer->value(),
        );

        $this->reviewHandler->handle($reviewCommand);

        // Try to approve again — should throw
        $this->reviewHandler->handle($reviewCommand);
    }

    public function test_cannot_approve_rejected_application(): void
    {
        $this->expectException(\DomainException::class);

        $memberId = MemberId::generate();
        $committeeId = 'committee-1';

        $this->eligibilityPolicy->setEligible(true);

        // Apply for membership
        $applyCommand = new ApplyForCommitteeMembershipCommand(
            tenantId: $this->tenantId,
            memberId: $memberId->value(),
            committeeId: $committeeId,
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            memberGeoPath: new GeoPathChain(1, '/1', [1]),
            committeeGeoPath: new GeoPathChain(1, '/1', [1]),
        );

        $applicationId = $this->applyHandler->handle($applyCommand);

        $reviewer = MemberId::generate();

        // Reject
        $rejectCommand = new ReviewMembershipApplicationCommand(
            tenantId: $this->tenantId,
            applicationId: $applicationId->value(),
            action: 'REJECT',
            reviewedBy: $reviewer->value(),
        );

        $this->reviewHandler->handle($rejectCommand);

        // Try to approve rejected application — should throw
        $approveCommand = new ReviewMembershipApplicationCommand(
            tenantId: $this->tenantId,
            applicationId: $applicationId->value(),
            action: 'APPROVE',
            reviewedBy: $reviewer->value(),
        );

        $this->reviewHandler->handle($approveCommand);
    }
}
