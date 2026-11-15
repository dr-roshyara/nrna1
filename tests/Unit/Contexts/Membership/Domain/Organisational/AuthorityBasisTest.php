<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\AuthorityBasis;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\AuthorityOutcome;
use App\Contexts\Membership\Domain\Organisational\CompetenceKind;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-354 S2-3a (D-238; accepted AuthorityCheck answer, D-208 §1): the six ratified fields stay as they are; the
 * BASIS is added: outcome (Authorised · NotAuthorised · Undetermined), evaluatedFor, competenceKind (incl. ownAct,
 * D-203), decisionRef, evidence. Membership carries it and never interprets it, except where D-203 requires (ownAct).
 */
final class AuthorityBasisTest extends TestCase
{
    private static function basis(AuthorityOutcome $outcome = AuthorityOutcome::Authorised, ?string $reason = null, ?CompetenceKind $kind = CompetenceKind::WorkedOut): AuthorityBasis
    {
        return AuthorityBasis::of($outcome, $reason, TimePoint::of(20_000), $kind, 'resolution:12/part:3', ['sha256:abc']);
    }

    public function test_the_three_outcomes_are_distinct(): void
    {
        $this->assertSame(['authorised', 'not-authorised', 'undetermined'], array_map(static fn (AuthorityOutcome $o) => $o->value, AuthorityOutcome::cases()));
    }

    public function test_the_competence_kinds_are_the_accepted_closed_set(): void
    {
        // D-208 §1: worked out · asserted · gap declared · contradiction · not established; D-177 §8: interim role;
        // D-203: ownAct (self-service record-resignation only)
        $this->assertSame(['worked-out', 'asserted', 'gap-declared', 'contradiction', 'not-established', 'interim-role', 'own-act'],
            array_map(static fn (CompetenceKind $k) => $k->value, CompetenceKind::cases()));
    }

    public function test_a_basis_keeps_every_field(): void
    {
        $b = self::basis();

        $this->assertSame(AuthorityOutcome::Authorised, $b->outcome());
        $this->assertNull($b->reason());
        $this->assertSame(20_000, $b->evaluatedFor()->ordinal());
        $this->assertSame(CompetenceKind::WorkedOut, $b->competenceKind());
        $this->assertSame('resolution:12/part:3', $b->decisionRef());
        $this->assertSame(['sha256:abc'], $b->evidence());
    }

    public function test_a_refusal_states_its_reason_and_an_authorisation_has_none(): void
    {
        $this->assertSame('rules silent', self::basis(AuthorityOutcome::Undetermined, 'rules silent', null)->reason());
        $this->assertSame('not the member concerned', self::basis(AuthorityOutcome::NotAuthorised, 'not the member concerned')->reason());

        $this->expectException(\InvalidArgumentException::class);
        self::basis(AuthorityOutcome::Authorised, 'a reason');
    }

    public function test_a_refusal_must_have_a_reason(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        self::basis(AuthorityOutcome::Undetermined, null, null);
    }

    public function test_an_authorisation_names_its_competence_kind(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        self::basis(AuthorityOutcome::Authorised, null, null);
    }

    public function test_evaluated_for_is_a_finite_date(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        AuthorityBasis::of(AuthorityOutcome::Authorised, null, TimePoint::infinity(), CompetenceKind::WorkedOut, null, []);
    }

    public function test_evidence_references_are_strings(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        AuthorityBasis::of(AuthorityOutcome::Authorised, null, TimePoint::of(1), CompetenceKind::WorkedOut, null, [42]);
    }

    public function test_evidence_without_a_basis_reads_as_not_recorded(): void
    {
        $e = AuthorityEvidence::of('a', 'c', 'r', 'v1', true, Instant::of(1), true);

        $this->assertNull($e->basis(), 'historical evidence: the basis was not recorded; nothing is inferred');
    }

    public function test_evidence_carries_its_basis_and_the_result_agrees_with_the_outcome(): void
    {
        $e = AuthorityEvidence::of('a', 'c', 'r', 'v1', true, Instant::of(1), true, self::basis());
        $this->assertSame(AuthorityOutcome::Authorised, $e->basis()->outcome());

        $this->expectException(\InvalidArgumentException::class);
        AuthorityEvidence::of('a', 'c', 'r', 'v1', false, Instant::of(1), true, self::basis());   // result false, outcome Authorised
    }
}
