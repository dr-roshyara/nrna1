<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Admit;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\IntervalSet;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\ProvenanceKind;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * Slice 0 contract §15–§17 (Admit, identity, terms, temporal coordinates). PBDIGIT-258.
 */
final class AdmissionTest extends TestCase
{
    private static function t(int $o): TimePoint
    {
        return TimePoint::of($o);
    }

    public function test_an_empty_member_can_be_admitted(): void
    {
        $h = MemberHistory::start();
        $d = $h->admit('A1', 1, 10);

        $this->assertTrue($d->isAccepted());
        $this->assertSame(0, $d->basedOnVersion());
    }

    public function test_admission_at_the_end_of_an_ended_period_is_accepted(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1, 10))->commit($h->end('E1', 'A1', 5));

        $this->assertTrue($h->admit('A2', 5, 20)->isAccepted());
    }

    public function test_admission_after_an_ended_period_is_accepted(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1, 10))->commit($h->end('E1', 'A1', 5));

        $this->assertTrue($h->admit('A2', 6, 20)->isAccepted());
    }

    public function test_backdated_admission_into_a_live_period_is_refused(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 3, 10));

        $d = $h->admit('A2', 1, 10);

        $this->assertFalse($d->isAccepted());
        $this->assertSame(RefusalReason::OverlappingMembershipPeriods, $d->refusal());
    }

    public function test_admission_before_the_end_of_an_existing_period_is_refused(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1, 10))->commit($h->end('E1', 'A1', 8));

        $this->assertSame(RefusalReason::OverlappingMembershipPeriods, $h->admit('A2', 6, 20)->refusal());
    }

    public function test_an_initial_term_must_end_after_the_admission(): void
    {
        $h = MemberHistory::start();

        $this->assertSame(RefusalReason::InvalidInitialTerm, $h->admit('A1', 5, 5)->refusal());
        $this->assertSame(RefusalReason::InvalidInitialTerm, $h->admit('A1', 5, 3)->refusal());
    }

    public function test_a_fact_id_cannot_be_used_twice(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1, 10))->commit($h->end('E1', 'A1', 5));

        $this->assertSame(RefusalReason::DuplicateFactId, $h->admit('A1', 6, 20)->refusal());
    }

    public function test_the_period_id_is_the_admit_fact_id(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1, 10));

        $periods = $h->member->resolve()->periods();
        $this->assertCount(1, $periods);
        $this->assertTrue(PeriodId::fromString('A1')->equals($periods[0]->id()));
    }

    public function test_re_entry_keeps_the_member_and_opens_a_new_period(): void
    {
        $h = MemberHistory::start('M1');
        $h->commit($h->admit('A1', 1, null, true))->commit($h->end('E1', 'A1', 5))->commit($h->admit('A2', 6, null, true));

        $resolved = $h->member->resolve();
        $this->assertSame('M1', $h->member->id()->value());
        $this->assertSame(['A1', 'A2'], array_map(static fn ($p) => $p->id()->value(), $resolved->periods()));
        $this->assertTrue($resolved->period(PeriodId::fromString('A1'))->lifetime()->equals(IntervalSet::of(Interval::of(self::t(1), self::t(5)))));
        $this->assertTrue($resolved->period(PeriodId::fromString('A2'))->inLifetime(self::t(6)));
        $this->assertFalse($resolved->isMember(self::t(5)), 'the gap between the periods is preserved');
    }

    public function test_decisions_never_change_the_holder_or_organisation(): void
    {
        $h = MemberHistory::start('M1', 'P1', 'O1');
        $h->commit($h->admit('A1', 1, 10))->commit($h->end('E1', 'A1', 5))->commit($h->admit('A2', 7, 9));

        $this->assertSame('P1', $h->member->holder()->value());
        $this->assertSame('O1', $h->member->organisation()->value());
    }

    public function test_term_case_a_finite_initial_term(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 2, 6));
        $r = $h->member->resolve();
        $p = $r->period(PeriodId::fromString('A1'));

        $this->assertTrue($p->isInForce(self::t(2)));
        $this->assertTrue($p->isInForce(self::t(5)));
        $this->assertFalse($p->isInForce(self::t(6)));
        $this->assertTrue($p->isLapsed(self::t(6)), 'after the term the member is lapsed, not ended');
        $this->assertFalse($p->isEnded(self::t(6)));
    }

    public function test_term_case_b_unlimited_initial_term(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 2, null, true));
        $r = $h->member->resolve();

        $this->assertTrue($r->isMember(self::t(2)));
        $this->assertTrue($r->isMember(self::t(PHP_INT_MAX - 1)));
    }

    public function test_term_case_c_admission_without_an_initial_term_is_lapsed(): void
    {
        // C-S0-2 (Principal Reviewer decision): no default term, and no ∞.
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 2));
        $r = $h->member->resolve();
        $p = $r->period(PeriodId::fromString('A1'));

        $this->assertTrue($p->inLifetime(self::t(2)));
        $this->assertTrue($p->term()->isEmpty());
        $this->assertTrue($p->isLapsed(self::t(2)));
        $this->assertFalse($p->isInForce(self::t(2)));
        $this->assertFalse($r->isMember(self::t(2)));
    }

    public function test_effective_decided_and_recorded_times_are_independent(): void
    {
        $h = MemberHistory::start();
        $fact = $h->member->decideAdmit(
            FactId::fromString('A1'),
            TimePoint::of(10),
            MemberHistory::decisionDate(),
            null,
            MemberHistory::admissionProvenance(),
            Instant::of(500),
        )->fact();
        $this->assertInstanceOf(Admit::class, $fact);
        $this->assertTrue(TimePoint::of(10)->equals($fact->effectiveAt()));
        $this->assertTrue(Instant::of(500)->equals($fact->decidedAt()));

        // backdated: recorded long after it took effect
        $h->commit($h->member->decideAdmit(FactId::fromString('A1'), TimePoint::of(10), MemberHistory::decisionDate(), null, MemberHistory::admissionProvenance()), 9000);
        $this->assertTrue(Instant::of(9000)->equals($h->member->log()[0]->recordedAt()));
        $this->assertSame(1, $h->member->log()[0]->seq());
    }

    public function test_provenance_is_carried_unchanged(): void
    {
        $evidence = AuthorityEvidence::of('user-7', 'application_role:admin', 'interim:org-role', 'v1', true, Instant::of(42), false);
        $provenance = Provenance::decision('user-7', $evidence, 'declaration-123');
        $h = MemberHistory::start();
        $fact = $h->member->decideAdmit(FactId::fromString('A1'), TimePoint::of(1), MemberHistory::decisionDate(), null, $provenance)->fact();

        $this->assertSame(ProvenanceKind::Decision, $fact->provenance()->kind());
        $this->assertSame($evidence, $fact->provenance()->authorityEvidence());
        $this->assertFalse($fact->provenance()->authorityEvidence()->competenceVerified());
        $this->assertSame('declaration-123', $fact->provenance()->declarationRef());
    }

    public function test_provenance_is_structurally_validated(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Provenance::decision(' ');
    }

    public function test_rule_and_attested_provenance_exist(): void
    {
        $this->assertSame(ProvenanceKind::Rule, Provenance::rule('statute-§4', '2026-1')->kind());
        $this->assertSame(ProvenanceKind::Attested, Provenance::attested('legacy-members', 'import-job-1')->kind());
    }
}
