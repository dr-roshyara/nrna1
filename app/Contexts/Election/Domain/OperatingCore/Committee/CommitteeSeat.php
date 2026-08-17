<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Committee;

use App\Contexts\Election\Domain\OperatingCore\Exception\SeatAlreadyVacant;
use App\Contexts\Election\Domain\OperatingCore\Exception\SeatNotVacant;

/**
 * A constituted Committee seat (entity inside AG-1). The seat survives its occupant
 * (EM-GOV-056): vacating and filling change occupancy, never the seat's existence.
 * Temporary unavailability is deliberately NOT representable here (EM-GOV-064; B-2).
 */
final class CommitteeSeat
{
    private bool $vacant = false;

    public function __construct(private readonly CommitteeSeatId $id)
    {
    }

    public function id(): CommitteeSeatId
    {
        return $this->id;
    }

    public function isVacant(): bool
    {
        return $this->vacant;
    }

    /** I-2: only a recorded vacancy event vacates a seat (EM-GOV-064). */
    public function vacate(): void
    {
        if ($this->vacant) {
            throw SeatAlreadyVacant::withId($this->id->toString());
        }
        $this->vacant = true;
    }

    /** I-4: filling re-occupies the EXISTING seat (EM-GOV-056). */
    public function fill(): void
    {
        if (! $this->vacant) {
            throw SeatNotVacant::withId($this->id->toString());
        }
        $this->vacant = false;
    }
}
