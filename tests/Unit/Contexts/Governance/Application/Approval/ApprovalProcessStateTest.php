<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Application\Approval;

use App\Contexts\Governance\Application\Approval\ApprovalProcessState;
use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalStatus;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ApprovalProcessStateTest extends TestCase
{
    private ApprovalId $approvalId;
    private GovernanceDecisionId $decisionId;
    private CommitteeId $committeeA;
    private CommitteeId $committeeB;
    private MemberId $memberId;
    private DateTimeImmutable $now;

    protected function setUp(): void
    {
        $this->approvalId = ApprovalId::from('approval-1');
        $this->decisionId = GovernanceDecisionId::from('decision-1');
        $this->committeeA = CommitteeId::fromString('committee-a');
        $this->committeeB = CommitteeId::fromString('committee-b');
        $this->memberId = MemberId::from('member-1');
        $this->now = new DateTimeImmutable('2026-01-01 10:00:00');
    }

    private function makeState(array $required = null): ApprovalProcessState
    {
        return ApprovalProcessState::start(
            approvalId: $this->approvalId,
            decisionId: $this->decisionId,
            requiredApprovals: $required ?? [$this->committeeA],
            now: $this->now,
        );
    }

    public function test_creates_new_state_with_pending_status(): void
    {
        $state = $this->makeState();

        $this->assertSame(ApprovalStatus::PENDING, $state->status());
        $this->assertCount(1, $state->requiredApprovals());
        $this->assertCount(0, $state->receivedApprovals());
        $this->assertSame(1, $state->version());
    }

    public function test_start_preserves_ids(): void
    {
        $state = $this->makeState();

        $this->assertTrue($state->approvalId()->equals($this->approvalId));
        $this->assertTrue($state->decisionId()->equals($this->decisionId));
    }

    public function test_add_approval_returns_new_instance(): void
    {
        $state = $this->makeState();
        $newState = $state->addApproval($this->committeeA, $this->memberId, $this->now);

        $this->assertNotSame($state, $newState);
        $this->assertCount(0, $state->receivedApprovals(), 'Original state must be unchanged');
        $this->assertCount(1, $newState->receivedApprovals());
    }

    public function test_add_approval_stays_pending_until_all_received(): void
    {
        $state = $this->makeState([$this->committeeA, $this->committeeB]);

        $afterFirst = $state->addApproval($this->committeeA, $this->memberId, $this->now);

        $this->assertSame(ApprovalStatus::PENDING, $afterFirst->status());
    }

    public function test_inv_p05_becomes_approved_when_all_committees_approve(): void
    {
        $state = $this->makeState([$this->committeeA, $this->committeeB]);

        $afterFirst = $state->addApproval($this->committeeA, $this->memberId, $this->now);
        $afterSecond = $afterFirst->addApproval($this->committeeB, $this->memberId, $this->now);

        $this->assertSame(ApprovalStatus::APPROVED, $afterSecond->status());
    }

    public function test_single_required_approval_immediately_approves(): void
    {
        $state = $this->makeState([$this->committeeA]);
        $approved = $state->addApproval($this->committeeA, $this->memberId, $this->now);

        $this->assertSame(ApprovalStatus::APPROVED, $approved->status());
    }

    public function test_inv_p02_duplicate_committee_approval_throws(): void
    {
        $state = $this->makeState([$this->committeeA, $this->committeeB]);
        $state = $state->addApproval($this->committeeA, $this->memberId, $this->now);

        $this->expectException(\DomainException::class);
        $state->addApproval($this->committeeA, $this->memberId, $this->now);
    }

    public function test_reject_returns_new_instance_with_rejected_status(): void
    {
        $state = $this->makeState();
        $rejected = $state->reject($this->memberId, 'Not constitutionally valid', $this->now);

        $this->assertNotSame($state, $rejected);
        $this->assertSame(ApprovalStatus::PENDING, $state->status(), 'Original unchanged');
        $this->assertSame(ApprovalStatus::REJECTED, $rejected->status());
    }

    public function test_inv_p03_reject_reason_must_be_non_empty(): void
    {
        $state = $this->makeState();

        $this->expectException(\DomainException::class);
        $state->reject($this->memberId, '', $this->now);
    }

    public function test_inv_p03_reject_whitespace_reason_throws(): void
    {
        $state = $this->makeState();

        $this->expectException(\DomainException::class);
        $state->reject($this->memberId, '   ', $this->now);
    }

    public function test_expire_returns_new_instance_with_expired_status(): void
    {
        $state = $this->makeState();
        $expired = $state->expire($this->now);

        $this->assertNotSame($state, $expired);
        $this->assertSame(ApprovalStatus::EXPIRED, $expired->status());
    }

    public function test_inv_p01_cannot_approve_after_rejection(): void
    {
        $state = $this->makeState([$this->committeeA]);
        $rejected = $state->reject($this->memberId, 'Rejected reason', $this->now);

        $this->expectException(\DomainException::class);
        $rejected->addApproval($this->committeeA, $this->memberId, $this->now);
    }

    public function test_inv_p01_cannot_approve_after_approval(): void
    {
        $state = $this->makeState([$this->committeeA]);
        $approved = $state->addApproval($this->committeeA, $this->memberId, $this->now);

        $this->expectException(\DomainException::class);
        $approved->addApproval($this->committeeB, $this->memberId, $this->now);
    }

    public function test_inv_p01_cannot_reject_after_approval(): void
    {
        $state = $this->makeState([$this->committeeA]);
        $approved = $state->addApproval($this->committeeA, $this->memberId, $this->now);

        $this->expectException(\DomainException::class);
        $approved->reject($this->memberId, 'Too late', $this->now);
    }

    public function test_inv_p01_cannot_reject_after_expiry(): void
    {
        $state = $this->makeState();
        $expired = $state->expire($this->now);

        $this->expectException(\DomainException::class);
        $expired->reject($this->memberId, 'Already expired', $this->now);
    }

    public function test_inv_p01_cannot_expire_after_approval(): void
    {
        $state = $this->makeState([$this->committeeA]);
        $approved = $state->addApproval($this->committeeA, $this->memberId, $this->now);

        $this->expectException(\DomainException::class);
        $approved->expire($this->now);
    }

    public function test_is_fully_approved_false_when_pending(): void
    {
        $state = $this->makeState([$this->committeeA, $this->committeeB]);
        $this->assertFalse($state->isFullyApproved());
    }

    public function test_is_fully_approved_true_when_all_received(): void
    {
        $state = $this->makeState([$this->committeeA]);
        $approved = $state->addApproval($this->committeeA, $this->memberId, $this->now);
        $this->assertTrue($approved->isFullyApproved());
    }

    public function test_version_increments_on_each_transition(): void
    {
        $state = $this->makeState([$this->committeeA, $this->committeeB]);
        $this->assertSame(1, $state->version());

        $afterFirst = $state->addApproval($this->committeeA, $this->memberId, $this->now);
        $this->assertSame(2, $afterFirst->version());

        $afterSecond = $afterFirst->addApproval($this->committeeB, $this->memberId, $this->now);
        $this->assertSame(3, $afterSecond->version());
    }

    public function test_state_is_readonly_class(): void
    {
        $ref = new \ReflectionClass(ApprovalProcessState::class);
        $this->assertTrue($ref->isReadOnly());
    }
}
