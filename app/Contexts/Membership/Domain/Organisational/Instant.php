<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * A point of decision or recording time (decidedAt, recordedAt).
 *
 * A separate type from TimePoint (valid time), so that the temporal coordinates
 * cannot be collapsed (contract §9). Always finite.
 */
final readonly class Instant
{
    private function __construct(private int $ordinal)
    {
    }

    public static function of(int $ordinal): self
    {
        return new self($ordinal);
    }

    public function ordinal(): int
    {
        return $this->ordinal;
    }

    public function isAfter(self $other): bool
    {
        return $this->ordinal > $other->ordinal;
    }

    public function equals(self $other): bool
    {
        return $this->ordinal === $other->ordinal;
    }
}
