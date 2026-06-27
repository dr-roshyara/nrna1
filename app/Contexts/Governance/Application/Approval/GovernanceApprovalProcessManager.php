<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Approval;

use App\Contexts\Governance\Application\Approval\Commands\AbortDecision;
use App\Contexts\Governance\Application\Approval\Commands\FinalizeDecision;
use App\Contexts\Governance\Application\Approval\Commands\StartApprovalProcess;
use App\Contexts\Governance\Application\Contracts\CommandBusInterface;
use App\Contexts\Governance\Domain\Approval\Events\ApprovalGranted;
use App\Contexts\Governance\Domain\Approval\Events\ApprovalRejected;
use App\Contexts\Governance\Domain\Approval\Policies\ApprovalRequirementPolicy;
use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use App\Contexts\Governance\Domain\Approval\ValueObjects\IdempotencyKey;
use App\Contexts\Governance\Domain\Events\GovernanceDecisionRecorded;
use DateTimeImmutable;

final class GovernanceApprovalProcessManager
{
    public function __construct(
        private readonly ApprovalProcessRepository $repository,
        private readonly CommandBusInterface $commandBus,
        private readonly ApprovalRequirementPolicy $policy,
    ) {}

    public function handle(object $event, DateTimeImmutable $now): void
    {
        match (true) {
            $event instanceof GovernanceDecisionRecorded => $this->onDecisionRecorded($event, $now),
            $event instanceof ApprovalGranted            => $this->onApprovalGranted($event, $now),
            $event instanceof ApprovalRejected           => $this->onApprovalRejected($event, $now),
            default                                      => null,
        };
    }

    private function onDecisionRecorded(GovernanceDecisionRecorded $event, DateTimeImmutable $now): void
    {
        if (!$this->policy->requiresApproval($event->capabilityType())) {
            return;
        }

        // Idempotency: skip if process already started for this decision
        if ($this->repository->findByDecisionId($event->decisionId()) !== null) {
            return;
        }

        $approvalId = ApprovalId::generate();

        // For now, required approvals list is empty (resolver would populate this in real usage)
        // The Process Manager coordinates; the resolver (app service) provides CommitteeIds
        $requiredApprovals = [];

        $idempotencyKey = IdempotencyKey::generate($event->committeeId(), $now);

        $state = ApprovalProcessState::start(
            approvalId: $approvalId,
            decisionId: $event->decisionId(),
            requiredApprovals: $requiredApprovals,
            now: $now,
        );

        $this->repository->save($state);

        $this->commandBus->dispatch(new StartApprovalProcess(
            approvalId: $approvalId,
            decisionId: $event->decisionId(),
            requiredApprovals: $requiredApprovals,
            idempotencyKey: $idempotencyKey,
        ));
    }

    private function onApprovalGranted(ApprovalGranted $event, DateTimeImmutable $now): void
    {
        $state = $this->repository->findById($event->approvalId());

        if ($state === null || $state->status()->isTerminal()) {
            return;
        }

        $newState = $state->addApproval($event->approvingCommitteeId(), $event->grantedBy(), $event->grantedAt());
        $this->repository->save($newState);

        if ($newState->isFullyApproved()) {
            $this->commandBus->dispatch(new FinalizeDecision(
                decisionId: $newState->decisionId(),
                approvalId: $newState->approvalId(),
            ));
        }
    }

    private function onApprovalRejected(ApprovalRejected $event, DateTimeImmutable $now): void
    {
        $state = $this->repository->findById($event->approvalId());

        if ($state === null || $state->status()->isTerminal()) {
            return;
        }

        $newState = $state->reject($event->rejectedBy(), $event->reason(), $event->rejectedAt());
        $this->repository->save($newState);

        $this->commandBus->dispatch(new AbortDecision(
            decisionId: $newState->decisionId(),
            approvalId: $newState->approvalId(),
            reason: $event->reason(),
        ));
    }

    public function expireOldProcesses(DateTimeImmutable $now, DateTimeImmutable $cutoff): void
    {
        $pending = $this->repository->findExpiredPending($cutoff);

        foreach ($pending as $state) {
            $expired = $state->expire($now);
            $this->repository->save($expired);

            $this->commandBus->dispatch(new AbortDecision(
                decisionId: $expired->decisionId(),
                approvalId: $expired->approvalId(),
                reason: 'Approval process expired',
            ));
        }
    }
}
