<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Events;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\DomainEvent;
use DateTimeImmutable;

/**
 * The binding ruling on a contested election (Canonical Event Catalog v1.0,
 * Decision/Core, restricted). Sole producer: Adjudication. Consumers: Election/
 * Lifecycle, Legitimacy projection, Audit — NOT Voting. Carries no voter↔vote
 * linkage (evidence referenced by hash only). Transport envelope added at outbox.
 */
final readonly class DeterminationIssued implements DomainEvent
{
    public function __construct(
        public DeterminationId $determinationId,
        public ChallengeRef $challengeRef,
        public DeterminationOutcome $outcome,
        public Legitimacy $legitimacy,
        public Reason $reason,
        public EvidenceEnvelopeRef $evidenceEnvelopeRef,
        public IssuedByAuthority $issuedByAuthority,
        public Jurisdiction $jurisdiction,
        public DateTimeImmutable $occurredAt,
    ) {
    }
}
