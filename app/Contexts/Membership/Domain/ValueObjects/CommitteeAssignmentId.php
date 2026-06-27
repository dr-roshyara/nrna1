<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * CommitteeAssignmentId Value Object
 *
 * Represents a committee assignment identifier (system ULID).
 *
 * Design Principles:
 * - Immutable by design
 * - Self-validating
 * - Uses ULID for temporal ordering
 * - Comparable via equals() method
 */
final class CommitteeAssignmentId
{
    private string $value;

    public function __construct(string $assignmentId)
    {
        $this->validate($assignmentId);
        $this->value = $assignmentId;
    }

    /**
     * Generate a new CommitteeAssignmentId with ULID
     */
    public static function generate(): self
    {
        return new self((string) Str::ulid());
    }

    /**
     * Create from string (alias for constructor)
     */
    public static function fromString(string $assignmentId): self
    {
        return new self($assignmentId);
    }

    private function validate(string $assignmentId): void
    {
        $assignmentId = trim($assignmentId);

        if (empty($assignmentId)) {
            throw new InvalidArgumentException('Committee assignment ID cannot be empty');
        }

        // ULID validation: 26 characters, base32 encoded
        if (!preg_match('/^[0-9A-HJKMNP-TV-Z]{26}$/i', $assignmentId)) {
            throw new InvalidArgumentException(
                'Committee assignment ID must be a valid ULID (26 characters, base32)'
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

    public function equals(CommitteeAssignmentId $other): bool
    {
        return $this->value === $other->value;
    }
}