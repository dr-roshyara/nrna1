<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Gate;

use InvalidArgumentException;

/**
 * The NAMED acceptance rule (Specification pattern — EM-GOV-035: the configuration
 * records the named rule, never a numeric percentage). Model A consumes adopted
 * EM-GOV-036 (`TWO_THIRDS_OF_COMMITTEE_VOTES`) DIRECTLY; the permitted-menu
 * artifact is not designed and not built (D-6; EM-OPEN-077/076 concern the
 * representation-vote channel, not participating in Model A). @immutable
 */
final readonly class ThresholdRule
{
    private const TWO_THIRDS_OF_COMMITTEE_VOTES = 'TWO_THIRDS_OF_COMMITTEE_VOTES';

    private function __construct(public string $name)
    {
    }

    public static function twoThirdsOfCommitteeVotes(): self
    {
        return new self(self::TWO_THIRDS_OF_COMMITTEE_VOTES);
    }

    public static function fromName(string $name): self
    {
        if ($name !== self::TWO_THIRDS_OF_COMMITTEE_VOTES) {
            throw new InvalidArgumentException(sprintf(
                'Unknown acceptance rule "%s": Model A consumes adopted EM-GOV-036 directly; no menu exists (D-6).',
                $name,
            ));
        }

        return new self($name);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function requiredVotesFor(int $constitutedSize): RequiredVotes
    {
        return RequiredVotes::forConstitutedSize($constitutedSize);
    }
}
