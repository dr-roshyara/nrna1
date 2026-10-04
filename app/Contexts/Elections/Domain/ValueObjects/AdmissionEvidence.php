<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

/**
 * AdmissionEvidence — the admission requirements CURRENTLY satisfied for one
 * (election, voter) pair (ADR_20261004_1549 §2.2 inv. 7 and 9).
 *
 * It carries the current admission effect only, never the historical fact.
 * Built by infrastructure from the evidence records (C3); pure value here.
 */
final readonly class AdmissionEvidence
{
    /** @param list<AdmissionRequirement> $satisfied */
    private function __construct(private array $satisfied) {}

    public static function none(): self
    {
        return new self([]);
    }

    public static function satisfying(AdmissionRequirement ...$requirements): self
    {
        return new self(array_values(array_unique($requirements, SORT_REGULAR)));
    }

    public function satisfies(AdmissionRequirement $requirement): bool
    {
        return in_array($requirement, $this->satisfied, true);
    }
}
