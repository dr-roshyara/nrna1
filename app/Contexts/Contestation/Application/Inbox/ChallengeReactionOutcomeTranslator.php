<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application\Inbox;

use App\Contexts\Contestation\Application\Exception\AwaitingAdjudication;
use App\Contexts\Contestation\Application\Exception\DeterminationAlreadyApplied;
use App\Contexts\Contestation\Domain\Challenge\Exception\ConflictingDetermination;
use App\Contexts\Contestation\Domain\Challenge\Exception\IllegalChallengeTransition;
use App\Contexts\Shared\Application\Inbox\CausalPreconditionMissing;
use Throwable;

/**
 * The single translation boundary (ARB F-1): maps a Contestation *business condition* to a
 * Messaging Platform *operational marker*. This is the only place coupled to messaging
 * semantics; the reactions and their behaviour tests stay messaging-agnostic.
 *
 *   AwaitingAdjudication         → CausalPreconditionMissing (park + re-drive)
 *   DeterminationAlreadyApplied  → IdempotentReplay          (ack, no-op)
 *   ConflictingDetermination     → PermanentInboxFailure     (dead-letter + escalate)
 *   IllegalChallengeTransition   → PermanentInboxFailure
 */
final class ChallengeReactionOutcomeTranslator
{
    public function toInboxOutcome(Throwable $businessCondition): Throwable
    {
        return match (true) {
            $businessCondition instanceof AwaitingAdjudication
                => new CausalPreconditionMissing($businessCondition->getMessage()),
            $businessCondition instanceof DeterminationAlreadyApplied
                => new ChallengeReactionReplay($businessCondition->getMessage(), 0, $businessCondition),
            $businessCondition instanceof ConflictingDetermination,
            $businessCondition instanceof IllegalChallengeTransition
                => new ChallengeReactionPermanentFailure($businessCondition->getMessage(), 0, $businessCondition),
            default => $businessCondition,
        };
    }
}
