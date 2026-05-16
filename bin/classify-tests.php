#!/usr/bin/env php
<?php
/**
 * Fast Test Classifier
 *
 * Categorizes tests by architectural intent WITHOUT executing them.
 * Zero Laravel bootstrap. Pure static analysis.
 *
 * Usage: php bin/classify-tests.php [output-file]
 */

declare(strict_types=1);

const TESTS_DIR = __DIR__ . '/../tests';

// Keywords for classification
const MEMBERSHIP_KEYWORDS = [
    'Committee::',
    'addMember',
    'removeMember',
    'MemberId',
    'CommitteeId',
    'lifecycle',
    'status()',
    'AssignMember',
    'CommitteeEligibility',
    'MembershipLineage',
];

const PROJECTION_KEYWORDS = [
    'View',
    'Summary',
    'Resource',
    'DTO',
    'Transformer',
    'transform',
    'toArray',
    'serialize',
    'Response',
    'Json',
];

const INTEGRATION_KEYWORDS = [
    '$this->post(',
    '$this->get(',
    '$this->put(',
    '$this->delete(',
    'assertDatabase',
    'RefreshDatabase',
    'actingAs',
    'withHeader',
    'response->assert',
];

const DRIFT_KEYWORDS = [
    'Mockery::mock(Committee',
    'Mock(Committee',
    'mock(Committee',
    'new Committee(',
    'session(\'',
    'DB::table(\'committees_',
    'mock(CommitteeRepository',
];

class TestClassifier
{
    private array $tests = [];
    private array $categories = [
        'A' => [],
        'B' => [],
        'C' => [],
        'D' => [],
    ];

    public function __construct()
    {
        $this->indexTests();
    }

    private function indexTests(): void
    {
        $files = $this->findTestFiles(TESTS_DIR);

        foreach ($files as $file) {
            $this->tests[$file] = file_get_contents($file);
        }
    }

    private function findTestFiles(string $dir): array
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php' && str_contains($file->getFilename(), 'Test.php')) {
                $files[] = $file->getRealPath();
            }
        }

        return $files;
    }

    public function classify(): void
    {
        foreach ($this->tests as $file => $content) {
            $category = $this->classifyTest($content, $file);
            $this->categories[$category][] = [
                'file' => $this->relativePath($file),
                'score' => $this->getScore($content),
            ];
        }

        // Sort each category by filename
        foreach ($this->categories as &$tests) {
            usort($tests, fn($a, $b) => strcmp($a['file'], $b['file']));
        }
    }

    private function classifyTest(string $content, string $file): string
    {
        $scores = [
            'membership' => $this->countKeywords($content, MEMBERSHIP_KEYWORDS),
            'projection' => $this->countKeywords($content, PROJECTION_KEYWORDS),
            'integration' => $this->countKeywords($content, INTEGRATION_KEYWORDS),
            'drift' => $this->countKeywords($content, DRIFT_KEYWORDS),
        ];

        // Drift is a signal of problems
        if ($scores['drift'] > 0) {
            return 'C'; // Integration/Drift
        }

        // Integration tests (feature tests)
        if ($scores['integration'] > 2) {
            if ($scores['membership'] > 0) {
                return 'C'; // Mixed concerns = drift
            }
            return 'B'; // Pure integration tests
        }

        // Projection tests
        if ($scores['projection'] > $scores['membership']) {
            return 'B';
        }

        // Membership tests
        if ($scores['membership'] > 0) {
            return 'A';
        }

        // Unknown
        return 'D';
    }

    private function getScore(string $content): array
    {
        return [
            'membership' => $this->countKeywords($content, MEMBERSHIP_KEYWORDS),
            'projection' => $this->countKeywords($content, PROJECTION_KEYWORDS),
            'integration' => $this->countKeywords($content, INTEGRATION_KEYWORDS),
            'drift' => $this->countKeywords($content, DRIFT_KEYWORDS),
        ];
    }

    private function countKeywords(string $content, array $keywords): int
    {
        $count = 0;
        foreach ($keywords as $keyword) {
            $count += substr_count($content, $keyword);
        }
        return $count;
    }

    private function relativePath(string $path): string
    {
        return str_replace(dirname(TESTS_DIR) . '/', '', $path);
    }

    public function report(): string
    {
        $output = [];
        $output[] = "═══════════════════════════════════════════════════════════════";
        $output[] = "TEST CLASSIFICATION REPORT";
        $output[] = "═══════════════════════════════════════════════════════════════";
        $output[] = "";

        $total = 0;
        foreach (['A', 'B', 'C', 'D'] as $category) {
            $count = count($this->categories[$category]);
            $total += $count;

            $output[] = $this->categoryHeader($category) . " ({$count} tests)";
            $output[] = str_repeat("─", 65);

            foreach ($this->categories[$category] as $test) {
                $output[] = sprintf(
                    "  ✓ %s",
                    $test['file']
                );
            }

            $output[] = "";
        }

        $output[] = "═══════════════════════════════════════════════════════════════";
        $output[] = sprintf("TOTAL: %d tests classified", $total);
        $output[] = "";
        $output[] = "SUMMARY:";
        $output[] = sprintf("  A (Membership/Domain):     %3d tests - Domain truth layer", count($this->categories['A']));
        $output[] = sprintf("  B (Projection/Integration): %3d tests - Read models & queries", count($this->categories['B']));
        $output[] = sprintf("  C (Drift/Mixed):           %3d tests - Cross-context concerns", count($this->categories['C']));
        $output[] = sprintf("  D (Unknown):               %3d tests - Manual review needed", count($this->categories['D']));
        $output[] = "";
        $output[] = "NEXT STEPS:";
        $output[] = "  1. Category A: minimal fixes (domain correctness)";
        $output[] = "  2. Category B: ACL mapping alignment";
        $output[] = "  3. Category C: boundary refactoring (highest priority)";
        $output[] = "  4. Category D: clarify test intent";
        $output[] = "";

        return implode("\n", $output);
    }

    private function categoryHeader(string $cat): string
    {
        return match($cat) {
            'A' => "CATEGORY A — Membership/Domain Tests (Truth Layer)",
            'B' => "CATEGORY B — Projection/Integration Tests",
            'C' => "CATEGORY C — Drift/Mixed Concerns (⚠️  Priority)",
            'D' => "CATEGORY D — Unknown Intent (❓ Review)",
        };
    }

    public function export(string $file): void
    {
        file_put_contents($file, $this->report());
        echo "✅ Report written to: {$file}\n";
    }
}

// Execute
$classifier = new TestClassifier();
$classifier->classify();

$outputFile = $argv[1] ?? 'test-categories.txt';
$classifier->export($outputFile);

echo "\n" . $classifier->report();
