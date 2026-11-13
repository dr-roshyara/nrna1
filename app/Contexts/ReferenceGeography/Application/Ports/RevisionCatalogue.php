<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Domain\RevisionId;

/**
 * Whether a revision exists (G-6a), for use cases that only REFER to one (an accepted candidate names its revision).
 * Loading and saving revisions stays with the revision use cases (no-bypass rule 2).
 */
interface RevisionCatalogue
{
    public function exists(RevisionId $id): bool;
}
