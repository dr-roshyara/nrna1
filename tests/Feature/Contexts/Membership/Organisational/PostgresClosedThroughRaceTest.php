<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\InstantMicros;
use App\Contexts\Membership\Infrastructure\Organisational\LedgerClockCloser;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresLedgerClockReader;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * PBDIGIT-343 task 6 (plan test 4 = LC-3 = K-2 A1 … A3; test 11 at the closer level; D-209): with the real database
 * clock, writers and readers interleaved in separate processes. Property: a reported closedThrough is never
 * contradicted — every fact with recordedAt ≤ a reported boundary was already committed and visible when the boundary
 * was reported (A3: read in a new statement after the close), and none appears later.
 */
#[Group('membership-race')]
final class PostgresClosedThroughRaceTest extends MembershipPostgresTestCase
{
    private const WRITERS = 3;
    private const ADMITS_PER_WRITER = 6;
    private const READERS = 2;
    /** closes per reader AFTER every writer has finished; before that, readers close continuously */
    private const CLOSES_PER_READER = 5;
    /** safety bound on a reader's loop */
    private const MAX_CLOSES_PER_READER = 2_000;

    protected function setUp(): void
    {
        parent::setUp();
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
    }

    private function organisation(): string
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $id, 'name' => 'lc-race', 'slug' => 'lc-race-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);

        return $id;
    }

    private static function repository(): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), new PostgresLedgerClockReader());
    }

    private static function closer(): LedgerClockCloser
    {
        return new LedgerClockCloser(DB::connection(), new PostgresLedgerClockReader());
    }

    /** @return list<string> fact ids of $org with recordedAt ≤ $boundaryUs, in a new statement */
    private static function visibleAtOrBefore(string $org, int $boundaryUs): array
    {
        $ids = DB::table('org_membership_facts')->where('organisation_id', $org)
            ->whereRaw(InstantMicros::selectExpression('recorded_at') . ' <= ?', [$boundaryUs])
            ->orderBy('fact_id')->pluck('fact_id')->all();

        return array_values(array_map('strval', $ids));
    }

    public function test_a_reported_closed_through_is_never_contradicted_by_a_later_commit(): void
    {
        $org = $this->organisation();
        $dir = sys_get_temp_dir() . '/pbdigit343-close-race-' . Str::random(8);
        mkdir($dir);
        DB::disconnect();

        $pids = [];
        for ($p = 0; $p < self::WRITERS + self::READERS; $p++) {
            $pid = pcntl_fork();
            if ($pid === 0) {
                DB::purge();
                while (!file_exists("$dir/go")) {
                    usleep(100);
                }
                $lines = [];
                try {
                    if ($p < self::WRITERS) {
                        $handler = new AdmitMemberHandler(self::repository(), new FakeAuthority());
                        for ($i = 0; $i < self::ADMITS_PER_WRITER; $i++) {
                            $o = $handler->handle(new AdmitMember($org, "P-$p-$i", "M-$p-$i", "A-$p-$i", 20_000, 20_000, null, true, 'registrar', 'decl-fixture', null, "k-$p-$i"));
                            $lines[] = ['admit', $o->isAppended() ? 'appended' : ($o->domainRefusal() ?? $o->applicationRefusal())->value];
                        }
                        touch("$dir/writer-$p.done");
                    } else {
                        // Close until every writer has finished, then a few more times, so boundaries are reported
                        // DURING the writes, not only before or after them (the interleaving under test).
                        $writersDone = static fn (): bool => count(glob("$dir/writer-*.done") ?: []) === self::WRITERS;
                        $after = 0;
                        for ($i = 0; $i < self::MAX_CLOSES_PER_READER && $after < self::CLOSES_PER_READER; $i++) {
                            $b = self::closer()->closeOrRead(TenantId::fromString($org));
                            if ($b !== null) {
                                $lines[] = ['close', $b->ordinal(), self::visibleAtOrBefore($org, $b->ordinal())];
                            }
                            if ($writersDone()) {
                                ++$after;
                            }
                            usleep(random_int(0, 3000));
                        }
                    }
                } catch (\Throwable $e) {
                    $lines[] = ['error', $e::class . ': ' . $e->getMessage()];
                }
                file_put_contents("$dir/$p.json", json_encode($lines));
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

        $closes = 0;
        $boundaries = [];
        for ($p = 0; $p < self::WRITERS + self::READERS; $p++) {
            $lines = json_decode((string) @file_get_contents("$dir/$p.json"), true) ?? [['error', 'no result']];
            $previous = null;
            foreach ($lines as $line) {
                $this->assertNotSame('error', $line[0], "process $p: " . ($line[1] ?? ''));
                if ($line[0] === 'admit') {
                    $this->assertSame('appended', $line[1], "process $p admit");
                }
                if ($line[0] === 'close') {
                    ++$closes;
                    [, $b, $visible] = $line;
                    $this->assertSame(self::visibleAtOrBefore($org, $b), $visible, "boundary $b contradicted: a fact at or before it committed later");
                    $this->assertTrue($previous === null || $b >= $previous, 'a reader never sees the boundary move back');
                    $previous = $b;
                    $boundaries[] = $b;
                }
            }
        }
        array_map('unlink', glob("$dir/*") ?: []);
        rmdir($dir);

        $total = DB::table('org_membership_facts')->where('organisation_id', $org)->count();
        $this->assertSame(self::WRITERS * self::ADMITS_PER_WRITER, $total);
        $this->assertGreaterThan(0, $closes, 'the readers reported boundaries');
        $this->assertGreaterThan(1, count(array_unique($boundaries)), 'the boundary advanced during the run');
        // Non-vacuity: at least one boundary was reported while writes were still in flight (facts committed after it).
        $midRun = array_filter($boundaries, static fn (int $b): bool => count(self::visibleAtOrBefore($org, $b)) < $total);
        $this->assertNotEmpty($midRun, 'the run interleaved: some boundary was reported before the last write committed');
    }

    public function test_a_reader_between_a_writers_lock_and_its_commit_gets_a_boundary_the_writer_then_records_after(): void
    {
        $org = $this->organisation();
        $t = TenantId::fromString($org);
        (new AdmitMemberHandler(self::repository(), new FakeAuthority()))->handle(new AdmitMember($org, 'P1', 'M1', 'A1', 20_000, 20_000, null, true, 'registrar', 'decl-fixture', null, 'k-a'));
        $before = self::closer()->closeOrRead($t);
        $this->assertNotNull($before);
        $default = config('database.default');
        config(['database.connections.pbdigit343_holder' => config("database.connections.$default")]);
        $dir = sys_get_temp_dir() . '/pbdigit343-between-' . Str::random(8);
        mkdir($dir);
        DB::disconnect();

        $pid = pcntl_fork();
        if ($pid === 0) {
            DB::purge();
            while (!file_exists("$dir/go")) {
                usleep(200);
            }
            try {
                $repo = self::repository();
                $m = $repo->load($t, MemberId::fromString('M1'));
                $end = $m->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(20_100), TimePoint::of(20_100), EndKind::fromString('resignation'), Provenance::decision('registrar'));
                $result = $repo->append($t, $m->id(), $end, IdempotencyKey::fromString('k-e'), 'fp-e');
                $out = [$result->outcome()->value, $result->appended()?->recordedAt()->ordinal()];
            } catch (\Throwable $e) {
                $out = ['error', $e->getMessage()];
            }
            file_put_contents("$dir/child", json_encode($out));
            DB::disconnect();
            posix_kill(posix_getpid(), SIGKILL);
        }

        $holder = DB::connection('pbdigit343_holder');
        $holder->beginTransaction();
        $holder->select('SELECT 1 FROM org_memberships WHERE organisation_id = ? AND id = ? FOR UPDATE', [$org, 'M1']);
        touch("$dir/go");
        usleep(400_000);   // the writer holds the clock row and waits for the membership row
        DB::purge();
        $during = self::closer()->closeOrRead($t);
        $holder->commit();
        pcntl_waitpid($pid, $status);
        DB::reconnect();
        [$outcome, $recordedAt] = json_decode((string) @file_get_contents("$dir/child"), true) ?? ['error', null];
        array_map('unlink', glob("$dir/*") ?: []);
        rmdir($dir);
        $after = self::closer()->closeOrRead($t);

        $this->assertSame($before->ordinal(), $during->ordinal(), 'busy: the committed boundary, not advanced past the writer');
        $this->assertSame('appended', $outcome);
        $this->assertGreaterThan($during->ordinal(), $recordedAt, 'the writer records after the boundary reported while it held the lock');
        $this->assertGreaterThanOrEqual($recordedAt, $after->ordinal());
    }
}
