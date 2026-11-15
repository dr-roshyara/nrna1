<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\AuthorityOutcome;

/**
 * Governance's answer as Membership receives it (PBDIGIT-354 S2-3a; D-208 §1; D-238). The outcome is the evidence's
 * basis outcome when a basis is recorded; without one (an adapter that records none, or historical evidence) it is the
 * ratified `result`: true → Authorised, false → NotAuthorised. Undetermined is never inferred.
 */
final readonly class AuthorityDecision
{
    private function __construct(private AuthorityEvidence $evidence)
    {
    }

    public static function of(AuthorityEvidence $evidence): self
    {
        return new self($evidence);
    }

    public function outcome(): AuthorityOutcome
    {
        return $this->evidence->basis()?->outcome()
            ?? ($this->evidence->result() ? AuthorityOutcome::Authorised : AuthorityOutcome::NotAuthorised);
    }

    public function isAuthorised(): bool
    {
        return $this->outcome() === AuthorityOutcome::Authorised;
    }

    public function evidence(): AuthorityEvidence
    {
        return $this->evidence;
    }
}
