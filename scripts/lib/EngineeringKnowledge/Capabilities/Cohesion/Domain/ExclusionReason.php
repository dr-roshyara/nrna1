<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L4 — why a behaviour reference did not become an edge. CLOSED VOCABULARY.
 *
 * These are DISTINCT VALUES so that the distinctions 13.3 forbids merging
 * cannot be merged without editing this enum, which is a contract change.
 * Every excluded reference is REPORTED with its reason: a reference that is
 * seen and excluded must remain distinguishable from one never seen.
 */
enum ExclusionReason
{
    case OutOfFrame;             // parent:: — inherited behaviour, outside the unit
    case NotTheAnalysedUnit;     // determinable, and it denotes a different unit
    case NotDeterminable;        // the target cannot be determined in this scope
    case CallableNotInvocation;  // m(...) references a behaviour, does not invoke it
    case AliasedSpelling;        // stated LIMITATION, not a defect
    case TargetNotDeclaredHere;  // included, but no such node in this unit
}
