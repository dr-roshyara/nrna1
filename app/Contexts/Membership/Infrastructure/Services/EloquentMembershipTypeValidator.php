<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Services;

use App\Contexts\Membership\Domain\Services\MembershipTypeValidationInterface;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Models\MembershipType;

final class EloquentMembershipTypeValidator implements MembershipTypeValidationInterface
{
    public function ensureValid(MembershipTypeId $typeId, TenantId $tenantId): void
    {
        $type = MembershipType::where('id', $typeId->value())
            ->where(function ($q) use ($tenantId) {
                $q->whereNull('organisation_id')
                  ->orWhere('organisation_id', $tenantId->value());
            })
            ->first();

        if (!$type) {
            throw new \InvalidArgumentException("Membership type '{$typeId->value()}' not found");
        }

        if (!$type->is_active) {
            throw new \InvalidArgumentException("Membership type '{$typeId->value()}' is not active");
        }
    }
}
