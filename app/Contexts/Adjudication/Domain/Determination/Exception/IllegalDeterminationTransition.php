<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination\Exception;

use App\Contexts\Adjudication\Domain\Determination\DeterminationState;
use DomainException;

/**
 * Thrown on a forbidden Determination state transition (Round 50-07 v1.2
 * illegal-transition policy): DomainException + NO state mutation.
 */
final class IllegalDeterminationTransition extends DomainException
{
    public static function from(DeterminationState $current, string $command): self
    {
        return new self(sprintf(
            'Illegal determination transition: cannot "%s" from state "%s".',
            $command,
            $current->value
        ));
    }
}
