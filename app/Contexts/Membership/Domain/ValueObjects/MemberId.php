<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * MemberId Value Object
 *
 * Represents a party-defined member identifier (not system ULID).
 * Examples: "UML-2024-0001", "NC-P3-00123", "NRNA-USA-2024-456"
 *
 * Immutable and self-validating.
 */
final class MemberId
{
    private string $value;

    public function __construct(string $memberId)
    {
        $this->validate($memberId);
        $this->value = strtoupper(trim($memberId));
    }

    public static function fromString(string $memberId): self
    {
        return new self($memberId);
    }

    private function validate(string $memberId): void
    {
        $memberId = trim($memberId);

        if (empty($memberId)) {
            throw new InvalidArgumentException('Member ID cannot be empty');
        }

        if (strlen($memberId) > 50) {
            throw new InvalidArgumentException('Member ID cannot exceed 50 characters');
        }

        if (strlen($memberId) < 3) {
            throw new InvalidArgumentException('Member ID must be at least 3 characters');
        }

        // Allow alphanumeric, hyphens, underscores
        if (!preg_match('/^[A-Z0-9\-_]+$/i', $memberId)) {
            throw new InvalidArgumentException(
                'Member ID can only contain letters, numbers, hyphens, and underscores'
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(MemberId $other): bool
    {
        return $this->value === $other->value;
    }

    /**
     * Check if member ID follows a specific pattern
     * Useful for party-specific validation
     */
    public function matchesPattern(string $pattern): bool
    {
        return (bool) preg_match($pattern, $this->value);
    }
}
