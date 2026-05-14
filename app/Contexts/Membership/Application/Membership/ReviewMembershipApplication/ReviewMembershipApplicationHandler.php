<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\ReviewMembershipApplication;

use App\Contexts\Membership\Application\Membership\Ports\CommitteeAssociationRepositoryPort;
use App\Contexts\Membership\Application\Membership\Ports\MembershipApplicationRepositoryPort;
use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Membership\Application\Committee\Ports\EventBusPort;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipApplicationId;

final readonly class ReviewMembershipApplicationHandler
{
    public function __construct(
        private MembershipApplicationRepositoryPort $repository,
        private CommitteeAssociationRepositoryPort $associationRepository,
        private MembershipLineageRepositoryPort $lineageRepository,
        private EventBusPort $eventBus,
    ) {
    }

    public function handle(ReviewMembershipApplicationCommand $command): ?CommitteeAssociation
    {
        $applicationId = MembershipApplicationId::fromString($command->applicationId);
        $reviewedBy = MemberId::fromString($command->reviewedBy);

        // Load application
        $application = $this->repository->getOrFailForTenant($applicationId, $command->tenantId);

        // Execute review decision (domain enforces state machine)
        $association = null;
        if ($command->action === 'APPROVE') {
            $association = $application->approve($reviewedBy);
        } elseif ($command->action === 'REJECT') {
            $application->reject($reviewedBy);
        }

        // Persist updated application
        $this->repository->saveForTenant($application);

        // If approved, create and persist lineage
        if ($association !== null) {
            $lineage = MembershipLineage::establish(
                lineageId: LineageId::generate(),
                memberId: $application->memberId(),
                committeeId: $application->committeeId(),
                tenantId: $command->tenantId,
                reason: $application->reason(),
                at: new \DateTimeImmutable(),
            );

            $this->lineageRepository->saveForTenant($lineage, $command->tenantId);

            // Also persist association for backward compatibility (Elections context queries)
            $this->associationRepository->saveForTenant($association, $command->tenantId);
        }

        // Publish events
        foreach ($application->releaseEvents() as $event) {
            $this->eventBus->publish($event);
        }

        return $association;
    }
}
