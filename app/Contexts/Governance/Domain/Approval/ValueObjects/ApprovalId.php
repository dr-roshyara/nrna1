<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Approval\ValueObjects;

use Ramsey\Uuid\Uuid;

final readonly class ApprovalId
{
    private function __construct(private string $value) {}

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function from(string $value): self
    {
        if (trim($value) === '') {
            throw new \DomainException('ApprovalId cannot be empty');
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
