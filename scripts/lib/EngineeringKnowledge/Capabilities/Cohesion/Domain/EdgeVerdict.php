<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/** L4 — the outcome of applying the edge rule table to one behaviour reference. */
final readonly class EdgeVerdict
{
    private function __construct(private bool $included, private ?ExclusionReason $reason)
    {
    }

    public static function include(): self
    {
        return new self(true, null);
    }

    public static function exclude(ExclusionReason $reason): self
    {
        return new self(false, $reason);
    }

    public function isIncluded(): bool
    {
        return $this->included;
    }

    public function reason(): ?ExclusionReason
    {
        return $this->reason;
    }
}
