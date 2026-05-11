<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Approval;

use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Governance\Domain\Approval\ValueObjects\IdempotencyKey;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;
use DateTimeImmutable;

interface ApprovalProcessRepository
{
    public function findById(ApprovalId $id): ?ApprovalProcessState;

    public function findByDecisionId(GovernanceDecisionId $decisionId): ?ApprovalProcessState;

    public function findByIdempotencyKey(IdempotencyKey $key): ?ApprovalProcessState;

    /** @return ApprovalProcessState[] */
    public function findExpiredPending(DateTimeImmutable $cutoff): array;

    public function save(ApprovalProcessState $state): void;
}
