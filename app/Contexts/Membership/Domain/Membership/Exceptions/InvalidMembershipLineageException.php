<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\Exceptions;

use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;

final class InvalidMembershipLineageException extends \DomainException
{
    public static function emptyEpisodeChain(): self
    {
        return new self('Lineage must have at least one episode');
    }

    public static function invalidInitialEpisode(MembershipStatus $status): self
    {
        $label = $status->equals(MembershipStatus::SUSPENDED)
            ? 'SUSPENDED requires prior ACTIVE'
            : 'Episode chain must contain valid transitions';
        return new self($label);
    }

    public static function invalidStateTransition(): self
    {
        return new self('Invalid state transition');
    }

    public static function terminatedIsTerminal(): self
    {
        return new self('TERMINATED is terminal');
    }

    public static function suspendedRequiresPriorActive(): self
    {
        return new self('SUSPENDED requires prior ACTIVE');
    }

    public static function cannotRestoreFromTerminated(): self
    {
        return new self('Cannot restore from TERMINATED');
    }
}
