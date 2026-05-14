<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;

final class MembershipReapplied extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $memberId,
        public readonly string $committeeId,
        public readonly string $newAssociationId,
        public readonly \DateTimeImmutable $reappliedAt,
    ) {
        parent::__construct();
    }
}
