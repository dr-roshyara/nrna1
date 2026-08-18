<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L4 — which unit kinds are analysed units. Decision 13.5, decided separately
 * for each kind rather than as a family.
 *
 * Interface is NOT a unit: it declares no bodies, so every method would be an
 * isolated node and LCOM4 would degenerate to "n disjoint responsibility
 * clusters" — a false statement about a type that declares no behaviour.
 */
final class UnitEligibility
{
    private function __construct()
    {
    }

    public static function isAnalysedUnit(UnitKind $kind): bool
    {
        return match ($kind) {
            UnitKind::ClassUnit, UnitKind::AnonymousClass, UnitKind::EnumUnit, UnitKind::TraitUnit => true,
            UnitKind::InterfaceUnit => false,
        };
    }
}
