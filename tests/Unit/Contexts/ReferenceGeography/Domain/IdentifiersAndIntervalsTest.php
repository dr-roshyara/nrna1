<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-1, cycle 1 (v3.11 §2 identity, §3 valid interval):
 * - ReferenceUnitId: PublicDigit's opaque identity, minted once (I-3 is enforced by IdentityLedger, cycle 4);
 * - a valid interval is [from, to): from inclusive, to exclusive; no `to` = until further notice; empty or
 *   reversed intervals are refused.
 * Database-free: vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/ReferenceGeography
 */
final class IdentifiersAndIntervalsTest extends TestCase
{
    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    public function test_identities_are_opaque_lower_case_uuids_and_compare_by_value(): void
    {
        $id = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';

        $this->assertTrue(ReferenceUnitId::fromString($id)->equals(ReferenceUnitId::fromString($id)));
        $this->assertTrue(ClaimId::fromString($id)->equals(ClaimId::fromString($id)));
        foreach (['', 'NP', 'np-p3', strtoupper($id), '3f2b8c1d6a4e4b7f9c2d1e5a7b9c0d3f'] as $bad) {
            try {
                ReferenceUnitId::fromString($bad);
                $this->fail("'{$bad}' must be refused: a code is never an identity");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_a_valid_date_is_a_real_calendar_date(): void
    {
        $this->assertSame('2015-09-20', ValidDate::fromString('2015-09-20')->value());
        $this->assertTrue(ValidDate::fromString('2016-02-29')->isBefore(ValidDate::fromString('2016-03-01')));

        foreach (['2015-02-29', '2015-13-01', '20150920', '2015-9-20', ''] as $bad) {
            try {
                ValidDate::fromString($bad);
                $this->fail("'{$bad}' must be refused");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_from_is_inclusive_and_to_is_exclusive(): void
    {
        $i = self::iv('2015-09-20', '2017-03-10');

        $this->assertTrue($i->covers(ValidDate::fromString('2015-09-20')), 'from is inclusive');
        $this->assertTrue($i->covers(ValidDate::fromString('2017-03-09')));
        $this->assertFalse($i->covers(ValidDate::fromString('2017-03-10')), 'to is exclusive');
        $this->assertFalse($i->covers(ValidDate::fromString('2015-09-19')));
    }

    public function test_an_open_interval_lasts_until_further_notice(): void
    {
        $open = self::iv('2015-09-20');

        $this->assertTrue($open->isOpen());
        $this->assertTrue($open->covers(ValidDate::fromString('9999-12-31')));
    }

    public function test_an_empty_or_reversed_interval_is_refused(): void
    {
        foreach ([['2017-03-10', '2017-03-10'], ['2017-03-10', '2015-09-20']] as [$from, $to]) {
            try {
                self::iv($from, $to);
                $this->fail("[{$from}, {$to}) must be refused");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_adjacent_intervals_do_not_overlap_but_shared_days_do(): void
    {
        $a = self::iv('2015-01-01', '2016-01-01');

        $this->assertFalse($a->overlaps(self::iv('2016-01-01', '2017-01-01')), 'adjacent: [a, b) and [b, c)');
        $this->assertTrue($a->overlaps(self::iv('2015-12-31', '2017-01-01')));
        $this->assertTrue($a->overlaps(self::iv('2014-01-01')), 'open interval reaching in');
        $this->assertTrue(self::iv('2014-01-01')->overlaps(self::iv('2020-01-01')), 'two open intervals');
        $this->assertFalse(self::iv('2016-01-01')->overlaps($a));
    }
}
