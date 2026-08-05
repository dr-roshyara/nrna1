<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain;

use InvalidArgumentException;

/**
 * The Election aggregate's OWN identity — this context OWNS the Election, so this VO
 * is the aggregate root's identity, not a foreign reference. (Contrast: Contestation
 * and Adjudication each keep their own local `ElectionId` as a *reference* to an
 * election another context owns — ADR-T16. Same primitive, different semantic role;
 * intentionally not shared, so each can evolve independently.) @immutable
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
