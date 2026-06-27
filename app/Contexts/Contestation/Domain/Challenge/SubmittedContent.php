<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge;

use InvalidArgumentException;

/**
 * The challenge's submitted argument/claims (ChallengeContentValidation guard).
 * Content, not a workflow object. Must be non-empty to raise a challenge.
 */
final readonly class SubmittedContent
{
    private function __construct(public string $claim)
    {
        if (trim($claim) === '') {
            throw new InvalidArgumentException('A challenge requires non-empty submitted content.');
        }
    }

    public static function fromString(string $claim): self
    {
        return new self($claim);
    }

    public function toString(): string
    {
        return $this->claim;
    }
}
