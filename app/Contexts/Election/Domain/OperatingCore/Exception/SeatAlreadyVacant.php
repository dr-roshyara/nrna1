<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Exception;

use DomainException;

/** I-2: vacancy is an event on an occupied seat; a vacant seat cannot vacate again (EM-GOV-064). */
final class SeatAlreadyVacant extends DomainException
{
    public static function withId(string $seatId): self
    {
        return new self(sprintf('Committee seat "%s" is already vacant.', $seatId));
    }
}
