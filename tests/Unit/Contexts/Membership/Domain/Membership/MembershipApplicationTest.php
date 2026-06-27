<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Membership;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\MembershipApplication;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationStatus;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipApplicationId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

final class MembershipApplicationTest extends TestCase
{
    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');
    }

    public function test_can_submit_residence_application_when_eligible(): void
    {
        $id = MembershipApplicationId::generate();
        $memberId = MemberId::generate();
        $committeeId = CommitteeId::generate();

        $application = MembershipApplication::submit(
            id: $id,
            tenantId: $this->tenantId,
            memberId: $memberId,
            committeeId: $committeeId,
            reason: ApplicationReason::RESIDENCE,
            exceptionJustification: null,
            isEligible: true,
        );

        self::assertTrue($application->status()->equals(ApplicationStatus::SUBMITTED));
        self::assertTrue($application->memberId()->equals($memberId));
        self::assertTrue($application->committeeId()->equals($committeeId));
    }

    public function test_rejects_ineligible_residence_application(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Member not eligible for this committee');

        MembershipApplication::submit(
            id: MembershipApplicationId::generate(),
            tenantId: $this->tenantId,
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            reason: ApplicationReason::RESIDENCE,
            exceptionJustification: null,
            isEligible: false,
        );
    }

    public function test_exception_application_requires_justification(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Exception applications require justification');

        MembershipApplication::submit(
            id: MembershipApplicationId::generate(),
            tenantId: $this->tenantId,
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            reason: ApplicationReason::EXCEPTION,
            exceptionJustification: null,
            isEligible: false,
        );
    }

    public function test_manual_application_skips_eligibility_check(): void
    {
        $id = MembershipApplicationId::generate();

        $application = MembershipApplication::submit(
            id: $id,
            tenantId: $this->tenantId,
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            isEligible: false, // ignored for MANUAL
        );

        self::assertTrue($application->status()->equals(ApplicationStatus::SUBMITTED));
    }

    public function test_approve_transitions_to_approved_and_returns_association(): void
    {
        $id = MembershipApplicationId::generate();
        $memberId = MemberId::generate();
        $committeeId = CommitteeId::generate();

        $application = MembershipApplication::submit(
            id: $id,
            tenantId: $this->tenantId,
            memberId: $memberId,
            committeeId: $committeeId,
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            isEligible: false,
        );

        $association = $application->approve(MemberId::generate());

        self::assertTrue($application->status()->equals(ApplicationStatus::APPROVED));
        self::assertInstanceOf(CommitteeAssociation::class, $association);
        self::assertTrue($association->memberId->equals($memberId));
        self::assertTrue($association->committeeId->equals($committeeId));
        self::assertTrue($association->status->equals(MembershipStatus::ACTIVE));
    }

    public function test_reject_transitions_to_rejected(): void
    {
        $application = MembershipApplication::submit(
            id: MembershipApplicationId::generate(),
            tenantId: $this->tenantId,
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            isEligible: false,
        );

        $application->reject(MemberId::generate());

        self::assertTrue($application->status()->equals(ApplicationStatus::REJECTED));
    }

    public function test_cannot_approve_already_approved(): void
    {
        $this->expectException(\DomainException::class);

        $application = MembershipApplication::submit(
            id: MembershipApplicationId::generate(),
            tenantId: $this->tenantId,
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            isEligible: false,
        );

        $application->approve(MemberId::generate());
        $application->approve(MemberId::generate());
    }

    public function test_cannot_approve_rejected_application(): void
    {
        $this->expectException(\DomainException::class);

        $application = MembershipApplication::submit(
            id: MembershipApplicationId::generate(),
            tenantId: $this->tenantId,
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            isEligible: false,
        );

        $application->reject(MemberId::generate());
        $application->approve(MemberId::generate());
    }

    public function test_cannot_reject_already_rejected_application(): void
    {
        $this->expectException(\DomainException::class);

        $application = MembershipApplication::submit(
            id: MembershipApplicationId::generate(),
            tenantId: $this->tenantId,
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            isEligible: false,
        );

        $application->reject(MemberId::generate());
        $application->reject(MemberId::generate());
    }

    public function test_submission_emits_CommitteeMembershipApplicationSubmitted_event(): void
    {
        $id = MembershipApplicationId::generate();

        $application = MembershipApplication::submit(
            id: $id,
            tenantId: $this->tenantId,
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            isEligible: false,
        );

        $events = $application->releaseEvents();

        self::assertCount(1, $events);
        self::assertStringContainsString('CommitteeMembershipApplicationSubmitted', $events[0]::class);
    }

    public function test_approval_emits_CommitteeMembershipApplicationApproved_event(): void
    {
        $application = MembershipApplication::submit(
            id: MembershipApplicationId::generate(),
            tenantId: $this->tenantId,
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            reason: ApplicationReason::MANUAL,
            exceptionJustification: null,
            isEligible: false,
        );

        $application->releaseEvents(); // Clear submission events

        $application->approve(MemberId::generate());

        $events = $application->releaseEvents();

        self::assertCount(1, $events);
        self::assertStringContainsString('CommitteeMembershipApplicationApproved', $events[0]::class);
    }
}
