<?php

namespace App\Application\Election\Security\Simplified;

/**
 * PolicyFinding
 *
 * Result of evaluating a constitutional policy against evidence.
 * Policies evaluate evidence only — NEVER derive authority.
 * PolicyFinding has NO trust level, NO allow/deny — only evaluation facts.
 */
readonly class PolicyFinding
{
    public function __construct(
        public bool $passed,
        public string $constitutionalBasis,
        public string $policyIdentifier,
        public array $supportingFacts,
    ) {}
}
