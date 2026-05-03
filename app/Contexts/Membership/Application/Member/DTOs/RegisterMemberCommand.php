<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Member\DTOs;

use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;

final readonly class RegisterMemberCommand
{
    public function __construct(
        private TenantId $tenantId,
        private string $fullName,
        private string $email,
        private string $phone,
        private MembershipTypeId $membershipTypeId
    ) {}

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getMembershipTypeId(): MembershipTypeId
    {
        return $this->membershipTypeId;
    }
}
