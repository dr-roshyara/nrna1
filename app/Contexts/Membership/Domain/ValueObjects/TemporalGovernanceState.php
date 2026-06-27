<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

enum TemporalGovernanceState: string
{
    case VALID = 'VALID';
    case EXPIRING = 'EXPIRING';
    case EXPIRED = 'EXPIRED';
    case CARETAKER = 'CARETAKER';
    case NO_TERM = 'NO_TERM';
    case NOT_YET_ACTIVE = 'NOT_YET_ACTIVE';
}
