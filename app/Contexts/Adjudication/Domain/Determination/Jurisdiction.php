<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

use InvalidArgumentException;

/**
 * The jurisdiction under which the determination is issued (50-05 `jurisdiction`).
 */
final readonly class Jurisdiction
{
    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('A determination requires a jurisdiction.');
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
