<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

/** Task 6: the organisation was not entered. The message carries the reason only, never an id or a locator. */
final class OrganisationEntryRefused extends \DomainException
{
    public function __construct(public readonly EntryRefusal $reason)
    {
        parent::__construct("Organisation entry refused: {$reason->value}.");
    }
}
