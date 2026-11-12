<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\LedgerBoundaryPort;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/** Adapter: C2's boundary port is the ledger's closing step (guide 20). */
final class LedgerClockCloserBoundary implements LedgerBoundaryPort
{
    public function __construct(private readonly LedgerClockCloser $closer)
    {
    }

    public function closeOrRead(TenantId $organisation): ?Instant
    {
        return $this->closer->closeOrRead($organisation);
    }
}
