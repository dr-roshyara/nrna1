<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Committee\Policies;

use App\Contexts\Governance\Domain\Committee\Policies\OperationalStatePolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeFacts;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\StructuralOperationalState;
use PHPUnit\Framework\TestCase;

final class OperationalStatePolicyTest extends TestCase
{
    private OperationalStatePolicy $policy;
    private CommitteeId $id;

    protected function setUp(): void
    {
        $this->policy = new OperationalStatePolicy();
        $this->id = CommitteeId::fromString('test-committee-id');
    }

    public function test_returns_active_for_active_committee(): void
    {
        $facts = new CommitteeFacts(
            id: $this->id,
            operationalState: 'ACTIVE',
            term: null,
            parentId: null,
        );

        $result = $this->policy->evaluate($facts);

        $this->assertSame(StructuralOperationalState::ACTIVE, $result);
    }

    public function test_returns_suspended_for_suspended_committee(): void
    {
        $facts = new CommitteeFacts(
            id: $this->id,
            operationalState: 'SUSPENDED',
            term: null,
            parentId: null,
        );

        $result = $this->policy->evaluate($facts);

        $this->assertSame(StructuralOperationalState::SUSPENDED, $result);
    }

    public function test_returns_dissolved_for_dissolved_committee(): void
    {
        $facts = new CommitteeFacts(
            id: $this->id,
            operationalState: 'DISSOLVED',
            term: null,
            parentId: null,
        );

        $result = $this->policy->evaluate($facts);

        $this->assertSame(StructuralOperationalState::DISSOLVED, $result);
    }

    public function test_throws_value_error_for_invalid_operational_state(): void
    {
        $facts = new CommitteeFacts(
            id: $this->id,
            operationalState: 'INVALID_STATE',
            term: null,
            parentId: null,
        );

        $this->expectException(\ValueError::class);

        $this->policy->evaluate($facts);
    }
}
