<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Approval\Commands;

use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;

final readonly class AbortDecision
{
    public function __construct(
        public GovernanceDecisionId $decisionId,
        public ApprovalId $approvalId,
        public string $reason,
    ) {}
}
