<?php

namespace Tests\Unit\Application\Election\Capabilities\Policies;

use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Application\Election\Capabilities\Policies\TrustCapabilityPolicy;
use PHPUnit\Framework\TestCase;

/**
 * TrustCapabilityPolicyTest
 *
 * TEMPORARY: Tests refactored during D.R.2 overlay sovereignty audit.
 * The TrustCapabilityPolicy still uses old envelope structure (TrustEvaluationEnvelope).
 * Once TrustCapabilityPolicy is refactored to EvidenceCapabilityPolicy (D.R.2 freezing),
 * these tests will be expanded to verify the new frozen vocabulary behavior.
 *
 * For now, we verify only the layer contract.
 */
class TrustCapabilityPolicyTest extends TestCase
{
    public function test_policy_layer_returns_trust_priority(): void
    {
        $policy = new TrustCapabilityPolicy();

        $this->assertEquals(CapabilityPolicyLayer::Trust, $policy->layer());
        $this->assertEquals(2, CapabilityPolicyLayer::Trust->value);
    }
}
