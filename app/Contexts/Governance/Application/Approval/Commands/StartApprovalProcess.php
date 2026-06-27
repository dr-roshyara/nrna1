<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Approval\Commands;

use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Governance\Domain\Approval\ValueObjects\IdempotencyKey;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;

final readonly class StartApprovalProcess
{
    public function __construct(
        public ApprovalId $approvalId,
        public GovernanceDecisionId $decisionId,
        public array $requiredApprovals,
        public IdempotencyKey $idempotencyKey,
    ) {}
}
