<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Architecture;

use App\Shared\Architecture\Rule\RuleCategory;
use App\Shared\Architecture\Rule\RuleResult;
use App\Shared\Architecture\Rule\RuleSeverity;
use PHPUnit\Framework\TestCase;

final class RuleResultTest extends TestCase
{
    public function test_it_passes_with_empty_violations(): void
    {
        $result = new RuleResult('test_rule', RuleSeverity::ERROR, RuleCategory::DOMAIN_PURITY);

        $this->assertTrue($result->passed());
        $this->assertFalse($result->failed());
        $this->assertSame(0, $result->violationCount());
    }

    public function test_it_fails_with_violations(): void
    {
        $result = new RuleResult('test_rule', RuleSeverity::ERROR, RuleCategory::DOMAIN_PURITY, [
            'file.php imports forbidden',
        ]);

        $this->assertFalse($result->passed());
        $this->assertTrue($result->failed());
        $this->assertSame(1, $result->violationCount());
    }

    public function test_it_serializes_to_json(): void
    {
        $result = new RuleResult('test_rule', RuleSeverity::WARNING, RuleCategory::IMMUTABILITY, [
            'file.php is not readonly',
        ]);

        $data = $result->jsonSerialize();

        $this->assertSame('test_rule', $data['rule']);
        $this->assertSame('WARNING', $data['severity']);
        $this->assertSame('immutability', $data['category']);
        $this->assertFalse($data['passed']);
        $this->assertCount(1, $data['violations']);
        $this->assertSame(1, $data['violation_count']);
    }

    public function test_severity_order(): void
    {
        $this->assertTrue(RuleSeverity::INFO->value < RuleSeverity::WARNING->value);
        $this->assertTrue(RuleSeverity::WARNING->value < RuleSeverity::ERROR->value);
        $this->assertTrue(RuleSeverity::ERROR->value < RuleSeverity::BLOCKER->value);
    }

    public function test_category_values(): void
    {
        $this->assertSame('domain_purity', RuleCategory::DOMAIN_PURITY->value);
        $this->assertSame('immutability', RuleCategory::IMMUTABILITY->value);
        $this->assertSame('dependency', RuleCategory::DEPENDENCY->value);
        $this->assertSame('cqrs', RuleCategory::CQRS->value);
        $this->assertSame('api_contract', RuleCategory::API_CONTRACT->value);
    }
}
