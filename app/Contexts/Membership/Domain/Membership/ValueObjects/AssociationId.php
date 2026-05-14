<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\ValueObjects;

use Illuminate\Support\Str;

/**
 * AssociationId
 *
 * Pure surrogate identity for CommitteeAssociation.
 * This is NOT composite (member+committee) — it is an explicit identity.
 *
 * This enables:
 * - historical record preservation (reapplication creates new AssociationId)
 * - lifecycle semantics (TERMINATED is immutable, terminal)
 * - audit trail (each decision has unique identity)
 * - no ambiguity in governance records
 *
 * Architectural principle:
 * - Identity is immutable, generated only via factory
 * - No business meaning encoded (pure UUID)
 * - Supports reconstruction from persistence via fromString()
 */
final readonly class AssociationId
{
    private function __construct(
        private string $value
    ) {
        if (empty($value)) {
            throw new \InvalidArgumentException('AssociationId cannot be empty');
        }
    }

    /**
     * Generate new association identity
     */
    public static function generate(): self
    {
        return new self((string) Str::uuid());
    }

    /**
     * Reconstruct from persistence layer (hydration)
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
