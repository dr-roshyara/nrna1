<?php

declare(strict_types=1);

namespace Tests\Support\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\Ports\StewardRosterStore;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;

/**
 * TEST ONLY (G-4c): the roster store port in memory. A write that throws leaves the stored roster unchanged, as the
 * PostgreSQL store's rollback does.
 */
final class InMemoryStewardRosterStore implements StewardRosterStore
{
    private StewardRoster $roster;

    public function __construct()
    {
        $this->roster = StewardRoster::empty();
    }

    public function current(): StewardRoster
    {
        return StewardRoster::fromEntries($this->roster->entries());
    }

    /** For InMemoryPublicationLedger (G-6c-3): the roster as recorded under the ledger's lock becomes the committed one. */
    public function commit(StewardRoster $roster): void
    {
        $this->roster = StewardRoster::fromEntries($roster->entries());
    }

    public function writeUnderLock(\Closure $write): mixed
    {
        $working = StewardRoster::fromEntries($this->roster->entries());
        $result = $write($working);
        $this->roster = $working;

        return $result;
    }
}
