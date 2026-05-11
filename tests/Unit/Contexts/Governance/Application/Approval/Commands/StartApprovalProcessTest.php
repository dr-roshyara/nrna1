<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Application\Approval\Commands;

use App\Contexts\Governance\Application\Approval\Commands\StartApprovalProcess;
use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Governance\Domain\Approval\ValueObjects\IdempotencyKey;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use PHPUnit\Framework\TestCase;

final class StartApprovalProcessTest extends TestCase
{
    public function test_carries_all_required_fields(): void
    {
        $approvalId = ApprovalId::from('approval-1');
        $decisionId = GovernanceDecisionId::from('decision-1');
        $required = [CommitteeId::fromString('committee-a')];
        $key = IdempotencyKey::from('key-1');

        $cmd = new StartApprovalProcess(
            approvalId: $approvalId,
            decisionId: $decisionId,
            requiredApprovals: $required,
            idempotencyKey: $key,
        );

        $this->assertSame($approvalId, $cmd->approvalId);
        $this->assertSame($decisionId, $cmd->decisionId);
        $this->assertSame($required, $cmd->requiredApprovals);
        $this->assertSame($key, $cmd->idempotencyKey);
    }

    public function test_is_readonly(): void
    {
        $ref = new \ReflectionClass(StartApprovalProcess::class);
        $this->assertTrue($ref->isReadOnly());
    }
}
