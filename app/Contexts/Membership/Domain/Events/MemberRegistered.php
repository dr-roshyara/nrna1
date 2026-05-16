<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Events;

use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use InvalidArgumentException;

final readonly class MemberRegistered
{
    public function __construct(
        public MemberId $memberId,
        public TenantId $tenantId,
        public string $displayName,
        public string $email,
        public MembershipTypeId $membershipTypeId,
        public string $membershipTypeName,
        public string $organisationUserId,
    ) {
        if (empty(trim($this->displayName))) {
            throw new InvalidArgumentException('displayName cannot be empty');
        }
        if (empty(trim($this->email))) {
            throw new InvalidArgumentException('email cannot be empty');
        }
        if (empty(trim($this->membershipTypeName))) {
            throw new InvalidArgumentException('membershipTypeName cannot be empty');
        }
        if (empty(trim($this->organisationUserId))) {
            throw new InvalidArgumentException('organisationUserId cannot be empty');
        }
    }
}
