<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\ConfirmPublication;
use App\Contexts\ReferenceGeography\Application\IdentityNoticeConsumer;
use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\LockedLedger;
use App\Contexts\ReferenceGeography\Application\PublicationStatus;
use App\Contexts\ReferenceGeography\Domain\ApprovalStatusKind;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\FindingLifecycle;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublicationLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use Tests\Support\ReferenceGeography\CompetentInstrument;

/**
 * PBDIGIT-346 G-6c-5 (design revision 3 §8, §9; Principal decision 1: FULL COVERAGE): PublishRevision with `corrects: p`
 * publishes the correcting revision AND resolves p's open finding in one act, under the one lock, only if p has an open
 * finding and every claim of p is covered (slot and whole interval, jointly by several claims if needed). Otherwise the
 * whole publication is refused and nothing is written; the same revision may still be published WITHOUT the link, as
 * an ordinary revision, which resolves nothing. Tests B4, B5, B6, B11 of the design and the Principal's G-6c-5 list.
 */
#[Group('refgeo-race')]
final class CorrectionLinkTest extends RefgeoPostgresTestCase
{
    use PublishingFixtures;

    private static function ledger(?\Closure $beforeCommit = null): PostgresPublicationLedger
    {
        return new PostgresPublicationLedger(DB::connection(), beforeCommit: $beforeCommit);
    }

    private static function consumer(): IdentityNoticeConsumer
    {
        return new IdentityNoticeConsumer(self::ledger(), new PublicationGovernance());
    }

    /**
     * A structural revision asserting the existence of $unit over each interval, prepared and assessed by $assessor,
     * approved by $approver (both appointed, declared without interest).
     *
     * @param list<array{string, ?string}> $intervals
     */
    private static function existence(string $assessor, string $approver, ReferenceUnitId $unit, array $intervals): RevisionId
    {
        self::ensureCompetence();
        $id = RevisionId::fromString((string) Str::uuid());
        self::appoint($assessor, $approver);
        self::declare($assessor, $id);
        self::declare($approver, $id);
        $r = Revision::prepare($id, self::p($assessor));
        foreach ($intervals as [$from, $to]) {
            $r->addClaim(new Claim(ClaimId::fromString((string) Str::uuid()), SlotKey::existence($unit), ClaimValue::of('exists'),
                new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to)), new Basis(BasisKind::Instrument, 'act')), self::p($assessor));
        }
        $r->attachEvidence(CompetentInstrument::evidence('act'), self::p($assessor));
        self::submitAndApprove($r, $assessor, $approver);

        return $id;
    }

    /**
     * Publication p of a fresh unit's existence from 2016-01-01 (open-ended), assessed by p, approved by x; then Identity
     * finds x is p: an open finding f. Returns [unit, p, f].
     *
     * @return array{ReferenceUnitId, int, int}
     */
    private static function flagged(string $t): array
    {
        $unit = ReferenceUnitId::fromString((string) Str::uuid());
        $p = self::publisher("p-$t")->publish(self::existence("p-$t", "x-$t", $unit, [['2016-01-01', null]]))->position;
        self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
        $f = (int) DB::selectOne("SELECT position FROM refgeo_publications WHERE notice_kind = 'finding' AND concerns_position = ?", [$p])->position;

        return [$unit, $p, $f];
    }

    private static function lifecycle(int $p): FindingLifecycle
    {
        return self::ledger()->withLock(static fn (LockedLedger $l): FindingLifecycle => $l->lifecycleFor([$p])[$p]);
    }

    private static function confirmer(string $actor): ConfirmPublication
    {
        return new ConfirmPublication(self::ledger(), new PublicationGovernance(), new class (self::p($actor)) implements ActingPerson {
            public function __construct(private readonly PersonReference $person)
            {
            }

            public function current(): PersonReference
            {
                return $this->person;
            }
        });
    }

    public function test_a_fully_covering_published_correction_resolves_the_finding_in_the_same_act(): void
    {
        $t = Str::random(8);
        [$unit, $p, $f] = self::flagged($t);
        $q = self::existence("q-$t", "y-$t", $unit, [['2016-01-01', null]]);
        $this->assertSame($f, self::lifecycle($p)->openFindingAt(), 'drafted, submitted and approved: no effect yet');
        $before = self::lastPosition();

        $out = self::publisher("q-$t")->publish($q, corrects: $p);

        $this->assertSame(PublicationStatus::Published, $out->status, implode(',', array_map(static fn ($r) => $r->value, $out->refusals)));
        $this->assertSame($before + 1, $out->position);
        $this->assertSame($p, (int) DB::selectOne('SELECT corrects_position AS c FROM refgeo_publications WHERE position = ?', [$out->position])->c);
        $life = self::lifecycle($p);
        $this->assertNull($life->openFindingAt(), 'resolved by the published correction');
        $this->assertSame(ApprovalStatusKind::ApprovalDefective, $life->statusAt($out->position - 1)->kind, 'an earlier answer never changes');
        $this->assertSame([ApprovalStatusKind::Corrected, $out->position], [$life->statusAt($out->position)->kind, $life->statusAt($out->position)->correctedAt]);
        $this->assertSame([GovernanceRefusal::FindingNotOpen], self::confirmer("x2-$t")->confirm($f, str_repeat('0', 64))->refusals, 'no confirmation after correction');
    }

    public function test_several_adjacent_claims_may_cover_jointly(): void
    {
        $t = Str::random(8);
        [$unit, $p] = self::flagged($t);
        $q = self::existence("q-$t", "y-$t", $unit, [['2016-01-01', '2020-01-01'], ['2020-01-01', null]]);

        $this->assertSame(PublicationStatus::Published, self::publisher("q-$t")->publish($q, corrects: $p)->status);
        $this->assertNull(self::lifecycle($p)->openFindingAt());
    }

    public function test_a_partial_correction_is_refused_as_a_whole_and_may_be_published_only_as_an_ordinary_revision(): void
    {
        $t = Str::random(8);
        [$unit, $p, $f] = self::flagged($t);
        $before = self::lastPosition();
        $cases = ['ends too early (an open fact, a finite replacement)' => [['2016-01-01', '2030-01-01']], 'starts too late' => [['2016-06-01', null]], 'a gap' => [['2016-01-01', '2018-01-01'], ['2018-02-01', null]]];
        foreach ($cases as $why => $intervals) {
            $q = self::existence("q-$t", "y-$t", $unit, $intervals);
            $out = self::publisher("q-$t")->publish($q, corrects: $p);
            $this->assertSame([PublicationStatus::Refused, [GovernanceRefusal::CorrectionIncomplete]], [$out->status, $out->refusals], $why);
            $this->assertSame($before, self::lastPosition(), "{$why}: nothing written");
        }

        $ordinary = self::publisher("q-$t")->publish($q);
        $this->assertSame(PublicationStatus::Published, $ordinary->status, 'the partial fix as an ordinary revision');
        $this->assertNull(DB::selectOne('SELECT corrects_position AS c FROM refgeo_publications WHERE position = ?', [$ordinary->position])->c);
        $this->assertSame($f, self::lifecycle($p)->openFindingAt(), 'it resolves nothing: still flagged');
    }

    public function test_a_correction_link_needs_an_open_finding_on_a_revision_publication(): void
    {
        $t = Str::random(8);
        $unit = ReferenceUnitId::fromString((string) Str::uuid());
        $p = self::publisher("p-$t")->publish(self::existence("p-$t", "x-$t", $unit, [['2016-01-01', null]]))->position;
        $before = self::lastPosition();

        $out = self::publisher("q-$t")->publish(self::existence("q-$t", "y-$t", $unit, [['2016-01-01', null]]), corrects: $p);
        $this->assertSame([GovernanceRefusal::CorrectionWithoutOpenFinding], $out->refusals, 'never flagged');
        $this->assertSame($before, self::lastPosition());

        $u = Str::random(8);
        [$unit2, $p2, $f2] = self::flagged($u);
        $this->assertSame(PublicationStatus::Published, self::publisher("q-$u")->publish(self::existence("q-$u", "y-$u", $unit2, [['2016-01-01', null]]), corrects: $p2)->status);
        $again = self::publisher("r-$u")->publish(self::existence("r-$u", "z-$u", $unit2, [['2016-01-01', null]]), corrects: $p2);
        $this->assertSame([GovernanceRefusal::CorrectionWithoutOpenFinding], $again->refusals, 'already corrected: resolved once');
        $atFinding = self::publisher("r-$u")->publish(self::existence("r-$u", "z-$u", $unit2, [['2016-01-01', null]]), corrects: $f2);
        $this->assertSame([GovernanceRefusal::CorrectionWithoutOpenFinding], $atFinding->refusals, 'a notice is not a publication that can be corrected');
    }

    public function test_with_several_findings_the_open_one_is_resolved_and_a_corrected_publication_leaves_evaluation(): void
    {
        $t = Str::random(8);
        [$unit, $p, $f1] = self::flagged($t);
        self::consumer()->reversed("m-$t");                                      // f1 closed
        self::consumer()->merged("m2-$t", [self::p("x-$t"), self::p("p-$t")]);   // a new episode f2
        $f2 = self::lifecycle($p)->openFindingAt();
        $this->assertNotNull($f2);
        $this->assertGreaterThan($f1, $f2);

        $q = self::publisher("q-$t")->publish(self::existence("q-$t", "y-$t", $unit, [['2016-01-01', null]]), corrects: $p)->position;
        $status = self::lifecycle($p)->statusAt($q);
        $this->assertSame(ApprovalStatusKind::Corrected, $status->kind);
        $this->assertSame(['finding', 'closure', 'finding', 'correction'], array_column($status->history, 'event'));

        self::consumer()->reversed("m2-$t");
        self::consumer()->merged("m3-$t", [self::p("x-$t"), self::p("p-$t")]);
        $this->assertSame([], DB::select("SELECT position FROM refgeo_publications WHERE concerns_position = ? AND position > ?", [$p, $q]), 'corrected: no longer evaluated (full coverage, §8.2 row 8)');
    }

    public function test_a_failure_before_commit_leaves_neither_the_publication_nor_the_resolution(): void
    {
        $t = Str::random(8);
        [$unit, $p, $f] = self::flagged($t);
        $q = self::existence("q-$t", "y-$t", $unit, [['2016-01-01', null]]);
        $before = self::lastPosition();

        try {
            self::publisher("q-$t", self::ledger(static function (): void {
                throw new \RuntimeException('injected: before commit');
            }))->publish($q, corrects: $p);
            $this->fail('the injected failure was swallowed');
        } catch (\RuntimeException $e) {
            $this->assertSame('injected: before commit', $e->getMessage());
        }

        $this->assertSame([$before, $f], [self::lastPosition(), self::lifecycle($p)->openFindingAt()], 'nothing published, the finding still open');
        $this->assertSame(PublicationStatus::Published, self::publisher("q-$t")->publish($q, corrects: $p)->status, 'a retry succeeds');
    }

    /** Two corrections of one publication at once, and a confirmation racing a correction: the finding is resolved once. */
    public function test_concurrent_resolutions_resolve_the_finding_once(): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
        $holding = static fn (string $dir): PostgresPublicationLedger => self::ledger(static function () use ($dir): void {
            touch("$dir/holding");
            usleep(800_000);
        });

        $t = Str::random(8);
        [$unit, $p] = self::flagged($t);
        $q1 = self::existence("q-$t", "y-$t", $unit, [['2016-01-01', null]]);
        $q2 = self::existence("r-$t", "z-$t", $unit, [['2016-01-01', null]]);
        [$second, $waited] = self::whileHeld(
            static function (string $dir) use ($q1, $p, $t, $holding): void {
                self::publisher("q-$t", $holding($dir))->publish($q1, corrects: $p);
            },
            static fn () => self::publisher("r-$t")->publish($q2, corrects: $p),
        );
        $this->assertGreaterThan(400, $waited);
        $this->assertSame([GovernanceRefusal::CorrectionWithoutOpenFinding], $second->refusals, 'the later correction finds the finding resolved');
        $this->assertSame(1, (int) DB::selectOne('SELECT count(*) AS n FROM refgeo_publications WHERE corrects_position = ?', [$p])->n);

        $u = Str::random(8);
        [$unit2, $p2, $f2] = self::flagged($u);
        $id2 = self::repo()->get(RevisionId::fromString(DB::selectOne('SELECT revision_id AS r FROM refgeo_publications WHERE position = ?', [$p2])->r));
        self::appoint("c-$u");
        self::declare("c-$u", $id2->revision->id());
        $q3 = self::existence("q-$u", "y-$u", $unit2, [['2016-01-01', null]]);
        [$correction] = self::whileHeld(
            static function (string $dir) use ($u, $f2, $id2, $holding): void {
                (new ConfirmPublication($holding($dir), new PublicationGovernance(), new class (self::p("c-$u")) implements ActingPerson {
                    public function __construct(private readonly PersonReference $person)
                    {
                    }

                    public function current(): PersonReference
                    {
                        return $this->person;
                    }
                }))->confirm($f2, $id2->revision->currentVersion()->digest);
            },
            static fn () => self::publisher("q-$u")->publish($q3, corrects: $p2),
        );
        $this->assertSame([GovernanceRefusal::CorrectionWithoutOpenFinding], $correction->refusals, 'confirmed first: the correction link is refused');
    }
}
