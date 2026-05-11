<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Approval\Events;

use App\Contexts\Governance\Domain\Approval\Events\ApprovalRejected;
use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ApprovalRejectedTest extends TestCase
{
    private function makeEvent(): ApprovalRejected
    {
        return ApprovalRejected::occur(
            approvalId: ApprovalId::from('approval-1'),
            rejectingCommitteeId: CommitteeId::fromString('committee-b'),
            rejectedBy: MemberId::from('member-2'),
            rejectedAt: new DateTimeImmutable('2026-01-03 14:00:00'),
            reason: 'Committee not constitutionally quorate',
        );
    }

    public function test_carries_all_required_fields(): void
    {
        $event = $this->makeEvent();

        $this->assertSame('approval-1', $event->approvalId()->value());
        $this->assertSame('committee-b', $event->rejectingCommitteeId()->value());
        $this->assertSame('member-2', $event->rejectedBy()->value());
        $this->assertSame('2026-01-03 14:00:00', $event->rejectedAt()->format('Y-m-d H:i:s'));
        $this->assertSame('Committee not constitutionally quorate', $event->reason());
    }

    public function test_is_immutable_readonly(): void
    {
        $ref = new \ReflectionClass(ApprovalRejected::class);
        $this->assertTrue($ref->isReadOnly());
    }

    public function test_has_private_constructor(): void
    {
        $ref = new \ReflectionClass(ApprovalRejected::class);
        $this->assertTrue($ref->getConstructor()->isPrivate());
    }
}
