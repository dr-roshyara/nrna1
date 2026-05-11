<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Semantics;

final readonly class GovernanceSemanticCode
{
    private function __construct(private string $value)
    {
        if (empty(trim($this->value))) {
            throw new \InvalidArgumentException('Semantic code cannot be empty');
        }
    }

    public static function fromString(string $code): self
    {
        // Normalize to lowercase for canonical representation
        $normalized = strtolower(trim($code));
        return new self($normalized);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
