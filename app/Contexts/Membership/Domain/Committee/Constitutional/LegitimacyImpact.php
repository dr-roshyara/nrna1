<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

enum LegitimacyImpact: string
{
    case VALID = 'valid';
    case QUESTIONABLE = 'questionable';
    case INVALID = 'invalid';
}
