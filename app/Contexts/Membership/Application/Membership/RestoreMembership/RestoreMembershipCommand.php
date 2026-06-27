<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\RestoreMembership;

final readonly class RestoreMembershipCommand
{
    public function __construct(
        public string $tenantId,
        public string $lineageId,
        public string $actorId,
    ) {
    }
}
