<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\SuspendMembership;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final readonly class SuspendMembershipCommand
{
    public function __construct(
        public string $tenantId,
        public string $lineageId,
        public string $actorId,
        public string $reason,
    ) {
    }
}
