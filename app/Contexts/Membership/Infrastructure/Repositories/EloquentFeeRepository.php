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
            ->where('id', $id->toString())
            ->where('organisation_id', $tenantId->toString())
            ->first();

        if (!$record) {
            return null;
        }

        return $this->reconstitute($record);
    }

    public function save(Fee $fee, TenantId $tenantId): void
    {
        $this->model->withoutGlobalScopes()->updateOrCreate(
            [
                'id' => $fee->getId()->toString(),
                'organisation_id' => $tenantId->toString(),
            ],
            [
                'member_id' => $fee->getMemberId()->toString(),
                'amount' => $fee->getAmount(),
                'status' => $fee->getStatus()->value(),
                'due_date' => $fee->getDueDate(),
            ]
        );
    }

    public function findByStatusForTenant(FeeStatus $status, TenantId $tenantId): array
    {
        $records = $this->model
            ->withoutGlobalScopes()
            ->where('organisation_id', $tenantId->toString())
            ->where('status', $status->value())
            ->get();

        return $records->map(fn($record) => $this->reconstitute($record))->all();
    }

    public function findForMember(MemberId $memberId, TenantId $tenantId): array
    {
        $records = $this->model
            ->withoutGlobalScopes()
            ->where('organisation_id', $tenantId->toString())
            ->where('member_id', $memberId->toString())
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
            FeeStatus::fromString($record->status),
            TenantId::fromString($record->organisation_id),
            (string) $record->amount,
            new DateTimeImmutable($record->due_date)
        );
    }
}
