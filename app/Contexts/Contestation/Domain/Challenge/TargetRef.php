<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge;

use InvalidArgumentException;

/**
 * What is being challenged — a reference to an election outcome or a prior
 * determination. Reference only; carries no vote content.
 */
final readonly class TargetRef
{
    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('TargetRef cannot be empty.');
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
}
