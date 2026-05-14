<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\ApplyForCommitteeMembership;

use App\Contexts\Membership\Application\Membership\Ports\MembershipApplicationRepositoryPort;
use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Membership\Domain\Committee\Policies\EligibilityPolicy;
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
        private EligibilityPolicy $eligibilityPolicy,
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

        // Compute eligibility only for RESIDENCE applications (others ignore it)
        $isEligible = true;
        if ($command->reason === ApplicationReason::RESIDENCE) {
            $isEligible = $this->eligibilityPolicy->isEligible(
                $command->committeeGeoPath,
                $command->memberGeoPath,
            );
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
