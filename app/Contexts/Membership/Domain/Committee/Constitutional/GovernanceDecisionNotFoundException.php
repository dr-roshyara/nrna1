<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

final class GovernanceDecisionNotFoundException extends \DomainException
{
    public function __construct(string $decisionId)
    {
        parent::__construct("Governance decision not found: {$decisionId}");
    }
}
