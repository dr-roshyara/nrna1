<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * D-5(a) Z1 / D-5(b) (PO decisions 2026-10-11): every new Admit and End carries an explicit, caller-supplied
 * decision date (no clock in the domain); the effective date may not lie after it; backdating stays subject
 * to the existing invariants. PBDIGIT-271, Slice 2a step 5.
 */
final class FutureDatingTest extends TestCase
{
    private static function admit(MemberHistory $h, string $id, int $at, int $decisionDate, ?Provenance $provenance = null): Decision
    {
        return $h->member->decideAdmit(FactId::fromString($id), TimePoint::of($at), TimePoint::of($decisionDate), TimePoint::infinity(), $provenance ?? MemberHistory::admissionProvenance());
    }

    private static function end(MemberHistory $h, string $id, string $period, int $at, int $decisionDate, string $kind = EndKind::RESIGNATION): Decision
    {
        return $h->member->decideEnd(FactId::fromString($id), PeriodId::fromString($period), TimePoint::of($at), TimePoint::of($decisionDate), EndKind::fromString($kind), MemberHistory::provenance());
    }

    private static function refusal(Decision $d): ?string
    {
        return $d->isAccepted() ? null : $d->refusal()->value;
    }

    public function test_a_future_dated_admission_is_refused(): void
    {
        $this->assertSame('effective_date_after_decision_date', self::refusal(self::admit(MemberHistory::start(), 'A1', 11, 10)));
    }

    public function test_an_admission_on_or_before_its_decision_date_is_accepted(): void
    {
        $this->assertTrue(self::admit(MemberHistory::start(), 'A1', 10, 10)->isAccepted(), 'same day');
        $this->assertTrue(self::admit(MemberHistory::start(), 'A1', 3, 10)->isAccepted(), 'backdated');
    }

    public function test_backdating_into_a_live_lifetime_is_still_refused_by_the_invariant(): void
    {
        $h = MemberHistory::start();
        $h->commit(self::admit($h, 'A1', 1, 1));

        $this->assertSame('overlapping_membership_periods', self::refusal(self::admit($h, 'A2', 3, 10)));
    }

    public function test_a_future_dated_end_is_refused_and_a_backdated_end_accepted(): void
    {
        $h = MemberHistory::start();
        $h->commit(self::admit($h, 'A1', 1, 1));

        $this->assertSame('effective_date_after_decision_date', self::refusal(self::end($h, 'E1', 'A1', 9, 8)));
        $this->assertTrue(self::end($h, 'E1', 'A1', 8, 8)->isAccepted(), 'same day');
        $this->assertTrue(self::end($h, 'E1', 'A1', 4, 8)->isAccepted(), 'backdated');
    }

    public function test_guard_precedence_around_the_decision_date(): void
    {
        // Plan §4.1. Admit: … AdmissionBasisMissing → EffectiveDateAfterDecisionDate → G*.
        $h = MemberHistory::start();
        $h->commit(self::admit($h, 'A1', 1, 1));
        $this->assertSame('admission_basis_missing', self::refusal(self::admit($h, 'A2', 11, 10, Provenance::decision('registrar-1'))));
        $this->assertSame('effective_date_after_decision_date', self::refusal(self::admit($h, 'A2', 11, 10)), 'before the overlap invariant');

        // End: … EndNotAfterPeriodStart → LegacyEndRequiresAttestedHistory → EffectiveDateAfterDecisionDate.
        $this->assertSame('end_not_after_period_start', self::refusal(self::end($h, 'E1', 'A1', 1, 0)));
        $this->assertSame('legacy_end_requires_attested_history', self::refusal(self::end($h, 'E1', 'A1', 9, 8, EndKind::LEGACY)));
    }

    // Hardening H1-a / H1-b (Human PO / ARB, 2026-10-11): a decision date is a calendar date and an Admit / End
    // takes effect at a finite time. An infinite value is invalid input (a precondition, InvalidArgumentException),
    // not a business refusal; TimePoint keeps ∞ for open-ended terms.

    public function test_an_infinite_decision_date_on_an_admission_is_invalid_input(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        MemberHistory::start()->member->decideAdmit(FactId::fromString('A1'), TimePoint::of(5000), TimePoint::infinity(), null, MemberHistory::admissionProvenance());
    }

    public function test_an_infinite_effective_date_on_an_admission_is_invalid_input(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        MemberHistory::start()->member->decideAdmit(FactId::fromString('A1'), TimePoint::infinity(), TimePoint::of(100), null, MemberHistory::admissionProvenance());
    }

    public function test_an_infinite_decision_date_on_an_end_is_invalid_input(): void
    {
        $h = MemberHistory::start();
        $h->commit(self::admit($h, 'A1', 1, 1));

        $this->expectException(\InvalidArgumentException::class);
        $h->member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(5000), TimePoint::infinity(), EndKind::fromString(EndKind::RESIGNATION), MemberHistory::provenance());
    }

    public function test_an_infinite_effective_date_on_an_end_is_invalid_input(): void
    {
        $h = MemberHistory::start();
        $h->commit(self::admit($h, 'A1', 1, 1));

        $this->expectException(\InvalidArgumentException::class);
        $h->member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::infinity(), TimePoint::of(100), EndKind::fromString(EndKind::RESIGNATION), MemberHistory::provenance());
    }

    public function test_the_finite_date_precondition_precedes_every_refusal(): void
    {
        // A duplicate fact id would otherwise be the first refusal; invalid input is rejected before any outcome.
        $h = MemberHistory::start();
        $h->commit(self::admit($h, 'A1', 1, 1));

        $this->expectException(\InvalidArgumentException::class);
        $h->member->decideAdmit(FactId::fromString('A1'), TimePoint::of(5), TimePoint::infinity(), null, MemberHistory::admissionProvenance());
    }
}
