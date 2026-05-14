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
}
