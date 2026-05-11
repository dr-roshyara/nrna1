<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Approval;

use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalStatus;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DateTimeImmutable;

final readonly class ApprovalProcessState
{
    private function __construct(
        private ApprovalId $approvalId,
        private GovernanceDecisionId $decisionId,
        private ApprovalStatus $status,
        private array $requiredApprovals,
        private array $receivedApprovals,
        private ?MemberId $rejectedBy,
        private ?string $rejectionReason,
        private DateTimeImmutable $createdAt,
        private int $version,
    ) {}

    public static function start(
        ApprovalId $approvalId,
        GovernanceDecisionId $decisionId,
        array $requiredApprovals,
        DateTimeImmutable $now,
    ): self {
        return new self(
            approvalId: $approvalId,
            decisionId: $decisionId,
            status: ApprovalStatus::PENDING,
            requiredApprovals: $requiredApprovals,
            receivedApprovals: [],
            rejectedBy: null,
            rejectionReason: null,
            createdAt: $now,
            version: 1,
        );
    }

    public function addApproval(CommitteeId $committeeId, MemberId $grantedBy, DateTimeImmutable $grantedAt): self
    {
        if ($this->status->isTerminal()) {
            throw new \DomainException(
                "Cannot add approval: process is in terminal state [{$this->status->value}]"
            );
        }

        if (array_key_exists($committeeId->value(), $this->receivedApprovals)) {
            throw new \DomainException(
                "Committee [{$committeeId->value()}] has already approved this process"
            );
        }

        $received = $this->receivedApprovals;
        $received[$committeeId->value()] = [
            'grantedBy' => $grantedBy->value(),
            'grantedAt' => $grantedAt->format(\DateTimeInterface::ATOM),
        ];

        $allApproved = count($received) === count($this->requiredApprovals);
        $newStatus = $allApproved ? ApprovalStatus::APPROVED : ApprovalStatus::PENDING;

        return new self(
            approvalId: $this->approvalId,
            decisionId: $this->decisionId,
            status: $newStatus,
            requiredApprovals: $this->requiredApprovals,
            receivedApprovals: $received,
            rejectedBy: null,
            rejectionReason: null,
            createdAt: $this->createdAt,
            version: $this->version + 1,
        );
    }

    public function reject(MemberId $rejectedBy, string $reason, DateTimeImmutable $rejectedAt): self
    {
        if ($this->status->isTerminal()) {
            throw new \DomainException(
                "Cannot reject: process is in terminal state [{$this->status->value}]"
            );
        }

        if (trim($reason) === '') {
            throw new \DomainException('Rejection reason cannot be empty');
        }

        return new self(
            approvalId: $this->approvalId,
            decisionId: $this->decisionId,
            status: ApprovalStatus::REJECTED,
            requiredApprovals: $this->requiredApprovals,
            receivedApprovals: $this->receivedApprovals,
            rejectedBy: $rejectedBy,
            rejectionReason: $reason,
            createdAt: $this->createdAt,
            version: $this->version + 1,
        );
    }

    public function expire(DateTimeImmutable $expiredAt): self
    {
        if ($this->status->isTerminal()) {
            throw new \DomainException(
                "Cannot expire: process is in terminal state [{$this->status->value}]"
            );
        }

        return new self(
            approvalId: $this->approvalId,
            decisionId: $this->decisionId,
            status: ApprovalStatus::EXPIRED,
            requiredApprovals: $this->requiredApprovals,
            receivedApprovals: $this->receivedApprovals,
            rejectedBy: null,
            rejectionReason: null,
            createdAt: $this->createdAt,
            version: $this->version + 1,
        );
    }

    public function isFullyApproved(): bool
    {
        return $this->status === ApprovalStatus::APPROVED;
    }

    public function approvalId(): ApprovalId
    {
        return $this->approvalId;
    }

    public function decisionId(): GovernanceDecisionId
    {
        return $this->decisionId;
    }

    public function status(): ApprovalStatus
    {
        return $this->status;
    }

    /** @return CommitteeId[] */
    public function requiredApprovals(): array
    {
        return $this->requiredApprovals;
    }

    public function receivedApprovals(): array
    {
        return $this->receivedApprovals;
    }

    public function rejectedBy(): ?MemberId
    {
        return $this->rejectedBy;
    }

    public function rejectionReason(): ?string
    {
        return $this->rejectionReason;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function version(): int
    {
        return $this->version;
    }
}
