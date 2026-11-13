<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Domain\StewardRoster;

/**
 * Where the roster is kept (v3.11 §8). Every write runs under the ONE Geography publication lock, on the roster as
 * last committed; the new entries are appended at the next roster versions, or nothing is written.
 */
interface StewardRosterStore extends RosterReader
{
    /** The roster as last committed. */
    public function current(): StewardRoster;

    /**
     * Runs $write on the current roster under the publication lock and appends the entries it made, atomically.
     * If $write throws, nothing is written and the exception propagates.
     *
     * @template T
     * @param \Closure(StewardRoster): T $write
     * @return T
     */
    public function writeUnderLock(\Closure $write): mixed;
}
