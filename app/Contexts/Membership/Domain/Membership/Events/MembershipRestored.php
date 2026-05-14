<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;

final class MembershipRestored extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $lineageId,
        public readonly string $memberId,
        public readonly string $committeeId,
        public readonly string $actorId,
        public readonly \DateTimeImmutable $restoredAt,
    ) {
        parent::__construct();
    }
}
