<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L3 — the complete fact set for ONE analysis scope.
 *
 * OQ-2, decided: the analysis scope is exactly ONE PHP SOURCE FILE. Identity
 * uniqueness (INV-L3-2) is guaranteed within this extent and nowhere wider.
 */
final readonly class FactSet
{
    /** @param list<DeclaredUnit> $units */
    public function __construct(public array $units = [])
    {
    }
}
