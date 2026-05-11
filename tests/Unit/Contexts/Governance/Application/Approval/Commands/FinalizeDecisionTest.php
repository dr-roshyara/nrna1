<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Application\Approval\Commands;

use App\Contexts\Governance\Application\Approval\Commands\FinalizeDecision;
use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;
use PHPUnit\Framework\TestCase;

final class FinalizeDecisionTest extends TestCase
{
    public function test_carries_all_required_fields(): void
    {
        $decisionId = GovernanceDecisionId::from('decision-1');
        $approvalId = ApprovalId::from('approval-1');

        $cmd = new FinalizeDecision(
            decisionId: $decisionId,
            approvalId: $approvalId,
        );

        $this->assertSame($decisionId, $cmd->decisionId);
        $this->assertSame($approvalId, $cmd->approvalId);
    }

    public function test_is_readonly(): void
    {
        $ref = new \ReflectionClass(FinalizeDecision::class);
        $this->assertTrue($ref->isReadOnly());
    }
}
