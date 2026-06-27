<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\TerminateMembership;

final readonly class TerminateMembershipCommand
{
    public function __construct(
        public string $tenantId,
        public string $lineageId,
        public string $actorId,
        public string $reason,
    ) {
    }
}
