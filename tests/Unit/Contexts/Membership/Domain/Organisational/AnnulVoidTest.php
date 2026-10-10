<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
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
}
