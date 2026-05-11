<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Approval\Events;

use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DateTimeImmutable;

final readonly class ApprovalGranted
{
    private function __construct(
        private ApprovalId $approvalId,
        private CommitteeId $approvingCommitteeId,
        private MemberId $grantedBy,
        private DateTimeImmutable $grantedAt,
        private string $notes,
    ) {}

    public static function occur(
        ApprovalId $approvalId,
        CommitteeId $approvingCommitteeId,
        MemberId $grantedBy,
        DateTimeImmutable $grantedAt,
        string $notes,
    ): self {
        return new self($approvalId, $approvingCommitteeId, $grantedBy, $grantedAt, $notes);
    }

    public function approvalId(): ApprovalId
    {
        return $this->approvalId;
    }

    public function approvingCommitteeId(): CommitteeId
    {
        return $this->approvingCommitteeId;
    }

    public function grantedBy(): MemberId
    {
        return $this->grantedBy;
    }

    public function grantedAt(): DateTimeImmutable
    {
        return $this->grantedAt;
    }

    public function notes(): string
    {
        return $this->notes;
    }
}
