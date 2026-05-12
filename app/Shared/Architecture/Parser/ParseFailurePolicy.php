<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Parser;

enum ParseFailurePolicy: string
{
    case FAIL_FAST = 'fail_fast';
    case WARN_AND_SKIP = 'warn_and_skip';
    case LEGACY_FALLBACK = 'legacy_fallback';
}
