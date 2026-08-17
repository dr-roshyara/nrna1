<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Recovery;

use InvalidArgumentException;

/**
 * Memento of the service policy AT PERIOD START: version + duration, bound once —
 * a later policy change never retroactively alters a running period (EM-GOV-050(b);
 * EM-GOV-014 Part 2; EM-GOV-059(a) recording obligation). The service side supplies
 * ONLY these two values and can never supply a consequence (B-5; EM-OPEN-047
 * resolution). @immutable
 */
final readonly class PolicyBinding
{
    private function __construct(
        public string $policyVersion,
        public int $durationSeconds,
    ) {
        if (trim($policyVersion) === '') {
            throw new InvalidArgumentException('The applicable policy version must be recorded (EM-GOV-014 Part 2).');
        }
        if ($durationSeconds <= 0) {
            throw new InvalidArgumentException('A governed period has a positive duration.');
        }
    }

    public static function of(string $policyVersion, int $durationSeconds): self
    {
        return new self($policyVersion, $durationSeconds);
    }
}
