<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Approval\Events;

use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Governance\Domain\Approval\ValueObjects\IdempotencyKey;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;

final readonly class ApprovalRequested
{
    private function __construct(
        private ApprovalId $approvalId,
        private GovernanceDecisionId $decisionId,
        private array $requiredApprovals,
        private DateTimeImmutable $requestedAt,
        private IdempotencyKey $idempotencyKey,
    ) {}

    public static function occur(
        ApprovalId $approvalId,
        GovernanceDecisionId $decisionId,
        array $requiredApprovals,
        DateTimeImmutable $requestedAt,
        IdempotencyKey $idempotencyKey,
    ): self {
        return new self($approvalId, $decisionId, $requiredApprovals, $requestedAt, $idempotencyKey);
    }

    public function approvalId(): ApprovalId
    {
        return $this->approvalId;
    }

    public function decisionId(): GovernanceDecisionId
    {
        return $this->decisionId;
    }

    /** @return CommitteeId[] */
    public function requiredApprovals(): array
    {
        return $this->requiredApprovals;
    }

    public function requestedAt(): DateTimeImmutable
    {
        return $this->requestedAt;
    }

    public function idempotencyKey(): IdempotencyKey
    {
        return $this->idempotencyKey;
    }
}
