<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\IdentityNoticeConsumer;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RosterRefused;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresStewardRosterStore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;

/**
 * PBDIGIT-346 G-4c on PostgreSQL (v3.11 §8 "Roster and publication ordering"; G-4 interpretation §2, B-6):
 * - every roster write runs under the ONE publication lock (refgeo_sequence FOR UPDATE, READ COMMITTED as the first
 *   statement), on the roster as last committed, and appends its entries at the next roster versions;
 * - a refused domain write, or any failure, writes nothing;
 * - a reload rebuilds exactly the roster that was written (StewardRoster::fromEntries);
 * - concurrent writers in separate processes are serialised: no lost write, no gap, no duplicate version;
 * - an Identity notice is applied once.
 * The harness keeps data between tests: persons are unique per test; assertions are relative to the state before.
 */
#[Group('refgeo-race')]
final class PostgresStewardRosterStoreTest extends MembershipPostgresTestCase
{
    private static function p(string $label): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $label));
    }

    private static function store(): PostgresStewardRosterStore
    {
        return new PostgresStewardRosterStore(DB::connection());
    }

    private static function lastRosterVersion(): int
    {
        return (int) DB::selectOne('SELECT last_roster_version FROM refgeo_sequence')->last_roster_version;
    }

    public function test_a_write_is_appended_at_the_next_versions_and_reloads_exactly(): void
    {
        $t = Str::random(8);
        $before = self::lastRosterVersion();
        $matter = RevisionId::fromString((string) Str::uuid());

        $last = self::store()->writeUnderLock(static function (StewardRoster $r) use ($t, $matter): int {
            $r->appointSteward(self::p("a-$t"), self::p("owner-$t"));
            $r->recordMerge("m-$t", [self::p("a-$t"), self::p("x-$t")]);

            return $r->declareConflict(self::p("a-$t"), $matter, true);
        });

        $this->assertSame($before + 3, $last);
        $this->assertSame($last, self::lastRosterVersion());
        $reloaded = self::store()->current();
        $this->assertSame($last, $reloaded->version());
        $this->assertTrue($reloaded->isStewardAt(self::p("a-$t"), $last));
        $this->assertTrue($reloaded->knownSameAt(self::p("a-$t"), self::p("x-$t"), $last));
        $this->assertTrue($reloaded->declaredInterestAt(self::p("a-$t"), $matter, $last));
        $this->assertTrue($reloaded->entries()[$before]->by->equals(self::p("owner-$t")));
    }

    public function test_a_refused_write_writes_nothing(): void
    {
        $t = Str::random(8);
        $before = self::lastRosterVersion();

        try {
            self::store()->writeUnderLock(static function (StewardRoster $r) use ($t): void {
                $r->appointSteward(self::p("a-$t"), self::p("owner-$t"));
                $r->endSteward(self::p("never-$t"), self::p("owner-$t"));   // refused by the domain
            });
            $this->fail('must be refused');
        } catch (RosterRefused) {
            $this->addToAssertionCount(1);
        }
        $this->assertSame($before, self::lastRosterVersion(), 'not even the appointment before the refusal');
        $this->assertFalse(self::store()->current()->isStewardAt(self::p("a-$t"), $before));
    }

    public function test_an_identity_notice_is_applied_once(): void
    {
        $t = Str::random(8);
        $consumer = new IdentityNoticeConsumer(self::store());

        $v = $consumer->merged("m-$t", [self::p("a-$t"), self::p("b-$t")]);
        $this->assertSame(self::lastRosterVersion(), $v);
        $this->assertNull($consumer->merged("m-$t", [self::p("a-$t"), self::p("b-$t")]));
        $this->assertSame($v, self::lastRosterVersion(), 'a replay writes nothing');
    }

    public function test_a_write_must_start_its_own_transaction(): void
    {
        DB::beginTransaction();
        try {
            self::store()->writeUnderLock(static fn (StewardRoster $r): int => $r->version());
            $this->fail('must be refused: the lock and READ COMMITTED need their own transaction');
        } catch (\LogicException) {
            $this->addToAssertionCount(1);
        } finally {
            DB::rollBack();
        }
    }

    public function test_concurrent_writers_in_separate_processes_are_serialised_without_a_lost_write_or_a_gap(): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
        $writers = 4;
        $perWriter = 5;
        $t = Str::random(8);
        $before = self::lastRosterVersion();
        $dir = sys_get_temp_dir() . '/pbdigit346-roster-race-' . $t;
        mkdir($dir);
        DB::disconnect();
        $pids = [];
        for ($w = 0; $w < $writers; $w++) {
            $pid = pcntl_fork();
            if ($pid === 0) {
                DB::purge();
                while (!file_exists("$dir/go")) {
                    usleep(100);
                }
                $lines = [];
                try {
                    for ($i = 0; $i < $perWriter; $i++) {
                        $lines[] = self::store()->writeUnderLock(static fn (StewardRoster $r): int => $r->appointSteward(self::p("w$w-$i-$t"), self::p("owner-$t")));
                        usleep(random_int(0, 2000));
                    }
                } catch (\Throwable $e) {
                    $lines[] = 'error: ' . $e::class . ': ' . $e->getMessage();
                }
                file_put_contents("$dir/$w.json", json_encode($lines));
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

        $versions = [];
        for ($w = 0; $w < $writers; $w++) {
            foreach (json_decode((string) @file_get_contents("$dir/$w.json"), true) ?? ['error: no result'] as $line) {
                $this->assertIsInt($line, (string) $line);
                $versions[] = $line;
            }
        }
        sort($versions);
        $this->assertSame(range($before + 1, $before + $writers * $perWriter), $versions, 'every write got its own version, without a gap');
        $roster = self::store()->current();   // replays the whole history: refused if anything were inconsistent
        for ($w = 0; $w < $writers; $w++) {
            for ($i = 0; $i < $perWriter; $i++) {
                $this->assertTrue($roster->isStewardAt(self::p("w$w-$i-$t"), $roster->version()), "no lost write: w$w-$i");
            }
        }
    }
}
