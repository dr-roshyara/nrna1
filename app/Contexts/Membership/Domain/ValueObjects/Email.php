<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Email Value Object
 *
 * Represents a valid email address in the Membership domain.
 * Immutable and self-validating.
 */
final class Email
{
    private string $value;

    public function __construct(string $email)
    {
        $this->validate($email);
        $this->value = strtolower(trim($email));
    }

    private function validate(string $email): void
    {
        $email = trim($email);

        if (empty($email)) {
            throw new InvalidArgumentException('Email cannot be empty');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format: {$email}");
        }

        // Additional business rules
        if (strlen($email) > 255) {
            throw new InvalidArgumentException('Email cannot exceed 255 characters');
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

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }
}
