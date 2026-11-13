<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Application\ActingPersonRefused;
use App\Contexts\ReferenceGeography\Domain\PersonReference;

/** Who is acting now, as Geography's own person reference (D-225 IG-2). */
interface ActingPerson
{
    /** @throws ActingPersonRefused */
    public function current(): PersonReference;
}
