<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L3 — a type declaration the binding found.
 *
 * Emitted for EVERY declaration including interfaces (INV-L3-3 / INV-8);
 * whether it is an analysed unit is L4's decision (UnitEligibility).
 */
final readonly class DeclaredUnit
{
    /** @param list<MethodFacts> $methods */
    public function __construct(
        public UnitKind $kind,
        public UnitIdentity $identity,
        public ?string $declaredName,
        public array $methods = [],
    ) {
    }
}
