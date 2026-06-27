<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Committee\Policies;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeFacts;
use App\Contexts\Membership\Domain\ValueObjects\StructuralOperationalState;

final class OperationalStatePolicy
{
    public function evaluate(CommitteeFacts $facts): StructuralOperationalState
    {
        return match ($facts->operationalState) {
            'ACTIVE' => StructuralOperationalState::ACTIVE,
            'SUSPENDED' => StructuralOperationalState::SUSPENDED,
            'DISSOLVED' => StructuralOperationalState::DISSOLVED,
            default => throw new \ValueError("Invalid operational state: {$facts->operationalState}"),
        };
    }
}
