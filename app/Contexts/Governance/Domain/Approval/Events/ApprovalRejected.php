<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Approval\Events;

use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DateTimeImmutable;

final readonly class ApprovalRejected
{
    private function __construct(
        private ApprovalId $approvalId,
        private CommitteeId $rejectingCommitteeId,
        private MemberId $rejectedBy,
        private DateTimeImmutable $rejectedAt,
        private string $reason,
    ) {}

    public static function occur(
        ApprovalId $approvalId,
        CommitteeId $rejectingCommitteeId,
        MemberId $rejectedBy,
        DateTimeImmutable $rejectedAt,
        string $reason,
    ): self {
        return new self($approvalId, $rejectingCommitteeId, $rejectedBy, $rejectedAt, $reason);
    }

    public function approvalId(): ApprovalId
    {
        return $this->approvalId;
    }

    public function rejectingCommitteeId(): CommitteeId
    {
        return $this->rejectingCommitteeId;
    }

    public function rejectedBy(): MemberId
    {
        return $this->rejectedBy;
    }

    public function rejectedAt(): DateTimeImmutable
    {
        return $this->rejectedAt;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
