<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Committee\Events;

use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Domain\Committee\Enums\CommitteeRole;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final readonly class MemberAssignedToCommittee
{
    public function __construct(
        public CommitteeId $committeeId,
        public MemberId $memberId,
        public TenantId $tenantId,
        private CommitteeRole $roleValue,
        public \DateTimeImmutable $occurredAt = new \DateTimeImmutable()
    ) {}

    public function role(): CommitteeRole
    {
        return $this->roleValue;
    }
}
