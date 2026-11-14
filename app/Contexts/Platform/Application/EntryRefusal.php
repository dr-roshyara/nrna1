<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

/** Task 6 (E-1): why an organisation was not entered. Logged as this value only (ISO-10: content-free). */
enum EntryRefusal: string
{
    case NotSignedIn = 'not-signed-in';
    case NoSelection = 'no-selection';
    case MalformedId = 'malformed-id';
    case ConflictingId = 'conflicting-id';
    case LegacyOrganisation = 'legacy-organisation';
    case NotBound = 'not-bound';
    case Unknown = 'unknown';
    case NotOperational = 'not-operational';
    case SchemaNotCurrent = 'schema-not-current';

    public static function fromRouting(RoutingRefusal $reason): self
    {
        return match ($reason) {
            RoutingRefusal::Unknown => self::Unknown,
            RoutingRefusal::NotOperational => self::NotOperational,
            RoutingRefusal::SchemaNotCurrent => self::SchemaNotCurrent,
        };
    }
}
