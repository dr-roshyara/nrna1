<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * CommitteeStatus Value Object
 *
 * Represents the lifecycle status of a committee.
 * Immutable and self-validating.
 *
 * Committee Statuses:
 * - ACTIVE: Committee is active and can assign members
 * - INACTIVE: Committee is not active but record is preserved
 * - DISSOLVED: Committee has been dissolved (terminal state)
 */
final class CommitteeStatus
{
    private const ACTIVE = 'active';
    private const INACTIVE = 'inactive';
    private const DISSOLVED = 'dissolved';

    private const VALID_STATUSES = [
        self::ACTIVE,
        self::INACTIVE,
        self::DISSOLVED,
    ];

    private string $value;

    private function __construct(string $status)
    {
        $normalized = strtolower(trim($status));

        if (!in_array($normalized, self::VALID_STATUSES, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid committee status "%s". Valid statuses: %s',
                    $status,
                    implode(', ', self::VALID_STATUSES)
                )
            );
        }

        $this->value = $normalized;
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function inactive(): self
    {
        return new self(self::INACTIVE);
    }

    public static function dissolved(): self
    {
        return new self(self::DISSOLVED);
    }

    public static function fromString(string $status): self
    {
        return new self($status);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(CommitteeStatus $other): bool
    {
        return $this->value === $other->value;
    }

    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->value === self::INACTIVE;
    }

    public function isDissolved(): bool
    {
        return $this->value === self::DISSOLVED;
    }

    public function canAssignMembers(): bool
    {
        return $this->isActive();
    }
}
