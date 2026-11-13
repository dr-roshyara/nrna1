<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\BoundaryNotClosed;
use App\Contexts\ReferenceGeography\Application\IdentityNoticeConsumer;
use App\Contexts\ReferenceGeography\Application\PublicationStatus;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublicationLedger;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedAtlas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;

/**
 * PBDIGIT-346 G-4g, the concurrency matrix derived from v3.11 §6 (protocol tests, PO-1, PO-4, PO-6), §8 ("Checks":
 * an act stands once given; publication re-reads the roster under the lock) and the G-4 interpretation (B-1 to B-9).
 * Every race is DETERMINISTIC: one operation holds the one lock (whileHeld) while the other starts, in BOTH orderings,
 * and the persisted state is checked, not only the returned status.
 */
#[Group('refgeo-race')]
final class PostgresConcurrencyMatrixTest extends RefgeoPostgresTestCase
{
    use PublishingFixtures;

    /**
     * Roster races R1–R7: [actor, setup on the roster (committed first), the racing write, outcome if the write commits
     * first, outcome if the publication commits first]. An outcome is 'published' or a refusal.
     *
     * @return array<string, array{string}>
     */
    public static function rosterRaces(): array
    {
        return array_map(static fn (string $c): array => [$c], array_combine(
            ['R1 appoint the actor', 'R2 end the actor', 'R3 end the approver', 'R4 appoint a matter reviewer as actor', 'R5 approver declares an interest', 'R6 Identity merge of approver and preparer', 'R7 reversal of that merge'],
            ['R1', 'R2', 'R3', 'R4', 'R5', 'R6', 'R7'],
        ));
    }

    /** @return array{string, \Closure(StewardRoster): void, \Closure(StewardRoster): mixed, string|GovernanceRefusal, string|GovernanceRefusal} */
    private static function race(string $case, string $t, RevisionId $id): array
    {
        $p = self::p("p-$t");
        $x = self::p("x-$t");
        $none = static function (StewardRoster $r): void {
        };

        return match ($case) {
            'R1' => ["q-$t", $none, static fn (StewardRoster $r) => $r->appointSteward(self::p("q-$t"), self::p('owner')), 'published', GovernanceRefusal::NotAppointed],
            'R2' => ["p-$t", $none, static fn (StewardRoster $r) => $r->endSteward($p, self::p('owner')), GovernanceRefusal::NotAppointed, 'published'],
            'R3' => ["p-$t", $none, static fn (StewardRoster $r) => $r->endSteward($x, self::p('owner')), 'published', 'published'],   // §8: the act stands
            'R4' => ["r-$t", $none, static fn (StewardRoster $r) => $r->appointMatterReviewer(self::p("r-$t"), $id, self::p('owner')), 'published', GovernanceRefusal::NotAppointed],
            'R5' => ["p-$t", $none, static fn (StewardRoster $r) => $r->declareConflict($x, $id, true), GovernanceRefusal::MateriallyConflicted, 'published'],
            'R6' => ["p-$t", $none, static fn (StewardRoster $r) => $r->recordMerge("m-$t", [$p, $x]), GovernanceRefusal::ApproverNotIndependent, 'published'],
            'R7' => ["p-$t", static fn (StewardRoster $r) => $r->recordMerge("m-$t", [$p, $x]), static fn (StewardRoster $r) => $r->recordReversal("m-$t"), 'published', GovernanceRefusal::ApproverNotIndependent],
        };
    }

    private static function outcomeOf(RevisionId $id, ?object $out = null): string|GovernanceRefusal
    {
        if ($out !== null && $out->status === PublicationStatus::Refused) {
            return $out->refusals[0];
        }

        return DB::table('refgeo_publications')->where('revision_id', $id->value())->exists() ? 'published' : 'not published';
    }

    #[DataProvider('rosterRaces')]
    public function test_a_roster_write_holding_the_lock_is_seen_by_the_publication_that_waits_for_it(string $case): void
    {
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t", judgment: true);
        [$actor, $setup, $write, $writeFirst] = self::race($case, $t, $id);
        self::store()->writeUnderLock($setup);
        $before = self::store()->current()->version();

        [$out, $waited] = self::whileHeld(self::holdingRosterWrite($write), static fn () => self::publisher($actor)->publish($id));

        $this->assertGreaterThan(400, $waited, "{$case}: the publication waited for the roster write");
        $this->assertSame($writeFirst, self::outcomeOf($id, $out), "{$case}: judged WITH the write");
        $pub = DB::selectOne('SELECT roster_version FROM refgeo_publications WHERE revision_id = ?', [$id->value()]);
        if ($pub !== null) {
            $this->assertGreaterThan($before, (int) $pub->roster_version, "{$case}: the publication recorded the roster version including the write");
        }
    }

    #[DataProvider('rosterRaces')]
    public function test_a_publication_holding_the_lock_comes_before_the_roster_write_that_waits_for_it(string $case): void
    {
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t", judgment: true);
        [$actor, $setup, $write, , $publishFirst] = self::race($case, $t, $id);
        self::store()->writeUnderLock($setup);

        [$version, $waited] = self::whileHeld(self::holdingPublication($id, $actor), static fn () => self::store()->writeUnderLock(static function (StewardRoster $r) use ($write): int {
            $write($r);

            return $r->version();
        }));

        $this->assertGreaterThan(400, $waited, "{$case}: the roster write waited for the publication");
        $outcome = self::outcomeOf($id);
        $this->assertSame($publishFirst === 'published' ? 'published' : 'not published', $outcome, "{$case}: judged WITHOUT the write");
        $pub = DB::selectOne('SELECT roster_version, recorded_at FROM refgeo_publications WHERE revision_id = ?', [$id->value()]);
        if ($pub !== null) {
            $this->assertLessThan($version, (int) $pub->roster_version, "{$case}: recorded before the write");
            $entry = DB::selectOne('SELECT recorded_at FROM refgeo_roster_entries WHERE roster_version = ?', [$version]);
            $this->assertTrue((bool) DB::selectOne('SELECT ?::timestamptz > ?::timestamptz AS after', [$entry->recorded_at, $pub->recorded_at])->after, "{$case}: the write is recorded after the publication");
        }
    }

    /** R8: the same Identity notice delivered three times at once is recorded once. */
    public function test_a_notice_delivered_concurrently_is_recorded_exactly_once(): void
    {
        $t = Str::random(8);
        $dir = sys_get_temp_dir() . '/pbdigit346-g4g-notice-' . $t;
        mkdir($dir);
        DB::disconnect();
        $pids = [];
        for ($w = 0; $w < 3; $w++) {
            $pid = pcntl_fork();
            if ($pid === 0) {
                DB::purge();
                while (!file_exists("$dir/go")) {
                    usleep(100);
                }
                try {
                    $v = (new IdentityNoticeConsumer(self::store()))->merged("m-$t", [self::p("a-$t"), self::p("b-$t")]);
                    file_put_contents("$dir/$w", $v === null ? 'replay' : 'recorded');
                } catch (\Throwable $e) {
                    file_put_contents("$dir/$w", 'error: ' . $e->getMessage());
                } finally {
                    DB::disconnect();
                    posix_kill(posix_getpid(), SIGKILL);
                }
            }
            $pids[] = $pid;
        }
        usleep(50_000);
        touch("$dir/go");
        foreach ($pids as $pid) {
            pcntl_waitpid($pid, $status);
        }
        DB::reconnect();

        $results = array_map(static fn (int $w): string => (string) @file_get_contents("$dir/$w"), range(0, 2));
        sort($results);
        $this->assertSame(['recorded', 'replay', 'replay'], $results);
        $this->assertSame(1, DB::table('refgeo_roster_entries')->where('merge_id', "m-$t")->count());
    }

    /** @return array<string, array{string}> */
    public static function lifecycleRaces(): array
    {
        return ['S1 a re-prepare in progress' => ['reprepare'], 'S2 a withdrawal in progress' => ['withdraw']];
    }

    /** S1, S2: a lifecycle save holding the revision row: publication waits, then sees the new state and is refused. */
    #[DataProvider('lifecycleRaces')]
    public function test_a_lifecycle_save_in_progress_is_seen_by_the_publication_that_waits_for_it(string $act): void
    {
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t");

        [$out, $waited] = self::whileHeld(
            static function (string $dir) use ($id, $act, $t): void {
                DB::beginTransaction();
                $stored = self::repo()->get($id);
                if ($act === 'reprepare') {
                    $stored->revision->markStale();
                    $stored->revision->reprepare(self::p("p-$t"));
                } else {
                    $stored->revision->withdraw(self::p("p-$t"));
                }
                self::repo()->save($stored->revision, $stored->rowVersion);   // holds the revision row until commit
                touch("$dir/holding");
                usleep(800_000);
                DB::commit();
            },
            static fn () => self::publisher("p-$t")->publish($id),
        );

        $this->assertGreaterThan(400, $waited, "{$act}: the publication waited for the save");
        $this->assertSame([GovernanceRefusal::NotSubmitted], $out->refusals, "{$act}: judged on the saved state");
        $this->assertSame(0, DB::table('refgeo_publications')->where('revision_id', $id->value())->count());
        $this->assertSame($act === 'reprepare' ? 'draft' : 'withdrawn', DB::selectOne('SELECT state FROM refgeo_revisions WHERE id = ?', [$id->value()])->state);
    }

    /** P1 (PO-4): two publications at once: the second waits; positions are consecutive; p+1 is never visible without p. */
    public function test_concurrent_publications_get_consecutive_positions_and_none_is_visible_early(): void
    {
        $t = Str::random(8);
        $first = self::revision("p-$t", "x-$t");
        $second = self::revision("q-$t", "y-$t");
        $before = self::lastPosition();

        [[$observed, $out], $waited] = self::whileHeld(self::holdingPublication($first, "p-$t"), static fn (): array => [self::lastPosition(), self::publisher("q-$t")->publish($second)]);

        $this->assertSame($before, $observed, 'while the first was uncommitted, no new position was visible');
        $this->assertGreaterThan(400, $waited, 'the second publication waited');
        $this->assertSame($before + 2, $out->position);
        $this->assertSame($before + 1, (int) DB::selectOne('SELECT position FROM refgeo_publications WHERE revision_id = ?', [$first->value()])->position);
    }

    /** C1 (PO-1): a close never confirms past a publication that read its clock and has not committed. */
    public function test_a_close_never_confirms_past_an_uncommitted_publication(): void
    {
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t");
        $ledger = new PostgresPublicationLedger(DB::connection());

        [[$tau, $closed], ] = self::whileHeld(self::holdingPublication($id, "p-$t"), static function () use ($ledger): array {
            $tau = DB::selectOne('SELECT clock_timestamp()::text AS t')->t;
            try {
                return [$tau, $ledger->closeThrough($tau)];
            } catch (BoundaryNotClosed) {
                return [$tau, null];
            }
        });

        $this->assertNull($closed, 'refused while the publication was in flight (nothing confirmed)');
        $w = $ledger->closeThrough($tau);   // after its commit
        $pub = DB::selectOne('SELECT recorded_at <= ?::timestamptz AS within FROM refgeo_publications WHERE revision_id = ?', [$w, $id->value()]);
        $this->assertNotNull($pub, 'the held publication committed');
        $this->assertTrue((bool) $pub->within, 'once closed, the publication read before τ is inside the boundary and committed');
    }

    /** C2 (PO-6, v3.11 "equal timestamps"): an equal reading is allowed, ordered by position, and stored exactly as read. */
    public function test_an_equal_clock_reading_is_allowed_ordered_by_position_and_never_adjusted(): void
    {
        $t = Str::random(8);
        $first = self::revision("p-$t", "x-$t");
        $second = self::revision("q-$t", "y-$t");
        self::publisher("p-$t")->publish($first);

        $out = self::publisher("q-$t", new PostgresPublicationLedger(DB::connection(), clockSql: '(SELECT last_recorded_at FROM refgeo_sequence WHERE id = 1)'))->publish($second);

        $this->assertSame(PublicationStatus::Published, $out->status);
        $rows = DB::select('SELECT position, recorded_at::text AS r FROM refgeo_publications WHERE revision_id IN (?, ?) ORDER BY position', [$first->value(), $second->value()]);
        $this->assertSame($rows[0]->r, $rows[1]->r, 'the same recorded_at, exactly: no microsecond added');
        $this->assertSame((int) $rows[0]->position + 1, (int) $rows[1]->position);
    }

    /** C3 (v3.11 "query by position above closedThroughPosition: refused"). */
    public function test_a_read_by_position_beyond_the_committed_one_is_refused(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        PostgresPublishedAtlas::at(DB::connection(), self::lastPosition() + 1);
    }

    /**
     * X: a mixed stress run (publishers, a roster writer, a lifecycle saver) in separate processes. Supplementary to the
     * deterministic proofs above: no deadlock or error, no gap in positions or roster versions, and every publication
     * valid at the roster version it recorded with no roster entry crossing it.
     */
    public function test_a_mixed_concurrent_load_keeps_every_invariant(): void
    {
        $t = Str::random(8);
        $toPublish = [];
        for ($i = 0; $i < 6; $i++) {
            $toPublish[] = [self::revision("p$i-$t", "x$i-$t"), "p$i-$t"];
        }
        $toChange = [self::revision("s0-$t", "y0-$t"), self::revision("s1-$t", "y1-$t")];
        $startPosition = self::lastPosition();
        $startRoster = self::store()->current()->version();
        $dir = sys_get_temp_dir() . '/pbdigit346-g4g-stress-' . $t;
        mkdir($dir);
        DB::disconnect();
        $pids = [];
        foreach (['publisher-a', 'publisher-b', 'roster', 'saver'] as $role) {
            $pid = pcntl_fork();
            if ($pid === 0) {
                DB::purge();
                while (!file_exists("$dir/go")) {
                    usleep(100);
                }
                $lines = [];
                try {
                    if (str_starts_with($role, 'publisher')) {
                        foreach (array_slice($toPublish, $role === 'publisher-a' ? 0 : 3, 3) as [$id, $actor]) {
                            $lines[] = self::publisher($actor)->publish($id)->status->value;
                            usleep(random_int(0, 3000));
                        }
                    } elseif ($role === 'roster') {
                        for ($i = 0; $i < 8; $i++) {
                            $lines[] = self::store()->writeUnderLock(static fn (StewardRoster $r): int => $r->appointSteward(self::p("extra$i-$t"), self::p('owner')));
                            usleep(random_int(0, 3000));
                        }
                    } else {
                        foreach ($toChange as $j => $id) {
                            $stored = self::repo()->get($id);
                            $stored->revision->withdraw(self::p("s$j-$t"));
                            $lines[] = self::repo()->save($stored->revision, $stored->rowVersion);
                        }
                    }
                } catch (\Throwable $e) {
                    $lines[] = 'error: ' . $e::class . ': ' . $e->getMessage();
                }
                file_put_contents("$dir/$role.json", json_encode($lines));
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

        foreach (['publisher-a', 'publisher-b', 'roster', 'saver'] as $role) {
            foreach (json_decode((string) @file_get_contents("$dir/$role.json"), true) ?? ['error: no result'] as $line) {
                $this->assertFalse(is_string($line) && str_starts_with($line, 'error'), "{$role}: {$line}");
            }
        }
        $positions = array_map(static fn (object $r): int => (int) $r->position, DB::select('SELECT position FROM refgeo_publications WHERE position > ? ORDER BY position', [$startPosition]));
        $this->assertSame(range($startPosition + 1, self::lastPosition()), $positions, 'no gap in positions');
        $versions = array_map(static fn (object $r): int => (int) $r->roster_version, DB::select('SELECT roster_version FROM refgeo_roster_entries WHERE roster_version > ? ORDER BY roster_version', [$startRoster]));
        $this->assertSame(range($startRoster + 1, self::store()->current()->version()), $versions, 'no gap in roster versions');
        $roster = self::store()->current();
        foreach ($toPublish as [$id]) {
            $pub = DB::selectOne('SELECT roster_version, recorded_at FROM refgeo_publications WHERE revision_id = ?', [$id->value()]);
            $this->assertNotNull($pub, 'every publisher succeeded');
            $this->assertTrue((new PublicationGovernance())->mayPublish(self::repo()->get($id)->revision, $roster, (int) $pub->roster_version)->allowed);
            $crossing = (int) DB::selectOne('SELECT count(*) AS n FROM refgeo_roster_entries WHERE (roster_version > ? AND recorded_at < ?::timestamptz) OR (roster_version <= ? AND recorded_at > ?::timestamptz)',
                [$pub->roster_version, $pub->recorded_at, $pub->roster_version, $pub->recorded_at])->n;
            $this->assertSame(0, $crossing, 'no roster entry crosses a publication');
        }
    }
}
