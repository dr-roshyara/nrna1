<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\LedgerBoundaryPort;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Platform\Infrastructure\Published\RoutedOrganisationConnection;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * M-3 (PBDIGIT-345 task 10; routing design §3.5; D-233): the existing LedgerClockCloserBoundary, on the ROUTED
 * organisation's own connection, obtained per call from Platform's published RoutedOrganisationConnection (another
 * organisation, or none → NotTheRoutedOrganisation; nothing is opened).
 */
final class RoutedLedgerBoundary implements LedgerBoundaryPort
{
    public function __construct(
        private readonly RoutedOrganisationConnection $connections,
        private readonly LedgerClockReader $clock,
    ) {
    }

    public function closeOrRead(TenantId $organisation): ?Instant
    {
        return (new LedgerClockCloserBoundary(new LedgerClockCloser($this->connections->connectionFor($organisation), $this->clock)))->closeOrRead($organisation);
    }
}
