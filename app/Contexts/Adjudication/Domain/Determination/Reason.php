<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

use InvalidArgumentException;

/**
 * The reasoning recorded with a determination (50-05 `reason`). Non-empty.
 */
final readonly class Reason
{
    private function __construct(public string $text)
    {
        if (trim($text) === '') {
            throw new InvalidArgumentException('A determination requires a non-empty reason.');
        }
    }

    public static function fromString(string $text): self
    {
        return new self($text);
    }

    public function toString(): string
    {
        return $this->text;
    }
}
