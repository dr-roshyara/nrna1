<?php

namespace App\Domain\Election\Security\Simplified;

readonly class ConstitutionalObservationContext
{
    /** @param OverlaySignal[] $observations */
    public function __construct(
        public readonly array $observations,
    ) {}

    public function count(): int
    {
        return count($this->observations);
    }

    public function all(): array
    {
        return $this->observations;
    }

    public function isEmpty(): bool
    {
        return empty($this->observations);
    }

    public static function empty(): self
    {
        return new self([]);
    }
}
