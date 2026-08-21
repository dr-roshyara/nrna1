<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application\DocumentContentsReader;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\DocumentSectionIdentifier;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\DocumentSectionSequence;
use RuntimeException;

/**
 * Reads a document's numbered section headings directly from the document (S1).
 *
 * ⛔ DR-1 / AP-2: reads the DOCUMENT ITSELF. No cached index, no projection.
 * ⛔ AP-4:        observes an existing document; creates no source of truth.
 * ⛔ AP-7 / DR-4: read-only throughout.
 *
 * A numbered section identifier is the leading numeric token of a heading —
 * `## 4.1 ⭐ Phase 5` → `4.1`. Two constructs are NOT identifiers and are skipped,
 * so the register stays clean against the corpus's own shapes:
 *   headings inside fenced code blocks (quoted code, not normative sections)
 *   blockquote headings (`> ## …` — quoted text, not the document's own register)
 *
 * Replaceable: the port is `DocumentContentsReader`; a YAML or graph reader
 * substitutes here without the domain changing.
 */
final readonly class MarkdownDocumentContentsReader implements DocumentContentsReader
{
    public function read(string $path): DocumentSectionSequence
    {
        if (! is_readable($path)) {
            throw new RuntimeException("document not readable at {$path}");
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException("document could not be read at {$path}");
        }

        // Split on ACTUAL newlines only — `\R` also matches exotic Unicode linebreaks
        // (NEL U+0085, form feed, …) which the corpus uses as separators INSIDE lines.
        // Splitting on them would inflate every line number after the first separator.
        $lines = preg_split('/\r\n|\r|\n/', $contents);
        $identifiers = [];
        $inFence = false;

        foreach ($lines as $index => $line) {
            $trimmed = ltrim($line);

            // Fenced code blocks are content, not headings — their `#` lines are skipped.
            if (str_starts_with($trimmed, '```')) {
                $inFence = ! $inFence;
                continue;
            }

            if ($inFence) {
                continue;
            }

            if (preg_match('/^#{1,6}\s+(.+)$/', $trimmed, $m) !== 1) {
                continue;
            }

            $heading = preg_replace('/\s*#+\s*$/', '', $m[1]); // ATX closing hashes

            if (preg_match('/^(\d+(?:\.\d+)*)(?:\s+|$)/', $heading, $n) === 1) {
                $identifiers[] = DocumentSectionIdentifier::fromNumberLine($n[1], $index + 1);
            }
        }

        return DocumentSectionSequence::fromIdentifiers($identifiers);
    }
}
