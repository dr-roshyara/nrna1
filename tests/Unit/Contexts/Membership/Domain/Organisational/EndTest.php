<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * Slice 0 contract §18 (End), §13 (INV-2, INV-3). PBDIGIT-258.
 */
final class EndTest extends TestCase
{
    private static function t(int $o): TimePoint
    {
        return TimePoint::of($o);
    }

    private static function admitted(int $at = 1, ?int $termUntil = 10): MemberHistory
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', $at, $termUntil));

        return $h;
    }

    public function test_an_open_period_can_be_ended(): void
    {
        $this->assertTrue(self::admitted()->end('E1', 'A1', 5)->isAccepted());
    }

    public function test_ending_an_unknown_period_is_refused(): void
    {
        $this->assertSame(RefusalReason::PeriodNotFound, self::admitted()->end('E1', 'NOPE', 5)->refusal());
    }

    public function test_ending_an_already_ended_period_is_refused(): void
    {
        $h = self::admitted();
        $h->commit($h->end('E1', 'A1', 5));

        $this->assertSame(RefusalReason::PeriodAlreadyEnded, $h->end('E2', 'A1', 4)->refusal(), 'a changed end date is a Correct (Slice 7), not a second End');
    }

    public function test_an_end_before_the_period_start_is_refused(): void
    {
        // C-S0-1 (Principal Reviewer decision)
        $this->assertSame(RefusalReason::EndNotAfterPeriodStart, self::admitted(3)->end('E1', 'A1', 2)->refusal());
    }

    public function test_an_end_exactly_at_the_period_start_is_refused(): void
    {
        // C-S0-1: an admission that never took effect is an Annul / Void (Slice 7), not an End.
        $this->assertSame(RefusalReason::EndNotAfterPeriodStart, self::admitted(3)->end('E1', 'A1', 3)->refusal());
    }

    public function test_an_end_shrinks_the_base_claim(): void
    {
        $h = self::admitted(1, null);
        $h->commit($h->end('E1', 'A1', 5));

        $this->assertTrue(Interval::of(self::t(1), self::t(5))->equals($h->member->resolve()->period(PeriodId::fromString('A1'))->baseClaim()));
    }

    public function test_before_the_end_the_member_is_in_force_if_the_term_covers_it(): void
    {
        $h = self::admitted(1, 10);
        $h->commit($h->end('E1', 'A1', 5));
        $p = $h->member->resolve()->period(PeriodId::fromString('A1'));

        $this->assertTrue($p->isInForce(self::t(4)));
        $this->assertFalse($p->isEnded(self::t(4)));
    }

    public function test_from_the_end_on_the_period_is_ended_and_out_of_lifetime_and_not_in_force(): void
    {
        $h = self::admitted(1, 10);
        $h->commit($h->end('E1', 'A1', 5));
        $r = $h->member->resolve();
        $p = $r->period(PeriodId::fromString('A1'));

        foreach ([5, 6, 9, 50] as $t) {
            $this->assertTrue($p->isEnded(self::t($t)));
            $this->assertFalse($p->inLifetime(self::t($t)));
            $this->assertFalse($p->isInForce(self::t($t)), 'INV-2: Ended ⇒ ¬InForce, even though the term ran to 10');
            $this->assertFalse($r->isMember(self::t($t)));
        }
    }

    public function test_lapse_is_not_termination(): void
    {
        // INV-3: a lapsed member is in the lifetime and never ended.
        $h = self::admitted(1, 4);
        $p = $h->member->resolve()->period(PeriodId::fromString('A1'));

        $this->assertTrue($p->isLapsed(self::t(6)));
        $this->assertFalse($p->isEnded(self::t(6)));

        $h->commit($h->end('E1', 'A1', 8));
        $p = $h->member->resolve()->period(PeriodId::fromString('A1'));
        $this->assertTrue($p->isLapsed(self::t(6)));
        $this->assertTrue($p->isEnded(self::t(8)));
        $this->assertFalse($p->isLapsed(self::t(8)), 'once ended, no longer lapsed');
    }
}
