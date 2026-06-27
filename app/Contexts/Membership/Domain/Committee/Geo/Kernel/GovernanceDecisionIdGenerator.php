<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Kernel;

interface GovernanceDecisionIdGenerator
{
    public function next(): GovernanceDecisionId;
}
