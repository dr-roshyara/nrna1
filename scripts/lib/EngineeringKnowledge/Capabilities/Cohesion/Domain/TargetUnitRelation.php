<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L3 — WHETHER the named target denotes the analysed unit. CLOSED VOCABULARY.
 *
 * The second orthogonal axis (see QualifierKind). "Include" under 13.3 means
 * include WHERE IT DENOTES THIS UNIT; aliased is excluded BY KIND even when it
 * does. Two axes in the ruling, two facts in the model.
 */
enum TargetUnitRelation
{
    case DenotesAnalysedUnit;
    case DenotesOtherUnit;
    case Undetermined;
}
