<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Application\Approval;

use App\Contexts\Governance\Application\Approval\ApprovalProcessRepository;
use App\Contexts\Governance\Application\Approval\ApprovalProcessState;
use App\Contexts\Governance\Application\Approval\Commands\AbortDecision;
use App\Contexts\Governance\Application\Approval\Commands\FinalizeDecision;
use App\Contexts\Governance\Application\Approval\Commands\StartApprovalProcess;
use App\Contexts\Governance\Application\Approval\GovernanceApprovalProcessManager;
use App\Contexts\Governance\Application\Contracts\CommandBusInterface;
use App\Contexts\Governance\Domain\Approval\Events\ApprovalGranted;
use App\Contexts\Governance\Domain\Approval\Events\ApprovalRejected;
use App\Contexts\Governance\Domain\Approval\Policies\ApprovalRequirementPolicy;
use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Governance\Domain\Events\GovernanceDecisionRecorded;
use App\Contexts\Governance\Domain\ValueObjects\AuthorityChain;
use App\Contexts\Governance\Domain\ValueObjects\AuthorityPath;
use App\Contexts\Governance\Domain\ValueObjects\ConstitutionalBasis;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceRole;
use App\Contexts\Governance\Domain\ValueObjects\Legitimacy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class GovernanceApprovalProcessManagerTest extends TestCase
{
    private ApprovalProcessRepository $repository;
    private CommandBusInterface $commandBus;
    private GovernanceApprovalProcessManager $manager;
    private DateTimeImmutable $now;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ApprovalProcessRepository::class);
        $this->commandBus = $this->createMock(CommandBusInterface::class);
        $this->manager = new GovernanceApprovalProcessManager(
            repository: $this->repository,
            commandBus: $this->commandBus,
            policy: new ApprovalRequirementPolicy(),
        );
        $this->now = new DateTimeImmutable('2026-01-01 10:00:00');
    }

    private function makeDecisionRecordedEvent(string $capabilityType): GovernanceDecisionRecorded
    {
        $chain = AuthorityChain::capture(
            actorId: MemberId::from('icc-president'),
            role: GovernanceRole::ICC_PRESIDENT,
            delegationPath: AuthorityPath::fromArray(['ICC']),
            capturedAt: $this->now,
        );

        return GovernanceDecisionRecorded::from(
            decisionId: GovernanceDecisionId::from('decision-1'),
            committeeId: CommitteeId::fromString('committee-1'),
            capabilityType: $capabilityType,
            legitimacy: Legitimacy::LEGITIMATE,
            authorityChain: $chain,
            constitutionalBasis: ConstitutionalBasis::from('Article 5'),
            effectiveFrom: $this->now,
            occurredAt: $this->now,
        );
    }

    public function test_starts_process_when_decision_requires_approval(): void
    {
        $event = $this->makeDecisionRecordedEvent('COMMITTEE_FORMATION');

        $this->repository
            ->expects($this->once())
            ->method('findByDecisionId')
            ->willReturn(null);

        $this->repository
            ->expects($this->once())
            ->method('save');

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(StartApprovalProcess::class));

        $this->manager->handle($event, $this->now);
    }

    public function test_ignores_decision_that_does_not_require_approval(): void
    {
        $event = $this->makeDecisionRecordedEvent('ROUTINE_DECISION');

        $this->repository->expects($this->never())->method('save');
        $this->commandBus->expects($this->never())->method('dispatch');

        $this->manager->handle($event, $this->now);
    }

    public function test_inv_p04_duplicate_decision_event_is_idempotent(): void
    {
        $event = $this->makeDecisionRecordedEvent('COMMITTEE_FORMATION');

        $existingState = ApprovalProcessState::start(
            approvalId: ApprovalId::from('approval-1'),
            decisionId: GovernanceDecisionId::from('decision-1'),
            requiredApprovals: [CommitteeId::fromString('committee-parent')],
            now: $this->now,
        );

        $this->repository
            ->expects($this->once())
            ->method('findByDecisionId')
            ->willReturn($existingState);

        $this->repository->expects($this->never())->method('save');
        $this->commandBus->expects($this->never())->method('dispatch');

        $this->manager->handle($event, $this->now);
    }

    public function test_handles_approval_granted_and_saves_updated_state(): void
    {
        $committeeA = CommitteeId::fromString('committee-a');
        $committeeB = CommitteeId::fromString('committee-b');
        $approvalId = ApprovalId::from('approval-1');

        $state = ApprovalProcessState::start(
            approvalId: $approvalId,
            decisionId: GovernanceDecisionId::from('decision-1'),
            requiredApprovals: [$committeeA, $committeeB],
            now: $this->now,
        );

        $event = ApprovalGranted::occur(
            approvalId: $approvalId,
            approvingCommitteeId: $committeeA,
            grantedBy: MemberId::from('member-1'),
            grantedAt: $this->now,
            notes: '',
        );

        $this->repository
            ->method('findById')
            ->willReturn($state);

        $this->repository->expects($this->once())->method('save');
        $this->commandBus->expects($this->never())->method('dispatch');

        $this->manager->handle($event, $this->now);
    }

    public function test_dispatches_finalize_when_all_approvals_received(): void
    {
        $committeeA = CommitteeId::fromString('committee-a');
        $approvalId = ApprovalId::from('approval-1');

        $state = ApprovalProcessState::start(
            approvalId: $approvalId,
            decisionId: GovernanceDecisionId::from('decision-1'),
            requiredApprovals: [$committeeA],
            now: $this->now,
        );

        $event = ApprovalGranted::occur(
            approvalId: $approvalId,
            approvingCommitteeId: $committeeA,
            grantedBy: MemberId::from('member-1'),
            grantedAt: $this->now,
            notes: '',
        );

        $this->repository->method('findById')->willReturn($state);
        $this->repository->expects($this->once())->method('save');

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(FinalizeDecision::class));

        $this->manager->handle($event, $this->now);
    }

    public function test_handles_approval_rejected_and_dispatches_abort(): void
    {
        $approvalId = ApprovalId::from('approval-1');

        $state = ApprovalProcessState::start(
            approvalId: $approvalId,
            decisionId: GovernanceDecisionId::from('decision-1'),
            requiredApprovals: [CommitteeId::fromString('committee-a')],
            now: $this->now,
        );

        $event = ApprovalRejected::occur(
            approvalId: $approvalId,
            rejectingCommitteeId: CommitteeId::fromString('committee-a'),
            rejectedBy: MemberId::from('member-1'),
            rejectedAt: $this->now,
            reason: 'Quorum not met',
        );

        $this->repository->method('findById')->willReturn($state);
        $this->repository->expects($this->once())->method('save');

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(AbortDecision::class));

        $this->manager->handle($event, $this->now);
    }

    public function test_approval_granted_on_terminal_state_is_ignored(): void
    {
        $approvalId = ApprovalId::from('approval-1');
        $committeeA = CommitteeId::fromString('committee-a');

        // Already approved
        $state = ApprovalProcessState::start(
            approvalId: $approvalId,
            decisionId: GovernanceDecisionId::from('decision-1'),
            requiredApprovals: [$committeeA],
            now: $this->now,
        );
        $approvedState = $state->addApproval($committeeA, MemberId::from('m1'), $this->now);

        $event = ApprovalGranted::occur(
            approvalId: $approvalId,
            approvingCommitteeId: CommitteeId::fromString('committee-b'),
            grantedBy: MemberId::from('member-1'),
            grantedAt: $this->now,
            notes: '',
        );

        $this->repository->method('findById')->willReturn($approvedState);
        $this->repository->expects($this->never())->method('save');
        $this->commandBus->expects($this->never())->method('dispatch');

        $this->manager->handle($event, $this->now);
    }

    public function test_approval_granted_when_state_not_found_is_ignored(): void
    {
        $event = ApprovalGranted::occur(
            approvalId: ApprovalId::from('unknown-approval'),
            approvingCommitteeId: CommitteeId::fromString('committee-a'),
            grantedBy: MemberId::from('member-1'),
            grantedAt: $this->now,
            notes: '',
        );

        $this->repository->method('findById')->willReturn(null);
        $this->repository->expects($this->never())->method('save');
        $this->commandBus->expects($this->never())->method('dispatch');

        $this->manager->handle($event, $this->now);
    }

    public function test_process_manager_is_stateless(): void
    {
        $ref = new \ReflectionClass(GovernanceApprovalProcessManager::class);
        $mutableProps = array_filter(
            $ref->getProperties(),
            fn($p) => !$p->isReadOnly() && $p->getName() !== 'events'
        );
        $this->assertCount(0, $mutableProps, 'Process Manager must have no mutable properties');
    }
}
