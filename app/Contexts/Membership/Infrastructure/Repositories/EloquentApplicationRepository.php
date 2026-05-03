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
            ->where('id', $id->toString())
            ->where('organisation_id', $tenantId->toString())
            ->first();

        if (!$record) {
            return null;
        }

        return $this->reconstitute($record);
    }

    public function save(Application $application, TenantId $tenantId): void
    {
        $this->model->withoutGlobalScopes()->updateOrCreate(
            [
                'id' => $application->getId()->toString(),
                'organisation_id' => $tenantId->toString(),
            ],
            [
                'member_id' => $application->getMemberId()->toString(),
                'membership_type_id' => $application->getMembershipTypeId()->toString(),
                'status' => $application->getStatus()->value(),
            ]
        );
    }

    public function findByStatusForTenant(ApplicationStatus $status, TenantId $tenantId): array
    {
        $records = $this->model
            ->withoutGlobalScopes()
            ->where('organisation_id', $tenantId->toString())
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
            TenantId::fromString($record->organisation_id),
            MemberId::fromString($record->member_id),
            MembershipTypeId::fromString($record->membership_type_id)
        );
    }
}
