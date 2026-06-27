<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\ValueObjects;

final readonly class TransitionReason
{
    private function __construct(private string $value)
    {
        if (empty(trim($value))) {
            throw new \DomainException('TransitionReason cannot be empty');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
