<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\CoverageKnownAt;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipSourceOfTruthPort;
use App\Contexts\Membership\Application\Organisational\Ports\OrganisationCalendar;
use App\Contexts\Membership\Application\Organisational\Ports\WorkingOrganisationPort;
use App\Contexts\Membership\Application\Organisational\PublishedLanguage\CoverageKnownAtRefusal;
use App\Contexts\Membership\Application\Organisational\ResignMembership;
use App\Contexts\Membership\Application\Organisational\ResignMembershipHandler;
use App\Contexts\Membership\Application\Organisational\ScheduleResignation;
use App\Contexts\Membership\Application\Organisational\ScheduleResignationHandler;
use App\Contexts\Membership\Application\Organisational\WithdrawScheduledResignation;
use App\Contexts\Membership\Application\Organisational\WithdrawScheduledResignationHandler;
use App\Contexts\Membership\Domain\Organisational\DeclarationId;
use App\Contexts\Membership\Domain\Organisational\End;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignation;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignationState;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\DueScheduledResignations;
use App\Contexts\Membership\Infrastructure\Organisational\LedgerClockCloser;
use App\Contexts\Membership\Infrastructure\Organisational\LedgerClockCloserBoundary;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresScheduledResignationStore;
use App\Contexts\Membership\Infrastructure\Organisational\ScheduledResignationExecutor;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\FixedOffsetOrganisationCalendar;
use Tests\Support\ScriptedLedgerClockReader;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * PBDIGIT-354 S2-4c on PostgreSQL: a scheduled resignation is executed in the ledger's closing step (D-204, D-205 (2),
 * D-209, D-223, D-239; M-TDD-01 §5.2; MI-3, MI-10, MI-15a/b/e, LC-4).
 * - The close asks the OrganisationCalendar for the organisation-local date of its own reading t, and records the End
 *   of every declaration due by that date IN THE SAME TRANSACTION, with recordedAt = t, before closedThrough = t.
 * - A failed execution rolls the whole close back: closedThrough stays, C2 refuses later τ (BOUNDARY_NOT_CLOSED).
 * - A declaration whose period already ended is superseded, not executed (MI-3).
 * Day ordinals: 20000 = 2024-10-04, 20010 = 2024-10-14, 20020 = 2024-10-24, 20030 = 2024-11-03, 20045 = 2024-11-18.
 * The closer's readings are real instants (µs), so the calendar's local date is meaningful.
 */
final class PostgresScheduledResignationExecutionTest extends MembershipPostgresTestCase
{
    private const DECIDED_AT = 1_728_900_000_000_000;   // 2024-10-14T10:00Z, when the member declared

    /** µs at (day ordinal, hh:mm UTC) */
    private static function at(int $day, int $hour = 0, int $minute = 0): int
    {
        return ($day * 86_400 + $hour * 3_600 + $minute * 60) * 1_000_000;
    }

    private function members(int $reading): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(static fn (): int => $reading));
    }

    private function store(): PostgresScheduledResignationStore
    {
        // S2-4d: withdrawal is decided at ledger time; here at 2024-10-24 12:00 UTC, UTC calendar (TEST ONLY)
        return new PostgresScheduledResignationStore(DB::connection(), new ScriptedLedgerClockReader(static fn (): int => self::at(20_020, 12)), new FixedOffsetOrganisationCalendar(0));
    }

    private function closer(int $reading, DueScheduledResignations $due): LedgerClockCloser
    {
        return new LedgerClockCloser(DB::connection(), new ScriptedLedgerClockReader(static fn (): int => $reading), $due);
    }

    private static function executor(int $offsetMinutes = 0): ScheduledResignationExecutor
    {
        return new ScheduledResignationExecutor(new FixedOffsetOrganisationCalendar($offsetMinutes));
    }

    /** An organisation; member M<n> (P<n>) admitted 2024-10-04 (recorded then); one declaration each, if a date is given. */
    private function organisation(array $scheduled = ['1' => 20_030]): TenantId
    {
        $org = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $org, 'name' => 's2-4c', 'slug' => 's24c-' . $org, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $minute = 0;
        foreach ($scheduled as $n => $effective) {
            $this->assertTrue((new AdmitMemberHandler($this->members(self::at(20_000, 9, $minute++)), new FakeAuthority()))->handle(
                new AdmitMember($org, "P{$n}", "M{$n}", "A{$n}", 20_000, 20_000, 20_365, false, 'registrar-1', 'decl-fixture', null, "k-admit-{$n}"))->isAppended());
            if ($effective !== null) {
                $this->assertTrue((new ScheduleResignationHandler($this->members(0), $this->store(), new FakeAuthority()))->handle(
                    new ScheduleResignation($org, "M{$n}", "D{$n}", 20_010, $effective, "P{$n}", self::DECIDED_AT, "k-schedule-{$n}"))->isRecorded());
            }
        }

        return TenantId::fromString($org);
    }

    private function declaration(TenantId $org, string $n = '1'): ScheduledResignation
    {
        return $this->store()->find($org, MemberId::fromString("M{$n}"), DeclarationId::fromString("D{$n}"));
    }

    /** @return list<End> */
    private function ends(TenantId $org, string $n = '1'): array
    {
        $log = $this->members(0)->load($org, MemberId::fromString("M{$n}"))->log();

        return array_values(array_map(static fn ($r) => $r->fact(), array_filter($log, static fn ($r) => $r->fact() instanceof End)));
    }

    /** The one End of member <n>: asserted, so a missing End fails the test instead of crashing it. */
    private function onlyEnd(TenantId $org, string $n = '1'): End
    {
        $ends = $this->ends($org, $n);
        $this->assertCount(1, $ends, "member {$n}: exactly one End");

        return $ends[0];
    }

    private function c1Count(TenantId $org): int
    {
        return (int) DB::selectOne("SELECT count(*) AS n FROM outbox_events WHERE organisation_id = ? AND event_type = 'MembershipCoverageResolved'", [$org->value()])->n;
    }

    public function test_mi15a_on_its_date_the_close_records_the_end_and_marks_the_declaration_executed(): void
    {
        $org = $this->organisation();
        $events = $this->c1Count($org);
        $t = self::at(20_030, 1);

        $closed = $this->closer($t, self::executor())->closeOrRead($org);

        $this->assertSame($t, $closed?->ordinal(), 'the boundary advances with the End recorded');
        $ends = $this->ends($org);
        $this->assertCount(1, $ends);
        $this->assertSame(['resignation', 20_030, 'A1', 'D1', 'P1', self::DECIDED_AT], [$ends[0]->kind()->value(), $ends[0]->effectiveAt()->ordinal(), $ends[0]->period()->value(),
            $ends[0]->provenance()->declarationRef(), $ends[0]->provenance()->actorOrSource(), $ends[0]->decidedAt()?->ordinal()], 'D-205 (2): effective on its date, decided by the declaration');
        $recorded = $this->members(0)->load($org, MemberId::fromString('M1'))->log()[1]->recordedAt()->ordinal();
        $this->assertSame($t, $recorded, 'recorded by the close, at its reading');
        $d = $this->declaration($org);
        $this->assertSame([ScheduledResignationState::Executed, $ends[0]->id()->value()], [$d->state(), $d->executedAs()?->value()], 'the declaration names its End');
        $this->assertSame($events + 1, $this->c1Count($org), 'C1 publishes the end of coverage, in the same transaction');
    }

    public function test_before_its_date_nothing_is_executed_and_the_boundary_advances(): void
    {
        $org = $this->organisation();
        $t = self::at(20_029, 23, 59);

        $this->assertSame($t, $this->closer($t, self::executor())->closeOrRead($org)?->ordinal());
        $this->assertSame([], $this->ends($org));
        $this->assertSame(ScheduledResignationState::Scheduled, $this->declaration($org)->state());
    }

    public function test_the_organisation_local_date_decides_not_utc(): void
    {
        $t = self::at(20_029, 20);   // 2024-11-02 20:00 UTC = 2024-11-03 01:45 in Nepal (UTC+5:45)
        $utc = $this->organisation();
        $nepal = $this->organisation();

        $this->closer($t, self::executor(0))->closeOrRead($utc);
        $this->closer($t, self::executor(345))->closeOrRead($nepal);

        $this->assertSame([], $this->ends($utc), 'still 2 November there');
        $this->assertSame(20_030, $this->onlyEnd($nepal)->effectiveAt()->ordinal(), 'already 3 November there (D-239)');
    }

    public function test_mi15b_late_execution_still_takes_effect_on_the_scheduled_date(): void
    {
        $org = $this->organisation();
        $t = self::at(20_045, 8);

        $this->closer($t, self::executor())->closeOrRead($org);

        $end = $this->onlyEnd($org);
        $this->assertSame([20_030, self::DECIDED_AT], [$end->effectiveAt()->ordinal(), $end->decidedAt()?->ordinal()], 'D-223: effective on the scheduled date, decided by the declaration');
        $this->assertSame($t, $this->members(0)->load($org, MemberId::fromString('M1'))->log()[1]->recordedAt()->ordinal(), 'recorded late');
    }

    public function test_mi10_lc4_a_failed_execution_holds_the_boundary_and_c2_refuses(): void
    {
        $org = $this->organisation();
        $before = $this->closer(self::at(20_020), self::executor())->closeOrRead($org)?->ordinal();
        $broken = new ScheduledResignationExecutor(new class implements OrganisationCalendar {
            public function localDateAt(TenantId $organisation, Instant $t): TimePoint
            {
                throw new \RuntimeException('calendar unavailable');
            }
        });

        $held = $this->closer(self::at(20_030, 1), $broken)->closeOrRead($org)?->ordinal();

        $this->assertSame($before, $held, 'closedThrough does not pass the date with the End unrecorded (LC-4)');
        $this->assertSame([], $this->ends($org));
        $this->assertSame(ScheduledResignationState::Scheduled, $this->declaration($org)->state());
        $c2 = new CoverageKnownAt(
            $this->members(0),
            new LedgerClockCloserBoundary($this->closer(self::at(20_030, 2), $broken)),
            new class implements MembershipSourceOfTruthPort { public function isLedger(TenantId $organisation): bool { return true; } },   // TEST ONLY
            new class ($org) implements WorkingOrganisationPort { public function __construct(private TenantId $t) {} public function current(): ?TenantId { return $this->t; } },
        );
        $answer = $c2->byMembershipIds($org, [MemberId::fromString('M1')], Instant::of(self::at(20_030, 1)));
        $this->assertSame(CoverageKnownAtRefusal::BoundaryNotClosed, $answer->refusal(), 'MI-10 / MI-15e: nobody is told "member" after the date');

        $this->closer(self::at(20_030, 3), self::executor())->closeOrRead($org);
        $this->assertCount(1, $this->ends($org), 'once the calendar answers, the next close executes it');
    }

    private function c2(TenantId $org, LedgerClockCloser $closer): CoverageKnownAt
    {
        return new CoverageKnownAt(
            $this->members(0),
            new LedgerClockCloserBoundary($closer),
            new class implements MembershipSourceOfTruthPort { public function isLedger(TenantId $organisation): bool { return true; } },   // TEST ONLY
            new class ($org) implements WorkingOrganisationPort { public function __construct(private TenantId $t) {} public function current(): ?TenantId { return $this->t; } },
        );
    }

    /** @return list<array{0: string, 1: ?string}> C2's coverage of M1 at τ (complete answers only) */
    private function coverageAt(TenantId $org, LedgerClockCloser $closer, int $tau): array
    {
        $answer = $this->c2($org, $closer)->byMembershipIds($org, [MemberId::fromString('M1')], Instant::of($tau));
        $this->assertTrue($answer->isComplete(), "complete at τ = $tau");

        return array_map(static fn ($i) => [$i->from, $i->to], $answer->items()[0]->coverage);
    }

    /**
     * S2-4d, MI-4 (M-TDD-01 §5.3, §14): "C1 / C2 show no planned change before execution; a backdated End changes
     * coverage only as known from its recording". C1 = MembershipCoverageResolved in the organisation's outbox, written
     * only when coverage, inForce or suspended change; C2 = CoverageKnownAt(τ), complete or refused.
     */
    public function test_mi4_c1_and_c2_show_nothing_before_execution_and_the_end_only_as_known_from_its_recording(): void
    {
        $org = $this->organisation([]);
        $this->assertTrue((new AdmitMemberHandler($this->members(self::at(20_000, 9)), new FakeAuthority()))->handle(
            new AdmitMember($org->value(), 'P1', 'M1', 'A1', 20_000, 20_000, 20_365, false, 'registrar-1', 'decl-fixture', null, 'k-admit'))->isAppended());
        $afterAdmission = $this->c1Count($org);
        $this->assertTrue((new ScheduleResignationHandler($this->members(0), $this->store(), new FakeAuthority()))->handle(
            new ScheduleResignation($org->value(), 'M1', 'D1', 20_010, 20_030, 'P1', self::DECIDED_AT, 'k-schedule'))->isRecorded());
        $open = [['2024-10-04', null]];   // coverage is the lifetime (open until an End); the term shows in inForce

        $this->assertSame($afterAdmission, $this->c1Count($org), 'C1: scheduling publishes nothing (the declaration is not a fact)');
        $this->assertSame($open, $this->coverageAt($org, $this->closer(self::at(20_025), self::executor()), self::at(20_025)), 'C2 before the date: unchanged');

        // due, not yet executed (the calendar fails): earlier τ keep their answer; τ on the date are refused, never "member"
        $broken = new ScheduledResignationExecutor(new class implements OrganisationCalendar {
            public function localDateAt(TenantId $organisation, Instant $t): TimePoint { throw new \RuntimeException('calendar unavailable'); }
        });
        $this->assertSame($open, $this->coverageAt($org, $this->closer(self::at(20_030, 1), $broken), self::at(20_025)));
        $refused = $this->c2($org, $this->closer(self::at(20_030, 2), $broken))->byMembershipIds($org, [MemberId::fromString('M1')], Instant::of(self::at(20_030, 1)));
        $this->assertSame(CoverageKnownAtRefusal::BoundaryNotClosed, $refused->refusal(), 'due but unexecuted: no answer from the date on');
        $this->assertSame($afterAdmission, $this->c1Count($org), 'C1: still nothing');

        // executed late, on 18 November: only the committed End changes the answer, and only as known from its recording
        $this->assertSame([['2024-10-04', '2024-11-03']], $this->coverageAt($org, $this->closer(self::at(20_045, 8), self::executor()), self::at(20_045, 8)), 'from its recording: ended on 3 November');
        $this->assertSame($open, $this->coverageAt($org, $this->closer(self::at(20_045, 9), self::executor()), self::at(20_025)), 'as known on 29 October: unchanged (historical answers do not change)');
        $this->assertSame($afterAdmission + 1, $this->c1Count($org), 'C1: one event, from the End');
        $events = array_map(static fn ($r) => json_decode($r->payload), DB::select("SELECT payload FROM outbox_events WHERE organisation_id = ? AND event_type = 'MembershipCoverageResolved'", [$org->value()]));
        usort($events, static fn (object $x, object $y): int => $x->seq <=> $y->seq);   // by seq, never created_at (guide 26)
        $last = end($events);
        $this->assertSame([['2024-10-04', '2024-11-03']], array_map(static fn ($i) => [$i->from, $i->to], $last->coverage));
    }

    public function test_the_execution_and_the_close_are_one_transaction(): void
    {
        $org = $this->organisation(['1' => 20_030, '2' => 20_030]);
        $events = $this->c1Count($org);
        $before = $this->closer(self::at(20_020), self::executor())->closeOrRead($org)?->ordinal();
        $failsAfterwards = new class (self::executor()) implements DueScheduledResignations {
            public function __construct(private DueScheduledResignations $inner) {}
            public function executeDue(Connection $db, TenantId $organisation, Instant $t): void
            {
                $this->inner->executeDue($db, $organisation, $t);
                throw new \RuntimeException('a failure after both Ends were written');
            }
        };

        $this->assertSame($before, $this->closer(self::at(20_030, 1), $failsAfterwards)->closeOrRead($org)?->ordinal());

        $this->assertSame([[], []], [$this->ends($org, '1'), $this->ends($org, '2')], 'both Ends rolled back');
        $this->assertSame([ScheduledResignationState::Scheduled, ScheduledResignationState::Scheduled], [$this->declaration($org, '1')->state(), $this->declaration($org, '2')->state()]);
        $this->assertSame($events, $this->c1Count($org), 'no C1 event escaped');
    }

    public function test_every_due_declaration_is_executed_in_one_close_and_later_ones_wait(): void
    {
        $org = $this->organisation(['1' => 20_030, '2' => 20_031, '3' => 20_040, '4' => null]);

        $this->closer(self::at(20_031, 6), self::executor())->closeOrRead($org);

        $this->assertSame([20_030, 20_031], [$this->onlyEnd($org, '1')->effectiveAt()->ordinal(), $this->onlyEnd($org, '2')->effectiveAt()->ordinal()]);
        $this->assertSame([[], []], [$this->ends($org, '3'), $this->ends($org, '4')]);
        $this->assertSame(ScheduledResignationState::Scheduled, $this->declaration($org, '3')->state());
    }

    public function test_mi3_a_declaration_whose_period_ended_earlier_is_superseded_not_executed(): void
    {
        $org = $this->organisation();
        $this->assertTrue((new ResignMembershipHandler($this->members(self::at(20_020, 9)), new FakeAuthority()))->handle(
            new ResignMembership($org->value(), 'M1', 'E-now', 20_020, 'P1', null, 'k-resign-now'))->isAppended());

        $this->assertSame(self::at(20_030, 1), $this->closer(self::at(20_030, 1), self::executor())->closeOrRead($org)?->ordinal(), 'superseding is not a failure: the close advances');

        $ends = $this->ends($org);
        $this->assertSame(['E-now'], array_map(static fn (End $e) => $e->id()->value(), $ends), 'no second End');
        $d = $this->declaration($org);
        $this->assertSame(ScheduledResignationState::Superseded, $d->state());
        $this->assertStringContainsString('period_already_ended', (string) $d->supersededBecause());
    }

    /**
     * S2-4d, part D, the case the accepted rules settle (MI-3, M-TDD-01 §5.2 "the period ended EARLIER"): the
     * declaration is due but unexecuted (the close is failing), and a resignation effective BEFORE the scheduled date is
     * recorded meanwhile. When the close runs, the declaration is superseded with its reason; one End only; the history
     * keeps the declaration and names why. (An ending on or after the scheduled date is Q-5, not decided: plan §7d.)
     */
    public function test_s2_4d_an_earlier_ending_recorded_while_the_declaration_is_due_supersedes_it(): void
    {
        $org = $this->organisation();
        $broken = new ScheduledResignationExecutor(new class implements OrganisationCalendar {
            public function localDateAt(TenantId $organisation, Instant $t): TimePoint { throw new \RuntimeException('calendar unavailable'); }
        });
        $held = $this->closer(self::at(20_030, 1), $broken)->closeOrRead($org)?->ordinal();
        $this->assertNull($held, 'never closed: the due declaration holds the boundary');

        $this->assertTrue((new ResignMembershipHandler($this->members(self::at(20_035, 9)), new FakeAuthority()))->handle(
            new ResignMembership($org->value(), 'M1', 'E-earlier', 20_025, 'P1', null, 'k-resign-earlier'))->isAppended(), 'recorded on 8 November, effective 29 October');
        $this->assertSame(self::at(20_035, 10), $this->closer(self::at(20_035, 10), self::executor())->closeOrRead($org)?->ordinal());

        $this->assertSame(['E-earlier'], array_map(static fn (End $e) => $e->id()->value(), $this->ends($org)), 'one End, the earlier one');
        $this->assertSame(20_025, $this->onlyEnd($org)->effectiveAt()->ordinal());
        $d = $this->declaration($org);
        $this->assertSame(ScheduledResignationState::Superseded, $d->state());
        $this->assertSame([20_010, 20_030], [$d->declarationDate()->ordinal(), $d->effectiveDate()->ordinal()], 'the declaration stays in the history');
    }

    public function test_a_withdrawn_declaration_is_never_executed(): void
    {
        $org = $this->organisation();
        $this->assertTrue((new WithdrawScheduledResignationHandler($this->members(0), $this->store(), new FakeAuthority()))->handle(
            new WithdrawScheduledResignation($org->value(), 'M1', 'D1', 20_020, 'P1', 'k-withdraw'))->isRecorded());

        $this->assertSame(self::at(20_030, 1), $this->closer(self::at(20_030, 1), self::executor())->closeOrRead($org)?->ordinal(), 'not due: the close advances');

        $this->assertSame([], $this->ends($org));
        $this->assertSame(ScheduledResignationState::Withdrawn, $this->declaration($org)->state());
    }
}
