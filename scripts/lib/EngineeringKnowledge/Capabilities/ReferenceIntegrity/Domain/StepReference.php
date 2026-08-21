<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain;

use InvalidArgumentException;

/**
 * A `step N` reference found in a document — the S2 step-reference register.
 *
 * The corpus cites steps as `step 3`, `Phase 5 step 5`, `steps 3→5`: a step is an
 * integer optionally carrying a letter variant (`1b`), never a decimal. This VO carries
 * one referenced step and the line it appears on, so an assessment can state WHERE a
 * reference is. Whether the step is DEFINED is the domain service's job.
 */
final readonly class StepReference
{
    private function __construct(
        private string $step,
        private int $line,
    ) {
    }

    public static function fromStepLine(string $step, int $line): self
    {
        $step = trim($step);

        if (preg_match('/^\d+[a-z]?$/i', $step) !== 1) {
            throw new InvalidArgumentException("Not a step reference: '{$step}'");
        }

        if ($line < 1) {
            throw new InvalidArgumentException('Step reference lines are 1-based.');
        }

        return new self($step, $line);
    }

    public function step(): string
    {
        return $this->step;
    }

    public function line(): int
    {
        return $this->line;
    }
}
