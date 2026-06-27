<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

enum ConstitutionalLegitimacy: string
{
    case LEGITIMATE = 'LEGITIMATE';
    case REVOKED = 'REVOKED';
    case DISPUTED = 'DISPUTED';
    case UNAUTHORIZED = 'UNAUTHORIZED';
}
