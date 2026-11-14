<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\IdentityNoticeConsumer;
use App\Contexts\ReferenceGeography\Domain\Approval;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublicationLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;

/**
 * PBDIGIT-346 G-6c-3 (design revision 3 §5, accepted 2026-11-14; Principal instruction 2026-11-14): an Identity merge or
 * reversal is consumed together with every governance notice it causes, under the ONE publication lock, in one
 * transaction, or not at all. The roster entry (unique per merge id and kind) is the consumer checkpoint.
 * Tests A1, A2, A3/A4, A5, A6, A7 (index), A9 (concurrency), A10 (rollback), and a full-scan oracle on the cluster.
 * The harness keeps data between tests: people are fresh; positions are compared with the state before.
 */
#[Group('refgeo-race')]
final class IdentityNoticeImpactTest extends RefgeoPostgresTestCase
{
    use PublishingFixtures;

    private static function consumer(?PostgresPublicationLedger $ledger = null): IdentityNoticeConsumer
    {
        return new IdentityNoticeConsumer($ledger ?? new PostgresPublicationLedger(DB::connection()), new PublicationGovernance());
    }

    /** Publishes a fresh revision prepared and assessed by $assessor and approved by $approver; returns its position. */
    private static function published(string $assessor, string $approver): int
    {
        $out = self::publisher($assessor)->publish(self::revision($assessor, $approver));
        if ($out->position === null) {
            throw new \RuntimeException('fixture: not published: ' . implode(',', array_map(static fn ($r) => $r->value, $out->refusals)));
        }

        return $out->position;
    }

    /** @return list<object> the notice rows after $after, in position order */
    private static function noticesAfter(int $after): array
    {
        return DB::select("SELECT position, notice_kind, concerns_position, merge_basis::text AS merge_basis, ended_by, roster_version
            FROM refgeo_publications WHERE kind = 'notice' AND position > ? ORDER BY position", [$after]);
    }

    private static function rosterVersion(): int
    {
        return (int) DB::selectOne('SELECT last_roster_version AS v FROM refgeo_sequence')->v;
    }

    public function test_a1_a_merge_after_publication_writes_its_finding_with_the_roster_entry_and_the_reversal_closes_it(): void
    {
        $t = Str::random(8);
        $pub = self::published("p-$t", "x-$t");
        $before = self::lastPosition();

        $w = self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);

        $this->assertSame(self::rosterVersion(), $w);
        $found = self::noticesAfter($before);
        $this->assertCount(1, $found);
        $this->assertSame([$before + 1, 'finding', $pub, ["m-$t"], $w], [(int) $found[0]->position, $found[0]->notice_kind, (int) $found[0]->concerns_position, json_decode($found[0]->merge_basis, true), (int) $found[0]->roster_version], 'gap-free, at the merge\'s roster version');

        $w2 = self::consumer()->reversed("m-$t");
        $closed = self::noticesAfter($before + 1);
        $this->assertCount(1, $closed, 'the reversal closes the finding');
        $this->assertSame([$before + 2, 'closure', $before + 1, "reversal:m-$t", $w2], [(int) $closed[0]->position, $closed[0]->notice_kind, (int) $closed[0]->concerns_position, $closed[0]->ended_by, (int) $closed[0]->roster_version]);
    }

    public function test_a2_a_chain_reversal_in_the_middle_closes_the_finding_of_a_publication_on_its_ends(): void
    {
        $t = Str::random(8);
        $pub = self::published("d-$t", "a-$t");   // approver A, excluded D
        $before = self::lastPosition();
        self::consumer()->merged("ab-$t", [self::p("a-$t"), self::p("b-$t")]);
        self::consumer()->merged("bc-$t", [self::p("b-$t"), self::p("c-$t")]);
        $this->assertSame([], self::noticesAfter($before), 'A and D are not yet joined');
        self::consumer()->merged("cd-$t", [self::p("c-$t"), self::p("d-$t")]);
        $finding = self::noticesAfter($before);
        $this->assertCount(1, $finding, 'joined through A-B-C-D: one finding');
        $this->assertSame(['finding', $pub, ["ab-$t", "bc-$t", "cd-$t"]], [$finding[0]->notice_kind, (int) $finding[0]->concerns_position, json_decode($finding[0]->merge_basis, true)]);

        self::consumer()->reversed("bc-$t");

        $closed = self::noticesAfter((int) $finding[0]->position);
        $this->assertCount(1, $closed, 'the middle reversal closes it');
        $this->assertSame(['closure', (int) $finding[0]->position], [$closed[0]->notice_kind, (int) $closed[0]->concerns_position], 'A is reached through B, a person the reversed merge named');
    }

    public function test_a3_a4_a_reversal_of_one_path_keeps_the_finding_while_another_path_remains(): void
    {
        $t = Str::random(8);
        self::published("d-$t", "a-$t");
        foreach (['ab' => ['a', 'b'], 'bd' => ['b', 'd'], 'ac' => ['a', 'c'], 'cd' => ['c', 'd']] as $m => [$x, $y]) {
            self::consumer()->merged("$m-$t", [self::p("$x-$t"), self::p("$y-$t")]);
        }
        $afterMerges = self::lastPosition();

        self::consumer()->reversed("bd-$t");
        $this->assertSame([], self::noticesAfter($afterMerges), 'still A~D through A-C-D: no closure');

        self::consumer()->reversed("cd-$t");
        $this->assertSame(['closure'], array_column(self::noticesAfter($afterMerges), 'notice_kind'));
    }

    public function test_a5_redelivery_writes_nothing_and_a_reversal_before_its_merge_means_the_merge_never_counts(): void
    {
        $t = Str::random(8);
        self::published("p-$t", "x-$t");
        self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
        [$position, $roster] = [self::lastPosition(), self::rosterVersion()];

        $this->assertNull(self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]), 'already consumed: the checkpoint');
        $this->assertSame([$position, $roster], [self::lastPosition(), self::rosterVersion()], 'redelivery wrote nothing');

        $u = Str::random(8);
        self::published("p-$u", "x-$u");
        $before = self::lastPosition();
        self::consumer()->reversed("n-$u");
        self::consumer()->merged("n-$u", [self::p("x-$u"), self::p("p-$u")]);
        $this->assertSame($before, self::lastPosition(), 'the merge was reversed before it arrived: no finding');
    }

    public function test_a6_a7_the_index_holds_every_approver_and_excluded_person_and_the_counted_approver_decides(): void
    {
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t");
        self::appoint("y-$t");
        self::declare("y-$t", $id);
        $stored = self::repo()->get($id);
        $stored->revision->approve(new Approval($stored->revision->currentVersion()->number, self::store()->current()->version(), self::p("y-$t")));
        self::repo()->save($stored->revision, $stored->rowVersion);
        $pub = self::publisher("p-$t")->publish($id)->position;

        $indexed = array_map(static fn ($r) => [trim($r->person), $r->role], DB::select('SELECT person, role FROM refgeo_publication_persons WHERE position = ? ORDER BY role, person', [$pub]));
        $expected = [[self::p("x-$t")->value(), 'approver'], [self::p("y-$t")->value(), 'approver'], [self::p("p-$t")->value(), 'excluded']];
        usort($expected, static fn ($a, $b) => [$a[1], $a[0]] <=> [$b[1], $b[0]]);
        $this->assertSame($expected, $indexed, 'every approver of the published version and every excluded person, written with the publication');

        $before = self::lastPosition();
        self::consumer()->merged("my-$t", [self::p("y-$t"), self::p("p-$t")]);
        $this->assertSame($before, self::lastPosition(), 'Y is indexed (selected) but X is the counted approver: no defect');
        self::consumer()->merged("mx-$t", [self::p("x-$t"), self::p("p-$t")]);
        $this->assertSame(['finding'], array_column(self::noticesAfter($before), 'notice_kind'));
    }

    /** A10: a failure after the roster entry and before the notices, or after the notices and before commit, leaves NOTHING; redelivery then succeeds. */
    public function test_a10_a_failure_anywhere_in_the_act_leaves_neither_the_roster_entry_nor_any_notice(): void
    {
        $t = Str::random(8);
        self::published("p-$t", "x-$t");
        [$position, $roster] = [self::lastPosition(), self::rosterVersion()];

        // (1) immediately after the roster entry (the checkpoint) has been WRITTEN: an AFTER INSERT trigger on it
        DB::unprepared(<<<'SQL'
            CREATE FUNCTION refgeo_test_fail_after_roster() RETURNS trigger LANGUAGE plpgsql AS $$ BEGIN RAISE EXCEPTION 'injected: after the roster entry %', NEW.roster_version; END $$;
            CREATE TRIGGER refgeo_test_fail_after_roster AFTER INSERT ON refgeo_roster_entries FOR EACH ROW EXECUTE FUNCTION refgeo_test_fail_after_roster();
            SQL);
        try {
            self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
            $this->fail('the injected failure was swallowed');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('injected: after the roster entry ' . ($roster + 1), $e->getMessage(), 'the entry had been written when it failed');
        } finally {
            DB::unprepared('DROP TRIGGER refgeo_test_fail_after_roster ON refgeo_roster_entries; DROP FUNCTION refgeo_test_fail_after_roster();');
        }
        $this->assertSame([$position, $roster], [self::lastPosition(), self::rosterVersion()], 'failure right after the roster entry: nothing survived');

        // (2) after the roster entry, at the first notice write
        DB::unprepared(<<<'SQL'
            CREATE FUNCTION refgeo_test_fail_notice() RETURNS trigger LANGUAGE plpgsql AS $$ BEGIN RAISE EXCEPTION 'injected: notice write fails'; END $$;
            CREATE TRIGGER refgeo_test_fail_notice BEFORE INSERT ON refgeo_publications FOR EACH ROW WHEN (NEW.kind = 'notice') EXECUTE FUNCTION refgeo_test_fail_notice();
            SQL);
        try {
            self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
            $this->fail('the injected failure was swallowed');
        } catch (\Throwable $e) {
            $this->assertStringContainsString('injected: notice write fails', $e->getMessage());
        } finally {
            DB::unprepared('DROP TRIGGER refgeo_test_fail_notice ON refgeo_publications; DROP FUNCTION refgeo_test_fail_notice();');
        }
        $this->assertSame([$position, $roster], [self::lastPosition(), self::rosterVersion()], 'failure after the roster entry: the entry did not survive alone');

        // (3) after ALL notices are written, before commit: the hook sees them inside the transaction, then fails
        $seen = null;
        $failing = new PostgresPublicationLedger(DB::connection(), beforeCommit: static function () use (&$seen, $position): void {
            $seen = [(int) DB::selectOne("SELECT count(*) AS n FROM refgeo_publications WHERE kind = 'notice' AND position > ?", [$position])->n,
                (int) DB::selectOne('SELECT last_roster_version AS v FROM refgeo_sequence')->v];
            throw new \RuntimeException('injected: before commit');
        });
        try {
            self::consumer($failing)->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
            $this->fail('the injected failure was swallowed');
        } catch (\RuntimeException $e) {
            $this->assertSame('injected: before commit', $e->getMessage());
        }
        $this->assertSame([1, $roster + 1], $seen, 'at the failure the finding and the roster entry were both written (inside the transaction)');
        $this->assertSame([$position, $roster], [self::lastPosition(), self::rosterVersion()], 'failure after the notices: nothing survived');

        $this->assertNotNull(self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]), 'not consumed, so redelivery applies it');
        $this->assertSame(['finding'], array_column(self::noticesAfter($position), 'notice_kind'));
    }

    /** A9: a merge waits for a publication in progress, then sees it and raises the finding. */
    public function test_a9_a_merge_waits_for_a_publication_in_progress_and_then_raises_its_finding(): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t");

        [, $waited] = self::whileHeld(
            self::holdingPublication($id, "p-$t"),
            static fn () => self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]),
        );

        $pub = (int) DB::selectOne('SELECT position FROM refgeo_publications WHERE revision_id = ?', [$id->value()])->position;
        $this->assertGreaterThan(400, $waited, 'the merge waited for the publication');
        $found = self::noticesAfter($pub);
        $this->assertCount(1, $found, 'the merge saw the committed publication');
        $this->assertSame(['finding', $pub], [$found[0]->notice_kind, (int) $found[0]->concerns_position]);
    }

    /** A9: a publication waits for a merge in progress, then is refused (case A at publication; no finding needed). */
    public function test_a9_a_publication_waits_for_a_merge_in_progress_and_is_then_refused(): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t");
        $before = self::lastPosition();

        [$out, $waited] = self::whileHeld(
            static function (string $dir) use ($t): void {
                $holding = new PostgresPublicationLedger(DB::connection(), beforeCommit: static function () use ($dir): void {
                    touch("$dir/holding");
                    usleep(800_000);
                });
                self::consumer($holding)->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
            },
            static fn () => self::publisher("p-$t")->publish($id),
        );

        $this->assertGreaterThan(400, $waited, 'the publication waited for the merge');
        $this->assertSame([GovernanceRefusal::ApproverNotIndependent], $out->refusals);
        $this->assertSame($before, self::lastPosition(), 'neither a publication nor a notice');
    }

    /**
     * A8 on the cluster: after every step of a random merge/reversal sequence, a publication has an open finding iff its
     * counted approver is known-same as one of its excluded persons NOW (a full scan of this test's publications).
     */
    public function test_a8_the_bounded_impact_agrees_with_a_full_scan_after_every_step(): void
    {
        $t = Str::random(8);
        $people = ['a', 'b', 'c', 'd', 'e'];
        $pubs = [];   // position => [approver, excluded]
        foreach ([['a', 'b'], ['c', 'a'], ['e', 'd'], ['b', 'e']] as [$excluded, $approver]) {
            $pubs[self::published("$excluded-$t", "$approver-$t")] = [$approver, $excluded];
        }
        mt_srand(20261116);
        $merges = [];
        for ($step = 0; $step < 16; $step++) {
            if ($merges !== [] && mt_rand(0, 2) === 0) {
                self::consumer()->reversed($merges[mt_rand(0, count($merges) - 1)]);
            } else {
                [$x, $y] = [$people[mt_rand(0, 4)], $people[mt_rand(0, 4)]];
                if ($x === $y) {
                    continue;
                }
                $merges[] = $m = "m$step-$t";
                self::consumer()->merged($m, [self::p("$x-$t"), self::p("$y-$t")]);
            }
            $roster = self::store()->current();
            foreach ($pubs as $position => [$approver, $excluded]) {
                $defective = $roster->knownSameAt(self::p("$approver-$t"), self::p("$excluded-$t"), $roster->version());
                $open = DB::selectOne("SELECT count(*) AS n FROM refgeo_publications f WHERE f.notice_kind = 'finding' AND f.concerns_position = ?
                    AND NOT EXISTS (SELECT 1 FROM refgeo_publications r WHERE r.concerns_position = f.position AND r.notice_kind IN ('confirmation', 'closure'))", [$position])->n;
                $this->assertSame($defective ? 1 : 0, (int) $open, "step {$step}, publication {$position}");
            }
        }
    }

    public function test_the_consumer_starts_its_own_transaction(): void
    {
        DB::beginTransaction();
        try {
            self::consumer()->merged('m-' . Str::random(8), [self::p('a-' . Str::random(4)), self::p('b-' . Str::random(4))]);
            $this->fail('must be refused: the lock must be the first statement of its own transaction');
        } catch (\LogicException) {
            $this->addToAssertionCount(1);
        } finally {
            DB::rollBack();
        }
    }
}
