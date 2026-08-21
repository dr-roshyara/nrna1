<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Tests\Adapters;

use PHPUnit\Framework\TestCase;

/**
 * S7 — the CLI adapters (plan §4 D-1) wire the S1–S5 application services.
 *
 * These are INTEGRATION tests against the REAL entry points the Decision Authority
 * named, each exercised over a tiny fixture whose verdicts are already known from
 * the S1–S6 corpus. This test asserts the WIRING, not the rules:
 *
 *   knowledge-lint.php   --profile=structural --root=<dir> [--vocabulary=<path>] [--strict]
 *   link-check.php       --anchors=<dir>
 *   identifier-check.php --document=<path>
 *
 * ⛔ D-3 (warn-only): every adapter exits 0 regardless of verdict in Phase 0.
 * ⛔ D-4: every report carries the "what was NOT checked" statement verbatim.
 * ⛔ D-2 (fail-closed): a slice that cannot evaluate (no vocabulary config) is
 *    INCONCLUSIVE, never PASS — "absence of evidence is not PASS".
 *
 * The fixtures are deliberately tiny and their expected verdicts match the S1–S5
 * unit corpus (collision → FAIL, dangling step → FAIL, stale term + undeclared
 * confusable pair → FAIL, quiet document → PASS on every slice).
 */
final class StructuralAdaptersCliTest extends TestCase
{
    private string $scripts;  // repo root /scripts
    private string $root;     // repo root
    private string $dir;      // temp fixture dir

    protected function setUp(): void
    {
        $this->dir = sys_get_temp_dir() . '/ek-s7-adapters-' . bin2hex(random_bytes(4));
        mkdir($this->dir, 0777, true);
        $this->scripts = dirname(__DIR__, 4);    // .../scripts
        $this->root    = dirname($this->scripts); // repo root
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir . '/*') ?: [] as $file) {
            unlink($file);
        }

        rmdir($this->dir);
    }

    /**
     * The structural profile over a defective + a quiet document: every wired slice
     * fires the verdict the S1–S6 corpus locked, the quiet document stays off the
     * report, D-4's statement is present, and — D-3 — the exit code is 0.
     */
    public function test_knowledge_lint_structural_profile_reports_verdicts_warn_only(): void
    {
        file_put_contents($this->dir . '/bad.md', $this->collisionDoc());
        file_put_contents($this->dir . '/quiet.md', $this->quietDoc());
        file_put_contents($this->dir . '/vocab.yaml', "retired_terms:\n  - 'Phase 2b'\nconfusable_declaration_label: 'DI-7'\n");

        $out = $this->invoke('knowledge-lint.php', [
            '--profile=structural',
            '--root=' . $this->dir,
            '--vocabulary=' . $this->dir . '/vocab.yaml',
        ]);

        self::assertSame(0, $out['code'], 'D-3: warn-only — exit 0 always in Phase 0' . PHP_EOL . $out['stdout']);

        // S1 — CAP-001 collision
        self::assertStringContainsString('[FAIL] S1', $out['stdout']);
        self::assertStringContainsString("duplicate section identifier '4.1'", $out['stdout']);
        // S2 — CAP-004 dangling step reference
        self::assertStringContainsString('[FAIL] S2', $out['stdout']);
        self::assertStringContainsString("dangling step reference 'step 5'", $out['stdout']);
        // S3 — CAP-003 stale vocabulary + undeclared confusable collision
        self::assertStringContainsString('[FAIL] S3', $out['stdout']);
        self::assertStringContainsString("retired term 'Phase 2b' used live", $out['stdout']);
        self::assertStringContainsString('no declaration of the collision', $out['stdout']);
        // The quiet document is never FAILed: every FAIL names the defective one, and
        // the quiet one appears only as INCONCLUSIVE ("nothing to evaluate" — D-2).
        // Slice header lines carry the badge + file; evidence continuation lines do not.
        foreach (explode("\n", $out['stdout']) as $line) {
            if (! preg_match('/^\s*\[(FAIL|WARN|INCONCLUSIVE|PASS)\]\s+S\d/', $line)) {
                continue;
            }
            if (str_contains($line, 'quiet.md')) {
                self::assertStringContainsString('[INCONCLUSIVE]', $line);
            }
            if (str_starts_with(trim($line), '[FAIL]')) {
                self::assertStringContainsString('bad.md', $line, 'every FAIL belongs to the defective document');
            }
        }
        self::assertStringContainsString('Summary by slice', $out['stdout']);
        // D-4 — the NOT-CHECKED statement, carrying the DA's formulation verbatim.
        self::assertStringContainsString('NOT CHECKED', $out['stdout']);
        self::assertStringContainsString('UNDECLARED ARCHITECTURAL CONTENT', $out['stdout']);
    }

    /**
     * D-2 at the adapter boundary: without a vocabulary config there is nothing to
     * scan against, so S3 is INCONCLUSIVE on every file — never PASS.
     */
    public function test_knowledge_lint_structural_profile_fails_closed_without_vocabulary_config(): void
    {
        file_put_contents($this->dir . '/quiet.md', $this->quietDoc());

        $out = $this->invoke('knowledge-lint.php', [
            '--profile=structural',
            '--root=' . $this->dir,
        ]);

        self::assertSame(0, $out['code']);
        self::assertStringContainsString('[INCONCLUSIVE] S3', $out['stdout']);
        self::assertStringContainsString('Absence of evidence is not PASS', $out['stdout']);
    }

    /** D-3: a --strict flag MAY exist, but it is wired into nothing — here it only re-exits. */
    public function test_knowledge_lint_structural_profile_strict_flag_is_not_the_default(): void
    {
        file_put_contents($this->dir . '/bad.md', $this->collisionDoc());

        $default = $this->invoke('knowledge-lint.php', ['--profile=structural', '--root=' . $this->dir]);
        $strict  = $this->invoke('knowledge-lint.php', [
            '--profile=structural',
            '--root=' . $this->dir,
            '--strict',
        ]);

        self::assertSame(0, $default['code'], 'default is warn-only');
        self::assertSame(1, $strict['code'], '--strict exits non-zero on a FAIL (but is wired into no gate or hook)');
    }

    /** link-check --anchors adapts S2 (CAP-004 intra-document resolution), warn-only, with D-4. */
    public function test_link_check_anchors_reports_dangling_step_reference_warn_only(): void
    {
        file_put_contents($this->dir . '/anchor.md', $this->danglingStepDoc());

        $out = $this->invoke('link-check.php', ['--anchors=' . $this->dir]);

        self::assertSame(0, $out['code'], 'D-3: warn-only' . PHP_EOL . $out['stdout']);
        self::assertStringContainsString('[FAIL] S2', $out['stdout']);
        self::assertStringContainsString("dangling step reference 'step 5'", $out['stdout']);
        self::assertStringContainsString('NOT CHECKED', $out['stdout']);
    }

    /** identifier-check --document adapts S1 (CAP-001 document-local register), warn-only, with D-4. */
    public function test_identifier_check_document_reports_collision_warn_only(): void
    {
        $path = $this->dir . '/bad.md';
        file_put_contents($path, $this->collisionDoc());

        $out = $this->invoke('identifier-check.php', ['--document=' . $path]);

        self::assertSame(0, $out['code'], 'D-3: document-local mode is Phase-0 warn-only' . PHP_EOL . $out['stdout']);
        self::assertStringContainsString('[FAIL] S1', $out['stdout']);
        self::assertStringContainsString("duplicate section identifier '4.1'", $out['stdout']);
        self::assertStringContainsString('NOT CHECKED', $out['stdout']);
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
     * A document carrying the DI-1/DI-2/DI-5/DI-7 shapes from the historical corpus:
     * duplicated §4.1, a live retired term, an undeclared confusable pair, and a
     * dangling step reference. Quiet on S4 (no tables) and S5 (no dispositions).
     */
    private function collisionDoc(): string
    {
        return <<<'MD'
        # Defective Fixture

        ## 4.1 Phase 5
        The plan says Phase 2b is still used.

        ## 4.1 Repeat
        CASE B collides with CASE β.

        ## 4.2 Ordering
        Proceed to step 5.

        ```text
        1  Step one
        2  Step two
        ```
        MD;
    }

    /** A document every slice passes: unique monotonic headings, one resolvable §-reference. */
    private function quietDoc(): string
    {
        return <<<'MD'
        # Quiet Fixture

        ## 1 Overview
        Nothing structural here.

        ## 2 References
        See §1.
        MD;
    }

    /** S2's DI-5 shape: a normative block defines steps 1–4; prose cites step 5. */
    private function danglingStepDoc(): string
    {
        return <<<'MD'
        # Anchor Fixture

        ```text
        1  Step one
        2  Step two
        3  Step three
        4  Step four
        ```
        Proceed to step 5.
        MD;
    }
}
