#!/usr/bin/env php
<?php
/**
 * AST-Based Test Intent Classifier for Category C Tests
 *
 * Parses PHP test files via AST to deterministically classify Category C tests
 * into C-Domain, C-Projection, or C-Integration intent classes.
 *
 * Only processes Category C tests (76 tests with mixed concerns).
 *
 * Usage: php bin/test-intent-classifier.php [output-file]
 */

declare(strict_types=1);

use PhpParser\ParserFactory;
use PhpParser\NodeVisitor;
use PhpParser\Node;
use PhpParser\NodeTraverser;

const TESTS_DIR = __DIR__ . '/../tests';

// Load composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

class TestSignalCollector extends \PhpParser\NodeVisitorAbstract
{
    public array $signals = [
        'domain_aggregates' => [],    // Committee, Member, MembershipLineage
        'projection_models' => [],    // Views, DTOs, Resources
        'http_operations' => [],      // get(), post(), requests
        'acl_operations' => [],       // Authorization checks
        'repository_mocks' => [],     // Mock repositories
        'policy_checks' => [],        // Policy enforcement
        'transaction_boundaries' => 0, // DB::transaction
        'event_dispatch' => 0,         // event()
    ];

    public function enterNode(Node $node): ?int
    {
        // Method call patterns
        if ($node instanceof Node\Expr\MethodCall) {
            if ($node->name instanceof Node\Identifier) {
                $method = $node->name->name;

                // HTTP signals
                if (in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
                    $this->signals['http_operations'][] = $method;
                }

                // Policy checks
                if (str_contains($method, 'Policy') || str_contains($method, 'isEligible')) {
                    $this->signals['policy_checks'][] = $method;
                }
            }
        }

        // Static calls
        if ($node instanceof Node\Expr\StaticCall) {
            if ($node->name instanceof Node\Identifier && $node->class instanceof Node\Name) {
                $class = $node->class->toString();
                $method = $node->name->name;

                // Domain aggregates
                if (str_contains($class, 'Committee') || str_contains($class, 'Member')) {
                    $this->signals['domain_aggregates'][] = "{$class}::{$method}";
                }

                // Database transactions
                if ($class === 'DB' && $method === 'transaction') {
                    $this->signals['transaction_boundaries']++;
                }

                // Event dispatch
                if ($class === 'Event' || str_contains($method, 'dispatch') || str_contains($method, 'event')) {
                    $this->signals['event_dispatch']++;
                }
            }
        }

        // Catch blocks for exception types
        if ($node instanceof Node\Stmt\Catch_) {
            foreach ($node->types as $type) {
                if ($type instanceof Node\Name) {
                    $name = $type->toString();
                    if (str_contains($name, 'CommitteeEligibility') || str_contains($name, 'DomainException')) {
                        $this->signals['domain_aggregates'][] = "catch:{$name}";
                    }
                }
            }
        }

        // New expressions
        if ($node instanceof Node\Expr\New_) {
            if ($node->class instanceof Node\Name) {
                $class = $node->class->toString();
                if (str_contains($class, 'Repository') || str_contains($class, 'Service')) {
                    $this->signals['repository_mocks'][] = $class;
                }
                if (str_contains($class, 'View') || str_contains($class, 'Resource') || str_contains($class, 'DTO')) {
                    $this->signals['projection_models'][] = $class;
                }
            }
        }

        // Assert method calls
        if ($node instanceof Node\Expr\MethodCall) {
            if ($node->name instanceof Node\Identifier) {
                $assert = $node->name->name;
                if (str_starts_with($assert, 'assert')) {
                    if (str_contains($assert, 'Database') || str_contains($assert, 'Count')) {
                        $this->signals['http_operations'][] = $assert;
                    }
                }
            }
        }

        return null;
    }
}

class IntentClassifier
{
    private array $categoryCTests = [];
    private array $classifications = [
        'C-DOMAIN' => [],
        'C-PROJECTION' => [],
        'C-INTEGRATION' => [],
    ];

    private \PhpParser\Parser $parser;
    private NodeTraverser $traverser;

    public function __construct()
    {
        $parserFactory = new ParserFactory();
        $this->parser = $parserFactory->createForNewestSupportedVersion();
        $this->traverser = new NodeTraverser();
        $this->extractCategoryC();
    }

    private function extractCategoryC(): void
    {
        $categoryFile = __DIR__ . '/../test-categories.txt';
        if (!file_exists($categoryFile)) {
            echo "Error: test-categories.txt not found. Run: php bin/classify-tests.php\n";
            exit(1);
        }

        $content = file_get_contents($categoryFile);
        $lines = explode("\n", $content);

        $inCategoryC = false;
        foreach ($lines as $line) {
            if (str_contains($line, 'CATEGORY C')) {
                $inCategoryC = true;
                continue;
            }

            if (str_starts_with(trim($line), 'CATEGORY') && !str_contains($line, 'CATEGORY C')) {
                $inCategoryC = false;
            }

            if ($inCategoryC && str_contains($line, '.php')) {
                // Extract file path from output line - path is already absolute
                if (preg_match('/✓\s+(.+\.php)$/', trim($line), $matches)) {
                    $fullPath = $matches[1];

                    if (file_exists($fullPath)) {
                        $this->categoryCTests[$fullPath] = file_get_contents($fullPath);
                    }
                }
            }
        }

        echo "Found " . count($this->categoryCTests) . " Category C tests\n";
    }

    public function classify(): void
    {
        foreach ($this->categoryCTests as $file => $content) {
            $intent = $this->classifyTest($content, $file);
            $this->classifications[$intent][] = $this->relativePath($file);
        }

        // Sort each category
        foreach ($this->classifications as &$files) {
            sort($files);
        }
    }

    private function classifyTest(string $content, string $file): string
    {
        try {
            $ast = $this->parser->parse($content);
        } catch (\Throwable $e) {
            return 'C-INTEGRATION'; // Unparseable defaults to integration
        }

        $collector = new TestSignalCollector();
        $this->traverser->addVisitor($collector);

        try {
            $this->traverser->traverse($ast);
        } catch (\Throwable $e) {
            // Continue on parse errors
        }

        $this->traverser->removeVisitor($collector);

        $signals = $collector->signals;

        // Scoring logic - deterministic intent classification
        $domainScore = count($signals['domain_aggregates']) +
                       count($signals['policy_checks']) * 2 +
                       $signals['transaction_boundaries'] * 3;

        $projectionScore = count($signals['projection_models']) * 2;

        $integrationScore = count($signals['http_operations']) +
                           count($signals['acl_operations']) +
                           $signals['event_dispatch'] * 2;

        // Classification rules
        // If both domain + http/integration concerns, it's C-INTEGRATION
        if ($domainScore > 0 && $integrationScore > 1) {
            return 'C-INTEGRATION';
        }

        // If domain is dominant concern
        if ($domainScore > $projectionScore && $domainScore > 0) {
            return 'C-DOMAIN';
        }

        // If projection is dominant or no clear domain signals
        if ($projectionScore > 0 || $integrationScore == 0) {
            return 'C-PROJECTION';
        }

        // Default to integration if mixed signals
        return 'C-INTEGRATION';
    }

    private function relativePath(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $testsDir = str_replace('\\', '/', TESTS_DIR);
        return str_replace($testsDir . '/', 'tests/', $path);
    }

    public function export(string $file): void
    {
        $output = [
            'timestamp' => date('Y-m-d H:i:s'),
            'total_tests' => array_sum(array_map('count', $this->classifications)),
            'classifications' => $this->classifications,
            'summary' => [
                'C-DOMAIN' => count($this->classifications['C-DOMAIN']),
                'C-PROJECTION' => count($this->classifications['C-PROJECTION']),
                'C-INTEGRATION' => count($this->classifications['C-INTEGRATION']),
            ],
        ];

        file_put_contents($file, json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        echo "✅ Intent classifications written to: {$file}\n";
    }

    public function report(): string
    {
        $output = [];
        $output[] = "═══════════════════════════════════════════════════════════════";
        $output[] = "CATEGORY C TEST INTENT CLASSIFICATION REPORT";
        $output[] = "═══════════════════════════════════════════════════════════════";
        $output[] = "";

        $total = array_sum(array_map('count', $this->classifications));

        // C-DOMAIN
        $output[] = "C-DOMAIN Tests (" . count($this->classifications['C-DOMAIN']) . ")";
        $output[] = "━ Primary truth is domain aggregate behavior (ignore projections)";
        $output[] = "━ REFACTOR: Remove all read model/projection assertions";
        $output[] = "━ KEEP: Domain invariants, policy validation, exception types";
        foreach ($this->classifications['C-DOMAIN'] as $test) {
            $output[] = "  ✓ {$test}";
        }
        $output[] = "";

        // C-PROJECTION
        $output[] = "C-PROJECTION Tests (" . count($this->classifications['C-PROJECTION']) . ")";
        $output[] = "━ Primary truth is read model output (ignore domain setup)";
        $output[] = "━ REFACTOR: Remove domain aggregate construction, keep views/DTOs";
        $output[] = "━ KEEP: Projection transformation, serialization, formatting";
        foreach ($this->classifications['C-PROJECTION'] as $test) {
            $output[] = "  ✓ {$test}";
        }
        $output[] = "";

        // C-INTEGRATION
        $output[] = "C-INTEGRATION Tests (" . count($this->classifications['C-INTEGRATION']) . ")";
        $output[] = "━ Spans domain AND http/acl boundaries (MUST be split)";
        $output[] = "━ REFACTOR: Split into 2 tests: (1) domain-only, (2) e2e-flow";
        $output[] = "━ Pattern: Setup domain state → assert domain truth; then HTTP layer";
        foreach ($this->classifications['C-INTEGRATION'] as $test) {
            $output[] = "  ✓ {$test}";
        }
        $output[] = "";

        $output[] = "═══════════════════════════════════════════════════════════════";
        $output[] = sprintf("TOTAL: %d Category C tests analyzed", $total);
        $output[] = "";
        $output[] = "CONVERGENCE STRATEGY:";
        $output[] = sprintf("  Phase 1: Refactor C-DOMAIN (%d tests) — remove projection noise", count($this->classifications['C-DOMAIN']));
        $output[] = sprintf("  Phase 2: Refactor C-PROJECTION (%d tests) — remove domain setup", count($this->classifications['C-PROJECTION']));
        $output[] = sprintf("  Phase 3: Split C-INTEGRATION (%d tests) — enforce boundary separation", count($this->classifications['C-INTEGRATION']));
        $output[] = "";
        $output[] = "This eliminates Category C via intentional decomposition, not deletion.";
        $output[] = "";

        return implode("\n", $output);
    }
}

// Execute
try {
    $classifier = new IntentClassifier();
    $classifier->classify();

    $outputFile = $argv[1] ?? 'category-c-intent-map.json';
    $classifier->export($outputFile);

    echo "\n" . $classifier->report();
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
