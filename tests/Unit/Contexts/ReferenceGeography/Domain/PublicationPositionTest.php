<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-4d, kernel defect found by persistence (v3.11 §6: a publication POSITION belongs to one revision;
 * R-1: a revision may hold several claims for one slot if their intervals do not overlap). So one position may hold
 * several claims of a slot, provided they do not overlap; overlapping claims at one position stay refused.
 * G-1 assumed one claim per position; the in-memory test atlas hid it by giving every claim its own position.
 */
final class PublicationPositionTest extends TestCase
{
    private static int $n = 9000;

    private static function claim(string $value, string $from, ?string $to = null): Claim
    {
        self::$n++;

        return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)), SlotKey::classification(ReferenceUnitId::fromString('3c3c3c3c-3c3c-4c3c-8c3c-3c3c3c3c3c3c'), 'admin'), ClaimValue::of($value),
            new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to)), new Basis(BasisKind::Instrument, 'act'));
    }

    public function test_one_publication_may_hold_several_non_overlapping_claims_of_a_slot(): void
    {
        $first = self::claim('district', '2016-01-01', '2020-01-01');
        $h = SlotHistory::for($first->slot)->with($first, 5)->with(self::claim('municipality', '2020-01-01'), 5);

        $this->assertSame('district', $h->resolve(ValidDate::fromString('2017-01-01'))?->value());
        $this->assertSame('municipality', $h->resolve(ValidDate::fromString('2021-01-01'))?->value());
        $this->assertSame(['2020-01-01'], array_map(static fn (ValidDate $d): string => $d->value(), $h->boundariesWithin(new ValidInterval(ValidDate::fromString('2016-01-01')))));
    }

    public function test_overlapping_claims_at_one_position_stay_refused(): void
    {
        $first = self::claim('district', '2016-01-01', '2020-01-01');

        $this->expectException(\InvalidArgumentException::class);
        SlotHistory::for($first->slot)->with($first, 5)->with(self::claim('municipality', '2019-01-01'), 5);
    }

    public function test_a_later_position_still_supersedes_both(): void
    {
        $first = self::claim('district', '2016-01-01', '2020-01-01');
        $h = SlotHistory::for($first->slot)->with($first, 5)->with(self::claim('municipality', '2020-01-01'), 5)->with(self::claim('zone', '2016-01-01'), 6);

        $this->assertSame('zone', $h->resolve(ValidDate::fromString('2017-01-01'))?->value());
        $this->assertSame('district', $h->resolve(ValidDate::fromString('2017-01-01'), upToPosition: 5)?->value());
    }
}
