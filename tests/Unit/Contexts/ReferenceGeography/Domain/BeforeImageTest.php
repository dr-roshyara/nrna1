<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\PriorValue;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-2b, cycle 1 (v3.11 §5.1 "Before-image, piecewise"):
 * - a claim over interval I carries a partition of I into segments, each with the effective value it replaced there,
 *   computed against p₀;
 * - a segment boundary falls wherever the prior effective value changes;
 * - three kinds of prior value: a VALUE, NONE (a prior withdrawal in force), ABSENT (no claim ever covered that time).
 */
final class BeforeImageTest extends TestCase
{
    private const C = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const A = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';
    private const B = '6c5d4e3f-2a1b-4c9d-8e7f-6a5b4c3d2e1f';

    private static int $n = 0;

    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    private static function slot(): SlotKey
    {
        return SlotKey::containment(ReferenceUnitId::fromString(self::C), 'admin');
    }

    private static function claim(?string $value, string $from, ?string $to = null): Claim
    {
        self::$n++;

        return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)), self::slot(), $value === null ? ClaimValue::none() : ClaimValue::of($value), self::iv($from, $to), new Basis(BasisKind::Instrument, 'act'));
    }

    /** @return list<array{string, ?string, string}> [from, to, prior] with prior = value | NONE | ABSENT */
    private static function shape(BeforeImage $image): array
    {
        return array_map(static fn (array $s): array => [$s[0]->from->value(), $s[0]->to?->value(), $s[1]->describe()], $image->segments());
    }

    public function test_the_v3_11_example_a_correction_over_two_prior_parents(): void
    {
        $history = SlotHistory::for(self::slot())
            ->with(self::claim(self::A, '2016-01-01', '2016-07-01'), 1)
            ->with(self::claim(self::B, '2016-07-01'), 2);

        $image = BeforeImage::of($history, self::iv('2016-03-01', '2016-09-01'), atPosition: 2);

        $this->assertSame([['2016-03-01', '2016-07-01', self::A], ['2016-07-01', '2016-09-01', self::B]], self::shape($image));
    }

    public function test_absent_where_no_claim_ever_covered_none_where_a_withdrawal_was_in_force(): void
    {
        $history = SlotHistory::for(self::slot())
            ->with(self::claim(self::A, '2016-01-01'), 1)
            ->with(self::claim(null, '2016-05-01', '2016-06-01'), 2);   // withdrawn in May

        $image = BeforeImage::of($history, self::iv('2015-11-01', '2016-08-01'), atPosition: 2);

        $this->assertSame([
            ['2015-11-01', '2016-01-01', 'ABSENT'],
            ['2016-01-01', '2016-05-01', self::A],
            ['2016-05-01', '2016-06-01', 'NONE'],
            ['2016-06-01', '2016-08-01', self::A],
        ], self::shape($image));
    }

    public function test_it_is_computed_against_p0_never_against_later_claims(): void
    {
        $history = SlotHistory::for(self::slot())
            ->with(self::claim(self::A, '2016-01-01'), 1)
            ->with(self::claim(self::B, '2016-01-01'), 5);

        $this->assertSame([['2016-02-01', '2016-03-01', self::A]], self::shape(BeforeImage::of($history, self::iv('2016-02-01', '2016-03-01'), atPosition: 4)));
    }

    public function test_an_open_interval_and_adjacent_equal_values_merge_into_one_segment(): void
    {
        $history = SlotHistory::for(self::slot())
            ->with(self::claim(self::A, '2016-01-01', '2016-07-01'), 1)
            ->with(self::claim(self::A, '2016-07-01'), 2);   // same value continues: no boundary

        $this->assertSame([['2016-03-01', null, self::A]], self::shape(BeforeImage::of($history, self::iv('2016-03-01'), atPosition: 2)));
    }

    public function test_an_empty_history_gives_one_absent_segment(): void
    {
        $this->assertSame([['2016-03-01', null, 'ABSENT']], self::shape(BeforeImage::of(SlotHistory::for(self::slot()), self::iv('2016-03-01'), atPosition: 0)));
    }

    public function test_the_segments_partition_the_interval_exactly(): void
    {
        $history = SlotHistory::for(self::slot())
            ->with(self::claim(self::A, '2014-01-01', '2016-02-01'), 1)
            ->with(self::claim(self::B, '2016-04-01', '2016-05-01'), 2);
        $image = BeforeImage::of($history, self::iv('2015-01-01', '2017-01-01'), atPosition: 2);

        $segments = $image->segments();
        $this->assertSame('2015-01-01', $segments[0][0]->from->value());
        $this->assertSame('2017-01-01', $segments[count($segments) - 1][0]->to?->value());
        for ($i = 1; $i < count($segments); $i++) {
            $this->assertSame($segments[$i - 1][0]->to?->value(), $segments[$i][0]->from->value(), 'no gap, no overlap');
        }
        $this->assertTrue($image->hadValue(self::A, self::iv('2015-06-01', '2015-07-01')));
        $this->assertFalse($image->hadValue(self::A, self::iv('2016-02-01', '2016-04-01')), 'absent there');
        $this->assertTrue(PriorValue::absent()->isAbsent());
    }

    public function test_intervals_intersect_or_not(): void
    {
        $this->assertSame(['2016-03-01', '2016-07-01'], [self::iv('2016-01-01', '2016-07-01')->intersect(self::iv('2016-03-01'))?->from->value(), self::iv('2016-01-01', '2016-07-01')->intersect(self::iv('2016-03-01'))?->to?->value()]);
        $this->assertNull(self::iv('2016-01-01', '2016-07-01')->intersect(self::iv('2016-07-01')), 'adjacent: no intersection');
        $this->assertNull(self::iv('2016-03-01')->intersect(self::iv('2010-01-01'))?->to, 'two open intervals: open');
    }
}
