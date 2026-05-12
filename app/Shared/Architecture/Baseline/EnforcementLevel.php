<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Baseline;

enum EnforcementLevel: string
{
    case REQUIRED = 'required';
    case TRANSITIONAL = 'transitional';
    case LEGACY_ALLOWED = 'legacy_allowed';
    case DEPRECATED = 'deprecated';
    case ADVISORY = 'advisory';

    /**
     * REQUIRED:  Must pass, CI blocks.
     * TRANSITIONAL: Only NEW violations blocked.
     * LEGACY_ALLOWED: Documented debt, tolerated.
     * DEPRECATED: Allowed but emits warnings; new usages blocked.
     * ADVISORY: Informational only.
     */
}
