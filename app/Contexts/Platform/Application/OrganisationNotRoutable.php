<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

/** Refused: the organisation is not served right now. Content-free (ISO-10): names the reason, never a locator. */
final class OrganisationNotRoutable extends \RuntimeException
{
    public function __construct(public readonly RoutingRefusal $reason)
    {
        parent::__construct("The organisation is not routable: {$reason->value}.");
    }
}
