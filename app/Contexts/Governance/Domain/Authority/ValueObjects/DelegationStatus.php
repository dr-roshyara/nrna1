<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Authority\ValueObjects;

enum DelegationStatus: string
{
    case ACTIVE = 'ACTIVE';
    case REVOKED = 'REVOKED';
}
