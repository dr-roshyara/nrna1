<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Member\DTOs;

final readonly class MemberView
{
    public function __construct(
        private string $id,
        private string $fullName,
        private string $email,
        private string $phone,
        private string $status,
        private string $membershipTypeId,
        private string $tenantId
    ) {}

    public function getId(): string
    {
        return $this->id;
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getMembershipTypeId(): string
    {
        return $this->membershipTypeId;
    }

    public function getTenantId(): string
    {
        return $this->tenantId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'fullName' => $this->fullName,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'membershipTypeId' => $this->membershipTypeId,
            'tenantId' => $this->tenantId,
        ];
    }
}
