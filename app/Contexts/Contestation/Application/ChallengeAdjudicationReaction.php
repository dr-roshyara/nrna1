<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application;

use App\Contexts\Contestation\Application\Exception\AwaitingAdjudication;
use App\Contexts\Contestation\Application\Exception\DeterminationAlreadyApplied;
use App\Contexts\Contestation\Application\Port\ChallengeEventOutbox;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Challenge\DeterminationOutcome;
use App\Contexts\Contestation\Domain\Challenge\Exception\ConflictingDetermination;
use App\Contexts\Contestation\Domain\Events\ChallengeResolved;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use DateTimeImmutable;

/**
 * Business reaction to a binding determination (messaging-agnostic): the Challenge is
 * adjudicated (legal finality, ADR-T20). On a **Dismissed** determination the Election
 * stays silent, so the Challenge self-resolves in the same reaction (T5'). Business
 * conditions are raised as business exceptions; their translation to messaging outcomes
 * lives in the inbox handler/translator, not here.
 */
final class ChallengeAdjudicationReaction
{
    public function __construct(
        private readonly ChallengeRepository $challenges,
        private readonly ChallengeEventOutbox $outbox,
    ) {
    }

    public function on(
        ChallengeId $challengeId,
        DeterminationId $determinationId,
        DeterminationOutcome $outcome,
        DateTimeImmutable $at,
    ): void {
        $challenge = $this->challenges->find($challengeId);
        if ($challenge === null) {
            throw new AwaitingAdjudication(sprintf('Challenge "%s" is not present yet.', $challengeId->toString()));
        }

        if ($challenge->state() === ChallengeState::Routed) {
            $challenge->adjudicate($determinationId, $at);
            if ($outcome === DeterminationOutcome::Dismissed) {
                $challenge->resolve($determinationId, $at); // short-circuit: Election is silent on Dismissed
                // Dismissed ⇒ the Application enriches the published ChallengeResolved with a
                // Dismissed resolution (F-2); the domain event stays minimal.
                $this->outbox->enqueue(...$this->enrich($challenge->pullEvents(), Resolution::Dismissed));
            } else {
                $this->outbox->enqueue(...$challenge->pullEvents());
            }
            $this->challenges->save($challenge);

            return;
        }

        // Already adjudicated (or beyond): same determination = replay; different = conflict.
        $existing = $challenge->adjudicatedDeterminationId();
        if ($existing !== null && $existing->toString() === $determinationId->toString()) {
            throw new DeterminationAlreadyApplied(sprintf('Determination "%s" already applied.', $determinationId->toString()));
        }

        throw ConflictingDetermination::on($challengeId, $existing ?? $determinationId, $determinationId);
    }

    /**
     * Attach the Application-supplied `resolution` to the ChallengeResolved event for
     * publication (F-2); other events pass through unchanged. The domain event is not modified.
     *
     * @param  list<object> $events
     * @return list<object>
     */
    private function enrich(array $events, Resolution $resolution): array
    {
        return array_map(
            static fn (object $event): object => $event instanceof ChallengeResolved
                ? new ChallengeResolvedIntegration($event, $resolution)
                : $event,
            $events,
        );
    }
}
