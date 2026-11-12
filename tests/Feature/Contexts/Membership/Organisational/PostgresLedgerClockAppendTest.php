<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\InstantMicros;
use App\Contexts\Membership\Infrastructure\Organisational\MembershipCoverageResolvedOutbox;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\ScriptedLedgerClockReader;

/**
 * PBDIGIT-343 task 4 (plan 20261112-1251 D-2, D-5; D-209; D-215 K-C1; tests 2, 3, 8, 12, 13, 15, 16 and the lock order).
 * The append takes the organisation's clock row first, reads the database clock, refuses a reading that does not
 * advance (never adjusts it), then compares the version, stores the fact with recordedAt = the reading, updates the
 * clock and writes the C1 row, all in one transaction on the repository's own connection.
 */
final class PostgresLedgerClockAppendTest extends MembershipPostgresTestCase
{
    private int $reading = 1_000;

    private function repo(?string $connection = null): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection($connection), new ScriptedLedgerClockReader(fn (): int => $this->reading));
    }

    private function organisation(): TenantId
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $id, 'name' => 'lc-append', 'slug' => 'lc-append-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);

        return TenantId::fromString($id);
    }

    private function admit(PostgresMemberRepository $repo, TenantId $t, string $member, int $reading): AppendOutcome
    {
        $repo->create($t, MemberId::fromString($member), PersonRef::fromString("person-$member"));
        $m = $repo->load($t, MemberId::fromString($member));
        $d = $m->decideAdmit(FactId::fromString("A-$member"), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture'));
        $this->reading = $reading;

        return $repo->append($t, $m->id(), $d, IdempotencyKey::fromString("k-$member"), 'fp')->outcome();
    }

    private function endAt(PostgresMemberRepository $repo, TenantId $t, string $member, int $reading, string $key = 'k-end'): AppendOutcome
    {
        $m = $repo->load($t, MemberId::fromString($member));
        $d = $m->decideEnd(FactId::fromString("E-$member"), PeriodId::fromString("A-$member"), TimePoint::of(20_100), TimePoint::of(20_100), EndKind::fromString('resignation'), Provenance::decision('registrar'));
        $this->reading = $reading;

        return $repo->append($t, $m->id(), $d, IdempotencyKey::fromString($key), 'fp-end')->outcome();
    }

    /** @return list<int> recordedAt of $member's facts, in seq order */
    private function recordedAts(TenantId $t, string $member): array
    {
        return array_map(static fn (object $r): int => (int) $r->us, DB::table('org_membership_facts')
            ->where('organisation_id', $t->value())->where('membership_id', $member)->orderBy('seq')
            ->select([DB::raw(InstantMicros::selectExpression('recorded_at') . ' AS us')])->get()->all());
    }

    private function lastRecordedAt(TenantId $t): ?int
    {
        $v = DB::table('org_membership_ledger_clocks')->where('organisation_id', $t->value())
            ->value(DB::raw(InstantMicros::selectExpression('last_recorded_at')));

        return $v === null ? null : (int) $v;
    }

    private function c1Rows(TenantId $t): array
    {
        return DB::table('outbox_events')->where('organisation_id', $t->value())->where('event_type', 'MembershipCoverageResolved')->orderBy('created_at')->get()->all();
    }

    public function test_lc1_the_stored_record_time_is_the_clock_reading_exactly(): void
    {
        $repo = $this->repo();
        $t = $this->organisation();

        $this->assertSame(AppendOutcome::Appended, $this->admit($repo, $t, 'M1', 1_000));
        $this->assertSame(AppendOutcome::Appended, $this->endAt($repo, $t, 'M1', 1_007));

        $this->assertSame([1_000, 1_007], $this->recordedAts($t, 'M1'));
        $this->assertSame(1_007, $this->lastRecordedAt($t));
    }

    public function test_lc2_a_reading_at_or_before_closed_through_is_refused_and_nothing_changes(): void
    {
        $repo = $this->repo();
        $t = $this->organisation();
        $this->admit($repo, $t, 'M1', 1_000);
        DB::table('org_membership_ledger_clocks')->where('organisation_id', $t->value())->update(['closed_through' => InstantMicros::toTimestamp(\App\Contexts\Membership\Domain\Organisational\Instant::of(5_000))]);

        $this->assertSame(AppendOutcome::RecordTimeRefused, $this->endAt($repo, $t, 'M1', 4_000));
        $this->assertSame(AppendOutcome::RecordTimeRefused, $this->endAt($repo, $t, 'M1', 5_000));
        $this->assertSame([1_000], $this->recordedAts($t, 'M1'));
        $this->assertSame(1_000, $this->lastRecordedAt($t));
        $this->assertCount(1, $this->c1Rows($t));

        $this->assertSame(AppendOutcome::Appended, $this->endAt($repo, $t, 'M1', 5_001));
        $this->assertSame([1_000, 5_001], $this->recordedAts($t, 'M1'));
    }

    public function test_kc1_2_different_memberships_may_share_a_reading(): void
    {
        $repo = $this->repo();
        $t = $this->organisation();

        $this->assertSame(AppendOutcome::Appended, $this->admit($repo, $t, 'M1', 3_000));
        $this->assertSame(AppendOutcome::Appended, $this->admit($repo, $t, 'M2', 3_000));

        $this->assertSame([3_000], $this->recordedAts($t, 'M2'));
    }

    public function test_kc1_1_the_same_membership_may_not_repeat_a_reading(): void
    {
        $repo = $this->repo();
        $t = $this->organisation();
        $this->admit($repo, $t, 'M1', 3_000);

        $this->assertSame(AppendOutcome::RecordTimeRefused, $this->endAt($repo, $t, 'M1', 3_000));
        $this->assertSame([3_000], $this->recordedAts($t, 'M1'));
    }

    public function test_two_organisations_have_independent_clocks(): void
    {
        $repo = $this->repo();
        [$a, $b] = [$this->organisation(), $this->organisation()];
        $this->admit($repo, $a, 'M1', 9_000);

        $this->assertSame(AppendOutcome::Appended, $this->admit($repo, $b, 'M1', 1_000));
        $this->assertSame(9_000, $this->lastRecordedAt($a));
        $this->assertSame(1_000, $this->lastRecordedAt($b));
    }

    public function test_the_fact_the_clock_and_the_c1_row_roll_back_together(): void
    {
        $repo = $this->repo();
        $t = $this->organisation();
        $this->admit($repo, $t, 'M1', 1_000);
        DB::unprepared(<<<'SQL'
            CREATE FUNCTION pbdigit343_fail_outbox() RETURNS trigger LANGUAGE plpgsql AS $$
            BEGIN RAISE EXCEPTION 'forced outbox failure'; END $$;
            CREATE TRIGGER pbdigit343_fail_outbox BEFORE INSERT ON outbox_events FOR EACH ROW EXECUTE FUNCTION pbdigit343_fail_outbox();
            SQL);

        try {
            $this->endAt($repo, $t, 'M1', 2_000);
            $this->fail('the forced outbox failure must surface');
        } catch (QueryException $e) {
            $this->assertStringContainsString('forced outbox failure', $e->getMessage());
        } finally {
            DB::unprepared('DROP TRIGGER pbdigit343_fail_outbox ON outbox_events; DROP FUNCTION pbdigit343_fail_outbox();');
        }

        $this->assertSame([1_000], $this->recordedAts($t, 'M1'), 'no fact');
        $this->assertSame(1_000, $this->lastRecordedAt($t), 'clock unchanged');
        $this->assertCount(1, $this->c1Rows($t), 'no second event');
    }

    public function test_c1_is_written_on_the_repository_connection_not_the_default_one(): void
    {
        config(['database.connections.pbdigit343_probe' => config('database.connections.' . config('database.default'))]);
        $t = $this->organisation();
        DB::connection()->enableQueryLog();
        DB::connection('pbdigit343_probe')->enableQueryLog();

        $this->admit($this->repo('pbdigit343_probe'), $t, 'M1', 1_000);

        $outboxIn = static fn (array $log): int => count(array_filter($log, static fn (array $q): bool => str_contains($q['query'], 'outbox_events')));
        $this->assertSame(1, $outboxIn(DB::connection('pbdigit343_probe')->getQueryLog()));
        $this->assertSame(0, $outboxIn(DB::connection()->getQueryLog()));
        $this->assertCount(1, $this->c1Rows($t));
    }

    public function test_the_c1_row_matches_the_outbox_contract_for_a_non_uuid_member_id(): void
    {
        $t = $this->organisation();
        $this->admit($this->repo(), $t, 'M1', 1_760_000_000_000_123);

        $rows = $this->c1Rows($t);
        $this->assertCount(1, $rows);
        $row = $rows[0];
        $payload = json_decode($row->payload, true);

        $this->assertTrue(Str::isUuid($row->id));
        $this->assertTrue(Str::isUuid($row->event_id));
        $this->assertSame('Membership', $row->aggregate_type);
        $this->assertSame(MembershipCoverageResolvedOutbox::aggregateId($t->value(), 'M1'), $row->aggregate_id);
        $this->assertTrue(Str::isUuid($row->aggregate_id));
        $this->assertNotSame(MembershipCoverageResolvedOutbox::aggregateId((string) Str::uuid(), 'M1'), $row->aggregate_id);
        $this->assertSame('pending', $row->status);
        $this->assertSame(0, (int) $row->attempts);
        $this->assertNull($row->correlation_id);
        $this->assertNull($row->causation_id);
        $this->assertSame([
            'eventId' => $row->event_id,
            'organisationId' => $t->value(),
            'membershipId' => 'M1',
            'personRef' => 'person-M1',
            'seq' => 1,
            'coverage' => [['from' => '2024-10-04', 'to' => null]],
            'inForce' => [['from' => '2024-10-04', 'to' => null]],
            'suspended' => [],
            'recordedAt' => 1_760_000_000_000_123,
            'schemaVersion' => 2,
        ], $payload);
    }

    public function test_a_replayed_command_writes_one_fact_and_one_c1_row(): void
    {
        $repo = $this->repo();
        $t = $this->organisation();
        $this->admit($repo, $t, 'M1', 1_000);
        $m = $repo->load($t, MemberId::fromString('M1'));
        $end = $m->decideEnd(FactId::fromString('E-M1'), PeriodId::fromString('A-M1'), TimePoint::of(20_100), TimePoint::of(20_100), EndKind::fromString('resignation'), Provenance::decision('registrar'));
        $this->reading = 2_000;
        $this->assertSame(AppendOutcome::Appended, $repo->append($t, $m->id(), $end, IdempotencyKey::fromString('k-same'), 'fp-end')->outcome());

        $this->reading = 3_000;   // the same decision and key again: answered from the key, nothing written
        $this->assertSame(AppendOutcome::AlreadyApplied, $repo->append($t, $m->id(), $end, IdempotencyKey::fromString('k-same'), 'fp-end')->outcome());
        $this->assertSame([1_000, 2_000], $this->recordedAts($t, 'M1'));
        $this->assertCount(2, $this->c1Rows($t));
    }

    public function test_the_clock_row_is_locked_before_the_version_compare_and_set(): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
        $t = $this->organisation();
        $this->admit($this->repo(), $t, 'M1', 1_000);
        $default = config('database.default');
        config(['database.connections.pbdigit343_holder' => config("database.connections.$default"), 'database.connections.pbdigit343_probe' => config("database.connections.$default")]);
        $dir = sys_get_temp_dir() . '/pbdigit343-lock-' . Str::random(8);
        mkdir($dir);
        DB::disconnect();

        $pid = pcntl_fork();
        if ($pid === 0) {
            DB::purge();
            while (!file_exists("$dir/go")) {
                usleep(200);
            }
            try {
                $outcome = $this->endAt($this->repo(), $t, 'M1', 2_000)->name;
            } catch (\Throwable $e) {
                $outcome = 'error: ' . $e->getMessage();
            }
            file_put_contents("$dir/child", $outcome);
            DB::disconnect();
            posix_kill(posix_getpid(), SIGKILL);
        }

        $holder = DB::connection('pbdigit343_holder');
        $holder->beginTransaction();
        $holder->select('SELECT 1 FROM org_memberships WHERE organisation_id = ? AND id = ? FOR UPDATE', [$t->value(), 'M1']);
        touch("$dir/go");
        usleep(400_000);   // the child takes the clock row, then waits for the membership row

        $clockBusy = null;
        try {
            DB::connection('pbdigit343_probe')->transaction(static function () use ($t): void {
                DB::connection('pbdigit343_probe')->select('SELECT 1 FROM org_membership_ledger_clocks WHERE organisation_id = ? FOR UPDATE NOWAIT', [$t->value()]);
            });
            $clockBusy = false;
        } catch (QueryException $e) {
            $clockBusy = $e->getCode() === '55P03';
        }
        $holder->commit();
        pcntl_waitpid($pid, $status);
        DB::reconnect();
        $child = (string) @file_get_contents("$dir/child");
        array_map('unlink', glob("$dir/*") ?: []);
        rmdir($dir);

        $this->assertTrue($clockBusy, 'while the append waited for the membership version, it already held the clock row');
        $this->assertSame('Appended', $child);
    }
}
