<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

enum StructuralOperationalState: string
{
    case ACTIVE = 'ACTIVE';
    case SUSPENDED = 'SUSPENDED';
    case DISSOLVED = 'DISSOLVED';
}
