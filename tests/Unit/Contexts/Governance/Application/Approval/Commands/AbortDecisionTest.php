<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Application\Approval\Commands;

use App\Contexts\Governance\Application\Approval\Commands\AbortDecision;
use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;
use PHPUnit\Framework\TestCase;

final class AbortDecisionTest extends TestCase
{
    public function test_carries_all_required_fields(): void
    {
        $decisionId = GovernanceDecisionId::from('decision-1');
        $approvalId = ApprovalId::from('approval-1');

        $cmd = new AbortDecision(
            decisionId: $decisionId,
            approvalId: $approvalId,
            reason: 'Approval rejected by committee',
        );

        $this->assertSame($decisionId, $cmd->decisionId);
        $this->assertSame($approvalId, $cmd->approvalId);
        $this->assertSame('Approval rejected by committee', $cmd->reason);
    }

    public function test_is_readonly(): void
    {
        $ref = new \ReflectionClass(AbortDecision::class);
        $this->assertTrue($ref->isReadOnly());
    }
}
