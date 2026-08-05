<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Events;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
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
 *
 * PAYLOAD SCHEMA VERSION 3 (ADR-T22): additively carries the fixed considered-
 * evidence set (`evidenceSet`) — R-4-expanded lands at the aggregate's issuance;
 * the event IS the ruling's record (ADR-T19), so what was considered is fixed
 * in it. Version window (v3 current, v2 previous — v1 retired per the
 * versioning rule): a v2 payload hydrates evidenceSet as null. Schema v2
 * history (ADR-PL-01): contestedOutcome, nullable. Same event, NOT a new
 * class (ADR-T5 + Event Registry).
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
        public ?ContestedOutcomeRef $contestedOutcome,   // schema_version 2 (null for v1 payloads)
        public ?EvidenceSet $evidenceSet,                // schema_version 3 (null for v2 payloads)
        public DateTimeImmutable $occurredAt,
    ) {
    }
}
