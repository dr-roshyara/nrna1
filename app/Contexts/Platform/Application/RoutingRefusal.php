<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

/** Why an organisation id did not resolve to a connection (T-2; ISO-2, D-193 Part 4, D-178 §13.6). */
enum RoutingRefusal: string
{
    case Unknown = 'unknown';
    case NotOperational = 'not-operational';
    case SchemaNotCurrent = 'schema-not-current';
}
