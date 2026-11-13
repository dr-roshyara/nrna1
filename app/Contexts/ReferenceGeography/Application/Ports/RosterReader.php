<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Domain\StewardRoster;

/**
 * Read-only access to the steward roster (G-6a): for use cases that only CHECK who may act (candidates, objections).
 * The roster store's write path stays with the roster and revision use cases (no-bypass rule 3).
 */
interface RosterReader
{
    /** The roster as last committed. */
    public function current(): StewardRoster;
}
