<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\Events\CommitteeMembershipApplicationApproved;
use App\Contexts\Membership\Domain\Membership\Events\CommitteeMembershipApplicationRejected;
use App\Contexts\Membership\Domain\Membership\Events\CommitteeMembershipApplicationSubmitted;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationStatus;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipApplicationId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Shared\Domain\Concerns\RecordsEvents;
use DomainException;

final class MembershipApplication
{
    use RecordsEvents;

    private ApplicationStatus $status;

    private \DateTimeImmutable $submittedAt;

    private ?MemberId $reviewedBy = null;

    private ?\DateTimeImmutable $reviewedAt = null;

    private function __construct(
        private readonly MembershipApplicationId $id,
        private readonly TenantId $tenantId,
        private readonly MemberId $memberId,
        private readonly CommitteeId $committeeId,
        private readonly ApplicationReason $reason,
        private readonly ?string $exceptionJustification,
    ) {
    }

    public static function submit(
        MembershipApplicationId $id,
        TenantId $tenantId,
        MemberId $memberId,
        CommitteeId $committeeId,
        ApplicationReason $reason,
        ?string $exceptionJustification,
        bool $isEligible,
    ): self {
        // Validate RESIDENCE applications
        if ($reason === ApplicationReason::RESIDENCE && !$isEligible) {
            throw new DomainException('Member not eligible for this committee');
        }

        // Validate EXCEPTION applications require justification
        if ($reason === ApplicationReason::EXCEPTION && (null === $exceptionJustification || '' === trim($exceptionJustification))) {
            throw new DomainException('Exception applications require justification');
        }

        $application = new self($id, $tenantId, $memberId, $committeeId, $reason, $exceptionJustification);
        $application->status = ApplicationStatus::SUBMITTED;
        $application->submittedAt = new \DateTimeImmutable();

        $application->recordEvent(
            new CommitteeMembershipApplicationSubmitted(
                applicationId: $id->value(),
                memberId: $memberId->value(),
                committeeId: $committeeId->value(),
                reason: $reason->value,
                submittedAt: $application->submittedAt,
            )
        );

        return $application;
    }

    /**
     * Reconstitute from persisted state. Does NOT fire domain events.
     * For use by repository implementations only.
     */
    public static function reconstitute(
        MembershipApplicationId $id,
        TenantId $tenantId,
        MemberId $memberId,
        CommitteeId $committeeId,
        ApplicationReason $reason,
        ?string $exceptionJustification,
        ApplicationStatus $status,
        \DateTimeImmutable $submittedAt,
        ?MemberId $reviewedBy,
        ?\DateTimeImmutable $reviewedAt,
    ): self {
        $instance = new self($id, $tenantId, $memberId, $committeeId, $reason, $exceptionJustification);
        $instance->status      = $status;
        $instance->submittedAt = $submittedAt;
        $instance->reviewedBy  = $reviewedBy;
        $instance->reviewedAt  = $reviewedAt;
        return $instance;
    }

    public function approve(MemberId $reviewedBy): CommitteeAssociation
    {
        if (!$this->status->canTransitionTo(ApplicationStatus::APPROVED)) {
            throw new DomainException(
                sprintf('Cannot approve application in %s status', $this->status->value)
            );
        }

        $this->status = ApplicationStatus::APPROVED;
        $this->reviewedBy = $reviewedBy;
        $this->reviewedAt = new \DateTimeImmutable();

        $this->recordEvent(
            new CommitteeMembershipApplicationApproved(
                applicationId: $this->id->value(),
                reviewedBy: $reviewedBy->value(),
                reviewedAt: $this->reviewedAt,
            )
        );

        return CommitteeAssociation::create(
            memberId: $this->memberId,
            committeeId: $this->committeeId,
            associationType: $this->reason,
            associatedAt: $this->reviewedAt,
            status: MembershipStatus::ACTIVE,
        );
    }

    public function reject(MemberId $reviewedBy): void
    {
        if (!$this->status->canTransitionTo(ApplicationStatus::REJECTED)) {
            throw new DomainException(
                sprintf('Cannot reject application in %s status', $this->status->value)
            );
        }

        $this->status = ApplicationStatus::REJECTED;
        $this->reviewedBy = $reviewedBy;
        $this->reviewedAt = new \DateTimeImmutable();

        $this->recordEvent(
            new CommitteeMembershipApplicationRejected(
                applicationId: $this->id->value(),
                reviewedBy: $reviewedBy->value(),
                reviewedAt: $this->reviewedAt,
            )
        );
    }

    public function id(): MembershipApplicationId
    {
        return $this->id;
    }

    public function tenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function memberId(): MemberId
    {
        return $this->memberId;
    }

    public function committeeId(): CommitteeId
    {
        return $this->committeeId;
    }

    public function reason(): ApplicationReason
    {
        return $this->reason;
    }

    public function exceptionJustification(): ?string
    {
        return $this->exceptionJustification;
    }

    public function status(): ApplicationStatus
    {
        return $this->status;
    }

    public function submittedAt(): \DateTimeImmutable
    {
        return $this->submittedAt;
    }

    public function reviewedBy(): ?MemberId
    {
        return $this->reviewedBy;
    }

    public function reviewedAt(): ?\DateTimeImmutable
    {
        return $this->reviewedAt;
    }
}
