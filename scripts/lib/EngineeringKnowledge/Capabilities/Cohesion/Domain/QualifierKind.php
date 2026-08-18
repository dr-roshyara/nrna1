<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L3 — HOW the target of a behaviour reference was named. CLOSED VOCABULARY.
 *
 * Decision 13.3 rules per qualifier kind, so the binding must PRESERVE the kind
 * and the contract INTERPRETS it. This is one of the two orthogonal axes: a
 * single fused "is own class?" boolean cannot express both this and
 * TargetUnitRelation, and fusing them is exactly how the earlier reference lost
 * the distinction.
 *
 * OPEN-1 (recorded, AMD4): some values are PHP-derived in this first version.
 * That does NOT establish PHP taxonomy as permanently normative; a future
 * binding must test whether the vocabulary generalises, and any generalisation
 * is a contract amendment, not a model gap.
 */
enum QualifierKind
{
    case SelfKeyword;
    case StaticKeyword;
    case ParentKeyword;
    case InstanceReceiver;
    case UnqualifiedName;
    case QualifiedName;
    case FullyQualifiedName;
    case RelativeName;
    case AliasedName;
    case ComputedTarget;
}
