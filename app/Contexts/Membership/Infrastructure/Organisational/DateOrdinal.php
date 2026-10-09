<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\TimePoint;

/**
 * EP-Q6 = T1: a valid-time TimePoint is a calendar date. Ordinal = days since
 * 1970-01-01 (proleptic Gregorian) ⇄ 'YYYY-MM-DD'. ∞ is never a date.
 */
final class DateOrdinal
{
    public static function toDate(TimePoint $point): string
    {
        if ($point->isInfinite()) {
            throw new \InvalidArgumentException('∞ is not a calendar date; use the explicit initial-term state.');
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
