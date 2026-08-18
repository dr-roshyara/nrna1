<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L3 — the kind of a declared unit. CLOSED VOCABULARY (INV-L3-1).
 *
 * Every declaration the binding finds is emitted with its kind, INCLUDING
 * interfaces: eligibility is L4's decision, never the binding's (INV-8).
 * Otherwise a binding that drops interfaces is indistinguishable from correct
 * exclusion — O-2's lesson applied to unit eligibility.
 */
enum UnitKind
{
    case ClassUnit;
    case AnonymousClass;
    case EnumUnit;
    case TraitUnit;
    case InterfaceUnit;
}
