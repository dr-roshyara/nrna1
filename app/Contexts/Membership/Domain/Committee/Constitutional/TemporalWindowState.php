<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

enum TemporalWindowState
{
    case PENDING;
    case ACTIVE;
    case EXPIRED;
}
