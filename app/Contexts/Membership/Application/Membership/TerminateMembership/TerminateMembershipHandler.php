<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\TerminateMembership;

use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Membership\Application\Committee\Ports\EventBusPort;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DomainException;

final readonly class TerminateMembershipHandler
{
    public function __construct(
        private MembershipLineageRepositoryPort $lineageRepository,
        private EventBusPort $eventBus,
    ) {
    }

    public function handle(TerminateMembershipCommand $command): void
    {
        $lineageId = LineageId::fromString($command->lineageId);
        $tenantId = TenantId::fromString($command->tenantId);

        $lineage = $this->lineageRepository->findByLineageIdForTenant($lineageId, $tenantId);
        if ($lineage === null) {
            throw new DomainException('Lineage not found');
        }

        // Domain enforces transition rules (not already TERMINATED)
        $lineage->terminate(
            $command->actorId,
            $command->reason,
            new \DateTimeImmutable(),
        );

        $this->lineageRepository->saveForTenant($lineage, $tenantId);

        // Publish domain events
        $events = $lineage->releaseEvents();
        if (!empty($events)) {
            $this->eventBus->dispatchAll($events);
        }
    }
}
