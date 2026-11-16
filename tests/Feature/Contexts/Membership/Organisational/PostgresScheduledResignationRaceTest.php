<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\Ports\OrganisationCalendar;
use App\Contexts\Membership\Application\Organisational\ScheduleResignation;
use App\Contexts\Membership\Application\Organisational\ScheduleResignationHandler;
use App\Contexts\Membership\Application\Organisational\WithdrawScheduledResignation;
use App\Contexts\Membership\Application\Organisational\WithdrawScheduledResignationHandler;
use App\Contexts\Membership\Domain\Organisational\DeclarationId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignationState;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\DueScheduledResignations;
use App\Contexts\Membership\Infrastructure\Organisational\LedgerClockCloser;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresLedgerClockReader;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresScheduledResignationStore;
use App\Contexts\Membership\Infrastructure\Organisational\ScheduledResignationExecutor;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * PBDIGIT-354 S2-4d, MI-2 (M-TDD-01 §5.2, §13 row 18, §14): "scheduled resignation: execute vs withdraw, both orders,
 * under the existing append order; never two Ends; withdrawal after the date refused" — exactly one succeeds
 * (compare-and-set), whether concurrent or delayed.
 *
 * Real concurrent processes (pcntl_fork), each with its own PostgreSQL connection and the REAL ledger clock
 * (clock_timestamp()), as PostgresConcurrencyRaceTest. Both sides take the organisation's ledger-clock row (the close
 * FOR UPDATE NOWAIT, the withdrawal FOR UPDATE), so they serialise on it, and the declaration's state is checked and
 * changed by compare-and-set in the same transaction.
 *
 * The midnight boundary: with one calendar and one clock, a withdrawal decided before the date and an execution on the
 * date are ordered by the clock row. To make BOTH sides eligible at once (the hardest case for the compare-and-set),
 * the close's calendar says "3 November" (due) while the withdrawal's says "2 November" (still allowed), as two
 * readings either side of midnight would.
 */
#[Group('membership-race')]
final class PostgresScheduledResignationRaceTest extends MembershipPostgresTestCase
{
    private const ROUNDS = 8;
    private const DUE = 20_030;          // 2024-11-03, the scheduled date
    private const BEFORE = 20_029;       // 2024-11-02

    protected function setUp(): void
    {
        parent::setUp();
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
    }

    private static function calendar(int $day, int $sleepMs = 0): OrganisationCalendar
    {
        return new class ($day, $sleepMs) implements OrganisationCalendar {
            public function __construct(private int $day, private int $sleepMs) {}
            public function localDateAt(TenantId $organisation, Instant $t): TimePoint
            {
                usleep($this->sleepMs * 1_000);   // a slow answer, while the caller holds its locks

                return TimePoint::of($this->day);
            }
        };
    }

    private static function members(): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), new PostgresLedgerClockReader());
    }

    private static function store(int $day = self::BEFORE, int $sleepMs = 0): PostgresScheduledResignationStore
    {
        return new PostgresScheduledResignationStore(DB::connection(), new PostgresLedgerClockReader(), self::calendar($day, $sleepMs));
    }

    private static function closer(DueScheduledResignations $due): LedgerClockCloser
    {
        return new LedgerClockCloser(DB::connection(), new PostgresLedgerClockReader(), $due);
    }

    private static function executor(int $day = self::DUE): ScheduledResignationExecutor
    {
        return new ScheduledResignationExecutor(self::calendar($day));
    }

    /** An organisation, M1 admitted (2024-10-04), D1 scheduled for 3 November, and a first close before the date. */
    private function organisation(bool $schedule = true): string
    {
        $org = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $org, 'name' => 's2-4d-race', 'slug' => 's24d-race-' . $org, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $this->assertTrue((new AdmitMemberHandler(self::members(), new FakeAuthority()))->handle(
            new AdmitMember($org, 'P1', 'M1', 'A1', 20_000, 20_000, 20_365, false, 'registrar-1', 'decl-fixture', null, 'k-admit'))->isAppended());
        if ($schedule) {
            $this->assertTrue((new ScheduleResignationHandler(self::members(), self::store(), new FakeAuthority()))->handle(
                new ScheduleResignation($org, 'M1', 'D1', 20_010, self::DUE, 'P1', null, 'k-schedule'))->isRecorded());
        }
        $this->assertNotNull(self::closer(self::executor(self::BEFORE))->closeOrRead(TenantId::fromString($org)), 'a first close, before the date');

        return $org;
    }

    /** @return array{0: string, 1?: mixed} */
    private static function withdraw(string $org, PostgresScheduledResignationStore $store): array
    {
        $o = (new WithdrawScheduledResignationHandler(self::members(), $store, new FakeAuthority()))->handle(new WithdrawScheduledResignation($org, 'M1', 'D1', 20_020, 'P1', 'k-withdraw'));

        return $o->isRecorded() ? ['withdrawn'] : ['refused', ($o->domainRefusal() ?? $o->applicationRefusal())->value];
    }

    /** @return array{0: string, 1: ?int} */
    private static function close(string $org, DueScheduledResignations $due): array
    {
        return ['closed', self::closer($due)->closeOrRead(TenantId::fromString($org))?->ordinal()];
    }

    /**
     * Runs each closure in its own forked child (own connection), released together at a barrier; returns their
     * results by index. As PostgresConcurrencyRaceTest::race().
     *
     * @param list<\Closure(): array> $work
     * @return list<array>
     */
    private function race(array $work): array
    {
        $dir = sys_get_temp_dir() . '/s24d-race-' . Str::random(8);
        mkdir($dir);
        $barrier = "$dir/go";
        DB::disconnect();
        $pids = [];
        foreach ($work as $c => $job) {
            $pid = pcntl_fork();
            if ($pid === 0) {
                DB::purge();
                $deadline = microtime(true) + 5;
                while (!file_exists($barrier) && microtime(true) < $deadline) {
                    usleep(200);
                }
                try {
                    $result = $job();
                } catch (\Throwable $e) {
                    $result = ['error', $e::class . ': ' . $e->getMessage()];
                }
                file_put_contents("$dir/$c.json", json_encode($result));
                DB::disconnect();
                posix_kill(posix_getpid(), SIGKILL);   // no PHPUnit shutdown in the child
            }
            $pids[] = $pid;
        }
        usleep(50_000);
        touch($barrier);
        foreach ($pids as $pid) {
            pcntl_waitpid($pid, $status);
        }
        DB::reconnect();
        $results = [];
        foreach (array_keys($work) as $c) {
            $results[] = json_decode((string) @file_get_contents("$dir/$c.json"), true) ?? ['error', 'no result'];
        }
        array_map('unlink', glob("$dir/*") ?: []);
        rmdir($dir);

        return $results;
    }

    /** The state after a race: declaration state, End facts, the ledger clock row. */
    private static function outcome(string $org): array
    {
        $t = TenantId::fromString($org);
        $state = self::store()->find($t, MemberId::fromString('M1'), DeclarationId::fromString('D1'))->state();
        $ends = DB::table('org_membership_facts')->where('organisation_id', $org)->where('kind', 'end')->get(['fact_id', 'effective_at'])->all();
        $clock = DB::selectOne('SELECT (extract(epoch from last_recorded_at) * 1000000)::bigint AS last_us, (extract(epoch from closed_through) * 1000000)::bigint AS closed_us FROM org_membership_ledger_clocks WHERE organisation_id = ?', [$org]);
        $endRecorded = DB::table('org_membership_facts')->where('organisation_id', $org)->where('kind', 'end')->value(DB::raw('(extract(epoch from recorded_at) * 1000000)::bigint'));

        return [$state, $ends, (int) $clock->last_us, $clock->closed_us === null ? null : (int) $clock->closed_us, $endRecorded === null ? null : (int) $endRecorded];
    }

    /** Exactly one of the two succeeded, and the ledger agrees with it. */
    private function assertExactlyOne(string $org, array $withdrawal, array $close, string $round): string
    {
        [$state, $ends, $last, $closed, $endRecorded] = self::outcome($org);
        $context = "$round " . json_encode([$withdrawal, $close, $state->value, count($ends)]);
        if ($state === ScheduledResignationState::Executed) {
            $this->assertCount(1, $ends, "$context: one End");
            $this->assertSame(['scheduled-resignation:D1', '2024-11-03'], [$ends[0]->fact_id, $ends[0]->effective_at], $context);
            $this->assertSame(['refused', 'declaration_not_scheduled'], $withdrawal, "$context: the withdrawal lost the compare-and-set");
            $this->assertSame($endRecorded, $last, "$context: the clock's last record is the End");
            $this->assertGreaterThanOrEqual($endRecorded, $closed, "$context: the End is inside closedThrough");

            return 'executed';
        }
        $this->assertSame(ScheduledResignationState::Withdrawn, $state, $context);
        $this->assertSame([], $ends, "$context: no End");
        $this->assertSame(['withdrawn'], $withdrawal, $context);

        return 'withdrawn';
    }

    public function test_mi2_an_execution_holding_the_lock_makes_a_concurrent_withdrawal_lose(): void
    {
        $org = $this->organisation();
        $slowAfterExecuting = new class (self::executor()) implements DueScheduledResignations {
            public function __construct(private DueScheduledResignations $inner) {}
            public function executeDue(Connection $db, TenantId $organisation, Instant $t): void
            {
                $this->inner->executeDue($db, $organisation, $t);
                usleep(400_000);   // executed, not yet committed: the clock row and the declaration stay locked
            }
        };

        [$close, $withdrawal] = $this->race([
            fn (): array => self::close($org, $slowAfterExecuting),
            function () use ($org): array { usleep(100_000); return self::withdraw($org, self::store()); },   // starts while the close holds the lock
        ]);

        $this->assertNotNull($close[1], json_encode($close));
        $this->assertSame('executed', $this->assertExactlyOne($org, $withdrawal, $close, 'execution first'));
    }

    public function test_mi2_a_withdrawal_holding_the_lock_holds_back_a_concurrent_close(): void
    {
        $org = $this->organisation();
        $before = self::outcome($org)[3];

        [$withdrawal, $close] = $this->race([
            fn (): array => self::withdraw($org, self::store(self::BEFORE, sleepMs: 400)),   // holds the clock row while its calendar answers
            function () use ($org): array { usleep(100_000); return self::close($org, self::executor()); },
        ]);

        $this->assertSame('withdrawn', $this->assertExactlyOne($org, $withdrawal, $close, 'withdrawal first'));
        $this->assertSame(['closed', $before], $close, 'the close found the clock row busy: it did not advance (nor execute)');
        $after = self::close($org, self::executor());
        $this->assertGreaterThan($before, $after[1], 'the next close advances and executes nothing');
        $this->assertSame('withdrawn', $this->assertExactlyOne($org, $withdrawal, $after, 'after the next close'));
    }

    /** @return array{0: string, 1?: mixed} a competing "resign now" effective 8 November (on or after the scheduled 3 November) */
    private static function resignNow(string $org): array
    {
        $o = (new \App\Contexts\Membership\Application\Organisational\ResignMembershipHandler(self::members(), new FakeAuthority()))->handle(
            new \App\Contexts\Membership\Application\Organisational\ResignMembership($org, 'M1', 'E-now', 20_035, 'P1', null, 'k-now'));

        return $o->isAppended() ? ['appended'] : ['refused', ($o->domainRefusal() ?? $o->applicationRefusal())->value];
    }

    /** D-240 under concurrency: whatever the order, the only End ever recorded is the scheduled one, on its date. */
    private function assertOnlyTheScheduledEnd(string $org, array $competing, string $round): void
    {
        self::close($org, self::executor());   // a later close records it if the race left it pending
        [$state, $ends] = self::outcome($org);
        $context = "$round " . json_encode([$competing, $state->value, $ends]);
        $this->assertSame(ScheduledResignationState::Executed, $state, $context);
        $this->assertSame([['scheduled-resignation:D1', '2024-11-03']], array_map(static fn ($e) => [$e->fact_id, $e->effective_at], $ends), "$context: one End, on the member's date");
        $this->assertSame('refused', $competing[0], "$context: the competing End never lands");
        $this->assertContains($competing[1], ['scheduled_resignation_takes_effect_first', 'period_not_found', 'period_already_ended'], $context);
    }

    public function test_d240_an_execution_holding_the_lock_leaves_a_competing_end_nothing_to_end(): void
    {
        $org = $this->organisation();
        $slowAfterExecuting = new class (self::executor()) implements DueScheduledResignations {
            public function __construct(private DueScheduledResignations $inner) {}
            public function executeDue(Connection $db, TenantId $organisation, Instant $t): void
            {
                $this->inner->executeDue($db, $organisation, $t);
                usleep(400_000);
            }
        };

        [$close, $competing] = $this->race([
            fn (): array => self::close($org, $slowAfterExecuting),
            function () use ($org): array { usleep(100_000); return self::resignNow($org); },   // waits on the clock row
        ]);

        $this->assertNotNull($close[1], json_encode($close));
        $this->assertOnlyTheScheduledEnd($org, $competing, 'execution first');
    }

    /**
     * D-240 atomicity against SCHEDULING: a scheduling in flight holds the membership row (as
     * PostgresScheduledResignationStore::record does) while its declaration is inserted; a competing End starting then
     * must wait for it and see it, never check "no pending declaration" and append behind its back. (Scheduling does
     * not change the membership version, so the version compare-and-set alone would not catch it.)
     */
    public function test_d240_a_competing_end_waits_for_a_scheduling_in_flight_and_is_refused(): void
    {
        $org = $this->organisation(schedule: false);

        [$scheduling, $competing] = $this->race([
            function () use ($org): array {
                DB::transaction(function () use ($org): void {
                    DB::selectOne('SELECT version FROM org_memberships WHERE organisation_id = ? AND id = ? FOR UPDATE', [$org, 'M1']);
                    DB::insert("INSERT INTO org_membership_scheduled_resignations (organisation_id, membership_id, declaration_id, period_fact_id, declaration_date, effective_date, provenance, recorded_at, state)
                        VALUES (?, 'M1', 'D1', 'A1', '2024-10-14', '2024-11-03', ?, clock_timestamp(), 'scheduled')", [$org, \App\Contexts\Membership\Infrastructure\Organisational\ProvenanceJson::encode(\App\Contexts\Membership\Domain\Organisational\Provenance::decision('P1'))]);
                    usleep(400_000);   // in flight: the membership row stays locked
                });

                return ['scheduled'];
            },
            function () use ($org): array { usleep(100_000); return self::resignNow($org); },
        ]);

        $this->assertSame(['scheduled'], $scheduling);
        $this->assertSame(['refused', 'scheduled_resignation_takes_effect_first'], $competing, 'it waited for the scheduling and saw the declaration');
        $this->assertOnlyTheScheduledEnd($org, $competing, 'scheduling in flight');
    }

    public function test_d240_free_races_between_a_competing_end_and_the_close_never_displace_the_members_date(): void
    {
        $outcomes = [];
        for ($round = 0; $round < self::ROUNDS; $round++) {
            $org = $this->organisation();

            [$close, $competing] = $this->race([
                fn (): array => self::close($org, self::executor()),
                fn (): array => self::resignNow($org),
            ]);

            $this->assertNotSame('error', $close[0], json_encode($close));
            $this->assertOnlyTheScheduledEnd($org, $competing, "round $round");
            $outcomes[] = $competing[1];
        }
        fwrite(STDERR, "\nD-240 free races, the competing End's refusals: " . json_encode(array_count_values($outcomes)) . "\n");
    }

    public function test_mi2_free_races_always_end_with_exactly_one_success_and_never_two_ends(): void
    {
        $winners = [];
        for ($round = 0; $round < self::ROUNDS; $round++) {
            $org = $this->organisation();

            [$close, $withdrawal] = $this->race([
                fn (): array => self::close($org, self::executor()),
                fn (): array => self::withdraw($org, self::store()),
            ]);

            $this->assertNotSame('error', $close[0], json_encode($close));
            $winners[] = $this->assertExactlyOne($org, $withdrawal, $close, "round $round");
            self::close($org, self::executor());   // a later close never executes a withdrawn declaration, nor twice
            $this->assertExactlyOne($org, $withdrawal, $close, "round $round, after a later close");
        }
        fwrite(STDERR, "\nMI-2 free races, winners: " . json_encode(array_count_values($winners)) . "\n");
    }
}
