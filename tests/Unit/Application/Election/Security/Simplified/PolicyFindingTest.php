<?php

namespace Tests\Unit\Application\Election\Security\Simplified;

use App\Application\Election\Security\Simplified\PolicyFinding;
use PHPUnit\Framework\TestCase;

class PolicyFindingTest extends TestCase
{
    public function test_constructs_with_all_properties(): void
    {
        $finding = new PolicyFinding(
            passed: true,
            constitutionalBasis: 'Verification required true and attested true satisfy Article 6',
            policyIdentifier: 'verification_policy',
            supportingFacts: ['required' => true, 'attested' => true],
        );

        $this->assertTrue($finding->passed);
        $this->assertSame('Verification required true and attested true satisfy Article 6', $finding->constitutionalBasis);
        $this->assertSame('verification_policy', $finding->policyIdentifier);
        $this->assertSame(['required' => true, 'attested' => true], $finding->supportingFacts);
    }

    public function test_can_represent_failure(): void
    {
        $finding = new PolicyFinding(
            passed: false,
            constitutionalBasis: 'Verification required true but attested false violates Article 6',
            policyIdentifier: 'verification_policy',
            supportingFacts: ['required' => true, 'attested' => false],
        );

        $this->assertFalse($finding->passed);
    }

    public function test_is_readonly(): void
    {
        $finding = new PolicyFinding(
            passed: true,
            constitutionalBasis: 'test',
            policyIdentifier: 'test',
            supportingFacts: [],
        );

        $this->expectException(\Error::class);
        $finding->passed = false;
    }

    public function test_has_no_behavioral_methods(): void
    {
        $reflection = new \ReflectionClass(PolicyFinding::class);
        $methods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => $m->getName() !== '__construct'
        );

        $this->assertCount(0, $methods, 'PolicyFinding must be pure data');
    }
}
