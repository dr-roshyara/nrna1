<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Fee\UseCases;

use App\Contexts\Membership\Application\Fee\DTOs\WaiveFeeCommand;
use App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxWriter;
use Illuminate\Support\Facades\DB;

final class WaiveFee
{
    public function __construct(
        private FeeRepositoryInterface $feeRepository,
        private OutboxWriter $outboxWriter
    ) {}

    public function execute(WaiveFeeCommand $command): void
    {
        DB::transaction(function () use ($command) {
            $fee = $this->feeRepository->find($command->feeId, $command->tenantId);

            if (!$fee) {
                throw new \RuntimeException('Fee not found');
            }

            $fee->waive($command->reason);
            $this->feeRepository->save($fee, $command->tenantId);

            // Store events in Outbox for asynchronous processing (same transaction)
            foreach ($fee->pullEvents() as $event) {
                $this->outboxWriter->store($event, $command->tenantId->value());
            }
        });
    }
}
