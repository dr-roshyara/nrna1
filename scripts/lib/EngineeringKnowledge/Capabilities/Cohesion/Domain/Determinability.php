<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L3 — whether the target can be determined within the analysis scope.
 * CLOSED VOCABULARY (INV-L3-4).
 *
 * A determined target that is simply a DIFFERENT unit is Determinable; it is
 * excluded as NotTheAnalysedUnit, never as NotDeterminable. The contract
 * insists those two claims must never be merged: the first says there is
 * nothing here to connect to, the second admits we cannot see it.
 */
enum Determinability
{
    case Determinable;
    case NotDeterminable;
}
