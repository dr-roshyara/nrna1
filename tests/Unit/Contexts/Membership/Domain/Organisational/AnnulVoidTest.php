<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Admit;
use App\Contexts\Membership\Domain\Organisational\Annul;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RecordedFact;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Domain\Organisational\VoidFact;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * Correction slice C-1: Annul and Void in the existing kernel.
 * Contract `2026-10-09-membership-correction-slice-semantic-contract-draft.md` §14.3 / §14.8
 * (approved by the PO 2026-10-10); D-COR-2, D-COR-5 (A). PBDIGIT-268.
 */
final class AnnulVoidTest extends TestCase
{
    private static function admitted(int $at = 1): MemberHistory
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', $at, null, true));

        return $h;
    }

    // K-A1 / T1: Annul(Admit) is accepted; afterwards the admission is not live and no period remains.
    public function test_annulling_an_admission_removes_its_period(): void
    {
        $h = self::admitted();
        $h->commit($h->annul('X1', 'A1'));

        $this->assertSame([], $h->member->resolve()->periods());
        $this->assertFalse($h->member->resolve()->isMember(TimePoint::of(1)));
    }

    // K-V1 / T2: Void(Admit) is accepted; afterwards the admission is not live and no period remains.
    public function test_voiding_an_admission_removes_its_period(): void
    {
        $h = self::admitted();
        $h->commit($h->void('V1', 'A1'));

        $this->assertSame([], $h->member->resolve()->periods());
        $this->assertFalse($h->member->resolve()->isMember(TimePoint::of(1)));
    }

    // K-A2 / T11: Annul applies only to facts with decision provenance (R-2). Attested and rule facts are not decisions.
    public function test_annulling_a_fact_without_decision_provenance_is_refused(): void
    {
        foreach (['attested' => Provenance::attested('legacy-import', 'importer-1'), 'rule' => Provenance::rule('statute-7', 'v1')] as $kind => $provenance) {
            $h = MemberHistory::start();
            $h->commit($h->member->decideAdmit(FactId::fromString('A1'), TimePoint::of(1), TimePoint::infinity(), $provenance));
            $versionBefore = $h->member->version();

            $decision = $h->annul('X1', 'A1');

            $this->assertSame(RefusalReason::TargetHasNoDecisionProvenance, $decision->refusal(), $kind);
            $this->assertSame($versionBefore, $h->member->version(), "$kind: nothing appended");
        }
    }

    // Void is not restricted by provenance: a record that never reflected reality can be voided whatever its source.
    public function test_voiding_a_fact_without_decision_provenance_is_accepted(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->member->decideAdmit(FactId::fromString('A1'), TimePoint::of(1), TimePoint::infinity(), Provenance::attested('legacy-import', 'importer-1')));

        $this->assertTrue($h->void('V1', 'A1')->isAccepted());
    }

    // K-A3 / N-3: Annul(Void) is refused. Restoring a voided fact is Void(Void), not Annul.
    public function test_annulling_a_void_is_refused(): void
    {
        $h = self::admitted();
        $h->commit($h->void('V1', 'A1'));
        $versionBefore = $h->member->version();

        $decision = $h->annul('X1', 'V1');

        $this->assertSame(RefusalReason::AnnulOfVoidRefused, $decision->refusal());
        $this->assertSame($versionBefore, $h->member->version(), 'nothing appended');
        $this->assertSame([], $h->member->resolve()->periods(), 'the void still holds');
    }

    // P-2: an Annul must carry its decision instant; the kernel never substitutes the current time.
    public function test_annulling_without_decided_at_is_refused_and_changes_nothing(): void
    {
        $h = self::admitted();
        $logBefore = $h->member->log();
        $resolvedBefore = $h->member->resolve();

        $decision = $h->annul('X1', 'A1', null);

        $this->assertSame(RefusalReason::DecidedAtRequired, $decision->refusal());
        $this->assertFalse($decision->isAccepted());
        $this->assertSame($logBefore, $h->member->log(), 'no fact is appended');
        $this->assertEquals($resolvedBefore, $h->member->resolve(), 'resolved state unchanged');
        $this->assertTrue($h->member->resolve()->isMember(TimePoint::of(1)));

        $this->expectException(\LogicException::class);
        $decision->fact();
    }

    // Check order: a reused fact id is refused before anything is said about the target.
    public function test_a_reused_fact_id_is_refused_first(): void
    {
        $h = self::admitted();

        $this->assertSame(RefusalReason::DuplicateFactId, $h->annul('A1', 'ZZ', null)->refusal());
        $this->assertSame(RefusalReason::DuplicateFactId, $h->void('A1', 'ZZ')->refusal());
    }

    // P-3: a reversal targets a fact of this Member; an unknown target is refused, also before DecidedAtRequired.
    public function test_reversing_an_unknown_fact_is_refused(): void
    {
        $h = self::admitted();

        $this->assertSame(RefusalReason::TargetNotFound, $h->annul('X1', 'ZZ')->refusal());
        $this->assertSame(RefusalReason::TargetNotFound, $h->annul('X1', 'ZZ', null)->refusal());
        $this->assertSame(RefusalReason::TargetNotFound, $h->void('V1', 'ZZ')->refusal());
        $this->assertSame(1, $h->member->version());
    }

    // D-COR-2: Annul / Void of a fact that is no longer live is refused (fact liveness, not period activity).
    public function test_reversing_a_fact_that_is_not_live_is_refused(): void
    {
        $voided = self::admitted();
        $voided->commit($voided->void('V1', 'A1'));
        $this->assertSame(RefusalReason::TargetNotLive, $voided->annul('X1', 'A1')->refusal());
        $this->assertSame(RefusalReason::TargetNotLive, $voided->void('V2', 'A1')->refusal());

        $annulled = self::admitted();
        $annulled->commit($annulled->annul('X1', 'A1'));
        $this->assertSame(RefusalReason::TargetNotLive, $annulled->annul('X2', 'A1')->refusal());
        $this->assertSame(RefusalReason::TargetNotLive, $annulled->void('V1', 'A1')->refusal());
    }

    // P-3: an admission whose period has ended is still a live fact, so it can be annulled.
    public function test_the_admission_of_an_ended_period_is_still_live(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1));
        $h->commit($h->end('E1', 'A1', 5));

        $this->assertTrue($h->annul('X1', 'A1')->isAccepted());
    }

    // Restoration (act §4, D-COR-2): reversing a live reversal makes its target live again, and its period returns (D-COR-5 (A)).
    public function test_reversing_a_reversal_restores_the_period(): void
    {
        $cases = [
            'Annul(Annul)' => static fn (MemberHistory $h) => $h->commit($h->annul('X1', 'A1'))->commit($h->annul('X2', 'X1')),
            'Void(Annul)' => static fn (MemberHistory $h) => $h->commit($h->annul('X1', 'A1'))->commit($h->void('V1', 'X1')),
            'Void(Void)' => static fn (MemberHistory $h) => $h->commit($h->void('V1', 'A1'))->commit($h->void('V2', 'V1')),
        ];
        foreach ($cases as $name => $history) {
            $h = self::admitted(3);
            $before = $h->member->resolve();

            $history($h);

            $this->assertEquals($before, $h->member->resolve(), "$name restores the resolved state");
            $this->assertTrue($h->member->resolve()->isMember(TimePoint::of(3)), $name);
        }
    }

    // Restoration nests: Annul(Annul(Annul)) leaves the admission set aside again.
    public function test_a_third_reversal_sets_the_admission_aside_again(): void
    {
        $h = self::admitted();
        $h->commit($h->annul('X1', 'A1'))->commit($h->annul('X2', 'X1'))->commit($h->annul('X3', 'X2'));

        $this->assertSame([], $h->member->resolve()->periods());
    }

    /** Admit p0 at 1, End p0 at 5, Admit p1 at 10 (re-entry as a new period). */
    private static function reEntered(): MemberHistory
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1))->commit($h->end('E1', 'A1', 5))->commit($h->admit('A2', 10));

        return $h;
    }

    // T8 / K-G1 / D-COR-5 (A): annulling p0's End would revive p0 over p1; G* refuses it (INV-9). No implicit consequence.
    public function test_annulling_an_end_whose_revival_overlaps_a_later_period_is_refused(): void
    {
        $h = self::reEntered();

        $this->assertSame(RefusalReason::OverlappingMembershipPeriods, $h->annul('X1', 'E1')->refusal());
        $this->assertSame(3, $h->member->version());
    }

    // T9 / K-A1, K-G1: once p1's admission is annulled, p0's End may be annulled; p0 = [1, ∞).
    public function test_annulling_the_later_admission_first_lets_the_end_be_annulled(): void
    {
        $h = self::reEntered();
        $h->commit($h->annul('X1', 'A2'))->commit($h->annul('X2', 'E1'));

        $periods = $h->member->resolve()->periods();
        $this->assertCount(1, $periods);
        $this->assertSame('A1', $periods[0]->id()->admitFactId()->value());
        $this->assertSame(1, $periods[0]->baseClaim()->start()->ordinal());
        $this->assertTrue($periods[0]->baseClaim()->isOpenEnded());
    }

    // T10 / K-R3: restoring p1's admission would overlap the revived p0; G* refuses it (INV-9).
    public function test_restoring_an_admission_that_would_overlap_a_revived_period_is_refused(): void
    {
        $h = self::reEntered();
        $h->commit($h->annul('X1', 'A2'))->commit($h->annul('X2', 'E1'));

        $this->assertSame(RefusalReason::OverlappingMembershipPeriods, $h->annul('X3', 'X1')->refusal());
        $this->assertSame(5, $h->member->version());
    }

    // K-E1 (R-15): an accepted Annul decision carries the FactAnnulled data; emission is C-3. Sensitivity: M1, M3, M4.
    public function test_an_accepted_annul_carries_its_fact_data(): void
    {
        // The log is built directly, so the Annul is the only decision taken (no decide / commit in the setup).
        $member = Member::fromLog(
            MemberId::fromString('M1'),
            PersonRef::fromString('P1'),
            TenantId::fromString('O1'),
            RecordedFact::of(1, Instant::of(1000), new Admit(FactId::fromString('A1'), TimePoint::of(1), TimePoint::infinity(), MemberHistory::provenance())),
        );
        $provenance = Provenance::decision('registrar-2');

        $decision = $member->decideAnnul(FactId::fromString('X1'), FactId::fromString('A1'), $provenance, Instant::of(900));

        $this->assertTrue($decision->isAccepted());
        $fact = $decision->fact();
        $this->assertInstanceOf(Annul::class, $fact);
        $this->assertSame('X1', $fact->id()->value());
        $this->assertSame('A1', $fact->target()->value());
        $this->assertEquals($provenance, $fact->provenance());
        $this->assertEquals(Instant::of(900), $fact->decidedAt());
        $this->assertSame(1, $decision->basedOnVersion());
    }

    // K-E1 (R-15): an accepted Void decision carries the FactVoided data, decidedAt exactly as given. Sensitivity: M2.
    public function test_an_accepted_void_carries_its_fact_data(): void
    {
        foreach ([900, null] as $decidedAt) {
            $h = self::admitted();
            $provenance = Provenance::attested('legacy-import', 'importer-1');
            $instant = $decidedAt === null ? null : Instant::of($decidedAt);

            $decision = $h->member->decideVoid(FactId::fromString('V1'), FactId::fromString('A1'), $provenance, $instant);

            $this->assertTrue($decision->isAccepted());
            $fact = $decision->fact();
            $this->assertInstanceOf(VoidFact::class, $fact);
            $this->assertSame('V1', $fact->id()->value());
            $this->assertSame('A1', $fact->target()->value());
            $this->assertEquals($provenance, $fact->provenance());
            $this->assertEquals($instant, $fact->decidedAt());
            $this->assertSame(1, $decision->basedOnVersion());
        }
    }

    // P-KNOWN / K-H1 (R-12): a reversal never rewrites what was known before it was recorded.
    public function test_a_reversal_leaves_earlier_knowledge_unchanged(): void
    {
        foreach (['Annul' => fn (MemberHistory $h) => $h->annul('X1', 'A1'), 'Void' => fn (MemberHistory $h) => $h->void('V1', 'A1')] as $kind => $reverse) {
            $h = MemberHistory::start();
            $h->commit($h->admit('A1', 1), 1000);
            $knownBefore = $h->member->resolve();

            $h->commit($reverse($h), 2000);

            foreach ([1000, 1500, 1999] as $tau) {
                $this->assertEquals($knownBefore, $h->member->knownAt(Instant::of($tau)), "$kind: KnownAt($tau) unchanged");
            }
            $this->assertSame([], $h->member->knownAt(Instant::of(2000))->periods(), "$kind: known from its recording instant");
        }
    }
}
