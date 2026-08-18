<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/** L3 — a method declared in a unit's own body, with the facts observed in it. */
final readonly class MethodFacts
{
    /**
     * @param list<StateAccess>        $stateAccesses
     * @param list<BehaviourReference> $behaviourReferences
     */
    public function __construct(
        public string $methodIdentity,
        public bool $hasBody,
        public array $stateAccesses = [],
        public array $behaviourReferences = [],
    ) {
    }
}
