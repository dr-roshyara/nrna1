<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Tests\BackTest;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Application\ValidateDocumentLocalIntegrity;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure\MarkdownDocumentContentsReader;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Application\ValidateIntraDocumentReferences;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Application\ValidateTableColumnCount;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Infrastructure\MarkdownIntraDocumentReader;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Infrastructure\MarkdownTableReader;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application\ValidateCompetingCurrentDefinitions;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Application\ValidateVocabularyIntegrity;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Infrastructure\MarkdownDispositionReader;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Infrastructure\MarkdownVocabularyReader;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Infrastructure\YamlVocabularySource;
use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * S6 — the Phase-0 back-test harness (plan §6's exit criterion, as an executable test).
 *
 * Runs the five REALIZED S1–S5 application services (with their real infrastructure
 * adapters) over the three HISTORICAL states of the governed migration plan,
 * materialized from git at their actual commits, and requires §6's matrix to be
 * reproduced EXACTLY — cell for cell:
 *
 *   state   S1 (identifiers)      S2 (references)         S3 (vocabulary)   S4 (tables)  S5 (dispositions)
 *   amd4    FAIL DI-1 collision   FAIL §4.1/§4.2 ambiguous FAIL DI-2 ×4      PASS         PASS
 *           + non-monotonic       ← a DI-1 consequence,   (Phase 2b live)
 *                                  not an independent
 *                                  defect class
 *   amd5    PASS                  FAIL DI-5 dangling      FAIL DI-7 ×2       PASS         WARN DI-4
 *                                 step 5                  (CASE β/B, α/A)                 (§8:709 vs §4.4)
 *   amd6    PASS                  PASS                    PASS              PASS         PASS
 *   (the repaired artifact — QUIET across every slice; the regression direction)
 *
 * ⛔ The expected verdicts are the APPROVED PLAN's §6 oracle — test data, not policy.
 *    The harness OWNS no rule: it orchestrates the existing application services and
 *    reads only the document states it is pointed at (DDD: application services
 *    orchestrate; business meaning stays in the domain; AC-1: this harness is invoked,
 *    it never schedules itself).
 *
 * ⛔ One cell is BEYOND the plan's table and is recorded, not hidden: AMD4's S2 = FAIL.
 *    A §-reference to a duplicated heading ("§4.1" where "## 4.1" appears at lines 349
 *    and 436) is ambiguous under DP-4 — a deterministic consequence of DI-1's collision,
 *    not an independent defect class. Trimming it from the oracle would make the harness
 *    a weaker reproduction of the corpus.
 *
 * Fail-closed (D-2): materialization runs through `git show`; a missing commit, an
 * unreadable path, or a state whose newline count disagrees with the plan's §6 "Lines"
 * column is an assertion FAILURE, never a silent skip. A future change to any S1–S5
 * checker that drifts any cell — over-firing on the repaired AMD6 or under-firing on a
 * defective historical state — turns this test RED.
 */
final class Phase0BackTest extends TestCase
{
    /** The three historical states, by their §6 labels. */
    private const COMMITS = [
        'amd4' => '0a2fa71d',
        'amd5' => '7d3abc59',
        'amd6' => '8307beca',
    ];

    /** The governed artifact path at each state (identical across the three commits). */
    private const PLAN_PATH = 'docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md';

    /** Plan §6 "Lines" column — an anchor that we are reading the states we think we are. */
    private const EXPECTED_LINE_COUNTS = [
        'amd4' => 706,
        'amd5' => 833,
        'amd6' => 1206,
    ];

    /** The §6 matrix, locked cell for cell (Verdict cases are const-safe on PHP 8.1+). */
    private const MATRIX = [
        'amd4' => [
            'S1' => Verdict::FAIL, 'S2' => Verdict::FAIL, 'S3' => Verdict::FAIL,
            'S4' => Verdict::PASS, 'S5' => Verdict::PASS,
        ],
        'amd5' => [
            'S1' => Verdict::PASS, 'S2' => Verdict::FAIL, 'S3' => Verdict::FAIL,
            'S4' => Verdict::PASS, 'S5' => Verdict::WARN,
        ],
        'amd6' => [
            'S1' => Verdict::PASS, 'S2' => Verdict::PASS, 'S3' => Verdict::PASS,
            'S4' => Verdict::PASS, 'S5' => Verdict::PASS,
        ],
    ];

    private string $dir;

    /** @var array<string, Assessment> — one per slice; built in setUp with real adapters. */
    private array $services;

    /** @var array<string, string> — materialized state path per state label, cached. */
    private array $paths = [];

    protected function setUp(): void
    {
        $this->dir = sys_get_temp_dir().'/ek-phase0-backtest-'.bin2hex(random_bytes(4));
        mkdir($this->dir, 0777, true);

        // D-5: Phase 0 supplies a temporary vocabulary config; the governed
        // docs/knowledge/schema/ copy is a later adoption act this run must not do.
        $vocabularyConfig = $this->dir.'/vocabulary.yaml';
        file_put_contents(
            $vocabularyConfig,
            "retired_terms:\n  - 'Phase 2b'\nconfusable_declaration_label: 'DI-7'\n",
        );

        $this->services = [
            'S1' => new ValidateDocumentLocalIntegrity(new MarkdownDocumentContentsReader()),
            'S2' => new ValidateIntraDocumentReferences(new MarkdownIntraDocumentReader()),
            'S3' => new ValidateVocabularyIntegrity(
                new YamlVocabularySource($vocabularyConfig),
                new MarkdownVocabularyReader(),
            ),
            'S4' => new ValidateTableColumnCount(new MarkdownTableReader()),
            'S5' => new ValidateCompetingCurrentDefinitions(new MarkdownDispositionReader()),
        ];
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir.'/*') ?: [] as $file) {
            unlink($file);
        }

        rmdir($this->dir);
    }

    /** @return list<array{string, string, Verdict}> */
    public static function matrixProvider(): array
    {
        $cells = [];

        foreach (self::MATRIX as $state => $slices) {
            foreach ($slices as $slice => $expected) {
                $cells[] = [$state, $slice, $expected];
            }
        }

        return $cells;
    }

    /** ⛔ THE S6 ORACLE — every one of the 15 matrix cells must be reproduced exactly. */
    #[DataProvider('matrixProvider')]
    public function test_the_phase0_matrix_is_reproduced_exactly(
        string $state,
        string $slice,
        Verdict $expected,
    ): void {
        $actual = $this->assess($state, $slice)->verdict();

        self::assertSame(
            $expected,
            $actual,
            "{$state} {$slice}: plan §6 expects {$expected->value}, checker returned {$actual->value}",
        );
    }

    /** The state anchor: each materialized document is the plan's §6 line count. */
    public function test_each_state_is_the_plans_s6_line_count(): void
    {
        foreach (self::COMMITS as $state => $commit) {
            $content = (string) file_get_contents($this->materializeState($state));

            self::assertSame(
                self::EXPECTED_LINE_COUNTS[$state],
                substr_count($content, "\n"),
                "{$state} ({$commit}): newline count disagrees with plan §6's Lines column",
            );
        }
    }

    /** AMD4 rediscoveries, by the evidence that names the finding class. */
    public function test_amd4_evidence_rediscoveres_di1_and_di2(): void
    {
        $s1 = $this->assess('amd4', 'S1');
        self::assertSame(Verdict::FAIL, $s1->verdict());
        self::assertStringContainsString("duplicate section identifier '4.1' at lines 349, 436", $s1->evidence());
        self::assertStringContainsString("duplicate section identifier '4.2' at lines 404, 442", $s1->evidence());
        self::assertStringContainsString('non-monotonic section order', $s1->evidence());

        // Beyond the plan's table, recorded not hidden: §4.1's ambiguity is DI-1's
        // collision consequence (a §-reference to a duplicated heading is ambiguous).
        $s2 = $this->assess('amd4', 'S2');
        self::assertSame(Verdict::FAIL, $s2->verdict());
        self::assertStringContainsString("ambiguous section reference '§4.1'", $s2->evidence());

        $s3 = $this->assess('amd4', 'S3');
        self::assertSame(Verdict::FAIL, $s3->verdict());
        self::assertStringContainsString("retired term 'Phase 2b' used live", $s3->evidence());
        self::assertStringContainsString('(line 469)', $s3->evidence());
        self::assertStringContainsString('(line 673)', $s3->evidence());
    }

    /** AMD5 rediscoveries, by the evidence that names the finding class. */
    public function test_amd5_evidence_rediscoveres_di5_di7_and_di4_warn(): void
    {
        $s2 = $this->assess('amd5', 'S2');
        self::assertSame(Verdict::FAIL, $s2->verdict());
        self::assertStringContainsString("dangling step reference 'step 5'", $s2->evidence());
        self::assertStringContainsString('defines 1 · 2 · 3 · 4', $s2->evidence());

        $s3 = $this->assess('amd5', 'S3');
        self::assertSame(Verdict::FAIL, $s3->verdict());
        self::assertStringContainsString("'CASE β'", $s3->evidence());
        self::assertStringContainsString("'CASE B'", $s3->evidence());
        self::assertStringContainsString('no declaration of the collision', $s3->evidence());

        $s5 = $this->assess('amd5', 'S5');
        self::assertSame(Verdict::WARN, $s5->verdict());
        self::assertStringContainsString('(line 709)', $s5->evidence());
        self::assertStringContainsString('declares it SPLIT', $s5->evidence());
    }

    /** ⭐ The regression direction: the repaired artifact is QUIET across every slice. */
    public function test_amd6_is_quiet_across_all_five_slices(): void
    {
        foreach (['S1', 'S2', 'S3', 'S4', 'S5'] as $slice) {
            $assessment = $this->assess('amd6', $slice);

            self::assertSame(
                Verdict::PASS,
                $assessment->verdict(),
                "AMD6 {$slice}: expected PASS on the repaired artifact, got {$assessment->verdict()->value} — a false-positive generator",
            );
        }

        $s3 = $this->assess('amd6', 'S3');
        self::assertStringContainsString('0 live', $s3->evidence());
        self::assertStringContainsString('DECLARED', $s3->evidence());
    }

    private function assess(string $state, string $slice): Assessment
    {
        return $this->services[$slice]->handle($this->materializeState($state));
    }

    /**
     * Materialize a historical state from git, cached per state. Fail-closed: a failed
     * git read, an empty result, or a line-count mismatch is an assertion failure.
     */
    private function materializeState(string $state): string
    {
        if (isset($this->paths[$state])) {
            return $this->paths[$state];
        }

        $path = $this->dir.'/'.$state.'.md';
        $repoRoot = dirname(__DIR__, 4);
        $revision = self::COMMITS[$state].':'.self::PLAN_PATH;

        $command = sprintf(
            'git -C %s show %s 2>/dev/null > %s',
            escapeshellarg($repoRoot),
            escapeshellarg($revision),
            escapeshellarg($path),
        );

        exec($command, $ignored, $exitCode);

        self::assertSame(
            0,
            $exitCode,
            "git show {$revision} failed (exit {$exitCode}); the Phase-0 back-test corpus is unreachable",
        );
        self::assertFileExists($path);
        self::assertGreaterThan(0, filesize($path), "git show {$revision} produced an empty state");

        return $this->paths[$state] = $path;
    }
}
