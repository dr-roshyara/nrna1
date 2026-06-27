<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Fee\UseCases;

use App\Contexts\Membership\Domain\Fee\Services\PaymentPolicy;
use App\Contexts\Membership\Domain\Fee\ValueObjects\PaymentDetails;
use App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface;
use App\Contexts\Membership\Application\Fee\DTOs\RecordFeePaymentCommand;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxWriter;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

final class RecordFeePayment
{
    public function __construct(
        private FeeRepositoryInterface $feeRepository,
        private PaymentPolicy $paymentPolicy,
        private OutboxWriter $outboxWriter
    ) {}

    public function execute(RecordFeePaymentCommand $command): void
    {
        DB::transaction(function () use ($command) {
            $fee = $this->feeRepository->find($command->feeId, $command->tenantId);

            if (!$fee) {
                throw new \RuntimeException('Fee not found');
            }

            // All preconditions validated by domain service (throws DomainException)
            $this->paymentPolicy->assertCanRecord(
                $fee,
                $command->tenantId,
                $command->transactionReference,
                $this->feeRepository
            );

            $payment = new PaymentDetails(
                method: $command->paymentMethod,
                paidAt: $command->paidAt,
                transactionReference: $command->transactionReference,
                recordedByUserId: $command->recordedByUserId,
            );

            $fee->markAsPaid($payment);

            try {
                $this->feeRepository->save($fee, $command->tenantId);
            } catch (QueryException $e) {
                // Handle unique constraint violation on transaction_reference
                if (str_contains($e->getMessage(), 'unique_txn_ref_per_org') ||
                    str_contains($e->getMessage(), 'transaction_reference') ||
                    str_contains($e->getMessage(), 'Duplicate')) {
                    throw new \DomainException('Payment with this transaction reference already exists');
                }
                throw $e;
            }

            // Store events in Outbox for asynchronous processing (same transaction)
            foreach ($fee->pullEvents() as $event) {
                $this->outboxWriter->store($event, $command->tenantId->value());
            }
        });
    }
}
