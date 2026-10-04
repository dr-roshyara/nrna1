<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

/**
 * AdmissionDecision — whether a registered voter currently satisfies every
 * requirement of the election's VoterAdmissionPolicy, and which ones are missing.
 *
 * Derived, never stored (ADR_20261004_1549 §2.2 inv. 11).
 */
final readonly class AdmissionDecision
{
    /** @param list<AdmissionRequirement> $missing */
    private function __construct(private array $missing) {}

    /** @param list<AdmissionRequirement> $missing  empty ⇒ admitted */
    public static function fromMissing(array $missing): self
    {
        return new self(array_values($missing));
    }

    public function isAdmitted(): bool
    {
        return $this->missing === [];
    }

    /** @return list<AdmissionRequirement> */
    public function missing(): array
    {
        return $this->missing;
    }
}
