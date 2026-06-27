<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Application\Events;

use App\Contexts\Membership\Domain\Application\ApplicationId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

final readonly class ApplicationSubmitted
{
    public function __construct(
        private ApplicationId $applicationId,
        private TenantId $tenantId,
        private string $userId,
        private MembershipTypeId $membershipTypeId,
        private DateTimeImmutable $occurredAt
    ) {}

    public function getApplicationId(): ApplicationId
    {
        return $this->applicationId;
    }

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getMembershipTypeId(): MembershipTypeId
    {
        return $this->membershipTypeId;
    }

    public function getOccurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
