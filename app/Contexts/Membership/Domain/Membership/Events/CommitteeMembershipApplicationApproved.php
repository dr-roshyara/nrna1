<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;

final class CommitteeMembershipApplicationApproved extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $applicationId,
        public readonly string $reviewedBy,
        public readonly \DateTimeImmutable $reviewedAt,
    ) {
        parent::__construct();
    }
}
