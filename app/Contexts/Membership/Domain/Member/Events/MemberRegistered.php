<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Member\Events;

use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Member\ValueObjects\PersonalInfo;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use DateTimeImmutable;

final readonly class MemberRegistered
{
    public function __construct(
        private MemberId $memberId,
        private TenantId $tenantId,
        private PersonalInfo $personalInfo,
        private MembershipTypeId $membershipTypeId,
        private DateTimeImmutable $occurredAt
    ) {}

    public function getMemberId(): MemberId
    {
        return $this->memberId;
    }

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function getPersonalInfo(): PersonalInfo
    {
        return $this->personalInfo;
    }

    public function getMembershipTypeId(): MembershipTypeId
    {
        return $this->membershipTypeId;
    }

    public function getOccurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
