<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Time;

use InvalidArgumentException;

/**
 * A recording instant — a point on the record, carrying NO civil-time or schedule
 * meaning (D-8; EM-OPEN-024 interim rule: no implementation may fix the meaning of
 * an entered time). Clocks compute durations BETWEEN recorded instants only (DD-1).
 * @immutable
 */
final readonly class RecordedInstant
{
    private function __construct(public int $epochSeconds)
    {
        if ($epochSeconds < 0) {
            throw new InvalidArgumentException('A recorded instant cannot precede the epoch.');
        }
    }

    public static function fromEpochSeconds(int $epochSeconds): self
    {
        return new self($epochSeconds);
    }

    public function isBefore(self $other): bool
    {
        return $this->epochSeconds < $other->epochSeconds;
    }

    /** Seconds from this instant to a later one — pure interval arithmetic (EM-GOV-062). */
    public function secondsUntil(self $later): int
    {
        if ($later->isBefore($this)) {
            throw new InvalidArgumentException('Interval arithmetic runs forward on the record.');
        }

        return $later->epochSeconds - $this->epochSeconds;
    }
}
