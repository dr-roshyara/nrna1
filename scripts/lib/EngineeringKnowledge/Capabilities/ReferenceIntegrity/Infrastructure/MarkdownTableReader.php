<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Infrastructure;

use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Application\DocumentTableReader;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\TableContents;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\TableRow;
use RuntimeException;

/**
 * Markdown adapter for the table-row register (S4).
 *
 * Reads the DOCUMENT ITSELF (DR-1 / AP-2: never a projection). Read-only (AP-7 / DR-4).
 *
 * Parsing rules, each pinned to the corpus:
 *   • A row is any non-fence line whose trimmed form starts with `|`. Cell count is the
 *     number of `|`-separated segments after the outer pipes are removed.
 *   • An ESCAPED pipe (`\|`) does not split a row, so a cell holding a literal pipe keeps
 *     an honest count.
 *   • A SEPARATOR row is one whose every cell is dashes/colons (`| --- | :--: |`).
 *   • Table lines INSIDE fenced code blocks are code, not tables — they are skipped.
 *   • Line numbers are 1-based and NEL-safe: `preg_split('/\r\n|\r|\n/')` — `\R` would
 *     treat U+0085 (the corpus's in-line separator) as a line break and inflate numbers.
 *
 * NOT-CHECKED (D-4, stated so the report does not overclaim): a pipe inside an inline
 * code span is not excluded (it would inflate a cell count and could flag a healthy
 * row); blockquote-prefixed tables (`> | … |`) are not observed.
 */
final readonly class MarkdownTableReader implements DocumentTableReader
{
    public function read(string $path): TableContents
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

        $rows = [];
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

            array_push($rows, ...self::tableRowsFrom($line, $number));
        }

        return TableContents::of($rows);
    }

    /** @return list<TableRow> */
    private static function tableRowsFrom(string $line, int $number): array
    {
        if (! str_starts_with(ltrim($line), '|')) {
            return [];
        }

        $cells = self::cells($line);

        if ($cells === []) {
            return [];
        }

        return [TableRow::of(count($cells), $number, self::isSeparatorRow($cells))];
    }

    /**
     * The row's cells: leading whitespace removed so an INDENTED row's outer pipe is
     * still stripped, then split on pipes NOT escaped by a backslash. A `|`-leading
     * line always has at least one segment, so the count is the number of segments.
     *
     * @return list<string>
     */
    private static function cells(string $line): array
    {
        $body = ltrim($line);
        $body = preg_replace('/^\|/', '', $body);
        $body = preg_replace('/\|$/', '', (string) $body);

        $split = preg_split('/(?<!\\\\)\|/', (string) $body);

        return $split === false ? [] : $split;
    }

    /** @param  list<string>  $cells */
    private static function isSeparatorRow(array $cells): bool
    {
        foreach ($cells as $cell) {
            if (preg_match('/^:?-+:?$/', trim($cell)) !== 1) {
                return false;
            }
        }

        return true;
    }
}
