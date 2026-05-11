<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Approval\Events;

use App\Contexts\Governance\Domain\Approval\Events\ApprovalRequested;
use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Governance\Domain\Approval\ValueObjects\IdempotencyKey;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ApprovalRequestedTest extends TestCase
{
    private function makeEvent(): ApprovalRequested
    {
        return ApprovalRequested::occur(
            approvalId: ApprovalId::from('approval-1'),
            decisionId: GovernanceDecisionId::from('decision-1'),
            requiredApprovals: [CommitteeId::fromString('committee-a'), CommitteeId::fromString('committee-b')],
            requestedAt: new DateTimeImmutable('2026-01-01 10:00:00'),
            idempotencyKey: IdempotencyKey::from('key-abc'),
        );
    }

    public function test_carries_all_required_fields(): void
    {
        $event = $this->makeEvent();

        $this->assertSame('approval-1', $event->approvalId()->value());
        $this->assertSame('decision-1', $event->decisionId()->toString());
        $this->assertCount(2, $event->requiredApprovals());
        $this->assertSame('2026-01-01 10:00:00', $event->requestedAt()->format('Y-m-d H:i:s'));
        $this->assertSame('key-abc', $event->idempotencyKey()->value());
    }

    public function test_required_approvals_are_committee_id_instances(): void
    {
        $event = $this->makeEvent();

        foreach ($event->requiredApprovals() as $committeeId) {
            $this->assertInstanceOf(CommitteeId::class, $committeeId);
        }
    }

    public function test_is_immutable_readonly(): void
    {
        $ref = new \ReflectionClass(ApprovalRequested::class);
        $this->assertTrue($ref->isReadOnly());
    }

    public function test_has_private_constructor(): void
    {
        $ref = new \ReflectionClass(ApprovalRequested::class);
        $this->assertTrue($ref->getConstructor()->isPrivate());
    }
}
