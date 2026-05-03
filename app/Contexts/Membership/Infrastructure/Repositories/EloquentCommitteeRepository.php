<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class EloquentCommitteeRepository implements CommitteeRepositoryInterface
{
    public function findForTenant(CommitteeId $id, TenantId $tenantId): ?Committee
    {
        $model = CommitteeModel::withoutGlobalScopes()
            ->where('id', $id->value())
            ->where('organisation_id', $tenantId->value())
            ->whereNull('deleted_at')
            ->first();

        return $model ? $this->reconstitute($model) : null;
    }

    public function saveForTenant(Committee $committee): void
    {
        CommitteeModel::withoutGlobalScopes()->updateOrCreate(
            [
                'id'              => $committee->getId()->value(),
                'organisation_id' => $committee->getTenantId()->value(),
            ],
            $this->serialize($committee)
        );
        // No event dispatch here — caller (application layer) pulls events after save.
        // No transaction here — caller wraps the full use-case unit in a transaction.
    }

    public function findByGeographyForTenant(GeoReference $geoRef, TenantId $tenantId): array
    {
        // Prefix path matching for Phase 1.
        // Phase 2: replace with geo_closure JOIN for better performance.
        return CommitteeModel::withoutGlobalScopes()
            ->where('organisation_id', $tenantId->value())
            ->whereNull('deleted_at')
            ->where(function ($q) use ($geoRef) {
                $q->where('operational_geo_reference', $geoRef->value())
                  ->orWhere('operational_geo_reference', 'LIKE', $geoRef->pathPrefix().'%');
            })
            ->get()
            ->map(fn (CommitteeModel $model) => $this->reconstitute($model))
            ->all();
    }

    public function findByTypeForTenant(CommitteeType $type, TenantId $tenantId): array
    {
        return CommitteeModel::withoutGlobalScopes()
            ->where('organisation_id', $tenantId->value())
            ->where('type', $type->value())
            ->whereNull('deleted_at')
            ->get()
            ->map(fn (CommitteeModel $model) => $this->reconstitute($model))
            ->all();
    }

    public function existsForTenant(CommitteeId $id, TenantId $tenantId): bool
    {
        return CommitteeModel::withoutGlobalScopes()
            ->where('id', $id->value())
            ->where('organisation_id', $tenantId->value())
            ->whereNull('deleted_at')
            ->exists();
    }

    public function deleteForTenant(CommitteeId $id, TenantId $tenantId): void
    {
        CommitteeModel::withoutGlobalScopes()
            ->where('id', $id->value())
            ->where('organisation_id', $tenantId->value())
            ->delete();
    }

    public function findAllForTenant(TenantId $tenantId): array
    {
        return CommitteeModel::withoutGlobalScopes()
            ->where('organisation_id', $tenantId->value())
            ->whereNull('deleted_at')
            ->get()
            ->map(fn (CommitteeModel $model) => $this->reconstitute($model))
            ->all();
    }

    private function reconstitute(CommitteeModel $model): Committee
    {
        $geoRef = $model->operational_geo_reference
            ? GeoReference::fromString($model->operational_geo_reference)
            : null;

        $status = is_string($model->status)
            ? \App\Contexts\Membership\Domain\ValueObjects\CommitteeStatus::fromString($model->status)
            : $model->status;

        return Committee::reconstruct(
            CommitteeId::fromString($model->id),
            TenantId::fromString($model->organisation_id),
            CommitteeType::fromString($model->type),
            \App\Contexts\Membership\Domain\ValueObjects\CommitteeName::fromString($model->name),
            $model->code,
            $geoRef,
            $status
        );
    }

    private function serialize(Committee $committee): array
    {
        // Map committee type to level (1=central, 2=province, 3=district, 4=ward, etc.)
        $levelMap = [
            'central' => 1,
            'province' => 2,
            'district' => 3,
            'ward' => 4,
            'youth' => 5,
            'women' => 5,
            'student' => 5,
        ];

        $type = $committee->type()->value();
        $level = $levelMap[$type] ?? 5;

        return [
            'id' => $committee->getId()->value(),
            'organisation_id' => $committee->getTenantId()->value(),
            'name' => $committee->getName()->value(),
            'code' => $committee->code(),
            'type' => $type,
            'level' => $level,
            'operational_geo_reference' => $committee->getOperationalGeoReference()?->value(),
            'status' => $committee->getStatus()->value(),
        ];
    }
}
