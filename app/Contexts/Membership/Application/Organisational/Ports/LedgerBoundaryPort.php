<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/** The ledger's completeness point (D-209 closing step): close if possible, return the COMMITTED boundary (null = never). */
interface LedgerBoundaryPort
{
    public function closeOrRead(TenantId $organisation): ?Instant;
}
