<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Process\Exception;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Shared\Application\Inbox\PermanentInboxFailure;
use RuntimeException;

/**
 * §12's SECOND reconciliation branch: a determination exists for this challenge that this
 * process's conclusion did not produce.
 *
 * **EPIC-004K §12 (RULED):** an INV-B1 refusal is *"not an error to escalate blindly —
 * **reconcile**: if this process itself concluded-and-requested earlier (redelivery), ack;
 * if another writer issued (should be impossible under PM-1 + INV-B1 together),
 * **dead-letter + escalate**."* This is that second case.
 *
 * **It should be unreachable.** PM-1 permits one ACTIVE process per challenge and INV-B1 one
 * determination per challenge, so no second writer should exist. **That is precisely why it
 * must be loud:** if it ever fires, one of two structural guarantees has failed, and a retry
 * cannot repair it — hence {@see PermanentInboxFailure}, dead-letter and escalate, following
 * the `ConflictingDetermination → PermanentFailure` precedent §12 names.
 *
 * **The marker is NOT written when this is raised.** Marking would remove the process from
 * the redrive set and bury the conflict.
 *
 * **Layer:** Application/Process — a rule about the process manager's reaction, not about
 * the `Determination` aggregate's invariants, so it sits beside
 * {@see LateDecisionOnExpiredAdjudication}.
 */
final class ConflictingDeterminationForChallenge extends RuntimeException implements PermanentInboxFailure
{
    public static function forChallenge(ChallengeRef $challenge, string $detail): self
    {
        return new self(sprintf(
            'A determination exists for challenge "%s" that this process did not produce: %s. '
            . 'PM-1 and INV-B1 together should make this impossible; it is dead-lettered and '
            . 'escalated rather than retried (EPIC-004K §12).',
            $challenge->toString(),
            $detail,
        ));
    }
}
