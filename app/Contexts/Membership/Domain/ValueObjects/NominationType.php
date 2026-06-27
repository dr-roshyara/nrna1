<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * NominationType Value Object
 *
 * Represents how a committee member was nominated/selected for their role.
 * Important for democratic legitimacy tracking.
 *
 * Types:
 * - elected: Via democratic election (requires election_date)
 * - appointed: Appointed by higher authority
 * - volunteered: Self-nominated/volunteered
 */
final class NominationType
{
    private const ELECTED = 'elected';
    private const APPOINTED = 'appointed';
    private const VOLUNTEERED = 'volunteered';

    private const VALID_TYPES = [
        self::ELECTED,
        self::APPOINTED,
        self::VOLUNTEERED,
    ];

    private string $value;

    public function __construct(string $type)
    {
        $normalized = strtolower(trim($type));

        if (!in_array($normalized, self::VALID_TYPES, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid nomination type "%s". Valid types: %s',
                    $type,
                    implode(', ', self::VALID_TYPES)
                )
            );
        }

        $this->value = $normalized;
    }

    public static function fromString(string $type): self
    {
        return new self($type);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(NominationType $other): bool
    {
        return $this->value === $other->value;
    }

    // Factory methods
    public static function elected(): self
    {
        return new self(self::ELECTED);
    }

    public static function appointed(): self
    {
        return new self(self::APPOINTED);
    }

    public static function volunteered(): self
    {
        return new self(self::VOLUNTEERED);
    }

    // Business rule checks
    public function requiresElectionDate(): bool
    {
        return $this->value === self::ELECTED;
    }

    public function isElected(): bool
    {
        return $this->value === self::ELECTED;
    }

    public function isAppointed(): bool
    {
        return $this->value === self::APPOINTED;
    }

    public function isVolunteered(): bool
    {
        return $this->value === self::VOLUNTEERED;
    }
}