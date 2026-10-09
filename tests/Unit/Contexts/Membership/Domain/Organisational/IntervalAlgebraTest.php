<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\IntervalSet;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;

/**
 * Slice 0 contract §10: the interval algebra over [from, until).
 * PBDIGIT-258.
 */
final class IntervalAlgebraTest extends TestCase
{
    private static function iv(int $from, ?int $until): Interval
    {
        return Interval::of(TimePoint::of($from), $until === null ? TimePoint::infinity() : TimePoint::of($until));
    }

    public function test_infinity_is_after_every_finite_time_point(): void
    {
        $this->assertTrue(TimePoint::of(PHP_INT_MAX - 1)->isBefore(TimePoint::infinity()));
        $this->assertFalse(TimePoint::infinity()->isBefore(TimePoint::infinity()));
        $this->assertTrue(TimePoint::infinity()->isInfinite());
    }

    public function test_a_half_open_interval_contains_its_start_but_not_its_end(): void
    {
        $i = self::iv(1, 5);

        $this->assertTrue($i->contains(TimePoint::of(1)));
        $this->assertTrue($i->contains(TimePoint::of(4)));
        $this->assertFalse($i->contains(TimePoint::of(5)));
        $this->assertFalse($i->contains(TimePoint::of(0)));
    }

    public function test_an_open_ended_interval_contains_every_later_point(): void
    {
        $i = self::iv(3, null);

        $this->assertTrue($i->isOpenEnded());
        $this->assertTrue($i->contains(TimePoint::of(PHP_INT_MAX - 1)));
        $this->assertFalse($i->contains(TimePoint::of(2)));
    }

    public function test_an_interval_whose_end_is_not_after_its_start_is_empty(): void
    {
        $this->assertTrue(self::iv(4, 4)->isEmpty());
        $this->assertTrue(self::iv(4, 2)->isEmpty());
        $this->assertFalse(self::iv(4, 5)->isEmpty());
        $this->assertFalse(self::iv(4, 4)->contains(TimePoint::of(4)));
    }

    public function test_an_interval_cannot_start_at_infinity(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Interval::of(TimePoint::infinity(), TimePoint::infinity());
    }

    public function test_adjacent_intervals_are_disjoint_not_overlapping(): void
    {
        $this->assertTrue(self::iv(1, 5)->isAdjacentTo(self::iv(5, 9)));
        $this->assertFalse(self::iv(1, 5)->overlaps(self::iv(5, 9)));
        $this->assertFalse(self::iv(5, 9)->overlaps(self::iv(1, 5)));
    }

    public function test_overlap_requires_a_shared_point(): void
    {
        $this->assertTrue(self::iv(1, 5)->overlaps(self::iv(4, 9)));
        $this->assertTrue(self::iv(1, null)->overlaps(self::iv(7, null)));
        $this->assertFalse(self::iv(1, 3)->overlaps(self::iv(6, 9)));
        $this->assertFalse(self::iv(3, 3)->overlaps(self::iv(1, 9)), 'an empty interval overlaps nothing');
    }

    public function test_truncation_never_enlarges_an_interval(): void
    {
        $i = self::iv(2, 8);

        $this->assertTrue(self::iv(2, 5)->equals($i->truncatedAt(TimePoint::of(5))));
        $this->assertTrue($i->equals($i->truncatedAt(TimePoint::of(12))), 'truncation after the end changes nothing');
        $this->assertTrue($i->truncatedAt(TimePoint::of(1))->isEmpty(), 'truncation before the start empties it');
        $this->assertTrue(self::iv(2, 5)->equals(self::iv(2, null)->truncatedAt(TimePoint::of(5))));
    }

    public function test_intersection(): void
    {
        $this->assertTrue(self::iv(4, 5)->equals(self::iv(1, 5)->intersectedWith(self::iv(4, 9))));
        $this->assertTrue(self::iv(1, 5)->intersectedWith(self::iv(5, 9))->isEmpty());
    }

    public function test_an_interval_set_merges_overlapping_and_adjacent_intervals_and_drops_empty_ones(): void
    {
        $set = IntervalSet::of(self::iv(5, 9), self::iv(1, 5), self::iv(12, 12), self::iv(20, null), self::iv(8, 10));

        $this->assertCount(2, $set->intervals());
        $this->assertTrue(self::iv(1, 10)->equals($set->intervals()[0]));
        $this->assertTrue(self::iv(20, null)->equals($set->intervals()[1]));
        $this->assertTrue($set->contains(TimePoint::of(9)));
        $this->assertFalse($set->contains(TimePoint::of(12)));
    }

    public function test_interval_sets_with_the_same_coverage_are_equal(): void
    {
        $this->assertTrue(IntervalSet::of(self::iv(1, 5), self::iv(5, 9))->equals(IntervalSet::of(self::iv(1, 9))));
        $this->assertFalse(IntervalSet::of(self::iv(1, 5))->equals(IntervalSet::of(self::iv(1, 6))));
        $this->assertTrue(IntervalSet::empty()->equals(IntervalSet::of(self::iv(3, 3))));
    }

    public function test_an_interval_set_can_be_truncated(): void
    {
        $set = IntervalSet::of(self::iv(1, 3), self::iv(5, 9));

        $this->assertTrue(IntervalSet::of(self::iv(1, 3), self::iv(5, 6))->equals($set->truncatedAt(TimePoint::of(6))));
    }
}
