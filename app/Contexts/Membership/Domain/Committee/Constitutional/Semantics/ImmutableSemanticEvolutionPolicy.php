<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Semantics;

use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\Exception\SemanticEvolutionViolationException;

final class ImmutableSemanticEvolutionPolicy
{
    public function validateEvolution(
        GovernanceSemanticDefinition $original,
        ?GovernanceSemanticDefinition $modified,
    ): void {
        $code = $original->code->toString();

        // Semantic removal is prohibited
        if ($modified === null) {
            throw new SemanticEvolutionViolationException(
                $code,
                'Semantic definition cannot be removed after registration'
            );
        }

        // Canonical meaning must not change
        if ($original->canonicalMeaning !== $modified->canonicalMeaning) {
            throw new SemanticEvolutionViolationException(
                $code,
                'Canonical meaning cannot change after publication'
            );
        }

        // Replay implications must not change
        if ($original->replayImplications !== $modified->replayImplications) {
            throw new SemanticEvolutionViolationException(
                $code,
                'Replay implications cannot change retroactively'
            );
        }

        // Legitimacy implications must not change
        if ($original->legitimacyImplications !== $modified->legitimacyImplications) {
            throw new SemanticEvolutionViolationException(
                $code,
                'Legitimacy implications cannot change retroactively'
            );
        }

        // Category must not change
        if ($original->category !== $modified->category) {
            throw new SemanticEvolutionViolationException(
                $code,
                'Semantic category cannot be mutated after registration'
            );
        }
    }

    public function registerNewSemantic(GovernanceSemanticDefinition $definition): void
    {
        // New semantics are allowed - definition validity is already enforced by VO constructor
    }
}
