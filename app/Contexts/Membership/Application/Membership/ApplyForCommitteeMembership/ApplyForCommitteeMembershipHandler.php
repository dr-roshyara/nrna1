<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\ApplyForCommitteeMembership;

use App\Contexts\Membership\Application\Membership\Ports\MembershipApplicationRepositoryPort;
use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Membership\Application\Membership\Query\EligibleCommitteeQueryService;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\MembershipApplication;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipApplicationId;
use App\Contexts\Membership\Application\Committee\Ports\EventBusPort;
use DomainException;

final readonly class ApplyForCommitteeMembershipHandler
{
    public function __construct(
        private MembershipApplicationRepositoryPort $repository,
        private MembershipLineageRepositoryPort $lineageRepository,
        private EventBusPort $eventBus,
        private EligibleCommitteeQueryService $eligibleCommitteeService,
    ) {
    }

    public function handle(ApplyForCommitteeMembershipCommand $command): MembershipApplicationId
    {
        $memberId = MemberId::fromString($command->memberId);
        $committeeId = CommitteeId::fromString($command->committeeId);

        // Check for duplicate active applications
        if ($this->repository->existsActiveForTenant($memberId, $committeeId, $command->tenantId)) {
            throw new DomainException('Only one active application per member and committee');
        }

        // Check for existing lineage (governance constraint)
        $existingLineage = $this->lineageRepository->findLineageByMemberAndCommitteeForTenant(
            $memberId,
            $committeeId,
            $command->tenantId,
        );

        if ($existingLineage !== null && !$existingLineage->canBeReapplied()) {
            throw new DomainException('Member already has active or suspended membership with this committee');
        }

        // PHASE C: Use eligibility service as single source of truth
        // (replaces inline eligibility policy for consistency across read + write paths)
        $eligible = $this->eligibleCommitteeService->eligibleForMember(
            $memberId,
            $command->tenantId,
            $command->memberGeoPath,
        );

        $isEligible = false;
        foreach ($eligible as $eligibleView) {
            if ($eligibleView->committeeId === (string) $committeeId) {
                $isEligible = true;
                break;
            }
        }

        // Submit application (domain logic validates based on reason)
        $application = MembershipApplication::submit(
            id: MembershipApplicationId::generate(),
            tenantId: $command->tenantId,
            memberId: $memberId,
            committeeId: $committeeId,
            reason: $command->reason,
            exceptionJustification: $command->exceptionJustification,
            isEligible: $isEligible,
        );

        // Persist and publish events
        $this->repository->saveForTenant($application);

        foreach ($application->releaseEvents() as $event) {
            $this->eventBus->publish($event);
        }

        return $application->id();
    }
}
