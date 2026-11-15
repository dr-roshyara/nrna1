<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application\Ports;

use App\Contexts\Identity\Domain\ClaimCode;

/** A fresh, unpredictable claim code (randomness stays outside the domain). */
interface ClaimCodes
{
    public function generate(): ClaimCode;
}
