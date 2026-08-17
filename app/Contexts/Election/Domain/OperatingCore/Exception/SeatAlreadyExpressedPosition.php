<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Exception;

use DomainException;

/**
 * I-7/I-9: a seat expresses at most ONE position per acceptance decision; a replacement
 * participates only in positions the seat has not yet expressed (EM-GOV-066).
 */
final class SeatAlreadyExpressedPosition extends DomainException
{
    public static function withId(string $seatId): self
    {
        return new self(sprintf(
            'Committee seat "%s" has already expressed a position for this acceptance decision (EM-GOV-066).',
            $seatId,
        ));
    }
}
