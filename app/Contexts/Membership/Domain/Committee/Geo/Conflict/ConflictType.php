<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Conflict;

enum ConflictType: string
{
    case MULTI_AUTHORITY = 'multi_authority';
    case EXCEPTION_PRESENT = 'exception_present';
    case OVERRIDE_PRESENT = 'override_present';
    case DIRECT_VS_DELEGATED = 'direct_vs_delegated';
}
