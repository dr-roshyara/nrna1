<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\BoundaryNotClosed;
use App\Contexts\ReferenceGeography\Application\ClockNotPastBoundary;
use App\Contexts\ReferenceGeography\Application\IdentityNoticeConsumer;
use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\PublicationStatus;
use App\Contexts\ReferenceGeography\Application\PublishRevision;
use App\Contexts\ReferenceGeography\Domain\Approval;
use App\Contexts\ReferenceGeography\Domain\Assessment;
use App\Contexts\ReferenceGeography\Domain\AssessmentKind;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\JudgmentReason;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RevisionState;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublicationLedger;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedAtlas;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresRevisionRepository;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresStewardRosterStore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use Tests\Support\ReferenceGeography\CompetentInstrument;

/**
 * PBDIGIT-346 G-4e, the publication ledger (v3.11 §5.3, §6, §8; completion §4; G-4 interpretation B-1 to B-7):
 * - ONE lock (refgeo_sequence FOR UPDATE) for publication and every roster write: governance is re-evaluated under it
 *   at the roster's current version, so no roster change or Identity notice falls between the check and the append;
 * - the actor must be appointed for the matter (D-84 "a steward assesses and publishes");
 * - staleness under the lock: a publication after p₀ matching a predicate refuses publication and marks the revision
 *   Stale (§5.3); an approval binds to its content version;
 * - a version is published at most once; a failure leaves nothing (no publication, no sequence advance); a
 *   publication is invisible until commit;
 * - clock rule (refused, never adjusted) and close-through-τ (never a future τ);
 * - each published claim is frozen with its before-image, which equals the belief just before publication.
 * The harness keeps data between tests: every person and unit is fresh; assertions are relative to the state before.
 */
#[Group('refgeo-race')]
final class PostgresPublicationLedgerTest extends RefgeoPostgresTestCase
{
    private static function p(string $label): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $label));
    }

    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    private static function store(): PostgresStewardRosterStore
    {
        return new PostgresStewardRosterStore(DB::connection());
    }

    private static function repo(): PostgresRevisionRepository
    {
        return new PostgresRevisionRepository(DB::connection());
    }

    private static function lastPosition(): int
    {
        return (int) DB::selectOne('SELECT last_position FROM refgeo_sequence')->last_position;
    }

    private static function acting(string $label): ActingPerson
    {
        return new class (self::p($label)) implements ActingPerson {
            public function __construct(private readonly PersonReference $person)
            {
            }

            public function current(): PersonReference
            {
                return $this->person;
            }
        };
    }

    private static function service(string $actor, ?PostgresPublicationLedger $ledger = null): PublishRevision
    {
        return new PublishRevision($ledger ?? new PostgresPublicationLedger(DB::connection()), new PublicationGovernance(), self::acting($actor));
    }

    /** @param list<string> $people appointed stewards */
    private static function appoint(string ...$people): void
    {
        self::store()->writeUnderLock(static function (StewardRoster $r) use ($people): void {
            foreach ($people as $p) {
                if (!$r->isStewardAt(self::p($p), $r->version())) {
                    $r->appointSteward(self::p($p), self::p('owner'));
                }
            }
        });
    }

    private static function declare(string $person, RevisionId $matter, bool $interest = false): void
    {
        self::store()->writeUnderLock(static fn (StewardRoster $r): int => $r->declareConflict(self::p($person), $matter, $interest));
    }

    /** The competence claim every territorial fixture relies on (D-82), published once per run through the real path. */
    /** The competence claim (D-82), published directly, never through the code under test (CompetenceBootstrap). */
    private static function ensureCompetence(): void
    {
        CompetenceBootstrap::ensure();
    }

    private static function submitAndApprove(Revision $r, string $assessor, ?string $approver): void
    {
        foreach (AssessmentKind::required() as $kind) {
            $r->recordAssessment(new Assessment($kind, 'satisfied', self::p($assessor)));
        }
        $r->validateWith(new RevisionValidator(), new DependencyRecordingReader(PostgresPublishedAtlas::at(DB::connection())));
        $r->submit(self::p($assessor));
        if ($approver !== null) {
            $r->approve(new Approval($r->currentVersion()->number, self::store()->current()->version(), self::p($approver)));
        }
        self::repo()->add($r);
    }

    /**
     * A structural revision (the existence of a fresh unit, or of $unit), prepared and assessed by $assessor,
     * approved by $approver; both appointed and declared without interest. Era B is always satisfied.
     */
    private static function revision(string $assessor, string $approver, ?ReferenceUnitId $unit = null, bool $judgment = false): RevisionId
    {
        self::ensureCompetence();
        $id = RevisionId::fromString((string) Str::uuid());
        self::appoint($assessor, $approver);
        self::declare($assessor, $id);
        self::declare($approver, $id);
        $r = Revision::prepare($id, self::p($assessor));
        $r->addClaim(new Claim(ClaimId::fromString((string) Str::uuid()), SlotKey::existence($unit ?? ReferenceUnitId::fromString((string) Str::uuid())), ClaimValue::of('exists'), self::iv('2016-01-01'), new Basis(BasisKind::Instrument, 'act')), self::p($assessor));
        $r->attachEvidence(CompetentInstrument::evidence('act'), self::p($assessor));
        if ($judgment) {
            $r->flagJudgmentMatter(self::p($assessor), JudgmentReason::UncertainClassification);
        }
        self::submitAndApprove($r, $assessor, $approver);

        return $id;
    }

    public function test_a_valid_revision_is_published_once_and_recorded_exactly(): void
    {
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t");
        $before = self::lastPosition();

        $out = self::service("p-$t")->publish($id);

        $this->assertSame(PublicationStatus::Published, $out->status, implode(',', array_map(static fn ($r) => $r->value, $out->refusals)));
        $this->assertSame($before + 1, $out->position);
        $row = DB::selectOne('SELECT * FROM refgeo_publications WHERE position = ?', [$out->position]);
        $this->assertSame([$id->value(), 1, (int) self::store()->current()->version(), 'second-person-approved'], [$row->revision_id, (int) $row->content_version, (int) $row->roster_version, $row->governance_rule]);
        $this->assertSame("independent as known to Identity at roster version {$row->roster_version}", $row->independence);

        $again = self::service("p-$t")->publish($id);
        $this->assertSame(PublicationStatus::AlreadyPublished, $again->status);
        $this->assertSame($before + 1, self::lastPosition());
    }

    public function test_governance_is_re_evaluated_under_the_lock_at_the_current_roster_version(): void
    {
        $t = Str::random(8);
        $cases = [
            'an Identity merge recorded after the approval' => [false, static fn (RevisionId $id) => (new IdentityNoticeConsumer(new PostgresPublicationLedger(DB::connection()), new PublicationGovernance()))->merged("m-$t", [self::p("p1-$t"), self::p("x1-$t")]), GovernanceRefusal::ApproverNotIndependent, 1],
            'an interest declared after the approval in a judgment matter' => [true, static fn (RevisionId $id) => self::declare("x2-$t", $id, true), GovernanceRefusal::MateriallyConflicted, 2],
        ];
        foreach ($cases as $why => [$judgment, $change, $refusal, $n]) {
            $id = self::revision("p$n-$t", "x$n-$t", judgment: $judgment);
            $change($id);
            $before = self::lastPosition();

            $out = self::service("p$n-$t")->publish($id);

            $this->assertSame(PublicationStatus::Refused, $out->status, $why);
            $this->assertSame([$refusal], $out->refusals, $why);
            $this->assertSame($before, self::lastPosition(), "{$why}: nothing written");
        }
    }

    public function test_an_approval_of_an_earlier_version_does_not_authorise_a_later_one(): void
    {
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t");
        $stored = self::repo()->get($id);
        $r = $stored->revision;
        $r->markStale();
        $r->reprepare(self::p("p-$t"));
        foreach (AssessmentKind::required() as $kind) {
            $r->recordAssessment(new Assessment($kind, 'again', self::p("p-$t")));
        }
        $r->validateWith(new RevisionValidator(), new DependencyRecordingReader(PostgresPublishedAtlas::at(DB::connection())));
        $r->submit(self::p("p-$t"));
        self::repo()->save($r, $stored->rowVersion);

        $out = self::service("p-$t")->publish($id);

        $this->assertSame([GovernanceRefusal::SecondPersonRequired], $out->refusals);
    }

    public function test_only_a_person_appointed_for_the_matter_may_publish(): void
    {
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t");
        $before = self::lastPosition();

        $out = self::service("stranger-$t")->publish($id);

        $this->assertSame([GovernanceRefusal::NotAppointed], $out->refusals);
        $this->assertSame($before, self::lastPosition());
    }

    public function test_a_stale_revision_is_refused_and_marked_stale(): void
    {
        $t = Str::random(8);
        $unit = ReferenceUnitId::fromString((string) Str::uuid());
        $late = self::revision("p-$t", "x-$t", $unit);       // validated first, reading existence(unit)
        $early = self::revision("q-$t", "y-$t", $unit);      // the same slot
        $this->assertSame(PublicationStatus::Published, self::service("q-$t")->publish($early)->status);
        $before = self::lastPosition();

        $out = self::service("p-$t")->publish($late);

        $this->assertSame(PublicationStatus::Stale, $out->status);
        $this->assertSame($before, self::lastPosition());
        $this->assertSame(RevisionState::Stale, self::repo()->get($late)->revision->state(), 'the stale mark is kept');
    }

    public function test_a_clock_reading_below_the_previous_one_is_refused_never_adjusted(): void
    {
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t");
        $before = self::lastPosition();
        $behind = new PostgresPublicationLedger(DB::connection(), clockSql: "clock_timestamp() - interval '1 day'");

        try {
            self::service("p-$t", $behind)->publish($id);
            $this->fail('must be refused');
        } catch (ClockNotPastBoundary) {
            $this->addToAssertionCount(1);
        }
        $this->assertSame($before, self::lastPosition());
        $this->assertSame(PublicationStatus::Published, self::service("p-$t")->publish($id)->status, 'published once the clock is past the boundary');
    }

    public function test_a_failure_before_commit_leaves_nothing_and_the_position_is_reused(): void
    {
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t");
        $before = self::lastPosition();
        $failing = new PostgresPublicationLedger(DB::connection(), beforeCommit: static function (): void {
            throw new \RuntimeException('test: failure injected after the append, before commit');
        });

        try {
            self::service("p-$t", $failing)->publish($id);
            $this->fail('the injected failure must surface');
        } catch (\RuntimeException) {
            $this->addToAssertionCount(1);
        }
        $this->assertSame($before, self::lastPosition(), 'no sequence advance');
        $this->assertSame(0, DB::table('refgeo_publications')->where('revision_id', $id->value())->count(), 'no publication');
        $this->assertSame($before + 1, self::service("p-$t")->publish($id)->position, 'the position is reused');
    }

    public function test_a_publication_is_invisible_until_it_commits(): void
    {
        $t = Str::random(8);
        $unit = ReferenceUnitId::fromString((string) Str::uuid());
        $id = self::revision("p-$t", "x-$t", $unit);
        $before = self::lastPosition();
        config(['database.connections.refgeo_observer' => config('database.connections.' . config('database.default'))]);
        $seen = [];
        $ledger = new PostgresPublicationLedger(DB::connection(), beforeCommit: static function () use (&$seen, $unit): void {
            $observer = PostgresPublishedAtlas::at(DB::connection('refgeo_observer'));
            $seen = [$observer->p0(), $observer->history(SlotKey::existence($unit))->priorAt(ValidDate::fromString('2017-01-01'))->isAbsent()];
        });

        $out = self::service("p-$t", $ledger)->publish($id);

        $this->assertSame([$before, true], $seen, 'another connection saw neither the position nor the claim before commit');
        $this->assertSame('exists', PostgresPublishedAtlas::at(DB::connection('refgeo_observer'))->history(SlotKey::existence($unit))->resolve(ValidDate::fromString('2017-01-01'))?->value());
        $this->assertSame($before + 1, $out->position);
        DB::purge('refgeo_observer');
    }

    public function test_close_through_tau_never_confirms_a_future_tau_and_later_readings_are_after_it(): void
    {
        $ledger = new PostgresPublicationLedger(DB::connection());

        try {
            $ledger->closeThrough(DB::selectOne("SELECT (clock_timestamp() + interval '1 hour')::text AS t")->t);
            $this->fail('a future τ must be refused');
        } catch (BoundaryNotClosed) {
            $this->addToAssertionCount(1);
        }
        $w = $ledger->closeThrough(null);   // through now
        $this->assertSame($w, $ledger->closeThrough('2000-01-01 00:00:00+00'), 'a τ at or below W is already confirmed; W never moves back');

        $t = Str::random(8);
        $out = self::service("p-$t")->publish(self::revision("p-$t", "x-$t"));
        $this->assertTrue((bool) DB::selectOne('SELECT recorded_at > ?::timestamptz AS after FROM refgeo_publications WHERE position = ?', [$w, $out->position])->after);
    }

    public function test_each_published_claim_is_frozen_with_its_before_image_which_equals_the_belief_just_before_publication(): void
    {
        $t = Str::random(8);
        $unit = ReferenceUnitId::fromString((string) Str::uuid());
        $id = self::revision("p-$t", "x-$t", $unit);

        $out = self::service("p-$t")->publish($id);

        $row = DB::selectOne('SELECT before_image::text AS b FROM refgeo_claims WHERE revision_id = ?', [$id->value()]);
        $stored = json_decode($row->b, true);
        $belief = BeforeImage::of(PostgresPublishedAtlas::at(DB::connection(), $out->position - 1)->history(SlotKey::existence($unit)), self::iv('2016-01-01'), $out->position - 1);
        $this->assertSame(array_map(static fn (array $s): array => [$s[0]->from->value(), $s[0]->to?->value(), $s[1]->isAbsent() ? 'absent' : ($s[1]->isNone() ? 'none' : 'value')], $belief->segments()),
            array_map(static fn (array $s): array => [$s[0], $s[1], $s[2]], $stored));
    }

    /**
     * Items 1–6 (the decisive interleaving): a publisher process publishes judgment-matter revisions while another
     * process records, for each, an Identity merge of approver and preparer or an interest declared by the approver.
     * Serialisation is checked directly: every publication is valid at the roster version it recorded, and every
     * roster entry lies strictly before or after it in commit order (recorded_at under the one lock).
     */
    public function test_publication_and_concurrent_governance_writes_are_serialised(): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
        $t = Str::random(8);
        $n = 8;
        $ids = [];
        for ($i = 0; $i < $n; $i++) {
            $ids[$i] = self::revision("p$i-$t", "x$i-$t", judgment: true);
        }
        $dir = sys_get_temp_dir() . '/pbdigit346-publish-race-' . $t;
        mkdir($dir);
        DB::disconnect();
        $pids = [];
        foreach (['publisher', 'governance'] as $role) {
            $pid = pcntl_fork();
            if ($pid === 0) {
                DB::purge();
                while (!file_exists("$dir/go")) {
                    usleep(100);
                }
                $lines = [];
                try {
                    for ($i = 0; $i < $n; $i++) {
                        usleep(random_int(0, 4000));
                        if ($role === 'publisher') {
                            $out = self::service("p$i-$t")->publish($ids[$i]);
                            $lines[] = [$i, $out->status->value, $out->position];
                        } elseif ($i % 2 === 0) {
                            $lines[] = [$i, 'merge', (new IdentityNoticeConsumer(new PostgresPublicationLedger(DB::connection()), new PublicationGovernance()))->merged("m$i-$t", [self::p("p$i-$t"), self::p("x$i-$t")])];
                        } else {
                            self::declare("x$i-$t", $ids[$i], true);
                            $lines[] = [$i, 'interest', self::store()->current()->version()];
                        }
                    }
                } catch (\Throwable $e) {
                    $lines[] = ['error', $e::class . ': ' . $e->getMessage()];
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

        foreach (['publisher', 'governance'] as $role) {
            foreach (json_decode((string) @file_get_contents("$dir/$role.json"), true) ?? [['error', 'no result']] as $line) {
                $this->assertNotSame('error', $line[0], (string) ($line[1] ?? ''));
                if ($role === 'publisher') {
                    $this->assertContains($line[1], ['published', 'refused'], 'never stale, never an error');
                }
            }
        }
        $roster = self::store()->current();
        $governance = new PublicationGovernance();
        $published = 0;
        foreach ($ids as $i => $id) {
            $pub = DB::selectOne('SELECT position, roster_version, recorded_at FROM refgeo_publications WHERE revision_id = ?', [$id->value()]);
            if ($pub === null) {
                continue;
            }
            $published++;
            $rv = (int) $pub->roster_version;
            $this->assertTrue($governance->mayPublish(self::repo()->get($id)->revision, $roster, $rv)->allowed, "revision $i was valid at the roster version it recorded");
            $later = (int) DB::selectOne('SELECT count(*) AS n FROM refgeo_roster_entries WHERE roster_version > ? AND recorded_at < ?::timestamptz', [$rv, $pub->recorded_at])->n;
            $earlier = (int) DB::selectOne('SELECT count(*) AS n FROM refgeo_roster_entries WHERE roster_version <= ? AND recorded_at > ?::timestamptz', [$rv, $pub->recorded_at])->n;
            $this->assertSame([0, 0], [$later, $earlier], "revision $i: no roster entry crossed its publication");
            $this->assertFalse($governance->mayPublish(self::repo()->get($id)->revision, $roster, $roster->version())->allowed, "revision $i: refused at the final roster version (sanity: the write does bite)");
        }
        $this->addToAssertionCount($published);
    }

    /**
     * Runs $holder in a forked process (it holds the one lock for a while) and, 200 ms after it starts, $contender in
     * this process; returns [the contender's result, its wait in ms].
     */
    private static function whileHeld(\Closure $holder, \Closure $contender): array
    {
        $dir = sys_get_temp_dir() . '/pbdigit346-held-' . Str::random(8);
        mkdir($dir);
        DB::disconnect();
        $pid = pcntl_fork();
        if ($pid === 0) {
            DB::purge();
            try {
                $holder($dir);
            } catch (\Throwable $e) {
                file_put_contents("$dir/holder-error", $e::class . ': ' . $e->getMessage());
            } finally {
                touch("$dir/holding");   // always signal: a failed holder must fail the test, not hang it (G-4g)
                DB::disconnect();
                posix_kill(posix_getpid(), SIGKILL);
            }
        }
        DB::reconnect();
        $deadline = hrtime(true) + 60_000_000_000;
        while (!file_exists("$dir/holding")) {
            if (hrtime(true) > $deadline) {
                posix_kill($pid, SIGKILL);
                pcntl_waitpid($pid, $status);
                throw new \RuntimeException('the holder never took the lock within 60 s');
            }
            usleep(1000);
        }
        usleep(200_000);
        $start = hrtime(true);
        $result = $contender();
        $waited = (int) ((hrtime(true) - $start) / 1_000_000);
        pcntl_waitpid($pid, $status);
        if (file_exists("$dir/holder-error")) {
            throw new \RuntimeException('the holder failed: ' . file_get_contents("$dir/holder-error"));
        }

        return [$result, $waited];
    }

    /** Deterministic: a publisher that starts while a roster write holds the lock waits for it, then sees it. */
    public function test_a_publication_waits_for_a_roster_write_in_progress_and_then_sees_it(): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t", judgment: true);
        $before = self::lastPosition();

        [$out, $waited] = self::whileHeld(
            static function (string $dir) use ($t, $id): void {
                self::store()->writeUnderLock(static function (StewardRoster $r) use ($t, $id, $dir): int {
                    $v = $r->declareConflict(self::p("x-$t"), $id, true);   // the approver declares an interest
                    touch("$dir/holding");
                    usleep(800_000);                                         // …and holds the lock

                    return $v;
                });
            },
            static fn () => self::service("p-$t")->publish($id),
        );

        $this->assertGreaterThan(400, $waited, 'the publisher waited for the roster write');
        $this->assertSame([GovernanceRefusal::MateriallyConflicted], $out->refusals, 'and then judged against it');
        $this->assertSame($before, self::lastPosition());
    }

    /** Deterministic: a roster write that starts while a publication holds the lock waits for it, and comes after it. */
    public function test_a_roster_write_waits_for_a_publication_in_progress_and_comes_after_it(): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t", judgment: true);

        [$version, $waited] = self::whileHeld(
            static function (string $dir) use ($t, $id): void {
                $holding = new PostgresPublicationLedger(DB::connection(), beforeCommit: static function () use ($dir): void {
                    touch("$dir/holding");
                    usleep(800_000);
                });
                self::service("p-$t", $holding)->publish($id);
            },
            static function () use ($t, $id): int {
                self::declare("x-$t", $id, true);

                return self::store()->current()->version();
            },
        );

        $pub = DB::selectOne('SELECT roster_version, recorded_at FROM refgeo_publications WHERE revision_id = ?', [$id->value()]);
        $entry = DB::selectOne('SELECT recorded_at FROM refgeo_roster_entries WHERE roster_version = ?', [$version]);
        $this->assertGreaterThan(400, $waited, 'the roster write waited for the publication');
        $this->assertNotNull($pub, 'the publication committed, judged before the declaration');
        $this->assertLessThan($version, (int) $pub->roster_version);
        $this->assertTrue((bool) DB::selectOne('SELECT ?::timestamptz > ?::timestamptz AS after', [$entry->recorded_at, $pub->recorded_at])->after, 'the declaration is recorded after the publication');
    }

    public function test_concurrent_publishers_of_one_revision_publish_it_exactly_once(): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t");
        $dir = sys_get_temp_dir() . '/pbdigit346-double-publish-' . $t;
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
                    $line = self::service("p-$t")->publish($id)->status->value;
                } catch (\Throwable $e) {
                    $line = 'error: ' . $e->getMessage();
                }
                file_put_contents("$dir/$w.txt", $line);
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

        $outcomes = array_map(static fn (int $w): string => (string) @file_get_contents("$dir/$w.txt"), range(0, 2));
        sort($outcomes);
        $this->assertSame(['already-published', 'already-published', 'published'], $outcomes);
        $this->assertSame(1, DB::table('refgeo_publications')->where('revision_id', $id->value())->count());
    }
}
