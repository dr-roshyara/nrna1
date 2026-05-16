<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;

final class MemberAssignedToCommittee extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $lineageId,
        public readonly string $memberId,
        public readonly string $committeeId,
        public readonly string $tenantId,
        public readonly string $nominationType,
        public readonly \DateTimeImmutable $assignedAt,
    ) {
        parent::__construct();
    }
}
