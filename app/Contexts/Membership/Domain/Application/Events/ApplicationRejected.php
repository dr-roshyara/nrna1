<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Application\Events;

use App\Contexts\Membership\Domain\Application\ApplicationId;
use DateTimeImmutable;

final readonly class ApplicationRejected
{
    public function __construct(
        private ApplicationId $applicationId,
        private string $reason,
        private DateTimeImmutable $occurredAt
    ) {}

    public function getApplicationId(): ApplicationId
    {
        return $this->applicationId;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getOccurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
