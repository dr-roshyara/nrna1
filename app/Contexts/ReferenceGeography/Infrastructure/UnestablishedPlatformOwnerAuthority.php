<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\ReferenceGeography\Application\Ports\PlatformOwnerAuthority;
use App\Contexts\ReferenceGeography\Domain\PersonReference;

/**
 * The production binding of PlatformOwnerAuthority while WHO the platform owner is remains unestablished (G-3
 * acceptance item 3: "No production roster-writing authority may be enabled until that authority is explicitly
 * established"). Refuses everyone. Replace it only after a Principal decision; it does not assume the Product Owner,
 * an organisation owner, an organisation administrator or a tenant administrator.
 */
final class UnestablishedPlatformOwnerAuthority implements PlatformOwnerAuthority
{
    public function isPlatformOwner(PersonReference $person): bool
    {
        return false;
    }
}
