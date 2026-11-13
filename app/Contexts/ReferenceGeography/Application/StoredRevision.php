<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Domain\Revision;

/**
 * A revision as loaded, with the row version a later save must name (optimistic concurrency), and whether any of its
 * versions is published (then it is closed to every act, G-4f).
 */
final readonly class StoredRevision
{
    public function __construct(public Revision $revision, public int $rowVersion, public bool $published = false)
    {
    }
}
