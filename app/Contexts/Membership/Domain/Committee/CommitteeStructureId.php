<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

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
        // Pure-PHP ULID (Crockford base32): 48-bit ms timestamp + 80 bits randomness.
        // Domain layer must not depend on Illuminate\Support\Str.
        $alphabet = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';

        $time = (int) (microtime(true) * 1000);
        $timePart = '';
        for ($i = 0; $i < 10; $i++) {
            $timePart = $alphabet[$time % 32] . $timePart;
            $time = intdiv($time, 32);
        }

        $randomPart = '';
        for ($i = 0; $i < 16; $i++) {
            $randomPart .= $alphabet[random_int(0, 31)];
        }

        return new self($timePart . $randomPart);
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
