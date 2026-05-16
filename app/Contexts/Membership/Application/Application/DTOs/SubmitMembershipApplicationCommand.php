<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Application\DTOs;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;

final readonly class SubmitMembershipApplicationCommand
{
    public function __construct(
        private TenantId $tenantId,
        private string $userId,
        private MembershipTypeId $membershipTypeId,
        private ?array $applicationData = null
    ) {}

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getMembershipTypeId(): MembershipTypeId
    {
        return $this->membershipTypeId;
    }

    public function getApplicationData(): ?array
    {
        return $this->applicationData;
    }

    public static function fromRequest(string $organisationId, string $userId, string $membershipTypeId, ?array $applicationData): self
    {
        return new self(
            TenantId::fromOrganisationId($organisationId),
            $userId,
            MembershipTypeId::fromString($membershipTypeId),
            $applicationData
        );
    }
}
