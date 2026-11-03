<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\IntervalSet;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Domain\Organisational\TrimConsequence;

/**
 * A Correct's frozen consequences ⇄ jsonb (COR-4, PBDIGIT-329, Q-5; FP §12.4 option C; P-2 as clarified):
 *   [{"period": "<admit fact id>", "removed": [["YYYY-MM-DD", "YYYY-MM-DD" | null], …]}, …]
 * Absolute valid-time dates through DateOrdinal; null = ∞. Stored as decided and never recomputed on load.
 * decode() is strict: any other shape throws \InvalidArgumentException (the adapter turns it into
 * StoredMemberLogInvalid).
 */
final class ConsequencesJson
{
    public const G2_VERSION = 1;

    /** @param list<TrimConsequence> $consequences */
    public static function encode(array $consequences): string
    {
        return json_encode(array_map(static fn (TrimConsequence $c): array => [
            'period' => $c->period()->value(),
            'removed' => array_map(static fn (Interval $i): array => [
                DateOrdinal::toDate($i->start()),
                $i->until()->isInfinite() ? null : DateOrdinal::toDate($i->until()),
            ], $c->removed()->intervals()),
        ], $consequences), JSON_THROW_ON_ERROR);
    }

    /** @return list<TrimConsequence> */
    public static function decode(string $json, int $g2Version): array
    {
        if ($g2Version !== self::G2_VERSION) {
            throw new \InvalidArgumentException("Unknown g2_version {$g2Version}.");
        }
        $items = json_decode($json, true, 8, JSON_THROW_ON_ERROR);
        if (!is_array($items) || !array_is_list($items)) {
            throw new \InvalidArgumentException('Consequences are not a list.');
        }

        return array_map(static function (mixed $item): TrimConsequence {
            // jsonb does not keep key order, so the keys are compared as a set.
            $keys = is_array($item) ? array_keys($item) : [];
            sort($keys);
            if (!is_array($item) || $keys !== ['period', 'removed'] || !is_string($item['period']) || !is_array($item['removed']) || !array_is_list($item['removed'])) {
                throw new \InvalidArgumentException('A consequence is not {"period": string, "removed": list}.');
            }
            $intervals = array_map(static function (mixed $pair): Interval {
                if (!is_array($pair) || !array_is_list($pair) || count($pair) !== 2 || !is_string($pair[0]) || !(is_string($pair[1]) || $pair[1] === null)) {
                    throw new \InvalidArgumentException('A removed interval is not [date, date | null].');
                }

                return Interval::of(DateOrdinal::fromDate($pair[0]), $pair[1] === null ? TimePoint::infinity() : DateOrdinal::fromDate($pair[1]));
            }, $item['removed']);

            return new TrimConsequence(PeriodId::fromString($item['period']), IntervalSet::of(...$intervals));
        }, $items);
    }
}
