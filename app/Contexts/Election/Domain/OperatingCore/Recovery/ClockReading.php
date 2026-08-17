<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Recovery;

/**
 * A COMPUTED clock reading (DD-1): elapsed and remaining are derived measures over
 * recorded condition intervals — never a mutable ticking counter, never a scheduled
 * job's state. Neither clock is a countdown on the election's lifetime
 * (EM-GOV-062). @immutable
 */
final readonly class ClockReading
{
    public function __construct(
        public int $elapsedSeconds,
        public int $remainingSeconds,
    ) {
    }
}
