<?php

namespace Tests\Unit\Contracts;

use App\Contexts\Elections\Domain\Policies\VoterEligibilityPolicy;
use PHPUnit\Framework\TestCase;

/**
 * VoterEligibilityPolicyContractTest — Interface Shape Lock
 *
 * Responsibility: Verify interface exists and can be implemented.
 *
 * ✔ What we test:
 * - Interface exists
 * - Method exists
 * - It's truly an interface
 *
 * ❌ What we do NOT test:
 * - Parameter names (implementation detail)
 * - Return type internals (belongs in implementation tests)
 * - Implementation behavior (belongs in ElectionOnlyPolicyTest, etc.)
 * - Enum specifics (already validated in VoterSourceStrategyTest)
 *
 * Mental Model: "Can this contract exist and be implemented?"
 */
class VoterEligibilityPolicyContractTest extends TestCase
{
    /**
     * Test C.1.1: Interface exists
     */
    public function test_interface_exists(): void
    {
        $this->assertTrue(interface_exists(VoterEligibilityPolicy::class));
    }

    /**
     * Test C.1.2: isEligible method exists
     */
    public function test_interface_has_is_eligible_method(): void
    {
        $this->assertTrue(
            method_exists(VoterEligibilityPolicy::class, 'isEligible')
        );
    }

    /**
     * Test C.1.3: VoterEligibilityPolicy is interface, not class
     */
    public function test_interface_is_contract_only(): void
    {
        $reflection = new \ReflectionClass(VoterEligibilityPolicy::class);
        $this->assertTrue($reflection->isInterface());
    }
}
