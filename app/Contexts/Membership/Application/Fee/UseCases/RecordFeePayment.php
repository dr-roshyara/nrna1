<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Fee\UseCases;

use App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface;
use App\Contexts\Membership\Application\Fee\DTOs\RecordFeePaymentCommand;
use App\Shared\Infrastructure\Events\LaravelEventBus;
use Illuminate\Support\Facades\DB;

final class RecordFeePayment
{
    public function __construct(
        private FeeRepositoryInterface $feeRepository,
        private LaravelEventBus $eventBus
    ) {}

    public function execute(RecordFeePaymentCommand $command): void
    {
        DB::transaction(function () use ($command) {
            $fee = $this->feeRepository->find($command->getFeeId(), $command->getTenantId());

            if (!$fee) {
                throw new \Exception("Fee not found: {$command->getFeeId()->toString()}");
            }

            $fee->markAsPaid();

            $this->feeRepository->save($fee, $command->getTenantId());

            $events = $fee->pullEvents();
            foreach ($events as $event) {
                $this->eventBus->dispatch($event);
            }
        });
    }
}
