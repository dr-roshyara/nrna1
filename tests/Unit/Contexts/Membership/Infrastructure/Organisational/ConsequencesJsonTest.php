<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\IntervalSet;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Domain\Organisational\TrimConsequence;
use App\Contexts\Membership\Infrastructure\Organisational\ConsequencesJson;
use App\Contexts\Membership\Infrastructure\Organisational\DateOrdinal;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * COR-4 step 8 (PBDIGIT-329, Q-5): the jsonb format of a Correct's frozen consequences, database-free.
 * A characterisation test of the committed codec (step 5), added so it is mutation-testable without PostgreSQL.
 */
final class ConsequencesJsonTest extends TestCase
{
    private static function trim(string $period, array ...$intervals): TrimConsequence
    {
        return new TrimConsequence(PeriodId::fromString($period), IntervalSet::of(...array_map(
            static fn (array $i): Interval => Interval::of(TimePoint::of($i[0]), $i[1] === null ? TimePoint::infinity() : TimePoint::of($i[1])),
            $intervals,
        )));
    }

    public function test_the_format_is_period_and_removed_date_pairs_with_null_for_infinity(): void
    {
        $json = ConsequencesJson::encode([self::trim('A1', [0, 31], [59, null])]);

        $this->assertSame('[{"period":"A1","removed":[["1970-01-01","1970-02-01"],["1970-03-01",null]]}]', $json);
        $this->assertSame('[]', ConsequencesJson::encode([]));
    }

    public function test_consequences_round_trip_including_two_periods_and_open_ends(): void
    {
        $consequences = [self::trim('A0', [1, 2]), self::trim('A2', [2, 6], [9, null])];

        $this->assertEquals($consequences, ConsequencesJson::decode(ConsequencesJson::encode($consequences), ConsequencesJson::G2_VERSION));
    }

    public function test_decoding_ignores_jsonb_key_order_and_whitespace(): void
    {
        $decoded = ConsequencesJson::decode('[{"removed": [["1970-01-02", "1970-01-03"]], "period": "A1"}]', 1);

        $this->assertEquals([self::trim('A1', [1, 2])], $decoded);
    }

    /** @return array<string, array{string, int, class-string<\Throwable>}> */
    public static function malformed(): array
    {
        return [
            'unknown g2_version' => ['[]', 2, \InvalidArgumentException::class],
            'not JSON' => ['[', 1, \JsonException::class],
            'an object, not a list' => ['{"period":"A1","removed":[]}', 1, \InvalidArgumentException::class],
            'an item that is not an object' => ['["A1"]', 1, \InvalidArgumentException::class],
            'a missing key' => ['[{"period":"A1"}]', 1, \InvalidArgumentException::class],
            'an extra key' => ['[{"period":"A1","removed":[["1970-01-01",null]],"x":1}]', 1, \InvalidArgumentException::class],
            'a period that is not a string' => ['[{"period":5,"removed":[["1970-01-01",null]]}]', 1, \InvalidArgumentException::class],
            'removed is not a list' => ['[{"period":"A1","removed":{"a":["1970-01-01",null]}}]', 1, \InvalidArgumentException::class],
            'a pair of three' => ['[{"period":"A1","removed":[["1970-01-01","1970-01-02","1970-01-03"]]}]', 1, \InvalidArgumentException::class],
            'a start that is null' => ['[{"period":"A1","removed":[[null,"1970-01-02"]]}]', 1, \InvalidArgumentException::class],
            'an end that is a number' => ['[{"period":"A1","removed":[["1970-01-01",5]]}]', 1, \InvalidArgumentException::class],
            'a malformed date' => ['[{"period":"A1","removed":[["1970-13-45",null]]}]', 1, \UnexpectedValueException::class],
            'a trim that removes nothing' => ['[{"period":"A1","removed":[]}]', 1, \InvalidArgumentException::class],
            // Mutation findings (COR-4 step 8): shapes whose parts are individually valid must still be refused.
            'a keyed object of valid trims, not a list' => ['{"x":{"period":"A1","removed":[["1970-01-02",null]]}}', 1, \InvalidArgumentException::class],
            'a scalar, not a list' => ['5', 1, \InvalidArgumentException::class],
            'a pair given as an object with keys out of order' => ['[{"period":"A1","removed":[{"1":"1970-01-03","0":"1970-01-02"}]}]', 1, \InvalidArgumentException::class],
        ];
    }

    /** @param class-string<\Throwable> $expected */
    #[DataProvider('malformed')]
    public function test_malformed_stored_consequences_are_refused(string $json, int $version, string $expected): void
    {
        $this->expectException($expected);

        ConsequencesJson::decode($json, $version);
    }

    public function test_the_dates_go_through_date_ordinal(): void
    {
        $this->assertSame(DateOrdinal::toDate(TimePoint::of(20_000)), json_decode(ConsequencesJson::encode([self::trim('A1', [20_000, null])]), true)[0]['removed'][0][0]);
    }
}
