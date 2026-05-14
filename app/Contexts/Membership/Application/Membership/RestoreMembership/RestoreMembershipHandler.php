<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\RestoreMembership;

use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Membership\Application\Committee\Ports\EventBusPort;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DomainException;

final readonly class RestoreMembershipHandler
{
    public function __construct(
        private MembershipLineageRepositoryPort $lineageRepository,
        private EventBusPort $eventBus,
    ) {
    }

    public function handle(RestoreMembershipCommand $command): void
    {
        $lineageId = LineageId::fromString($command->lineageId);
        $tenantId = TenantId::fromString($command->tenantId);

        $lineage = $this->lineageRepository->findByLineageIdForTenant($lineageId, $tenantId);
        if ($lineage === null) {
            throw new DomainException('Lineage not found');
        }

        // Domain enforces transition rules (SUSPENDED → ACTIVE only)
        $lineage->restore(
            $command->actorId,
            new \DateTimeImmutable(),
        );

        $this->lineageRepository->saveForTenant($lineage, $tenantId);

        // @todo: Publish domain events when event system is extended
    }
}
