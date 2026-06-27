<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Commands;

final readonly class MemberImportCommand
{
    public function __construct(
        public string $email,
        public string $firstName,
        public string $lastName,
        public ?string $membershipTypeId = null,
        public ?string $status = null,
        public ?string $feesStatus = null,
        public ?string $joinedAt = null,
        public ?string $expiresAt = null,
        public ?string $geoUnitId = null,
    ) {}
}
