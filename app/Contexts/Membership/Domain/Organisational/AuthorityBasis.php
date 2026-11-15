<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The basis of an AuthorityCheck answer, beside the six ratified fields (accepted D-208 §1: outcome, evaluatedFor,
 * competenceKind, decisionRef, evidence; PBDIGIT-354 S2-3a, D-238). Carried on the fact as recorded provenance;
 * Membership never re-evaluates it.
 *
 * - `reason`: required for NotAuthorised and Undetermined, absent for Authorised (Undetermined ≠ No, with its why).
 * - `evaluatedFor`: the date the authority is evaluated for (D-12: the decision date), distinct from `checkedAt` (τ).
 * - `competenceKind`: required for Authorised; may be absent on a refusal (nothing was established).
 * - `decisionRef`: the Resolution part or act, where there is one; `evidence`: references or fingerprints.
 */
final readonly class AuthorityBasis
{
    /** @param list<string> $evidence */
    private function __construct(
        private AuthorityOutcome $outcome,
        private ?string $reason,
        private TimePoint $evaluatedFor,
        private ?CompetenceKind $competenceKind,
        private ?string $decisionRef,
        private array $evidence,
    ) {
        if ($outcome === AuthorityOutcome::Authorised && $reason !== null) {
            throw new \InvalidArgumentException('An authorisation carries no refusal reason.');
        }
        if ($outcome !== AuthorityOutcome::Authorised && ($reason === null || trim($reason) === '')) {
            throw new \InvalidArgumentException('A NotAuthorised or Undetermined answer states its reason.');
        }
        if ($outcome === AuthorityOutcome::Authorised && $competenceKind === null) {
            throw new \InvalidArgumentException('An authorisation names how the competence was established.');
        }
        if ($evaluatedFor->isInfinite()) {
            throw new \InvalidArgumentException('evaluatedFor is a calendar date.');
        }
        foreach ($evidence as $reference) {
            if (!is_string($reference) || trim($reference) === '') {
                throw new \InvalidArgumentException('An evidence reference is a non-empty string.');
            }
        }
    }

    /** @param list<string> $evidence */
    public static function of(AuthorityOutcome $outcome, ?string $reason, TimePoint $evaluatedFor, ?CompetenceKind $competenceKind, ?string $decisionRef, array $evidence): self
    {
        return new self($outcome, $reason, $evaluatedFor, $competenceKind, $decisionRef, array_values($evidence));
    }

    public function outcome(): AuthorityOutcome
    {
        return $this->outcome;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }

    public function evaluatedFor(): TimePoint
    {
        return $this->evaluatedFor;
    }

    public function competenceKind(): ?CompetenceKind
    {
        return $this->competenceKind;
    }

    public function decisionRef(): ?string
    {
        return $this->decisionRef;
    }

    /** @return list<string> */
    public function evidence(): array
    {
        return $this->evidence;
    }
}
