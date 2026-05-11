<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Domain\Committee\CommitteeAssignment;
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureRegistry;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeAssignmentId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeStatus;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\NominationType;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;
use App\Contexts\Membership\Infrastructure\Models\CommitteeAssignmentModel;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

final class EloquentCommitteeRepository implements CommitteeRepositoryInterface
{
    public function findForTenant(CommitteeId $id, TenantId $tenantId): ?Committee
    {
        $model = CommitteeModel::withoutGlobalScopes()
            ->where('id', $id->value())
            ->where('organisation_id', $tenantId->value())
            ->whereNull('deleted_at')
            ->first();

        if (!$model) {
            return null;
        }

        // Load assignments explicitly with tenant scoping
        $assignmentModels = CommitteeAssignmentModel::query()
            ->where('committee_id', $model->id)
            ->where('organisation_id', $tenantId->value())
            ->get();

        // Map all assignments (both active and inactive)
        $assignments = $assignmentModels->map(function (CommitteeAssignmentModel $a) {
            return CommitteeAssignment::reconstruct(
                CommitteeAssignmentId::fromString($a->id),
                CommitteeId::fromString($a->committee_id),
                new MemberId($a->member_id),
                RolePath::fromString($a->role_path),
                new DateTimeImmutable($a->joined_date->format('c')),
                NominationType::fromString($a->nomination_type),
                $a->election_date ? new DateTimeImmutable($a->election_date->format('c')) : null,
                $a->term_end_date ? new DateTimeImmutable($a->term_end_date->format('c')) : null,
                $a->left_date ? new DateTimeImmutable($a->left_date->format('c')) : null,
                $a->appointed_by_user_id ? new TenantUserId($a->appointed_by_user_id) : null,
                $a->notes,
                $a->metadata ?? []
            );
        })->all();

        $geoRef = $model->operational_geo_reference
            ? GeoReference::fromString($model->operational_geo_reference)
            : null;

        $status = CommitteeStatus::fromString($model->status);
        $type = CommitteeType::fromString($model->type);
        $structure = CommitteeStructureRegistry::forType($type);

        return Committee::reconstruct(
            CommitteeId::fromString($model->id),
            TenantId::fromString($model->organisation_id),
            $type,
            CommitteeName::fromString($model->name),
            $model->code,
            $geoRef,
            $status,
            $structure,
            $assignments
        );
    }

    public function saveForTenant(Committee $committee): void
    {
        // Step 1: Explicit find→update OR create for committee record
        $existingCommittee = CommitteeModel::withoutGlobalScopes()
            ->where('id', $committee->getId()->value())
            ->where('organisation_id', $committee->getTenantId()->value())
            ->first();

        $data = $this->serialize($committee);

        if ($existingCommittee) {
            // Detect name change and regenerate slug
            if ($existingCommittee->name !== $committee->getName()->value()) {
                $data['slug'] = CommitteeModel::generateUniqueSlug(
                    $committee->getName()->value(),
                    $committee->getTenantId()->value(),
                    $committee->getId()->value()
                );
            }
            $existingCommittee->update($data);
        } else {
            CommitteeModel::create($data);
        }

        // Step 2: Sync assignments — load current DB assignment IDs
        $existingIds = CommitteeAssignmentModel::withoutGlobalScopes()
            ->where('committee_id', $committee->getId()->value())
            ->pluck('id')
            ->all();

        // Step 3: Explicit find→update OR create per assignment (no updateOrCreate)
        $aggregateIds = [];
        foreach ($committee->getAssignments() as $assignment) {
            $id = $assignment->getId()->value();
            $aggregateIds[] = $id;
            $data = $this->serializeAssignment($assignment, $committee->getTenantId());

            if (in_array($id, $existingIds, true)) {
                CommitteeAssignmentModel::withoutGlobalScopes()
                    ->where('id', $id)
                    ->update($data);
            } else {
                CommitteeAssignmentModel::create($data);
            }
        }

        // Step 4: Soft-delete assignments removed from aggregate
        CommitteeAssignmentModel::withoutGlobalScopes()
            ->where('committee_id', $committee->getId()->value())
            ->whereNotIn('id', $aggregateIds)
            ->update([
                'left_date' => now(),
                'is_active' => false,
            ]);

        // No event dispatch here — caller (application layer) pulls events after save.
        // No transaction here — caller wraps the full use-case unit in a transaction.
    }

    public function findByGeographyForTenant(GeoReference $geoRef, TenantId $tenantId): array
    {
        // Prefix path matching for Phase 1.
        // Phase 2: replace with geo_closure JOIN for better performance.
        $models = CommitteeModel::withoutGlobalScopes()
            ->where('organisation_id', $tenantId->value())
            ->whereNull('deleted_at')
            ->where(function ($q) use ($geoRef) {
                $q->where('operational_geo_reference', $geoRef->value())
                  ->orWhere('operational_geo_reference', 'LIKE', $geoRef->pathPrefix().'%');
            })
            ->get();

        // Load assignments explicitly for each committee
        foreach ($models as $model) {
            $assignmentModels = CommitteeAssignmentModel::query()
                ->where('committee_id', $model->id)
                ->where('organisation_id', $tenantId->value())
                ->where('is_active', true)
                ->get();

            $model->setRelation('assignments', $assignmentModels);
        }

        return $models
            ->map(fn (CommitteeModel $model) => $this->reconstitute($model))
            ->all();
    }

    public function findByTypeForTenant(CommitteeType $type, TenantId $tenantId): array
    {
        $models = CommitteeModel::withoutGlobalScopes()
            ->where('organisation_id', $tenantId->value())
            ->where('type', $type->value())
            ->whereNull('deleted_at')
            ->get();

        // Load assignments explicitly for each committee
        foreach ($models as $model) {
            $assignmentModels = CommitteeAssignmentModel::query()
                ->where('committee_id', $model->id)
                ->where('organisation_id', $tenantId->value())
                ->where('is_active', true)
                ->get();

            $model->setRelation('assignments', $assignmentModels);
        }

        return $models
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
        $models = CommitteeModel::withoutGlobalScopes()
            ->where('organisation_id', $tenantId->value())
            ->whereNull('deleted_at')
            ->get();

        // Load assignments explicitly for each committee
        foreach ($models as $model) {
            $assignmentModels = CommitteeAssignmentModel::query()
                ->where('committee_id', $model->id)
                ->where('organisation_id', $tenantId->value())
                ->where('is_active', true)
                ->get();

            $model->setRelation('assignments', $assignmentModels);
        }

        return $models
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

        $type = CommitteeType::fromString($model->type);
        $structure = CommitteeStructureRegistry::forType($type);

        $assignments = $model->assignments->map(function (CommitteeAssignmentModel $a) {
            return CommitteeAssignment::reconstruct(
                CommitteeAssignmentId::fromString($a->id),
                CommitteeId::fromString($a->committee_id),
                new MemberId($a->member_id),
                RolePath::fromString($a->role_path),
                new DateTimeImmutable($a->joined_date->format('c')),
                NominationType::fromString($a->nomination_type),
                $a->election_date ? new DateTimeImmutable($a->election_date->format('c')) : null,
                $a->term_end_date ? new DateTimeImmutable($a->term_end_date->format('c')) : null,
                $a->left_date ? new DateTimeImmutable($a->left_date->format('c')) : null,
                $a->appointed_by_user_id ? new TenantUserId($a->appointed_by_user_id) : null,
                $a->notes,
                $a->metadata ?? []
            );
        })->all();

        return Committee::reconstruct(
            CommitteeId::fromString($model->id),
            TenantId::fromString($model->organisation_id),
            $type,
            CommitteeName::fromString($model->name),
            $model->code,
            $geoRef,
            $status,
            $structure,
            $assignments
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
            'region_code' => $committee->getRegionCode(),
            'country_code' => $committee->getCountryCode(),
        ];
    }

    private function serializeAssignment(CommitteeAssignment $assignment, TenantId $tenantId): array
    {
        return [
            'id' => $assignment->getId()->value(),
            'committee_id' => $assignment->getCommitteeId()->value(),
            'member_id' => $assignment->getMemberId()->value(),
            'role_path' => $assignment->getRolePath()->value(),
            'nomination_type' => $assignment->getNominationType()->value(),
            'election_date' => $assignment->getElectionDate()?->format('Y-m-d H:i:s'),
            'term_end_date' => $assignment->getTermEndDate()?->format('Y-m-d H:i:s'),
            'joined_date' => $assignment->getJoinedDate()->format('Y-m-d H:i:s'),
            'left_date' => $assignment->getLeftDate()?->format('Y-m-d H:i:s'),
            'is_active' => $assignment->isActive(),
            'appointed_by_user_id' => $assignment->getAppointedByUserId()?->value(),
            'notes' => $assignment->getNotes(),
            'metadata' => $assignment->getMetadata(),
            'organisation_id' => $tenantId->value(),
        ];
    }
}
