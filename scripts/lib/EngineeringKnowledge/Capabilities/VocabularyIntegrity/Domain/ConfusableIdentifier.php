<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain;

use InvalidArgumentException;

/**
 * One single-glyph identifier token the reader found (S3).
 *
 * The corpus's identifier family is `CASE` — `CASE B`, `CASE β`, `CASE A`. The glyph
 * is the SINGLE character after the family; its homoglyph (β→B) is what can make two
 * identifiers confusable. The family matters: a `CASE β` and a `STEP B` never collide.
 */
final readonly class ConfusableIdentifier
{
    private function __construct(
        private string $family,
        private string $glyph,
        private int $line,
    ) {
    }

    public static function of(string $family, string $glyph, int $line): self
    {
        $family = trim($family);

        if ($family === '') {
            throw new InvalidArgumentException('An identifier family cannot be empty.');
        }

        if (mb_strlen($glyph, 'UTF-8') !== 1) {
            throw new InvalidArgumentException(
                "An identifier glyph is a single grapheme, got '{$glyph}'.",
            );
        }

        if ($line < 1) {
            throw new InvalidArgumentException('Identifier lines are 1-based.');
        }

        return new self($family, $glyph, $line);
    }

    public function family(): string
    {
        return $this->family;
    }

    public function glyph(): string
    {
        return $this->glyph;
    }

    public function line(): int
    {
        return $this->line;
    }
}
