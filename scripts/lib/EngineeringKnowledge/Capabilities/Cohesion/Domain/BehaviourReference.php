<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L3 — one site where a method refers to a behaviour.
 *
 * ALL SIX attributes are mandatory (INV-L3-5): there is no default and no
 * "unknown". Every attribute exists because a decided rule ranges over it.
 *
 * NOTE (INV-4 / INV-L3-7): this carries NO provenance. Offsets, lexemes and
 * token indices live in a separate provenance record joined one-way by factId
 * and are never handed to L4 — which is what makes it impossible for a
 * cohesion rule to fall back to source syntax.
 */
final readonly class BehaviourReference
{
    public function __construct(
        public string $targetMethodName,
        public QualifierKind $qualifierKind,
        public TargetUnitRelation $targetUnitRelation,
        public ReferenceMode $referenceMode,
        public AccessMode $accessMode,
        public Determinability $determinability,
        public ?string $factId = null,
    ) {
    }
}
