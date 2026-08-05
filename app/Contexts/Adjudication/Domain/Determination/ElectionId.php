<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

use InvalidArgumentException;

/**
 * Adjudication's LOCAL identity for the single Election a ContestedOutcome
 * belongs to (ADR-T16: identities cross as strings; each context has its own VO).
 *
 * @immutable
 */
final readonly class ElectionId
{
    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('ElectionId cannot be empty.');
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
