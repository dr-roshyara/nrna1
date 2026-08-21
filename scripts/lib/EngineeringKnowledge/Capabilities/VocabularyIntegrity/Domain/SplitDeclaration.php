<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain;

use InvalidArgumentException;

/**
 * One observed split declaration (S5) — a document line declaring a disposition to be
 * TWO-BRANCH: "THE MISMATCH DISPOSITION IS SPLIT … never one branch for both."
 *
 * The heuristic competes disposition rows against these lines by shared trigger tokens:
 * an unlabelled single-remedy row whose trigger overlaps a declared split is the DI-4
 * shape (two competing current definitions, which §0.4.4 forbids).
 */
final readonly class SplitDeclaration
{
    private function __construct(
        private string $text,
        private int $line,
        private string $section,
    ) {
    }

    public static function of(string $text, int $line, string $section): self
    {
        if (trim($text) === '') {
            throw new InvalidArgumentException('A split declaration must carry text.');
        }

        if ($line < 1) {
            throw new InvalidArgumentException('A split declaration must carry a 1-based line number.');
        }

        if (trim($section) === '') {
            throw new InvalidArgumentException('A split declaration must carry its section.');
        }

        return new self($text, $line, $section);
    }

    public function text(): string
    {
        return $this->text;
    }

    public function line(): int
    {
        return $this->line;
    }

    public function section(): string
    {
        return $this->section;
    }
}
