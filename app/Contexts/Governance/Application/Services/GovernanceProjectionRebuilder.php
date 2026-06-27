<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Services;

use App\Contexts\Governance\Application\DTOs\RebuildResult;
use App\Contexts\Governance\Application\Ports\CommitteeGovernanceProjectorInterface;
use App\Contexts\Governance\Application\Ports\CommitteeProjectionRebuildRepository;
use App\Contexts\Governance\Application\Ports\GovernanceClock;
use App\Contexts\Governance\Application\Ports\LockInterface;
use App\Contexts\Governance\Application\Ports\RebuildRunRepository;
use App\Contexts\Governance\Infrastructure\Projections\CommitteeGovernanceProjectionModel;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;
use Symfony\Component\Console\Output\OutputInterface;

final class GovernanceProjectionRebuilder
{
    private const LOCK_TTL_SECONDS = 600;
    private const LOCK_PREFIX = 'projection_rebuild_lock';
    private const SCHEMA_VERSION = 1;

    public function __construct(
        private readonly CommitteeGovernanceProjectorInterface $projector,
        private readonly CommitteeProjectionRebuildRepository $rebuildRepository,
        private readonly RebuildRunRepository $rebuildRunRepository,
        private readonly LockInterface $lock,
        private readonly GovernanceClock $clock,
    ) {}

    /**
     * Rebuild all governance projections.
     *
     * Projection fields are eventually consistent — NOT authoritative domain truth. See docs/ARCHITECTURE.md
     * RULE-H6-05/H6-07: Tenant-scoped lock prevents concurrent rebuilds.
     * RULE-H6-08: Failures isolate per aggregate; one failure doesn't abort the rebuild.
     * RULE-H6-09: Single UUIDv7 generation per rebuild session.
     */
    public function rebuildAll(
        ?TenantId $tenantId = null,
        ?OutputInterface $output = null,
    ): RebuildResult {
        $lockKey = $tenantId !== null
            ? self::LOCK_PREFIX . ':' . $tenantId->value()
            : self::LOCK_PREFIX . ':global';

        if (!$this->lock->acquire($lockKey, self::LOCK_TTL_SECONDS)) {
            $output?->writeln('<error>Another rebuild is in progress. Skipping.</error>');
            return new RebuildResult(0, 0, 0, 'skipped: lock held');
        }

        $generation = $this->generateGeneration();
        $now = $this->clock->now();
        $total = $this->rebuildRepository->countCommittees($tenantId);
        $rebuildRun = $this->startRebuildRun($tenantId, $generation, $now, $total);
        $rebuilt = 0;
        $failed = 0;

        try {
            foreach ($this->rebuildRepository->streamCommitteeIds($tenantId) as $committee) {
                try {
                    $this->projector->rebuild(
                        CommitteeId::fromString($committee->id),
                        $now,
                        'rebuild-all',
                        $now,
                        $generation,
                    );
                    $rebuilt++;
                    $output?->writeln("  <info>OK</info> {$committee->id} {$committee->name}");
                } catch (\Throwable $e) {
                    $failed++;
                    $output?->writeln("  <error>FAIL</error> {$committee->id}: {$e->getMessage()}");
                }
            }

            $this->completeRebuildRun($rebuildRun, $rebuilt, $failed, $now);

            return new RebuildResult($rebuilt, $failed, 0, 'completed');
        } catch (\Throwable $e) {
            $this->failRebuildRun($rebuildRun, $e->getMessage());
            throw $e;
        } finally {
            $this->lock->release($lockKey);
        }
    }

    /**
     * Rollback projections to a previous generation and trigger rebuild.
     *
     * RULE-H6-10: Rollback deletes current generation projections, then rebuilds.
     * During rebuild, reads may observe mixed generations.
     */
    public function rollbackToGeneration(TenantId $tenantId, string $previousGeneration): RebuildResult
    {
        CommitteeGovernanceProjectionModel::whereHas('committee', function ($q) use ($tenantId) {
            $q->where('organisation_id', $tenantId->value());
        })->delete();

        return $this->rebuildAll($tenantId);
    }

    private function generateGeneration(): string
    {
        // UUIDv7-like generation: timestamp + random bytes
        $now = (int) (microtime(true) * 1000);
        $rand = bin2hex(random_bytes(10));

        return sprintf('%08x-%04x-%s', $now >> 16, $now & 0xFFFF, $rand);
    }

    private function startRebuildRun(?TenantId $tenantId, string $generation, DateTimeImmutable $now, int $total): string
    {
        $this->rebuildRunRepository->start($generation, $tenantId?->value(), $now, $total);

        return $generation;
    }

    private function completeRebuildRun(string $generation, int $rebuilt, int $failed, DateTimeImmutable $now): void
    {
        $this->rebuildRunRepository->complete($generation, $rebuilt, $failed, $now);
    }

    private function failRebuildRun(string $generation, string $error): void
    {
        $this->rebuildRunRepository->fail($generation, $error);
    }
}
