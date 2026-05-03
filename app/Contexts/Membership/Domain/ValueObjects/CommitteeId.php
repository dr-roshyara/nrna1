<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * CommitteeId Value Object
 *
 * Represents a committee identifier (system ULID).
 *
 * Design Principles:
 * - Immutable by design
 * - Self-validating
 * - Uses ULID for temporal ordering
 * - Comparable via equals() method
 *
 * Committee IDs are system-generated, not party-defined.
 * For party-defined committee codes, use separate CommitteeCode value object.
 */
final class CommitteeId
{
    private string $value;

    public function __construct(string $committeeId)
    {
        $this->validate($committeeId);
        $this->value = $committeeId;
    }

    /**
     * Generate a new CommitteeId with ULID
     */
    public static function generate(): self
    {
        return new self((string) Str::ulid());
    }

    /**
     * Create CommitteeId from string value
     *
     * @param string $value ULID string
     * @return self
     * @throws InvalidArgumentException If value is invalid
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    private function validate(string $committeeId): void
    {
        $committeeId = trim($committeeId);

        if (empty($committeeId)) {
            throw new InvalidArgumentException('Committee ID cannot be empty');
        }

        // ULID validation: 26 characters, base32 encoded
        if (!preg_match('/^[0-9A-HJKMNP-TV-Z]{26}$/i', $committeeId)) {
            throw new InvalidArgumentException(
                'Committee ID must be a valid ULID (26 characters, base32)'
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

    public function equals(CommitteeId $other): bool
    {
        return $this->value === $other->value;
    }
}