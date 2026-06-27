<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\DTO;

final readonly class MemberDirectory
{
    public function __construct(
        public string $memberId,
        public string $tenantId,
        public string $displayName,
        public string $email,
        public string $status,
        public string $membershipTypeId,
        public string $membershipTypeName,
        public string $organisationUserId,
    ) {}
}
