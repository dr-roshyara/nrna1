<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\WorkingOrganisationPort;
use App\Contexts\Platform\Application\Published\RoutedOrganisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * M-1 (PBDIGIT-345 task 10; routing design §3.5; D-233): the working organisation IS the organisation this operation
 * was routed to (Platform's published RoutedOrganisation), or null outside a routed operation. Never caller input.
 */
final class RoutedWorkingOrganisation implements WorkingOrganisationPort
{
    public function __construct(private readonly RoutedOrganisation $routed)
    {
    }

    public function current(): ?TenantId
    {
        return $this->routed->current();
    }
}
