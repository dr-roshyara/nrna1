<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\StorableValidTime;
use App\Contexts\Membership\Domain\Organisational\TimePoint;

/**
 * EP-Q6 = T1: a valid-time TimePoint is a calendar date. Ordinal = days since
 * 1970-01-01 (proleptic Gregorian) ⇄ 'YYYY-MM-DD'. ∞ is never a date.
 */
final class DateOrdinal
{
    /** 0001-01-01 and 9999-12-31: the dates a four-digit year can represent and fromDate() can read back (PBDIGIT-264 F-1). */
    private const FIRST_ORDINAL = StorableValidTime::FIRST;   // defined once, in the port contract (R-1a-6);
    private const LAST_ORDINAL = StorableValidTime::LAST;     // this guard stays as the final conversion safeguard

    public static function toDate(TimePoint $point): string
    {
        if ($point->isInfinite()) {
            throw new \InvalidArgumentException('∞ is not a calendar date; use the explicit initial-term state.');
        }
        // Checked numerically before any date arithmetic, so an out-of-range fact fails before it is written.
        if ($point->ordinal() < self::FIRST_ORDINAL || $point->ordinal() > self::LAST_ORDINAL) {
            throw new \InvalidArgumentException("Date ordinal {$point->ordinal()} is outside 0001-01-01 … 9999-12-31.");
        }

        return (new \DateTimeImmutable('1970-01-01', new \DateTimeZone('UTC')))
            ->modify(sprintf('%+d days', $point->ordinal()))
            ->format('Y-m-d');
    }

    public static function fromDate(string $date): TimePoint
    {
        $parsed = \DateTimeImmutable::createFromFormat('!Y-m-d', $date, new \DateTimeZone('UTC'));
        if ($parsed === false || $parsed->format('Y-m-d') !== $date) {
            throw new \UnexpectedValueException("Not a calendar date: '$date'.");
        }

        return TimePoint::of(intdiv($parsed->getTimestamp(), 86400));   // UTC midnight: an exact multiple of 86 400
    }
}
