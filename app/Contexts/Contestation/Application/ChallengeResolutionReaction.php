<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application;

use App\Contexts\Contestation\Application\Exception\AwaitingAdjudication;
use App\Contexts\Contestation\Application\Exception\DeterminationAlreadyApplied;
use App\Contexts\Contestation\Application\Port\ChallengeEventOutbox;
use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use DateTimeImmutable;

/**
 * Business reaction to a correction being applied (messaging-agnostic): the Challenge is
 * resolved (operational completion, ADR-T20). The correlation key is the `determinationId`
 * (the correction event carries no challengeId). If no Challenge yet carries this
 * determination, the correction is temporally premature (`AwaitingAdjudication`); a
 * re-delivery of an already-resolved Challenge is a business replay.
 */
final class ChallengeResolutionReaction
{
    public function __construct(
        private readonly ChallengeRepository $challenges,
        private readonly ChallengeEventOutbox $outbox,
    ) {
    }

    public function on(DeterminationId $determinationId, DateTimeImmutable $at): void
    {
        $challenge = $this->challenges->findByDeterminationId($determinationId);
        if ($challenge === null) {
            throw new AwaitingAdjudication(sprintf(
                'No challenge is adjudicated with determination "%s" yet.',
                $determinationId->toString(),
            ));
        }

        if ($challenge->state() === ChallengeState::Adjudicated) {
            $challenge->resolve($determinationId, $at);
            $this->outbox->enqueue(...$challenge->pullEvents());
            $this->challenges->save($challenge);

            return;
        }

        // Already resolved with this determination → the same fact, re-delivered.
        throw new DeterminationAlreadyApplied(sprintf(
            'Challenge for determination "%s" is already resolved.',
            $determinationId->toString(),
        ));
    }
}
