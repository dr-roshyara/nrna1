<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Approval\Policies;

use App\Contexts\Governance\Domain\Approval\Policies\ApprovalRequirementPolicy;
use PHPUnit\Framework\TestCase;

final class ApprovalRequirementPolicyTest extends TestCase
{
    private ApprovalRequirementPolicy $policy;

    protected function setUp(): void
    {
        $this->policy = new ApprovalRequirementPolicy();
    }

    public function test_committee_formation_requires_approval(): void
    {
        $this->assertTrue($this->policy->requiresApproval('COMMITTEE_FORMATION'));
    }

    public function test_committee_formation_returns_parent_committee_strategy(): void
    {
        $types = $this->policy->requiredApproverTypes('COMMITTEE_FORMATION');
        $this->assertSame(['PARENT_COMMITTEE'], $types);
    }

    public function test_constitutional_amendment_requires_approval(): void
    {
        $this->assertTrue($this->policy->requiresApproval('CONSTITUTIONAL_AMENDMENT'));
    }

    public function test_constitutional_amendment_returns_icc_board_strategy(): void
    {
        $types = $this->policy->requiredApproverTypes('CONSTITUTIONAL_AMENDMENT');
        $this->assertSame(['ICC_BOARD'], $types);
    }

    public function test_authority_delegation_requires_approval(): void
    {
        $this->assertTrue($this->policy->requiresApproval('AUTHORITY_DELEGATION'));
    }

    public function test_authority_delegation_returns_source_committee_strategy(): void
    {
        $types = $this->policy->requiredApproverTypes('AUTHORITY_DELEGATION');
        $this->assertSame(['SOURCE_COMMITTEE'], $types);
    }

    public function test_routine_decision_does_not_require_approval(): void
    {
        $this->assertFalse($this->policy->requiresApproval('ROUTINE_DECISION'));
    }

    public function test_routine_decision_returns_empty_types(): void
    {
        $types = $this->policy->requiredApproverTypes('ROUTINE_DECISION');
        $this->assertSame([], $types);
    }

    public function test_policy_is_stateless_no_constructor_dependencies(): void
    {
        $ref = new \ReflectionClass(ApprovalRequirementPolicy::class);
        $constructor = $ref->getConstructor();
        $params = $constructor ? $constructor->getParameters() : [];
        $this->assertCount(0, $params, 'Policy must have no constructor dependencies');
    }

    public function test_policy_has_no_instance_properties(): void
    {
        $ref = new \ReflectionClass(ApprovalRequirementPolicy::class);
        $properties = $ref->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED);
        $this->assertCount(0, $properties, 'Policy must have no instance properties');
    }
}
