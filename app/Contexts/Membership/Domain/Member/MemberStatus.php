<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Member;

final readonly class MemberStatus
{
    private const ACTIVE = 'active';
    private const INACTIVE = 'inactive';
    private const SUSPENDED = 'suspended';
    private const ARCHIVED = 'archived';

    private function __construct(private string $value)
    {
        if (!in_array($value, [self::ACTIVE, self::INACTIVE, self::SUSPENDED, self::ARCHIVED], true)) {
            throw new \InvalidArgumentException("Invalid member status: {$value}");
        }
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function inactive(): self
    {
        return new self(self::INACTIVE);
    }

    public static function suspended(): self
    {
        return new self(self::SUSPENDED);
    }

    public static function archived(): self
    {
        return new self(self::ARCHIVED);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->value === self::INACTIVE;
    }

    public function isSuspended(): bool
    {
        return $this->value === self::SUSPENDED;
    }

    public function isArchived(): bool
    {
        return $this->value === self::ARCHIVED;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function canTransitionTo(self $newStatus): bool
    {
        // Archived members cannot transition to any other state
        if ($this->isArchived()) {
            return false;
        }

        // Can transition from any non-archived state to any other state
        return true;
    }
}
