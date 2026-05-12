<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Architecture;

use App\Shared\Architecture\Rule\ArchitectureRule;
use App\Shared\Architecture\Rule\RuleCategory;
use App\Shared\Architecture\Rule\RuleResult;
use App\Shared\Architecture\Rule\RuleSeverity;
use App\Shared\Architecture\Runner\RuleRunner;
use PHPUnit\Framework\TestCase;

final class RuleRunnerTest extends TestCase
{
    public function test_it_runs_all_rules(): void
    {
        $runner = new RuleRunner();
        $rules = [
            new class implements ArchitectureRule {
                public function name(): string { return 'passing_rule'; }
                public function category(): RuleCategory { return RuleCategory::DOMAIN_PURITY; }
                public function severity(): RuleSeverity { return RuleSeverity::INFO; }
                public function check(array $config): RuleResult {
                    return new RuleResult($this->name(), $this->severity(), $this->category());
                }
            },
            new class implements ArchitectureRule {
                public function name(): string { return 'failing_rule'; }
                public function category(): RuleCategory { return RuleCategory::DEPENDENCY; }
                public function severity(): RuleSeverity { return RuleSeverity::ERROR; }
                public function check(array $config): RuleResult {
                    return new RuleResult($this->name(), $this->severity(), $this->category(), ['violation']);
                }
            },
        ];

        $results = $runner->run($rules);

        $this->assertCount(2, $results);
        $this->assertTrue($results[0]->passed());
        $this->assertFalse($results[1]->passed());
    }

    public function test_it_filters_by_minimum_severity(): void
    {
        $runner = new RuleRunner();
        $rules = [
            new class implements ArchitectureRule {
                public function name(): string { return 'info_rule'; }
                public function category(): RuleCategory { return RuleCategory::DOMAIN_PURITY; }
                public function severity(): RuleSeverity { return RuleSeverity::INFO; }
                public function check(array $config): RuleResult {
                    return new RuleResult($this->name(), $this->severity(), $this->category(), ['info violation']);
                }
            },
            new class implements ArchitectureRule {
                public function name(): string { return 'error_rule'; }
                public function category(): RuleCategory { return RuleCategory::DEPENDENCY; }
                public function severity(): RuleSeverity { return RuleSeverity::ERROR; }
                public function check(array $config): RuleResult {
                    return new RuleResult($this->name(), $this->severity(), $this->category(), ['error violation']);
                }
            },
        ];

        $results = $runner->runWithMinimumSeverity($rules, RuleSeverity::ERROR);

        $this->assertCount(1, $results);
        $this->assertSame('error_rule', $results[0]->ruleName);
    }

    public function test_it_formats_failures(): void
    {
        $runner = new RuleRunner();
        $results = [
            new RuleResult('pass', RuleSeverity::INFO, RuleCategory::DOMAIN_PURITY),
            new RuleResult('fail', RuleSeverity::ERROR, RuleCategory::DEPENDENCY, ['file1.php problem', 'file2.php problem']),
        ];

        $formatted = $runner->formatFailures($results);

        $this->assertStringContainsString('[ERROR]', $formatted);
        $this->assertStringContainsString('fail', $formatted);
        $this->assertStringContainsString('file1.php problem', $formatted);
        $this->assertStringContainsString('file2.php problem', $formatted);
        $this->assertStringNotContainsString('[INFO]', $formatted);
    }

    public function test_it_returns_only_failed_results(): void
    {
        $runner = new RuleRunner();
        $results = [
            new RuleResult('pass', RuleSeverity::INFO, RuleCategory::DOMAIN_PURITY),
            new RuleResult('fail', RuleSeverity::ERROR, RuleCategory::DEPENDENCY, ['violation']),
        ];

        $failed = $runner->failedResults($results);

        $this->assertCount(1, $failed);
        $this->assertSame('fail', $failed[0]->ruleName);
    }
}
