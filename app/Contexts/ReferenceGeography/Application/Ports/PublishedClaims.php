<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Domain\ClaimId;

/** Whether a claim is published (G-6b): an objection challenges a PUBLISHED fact only (strategic baseline S1.1). */
interface PublishedClaims
{
    public function isPublished(ClaimId $claim): bool;
}
