<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\Exceptions;

use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;

/**
 * InvalidLineageTransitionException
 *
 * Thrown when a membership lineage state machine transition is invalid.
 *
 * This is a constitutional boundary enforcement. Invalid transitions are
 * not application errors — they are governance violations.
 */
final class InvalidLineageTransitionException extends \DomainException
{
    public static function cannotTransitionFrom(
        MembershipStatus $currentStatus,
        MembershipStatus $targetStatus,
    ): self {
        return new self(
            sprintf(
                'Cannot transition membership from %s to %s',
                $currentStatus->value,
                $targetStatus->value,
            ),
        );
    }

    public static function terminatedIsTerminal(): self {
        return new self(
            'TERMINATED membership is terminal. No transitions are possible. ' .
            'To rejoin, member must reapply, creating a new membership lineage.',
        );
    }

    public static function cannotSuspendNonActive(): self {
        return new self(
            'Can only suspend ACTIVE membership. Cannot suspend SUSPENDED or TERMINATED.',
        );
    }

    public static function cannotRestoreNonSuspended(): self {
        return new self(
            'Can only restore SUSPENDED membership. Cannot restore ACTIVE or TERMINATED.',
        );
    }

    public static function cannotTerminateTerminated(): self {
        return new self(
            'Cannot terminate an already TERMINATED membership.',
        );
    }

    public static function cannotReapplyNonTerminated(): self {
        return new self(
            'Can only reapply after TERMINATED membership. ' .
            'ACTIVE or SUSPENDED memberships cannot reapply.',
        );
    }
}
