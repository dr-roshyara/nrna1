<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Commands;

use App\Contexts\Membership\Domain\ValueObjects\Email;

/**
 * Register Member Command
 *
 * Immutable command object representing the intent to register a new member.
 *
 * Business rules validated here (command level):
 * - Basic data types and required fields
 *
 * Business rules validated in handler:
 * - Digital identity exists
 * - Member ID uniqueness
 * - Geography validity (if provided)
 */
readonly class RegisterMemberCommand
{
    public function __construct(
        public string $tenantUserId,
        public string $tenantId,
        public string $fullName,
        public Email $email,
        public ?string $phone = null,
        public ?string $memberId = null,
        public ?string $geoReference = null
    ) {
        // Validation: tenant_user_id required (digital identity first)
        if (empty(trim($tenantUserId))) {
            throw new \InvalidArgumentException(
                'tenant_user_id is required for member registration'
            );
        }

        // Validation: tenant_id required
        if (empty(trim($tenantId))) {
            throw new \InvalidArgumentException(
                'tenant_id is required for member registration'
            );
        }

        // Validation: full_name required
        if (empty(trim($fullName))) {
            throw new \InvalidArgumentException(
                'full_name is required for member registration'
            );
        }
    }
}
