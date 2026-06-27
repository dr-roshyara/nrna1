<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Jurisdiction;

final readonly class GovernanceJurisdictionId
{
    private function __construct(private string $value)
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('GovernanceJurisdictionId cannot be empty');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
