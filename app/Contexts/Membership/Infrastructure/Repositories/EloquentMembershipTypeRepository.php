<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Domain\Repositories\MembershipTypeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\MembershipType;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class EloquentMembershipTypeRepository implements MembershipTypeRepositoryInterface
{
    public function findByIdForTenant(MembershipTypeId $typeId, TenantId $tenantId): ?MembershipType
    {
        $record = \App\Models\MembershipType::where('id', $typeId->value())
            ->where(function ($q) use ($tenantId) {
                $q->whereNull('organisation_id')
                  ->orWhere('organisation_id', $tenantId->value());
            })
            ->first();

        if (!$record) {
            return null;
        }

        return new MembershipType(
            MembershipTypeId::fromString($record->id),
            (bool) $record->is_active,
        );
    }
}
