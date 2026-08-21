<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Tests\Infrastructure;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Infrastructure\MarkdownDispositionReader;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * S5 — the markdown adapter that observes a document's disposition constructs.
 *
 * Parsing rules, each pinned to the corpus:
 *   • A DISPOSITION ROW is a table row whose non-first cells carry a normative
 *     disposition phrase (never · must · is refused · stop · quarantine · escalate …).
 *   • A row is LABELLED when any of its cells carries a superseded marker
 *     (superseded · withdrawn · retained as labelled history · per §0.4.4).
 *   • A row is TWO-BRANCH when its remedy carries a two-branch marker
 *     (CASE α/β/A/B · PRE-SWITCH · POST-DEMOTION · PRE/POST-writer-switch).
 *   • A SPLIT DECLARATION is any line declaring a disposition to be two-branch
 *     (split · never one branch · one trigger, two causes · two-branch).
 *   • The document DECLARES the canonical-document rule (§0.4.4) when it carries the
 *     `## 0.4.4` heading AND the "no two competing current definitions" statement.
 *   • Section context is the current heading's first token (`8` from `# 8 · Rollback`).
 *   • Line numbers are 1-based and NEL-safe (`preg_split('/\r\n|\r|\n/')`).
 *   • Fenced code blocks are skipped (code, not document structure).
 */
final class MarkdownDispositionReaderTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        $this->dir = sys_get_temp_dir().'/ek-md-disposition-'.bin2hex(random_bytes(4));
        mkdir($this->dir, 0777, true);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir.'/*') ?: [] as $file) {
            unlink($file);
        }

        rmdir($this->dir);
    }

    private function write(string $name, string $contents): string
    {
        $path = $this->dir.'/'.$name;
        file_put_contents($path, $contents);

        return $path;
    }

    private function reader(): MarkdownDispositionReader
    {
        return new MarkdownDispositionReader();
    }

    /** The AMD5 DI-4 shape observed: one unlabelled single-remedy row, one split declaration,
     *  and the §0.4.4 declaration — the corpus shape that must yield the WARN. */
    public function test_observes_the_amd5_di4_shape(): void
    {
        $path = $this->write('amd5-shape.md', <<<'MD'
# 0.4.4 Canonical-document rule
**One canonical CURRENT definition per section.** No two competing current definitions are left standing.

# 4.4 The final integrity check
**THE MISMATCH DISPOSITION IS SPLIT** — at Phase 7, on a FINAL re-hash mismatch, CASE α reconciles under §6 and CASE β is QUARANTINED — never one branch for both.

# 8 Rollback
| Window | Disposition |
|---|---|
| at Phase 7, on a FINAL re-hash mismatch | ⛔ STOP-AND-RECONCILE. Removal is refused until the difference is reconciled and re-verified. |
MD);

        $contents = $this->reader()->read($path);

        self::assertTrue($contents->policyDeclared());
        self::assertCount(1, $contents->dispositionRows());
        self::assertCount(1, $contents->splitDeclarations());

        $row = $contents->dispositionRows()[0];
        self::assertSame('at Phase 7, on a FINAL re-hash mismatch', $row->trigger());
        self::assertStringContainsString('STOP-AND-RECONCILE', $row->remedy());
        self::assertSame(10, $row->line());
        self::assertSame('8', $row->section());
        self::assertFalse($row->labelled());
        self::assertFalse($row->twoBranch());

        $split = $contents->splitDeclarations()[0];
        self::assertStringContainsString('IS SPLIT', $split->text());
        self::assertSame(5, $split->line());
        self::assertSame('4.4', $split->section());
    }

    /** The AMD6 repair observed: the stale wording is inside a superseded marker, so the
     *  row is LABELLED — and it carries the two-branch shape. Both exclusions fire. */
    public function test_observes_the_amd6_repair_shape(): void
    {
        $path = $this->write('amd6-shape.md', <<<'MD'
# 0.4.4 Canonical-document rule
No two competing current definitions are left standing.

# 8 Rollback
| Window | Disposition |
|---|---|
| at Phase 7, on a FINAL re-hash mismatch | ⛔ PRE-SWITCH: reconcile under §6 then re-verify; POST-DEMOTION: quarantine. (superseded single-branch wording, retained as labelled history per §0.4.4: "STOP-AND-RECONCILE. Removal is refused until the difference is reconciled and re-verified.") |
MD);

        $contents = $this->reader()->read($path);

        self::assertTrue($contents->policyDeclared());

        $row = $contents->dispositionRows()[0];
        self::assertTrue($row->labelled());
        self::assertTrue($row->twoBranch());
    }

    /** The AMD4 quiet shape: the split declared is for a different subject — the reader
     *  still observes both, and the domain service decides nothing competes. */
    public function test_observes_a_split_for_a_different_trigger(): void
    {
        $path = $this->write('amd4-shape.md', <<<'MD'
# 0.4.4 Canonical-document rule
No two competing current definitions are left standing.

# 8 Rollback
| Window | Disposition |
|---|---|
| at Phase 7, on a FINAL re-hash mismatch | ⛔ STOP-AND-RECONCILE. Removal is refused until the difference is reconciled and re-verified. |

INV-R1 was SPLIT into location-refusal (exit 65) and workflow-state UNRESOLVABLE (report, exit 0).
MD);

        $contents = $this->reader()->read($path);

        self::assertCount(1, $contents->dispositionRows());
        self::assertCount(1, $contents->splitDeclarations());
        self::assertStringContainsString('INV-R1', $contents->splitDeclarations()[0]->text());
    }

    /** A document that never declares §0.4.4 is reported as such — the heuristic refuses
     *  to assert a rule the document did not bind itself to. */
    public function test_document_without_the_canonical_rule_is_not_policy_declared(): void
    {
        $path = $this->write('no-policy.md', "# 1 Plain\n\n| A | B |\n|---|---|\n| x | y |\n");

        self::assertFalse($this->reader()->read($path)->policyDeclared());
    }

    /** A disposition row with no normative phrase is not a disposition construct. */
    public function test_a_plain_table_row_is_not_a_disposition(): void
    {
        $path = $this->write('plain.md', <<<'MD'
# 0.4.4 Canonical-document rule
No two competing current definitions are left standing.

| A | B |
|---|---|
| x | y |
MD);

        self::assertCount(0, $this->reader()->read($path)->dispositionRows());
    }

    /** NEL (U+0085, the corpus's in-line separator) is not a line break — line numbers stay honest. */
    public function test_line_numbers_are_nel_safe(): void
    {
        $nel = "\u{0085}";
        $path = $this->write('nel.md', <<<MD
# 0.4.4 Canonical-document rule
No two competing current definitions are left standing.

# 8 Rollback
| Window | Disposition |
|---|---|
| at Phase 7, on a FINAL re-hash mismatch | ⛔ STOP-AND-RECONCILE. Removal is refused until the difference is reconciled and re-verified.${nel} |
MD);

        $contents = $this->reader()->read($path);

        self::assertCount(1, $contents->dispositionRows());
        self::assertSame(7, $contents->dispositionRows()[0]->line());
    }

    /** A table inside a fenced code block is code, not a disposition construct. */
    public function test_fenced_code_is_skipped(): void
    {
        $path = $this->write('fenced.md', <<<'MD'
# 0.4.4 Canonical-document rule
No two competing current definitions are left standing.

# 8 Rollback

```md
| at Phase 7, on a FINAL re-hash mismatch | STOP-AND-RECONCILE. Removal is refused. |
```
MD);

        self::assertCount(0, $this->reader()->read($path)->dispositionRows());
    }

    /** Fail closed: an unreadable path throws, never silently yields an empty register. */
    public function test_unreadable_document_throws(): void
    {
        $this->expectException(RuntimeException::class);

        $this->reader()->read($this->dir.'/missing.md');
    }

    /** Observed in the AMD5 corpus: the RD-3 split is declared FROM A HEADING
     *  ("### 🔴 … THE MISMATCH DISPOSITION IS SPLIT. One trigger, two causes…") — the
     *  unnumbered sub-heading stays in its numbered parent section (§4.4). */
    public function test_split_declared_from_a_heading_is_recorded(): void
    {
        $path = $this->write('split-heading.md', <<<'MD'
# 0.4.4 Canonical-document rule
No two competing current definitions are left standing.

# 4.4 The final integrity check
### 🔴 ⭐ **AMD5 (`RD-3`) — THE MISMATCH DISPOSITION IS SPLIT. One trigger, two causes.**
MD);

        $contents = $this->reader()->read($path);

        self::assertCount(1, $contents->splitDeclarations());
        self::assertStringContainsString('IS SPLIT', $contents->splitDeclarations()[0]->text());
        self::assertSame('4.4', $contents->splitDeclarations()[0]->section());
    }

    /** Observed in the AMD5 corpus: the phase-table row 11 declares the split AND is a
     *  two-branch disposition row — one table row is both, and the reader records both. */
    public function test_a_table_row_can_declare_a_split_and_be_a_disposition(): void
    {
        $path = $this->write('split-table-row.md', <<<'MD'
# 0.4.4 Canonical-document rule
No two competing current definitions are left standing.

# 4 The phase table
| row | phase | disposition |
|---|---|---|
| 11 | Runtime cleanup | a mismatch is disposed by CASE α (reconcile) or CASE β (QUARANTINE + escalate) — never one branch for both |
MD);

        $contents = $this->reader()->read($path);

        self::assertCount(1, $contents->splitDeclarations());
        self::assertCount(1, $contents->dispositionRows());
        self::assertTrue($contents->dispositionRows()[0]->twoBranch());
    }

    /** Observed in the AMD6 corpus: a row whose TRIGGER names ONE branch
     *  ("Phase 7, ONLY on a POST-DEMOTION disposition") is a constituent of the split —
     *  the reader classes it two-branch so the service never competes it. */
    public function test_a_trigger_naming_one_branch_is_classed_two_branch(): void
    {
        $path = $this->write('branch-trigger.md', <<<'MD'
# 0.4.4 Canonical-document rule
No two competing current definitions are left standing.

# 8 Rollback
| Window | Disposition |
|---|---|
| Phase 7, ONLY on a POST-DEMOTION disposition | ⛔ WRITE QUARANTINED — retract it only by a governed disposition |
MD);

        $contents = $this->reader()->read($path);

        self::assertCount(1, $contents->dispositionRows());
        self::assertTrue($contents->dispositionRows()[0]->twoBranch());
    }

    /** Observed in the AMD5 corpus: the bare word "split" in prose ("RC-1's split",
     *  "the boundary check had to be split") is a MECHANISM split, not a disposition
     *  split — it is not recorded, so it can never fabricate a competitor. */
    public function test_bare_split_word_in_prose_is_not_a_split_declaration(): void
    {
        $path = $this->write('mechanism-split.md', <<<'MD'
# 0.4.4 Canonical-document rule
No two competing current definitions are left standing.

RC-1's split, because the commit order pins the manifest, is a mechanism split.
MD);

        self::assertCount(0, $this->reader()->read($path)->splitDeclarations());
    }
}
