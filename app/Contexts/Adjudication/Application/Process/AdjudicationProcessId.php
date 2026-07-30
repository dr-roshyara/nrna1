<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Process;

use InvalidArgumentException;

/**
 * Identity of one adjudication process.
 *
 * Required because uniqueness binds *active* processes only (EPIC-004K §3 PM-1:
 * "open exactly one **active** process per challenge"), so a challenge may
 * accumulate more than one process over time and `challenge_ref` cannot serve as
 * row identity. Derived implication of approved authority — see the WP-2 plan
 * §13 Business Assumption Review (finding F-T1).
 */
final readonly class AdjudicationProcessId
{
    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('AdjudicationProcessId cannot be empty.');
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
