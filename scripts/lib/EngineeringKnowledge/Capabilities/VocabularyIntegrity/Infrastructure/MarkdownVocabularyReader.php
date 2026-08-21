<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Infrastructure;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application\DocumentVocabularyReader;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\ConfusableIdentifier;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DeclaredVocabulary;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\TermOccurrence;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\VocabularyContents;
use RuntimeException;

/**
 * Markdown adapter for the vocabulary scan (S3).
 *
 * Reads the DOCUMENT ITSELF (DR-1 / AP-2: never a projection). Read-only (AP-7 / DR-4).
 *
 * Parsing rules, each pinned to the corpus:
 *   • A retired term matches only STANDALONE — not preceded by a letter/digit and not
 *     followed by one or a hyphen, so `Phase 2b-pin` is a DISTINCT term, never flagged.
 *   • An occurrence is CITED when its line carries a citation construct: it is inside
 *     straight double-quotes (`*"Phase 2b"*` — quoted superseded wording), it is a
 *     §-SCOPED label (`§4 Phase 2b — …`, the recap-catalog row), or the line opens a
 *     `Traceability (` block. Everything else is LIVE (the AMD4 defect shape).
 *   • Confusable identifiers are the corpus's `CASE <single glyph>` tokens, Latin or
 *     Greek, from non-fence lines INCLUDING blockquotes (AMD5's live `CASE β` rows).
 *   • The collision declaration is the document carrying the backticked integrity
 *     label the config names (`` `DI-7` `` — AMD6 carries it 13 times; AMD5 zero).
 *   • Line numbers are 1-based and NEL-safe: `preg_split('/\r\n|\r|\n/')` — `\R` would
 *     treat U+0085 (the corpus's in-line separator) as a line break and inflate numbers.
 *
 * NOT-CHECKED (D-4, stated so the report does not overclaim): bare (unbackticked)
 * declaration labels are not counted; Greek glyphs outside the HomoglyphMap never
 * collide; a per-occurrence repair check is not performed — a declared collision is
 * taken as qualified.
 */
final readonly class MarkdownVocabularyReader implements DocumentVocabularyReader
{
    public function read(string $path, DeclaredVocabulary $vocabulary): VocabularyContents
    {
        if (! is_readable($path)) {
            throw new RuntimeException("document not readable at {$path}");
        }

        $markdown = file_get_contents($path);

        if ($markdown === false) {
            throw new RuntimeException("document could not be read at {$path}");
        }

        $lines = preg_split('/\r\n|\r|\n/', $markdown);

        if ($lines === false) {
            throw new RuntimeException("Unable to split document '{$path}'.");
        }

        $occurrences = [];
        $identifiers = [];
        $collisionDeclared = false;
        $inFence = false;

        foreach ($lines as $index => $line) {
            $number = $index + 1;

            if (str_starts_with(ltrim($line), '```')) {
                $inFence = !$inFence;
                continue;
            }

            if ($inFence) {
                continue;
            }

            foreach ($vocabulary->retiredTerms() as $term) {
                array_push($occurrences, ...self::staleTermOccurrencesFrom($line, $number, $term));
            }

            array_push($identifiers, ...self::confusableIdentifiersFrom($line, $number));

            if (self::declaresCollision($line, $vocabulary->confusableDeclarationLabel())) {
                $collisionDeclared = true;
            }
        }

        return VocabularyContents::of($occurrences, $identifiers, $collisionDeclared);
    }

    /**
     * @return list<TermOccurrence>
     */
    private static function staleTermOccurrencesFrom(string $line, int $number, string $term): array
    {
        $pattern = '/(?<![\p{L}\p{N}])'.preg_quote($term, '/').'(?![A-Za-z0-9-])/u';

        if (preg_match_all($pattern, $line, $matches, PREG_OFFSET_CAPTURE) === false) {
            return [];
        }

        $occurrences = [];

        foreach ($matches[0] as [$text, $offset]) {
            $occurrences[] = TermOccurrence::of($term, $number, self::isCitedOnLine($line, $offset));
        }

        return $occurrences;
    }

    /**
     * The citation constructs that make an occurrence CITED (DP-3: qualified). Offsets
     * are byte offsets, and every check here is byte-consistent with the line itself.
     */
    private static function isCitedOnLine(string $line, int $offset): bool
    {
        if (str_contains($line, 'Traceability (')) {
            return true;
        }

        $prefix = substr($line, 0, $offset);

        // §-scoped label: `§4 Phase 2b — …` (the recap-catalog row).
        if (preg_match('/§\d+(?:\.\d+)*[[:space:]]+$/u', $prefix) === 1) {
            return true;
        }

        // Inside straight double-quotes: `*"Phase 2b"*` (quoted superseded wording).
        return substr_count($prefix, '"') % 2 === 1;
    }

    /**
     * @return list<ConfusableIdentifier>
     */
    private static function confusableIdentifiersFrom(string $line, int $number): array
    {
        if (preg_match_all('/\bCASE[[:space:]]+([A-Zα-ω])(?![A-Za-z])/u', $line, $matches, PREG_SET_ORDER) === false) {
            return [];
        }

        $identifiers = [];

        foreach ($matches as $match) {
            $identifiers[] = ConfusableIdentifier::of('CASE', $match[1], $number);
        }

        return $identifiers;
    }

    /** The collision declaration is the backticked integrity label (`DI-7`). */
    private static function declaresCollision(string $line, string $label): bool
    {
        return str_contains($line, '`'.$label.'`');
    }
}
