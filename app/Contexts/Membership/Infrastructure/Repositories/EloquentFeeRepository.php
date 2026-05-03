<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface;
use App\Contexts\Membership\Domain\Fee\Fee;
use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\Fee\FeeStatus;
use App\Contexts\Membership\Domain\Fee\ValueObjects\PaymentDetails;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Infrastructure\Models\FeeContextModel;
use DateTimeImmutable;

final class EloquentFeeRepository implements FeeRepositoryInterface
{
    public function __construct(private FeeContextModel $model) {}

    public function find(FeeId $id, TenantId $tenantId): ?Fee
    {
        $record = $this->scopedQuery($tenantId)
            ->where('id', $id->value())
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

        $model = $this->scopedQuery($tenantId)
            ->where('id', $feeId)
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

        // Persist payment details if present
        $paymentDetails = $fee->getPaymentDetails();
        if ($paymentDetails !== null) {
            $model->payment_method = $paymentDetails->method;
            $model->payment_reference = $paymentDetails->transactionReference;
            $model->paid_at = $paymentDetails->paidAt;
            $model->recorded_by = $paymentDetails->recordedByUserId;
        }

        $model->save();
    }

    public function findByStatusForTenant(FeeStatus $status, TenantId $tenantId): array
    {
        $records = $this->scopedQuery($tenantId)
            ->where('status', $status->value())
            ->get();

        return $records->map(fn($record) => $this->reconstitute($record))->all();
    }

    public function findForMember(MemberId $memberId, TenantId $tenantId): array
    {
        $records = $this->scopedQuery($tenantId)
            ->where('member_id', $memberId->value())
            ->get();

        return $records->map(fn($record) => $this->reconstitute($record))->all();
    }

    public function findOverdueForTenant(TenantId $tenantId): array
    {
        return $this->findByStatusForTenant(FeeStatus::overdue(), $tenantId);
    }

    public function findByTransactionReference(string $ref, TenantId $tenantId): ?Fee
    {
        $record = $this->scopedQuery($tenantId)
            ->where('transaction_reference', $ref)
            ->first();

        return $record ? $this->reconstitute($record) : null;
    }

    private function reconstitute(FeeContextModel $record): Fee
    {
        $paymentDetails = null;
        if ($record->payment_method !== null && $record->paid_at !== null) {
            $paidAtString = is_string($record->paid_at)
                ? $record->paid_at
                : $record->paid_at->format('Y-m-d H:i:s');

            $paymentDetails = new PaymentDetails(
                method: $record->payment_method,
                paidAt: new DateTimeImmutable($paidAtString),
                transactionReference: $record->transaction_reference,
                recordedByUserId: $record->recorded_by,
            );
        }

        $dueDateString = is_string($record->due_date)
            ? $record->due_date
            : $record->due_date->format('Y-m-d H:i:s');

        return Fee::reconstitute(
            FeeId::fromString($record->id),
            MemberId::fromString($record->member_id),
            MembershipTypeId::fromString($record->membership_type_id),
            FeeStatus::fromString($record->status),
            TenantId::fromOrganisationId($record->organisation_id),
            (string) $record->amount,
            new DateTimeImmutable($dueDateString),
            $paymentDetails,
        );
    }

    private function scopedQuery(TenantId $tenantId)
    {
        return $this->model
            ->withoutGlobalScopes()
            ->where('organisation_id', $tenantId->value());
    }
}
