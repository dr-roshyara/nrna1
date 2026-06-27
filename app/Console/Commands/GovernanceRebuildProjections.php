<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Contexts\Governance\Application\Ports\CommitteeProjectionRebuildRepository;
use App\Contexts\Governance\Application\Ports\GovernanceClock;
use App\Contexts\Governance\Application\Services\GovernanceProjectionRebuilder;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Console\Command;

final class GovernanceRebuildProjections extends Command
{
    protected $signature = 'governance:rebuild-projections
        {--tenant= : Rebuild projections for a specific tenant ID}
        {--force : Skip lock check}
        {--dry-run : Preview which committees would be rebuilt without making changes}';

    protected $description = 'Rebuild all governance projections from domain events';

    public function __construct(
        private readonly GovernanceProjectionRebuilder $rebuilder,
        private readonly CommitteeProjectionRebuildRepository $rebuildRepository,
        private readonly GovernanceClock $clock,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $tenantId = $this->option('tenant')
            ? TenantId::fromString($this->option('tenant'))
            : null;

        $scope = $tenantId ? "tenant {$tenantId->value()}" : 'all tenants';

        if ($this->option('dry-run')) {
            $count = $this->rebuildRepository->countCommittees($tenantId);
            $this->info("Would rebuild {$count} committee projections for {$scope} (dry-run)");
            return Command::SUCCESS;
        }

        $this->info("Rebuilding governance projections for {$scope}...");
        $result = $this->rebuilder->rebuildAll($tenantId, $this->output);

        $this->line('');
        $this->info("Rebuilt: {$result->rebuilt}");
        $this->warn("Failed: {$result->failed}");
        $this->line("Skipped: {$result->skipped}");
        $this->line("Status: {$result->status}");

        return $result->failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
