<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\DTOs;

/**
 * Member Rejection DTO
 *
 * Data Transfer Object for member rejection operations.
 * Carries data from infrastructure layer (controller) to application service.
 *
 * Immutable by design (readonly properties).
 */
final class MemberRejectionDto
{
    public function __construct(
        public readonly string $memberId,
        public readonly string $tenantId,
        public readonly string $adminUserId,
        public readonly string $reason
    ) {
    }
}
