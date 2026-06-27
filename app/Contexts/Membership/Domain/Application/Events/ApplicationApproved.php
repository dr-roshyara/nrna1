<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Application\Events;

use App\Contexts\Membership\Domain\Application\ApplicationId;
use DateTimeImmutable;

final readonly class ApplicationApproved
{
    public function __construct(
        private ApplicationId $applicationId,
        private DateTimeImmutable $occurredAt
    ) {}

    public function getApplicationId(): ApplicationId
    {
        return $this->applicationId;
    }

    public function getOccurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
