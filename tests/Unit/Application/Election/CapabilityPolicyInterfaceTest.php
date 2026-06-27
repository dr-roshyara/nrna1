<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\Policy\CapabilityPolicy;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use Tests\TestCase;

class CapabilityPolicyInterfaceTest extends TestCase
{
    public function test_policy_can_grant_capability(): void
    {
        $policy = new class implements CapabilityPolicy {
            public function evaluate(CapabilityContext $context): ?CapabilityDecision
            {
                return CapabilityDecision::grant();
            }

            public function layer(): CapabilityPolicyLayer
            {
                return CapabilityPolicyLayer::Lifecycle;
            }
        };

        $election = new Election();
        $context = new CapabilityContext(
            $election,
            null,
            'open_voting',
            [],
            ElectionLifecycleState::Draft
        );

        $decision = $policy->evaluate($context);

        $this->assertInstanceOf(CapabilityDecision::class, $decision);
        $this->assertTrue($decision->allows());
    }

    public function test_policy_can_deny_capability(): void
    {
        $policy = new class implements CapabilityPolicy {
            public function evaluate(CapabilityContext $context): ?CapabilityDecision
            {
                return CapabilityDecision::deny(CapabilityDenialReason::Suspended, 'admin hold');
            }

            public function layer(): CapabilityPolicyLayer
            {
                return CapabilityPolicyLayer::Overlay;
            }
        };

        $election = new Election();
        $context = new CapabilityContext(
            $election,
            null,
            'open_voting',
            [],
            ElectionLifecycleState::VotingActive
        );

        $decision = $policy->evaluate($context);

        $this->assertInstanceOf(CapabilityDecision::class, $decision);
        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::Suspended, $decision->reason);
    }

    public function test_policy_can_abstain(): void
    {
        $policy = new class implements CapabilityPolicy {
            public function evaluate(CapabilityContext $context): ?CapabilityDecision
            {
                return CapabilityDecision::abstain();
            }

            public function layer(): CapabilityPolicyLayer
            {
                return CapabilityPolicyLayer::Authorization;
            }
        };

        $election = new Election();
        $context = new CapabilityContext(
            $election,
            null,
            'open_voting',
            [],
            ElectionLifecycleState::Draft
        );

        $decision = $policy->evaluate($context);

        $this->assertInstanceOf(CapabilityDecision::class, $decision);
        $this->assertTrue($decision->allows());
    }

    public function test_policy_declares_layer(): void
    {
        $overlayPolicy = new class implements CapabilityPolicy {
            public function evaluate(CapabilityContext $context): ?CapabilityDecision
            {
                return CapabilityDecision::abstain();
            }

            public function layer(): CapabilityPolicyLayer
            {
                return CapabilityPolicyLayer::Overlay;
            }
        };

        $lifecyclePolicy = new class implements CapabilityPolicy {
            public function evaluate(CapabilityContext $context): ?CapabilityDecision
            {
                return CapabilityDecision::abstain();
            }

            public function layer(): CapabilityPolicyLayer
            {
                return CapabilityPolicyLayer::Lifecycle;
            }
        };

        $this->assertEquals(CapabilityPolicyLayer::Overlay, $overlayPolicy->layer());
        $this->assertEquals(CapabilityPolicyLayer::Lifecycle, $lifecyclePolicy->layer());
    }

    public function test_multiple_policies_can_coexist(): void
    {
        $policies = [
            new class implements CapabilityPolicy {
                public function evaluate(CapabilityContext $context): ?CapabilityDecision
                {
                    return CapabilityDecision::abstain();
                }

                public function layer(): CapabilityPolicyLayer
                {
                    return CapabilityPolicyLayer::Overlay;
                }
            },
            new class implements CapabilityPolicy {
                public function evaluate(CapabilityContext $context): ?CapabilityDecision
                {
                    return CapabilityDecision::abstain();
                }

                public function layer(): CapabilityPolicyLayer
                {
                    return CapabilityPolicyLayer::Lifecycle;
                }
            },
            new class implements CapabilityPolicy {
                public function evaluate(CapabilityContext $context): ?CapabilityDecision
                {
                    return CapabilityDecision::abstain();
                }

                public function layer(): CapabilityPolicyLayer
                {
                    return CapabilityPolicyLayer::Authorization;
                }
            },
        ];

        $this->assertCount(3, $policies);
        $this->assertInstanceOf(CapabilityPolicy::class, $policies[0]);
        $this->assertInstanceOf(CapabilityPolicy::class, $policies[1]);
        $this->assertInstanceOf(CapabilityPolicy::class, $policies[2]);
    }
}
