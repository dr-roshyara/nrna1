<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Shared\Architecture\Baseline\BaselineManager;
use App\Shared\Architecture\Runner\RuleRunner;
use Illuminate\Console\Command;

final class ArchitectureBaselineCommand extends Command
{
    protected $signature = 'architecture:baseline
        {context : Ruleset context name (e.g. geography)}
        {--diff : Show new vs resolved violations without regenerating}
        {--format=summary : Output format (summary|detailed)}';

    protected $description = 'Generate or diff architecture enforcement baselines';

    public function __construct(
        private readonly RuleRunner $runner,
        private readonly BaselineManager $baselineManager,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $context = $this->argument('context');
        $showDiff = $this->option('diff');
        $format = $this->option('format');

        // Resolve ruleset for the given context
        $ruleset = $this->resolveRuleset($context);
        if ($ruleset === null) {
            $this->error("Unknown context: {$context}");
            $this->line('Available contexts: geography');
            return 1;
        }

        if ($showDiff) {
            return $this->handleDiff($context, $ruleset, $format);
        }

        return $this->handleGenerate($context, $ruleset, $format);
    }

    private function handleGenerate(string $context, array $ruleset, string $format): int
    {
        [$rules, $config] = $ruleset;
        $this->info("Generating baseline for context: {$context}");

        $results = $this->runner->run($rules, $config);
        $violations = $this->baselineManager->generate($context, $results);

        $failedResults = $this->runner->failedResults($results);
        $failedCount = array_sum(array_map(fn ($r) => $r->violationCount(), $failedResults));

        $this->line("Baseline generated: {$failedCount} violation(s) across " . count($failedResults) . " rule(s)");
        $this->line("Storage: storage/architecture-baselines/{$context}.json");

        if ($format === 'detailed' && $violations !== []) {
            $this->newLine();
            $this->line('Violations captured:');
            foreach ($violations as $violation) {
                $this->line("  [{$violation->rule}] {$violation->file}:{$violation->symbol}");
                $this->line("    {$violation->message}");
            }
        }

        $this->newLine();
        $this->info('Baseline generation complete.');

        return 0;
    }

    private function handleDiff(string $context, array $ruleset, string $format): int
    {
        [$rules, $config] = $ruleset;

        // Load existing baseline
        $baseline = $this->baselineManager->load($context);
        if ($baseline === []) {
            $this->warn("No existing baseline found for context: {$context}");
            $this->line('Generate one first: php artisan architecture:baseline ' . $context);
            return 1;
        }

        // Run current rules
        $results = $this->runner->run($rules, $config);
        $currentViolations = $this->collectViolations($results);
        $baselineIndexed = $baseline;

        [$newViolations, $resolvedViolations] = $this->computeDiff($currentViolations, $baselineIndexed);

        $this->info("Baseline diff for context: {$context}");
        $this->newLine();
        $this->line("New violations:      " . count($newViolations));
        $this->line("Resolved violations: " . count($resolvedViolations));
        $this->newLine();

        if ($newViolations !== [] && $format === 'detailed') {
            $this->line('NEW violations:');
            foreach ($newViolations as $v) {
                $this->line("  [{$v->rule}] {$v->file}:{$v->symbol}");
                $this->line("    {$v->message}");
            }
            $this->newLine();
        }

        if ($resolvedViolations !== [] && $format === 'detailed') {
            $this->line('RESOLVED violations:');
            foreach ($resolvedViolations as $v) {
                $this->line("  [{$v->rule}] {$v->file}:{$v->symbol}");
            }
            $this->newLine();
        }

        return (int) ($newViolations !== []);
    }

    /**
     * @return array{0: StructuredViolation[], 1: StructuredViolation[]}
     */
    private function computeDiff(array $current, array $baseline): array
    {
        $new = [];
        $resolved = [];

        $currentHashes = array_map(fn ($v) => $v->hash, $current);

        foreach ($current as $v) {
            if (!isset($baseline[$v->hash])) {
                $new[] = $v;
            }
        }

        foreach ($baseline as $hash => $v) {
            if (!in_array($hash, $currentHashes, true)) {
                $resolved[] = $v;
            }
        }

        return [$new, $resolved];
    }

    /**
     * @return StructuredViolation[]
     */
    private function collectViolations(array $results): array
    {
        $violations = [];
        foreach ($results as $result) {
            foreach ($result->violations as $violation) {
                $violations[] = \App\Shared\Architecture\Baseline\StructuredViolation::create(
                    rule: $result->ruleName,
                    file: $this->extractFile($violation),
                    symbol: $this->extractSymbol($violation),
                    message: $violation,
                );
            }
        }
        return $violations;
    }

    /**
     * @return array{0: ArchitectureRule[], 1: array<string, mixed>}|null
     */
    private function resolveRuleset(string $context): ?array
    {
        $rulesets = [
            'geography' => new \App\Shared\Architecture\Config\GeographyRuleset(),
        ];

        if (!isset($rulesets[$context])) {
            return null;
        }

        $rs = $rulesets[$context];
        return [$rs->rules(), $rs->config()];
    }

    private function extractFile(string $violation): string
    {
        if (preg_match('#([A-Za-z]:(?:\\\\|/)[^:\n]+\.php|/[^:\n]+\.php|(?:\./)?[^:\n]+\.php)#', $violation, $m)) {
            return str_replace('\\', '/', $m[1]);
        }
        return 'unknown';
    }

    private function extractSymbol(string $violation): string
    {
        if (preg_match('#\b([A-Z][a-zA-Z0-9_]+)\b#', $violation, $m)) {
            return $m[1];
        }
        return 'unknown';
    }
}
