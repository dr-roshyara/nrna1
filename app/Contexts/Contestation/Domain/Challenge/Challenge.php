<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge;

use App\Contexts\Contestation\Domain\Challenge\Exception\IllegalChallengeTransition;
use App\Contexts\Contestation\Domain\DomainEvent;
use App\Contexts\Contestation\Domain\Events\ChallengeAdjudicated;
use App\Contexts\Contestation\Domain\Events\ChallengeAdmitted;
use App\Contexts\Contestation\Domain\Events\ChallengeDismissed;
use App\Contexts\Contestation\Domain\Events\ChallengeRaised;
use App\Contexts\Contestation\Domain\Events\ChallengeResolved;
use App\Contexts\Contestation\Domain\Events\ChallengeRouted;
use DateTimeImmutable;

/**
 * Challenge aggregate root (greenfield Core, Round 50-07 v1.2).
 *
 * State machine: Raised → Admitted → Routed → Resolved▣
 *                Raised → Dismissed▣ | (Raised|Admitted) → Lapsed▣
 *
 * Discipline:
 *  - Forbidden transitions throw IllegalChallengeTransition with NO mutation
 *    (illegal-transition policy); the caller audits the attempt.
 *  - Time is injected (clock authority, Principle 9) — no now()/Carbon here.
 *  - Carries NO voter↔vote linkage (Q7 / ADR-T11): raiser is a standing actor.
 *  - This aggregate REQUESTS a Determination; it never creates one (TP-2). It
 *    only records the determinationId it is resolved with.
 */
final class Challenge
{
    /** @var list<DomainEvent> */
    private array $recordedEvents = [];

    private function __construct(
        private readonly ChallengeId $id,
        private ChallengeState $state,
    ) {
    }

    public static function raise(
        ChallengeId $id,
        RaiserStandingRef $raiser,
        ContestedOutcomeRef $contestedOutcome,
        SubmittedContent $content,
        DateTimeImmutable $at,
    ): self {
        $challenge = new self($id, ChallengeState::Raised);
        $challenge->record(new ChallengeRaised($id, $raiser, $contestedOutcome, $content, $at));

        return $challenge;
    }

    public function admit(DateTimeImmutable $at): void
    {
        $this->guard('admit', ChallengeState::Raised);
        $this->state = ChallengeState::Admitted;
        $this->record(new ChallengeAdmitted($this->id, $at));
    }

    public function dismiss(string $reason, DateTimeImmutable $at): void
    {
        $this->guard('dismiss', ChallengeState::Raised);
        $this->state = ChallengeState::Dismissed;
        $this->record(new ChallengeDismissed($this->id, $reason, $at));
    }

    /** Optional investigation phase (modeled per ADR-T20; trigger driven later). */
    public function beginInvestigation(DateTimeImmutable $at): void
    {
        $this->guard('beginInvestigation', ChallengeState::Admitted);
        $this->state = ChallengeState::Investigating;
    }

    public function route(string $routedTo, DateTimeImmutable $at): void
    {
        $this->guard('route', ChallengeState::Admitted, ChallengeState::Investigating);
        $this->state = ChallengeState::Routed;
        $this->record(new ChallengeRouted($this->id, $routedTo, $at));
    }

    /**
     * Legal finality (ADR-T20): a binding determination exists. Routed →
     * Adjudicated; emits ChallengeAdjudicated. NOT yet Resolved.
     */
    public function adjudicate(DeterminationId $determinationId, DateTimeImmutable $at): void
    {
        $this->guard('adjudicate', ChallengeState::Routed);
        $this->state = ChallengeState::Adjudicated;
        $this->record(new ChallengeAdjudicated($this->id, $determinationId, $at));
    }

    /**
     * Operational completion (ADR-T20): consequences executed. Adjudicated →
     * Resolved; emits ChallengeResolved. Requires prior adjudication.
     */
    public function resolve(DeterminationId $determinationId, DateTimeImmutable $at): void
    {
        $this->guard('resolve', ChallengeState::Adjudicated);
        $this->state = ChallengeState::Resolved;
        $this->record(new ChallengeResolved($this->id, $determinationId, $at));
    }

    /**
     * System-initiated timeout. Allowed only before a decision. Per Round 50-07
     * v1.2 the Lapsed timeout emits NO domain event (distinct from Dismissed);
     * Audit observes it via the decision-window timeout log, not an event.
     */
    public function lapse(DateTimeImmutable $at): void
    {
        $this->guard('lapse', ChallengeState::Raised, ChallengeState::Admitted);
        $this->state = ChallengeState::Lapsed;
    }

    public function id(): ChallengeId
    {
        return $this->id;
    }

    public function state(): ChallengeState
    {
        return $this->state;
    }

    /**
     * Pure derived query (no mutation, no authorization, no persistence, no
     * events): a challenge may be adjudicated only once routed to a jurisdiction
     * (Round 50-07). The application service uses this read-only check; the
     * Challenge is never written during adjudication (ADR-T14).
     */
    public function canProceedToAdjudication(): bool
    {
        return $this->state === ChallengeState::Routed;
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

    /**
     * Guard a transition: the current state must be one of the allowed states,
     * otherwise throw WITHOUT mutating (illegal-transition policy).
     */
    private function guard(string $command, ChallengeState ...$allowed): void
    {
        if (!in_array($this->state, $allowed, true)) {
            throw IllegalChallengeTransition::from($this->state, $command);
        }
    }
}
