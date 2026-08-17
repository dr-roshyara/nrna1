<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Exception;

use DomainException;

/** Only vacancy-filling exists — no reconstitution, no replacement of an occupant (EM-GOV-056; B-2). */
final class SeatNotVacant extends DomainException
{
    public static function withId(string $seatId): self
    {
        return new self(sprintf('Committee seat "%s" is not vacant; only a vacancy can be filled (EM-GOV-056).', $seatId));
    }
}
