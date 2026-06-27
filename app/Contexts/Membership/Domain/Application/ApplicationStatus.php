<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Application;

final readonly class ApplicationStatus
{
    private function __construct(private string $value)
    {
        if (!in_array($value, ['draft', 'submitted', 'approved', 'rejected'])) {
            throw new \InvalidArgumentException("Invalid ApplicationStatus: {$value}");
        }
    }

    public static function draft(): self
    {
        return new self('draft');
    }

    public static function submitted(): self
    {
        return new self('submitted');
    }

    public static function approved(): self
    {
        return new self('approved');
    }

    public static function rejected(): self
    {
        return new self('rejected');
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isDraft(): bool
    {
        return $this->value === 'draft';
    }

    public function isSubmitted(): bool
    {
        return $this->value === 'submitted';
    }

    public function isApproved(): bool
    {
        return $this->value === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->value === 'rejected';
    }

    public function equals(ApplicationStatus $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
