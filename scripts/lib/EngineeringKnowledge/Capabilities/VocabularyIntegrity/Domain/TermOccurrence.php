<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain;

use InvalidArgumentException;

/**
 * One occurrence of a RETIRED term the reader found (S3).
 *
 * The reader classifies each occurrence CITED or LIVE against the document's own
 * citation constructs (quoted superseded wording, a `§N Phase 2b` recap label, a
 * `Traceability (` block). Whether a LIVE occurrence is a DEFECT is the domain
 * service's call under DP-3 — this VO only carries the observation and its class.
 */
final readonly class TermOccurrence
{
    private function __construct(
        private string $term,
        private int $line,
        private bool $cited,
    ) {
    }

    public static function of(string $term, int $line, bool $cited): self
    {
        $term = trim($term);

        if ($term === '') {
            throw new InvalidArgumentException('A retired term cannot be empty.');
        }

        if ($line < 1) {
            throw new InvalidArgumentException('Term occurrence lines are 1-based.');
        }

        return new self($term, $line, $cited);
    }

    public function term(): string
    {
        return $this->term;
    }

    public function line(): int
    {
        return $this->line;
    }

    public function cited(): bool
    {
        return $this->cited;
    }
}
