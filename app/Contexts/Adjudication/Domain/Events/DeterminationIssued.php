<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Events;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
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
 *
 * PAYLOAD SCHEMA VERSION 2 (ADR-PL-01): additively carries the `contestedOutcome`
 * reference (which contains electionId) so a consumer can resolve the target
 * Election. Backward-compatible — nullable; a v1 payload hydrates it as null.
 * Same event, NOT a new class (ADR-T5 + Event Registry).
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
        public DateTimeImmutable $occurredAt,
    ) {
    }
}
