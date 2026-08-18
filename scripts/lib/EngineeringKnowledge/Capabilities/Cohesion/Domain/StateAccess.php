<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/** L3 — a touch of the analysed instance's state. */
final readonly class StateAccess
{
    public function __construct(
        public string $propertyName,
        public AccessMode $accessMode,
    ) {
    }
}
