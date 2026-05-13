<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * CommitteeStructureId Value Object
 *
 * Represents a committee structure identifier (ULID).
 * Immutable and self-validating.
 */
final class CommitteeStructureId
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    public static function generate(): self
    {
        return new self((string) Str::ulid());
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    private function validate(string $value): void
    {
        $value = trim($value);

        if (empty($value)) {
            throw new InvalidArgumentException('CommitteeStructureId cannot be empty');
        }

        if (!preg_match('/^[0-9A-HJKMNP-TV-Z]{26}$/i', $value)) {
            throw new InvalidArgumentException(
                'CommitteeStructureId must be a valid ULID (26 characters, base32)'
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

    public function equals(CommitteeStructureId $other): bool
    {
        return $this->value === $other->value;
    }
}
