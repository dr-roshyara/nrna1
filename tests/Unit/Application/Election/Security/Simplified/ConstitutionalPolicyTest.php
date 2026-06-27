<?php

namespace Tests\Unit\Application\Election\Security\Simplified;

use App\Application\Election\Security\Simplified\ConstitutionalPolicy;
use App\Application\Election\Security\Simplified\PolicyFinding;
use App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot;
use PHPUnit\Framework\TestCase;

class ConstitutionalPolicyTest extends TestCase
{
    public function test_interface_requires_evaluate_method(): void
    {
        $reflection = new \ReflectionClass(ConstitutionalPolicy::class);
        $methods = $reflection->getMethods();

        $evaluateMethods = array_filter($methods, fn($m) => $m->getName() === 'evaluate');
        $this->assertCount(1, $evaluateMethods);

        $evaluate = $evaluateMethods[0];
        $this->assertTrue($evaluate->hasReturnType());
        $this->assertSame(PolicyFinding::class, $evaluate->getReturnType()->getName());

        $params = $evaluate->getParameters();
        $this->assertCount(1, $params);
        $this->assertSame(ConstitutionalEvidenceSnapshot::class, $params[0]->getType()->getName());
    }

    public function test_interface_has_only_one_method(): void
    {
        $reflection = new \ReflectionClass(ConstitutionalPolicy::class);
        $methods = $reflection->getMethods();

        // Interfaces may have inherited methods from object
        $ownMethods = array_filter($methods, fn($m) => $m->getDeclaringClass()->getName() === ConstitutionalPolicy::class);
        $this->assertCount(1, $ownMethods);
    }
}
