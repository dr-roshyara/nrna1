<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Contexts\Shared\Infrastructure\Inbox\RedriveParkedInboxEvents;
use Illuminate\Console\Command;

/**
 * Re-drives due PARKED inbox rows (Blueprint §7 F4 / §7.1 Recovery).
 * Scheduled every minute (routes/console.php), beside outbox:process.
 * Mirrors ProcessOutboxEvents' shape (ER-03). No business logic — delegates.
 */
final class RedriveInboxEvents extends Command
{
    protected $signature = 'inbox:redrive';

    protected $description = 'Re-drive due parked inbox events; dead-letter those past their park deadline';

    public function handle(RedriveParkedInboxEvents $redrive): int
    {
        $this->info('Starting inbox re-drive...');

        try {
            $redrive->handle();
            $this->info('Inbox re-drive complete.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Inbox re-drive failed: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
