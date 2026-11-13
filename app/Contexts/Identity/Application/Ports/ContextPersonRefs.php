<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application\Ports;

use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Domain\ContextPersonRef;
use App\Contexts\Identity\Domain\PersonId;
use App\Contexts\Identity\Domain\PlatformContext;

/**
 * D-225 IG-1: one reference per (Person, context), issued on first request, then always the same.
 * There is deliberately NO reverse lookup (IG-3).
 */
interface ContextPersonRefs
{
    /** @throws IdentityUnavailable */
    public function refFor(PersonId $person, PlatformContext $context): ContextPersonRef;
}
