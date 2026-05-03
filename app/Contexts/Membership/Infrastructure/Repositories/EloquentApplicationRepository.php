<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Domain\Repositories\ApplicationRepositoryInterface;
use App\Contexts\Membership\Domain\Application\Application;
use App\Contexts\Membership\Domain\Application\ApplicationId;
use App\Contexts\Membership\Domain\Application\ApplicationStatus;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Infrastructure\Models\ApplicationContextModel;

final class EloquentApplicationRepository implements ApplicationRepositoryInterface
{
    public function __construct(private ApplicationContextModel $model) {}

    public function find(ApplicationId $id, TenantId $tenantId): ?Application
    {
        $record = $this->model
            ->withoutGlobalScopes()
            ->where('id', $id->value())
            ->where('organisation_id', $tenantId->value())
            ->first();

        if (!$record) {
            return null;
        }

        return $this->reconstitute($record);
    }

    public function save(Application $application, TenantId $tenantId): void
    {
        $appId = $application->getId()->value();
        $orgId = $tenantId->value();

        \Log::info('ApplicationRepository.save() - starting', [
            'id' => $appId,
            'org_id' => $orgId,
            'status' => $application->getStatus()->value(),
        ]);

        $model = $this->model
            ->withoutGlobalScopes()
            ->where('id', $appId)
            ->where('organisation_id', $orgId)
            ->firstOrCreate(
                [
                    'id' => $appId,
                    'organisation_id' => $orgId,
                ]
            );

        \Log::info('ApplicationRepository.save() - found or created model', [
            'found' => !$model->wasRecentlyCreated,
            'current_status' => $model->status,
        ]);

        $model->user_id = $application->getUserId();
        $model->membership_type_id = $application->getMembershipTypeId()->value();
        $model->status = $application->getStatus()->value();
        $model->rejection_reason = $application->getRejectionReason();
        $model->application_data = $application->getApplicationData();

        $saved = $model->save();

        \Log::info('ApplicationRepository.save() - after save', [
            'saved' => $saved,
            'status_after' => $model->status,
            'dirty_attributes' => $model->getDirty(),
        ]);

        // Verify from database
        $verify = $this->model->withoutGlobalScopes()->find($appId);
        \Log::info('ApplicationRepository.save() - verified from DB', [
            'db_status' => $verify?->status,
        ]);
    }

    public function findByStatusForTenant(ApplicationStatus $status, TenantId $tenantId): array
    {
        $records = $this->model
            ->withoutGlobalScopes()
            ->where('organisation_id', $tenantId->value())
            ->where('status', $status->value())
            ->get();

        return $records->map(fn($record) => $this->reconstitute($record))->all();
    }

    public function findPendingForTenant(TenantId $tenantId): array
    {
        return $this->findByStatusForTenant(ApplicationStatus::submitted(), $tenantId);
    }

    private function reconstitute(ApplicationContextModel $record): Application
    {
        return Application::reconstitute(
            ApplicationId::fromString($record->id),
            ApplicationStatus::fromString($record->status),
            TenantId::fromOrganisationId($record->organisation_id),
            $record->user_id,
            MembershipTypeId::fromString($record->membership_type_id),
            $record->application_data,
            $record->rejection_reason
        );
    }
}
