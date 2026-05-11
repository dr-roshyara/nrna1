<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contexts\Membership\Application\CommitteeStructure\ActivateCommitteeStructure;

final class TestRunActivateStructureCommand extends Command
{
    protected $signature = 'test-run:activate-structure {tenantId} {structureId}';

    protected $description = 'Test command: activate a structure (used by stress test harness)';

    public function handle(): int
    {
        try {
            app(ActivateCommitteeStructure::class)->execute([
                'tenantId' => $this->argument('tenantId'),
                'structureId' => $this->argument('structureId'),
            ]);

            return self::SUCCESS;
        } catch (\Exception $e) {
            // Silently catch exceptions (failed activations are expected in stress test)
            // This allows other concurrent attempts to proceed
            return self::FAILURE;
        }
    }
}
