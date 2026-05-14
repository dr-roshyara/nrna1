<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\Exceptions;

final class InvalidAssociationTransitionException extends \DomainException
{
    public static function transition(string $from, string $to): self
    {
        return new self(
            "Cannot transition from {$from} to {$to}"
        );
    }

    public static function fromTerminated(): self
    {
        return new self(
            "Cannot transition from terminated association. Create new association instead."
        );
    }

    public static function cannotSuspendFrom(\App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus $from): self
    {
        return new self(
            "Cannot suspend: current status is {$from->value}, must be ACTIVE"
        );
    }

    public static function cannotRestoreFrom(\App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus $from): self
    {
        return new self(
            "Cannot restore: current status is {$from->value}, must be SUSPENDED"
        );
    }

    public static function cannotTerminateFrom(\App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus $from): self
    {
        return new self(
            "Cannot terminate: current status is {$from->value}, termination is only possible from ACTIVE or SUSPENDED"
        );
    }
}
