<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\InstantMicros;
use App\Contexts\Membership\Infrastructure\Organisational\LedgerClockCloser;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresLedgerClockReader;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use Tests\Support\ScriptedLedgerClockReader;

/**
 * PBDIGIT-343 task 6 correction (Principal review of task 6; plan deviation 4): the clock row is created without an
 * unbounded wait, and the closer never waits on it.
 * - The defect: INSERT … ON CONFLICT DO NOTHING waits for any transaction holding an uncommitted change of the
 *   conflicting row (an uncommitted update, or an uncommitted insert of the same key).
 * - The append therefore creates its row in its own committed statement BEFORE its transaction (so an append in
 *   flight never holds an uncommitted new row), and the closer's insert is bounded by lock_timeout and fails closed.
 * Every test sets statement_timeout, so a wait FAILS the test instead of hanging the suite.
 */
final class PostgresLedgerClockRowInitialisationTest extends MembershipPostgresTestCase
{
    private const GUARD = "SET statement_timeout = '3s'";

    protected function setUp(): void
    {
        parent::setUp();
        $default = config('database.default');
        config(['database.connections.pbdigit343_holder' => config("database.connections.$default")]);
        DB::statement(self::GUARD);
    }

    protected function tearDown(): void
    {
        DB::statement('SET statement_timeout = 0');
        DB::purge('pbdigit343_holder');
        parent::tearDown();
    }

    private function organisation(): TenantId
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $id, 'name' => 'lc-init', 'slug' => 'lc-init-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);

        return TenantId::fromString($id);
    }

    private static function elapsedMs(\Closure $work): array
    {
        $start = hrtime(true);
        $result = $work();

        return [$result, (int) ((hrtime(true) - $start) / 1_000_000)];
    }

    public function test_the_defect_insert_on_conflict_waits_behind_an_uncommitted_update_of_the_row(): void
    {
        $t = $this->organisation();
        DB::table('org_membership_ledger_clocks')->insert(['organisation_id' => $t->value()]);
        $holder = DB::connection('pbdigit343_holder');
        $holder->beginTransaction();
        $holder->update('UPDATE org_membership_ledger_clocks SET closed_through = now() WHERE organisation_id = ?', [$t->value()]);

        try {
            DB::statement("SET lock_timeout = '300ms'");
            DB::statement('INSERT INTO org_membership_ledger_clocks (organisation_id) VALUES (?) ON CONFLICT (organisation_id) DO NOTHING', [$t->value()]);
            $this->fail('expected the insert to wait');
        } catch (QueryException $e) {
            $this->assertSame('55P03', $e->getCode(), 'it waited for the holder and hit lock_timeout: the original hang');
        } finally {
            DB::statement('SET lock_timeout = 0');
            $holder->rollBack();
        }
    }

    public function test_the_closer_does_not_wait_on_an_existing_row_held_by_an_uncommitted_update(): void
    {
        $t = $this->organisation();
        DB::table('org_membership_ledger_clocks')->insert(['organisation_id' => $t->value(), 'closed_through' => InstantMicros::toTimestamp(Instant::of(1_760_000_000_000_000))]);
        $holder = DB::connection('pbdigit343_holder');
        $holder->beginTransaction();
        $holder->update('UPDATE org_membership_ledger_clocks SET closed_through = now() WHERE organisation_id = ?', [$t->value()]);

        try {
            [$b, $ms] = self::elapsedMs(fn () => (new LedgerClockCloser(DB::connection(), new PostgresLedgerClockReader()))->closeOrRead($t));
        } finally {
            $holder->rollBack();
        }

        $this->assertSame(1_760_000_000_000_000, $b?->ordinal(), 'the last committed boundary');
        $this->assertLessThan(1_000, $ms, 'promptly, without waiting for the holder');
    }

    public function test_the_closer_does_not_wait_while_another_session_creates_the_row_and_fails_closed(): void
    {
        $t = $this->organisation();
        $holder = DB::connection('pbdigit343_holder');
        $holder->beginTransaction();
        $holder->insert('INSERT INTO org_membership_ledger_clocks (organisation_id) VALUES (?)', [$t->value()]);   // creation in flight, uncommitted

        try {
            [$b, $ms] = self::elapsedMs(fn () => (new LedgerClockCloser(DB::connection(), new PostgresLedgerClockReader()))->closeOrRead($t));
        } finally {
            $holder->rollBack();
        }

        $this->assertNull($b, 'no committed boundary exists: fail closed, nothing fabricated');
        $this->assertLessThan(1_000, $ms, 'bounded by lock_timeout, never waiting for the creator');
    }

    public function test_the_append_creates_its_clock_row_outside_its_transaction(): void
    {
        $t = $this->organisation();
        $levels = [];
        DB::listen(static function (QueryExecuted $q) use (&$levels): void {
            if (str_contains($q->sql, 'INSERT INTO org_membership_ledger_clocks')) {
                $levels[] = $q->connection->transactionLevel();
            }
        });
        $repo = new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(static fn (): int => 1_000));
        $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('person-M1'))->member();

        $outcome = $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k1'), 'fp1')->outcome();

        $this->assertSame(AppendOutcome::Appended, $outcome);
        $this->assertSame([0], $levels, 'created once, committed on its own, never inside the append transaction');
    }

    public function test_an_existing_row_is_not_inserted_again(): void
    {
        $t = $this->organisation();
        DB::table('org_membership_ledger_clocks')->insert(['organisation_id' => $t->value()]);
        $inserts = 0;
        DB::listen(static function (QueryExecuted $q) use (&$inserts): void {
            if (str_contains($q->sql, 'INSERT INTO org_membership_ledger_clocks')) {
                ++$inserts;
            }
        });

        (new LedgerClockCloser(DB::connection(), new PostgresLedgerClockReader()))->closeOrRead($t);

        $this->assertSame(0, $inserts);
    }

    #[Group('membership-race')]
    public function test_concurrent_first_time_initialisations_are_safe_and_bounded(): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
        $t = $this->organisation();
        $dir = sys_get_temp_dir() . '/pbdigit343-init-' . Str::random(8);
        mkdir($dir);
        DB::disconnect();
        $pids = [];
        for ($c = 0; $c < 6; $c++) {
            $pid = pcntl_fork();
            if ($pid === 0) {
                DB::purge();
                DB::statement(self::GUARD);
                while (!file_exists("$dir/go")) {
                    usleep(50);
                }
                try {
                    if ($c % 2 === 0) {
                        [$b, $ms] = self::elapsedMs(fn () => (new LedgerClockCloser(DB::connection(), new PostgresLedgerClockReader()))->closeOrRead($t));
                        $out = ['close', $ms];
                    } else {
                        $repo = new PostgresMemberRepository(DB::connection(), new PostgresLedgerClockReader());
                        $repo->create($t, MemberId::fromString("M$c"), PersonRef::fromString("person-M$c"));
                        $m = $repo->load($t, MemberId::fromString("M$c"));
                        [$r, $ms] = self::elapsedMs(fn () => $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString("A$c"), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString("k$c"), 'fp')->outcome()->value);
                        $out = ['append', $ms, $r];
                    }
                } catch (\Throwable $e) {
                    $out = ['error', $e::class . ': ' . $e->getMessage()];
                }
                file_put_contents("$dir/$c", json_encode($out));
                DB::disconnect();
                posix_kill(posix_getpid(), SIGKILL);
            }
            $pids[] = $pid;
        }
        usleep(50_000);
        touch("$dir/go");
        foreach ($pids as $pid) {
            pcntl_waitpid($pid, $status);
        }
        DB::reconnect();
        $results = array_map(static fn (int $c): array => json_decode((string) @file_get_contents("$dir/$c"), true) ?? ['error', 'no result'], range(0, 5));
        array_map('unlink', glob("$dir/*") ?: []);
        rmdir($dir);

        foreach ($results as $c => $r) {
            $this->assertNotSame('error', $r[0], "process $c: " . ($r[1] ?? ''));
            $this->assertLessThan(3_000, $r[1], "process $c was bounded");
            if ($r[0] === 'append') {
                $this->assertContains($r[2], ['appended', 'record_time_refused'], "process $c");
            }
        }
        $this->assertSame(1, DB::table('org_membership_ledger_clocks')->where('organisation_id', $t->value())->count(), 'exactly one clock row');
        // Boundary validity under concurrency is PostgresClosedThroughRaceTest's property; here: safe and bounded creation.
    }
}
