<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php;

use EngineeringKnowledge\Capabilities\Cohesion\Application\Ports\SemanticFactProvider;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\FactSet;

/**
 * PHP adapter, behind the port. Delegates unchanged to the existing, tested
 * `PhpFactExtractor` (Architecture Gate, 2026-09-27, §7/§19) — this façade adds no behaviour
 * of its own; it exists solely so the Application layer can depend on `SemanticFactProvider`
 * instead of this concrete class.
 */
final class PhpSemanticFactProvider implements SemanticFactProvider
{
    public function extract(string $source): FactSet
    {
        return PhpFactExtractor::extract($source);
    }
}
