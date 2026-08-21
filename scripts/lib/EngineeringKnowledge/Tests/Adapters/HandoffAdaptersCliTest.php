<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Tests\Adapters;

use PHPUnit\Framework\TestCase;

/**
 * P3 — the Phase-1 handoff-assurance CLI adapter (plan D-1..D-7).
 *
 * INTEGRATION tests against the REAL entry point, over fixtures whose verdicts
 * are already known from the S1–S5 unit corpus:
 *
 *   knowledge-lint.php --report=handoff --document=<path> [--vocabulary=<path>] [--out=<path>]
 *
 * This test asserts the WIRING + the report contract, not the rules:
 *   ⛔ D-3/D-4 (warn-only): handoff mode ALWAYS exits 0 — findings are a FIX
 *      BEFORE HANDOFF recommendation, never a rejection; --strict does not apply.
 *   ⛔ D-2 (fail-closed): without a vocabulary config S3 is INCONCLUSIVE, never
 *      PASS — so the aggregate is INCONCLUSIVE, never PASS.
 *   ⛔ D-2 (nine elements): the report carries artifact + checker/version/commit
 *      + timestamp + command line, per-slice verdicts, findings with evidence,
 *      the D-4 NOT-CHECKED statement verbatim, named NOT-CHECKED areas, and
 *      known limitations — and never governance vocabulary (APPROVED/REJECTED/ACCEPTED).
 *   ⛔ D-6 (read-only): --out writes the report WHERE THE AUTHOR DIRECTS; the
 *      scanned document is never written.
 */
final class HandoffAdaptersCliTest extends TestCase
{
    private string $scripts;  // repo root /scripts
    private string $dir;      // temp fixture dir

    protected function setUp(): void
    {
        $this->dir = sys_get_temp_dir() . '/ek-handoff-' . bin2hex(random_bytes(4));
        mkdir($this->dir, 0777, true);
        $this->scripts = dirname(__DIR__, 4);    // .../scripts
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir . '/*') ?: [] as $file) {
            unlink($file);
        }

        rmdir($this->dir);
    }

    /** A defective document: DI-1 (dup §4.1), DI-5 (dangling step 5), DI-2 (live retired term). */
    public function test_handoff_report_finds_defects_warn_only(): void
    {
        $doc = $this->dir . '/bad.md';
        file_put_contents($doc, $this->defectiveDoc());
        file_put_contents($this->dir . '/vocab.yaml', $this->vocab());
        $out = $this->invoke('knowledge-lint.php', [
            '--report=handoff',
            '--document=' . $doc,
            '--vocabulary=' . $this->dir . '/vocab.yaml',
        ]);

        // D-4 — warn-only: findings are a recommendation, never a rejection.
        self::assertSame(0, $out['code'], 'D-4: handoff mode always exits 0' . PHP_EOL . $out['stdout']);
        self::assertStringContainsString('Aggregate verdict: [FAIL]', $out['stdout']);
        self::assertStringContainsString('FIX BEFORE HANDOFF', $out['stdout']);

        // D-2 — findings carry per-slice evidence.
        self::assertStringContainsString('[FAIL] S1', $out['stdout']);
        self::assertStringContainsString('duplicate section identifier', $out['stdout']);
        self::assertStringContainsString('[FAIL] S2', $out['stdout']);
        self::assertStringContainsString('dangling step reference', $out['stdout']);
        self::assertStringContainsString('[FAIL] S3', $out['stdout']);
        self::assertStringContainsString("retired term 'Phase 2b' used live", $out['stdout']);
    }

    /** A clean document passes all five slices; the aggregate is PASS — attach-as-evidence. */
    public function test_handoff_report_is_pass_on_a_clean_document(): void
    {
        $doc = $this->dir . '/clean.md';
        file_put_contents($doc, $this->cleanDoc());
        file_put_contents($this->dir . '/vocab.yaml', $this->vocab());

        $out = $this->invoke('knowledge-lint.php', [
            '--report=handoff',
            '--document=' . $doc,
            '--vocabulary=' . $this->dir . '/vocab.yaml',
        ]);

        self::assertSame(0, $out['code'], 'warn-only exit' . PHP_EOL . $out['stdout']);
        self::assertStringContainsString('[PASS] S1', $out['stdout']);
        self::assertStringContainsString('[PASS] S5', $out['stdout']);
        self::assertStringContainsString('Aggregate verdict: [PASS]', $out['stdout']);
        self::assertStringContainsString('ATTACH AS EVIDENCE', $out['stdout']);
    }

    /** D-2 fail-closed at the adapter: without a vocabulary config S3 is INCONCLUSIVE,
     *  so the aggregate is INCONCLUSIVE — a report is never PASS on silence. */
    public function test_handoff_report_fails_closed_on_s3_without_vocabulary_config(): void
    {
        $doc = $this->dir . '/clean.md';
        file_put_contents($doc, $this->cleanDoc());

        $out = $this->invoke('knowledge-lint.php', ['--report=handoff', '--document=' . $doc]);

        self::assertSame(0, $out['code']);
        self::assertStringContainsString('[INCONCLUSIVE] S3', $out['stdout']);
        self::assertStringContainsString('Absence of evidence is not PASS', $out['stdout']);
        self::assertStringContainsString('Aggregate verdict: [INCONCLUSIVE]', $out['stdout']);
        self::assertStringContainsString('REVIEW BEFORE HANDOFF', $out['stdout']);
        self::assertStringNotContainsString('Aggregate verdict: [PASS]', $out['stdout']);
    }

    /** D-2/D-5 — the report carries the nine elements: provenance, per-slice verdicts,
     *  the D-4 statement verbatim, named NOT-CHECKED areas, limitations — and never
     *  governance vocabulary. */
    public function test_handoff_report_carries_provenance_and_no_governance_vocabulary(): void
    {
        $doc = $this->dir . '/clean.md';
        file_put_contents($doc, $this->cleanDoc());
        file_put_contents($this->dir . '/vocab.yaml', $this->vocab());

        $out = $this->invoke('knowledge-lint.php', [
            '--report=handoff',
            '--document=' . $doc,
            '--vocabulary=' . $this->dir . '/vocab.yaml',
        ]);

        // provenance (D-5): checker identity + version + git commit (or UNKNOWN) + timestamp + command
        self::assertStringContainsString('Artifact : ' . $doc, $out['stdout']);
        self::assertStringContainsString('Checker  : knowledge-lint --report=handoff · version 1.0.0', $out['stdout']);
        self::assertMatchesRegularExpression('/Source   : (?:[0-9a-f]{7,40}|UNKNOWN)/', $out['stdout']);
        self::assertStringContainsString('Generated: ', $out['stdout']);
        self::assertStringContainsString('Command  : ', $out['stdout']);
        // per-slice verdicts + findings section
        self::assertStringContainsString('Checks executed (per-slice verdicts):', $out['stdout']);
        self::assertStringContainsString('Findings', $out['stdout']);
        // D-4 statement verbatim + named NOT-CHECKED areas
        self::assertStringContainsString('NOT CHECKED', $out['stdout']);
        self::assertStringContainsString('UNDECLARED ARCHITECTURAL CONTENT', $out['stdout']);
        self::assertStringContainsString('NOT-CHECKED areas (named):', $out['stdout']);
        self::assertStringContainsString('OQ-1', $out['stdout']);
        self::assertStringContainsString('Known limitations:', $out['stdout']);
        // ⛔ D-4 — no governance vocabulary anywhere in the report
        foreach (['APPROVED', 'REJECTED', 'ACCEPTED'] as $word) {
            self::assertStringNotContainsString($word, $out['stdout'], "no governance vocabulary — found '{$word}'");
        }
    }

    /** D-6 — --out writes the report where the author directs and leaves the scanned document untouched. */
    public function test_handoff_report_out_writes_the_file_and_leaves_the_document_untouched(): void
    {
        $doc = $this->dir . '/clean.md';
        $reportPath = $this->dir . '/handoff-report.md';
        file_put_contents($doc, $this->cleanDoc());
        file_put_contents($this->dir . '/vocab.yaml', $this->vocab());
        $before = file_get_contents($doc);

        $out = $this->invoke('knowledge-lint.php', [
            '--report=handoff',
            '--document=' . $doc,
            '--vocabulary=' . $this->dir . '/vocab.yaml',
            '--out=' . $reportPath,
        ]);

        self::assertSame(0, $out['code']);
        self::assertSame('', $out['stdout'], 'with --out the report goes to the file, not stdout');
        self::assertFileExists($reportPath);
        self::assertStringContainsString('Handoff assurance report', (string) file_get_contents($reportPath));
        self::assertStringContainsString('Aggregate verdict: [PASS]', (string) file_get_contents($reportPath));
        self::assertSame($before, file_get_contents($doc), 'the scanned document is never written (D-6)');
    }

    /** The author-side loop: findings surface, the author remediates, the rerun is quiet. */
    public function test_handoff_report_rerun_after_remediation_flips_to_pass(): void
    {
        $doc = $this->dir . '/doc.md';
        file_put_contents($this->dir . '/vocab.yaml', $this->vocab());

        file_put_contents($doc, $this->defectiveDoc());
        $first = $this->invoke('knowledge-lint.php', [
            '--report=handoff',
            '--document=' . $doc,
            '--vocabulary=' . $this->dir . '/vocab.yaml',
        ]);
        self::assertStringContainsString('Aggregate verdict: [FAIL]', $first['stdout']);
        self::assertStringContainsString('FIX BEFORE HANDOFF', $first['stdout']);

        // the author remediates the document, then re-runs the SAME command
        file_put_contents($doc, $this->cleanDoc());
        $second = $this->invoke('knowledge-lint.php', [
            '--report=handoff',
            '--document=' . $doc,
            '--vocabulary=' . $this->dir . '/vocab.yaml',
        ]);

        self::assertSame(0, $second['code']);
        self::assertStringContainsString('Aggregate verdict: [PASS]', $second['stdout']);
        self::assertStringContainsString('ATTACH AS EVIDENCE', $second['stdout']);
    }

    /** Usage: --report=handoff without --document is a usage error (exit 3). */
    public function test_handoff_report_requires_document(): void
    {
        $out = $this->invoke('knowledge-lint.php', ['--report=handoff']);

        self::assertSame(3, $out['code']);
        self::assertStringContainsString('requires --document', $out['stdout']);
    }

    /** D-4 — --strict is NOT applicable to handoff mode: combined, it prints a note and still exits 0. */
    public function test_handoff_report_strict_flag_is_not_applicable(): void
    {
        $doc = $this->dir . '/bad.md';
        file_put_contents($doc, $this->defectiveDoc());
        file_put_contents($this->dir . '/vocab.yaml', $this->vocab());

        $out = $this->invoke('knowledge-lint.php', [
            '--report=handoff',
            '--document=' . $doc,
            '--vocabulary=' . $this->dir . '/vocab.yaml',
            '--strict',
        ]);

        self::assertSame(0, $out['code'], 'D-4: --strict does not make handoff mode blocking');
        self::assertStringContainsString('warn-only (D-4)', $out['stdout']);
        self::assertStringContainsString('Aggregate verdict: [FAIL]', $out['stdout']);
    }

    /**
     * @param list<string> $args
     *
     * @return array{code: int, stdout: string}
     */
    private function invoke(string $script, array $args): array
    {
        $cmd = 'php ' . escapeshellarg($this->scripts . '/' . $script);

        foreach ($args as $a) {
            $cmd .= ' ' . escapeshellarg($a);
        }

        exec($cmd . ' 2>&1', $lines, $code);

        return ['code' => $code, 'stdout' => implode("\n", $lines)];
    }

    /**
     * The clean shape every slice passes: unique monotonic headings, one resolvable
     * §-reference, a well-formed table, a §0.4.4-declared labelled two-branch
     * disposition + a split declaration (S5 PASS), and one CITED retired term so S3
     * evaluates (a live term would be DI-2).
     */
    private function cleanDoc(): string
    {
        return <<<'MD'
        # 0.4.4 Canonical-document rule
        **One canonical CURRENT definition per section.** No two competing current definitions are left standing.

        # 4.4 The final integrity check
        **THE MISMATCH DISPOSITION IS SPLIT — one trigger, two causes.**

        # 8 Rollback
        | Window | Disposition |
        |---|---|
        | at Phase 7, on a FINAL re-hash mismatch | ⛔ PRE-SWITCH: reconcile then re-verify; POST-DEMOTION: quarantine. (superseded single-branch wording, retained as labelled history per §0.4.4) |

        Traceability (Phase 2b → 2c-commit): the phase label was renamed.
        MD;
    }

    /** The DI-1/DI-2/DI-5 shapes from the historical corpus: duplicate §4.1, a live
     *  retired term, a dangling step reference. Quiet on S4 (no tables) and S5 (no §0.4.4). */
    private function defectiveDoc(): string
    {
        return <<<'MD'
        # Defective Fixture

        ## 4.1 Phase 5
        The plan says Phase 2b is still used.

        ## 4.1 Repeat
        Proceed to step 5.
        MD;
    }

    /** The minimal S3 config shape (same keys the Phase-0 back-test fixtures use). */
    private function vocab(): string
    {
        return <<<'YAML'
        retired_terms:
          - 'Phase 2b'
        confusable_declaration_label: 'DI-7'
        YAML;
    }
}
