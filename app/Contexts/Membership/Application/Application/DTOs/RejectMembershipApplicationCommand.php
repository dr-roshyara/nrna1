<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Application\DTOs;

use App\Contexts\Membership\Domain\Application\ApplicationId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final readonly class RejectMembershipApplicationCommand
{
    public function __construct(
        private ApplicationId $applicationId,
        private TenantId $tenantId,
        private string $rejectionReason,
        private string $reviewedByUserId
    ) {}

    public function getApplicationId(): ApplicationId
    {
        return $this->applicationId;
    }

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function getRejectionReason(): string
    {
        return $this->rejectionReason;
    }

    public function getReviewedByUserId(): string
    {
        return $this->reviewedByUserId;
    }
}
