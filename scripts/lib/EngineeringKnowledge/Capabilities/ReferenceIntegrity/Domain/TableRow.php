<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain;

use InvalidArgumentException;

/**
 * The observed shape of ONE Markdown table row (S4).
 *
 * What the reader OBSERVES: how many cells the row carries, on which line, and whether
 * it is a SEPARATOR row (every cell is dashes/colons — `| --- | --- |`). Whether those
 * observations are a DEFECT is the domain service's call — a row is never judged here.
 *
 * Read-only (DR-1 / AP-7): this value object authors, edits, mints nothing.
 */
final readonly class TableRow
{
    private function __construct(
        private int $cellCount,
        private int $line,
        private bool $isSeparator,
    ) {
    }

    public static function of(int $cellCount, int $line, bool $isSeparator): self
    {
        if ($cellCount < 1) {
            throw new InvalidArgumentException(
                "a table row must carry at least one cell, got {$cellCount}",
            );
        }

        if ($line < 1) {
            throw new InvalidArgumentException(
                "a table row line must be 1-based, got {$line}",
            );
        }

        return new self($cellCount, $line, $isSeparator);
    }

    public function cellCount(): int
    {
        return $this->cellCount;
    }

    public function line(): int
    {
        return $this->line;
    }

    public function isSeparator(): bool
    {
        return $this->isSeparator;
    }
}
