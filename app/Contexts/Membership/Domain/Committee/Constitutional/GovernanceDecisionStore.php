<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionId;

interface GovernanceDecisionStore
{
    public function store(GovernanceDecisionSnapshot $snapshot): void;
    public function findById(GovernanceDecisionId $id): ?GovernanceDecisionSnapshot;
}
