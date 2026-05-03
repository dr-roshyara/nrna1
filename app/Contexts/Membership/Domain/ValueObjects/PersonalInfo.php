<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * PersonalInfo Value Object
 *
 * Represents personal information of a member.
 * Immutable and self-validating.
 */
final class PersonalInfo
{
    private string $fullName;
    private Email $email;
    private ?string $phone;

    public function __construct(
        string $fullName,
        Email $email,
        ?string $phone = null
    ) {
        $this->validateFullName($fullName);
        $this->fullName = trim($fullName);
        $this->email = $email;
        $this->phone = $phone ? trim($phone) : null;

        if ($this->phone !== null) {
            $this->validatePhone($this->phone);
        }
    }

    private function validateFullName(string $fullName): void
    {
        $fullName = trim($fullName);

        if (empty($fullName)) {
            throw new InvalidArgumentException('Full name cannot be empty');
        }

        if (strlen($fullName) < 2) {
            throw new InvalidArgumentException('Full name must be at least 2 characters');
        }

        if (strlen($fullName) > 255) {
            throw new InvalidArgumentException('Full name cannot exceed 255 characters');
        }
    }

    private function validatePhone(?string $phone): void
    {
        if ($phone === null) {
            return;
        }

        if (strlen($phone) > 20) {
            throw new InvalidArgumentException('Phone number cannot exceed 20 characters');
        }

        // Basic phone validation - digits, spaces, hyphens, plus sign
        if (!preg_match('/^[0-9\s\-\+\(\)]+$/', $phone)) {
            throw new InvalidArgumentException('Invalid phone number format');
        }
    }

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function phone(): ?string
    {
        return $this->phone;
    }

    public function toArray(): array
    {
        return [
            'full_name' => $this->fullName,
            'email' => $this->email->value(),
            'phone' => $this->phone,
        ];
    }

    public function equals(PersonalInfo $other): bool
    {
        return $this->fullName === $other->fullName
            && $this->email->equals($other->email)
            && $this->phone === $other->phone;
    }
}
