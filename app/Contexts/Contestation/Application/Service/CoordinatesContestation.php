<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application\Service;

use App\Contexts\Contestation\Application\Command\RaiseChallengeCommand;
use App\Contexts\Contestation\Application\Port\ChallengeEventOutbox;
use App\Contexts\Contestation\Application\Port\IdentityGenerator;
use App\Contexts\Contestation\Domain\Challenge\Challenge;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Events\ChallengeRouted;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use App\Domain\Shared\Clock\ClockInterface;

/**
 * The raise path's coordinator: raise → admit → route.
 *
 * ## Business process origin vs integration conversation origin
 *
 * These are **intentionally different concepts** (ARB clarification, 2026-07-31):
 *
 *  - the **business process** begins at `raise()` — a standing-holder's act;
 *  - the **integration conversation** begins at `route()`, when the first
 *    published event is emitted.
 *
 * That is why the mint lives in `route()` and why no provenance is persisted on the
 * challenge: a correlation minted before the first message would have to survive
 * until that message exists, which would make provenance part of challenge state.
 *
 * ## Publication policy — only integration events reach the outbox
 *
 * `raise()` and `admit()` publish NOTHING. `ChallengeRaised` and `ChallengeAdmitted`
 * are Contestation's internal record of its own process: no consumer, no hydrator,
 * no authority to cross a boundary. Only `ChallengeRouted` — published language, the
 * correction loop's head trigger (ADR-T21) — is enqueued, and this class enqueues it
 * **explicitly by type** rather than draining `pullEvents()`, so the policy is a
 * decision in the code and not a side effect of what the adapter happens to map.
 *
 * ## Chain origin (ADR-MP-06)
 *
 * `route()` is an allowlisted chain-origin producer: it calls
 * `EventProvenance::start()`. WP-5 relocates the CORRECTION-LOOP origin here.
 * Adjudication's `issueDetermination` mint stays in place intentionally until the
 * authority-decision path itself becomes message-driven (WP-6), at which point that
 * entry is expected to disappear.
 *
 * The invariant is not the NUMBER of originators: **each conversation has exactly one
 * origin.** Today two originators serve two DISTINCT conversations (the correction
 * loop; the authority decision) — a deliberate intermediate state, not a growing list.
 *
 * Traceability: roadmap §WP-5 + WP-3B (Option B) · TP-2 · ADR-T21 · ADR-MP-06 ·
 * ADR-T1 (atomicity supplied by {@see TransactionalContestationService}).
 */
final class CoordinatesContestation implements ContestationService
{
    public function __construct(
        private readonly ChallengeRepository $challenges,
        private readonly ChallengeEventOutbox $outbox,
        private readonly IdentityGenerator $identities,
        private readonly ClockInterface $clock,
    ) {
    }

    public function raise(RaiseChallengeCommand $command): ChallengeId
    {
        $challenge = Challenge::raise(
            $this->challenges->nextIdentity(),
            $command->raiser,
            $command->contestedOutcome,
            $command->content,
            $this->clock->now(),
        );

        $this->challenges->save($challenge);

        // No outbox write: the business process has begun, the integration
        // conversation has not.
        return $challenge->id();
    }

    public function admit(ChallengeId $id): void
    {
        $challenge = $this->challenges->get($id);
        $challenge->admit($this->clock->now());

        $this->challenges->save($challenge);
        // No outbox write — see the publication policy above.
    }

    public function route(ChallengeId $id, string $routedTo): void
    {
        $challenge = $this->challenges->get($id);
        $challenge->route($routedTo, $this->clock->now());

        $this->challenges->save($challenge);

        // The integration conversation ORIGIN: minted here, once, for the first
        // published event of the correction loop (ADR-MP-06).
        $this->outbox->enqueue(
            EventProvenance::start($this->identities->next()),
            ...$this->routedEventsOf($challenge),
        );
    }

    /**
     * Publish `ChallengeRouted` and nothing else. Selecting by type — rather than
     * draining `pullEvents()` — keeps "only integration events are published" a
     * decision this class owns.
     *
     * @return list<ChallengeRouted>
     */
    private function routedEventsOf(Challenge $challenge): array
    {
        $routed = [];
        foreach ($challenge->pullEvents() as $event) {
            if ($event instanceof ChallengeRouted) {
                $routed[] = $event;
            }
        }

        return $routed;
    }
}
