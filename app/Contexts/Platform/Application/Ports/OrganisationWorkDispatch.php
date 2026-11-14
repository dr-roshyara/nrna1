<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * PBDIGIT-345 task 8 (E-3): hand ONE organisation its share of a scheduled operation (the adapter queues one E-2
 * organisation job). It never reads the organisation's database. Any failure is thrown and recorded by the fan-out.
 */
interface OrganisationWorkDispatch
{
    public function dispatchFor(TenantId $organisation): void;
}
