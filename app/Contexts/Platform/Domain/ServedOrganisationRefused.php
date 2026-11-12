<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Domain;

/** A domain refusal (Rule 8): the lifecycle step is not allowed in the organisation's current state. */
final class ServedOrganisationRefused extends \DomainException
{
    public function __construct(public readonly LifecycleRefusal $refusal)
    {
        parent::__construct("Served organisation: {$refusal->value}");
    }
}
