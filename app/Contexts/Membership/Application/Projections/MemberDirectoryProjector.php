<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Projections;

use App\Contexts\Membership\Application\DTO\MemberDirectory;
use App\Contexts\Membership\Domain\Events\MemberRegistered;

final class MemberDirectoryProjector
{
    public function fromMemberRegistered(MemberRegistered $event): MemberDirectory
    {
        return new MemberDirectory(
            memberId: $event->memberId->value(),
            tenantId: $event->tenantId->value(),
            displayName: $event->displayName,
            email: strtolower($event->email),
            status: 'ACTIVE',
            membershipTypeId: $event->membershipTypeId->value(),
            membershipTypeName: $event->membershipTypeName,
            organisationUserId: $event->organisationUserId,
        );
    }
}
