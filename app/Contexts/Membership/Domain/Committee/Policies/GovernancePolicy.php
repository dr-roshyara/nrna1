<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Policies;

use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;
use DomainException;

final readonly class GovernancePolicy
{
    public function __construct(private GovernanceMatrix $matrix) {}

    public function assertAllowed(GovernanceAssignment $a): void
    {
        if (!$this->matrix->isAllowed($a->governanceLevel, $a->geoLevel)) {
            throw new DomainException(sprintf(
                'Invalid governance assignment: %d × %d not allowed',
                $a->governanceLevel,
                $a->geoLevel
            ));
        }
    }
}
