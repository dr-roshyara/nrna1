<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\PublicationStatus;
use App\Contexts\ReferenceGeography\Application\PublishRevision;
use App\Contexts\ReferenceGeography\Application\RevisionAlreadyPublished;
use App\Contexts\ReferenceGeography\Domain\Approval;
use App\Contexts\ReferenceGeography\Domain\Assessment;
use App\Contexts\ReferenceGeography\Domain\AssessmentKind;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\EvidenceVersion;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublicationLedger;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedAtlas;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresRevisionRepository;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresStewardRosterStore;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use Tests\Support\ReferenceGeography\CompetentInstrument;

/**
 * PBDIGIT-346 G-4f, a published revision is closed at the PERSISTENCE boundary too (published claims never change;
 * corrections are new revisions):
 * - the repository reports a revision as published and refuses to save it (RevisionAlreadyPublished);
 * - a database trigger refuses any update of a published revision's row, whoever writes it;
 * - publication locks the revision's row under the publication lock, so a save racing a publication waits for it
 *   and is then refused: never a re-prepared (or withdrawn) revision whose version is published.
 */
#[Group('refgeo-race')]
final class PostgresPublishedRevisionClosureTest extends RefgeoPostgresTestCase
{
    private static function p(string $label): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $label));
    }

    private static function store(): PostgresStewardRosterStore
    {
        return new PostgresStewardRosterStore(DB::connection());
    }

    private static function repo(): PostgresRevisionRepository
    {
        return new PostgresRevisionRepository(DB::connection());
    }

    private static function publisher(string $actor, ?PostgresPublicationLedger $ledger = null): PublishRevision
    {
        $acting = new class (self::p($actor)) implements ActingPerson {
            public function __construct(private readonly PersonReference $person)
            {
            }

            public function current(): PersonReference
            {
                return $this->person;
            }
        };

        return new PublishRevision($ledger ?? new PostgresPublicationLedger(DB::connection()), new PublicationGovernance(), $acting);
    }

    /** A submitted, approved, publishable revision (both stewards appointed and declared; the competence published once). */
    private static function revision(string $t): RevisionId
    {
        $id = RevisionId::fromString((string) Str::uuid());
        self::store()->writeUnderLock(static function (StewardRoster $r) use ($t, $id): void {
            foreach (["p-$t", "x-$t", 'boot-s', 'boot-x'] as $s) {
                if (!$r->isStewardAt(self::p($s), $r->version())) {
                    $r->appointSteward(self::p($s), self::p('owner'));
                }
            }
            $r->declareConflict(self::p("p-$t"), $id, false);
            $r->declareConflict(self::p("x-$t"), $id, false);
        });
        $competence = CompetentInstrument::competenceClaim();
        if (PostgresPublishedAtlas::at(DB::connection())->history($competence->slot)->priorAt(ValidDate::fromString('1960-01-01'))->isAbsent()) {
            $boot = RevisionId::fromString((string) Str::uuid());
            self::store()->writeUnderLock(static function (StewardRoster $r) use ($boot): void {
                $r->declareConflict(self::p('boot-s'), $boot, false);
                $r->declareConflict(self::p('boot-x'), $boot, false);
            });
            self::submit(Revision::prepare($boot, self::p('boot-s')), $competence, new EvidenceVersion('gazette', str_repeat('c', 64)), 'boot-s', 'boot-x');
            self::publisher('boot-s')->publish($boot);
        }
        $claim = new Claim(ClaimId::fromString((string) Str::uuid()), SlotKey::existence(ReferenceUnitId::fromString((string) Str::uuid())), ClaimValue::of('exists'),
            new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Instrument, 'act'));
        self::submit(Revision::prepare($id, self::p("p-$t")), $claim, CompetentInstrument::evidence('act'), "p-$t", "x-$t");

        return $id;
    }

    private static function submit(Revision $r, Claim $claim, EvidenceVersion $evidence, string $assessor, string $approver): void
    {
        $r->addClaim($claim, self::p($assessor));
        $r->attachEvidence($evidence, self::p($assessor));
        foreach (AssessmentKind::required() as $kind) {
            $r->recordAssessment(new Assessment($kind, 'satisfied', self::p($assessor)));
        }
        $r->validateWith(new RevisionValidator(), new DependencyRecordingReader(PostgresPublishedAtlas::at(DB::connection())));
        $r->submit(self::p($assessor));
        $r->approve(new Approval(1, self::store()->current()->version(), self::p($approver)));
        self::repo()->add($r);
    }

    public function test_the_repository_reports_a_published_revision_and_refuses_to_save_it(): void
    {
        $t = Str::random(8);
        $id = self::revision($t);
        $this->assertFalse(self::repo()->get($id)->published);
        $this->assertSame(PublicationStatus::Published, self::publisher("p-$t")->publish($id)->status);

        $stored = self::repo()->get($id);
        $this->assertTrue($stored->published);
        $stored->revision->markStale();
        $stored->revision->reprepare(self::p("p-$t"));

        $this->expectException(RevisionAlreadyPublished::class);
        self::repo()->save($stored->revision, $stored->rowVersion);
    }

    public function test_the_database_refuses_any_update_of_a_published_revisions_row(): void
    {
        $t = Str::random(8);
        $id = self::revision($t);
        self::publisher("p-$t")->publish($id);

        try {
            DB::update("UPDATE refgeo_revisions SET state = 'withdrawn' WHERE id = ?", [$id->value()]);
            $this->fail('must be refused');
        } catch (QueryException $e) {
            $this->assertSame('P0001', $e->errorInfo[0] ?? null, $e->getMessage());
        }
        $this->assertSame('submitted', DB::selectOne('SELECT state FROM refgeo_revisions WHERE id = ?', [$id->value()])->state);
    }

    /** Deterministic: a save that starts while the revision is being published waits for the publication, then is refused. */
    public function test_a_save_racing_a_publication_waits_for_it_and_is_then_refused(): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
        $t = Str::random(8);
        $id = self::revision($t);
        $loaded = self::repo()->get($id);   // loaded BEFORE the publication: not yet published
        $loaded->revision->markStale();
        $loaded->revision->reprepare(self::p("p-$t"));
        $dir = sys_get_temp_dir() . '/pbdigit346-closure-' . $t;
        mkdir($dir);
        DB::disconnect();
        $pid = pcntl_fork();
        if ($pid === 0) {
            DB::purge();
            try {
                self::publisher("p-$t", new PostgresPublicationLedger(DB::connection(), beforeCommit: static function () use ($dir): void {
                    touch("$dir/holding");
                    usleep(800_000);
                }))->publish($id);
            } finally {
                DB::disconnect();
                posix_kill(posix_getpid(), SIGKILL);
            }
        }
        DB::reconnect();
        while (!file_exists("$dir/holding")) {
            usleep(1000);
        }
        usleep(200_000);
        $start = hrtime(true);
        try {
            self::repo()->save($loaded->revision, $loaded->rowVersion);
            $outcome = 'saved';
        } catch (RevisionAlreadyPublished) {
            $outcome = 'refused';
        }
        $waited = (int) ((hrtime(true) - $start) / 1_000_000);
        pcntl_waitpid($pid, $status);

        $this->assertGreaterThan(400, $waited, 'the save waited for the publication');
        $this->assertSame('refused', $outcome, 'and was then refused');
        $this->assertSame('submitted', DB::selectOne('SELECT state FROM refgeo_revisions WHERE id = ?', [$id->value()])->state);
        $this->assertSame(1, DB::table('refgeo_publications')->where('revision_id', $id->value())->count());
    }
}
