<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Infrastructure;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application\DispositionContentsReader;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DispositionContents;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DispositionRow;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\SplitDeclaration;
use RuntimeException;

/**
 * Markdown adapter for the disposition scan (S5).
 *
 * Reads the DOCUMENT ITSELF (DR-1 / AP-2: never a projection). Read-only (AP-7 / DR-4).
 *
 * Parsing rules, each pinned to the corpus:
 *   • A DISPOSITION ROW is a table row whose non-first cells carry a normative disposition
 *     phrase (never · must · is refused · is quarantined · is suspended · stop · cannot ·
 *     quarantine · escalate · removal is — case-insensitive).
 *   • A row is LABELLED when any of its cells carries a superseded marker (superseded ·
 *     withdrawn · labelled history · per §0.4.4).
 *   • A row is TWO-BRANCH when its remedy carries a two-branch marker (CASE α/β/A/B ·
 *     PRE-SWITCH · POST-DEMOTION · PRE/POST-writer-switch).
 *   • A SPLIT DECLARATION is any line declaring a disposition to be two-branch — the
 *     corpus's genuine forms only: "…IS SPLIT", "…split into…", "One trigger, two
 *     causes", "never one branch (for both)". The bare word "split" is NOT a marker
 *     (mechanism splits like "RC-1's split" are not disposition splits). Declarations
 *     are recorded from headings, prose AND table rows — §4.4's register and the
 *     phase-table row declare the split from inside tables, the §4.4 heading from a
 *     heading.
 *   • The document DECLARES the canonical-document rule (§0.4.4) when it carries the
 *     `0.4.4` heading AND the "no two competing current definitions" statement.
 *   • Section context is the current NUMBERED heading (`8` from `# 8 · Rollback`, `4.4`
 *     from `## 4.4 …`); an unnumbered sub-heading (`### 🔴 ⭐ AMD5 (RD-3) …`) stays in
 *     its parent section.
 *   • Table buffering: a SEPARATOR row (`|---|---|`) drops the buffered header, so the
 *     header is never mistaken for a disposition row; buffered rows flush at the next
 *     non-table line or at end of document.
 *   • Line numbers are 1-based and NEL-safe: `preg_split('/\r\n|\r|\n/')` — `\R` would
 *     treat U+0085 (the corpus's in-line separator) as a line break and inflate numbers.
 *   • Fenced code blocks are skipped (code, not document structure).
 *
 * NOT-CHECKED (D-4, stated so the report does not overclaim): a pipe inside an inline code
 * span is not excluded; blockquote-prefixed tables and indented code blocks are not
 * observed; a split declaration INSIDE a table cell is not recorded (only prose lines);
 * and — the heuristic's bound — an unlabelled stale disposition is invisible when NO split
 * of the same trigger is declared anywhere in the document.
 */
final readonly class MarkdownDispositionReader implements DispositionContentsReader
{
    /** Normative remedies that mark a table row as a disposition construct. */
    private const DISPOSITION_MARKERS = [
        'never', 'must', 'is refused', 'is quarantined', 'is suspended', 'stop', 'cannot',
        'quarantine', 'escalat', 'removal is',
    ];

    /** Markers that class a row as retained-history wording (§0.4.4: superseded only where labelled). */
    private const SUPERSEDED_MARKERS = [
        'superseded', 'withdrawn', 'labelled history', 'per §0.4.4',
    ];

    /** Markers that class a row as already carrying the two-branch shape. */
    private const TWO_BRANCH_MARKERS = [
        'CASE α', 'CASE β', 'CASE A', 'CASE B', 'PRE-SWITCH', 'POST-DEMOTION',
        'PRE-writer-switch', 'POST-writer-switch',
    ];

    /** Phrases that DECLARE a disposition split — pinned to the corpus's §4.4 register and
     *  phase-table rows. The bare word "split" is deliberately NOT a marker: the corpus uses
     *  it for mechanism splits ("RC-1's split", "the boundary check had to be split") that
     *  do not declare a disposition split and would add competing lines that share no trigger. */
    private const SPLIT_MARKERS = [
        'is split', 'split into', 'one trigger, two causes', 'never one branch',
    ];

    public function read(string $path): DispositionContents
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
        $splits = [];
        $buffer = [];
        $section = '';
        $policyHeading = false;
        $policyStatement = false;
        $inFence = false;

        foreach ($lines as $index => $line) {
            $number = $index + 1;

            if (str_starts_with(ltrim($line), '```')) {
                $inFence = ! $inFence;
                continue;
            }

            if ($inFence) {
                continue;
            }

            // A heading closes the previous table and opens a new section. The heading ITSELF
            // may declare a split (§4.4's "…IS SPLIT. One trigger, two causes…" heading) —
            // record it with the ENCLOSING section, then enter the new section if it is
            // numbered (a `### 🔴 ⭐ …` sub-heading is still its parent section).
            if (preg_match('/^#{1,6}[ \t]+(.+)$/', ltrim($line), $heading) === 1) {
                array_push($rows, ...self::rowsFromBuffer($buffer));
                $buffer = [];

                if (self::hasAnyMarker($line, self::SPLIT_MARKERS)) {
                    $splits[] = SplitDeclaration::of($line, $number, $section);
                }

                if (preg_match('/^(\d+(?:\.\d+)*)/', $heading[1], $numberToken) === 1) {
                    $section = $numberToken[1];
                    $policyHeading = $policyHeading || $section === '0.4.4';
                }

                continue;
            }

            // A table line: buffer the row, or (separator) drop the buffered header. A table
            // row may ALSO declare a split (the phase-table row 11: "…never one branch for
            // both") — record it, and let the same row be observed as a disposition too.
            if (str_starts_with(ltrim($line), '|')) {
                if (self::hasAnyMarker($line, self::SPLIT_MARKERS)) {
                    $splits[] = SplitDeclaration::of($line, $number, $section);
                }

                $cells = self::cells($line);

                if ($cells !== []) {
                    if (self::isSeparatorRow($cells)) {
                        $buffer = [];
                    } else {
                        $buffer[] = ['cells' => $cells, 'number' => $number, 'section' => $section];
                    }
                }

                continue;
            }

            // A non-table line ends the table and may carry the policy statement or a split.
            array_push($rows, ...self::rowsFromBuffer($buffer));
            $buffer = [];

            $policyStatement = $policyStatement || self::containsNoCompetingCurrentDefinitions($line);

            if (self::hasAnyMarker($line, self::SPLIT_MARKERS)) {
                $splits[] = SplitDeclaration::of($line, $number, $section);
            }
        }

        array_push($rows, ...self::rowsFromBuffer($buffer));

        return DispositionContents::of(
            $rows,
            $splits,
            $policyHeading && $policyStatement,
        );
    }

    /**
     * @param  list<array{cells: list<string>, number: int, section: string}>  $buffer
     *
     * @return list<DispositionRow>
     */
    private static function rowsFromBuffer(array $buffer): array
    {
        $rows = [];

        foreach ($buffer as $entry) {
            $cells = $entry['cells'];

            if (count($cells) < 2) {
                continue;
            }

            $remedy = trim(implode(' ', array_slice($cells, 1)));

            if (! self::hasAnyMarker($remedy, self::DISPOSITION_MARKERS)) {
                continue;
            }

            $rows[] = DispositionRow::of(
                trim($cells[0]),
                $remedy,
                $entry['number'],
                $entry['section'],
                self::hasAnyMarker(implode(' ', $cells), self::SUPERSEDED_MARKERS),
                // Two-branch when the row CARRIES the two-branch shape — in its remedy
                // (§4.4's register: "CASE α … CASE β") OR in its trigger (AMD6's branch
                // rows: "Phase 7, ONLY on a `POST-DEMOTION` disposition"). A branch-named
                // row is a constituent of the split, not a competing whole-disposition.
                self::hasAnyMarker($remedy, self::TWO_BRANCH_MARKERS)
                    || self::hasAnyMarker($cells[0], self::TWO_BRANCH_MARKERS),
            );
        }

        return $rows;
    }

    /**
     * The row's cells: leading whitespace removed so an INDENTED row's outer pipe is
     * still stripped, then split on pipes NOT escaped by a backslash (S4's cells()).
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

    private static function containsNoCompetingCurrentDefinitions(string $line): bool
    {
        return stripos($line, 'no two competing current definitions') !== false;
    }

    /** @param  list<string>  $markers */
    private static function hasAnyMarker(string $text, array $markers): bool
    {
        foreach ($markers as $marker) {
            if (stripos($text, $marker) !== false) {
                return true;
            }
        }

        return false;
    }
}
