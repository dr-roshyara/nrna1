<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Member\ValueObjects;

final readonly class PersonalInfo
{
    private function __construct(
        private string $fullName,
        private string $email,
        private ?string $phone = null
    ) {
        if (empty($fullName)) {
            throw new \InvalidArgumentException('Full name cannot be empty');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format: {$email}");
        }

        if ($phone && !$this->isValidPhone($phone)) {
            throw new \InvalidArgumentException("Invalid phone format: {$phone}");
        }
    }

    public static function create(string $fullName, string $email, ?string $phone = null): self
    {
        return new self($fullName, $email, $phone);
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function equals(self $other): bool
    {
        return $this->fullName === $other->fullName
            && $this->email === $other->email
            && $this->phone === $other->phone;
    }

    private function isValidPhone(string $phone): bool
    {
        // Accept international format with +, digits, hyphens, spaces
        return preg_match('/^\+?[1-9]\d{1,14}(-|\s)?\d{1,14}$/', preg_replace('/[\s\-]/', '', $phone)) === 1;
    }
}
