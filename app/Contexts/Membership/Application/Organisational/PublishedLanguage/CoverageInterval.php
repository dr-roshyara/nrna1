<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\PublishedLanguage;

/**
 * Published Language (C1 / C2; C1/C2 spec K-6): one interval of calendar dates [from, to), as 'YYYY-MM-DD';
 * to = null means open-ended. Consumers compare their business dates against it without interpreting Membership time.
 */
final readonly class CoverageInterval
{
    public function __construct(
        public string $from,
        public ?string $to,
    ) {
    }

    /**
     * @return list<self> the set as calendar dates (EP-Q6 = T1: TimePoint = days since 1970-01-01, UTC; the same
     *   rule as Infrastructure's DateOrdinal, checked over its whole range by a unit test). Pure integer arithmetic:
     *   no DateTime (Application has no clock, MembershipSlice1aBoundaryTest) and no Infrastructure.
     */
    public static function listFrom(\App\Contexts\Membership\Domain\Organisational\IntervalSet $set): array
    {
        return array_map(
            static fn (\App\Contexts\Membership\Domain\Organisational\Interval $i): self => new self(
                self::civilDate($i->start()->ordinal()),
                $i->until()->isInfinite() ? null : self::civilDate($i->until()->ordinal()),
            ),
            $set->intervals(),
        );
    }

    /** Days since 1970-01-01 → 'YYYY-MM-DD', proleptic Gregorian (the civil-from-days algorithm, eras of 400 years). */
    private static function civilDate(int $days): string
    {
        $z = $days + 719_468;
        $era = intdiv($z >= 0 ? $z : $z - 146_096, 146_097);
        $dayOfEra = $z - $era * 146_097;
        $yearOfEra = intdiv($dayOfEra - intdiv($dayOfEra, 1_460) + intdiv($dayOfEra, 36_524) - intdiv($dayOfEra, 146_096), 365);
        $dayOfYear = $dayOfEra - (365 * $yearOfEra + intdiv($yearOfEra, 4) - intdiv($yearOfEra, 100));
        $mp = intdiv(5 * $dayOfYear + 2, 153);
        $day = $dayOfYear - intdiv(153 * $mp + 2, 5) + 1;
        $month = $mp < 10 ? $mp + 3 : $mp - 9;
        $year = $yearOfEra + $era * 400 + ($month <= 2 ? 1 : 0);

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }
}
