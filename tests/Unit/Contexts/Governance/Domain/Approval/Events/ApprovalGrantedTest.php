<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Approval\Events;

use App\Contexts\Governance\Domain\Approval\Events\ApprovalGranted;
use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ApprovalGrantedTest extends TestCase
{
    private function makeEvent(): ApprovalGranted
    {
        return ApprovalGranted::occur(
            approvalId: ApprovalId::from('approval-1'),
            approvingCommitteeId: CommitteeId::fromString('committee-a'),
            grantedBy: MemberId::from('member-1'),
            grantedAt: new DateTimeImmutable('2026-01-02 09:00:00'),
            notes: 'Approved by committee',
        );
    }

    public function test_carries_all_required_fields(): void
    {
        $event = $this->makeEvent();

        $this->assertSame('approval-1', $event->approvalId()->value());
        $this->assertSame('committee-a', $event->approvingCommitteeId()->value());
        $this->assertSame('member-1', $event->grantedBy()->value());
        $this->assertSame('2026-01-02 09:00:00', $event->grantedAt()->format('Y-m-d H:i:s'));
        $this->assertSame('Approved by committee', $event->notes());
    }

    public function test_notes_can_be_empty_string(): void
    {
        $event = ApprovalGranted::occur(
            approvalId: ApprovalId::from('approval-1'),
            approvingCommitteeId: CommitteeId::fromString('committee-a'),
            grantedBy: MemberId::from('member-1'),
            grantedAt: new DateTimeImmutable('2026-01-02'),
            notes: '',
        );

        $this->assertSame('', $event->notes());
    }

    public function test_is_immutable_readonly(): void
    {
        $ref = new \ReflectionClass(ApprovalGranted::class);
        $this->assertTrue($ref->isReadOnly());
    }

    public function test_has_private_constructor(): void
    {
        $ref = new \ReflectionClass(ApprovalGranted::class);
        $this->assertTrue($ref->getConstructor()->isPrivate());
    }
}
