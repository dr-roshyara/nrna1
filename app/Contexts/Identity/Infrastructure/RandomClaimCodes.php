<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure;

use App\Contexts\Identity\Application\Ports\ClaimCodes;
use App\Contexts\Identity\Domain\ClaimCode;

/** PBDIGIT-349 I-2: claim codes from the operating system's CSPRNG (random_bytes). */
final class RandomClaimCodes implements ClaimCodes
{
    public function generate(): ClaimCode
    {
        return ClaimCode::fromRandomBytes(random_bytes(20));
    }
}
