<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\PublishRevision;
use App\Contexts\ReferenceGeography\Domain\Approval;
use App\Contexts\ReferenceGeography\Domain\Assessment;
use App\Contexts\ReferenceGeography\Domain\AssessmentKind;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\JudgmentReason;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\ReferenceGeography\CompetentInstrument;

/**
 * TEST ONLY (G-4g): building publishable revisions on the isolated cluster, and running one operation while another
 * holds the one lock (deterministic interleavings, no timing luck). People and units are fresh per call.
 */
trait PublishingFixtures
{
    protected static function p(string $label): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $label));
    }

    protected static function store(): PostgresStewardRosterStore
    {
        return new PostgresStewardRosterStore(DB::connection());
    }

    protected static function repo(): PostgresRevisionRepository
    {
        return new PostgresRevisionRepository(DB::connection());
    }

    protected static function lastPosition(): int
    {
        return (int) DB::selectOne('SELECT last_position FROM refgeo_sequence')->last_position;
    }

    protected static function publisher(string $actor, ?PostgresPublicationLedger $ledger = null): PublishRevision
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

    /** @param list<string> $people */
    protected static function appoint(string ...$people): void
    {
        self::store()->writeUnderLock(static function (StewardRoster $r) use ($people): void {
            foreach ($people as $p) {
                if (!$r->isStewardAt(self::p($p), $r->version())) {
                    $r->appointSteward(self::p($p), self::p('owner'));
                }
            }
        });
    }

    protected static function declare(string $person, RevisionId $matter, bool $interest = false): void
    {
        self::store()->writeUnderLock(static fn (StewardRoster $r): int => $r->declareConflict(self::p($person), $matter, $interest));
    }

    /**
     * The competence claim every territorial fixture relies on (D-82), published once per run DIRECTLY (frozen claim
     * + publication row, under the publication lock, through the G-4a guards), NOT through PublishRevision: the
     * fixture must not depend on the code a mutation test is changing (G-4g: mutant G4 had killed this bootstrap,
     * so every test died in setup instead of on its assertion).
     */
    private static function ensureCompetence(): void
    {
        CompetenceBootstrap::ensure();
    }

    private static function submitAndApprove(Revision $r, string $assessor, string $approver): void
    {
        foreach (AssessmentKind::required() as $kind) {
            $r->recordAssessment(new Assessment($kind, 'satisfied', self::p($assessor)));
        }
        $r->validateWith(new RevisionValidator(), new DependencyRecordingReader(PostgresPublishedAtlas::at(DB::connection())));
        $r->submit(self::p($assessor));
        $r->approve(new Approval($r->currentVersion()->number, self::store()->current()->version(), self::p($approver)));
        self::repo()->add($r);
    }

    /**
     * A structural revision of a fresh unit's existence, prepared and assessed by $assessor, approved by $approver;
     * both appointed and declared without interest (era B is always latched in the shared cluster).
     */
    protected static function revision(string $assessor, string $approver, bool $judgment = false): RevisionId
    {
        self::ensureCompetence();
        $id = RevisionId::fromString((string) Str::uuid());
        self::appoint($assessor, $approver);
        self::declare($assessor, $id);
        self::declare($approver, $id);
        $r = Revision::prepare($id, self::p($assessor));
        $r->addClaim(new Claim(ClaimId::fromString((string) Str::uuid()), SlotKey::existence(ReferenceUnitId::fromString((string) Str::uuid())), ClaimValue::of('exists'),
            new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Instrument, 'act')), self::p($assessor));
        $r->attachEvidence(CompetentInstrument::evidence('act'), self::p($assessor));
        if ($judgment) {
            $r->flagJudgmentMatter(self::p($assessor), JudgmentReason::UncertainClassification);
        }
        self::submitAndApprove($r, $assessor, $approver);

        return $id;
    }

    /**
     * Runs $holder in a forked process; $holder must touch "$dir/holding" once it holds the lock, then keep holding.
     * 200 ms later $contender runs here. Returns [the contender's result, how long it waited in ms].
     */
    protected static function whileHeld(\Closure $holder, \Closure $contender): array
    {
        $dir = sys_get_temp_dir() . '/pbdigit346-g4g-' . Str::random(10);
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
                touch("$dir/holding");
                DB::disconnect();
                posix_kill(posix_getpid(), SIGKILL);
            }
        }
        DB::reconnect();
        $deadline = hrtime(true) + 60_000_000_000;   // a holder that never signals fails the test, never hangs the suite
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

    /** A holder that makes a roster write under the lock and keeps holding it. */
    protected static function holdingRosterWrite(\Closure $write): \Closure
    {
        return static function (string $dir) use ($write): void {
            self::store()->writeUnderLock(static function (StewardRoster $r) use ($write, $dir): void {
                $write($r);
                touch("$dir/holding");
                usleep(800_000);
            });
        };
    }

    /** A holder that publishes and keeps the lock after appending, before commit. */
    protected static function holdingPublication(RevisionId $id, string $actor): \Closure
    {
        return static function (string $dir) use ($id, $actor): void {
            $out = self::publisher($actor, new PostgresPublicationLedger(DB::connection(), beforeCommit: static function () use ($dir): void {
                touch("$dir/holding");
                usleep(800_000);
            }))->publish($id);
            file_put_contents("$dir/publication", $out->status->value);
        };
    }
}
