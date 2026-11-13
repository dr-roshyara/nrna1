<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Domain\Revision;

/** A revision as loaded, with the row version a later save must name (optimistic concurrency). */
final readonly class StoredRevision
{
    public function __construct(public Revision $revision, public int $rowVersion)
    {
    }
}
