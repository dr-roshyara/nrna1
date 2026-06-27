<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;

final class CommitteeMembershipApplicationSubmitted extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $applicationId,
        public readonly string $memberId,
        public readonly string $committeeId,
        public readonly string $reason,
        public readonly \DateTimeImmutable $submittedAt,
    ) {
        parent::__construct();
    }
}
