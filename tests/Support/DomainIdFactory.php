<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Ramsey\Uuid\Uuid;

final class DomainIdFactory
{
    /**
     * Generate valid MemberId (UUID)
     */
    public static function member(): MemberId
    {
        return MemberId::fromString(Uuid::uuid4()->toString());
    }

    /**
     * Generate valid FeeId (UUID)
     */
    public static function fee(): FeeId
    {
        return FeeId::fromString(Uuid::uuid4()->toString());
    }

    /**
     * Generate valid TenantId (UUID)
     */
    public static function tenant(): TenantId
    {
        return TenantId::fromString(Uuid::uuid4()->toString());
    }

    /**
     * Generate valid MembershipTypeId (UUID)
     */
    public static function membershipType(): MembershipTypeId
    {
        return MembershipTypeId::fromString(Uuid::uuid4()->toString());
    }

    /**
     * Generate valid TenantUserId (UUID)
     */
    public static function tenantUserId(): TenantUserId
    {
        return TenantUserId::fromString(Uuid::uuid4()->toString());
    }
}
