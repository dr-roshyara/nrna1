<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Command;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use DateTimeImmutable;

/**
 * Command to issue a Determination for an already-routed Challenge. Uses the
 * ubiquitous language at the boundary (ChallengeRef VO, not a raw string).
 * The legitimacy verdict is computed upstream (LegitimacyDecision policy) and
 * supplied here; the aggregate records it.
 */
final readonly class IssueDeterminationCommand
{
    public function __construct(
        public ChallengeRef $challengeRef,
        public DeterminationOutcome $outcome,
        public Legitimacy $legitimacy,
        public Reason $reason,
        public IssuedByAuthority $issuedByAuthority,
        public Jurisdiction $jurisdiction,
        public EvidenceEnvelopeRef $evidenceEnvelopeRef,
        public DateTimeImmutable $occurredAt,
    ) {
    }
}
