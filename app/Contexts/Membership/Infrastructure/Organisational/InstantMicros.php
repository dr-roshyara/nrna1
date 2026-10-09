<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\Instant;

/**
 * decidedAt / recordedAt are exact instants: ordinal = microseconds since the Unix
 * epoch, UTC ⇄ PostgreSQL timestamptz(6). Written as an exact text literal (no float);
 * read back via `(extract(epoch from col) * 1000000)::bigint` (exact numeric).
 */
final class InstantMicros
{
    public static function toTimestamp(Instant $instant): string
    {
        $micros = $instant->ordinal();
        $seconds = intdiv($micros, 1_000_000);
        $fraction = $micros % 1_000_000;
        if ($fraction < 0) {
            $seconds--;
            $fraction += 1_000_000;
        }

        return (new \DateTimeImmutable('@' . $seconds))->format('Y-m-d H:i:s') . sprintf('.%06d+00', $fraction);
    }

    /** The SQL expression that reads a timestamptz column as epoch microseconds. */
    public static function selectExpression(string $column): string
    {
        return "(extract(epoch from $column) * 1000000)::bigint";
    }
}
