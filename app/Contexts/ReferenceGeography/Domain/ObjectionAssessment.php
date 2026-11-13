<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** The three findings an objection is assessed on (strategic baseline S1.1 step 2): authenticity, mandate, applicability. */
final readonly class ObjectionAssessment
{
    public function __construct(public bool $authentic, public bool $mandate, public bool $applicable)
    {
    }

    public function allPositive(): bool
    {
        return $this->authentic && $this->mandate && $this->applicable;
    }
}
