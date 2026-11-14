<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\ReferenceGeography\Application\ConfirmationStatus;
use App\Contexts\ReferenceGeography\Application\ConfirmPublication;
use App\Contexts\ReferenceGeography\Application\IdentityNoticeConsumer;
use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\LockedLedger;
use App\Contexts\ReferenceGeography\Domain\ApprovalStatusKind;
use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\Revision;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use Tests\Support\ReferenceGeography\CompetentInstrument;
use App\Contexts\ReferenceGeography\Domain\FindingLifecycle;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublicationLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;

/**
 * PBDIGIT-346 G-6c-4 (design revision 3 §7, §8, §8.4 (a); accepted 2026-11-14): an eligible, independent steward
 * confirms an OPEN D-90 finding; the confirmation is an appended notice under the one publication lock, changes no
 * claim and no earlier row, and makes the confirmer the counted approver (re-evaluated by later merges). Tests B2, B6
 * (confirmation side), B10 (status) and the Principal's list for G-6c-4. Fresh people per test.
 */
#[Group('refgeo-race')]
final class ConfirmPublicationTest extends RefgeoPostgresTestCase
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

    private static function confirmer(string $actor, ?PostgresPublicationLedger $ledger = null): ConfirmPublication
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

        return new ConfirmPublication($ledger ?? self::ledger(), new PublicationGovernance(), $acting);
    }

    /**
     * Published (assessed by p, approved by x), then Identity finds x is p: an open finding. Stewards c and d are
     * appointed and declared without interest. Returns [revision id, publication position, finding position, digest].
     *
     * @return array{RevisionId, int, int, string}
     */
    private static function flagged(string $t): array
    {
        $id = self::revision("p-$t", "x-$t");
        $pub = self::publisher("p-$t")->publish($id)->position;
        self::appoint("c-$t", "d-$t");
        self::declare("c-$t", $id);
        self::declare("d-$t", $id);
        self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
        $f = (int) DB::selectOne("SELECT position FROM refgeo_publications WHERE notice_kind = 'finding' AND concerns_position = ?", [$pub])->position;

        return [$id, $pub, $f, self::repo()->get($id)->revision->currentVersion()->digest];
    }

    private static function lifecycle(int $pub): FindingLifecycle
    {
        return self::ledger()->withLock(static fn (LockedLedger $l): FindingLifecycle => $l->lifecycleFor([$pub])[$pub]);
    }

    private static function confirmations(int $f): int
    {
        return (int) DB::selectOne("SELECT count(*) AS n FROM refgeo_publications WHERE notice_kind = 'confirmation' AND concerns_position = ?", [$f])->n;
    }

    public function test_an_independent_steward_confirms_an_open_finding_as_an_appended_notice(): void
    {
        $t = Str::random(8);
        [, $pub, $f, $digest] = self::flagged($t);
        $original = (array) DB::selectOne('SELECT * FROM refgeo_publications WHERE position = ?', [$pub]);
        $before = self::lastPosition();

        $out = self::confirmer("c-$t")->confirm($f, $digest);

        $this->assertSame(ConfirmationStatus::Confirmed, $out->status, implode(',', array_map(static fn ($r) => $r->value, $out->refusals)));
        $this->assertSame($before + 1, $out->position, 'the next gap-free position');
        $row = DB::selectOne('SELECT notice_kind, concerns_position, trim(confirmed_by) AS by, confirmed_digest, roster_version FROM refgeo_publications WHERE position = ?', [$out->position]);
        $this->assertSame(['confirmation', $f, self::p("c-$t")->value(), $digest, (int) self::store()->current()->version()],
            [$row->notice_kind, (int) $row->concerns_position, $row->by, $row->confirmed_digest, (int) $row->roster_version]);
        $this->assertEquals($original, (array) DB::selectOne('SELECT * FROM refgeo_publications WHERE position = ?', [$pub]), 'the publication itself is unchanged');
        $this->assertNotNull(DB::selectOne("SELECT 1 AS x FROM refgeo_publication_persons WHERE position = ? AND person = ? AND role = 'confirmer'", [$pub, self::p("c-$t")->value()]),
            'the confirmer is indexed with the publication, so later merges re-evaluate it (§8.4 (a))');
    }

    public function test_ineligible_or_dependent_confirmers_and_a_wrong_digest_are_refused_and_write_nothing(): void
    {
        $t = Str::random(8);
        [, , $f, $digest] = self::flagged($t);
        $before = self::lastPosition();
        $cases = [
            'the defective approver' => ["x-$t", $digest, [GovernanceRefusal::ConfirmerNotIndependent]],
            'an excluded person (the assessor)' => ["p-$t", $digest, [GovernanceRefusal::ConfirmerNotIndependent]],
            'a person not appointed' => ["nobody-$t", $digest, [GovernanceRefusal::NotAppointed, GovernanceRefusal::ConflictDeclarationMissing]],
            'a stale or wrong digest' => ["c-$t", str_repeat('0', 64), [GovernanceRefusal::DigestMismatch]],
        ];
        foreach ($cases as $why => [$actor, $d, $refusals]) {
            $out = self::confirmer($actor)->confirm($f, $d);
            $this->assertSame(ConfirmationStatus::Refused, $out->status, $why);
            $this->assertSame($refusals, $out->refusals, $why);
        }
        $this->assertSame($before, self::lastPosition(), 'nothing written');
    }

    /**
     * G-6c-5 follow-up (database mutation K4 survived the earlier fixtures: every excluded person there was also the
     * defective approver's alias). Here e only attached evidence: e is in excluded(r) but NOT linked to the approver x,
     * so only the exclusion rule can refuse e.
     */
    public function test_a_second_preparer_unrelated_to_the_approver_is_refused_by_exclusion(): void
    {
        $t = Str::random(8);
        self::ensureCompetence();
        $id = RevisionId::fromString((string) Str::uuid());
        self::appoint("p-$t", "x-$t", "e-$t");
        foreach (["p-$t", "x-$t", "e-$t"] as $who) {
            self::declare($who, $id);
        }
        $r = Revision::prepare($id, self::p("p-$t"));
        $r->addClaim(new Claim(ClaimId::fromString((string) Str::uuid()), SlotKey::existence(ReferenceUnitId::fromString((string) Str::uuid())), ClaimValue::of('exists'),
            new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Instrument, 'act')), self::p("p-$t"));
        $r->attachEvidence(CompetentInstrument::evidence('act'), self::p("p-$t"));
        $r->attachEvidence(CompetentInstrument::evidence('act'), self::p("e-$t"));   // e: a second preparer, excluded
        self::submitAndApprove($r, "p-$t", "x-$t");
        $pub = self::publisher("p-$t")->publish($id)->position;
        self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
        $f = (int) DB::selectOne("SELECT position FROM refgeo_publications WHERE notice_kind = 'finding' AND concerns_position = ?", [$pub])->position;

        $out = self::confirmer("e-$t")->confirm($f, self::repo()->get($id)->revision->currentVersion()->digest);

        $this->assertFalse(self::store()->current()->knownSameAt(self::p("e-$t"), self::p("x-$t"), self::store()->current()->version()), 'e is not the approver');
        $this->assertSame([GovernanceRefusal::ConfirmerNotIndependent], $out->refusals);
    }

    public function test_a_materially_conflicted_steward_is_refused(): void
    {
        $t = Str::random(8);
        $id = self::revision("p-$t", "x-$t", judgment: true);
        $pub = self::publisher("p-$t")->publish($id)->position;
        self::appoint("c-$t");
        self::declare("c-$t", $id, true);   // an interest in a judgment matter
        self::consumer()->merged("m-$t", [self::p("x-$t"), self::p("p-$t")]);
        $f = (int) DB::selectOne("SELECT position FROM refgeo_publications WHERE notice_kind = 'finding' AND concerns_position = ?", [$pub])->position;

        $out = self::confirmer("c-$t")->confirm($f, self::repo()->get($id)->revision->currentVersion()->digest);

        $this->assertSame([GovernanceRefusal::MateriallyConflicted], $out->refusals);
    }

    public function test_a_finding_resolved_by_confirmation_closure_or_published_correction_cannot_be_confirmed(): void
    {
        $t = Str::random(8);
        [, , $f, $digest] = self::flagged($t);
        $this->assertSame(ConfirmationStatus::Confirmed, self::confirmer("c-$t")->confirm($f, $digest)->status);
        $before = self::lastPosition();
        $this->assertSame([GovernanceRefusal::FindingNotOpen], self::confirmer("d-$t")->confirm($f, $digest)->refusals, 'confirmed already');
        $this->assertSame([GovernanceRefusal::FindingNotOpen], self::confirmer("c-$t")->confirm($f, $digest)->refusals, 'a repeated request');
        $this->assertSame([$before, 1], [self::lastPosition(), self::confirmations($f)], 'no second confirmation');

        $u = Str::random(8);
        [, , $g, $digestG] = self::flagged($u);
        self::consumer()->reversed("m-$u");
        $this->assertSame([GovernanceRefusal::FindingNotOpen], self::confirmer("c-$u")->confirm($g, $digestG)->refusals, 'closed by the reversal');

        $v = Str::random(8);
        [, $pubH, $h, $digestH] = self::flagged($v);
        // a PUBLISHED correction of the publication (G-6c-5 will write it through PublishRevision; here the row itself)
        DB::transaction(static function () use ($pubH): void {
            $s = DB::selectOne('SELECT last_position, last_roster_version FROM refgeo_sequence WHERE id = 1 FOR UPDATE');
            DB::table('refgeo_publications')->insert(['position' => $s->last_position + 1, 'kind' => 'revision', 'revision_id' => (string) Str::uuid(), 'content_version' => 1,
                'corrects_position' => $pubH, 'roster_version' => $s->last_roster_version, 'governance_rule' => 'test-fixture', 'recorded_at' => DB::raw('clock_timestamp()')]);
        });
        $this->assertSame([GovernanceRefusal::FindingNotOpen], self::confirmer("c-$v")->confirm($h, $digestH)->refusals, 'corrected');

        $this->assertSame([GovernanceRefusal::FindingNotOpen], self::confirmer("c-$v")->confirm($pubH, $digestH)->refusals, 'a publication is not a finding');
    }

    public function test_status_before_the_confirmation_is_unchanged_and_after_it_is_confirmed(): void
    {
        $t = Str::random(8);
        [, $pub, $f, $digest] = self::flagged($t);
        $c = self::confirmer("c-$t")->confirm($f, $digest)->position;

        $life = self::lifecycle($pub);
        $this->assertSame(ApprovalStatusKind::Approved, $life->statusAt($f - 1)->kind);
        $this->assertSame(ApprovalStatusKind::ApprovalDefective, $life->statusAt($f)->kind);
        $this->assertSame(ApprovalStatusKind::ApprovalDefective, $life->statusAt($c - 1)->kind, 'an earlier answer never changes');
        $this->assertSame(ApprovalStatusKind::Confirmed, $life->statusAt($c)->kind);
    }

    public function test_a_merge_showing_the_confirmer_is_an_excluded_person_raises_a_new_finding_and_its_reversal_closes_it(): void
    {
        $t = Str::random(8);
        [, $pub, $f, $digest] = self::flagged($t);
        $c = self::confirmer("c-$t")->confirm($f, $digest)->position;

        self::consumer()->merged("mc-$t", [self::p("c-$t"), self::p("p-$t")]);
        $new = DB::select("SELECT position FROM refgeo_publications WHERE notice_kind = 'finding' AND concerns_position = ? AND position > ?", [$pub, $c]);
        $this->assertCount(1, $new, 'a new episode: the confirmer is the counted approver (§8.4 (a))');
        $this->assertSame(ApprovalStatusKind::ApprovalDefective, self::lifecycle($pub)->statusAt(self::lastPosition())->kind);
        $this->assertSame([GovernanceRefusal::ConfirmerNotIndependent], self::confirmer("c-$t")->confirm((int) $new[0]->position, $digest)->refusals, 'c may not confirm its own defect');

        self::consumer()->reversed("mc-$t");
        $this->assertSame(['closure'], array_column(DB::select('SELECT notice_kind FROM refgeo_publications WHERE concerns_position = ?', [(int) $new[0]->position]), 'notice_kind'));
        $this->assertSame(ApprovalStatusKind::FindingClosed, self::lifecycle($pub)->statusAt(self::lastPosition())->kind);
    }

    public function test_a_failure_before_commit_leaves_no_confirmation_and_no_index_row(): void
    {
        $t = Str::random(8);
        [, $pub, $f, $digest] = self::flagged($t);
        $before = self::lastPosition();

        try {
            self::confirmer("c-$t", self::ledger(static function (): void {
                throw new \RuntimeException('injected: before commit');
            }))->confirm($f, $digest);
            $this->fail('the injected failure was swallowed');
        } catch (\RuntimeException $e) {
            $this->assertSame('injected: before commit', $e->getMessage());
        }

        $this->assertSame([$before, 0], [self::lastPosition(), self::confirmations($f)]);
        $this->assertNull(DB::selectOne("SELECT 1 AS x FROM refgeo_publication_persons WHERE position = ? AND role = 'confirmer'", [$pub]));
        $this->assertSame(ConfirmationStatus::Confirmed, self::confirmer("c-$t")->confirm($f, $digest)->status, 'nothing half-done blocks a retry');
    }

    /** Two stewards confirm the same finding at once: one lock, so the second waits and finds it no longer open. */
    public function test_concurrent_confirmations_resolve_a_finding_once(): void
    {
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
        $t = Str::random(8);
        [, , $f, $digest] = self::flagged($t);

        [$second, $waited] = self::whileHeld(
            static function (string $dir) use ($t, $f, $digest): void {
                self::confirmer("c-$t", self::ledger(static function () use ($dir): void {
                    touch("$dir/holding");
                    usleep(800_000);
                }))->confirm($f, $digest);
            },
            static fn () => self::confirmer("d-$t")->confirm($f, $digest),
        );

        $this->assertGreaterThan(400, $waited, 'the second confirmation waited for the first');
        $this->assertSame([GovernanceRefusal::FindingNotOpen], $second->refusals);
        $this->assertSame(1, self::confirmations($f));
    }
}
