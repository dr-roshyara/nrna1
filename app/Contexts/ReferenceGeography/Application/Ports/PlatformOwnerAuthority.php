<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Domain\PersonReference;

/**
 * D-84 "The platform owner appoints Geography stewards"; D-84a: the platform owner appoints a matter reviewer for one
 * matter (and never decides that matter). WHO the platform owner is has not been established (G-3 acceptance item 3):
 * the production binding refuses everyone until a Principal decision establishes it.
 */
interface PlatformOwnerAuthority
{
    public function isPlatformOwner(PersonReference $person): bool;
}
