<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Kernel;

final class UuidGovernanceDecisionIdGenerator implements GovernanceDecisionIdGenerator
{
    public function next(): GovernanceDecisionId
    {
        return GovernanceDecisionId::generate();
    }
}
