<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain;

use InvalidArgumentException;

/**
 * A step the document's mandated internal-order block DEFINES (S2).
 *
 * The step register is the set of steps the normative enumeration — the fenced
 * "Phase 5, mandated internal order" block — defines. A `step N` reference RESOLVES iff
 * `N` is in this register; the AMD5 `step 5` references dangle because AMD5's block
 * defines `1 · 2 · 3 · 4` only.
 *
 * Same grammar as StepReference: an integer optionally carrying a letter variant (`1b`),
 * never a decimal. The VO protects that invariant so a caller cannot mint a malformed
 * register entry.
 */
final readonly class StepDefinition
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
            throw new InvalidArgumentException("Not a step definition: '{$step}'");
        }

        if ($line < 1) {
            throw new InvalidArgumentException('Step definition lines are 1-based.');
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
