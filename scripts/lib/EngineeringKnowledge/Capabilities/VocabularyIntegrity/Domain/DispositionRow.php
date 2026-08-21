<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain;

use InvalidArgumentException;

/**
 * One observed disposition construct (S5) — the OBSERVATION side of the heuristic.
 *
 * A disposition construct is a row whose non-first cells state a normative remedy for a
 * trigger ("at Phase 7, on a FINAL re-hash mismatch" → "Removal is refused until …").
 * `labelled` and `twoBranch` are CLASSED by the reader against the superseded-marker and
 * two-branch families; whether either makes the row a defect is the domain service's call
 * (same shape as S3's TermOccurrence, which the reader classes cited/live).
 *
 * This is the read side only. Nothing here authors, edits, or mints (DR-1 / AP-7).
 */
final readonly class DispositionRow
{
    private function __construct(
        private string $trigger,
        private string $remedy,
        private int $line,
        private string $section,
        private bool $labelled,
        private bool $twoBranch,
    ) {
    }

    public static function of(
        string $trigger,
        string $remedy,
        int $line,
        string $section,
        bool $labelled = false,
        bool $twoBranch = false,
    ): self {
        if (trim($trigger) === '') {
            throw new InvalidArgumentException('A disposition row must name a trigger.');
        }

        if (trim($remedy) === '') {
            throw new InvalidArgumentException('A disposition row must state a remedy.');
        }

        if ($line < 1) {
            throw new InvalidArgumentException('A disposition row must carry a 1-based line number.');
        }

        if (trim($section) === '') {
            throw new InvalidArgumentException('A disposition row must carry its section.');
        }

        return new self($trigger, $remedy, $line, $section, $labelled, $twoBranch);
    }

    public function trigger(): string
    {
        return $this->trigger;
    }

    public function remedy(): string
    {
        return $this->remedy;
    }

    public function line(): int
    {
        return $this->line;
    }

    public function section(): string
    {
        return $this->section;
    }

    public function labelled(): bool
    {
        return $this->labelled;
    }

    public function twoBranch(): bool
    {
        return $this->twoBranch;
    }
}
