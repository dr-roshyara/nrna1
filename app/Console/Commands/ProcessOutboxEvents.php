<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Contexts\Shared\Infrastructure\Outbox\OutboxEventProcessor;
use Illuminate\Console\Command;

final class ProcessOutboxEvents extends Command
{
    protected $signature = 'outbox:process';

    protected $description = 'Process pending outbox events and dispatch to listeners';

    public function handle(OutboxEventProcessor $processor): int
    {
        $this->info('Starting outbox event processing...');

        try {
            $processor->handle();
            $this->info('Outbox events processed successfully.');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to process outbox events: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
