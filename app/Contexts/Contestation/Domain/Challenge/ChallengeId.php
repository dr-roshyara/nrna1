<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge;

use InvalidArgumentException;

final readonly class ChallengeId
{
    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('ChallengeId cannot be empty.');
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
