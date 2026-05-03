<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Application\DTOs;

use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;

final readonly class SubmitApplicationCommand
{
    public function __construct(
        private TenantId $tenantId,
        private MemberId $memberId,
        private MembershipTypeId $membershipTypeId
    ) {}

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function getMemberId(): MemberId
    {
        return $this->memberId;
    }

    public function getMembershipTypeId(): MembershipTypeId
    {
        return $this->membershipTypeId;
    }
}
