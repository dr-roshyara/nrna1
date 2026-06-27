<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Authority\Policies;

use App\Contexts\Governance\Domain\Authority\Policies\DelegationLifecyclePolicy;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationStatus;
use PHPUnit\Framework\TestCase;

final class DelegationLifecyclePolicyTest extends TestCase
{
    private DelegationLifecyclePolicy $policy;

    protected function setUp(): void
    {
        $this->policy = new DelegationLifecyclePolicy();
    }

    public function test_active_can_transition_to_revoked(): void
    {
        $this->assertTrue(
            $this->policy->canTransition(DelegationStatus::ACTIVE, DelegationStatus::REVOKED)
        );
    }

    public function test_active_cannot_remain_active(): void
    {
        $this->assertFalse(
            $this->policy->canTransition(DelegationStatus::ACTIVE, DelegationStatus::ACTIVE)
        );
    }

    public function test_revoked_is_terminal_for_all_targets(): void
    {
        foreach (DelegationStatus::cases() as $target) {
            $this->assertFalse(
                $this->policy->canTransition(DelegationStatus::REVOKED, $target),
                "REVOKED must not transition to {$target->name} — it is a terminal constitutional fact"
            );
        }
    }

    public function test_policy_is_stateless(): void
    {
        $reflection = new \ReflectionClass(DelegationLifecyclePolicy::class);
        $properties = $reflection->getProperties();

        $this->assertEmpty($properties, 'DelegationLifecyclePolicy must be stateless — no instance properties');
    }
}
