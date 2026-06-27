<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\Exception;

final class SemanticEvolutionViolationException extends \DomainException
{
    public function __construct(string $semanticCode, string $violation)
    {
        parent::__construct("Semantic evolution violation for '{$semanticCode}': {$violation}");
    }
}
