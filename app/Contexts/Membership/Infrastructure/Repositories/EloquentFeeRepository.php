<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface;
use App\Contexts\Membership\Domain\Fee\Fee;
use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\Fee\FeeStatus;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Infrastructure\Models\FeeContextModel;
use DateTimeImmutable;

final class EloquentFeeRepository implements FeeRepositoryInterface
{
    public function __construct(private FeeContextModel $model) {}

    public function find(FeeId $id, TenantId $tenantId): ?Fee
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

    public function save(Fee $fee, TenantId $tenantId): void
    {
        $amount = $fee->getAmount();
        $feeId = $fee->getId()->value();
        $orgId = $tenantId->value();

        $model = $this->model
            ->withoutGlobalScopes()
            ->where('id', $feeId)
            ->where('organisation_id', $orgId)
            ->first();

        if (!$model) {
            $model = new $this->model();
            $model->id = $feeId;
            $model->organisation_id = $orgId;
        }

        $model->member_id = $fee->getMemberId()->value();
        $model->membership_type_id = $fee->getMembershipTypeId()->value();
        $model->amount = $amount;
        $model->fee_amount_at_time = $amount;
        $model->status = $fee->getStatus()->value();
        $model->due_date = $fee->getDueDate();

        $model->save();
    }

    public function findByStatusForTenant(FeeStatus $status, TenantId $tenantId): array
    {
        $records = $this->model
            ->withoutGlobalScopes()
            ->where('organisation_id', $tenantId->value())
            ->where('status', $status->value())
            ->get();

        return $records->map(fn($record) => $this->reconstitute($record))->all();
    }

    public function findForMember(MemberId $memberId, TenantId $tenantId): array
    {
        $records = $this->model
            ->withoutGlobalScopes()
            ->where('organisation_id', $tenantId->value())
            ->where('member_id', $memberId->value())
            ->get();

        return $records->map(fn($record) => $this->reconstitute($record))->all();
    }

    public function findOverdueForTenant(TenantId $tenantId): array
    {
        return $this->findByStatusForTenant(FeeStatus::overdue(), $tenantId);
    }

    private function reconstitute(FeeContextModel $record): Fee
    {
        return Fee::reconstitute(
            FeeId::fromString($record->id),
            MemberId::fromString($record->member_id),
            MembershipTypeId::fromString($record->membership_type_id),
            FeeStatus::fromString($record->status),
            TenantId::fromOrganisationId($record->organisation_id),
            (string) $record->amount,
            new DateTimeImmutable($record->due_date)
        );
    }
}
