<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Role Value Object
 *
 * Represents a committee role (chairperson, secretary, member, etc.).
 * Immutable and self-validating.
 */
final class Role
{
    private const ALLOWED_ROLES = [
        'chairperson',
        'secretary',
        'treasurer',
        'member',
        'vice_chairperson',
        'coordinator',
        'advisor',
    ];

    private string $value;

    public function __construct(string $value)
    {
        $normalized = strtolower(trim($value));

        if (!in_array($normalized, self::ALLOWED_ROLES, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid role "%s". Allowed roles: %s',
                    $value,
                    implode(', ', self::ALLOWED_ROLES)
                )
            );
        }

        $this->value = $normalized;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(Role $other): bool
    {
        return $this->value === $other->value;
    }

    // Convenience factory methods
    public static function chairperson(): self
    {
        return new self('chairperson');
    }

    public static function secretary(): self
    {
        return new self('secretary');
    }

    public static function treasurer(): self
    {
        return new self('treasurer');
    }

    public static function member(): self
    {
        return new self('member');
    }

    public static function viceChairperson(): self
    {
        return new self('vice_chairperson');
    }

    public static function coordinator(): self
    {
        return new self('coordinator');
    }

    public static function advisor(): self
    {
        return new self('advisor');
    }
}