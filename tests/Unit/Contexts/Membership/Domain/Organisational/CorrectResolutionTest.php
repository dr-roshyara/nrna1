<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\IntervalSet;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * Correction slice C-2, step 4: an accepted Correct changes the resolved state (value precedence and chains).
 * Contract §10a Correct (1), (2), (5); canonical §3 (value is stage 3, before BaseClaim). PBDIGIT-327.
 *
 * Consequences (trims of other periods) are step 5; these histories involve one period only.
 */
final class CorrectResolutionTest extends TestCase
{
    private static function claim(MemberHistory $h): Interval
    {
        return $h->member->resolve()->period(PeriodId::ofAdmit(FactId::fromString('A1')))->baseClaim();
    }

    private static function assertClaim(MemberHistory $h, int $from, ?int $until, string $message = ''): void
    {
        $expected = Interval::of(TimePoint::of($from), $until === null ? TimePoint::infinity() : TimePoint::of($until));
        self::assertTrue(self::claim($h)->equals($expected), $message . ': got [' . self::claim($h)->start()->ordinal() . ', '
            . (self::claim($h)->until()->isInfinite() ? '∞' : self::claim($h)->until()->ordinal()) . ')');
    }

    // §10a Correct (1): a corrected admission date moves the period's start; the lifetime follows.
    public function test_a_corrected_admission_date_moves_the_start(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 3, null, true));
        $h->commit($h->correct('C1', 'A1', 1));

        self::assertClaim($h, 1, null, 'earlier start');
        $this->assertTrue($h->member->resolve()->isMember(TimePoint::of(1)));
        $this->assertSame(1, $h->member->resolve()->firstAdmission()?->ordinal());

        $h->commit($h->correct('C2', 'A1', 4));
        self::assertClaim($h, 4, null, 'later start');
        $this->assertFalse($h->member->resolve()->isMember(TimePoint::of(3)));
    }

    // §10a Correct (1): a corrected end date moves the period's end.
    public function test_a_corrected_end_date_moves_the_end(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 2, null, true));
        $h->commit($h->end('E1', 'A1', 5));

        $h->commit($h->correct('C1', 'E1', 7));
        self::assertClaim($h, 2, 7, 'later end');
        $this->assertTrue($h->member->resolve()->isMember(TimePoint::of(6)));

        $h->commit($h->correct('C2', 'E1', 3));
        self::assertClaim($h, 2, 3, 'earlier end');
        $this->assertFalse($h->member->resolve()->isMember(TimePoint::of(3)));
    }

    // §10a Correct (2): among live corrections of the same fact, the latest by seq wins; reverting it restores the previous one.
    public function test_the_latest_live_correction_wins_and_reverting_it_restores_the_previous(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 3));
        $h->commit($h->correct('C1', 'A1', 1));
        $h->commit($h->correct('C2', 'A1', 2));
        self::assertClaim($h, 2, null, 'C2 is the latest');

        $h->commit($h->annul('X1', 'C2'));
        self::assertClaim($h, 1, null, 'C2 annulled: C1');

        $h->commit($h->void('V1', 'C1'));
        self::assertClaim($h, 3, null, 'C1 voided as well: the recorded date');

        $h->commit($h->annul('X2', 'X1'));
        self::assertClaim($h, 2, null, 'C2 revived');
    }

    // §10a Correct (2): Correct(Correct) replaces the value of the correction it targets.
    public function test_a_correction_of_a_correction_applies_along_the_chain(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 2));
        $h->commit($h->end('E1', 'A1', 5));
        $h->commit($h->correct('C1', 'E1', 8));
        $h->commit($h->correct('C2', 'C1', 6));
        self::assertClaim($h, 2, 6, 'C2 corrects C1');

        $h->commit($h->void('V1', 'C2'));
        self::assertClaim($h, 2, 8, 'C2 voided: C1');

        $h->commit($h->annul('X1', 'C1'));
        self::assertClaim($h, 2, 5, 'C1 annulled: the recorded End');
    }

    // §10a Correct (5): a correction of an annulled target is inert; it applies once the target is live again,
    // whether the Annul is annulled or voided.
    public function test_a_correction_of_an_annulled_target_is_inert_until_the_target_is_revived(): void
    {
        foreach (['Annul(Annul)' => fn (MemberHistory $h) => $h->annul('X2', 'X1'), 'Void(Annul)' => fn (MemberHistory $h) => $h->void('V1', 'X1')] as $kind => $revive) {
            $h = MemberHistory::start();
            $h->commit($h->admit('A1', 2));
            $h->commit($h->end('E1', 'A1', 5));
            $h->commit($h->annul('X1', 'E1'));
            $h->commit($h->correct('C1', 'E1', 7));
            self::assertClaim($h, 2, null, "$kind: inert while E1 is annulled");

            $h->commit($revive($h));
            self::assertClaim($h, 2, 7, "$kind: E1 revived with its corrected date");
        }
    }

    // §10a Correct (5) for an annulled admission: no period while annulled; on revival, the corrected start.
    public function test_a_corrected_admission_applies_when_the_admission_is_revived(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 3));
        $h->commit($h->annul('X1', 'A1'));
        $h->commit($h->correct('C1', 'A1', 1));
        $this->assertSame([], $h->member->resolve()->periods(), 'inert');

        $h->commit($h->annul('X2', 'X1'));
        self::assertClaim($h, 1, null, 'revived with the corrected start');
    }

    // Stage 7: the initial term starts at the corrected admission date (its until date is not correctable, D-COR-3).
    public function test_the_initial_term_follows_the_corrected_start(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 3, 6));
        $h->commit($h->correct('C1', 'A1', 1));

        $term = $h->member->resolve()->period(PeriodId::ofAdmit(FactId::fromString('A1')))->term();
        $this->assertTrue($term->equals(IntervalSet::of(Interval::of(TimePoint::of(1), TimePoint::of(6)))));
    }

    // P-KNOWN (R-12): a correction never rewrites what was known before it was recorded.
    public function test_a_correction_leaves_earlier_knowledge_unchanged(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 3, null, true), 10);
        $before = $h->member->resolve();
        $h->commit($h->correct('C1', 'A1', 1), 20);

        $this->assertEquals($before, $h->member->knownAt(Instant::of(19)));
        $this->assertTrue($h->member->knownAt(Instant::of(20))->isMember(TimePoint::of(1)));
    }
}
