<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\ValueObjects;

enum Legitimacy: string
{
    case LEGITIMATE = 'LEGITIMATE';
    case REVOKED = 'REVOKED';
    case DISPUTED = 'DISPUTED';
    case UNAUTHORIZED = 'UNAUTHORIZED';
}
