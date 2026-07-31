<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Process\Exception;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Shared\Application\Inbox\PermanentInboxFailure;
use RuntimeException;

/**
 * The constitutional authority decided AFTER the adjudication horizon elapsed.
 *
 * **EPIC-004K §197 (RULED):** *"late decisions never honored … a post-expiry authority
 * decision dead-letters as a conflict."* This is emphatically NOT the idempotent no-op
 * owed to a **redelivered** decision (ADR-T3): redelivery repeats a decision the
 * process already recorded, while this is a decision the process may never record.
 * Before WP-6 both shared one early-return branch, so a late ruling was swallowed in
 * silence — the loudest possible failure is the correct one.
 *
 * Implements {@see PermanentInboxFailure}: retrying cannot help, because the horizon
 * will never un-elapse. The wrapper dead-letters and escalates rather than retrying
 * forever.
 *
 * **Layer:** Application/Process — this is a rule about the process manager's
 * lifecycle, not about the `Determination` aggregate's invariants, so it sits beside
 * {@see IllegalProcessTransition} and not in `Domain\Exception`.
 */
final class LateDecisionOnExpiredAdjudication extends RuntimeException implements PermanentInboxFailure
{
    public static function forChallenge(ChallengeRef $challenge): self
    {
        return new self(sprintf(
            'The adjudication for challenge "%s" EXPIRED before the authority decided; '
            . 'a post-expiry decision is a conflict and is never honored (EPIC-004K §197).',
            $challenge->toString(),
        ));
    }
}
