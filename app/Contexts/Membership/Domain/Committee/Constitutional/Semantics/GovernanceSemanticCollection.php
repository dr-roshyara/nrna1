<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Semantics;

use Countable;

final class GovernanceSemanticCollection implements Countable
{
    /** @var array<string, GovernanceSemanticDefinition> */
    private array $definitions = [];

    public function __construct(GovernanceSemanticDefinition ...$definitions)
    {
        foreach ($definitions as $definition) {
            $codeString = $definition->code->toString();
            $this->definitions[$codeString] = $definition;
        }
    }

    public function add(GovernanceSemanticDefinition $definition): self
    {
        $codeString = $definition->code->toString();

        if (isset($this->definitions[$codeString])) {
            throw new \InvalidArgumentException(
                "Semantic code '{$codeString}' already exists in collection"
            );
        }

        $new = new self();
        $new->definitions = array_merge($this->definitions, [$codeString => $definition]);

        return $new;
    }

    public function get(GovernanceSemanticCode $code): ?GovernanceSemanticDefinition
    {
        return $this->definitions[$code->toString()] ?? null;
    }

    public function all(): array
    {
        $sorted = $this->definitions;
        ksort($sorted);

        return array_values($sorted);
    }

    public function count(): int
    {
        return count($this->definitions);
    }

    public function canonicalSerialization(): string
    {
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
