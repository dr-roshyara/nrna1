<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * D-1 = C1 (PO decision 2026-10-11): every new Admit carries exactly one recognised basis —
 * a declaration reference, a statutory rule, or attested history (migration only). The uncovered
 * case is decision provenance without a declaration reference. PBDIGIT-271, Slice 2a step 1.
 * Refusal values are compared as strings so the test fails by assertion before the case exists.
 */
final class AdmissionBasisTest extends TestCase
{
    private static function admit(MemberHistory $h, string $id, int $at, Provenance $provenance, ?int $until = null): Decision
    {
        return $h->member->decideAdmit(FactId::fromString($id), TimePoint::of($at), MemberHistory::decisionDate(), $until === null ? TimePoint::infinity() : TimePoint::of($until), $provenance);
    }

    private static function refusal(Decision $d): ?string
    {
        return $d->isAccepted() ? null : $d->refusal()->value;
    }

    public function test_a_decision_without_a_declaration_reference_is_refused(): void
    {
        $d = self::admit(MemberHistory::start(), 'A1', 1, Provenance::decision('registrar-1'));

        $this->assertSame('admission_basis_missing', self::refusal($d));
    }

    public function test_a_decision_with_a_declaration_reference_is_accepted(): void
    {
        $d = self::admit(MemberHistory::start(), 'A1', 1, Provenance::decision('registrar-1', null, 'application-42'));

        $this->assertTrue($d->isAccepted());
        $this->assertSame('application-42', $d->fact()->provenance()->declarationRef());
    }

    public function test_statutory_rule_and_attested_history_are_recognised_bases_in_the_domain(): void
    {
        // Recognised by D-1. The ordinary command cannot create them (only decision provenance), and
        // their construction in app/ is restricted to the rehydration decoder by R8 (S2-G).
        $this->assertTrue(self::admit(MemberHistory::start(), 'A1', 1, Provenance::rule('statute-7', 'v1'))->isAccepted());
        $this->assertTrue(self::admit(MemberHistory::start(), 'A1', 1, Provenance::attested('legacy-members', 'import-1'))->isAccepted());
    }

    public function test_structural_guards_take_precedence_over_the_basis_guard(): void
    {
        // Plan §4.1: DuplicateFactId → InvalidInitialTerm → AdmissionBasisMissing → … → G*.
        $h = MemberHistory::start();
        $h->commit(self::admit($h, 'A1', 1, Provenance::decision('registrar-1', null, 'application-1'), 3));

        $this->assertSame('duplicate_fact_id', self::refusal(self::admit($h, 'A1', 5, Provenance::decision('registrar-1'))));
        $this->assertSame('invalid_initial_term', self::refusal(self::admit($h, 'A2', 5, Provenance::decision('registrar-1'), 5)));
    }

    public function test_the_basis_guard_takes_precedence_over_the_lifetime_invariants(): void
    {
        // An admission into a live lifetime without a basis reports the missing basis, not the overlap.
        $h = MemberHistory::start();
        $h->commit(self::admit($h, 'A1', 1, Provenance::decision('registrar-1', null, 'application-1')));

        $this->assertSame('admission_basis_missing', self::refusal(self::admit($h, 'A2', 3, Provenance::decision('registrar-1'))));
        $this->assertSame('overlapping_membership_periods', self::refusal(self::admit($h, 'A2', 3, Provenance::decision('registrar-1', null, 'application-2'))));
    }
}
