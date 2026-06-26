<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge;

use InvalidArgumentException;

/**
 * Opaque reference to the standing-holder who raised the challenge.
 * NOT a voter identity and NEVER linked to a vote (Q7 / ADR-T11): a challenge
 * raiser is an identified constitutional actor, distinct from an anonymous voter.
 */
final readonly class RaiserStandingRef
{
    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('RaiserStandingRef cannot be empty.');
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
