<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Semantics;

use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\Exception\DuplicateSemanticCodeException;

final class GovernanceSemanticRegistry
{
    /** @var array<string, GovernanceSemanticDefinition> */
    private array $definitions = [];

    public function register(GovernanceSemanticDefinition $definition): void
    {
        $codeString = $definition->code->toString();

        if (isset($this->definitions[$codeString])) {
            throw new DuplicateSemanticCodeException($codeString);
        }

        $this->definitions[$codeString] = $definition;
    }

    public function find(GovernanceSemanticCode $code): ?GovernanceSemanticDefinition
    {
        return $this->definitions[$code->toString()] ?? null;
    }

    public function canonicalSerialization(): string
    {
        // Sort definitions by code for deterministic serialization
        $sorted = $this->definitions;
        ksort($sorted);

        $data = [];
        foreach ($sorted as $definition) {
            $data[] = [
                'code' => $definition->code->toString(),
                'category' => $definition->category->value,
                'meaning' => $definition->canonicalMeaning,
                'replay_implications' => $definition->replayImplications,
                'legitimacy_implications' => $definition->legitimacyImplications,
                'introduced' => $definition->introducedInDoctrineVersion,
            ];
        }

        return json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
