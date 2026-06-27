<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

use App\Contexts\Adjudication\Domain\Determination\Exception\IllegalDeterminationTransition;
use App\Contexts\Adjudication\Domain\DomainEvent;
use App\Contexts\Adjudication\Domain\Events\DeterminationIssued;
use DateTimeImmutable;

/**
 * Determination aggregate root (greenfield Core, Round 50-07 v1.2).
 *
 * State machine: Draft → Issued → Final▣.
 *
 * Boundary (50-07 / plan R1) — owns ONLY: identity, constitutional outcome,
 * legitimacy, issuing authority, jurisdiction, evidence references, finality
 * state. It does NOT own challenge/election workflow, correction execution,
 * replay, notification, or persistence.
 *
 * Discipline: forbidden transitions throw IllegalDeterminationTransition with
 * NO mutation and NO event (illegal-transition policy; no event after Final).
 * Issued exactly once. Time injected (clock authority). No voter↔vote linkage.
 */
final class Determination
{
    /** @var list<DomainEvent> */
    private array $recordedEvents = [];

    private function __construct(
        private readonly DeterminationId $id,
        private readonly ChallengeRef $challengeRef,
        private readonly IssuedByAuthority $issuedByAuthority,
        private readonly Jurisdiction $jurisdiction,
        private readonly EvidenceEnvelopeRef $evidenceEnvelopeRef,
        private DeterminationState $state,
    ) {
    }

    public static function prepare(
        DeterminationId $id,
        ChallengeRef $challengeRef,
        IssuedByAuthority $issuedByAuthority,
        Jurisdiction $jurisdiction,
        EvidenceEnvelopeRef $evidenceEnvelopeRef,
    ): self {
        // Draft: authority/jurisdiction/evidence present (VO ctors guarantee it).
        return new self(
            $id,
            $challengeRef,
            $issuedByAuthority,
            $jurisdiction,
            $evidenceEnvelopeRef,
            DeterminationState::Draft,
        );
    }

    public function issue(
        DeterminationOutcome $outcome,
        Legitimacy $legitimacy,
        Reason $reason,
        DateTimeImmutable $at,
    ): void {
        $this->guard('issue', DeterminationState::Draft);
        $this->state = DeterminationState::Issued;
        $this->record(new DeterminationIssued(
            $this->id,
            $this->challengeRef,
            $outcome,
            $legitimacy,
            $reason,
            $this->evidenceEnvelopeRef,
            $this->issuedByAuthority,
            $this->jurisdiction,
            $at,
        ));
    }

    public function finalize(DateTimeImmutable $at): void
    {
        $this->guard('finalize', DeterminationState::Issued);
        $this->state = DeterminationState::Final;
        // Final emits no event (Round 50-07 v1.2).
    }

    public function id(): DeterminationId
    {
        return $this->id;
    }

    public function challengeRef(): ChallengeRef
    {
        return $this->challengeRef;
    }

    public function state(): DeterminationState
    {
        return $this->state;
    }

    /**
     * Pull and clear recorded events (released to the outbox by the app layer).
     *
     * @return list<DomainEvent>
     */
    public function pullEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];

        return $events;
    }

    private function record(DomainEvent $event): void
    {
        $this->recordedEvents[] = $event;
    }

    private function guard(string $command, DeterminationState ...$allowed): void
    {
        if (!in_array($this->state, $allowed, true)) {
            throw IllegalDeterminationTransition::from($this->state, $command);
        }
    }
}
