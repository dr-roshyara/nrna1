<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Rule;

enum RuleSeverity: int
{
    case INFO = 0;
    case WARNING = 1;
    case ERROR = 2;
    case BLOCKER = 3;
}
