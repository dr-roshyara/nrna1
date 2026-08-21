<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain;

use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;

/**
 * The domain service that applies S4's table column-count rule (S4).
 *
 * Structural rule (CAP-004-ADJACENT — the approved plan's S4 row; NOT DP-4, which
 * governs references, not tables):
 *
 *   "A Markdown table is a block of consecutive rows whose second row is a separator;
 *    every row of the block must carry the same number of cells as its header row."
 *
 * A row whose cell count deviates from the header's is a RAGGED table — a structural
 * defect that any table parser must either pad, truncate or refuse, silently corrupting
 * the document's declared structure.
 *
 * ⛔ Ownership is stated honestly: no DP-1..DP-6 verbatim owns this rule. It extends the
 *    CAP-004 realization's document-structure reading (the package that already reads
 *    headings · references · enumerations) WITHOUT claiming DP-4 authority over tables.
 *    It creates no capability, no register, no policy (plan §5 boundary: "no new
 *    capability · no new CAP id · no new register · no new domain policy").
 *
 * Verdict mapping:
 *   at least one ragged row in a recognised table block → FAIL
 *   at least one complete block, none ragged           → PASS
 *   no rows, or no header+separator pair              → INCONCLUSIVE (D-2: nothing to
 *                                                        evaluate; absence of evidence
 *                                                        is never PASS)
 *
 * ⛔ This service owns EXECUTION only — it creates no row, no block, no policy.
 */
final readonly class AssessesTableColumnCount
{
    public function validate(TableContents $contents, string $subject): Assessment
    {
        $blocks = $this->tableBlocks($contents->rows());

        if ($blocks === []) {
            return Assessment::of(
                Verdict::INCONCLUSIVE,
                sprintf(
                    "Table column counts in '%s': no table block (a header row followed "
                    .'by a separator row) found; nothing to evaluate. Absence of evidence '
                    .'is not PASS.',
                    $subject,
                ),
                $subject,
            );
        }

        $defects = [];

        foreach ($blocks as $block) {
            array_push($defects, ...$this->raggedRows($block));
        }

        if ($defects !== []) {
            return Assessment::of(
                Verdict::FAIL,
                sprintf(
                    "Table column counts in '%s' are inconsistent: %s.",
                    $subject,
                    implode('; ', $defects),
                ),
                $subject,
            );
        }

        return Assessment::of(
            Verdict::PASS,
            sprintf(
                "Table column counts in '%s' are structurally consistent: %d structurally "
                .'consistent table(s), header column count maintained across every row.',
                $subject,
                count($blocks),
            ),
            $subject,
        );
    }

    /**
     * Reconstruct the corpus's table blocks from flat row observations.
     *
     * A block is a header row (non-separator) whose IMMEDIATELY following row — the very
     * next line — is a separator, then that separator, then consecutive non-separator
     * data rows. A `|`-leading line with no following separator is prose, not a table;
     * a blank line (a line gap) ends a block. Line numbers let the service decide
     * adjacency without ever seeing the raw text.
     *
     * @param  list<TableRow>  $rows
     * @return list<array{header: TableRow, separator: TableRow, data: list<TableRow>}>
     */
    private function tableBlocks(array $rows): array
    {
        $blocks = [];
        $i = 0;
        $n = count($rows);

        while ($i < $n) {
            $row = $rows[$i];

            if ($row->isSeparator()) {
                $i++;
                continue;
            }

            $next = $rows[$i + 1] ?? null;

            if ($next === null || ! $next->isSeparator() || $next->line() !== $row->line() + 1) {
                $i++;
                continue;
            }

            $data = [];
            $j = $i + 2;
            $previousLine = $next->line();

            while ($j < $n) {
                $candidate = $rows[$j];

                if ($candidate->isSeparator() || $candidate->line() !== $previousLine + 1) {
                    break;
                }

                $data[] = $candidate;
                $previousLine = $candidate->line();
                $j++;
            }

            $blocks[] = ['header' => $row, 'separator' => $next, 'data' => $data];
            $i = $j;
        }

        return $blocks;
    }

    /**
     * One defect per row whose cell count deviates from the header's — the separator is
     * as much a row of the block as any data row, and a ragged separator is just as fatal.
     *
     * @param  array{header: TableRow, separator: TableRow, data: list<TableRow>}  $block
     * @return list<string>
     */
    private function raggedRows(array $block): array
    {
        $header = $block['header'];
        $expected = $header->cellCount();
        $out = [];

        if ($block['separator']->cellCount() !== $expected) {
            $out[] = sprintf(
                'ragged table: separator row (line %d) has %d column(s); header row '
                .'(line %d) has %d',
                $block['separator']->line(),
                $block['separator']->cellCount(),
                $header->line(),
                $expected,
            );
        }

        foreach ($block['data'] as $row) {
            if ($row->cellCount() !== $expected) {
                $out[] = sprintf(
                    'ragged table: data row (line %d) has %d column(s); header row '
                    .'(line %d) has %d',
                    $row->line(),
                    $row->cellCount(),
                    $header->line(),
                    $expected,
                );
            }
        }

        return $out;
    }
}
