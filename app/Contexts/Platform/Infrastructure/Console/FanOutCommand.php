<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Console;

use App\Contexts\Platform\Application\FanOutToOrganisations;
use App\Contexts\Platform\Infrastructure\Queue\FanOutJobDispatch;
use App\Contexts\Platform\Infrastructure\Queue\OrganisationDispatchRefused;
use App\Contexts\Platform\Infrastructure\Queue\OrganisationJobDispatcher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * The E-3 fan-out (PBDIGIT-345 task 8): `platform:fan-out <operation>` queues one organisation job per routable
 * organisation. The operation must be listed in config('organisation_routing.fan_out') (operation => job class).
 * It reads only the central registry; it never opens an organisation database. Schedule one line per periodic task:
 *   Schedule::command('platform:fan-out <operation>')->daily()->withoutOverlapping();
 * Exit 0: every routable organisation got its job (skips are normal); exit 1: unknown operation or a dispatch failed.
 */
final class FanOutCommand extends Command
{
    protected $signature = 'platform:fan-out {operation : an operation listed in organisation_routing.fan_out}';

    protected $description = 'E-3: queue one organisation job per operational organisation.';

    public function handle(FanOutToOrganisations $fanOut, OrganisationJobDispatcher $dispatcher): int
    {
        $operation = (string) $this->argument('operation');
        // indexed directly: operation names contain dots, which config()'s dot notation would read as nesting
        $class = ((array) config('organisation_routing.fan_out', []))[$operation] ?? null;
        if (!is_string($class)) {
            $this->error("Unknown fan-out operation: {$operation}.");

            return self::FAILURE;
        }
        try {
            $work = new FanOutJobDispatch($class, $dispatcher);
        } catch (OrganisationDispatchRefused $e) {
            $this->error("Fan-out operation {$operation} refused: {$e->getMessage()}");

            return self::FAILURE;
        }

        $report = $fanOut->run($work);

        foreach ($report->skipped as $id => $reason) {
            $this->line("{$id} skipped: {$reason}");
        }
        foreach ($report->failed as $id => $errorClass) {
            $this->error("{$id} failed: {$errorClass}");
        }
        $summary = sprintf('dispatched: %d, skipped: %d, failed: %d', count($report->dispatched), count($report->skipped), count($report->failed));
        $this->info("{$operation}: {$summary}");
        Log::info('platform.fan_out', ['operation' => $operation, 'dispatched' => count($report->dispatched),
            'skipped' => array_count_values($report->skipped), 'failed' => count($report->failed)]);

        return $report->hasFailures() ? self::FAILURE : self::SUCCESS;
    }
}
