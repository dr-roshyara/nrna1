<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain;

use InvalidArgumentException;

/**
 * A `§N` reference found in a document (S2).
 *
 * The corpus cites sections as `§4.1`, `§18`, `its §13` — the number grammar is the
 * same as the heading grammar (a target the document could name), so a `§N` can be
 * matched against the heading register CAP-001's reader produced. This VO carries the
 * referenced number and the line it appears on, so an assessment can state WHERE a
 * reference is. Whether it RESOLVES is the domain service's job (DP-4).
 */
final readonly class SectionReference
{
    private function __construct(
        private string $number,
        private int $line,
    ) {
    }

    public static function fromNumberLine(string $number, int $line): self
    {
        $number = trim($number);

        if (preg_match('/^\d+(?:\.\d+)*$/', $number) !== 1) {
            throw new InvalidArgumentException("Not a section reference: '{$number}'");
        }

        if ($line < 1) {
            throw new InvalidArgumentException('Section reference lines are 1-based.');
        }

        return new self($number, $line);
    }

    public function number(): string
    {
        return $this->number;
    }

    public function line(): int
    {
        return $this->line;
    }
}
