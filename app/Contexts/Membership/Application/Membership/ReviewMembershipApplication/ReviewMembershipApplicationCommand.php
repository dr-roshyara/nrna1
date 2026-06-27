<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\ReviewMembershipApplication;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final readonly class ReviewMembershipApplicationCommand
{
    public function __construct(
        public TenantId $tenantId,
        public string $applicationId,
        public string $action, // 'APPROVE' or 'REJECT'
        public string $reviewedBy,
    ) {
    }
}
