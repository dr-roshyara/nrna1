<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Domain\Objection;

/** An objection as loaded, with the row version a save must match. */
final readonly class StoredObjection
{
    public function __construct(public Objection $objection, public int $rowVersion)
    {
    }
}
