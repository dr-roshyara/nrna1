<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Resolution;

enum AuthorityResolutionType: string
{
    case EXCEPTION = 'exception';
    case OVERRIDE = 'override';
    case DIRECT = 'direct';
    case DELEGATED = 'delegated';
    case NONE = 'none';
}
