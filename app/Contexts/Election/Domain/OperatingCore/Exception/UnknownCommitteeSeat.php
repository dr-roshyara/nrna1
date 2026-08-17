<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Exception;

use DomainException;

/** A recorded act can only concern a constituted seat (EM-GOV-056: constitution happens once). */
final class UnknownCommitteeSeat extends DomainException
{
    public static function withId(string $seatId): self
    {
        return new self(sprintf('No constituted Committee seat "%s" exists.', $seatId));
    }
}
