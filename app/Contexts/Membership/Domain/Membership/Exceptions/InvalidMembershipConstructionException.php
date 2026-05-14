<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\Exceptions;

final class InvalidMembershipConstructionException extends \DomainException
{
    public static function suspendedRequiresActorId(): self
    {
        return new self('SUSPENDED status requires actorId');
    }

    public static function suspendedRequiresTransitionReason(): self
    {
        return new self('SUSPENDED status requires transitionReason');
    }

    public static function suspendedRequiresTimestamp(): self
    {
        return new self('transitionedAt is required when status changes');
    }

    public static function terminatedRequiresActorId(): self
    {
        return new self('TERMINATED status requires actorId');
    }

    public static function terminatedRequiresTransitionReason(): self
    {
        return new self('TERMINATED status requires transitionReason');
    }

    public static function terminatedRequiresTimestamp(): self
    {
        return new self('transitionedAt is required when status changes');
    }
}
