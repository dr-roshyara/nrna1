<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\StorableValidTime;
use App\Contexts\Membership\Domain\Organisational\Correct;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\IntervalSet;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Domain\Organisational\TrimConsequence;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * COR-4 step 2 (PBDIGIT-329, Q-7; V-13): R-1a-6 covers a Correct's value and every finite bound of its consequences.
 * Through decide* a trim bound is always a stored date or ∞, so an unstorable bound cannot arise without an unstorable
 * value; the bound check is defence in depth for any other way a Correct is built.
 */
final class StorableValidTimeTest extends TestCase
{
    private static function correct(int $value, ?array $removed = null): Correct
    {
        $consequences = $removed === null ? [] : [new TrimConsequence(
            PeriodId::fromString('A1'),
            IntervalSet::of(Interval::of(TimePoint::of($removed[0]), $removed[1] === null ? TimePoint::infinity() : TimePoint::of($removed[1]))),
        )];

        return new Correct(FactId::fromString('C1'), FactId::fromString('A0'), TimePoint::of($value), Provenance::decision('registrar'), null, $consequences);
    }

    /** @return array<string, array{Correct, bool}> */
    public static function corrections(): array
    {
        return [
            'value at the first storable date' => [self::correct(StorableValidTime::FIRST), true],
            'value at the last storable date' => [self::correct(StorableValidTime::LAST), true],
            'value before the range' => [self::correct(StorableValidTime::FIRST - 1), false],
            'value after the range' => [self::correct(StorableValidTime::LAST + 1), false],
            'trim bounds at the range edges' => [self::correct(0, [StorableValidTime::FIRST, StorableValidTime::LAST]), true],
            'trim open to ∞' => [self::correct(0, [5, null]), true],
            'trim starting before the range' => [self::correct(0, [StorableValidTime::FIRST - 1, 5]), false],
            'trim ending after the range' => [self::correct(0, [5, StorableValidTime::LAST + 1]), false],
        ];
    }

    #[DataProvider('corrections')]
    public function test_a_correction_is_storable_iff_its_value_and_every_finite_trim_bound_are(Correct $correct, bool $storable): void
    {
        $this->assertSame($storable, StorableValidTime::admits($correct));
    }
}
