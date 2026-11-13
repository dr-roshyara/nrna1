<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Domain;

/**
 * The platform-level contexts that receive a ContextPersonRef (D-225 IG-1). A closed list: adding a context is a
 * decision, not a configuration.
 */
enum PlatformContext: string
{
    case ReferenceGeography = 'reference-geography';
}
