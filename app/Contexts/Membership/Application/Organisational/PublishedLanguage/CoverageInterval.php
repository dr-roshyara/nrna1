<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\PublishedLanguage;

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
     *   rule as Infrastructure's DateOrdinal, checked by a unit test). Pure PHP, so Application needs no Infrastructure.
     */
    public static function listFrom(\App\Contexts\Membership\Domain\Organisational\IntervalSet $set): array
    {
        $date = static fn (\App\Contexts\Membership\Domain\Organisational\TimePoint $p): string => (new \DateTimeImmutable('1970-01-01', new \DateTimeZone('UTC')))->modify(sprintf('%+d days', $p->ordinal()))->format('Y-m-d');

        return array_map(static fn (\App\Contexts\Membership\Domain\Organisational\Interval $i): self => new self($date($i->start()), $i->until()->isInfinite() ? null : $date($i->until())), $set->intervals());
    }
}
