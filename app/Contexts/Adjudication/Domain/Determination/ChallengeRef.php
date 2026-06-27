<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

use InvalidArgumentException;

/**
 * Opaque reference to the Challenge being adjudicated. Adjudication-LOCAL by
 * design: it does NOT import Contestation's ChallengeId (context isolation,
 * TP-1 — contexts collaborate via events, not shared domain types). The id
 * string crosses the boundary via the command/event payload.
 */
final readonly class ChallengeRef
{
    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('ChallengeRef cannot be empty.');
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
