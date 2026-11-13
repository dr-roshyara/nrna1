<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** A domain refusal (Rule 8): the roster write or query is not allowed. A refused write takes no version. */
final class RosterRefused extends \DomainException
{
    public function __construct(public readonly RosterRefusal $reason)
    {
        parent::__construct("Roster: {$reason->value}");
    }
}
